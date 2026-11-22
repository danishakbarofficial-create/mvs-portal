<?php
// MVS Project Pulse - Migrate / Recover Data from SQLite to Hostinger MySQL
header('Content-Type: text/html; charset=utf-8');

$sqliteFile = __DIR__ . '/data/mvs_sales.sqlite';
$mysqlHost = 'localhost';
$mysqlDb = 'u370632335_mvs_portal';
$mysqlUser = 'u370632335_portal_user';
$mysqlPass = 'Mvs@123$';

$logs = [];
$foundPipeline = [];
$foundProjects = [];

if (!file_exists($sqliteFile)) {
    $logs[] = "SQLite file not found at: {$sqliteFile}";
} else {
    try {
        $sqlite = new PDO('sqlite:' . $sqliteFile, null, null, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
        $sqlite->exec('PRAGMA journal_mode = WAL;');
        $sqlite->exec('PRAGMA wal_checkpoint(FULL);');

        // Check v4_pipeline
        try {
            $stmt = $sqlite->query("SELECT * FROM v4_pipeline");
            $foundPipeline = $stmt->fetchAll();
            $logs[] = "Found <strong>" . count($foundPipeline) . "</strong> record(s) in SQLite <code>v4_pipeline</code>.";
        } catch (Exception $e) {
            $logs[] = "v4_pipeline check: " . $e->getMessage();
        }

        // Check sales_opportunities (legacy)
        try {
            $stmt = $sqlite->query("SELECT * FROM sales_opportunities");
            $foundOpp = $stmt->fetchAll();
            if (count($foundOpp) > 0) {
                $logs[] = "Found <strong>" . count($foundOpp) . "</strong> record(s) in SQLite legacy <code>sales_opportunities</code>.";
            }
        } catch (Exception $e) {}

        // Check v4_projects
        try {
            $stmt = $sqlite->query("SELECT * FROM v4_projects");
            $foundProjects = $stmt->fetchAll();
            $logs[] = "Found <strong>" . count($foundProjects) . "</strong> record(s) in SQLite <code>v4_projects</code>.";
        } catch (Exception $e) {
            $logs[] = "v4_projects check: " . $e->getMessage();
        }

        // Connect to MySQL
        $mysql = new PDO("mysql:host={$mysqlHost};dbname={$mysqlDb};charset=utf8mb4", $mysqlUser, $mysqlPass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);

        // If User Clicked "Import into MySQL"
        if ($_SERVER['REQUEST_METHOD'] === 'POST' || isset($_GET['do_import'])) {
            $importedPipelines = 0;
            if (!empty($foundPipeline)) {
                $insPipe = $mysql->prepare("
                    INSERT INTO v4_pipeline (
                        row_no, sales_person, project_name, client, main_contractor,
                        consultant, scope_of_work, date_received, submission_date, quotation_no,
                        quotation_status, total_sar, expected_closing, quotation_valid_until,
                        contact_person, contact_details, decision_status, missing_info, next_step,
                        follow_up_date, notes, additional_notes
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ");

                foreach ($foundPipeline as $p) {
                    // Check if already in MySQL
                    $chk = $mysql->prepare("SELECT id FROM v4_pipeline WHERE project_name = ? AND quotation_no = ?");
                    $chk->execute([$p['project_name'], $p['quotation_no']]);
                    if (!$chk->fetch()) {
                        $insPipe->execute([
                            $p['row_no'] ?? null,
                            $p['sales_person'] ?? '',
                            $p['project_name'] ?? '',
                            $p['client'] ?? '',
                            $p['main_contractor'] ?? '',
                            $p['consultant'] ?? '',
                            $p['scope_of_work'] ?? '',
                            $p['date_received'] ?? null,
                            $p['submission_date'] ?? null,
                            $p['quotation_no'] ?? '',
                            $p['quotation_status'] ?? 'Open',
                            $p['total_sar'] ?? 0,
                            $p['expected_closing'] ?? null,
                            $p['quotation_valid_until'] ?? null,
                            $p['contact_person'] ?? '',
                            $p['contact_details'] ?? '',
                            $p['decision_status'] ?? 'Information Gathering',
                            $p['missing_info'] ?? 'None - Complete',
                            $p['next_step'] ?? '',
                            $p['follow_up_date'] ?? null,
                            $p['notes'] ?? '',
                            $p['additional_notes'] ?? ''
                        ]);
                        $importedPipelines++;
                    }
                }
                $logs[] = "<strong>Imported {$importedPipelines} pipeline record(s) into MySQL successfully!</strong>";
            }

            $importedProjects = 0;
            if (!empty($foundProjects)) {
                $insProj = $mysql->prepare("
                    INSERT INTO v4_projects (
                        row_no, project_manager, project_name, customer, po_status,
                        po_no, po_date, total_po_value, expected_closing, months_to_close,
                        milestone_no, milestone_desc, milestone_value, milestone_status,
                        technical_report, invoice_status, invoice_no, invoice_date,
                        invoice_amount, collection_date, collected_amount, collection_status,
                        pending_collection, to_be_invoiced, notes, additional_notes
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ");

                foreach ($foundProjects as $pr) {
                    $chk = $mysql->prepare("SELECT id FROM v4_projects WHERE project_name = ? AND milestone_no = ?");
                    $chk->execute([$pr['project_name'], $pr['milestone_no']]);
                    if (!$chk->fetch()) {
                        $insProj->execute([
                            $pr['row_no'] ?? null,
                            $pr['project_manager'] ?? '',
                            $pr['project_name'] ?? '',
                            $pr['customer'] ?? '',
                            $pr['po_status'] ?? 'In Progress',
                            $pr['po_no'] ?? '',
                            $pr['po_date'] ?? null,
                            $pr['total_po_value'] ?? 0,
                            $pr['expected_closing'] ?? null,
                            $pr['months_to_close'] ?? null,
                            $pr['milestone_no'] ?? '',
                            $pr['milestone_desc'] ?? '',
                            $pr['milestone_value'] ?? 0,
                            $pr['milestone_status'] ?? 'Pending',
                            $pr['technical_report'] ?? 'In Progress',
                            $pr['invoice_status'] ?? 'Not Invoiced',
                            $pr['invoice_no'] ?? '',
                            $pr['invoice_date'] ?? null,
                            $pr['invoice_amount'] ?? 0,
                            $pr['collection_date'] ?? null,
                            $pr['collected_amount'] ?? 0,
                            $pr['collection_status'] ?? 'Pending',
                            $pr['pending_collection'] ?? 0,
                            $pr['to_be_invoiced'] ?? 0,
                            $pr['notes'] ?? '',
                            $pr['additional_notes'] ?? ''
                        ]);
                        $importedProjects++;
                    }
                }
                $logs[] = "<strong>Imported {$importedProjects} project milestone(s) into MySQL successfully!</strong>";
            }
        }
    } catch (Exception $e) {
        $logs[] = "Error: " . htmlspecialchars($e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>MVS - SQLite to MySQL Data Importer &amp; Recovery</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Inter', sans-serif; background: #F8FAFC; color: #0F172A; padding: 40px 20px; }
    .card { max-width: 850px; margin: 0 auto; background: #fff; border-radius: 12px; border: 1px solid #E2E8F0; padding: 32px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    h1 { font-size: 1.5rem; color: #0F172A; margin-bottom: 8px; }
    p { color: #64748B; font-size: 0.95rem; margin-bottom: 24px; }
    .btn { display: inline-flex; align-items: center; gap: 8px; font-weight: 600; padding: 12px 24px; border-radius: 8px; cursor: pointer; text-decoration: none; border: none; font-size: 0.95rem; }
    .btn-primary { background: #10B981; color: #fff; }
    .btn-primary:hover { background: #059669; }
    .btn-secondary { background: #E2E8F0; color: #334155; margin-left: 10px; }
    .log-box { background: #0F172A; color: #F8FAFC; padding: 16px 20px; border-radius: 8px; font-family: monospace; font-size: 0.88rem; line-height: 1.6; margin-top: 20px; }
    .log-box strong { color: #38BDF8; }
    .log-box code { color: #4ADE80; }
    table { width: 100%; border-collapse: collapse; margin-top: 16px; font-size: 0.85rem; }
    th, td { padding: 8px 12px; border: 1px solid #E2E8F0; text-align: left; }
    th { background: #F1F5F9; font-weight: 700; }
  </style>
</head>
<body>
  <div class="card">
    <h1>SQLite to MySQL Data Recovery Tool</h1>
    <p>Scans the server's <code>data/mvs_sales.sqlite</code> file and transfers all entered pipeline inquiries and project records directly into the live MySQL database.</p>

    <div class="log-box">
      <?php foreach ($logs as $l): ?>
        <div>&gt; <?= $l ?></div>
      <?php endforeach; ?>
    </div>

    <?php if (!empty($foundPipeline)): ?>
      <h3 style="margin-top: 24px;">Pipeline Records Found in SQLite (<?= count($foundPipeline) ?>):</h3>
      <table>
        <thead>
          <tr>
            <th>Sales Person</th>
            <th>Project Name</th>
            <th>Client</th>
            <th>Quotation No.</th>
            <th>Total SAR</th>
            <th>Decision Status</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($foundPipeline as $row): ?>
            <tr>
              <td><strong><?= htmlspecialchars($row['sales_person'] ?? '') ?></strong></td>
              <td><?= htmlspecialchars($row['project_name'] ?? '') ?></td>
              <td><?= htmlspecialchars($row['client'] ?? '') ?></td>
              <td><code><?= htmlspecialchars($row['quotation_no'] ?? '') ?></code></td>
              <td><?= number_format($row['total_sar'] ?? 0, 2) ?> SAR</td>
              <td><?= htmlspecialchars($row['decision_status'] ?? '') ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>

    <form method="POST" style="margin-top: 24px;">
      <button type="submit" class="btn btn-primary">
        &#8635; Import Found Records into Live MySQL Database
      </button>
      <a href="sales_portal.php" class="btn btn-secondary">&larr; Return to Sales Portal</a>
    </form>
  </div>
</body>
</html>

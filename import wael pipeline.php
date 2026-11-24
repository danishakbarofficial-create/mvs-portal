<?php
// MVS Project Pulse - Safe Import for Wael (Sales Pipeline Register)
require_once __DIR__ . '/db.php';
header('Content-Type: text/html; charset=utf-8');

$pdo = Database::getConnection();
$message = '';
$status = '';
$inserted = 0;

$rows = [
    [
        'excel_no' => 2,
        'sales_person' => 'Wael',
        'project_name' => 'Qiddiya Development – Temporary Hoarding & Safety Barriers',
        'client' => 'Qiddiya',
        'main_contractor' => 'Alpha Graphics',
        'consultant' => '',
        'scope_of_work' => 'Hoarding & Safety Barriers',
        'date_received' => null,
        'submission_date' => '2026-02-04',
        'quotation_no' => '',
        'quotation_status' => 'Closed',
        'total_sar' => 2940402.80,
        'expected_closing' => null,
        'quotation_valid_until' => null,
        'contact_person' => '',
        'contact_details' => '',
        'decision_status' => 'Lost',
        'missing_info' => 'None - Complete',
        'next_step' => '',
        'follow_up_date' => null,
        'notes' => 'High price',
        'additional_notes' => ''
    ],
    [
        'excel_no' => 3,
        'sales_person' => 'Wael',
        'project_name' => 'Jetting Fiber Blowing Machine V3-1000N - ',
        'client' => '',
        'main_contractor' => 'Al Amad AL Baeed Co',
        'consultant' => '',
        'scope_of_work' => 'Jetting Fiber Blowing Machine V3-1000N - ',
        'date_received' => null,
        'submission_date' => '2026-02-08',
        'quotation_no' => '',
        'quotation_status' => 'Closed',
        'total_sar' => 123998.75,
        'expected_closing' => null,
        'quotation_valid_until' => null,
        'contact_person' => '',
        'contact_details' => '',
        'decision_status' => 'Lost',
        'missing_info' => 'None - Complete',
        'next_step' => '',
        'follow_up_date' => null,
        'notes' => 'High price',
        'additional_notes' => ''
    ],
    [
        'excel_no' => 4,
        'sales_person' => 'Wael',
        'project_name' => 'Accessories for V3-1800N',
        'client' => '',
        'main_contractor' => 'Al Amad AL Baeed Co',
        'consultant' => '',
        'scope_of_work' => 'Accessories for V3-1800N',
        'date_received' => null,
        'submission_date' => '2026-02-08',
        'quotation_no' => '',
        'quotation_status' => 'Closed',
        'total_sar' => 25898.00,
        'expected_closing' => null,
        'quotation_valid_until' => null,
        'contact_person' => '',
        'contact_details' => '',
        'decision_status' => 'Lost',
        'missing_info' => 'None - Complete',
        'next_step' => '',
        'follow_up_date' => null,
        'notes' => 'High price',
        'additional_notes' => ''
    ],
    [
        'excel_no' => 5,
        'sales_person' => 'Wael',
        'project_name' => 'Improvement of Waad Al Shamal Industrial City Fence & Gates | MODON',
        'client' => 'MODON',
        'main_contractor' => 'Qwaed',
        'consultant' => '',
        'scope_of_work' => 'Fence & Gates',
        'date_received' => null,
        'submission_date' => '2026-02-23',
        'quotation_no' => '',
        'quotation_status' => 'Closed',
        'total_sar' => 11288572.50,
        'expected_closing' => null,
        'quotation_valid_until' => null,
        'contact_person' => '',
        'contact_details' => '',
        'decision_status' => 'Lost',
        'missing_info' => 'None - Complete',
        'next_step' => '',
        'follow_up_date' => null,
        'notes' => 'High price',
        'additional_notes' => ''
    ],
    [
        'excel_no' => 6,
        'sales_person' => 'Wael',
        'project_name' => 'Routers(45) and Modem(2) -- government entity',
        'client' => '',
        'main_contractor' => 'Source Gate',
        'consultant' => '',
        'scope_of_work' => 'Supply and Installation of Routers and Modems',
        'date_received' => null,
        'submission_date' => '2026-02-23',
        'quotation_no' => '',
        'quotation_status' => 'Closed',
        'total_sar' => 50341.25,
        'expected_closing' => null,
        'quotation_valid_until' => null,
        'contact_person' => '',
        'contact_details' => '',
        'decision_status' => 'Lost',
        'missing_info' => 'None - Complete',
        'next_step' => '',
        'follow_up_date' => null,
        'notes' => 'High price',
        'additional_notes' => ''
    ],
    [
        'excel_no' => 7,
        'sales_person' => 'Wael',
        'project_name' => 'Supply and Installation of an Integrated Data Network in Student Buildings in Huraymila',
        'client' => 'Huraymila',
        'main_contractor' => 'Source Gate',
        'consultant' => '',
        'scope_of_work' => 'Supply and Installation of an Integrated Data Network',
        'date_received' => null,
        'submission_date' => '2026-03-15',
        'quotation_no' => '',
        'quotation_status' => 'Closed',
        'total_sar' => 882803.25,
        'expected_closing' => null,
        'quotation_valid_until' => null,
        'contact_person' => '',
        'contact_details' => '',
        'decision_status' => 'Lost',
        'missing_info' => 'None - Complete',
        'next_step' => '',
        'follow_up_date' => null,
        'notes' => 'High price',
        'additional_notes' => ''
    ],
    [
        'excel_no' => 8,
        'sales_person' => 'Wael',
        'project_name' => 'Securing and Supplying Computer Network Materials and Supplies',
        'client' => '',
        'main_contractor' => 'Source Gate',
        'consultant' => '',
        'scope_of_work' => 'Securing and Supplying Computer Network Materials and Supplies',
        'date_received' => null,
        'submission_date' => '2026-02-15',
        'quotation_no' => '',
        'quotation_status' => 'Closed',
        'total_sar' => 190159.40,
        'expected_closing' => null,
        'quotation_valid_until' => null,
        'contact_person' => '',
        'contact_details' => '',
        'decision_status' => 'Lost',
        'missing_info' => 'None - Complete',
        'next_step' => '',
        'follow_up_date' => null,
        'notes' => 'High price',
        'additional_notes' => ''
    ]
];

$totalPipelineVal = 0;
foreach ($rows as $r) {
    $totalPipelineVal += $r['total_sar'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' || isset($_GET['confirm'])) {
    try {
        // Clean Re-Sync: Delete existing records for Wael so exact sequence and all 7 rows are preserved without any duplicates
        $delStmt = $pdo->prepare("DELETE FROM v4_pipeline WHERE LOWER(TRIM(sales_person)) = 'wael'");
        $delStmt->execute();

        // Calculate starting sequence (preserving overall pipeline sequence)
        $maxRowStmt = $pdo->query("SELECT COALESCE(MAX(row_no), 0) FROM v4_pipeline");
        $baseRowNo = intval($maxRowStmt->fetchColumn());

        $stmtInsert = $pdo->prepare("
            INSERT INTO v4_pipeline (
                row_no, sales_person, project_name, client, main_contractor, consultant,
                scope_of_work, date_received, submission_date, quotation_no, quotation_status,
                total_sar, expected_closing, quotation_valid_until, contact_person, contact_details,
                decision_status, missing_info, next_step, follow_up_date, notes, additional_notes
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        foreach ($rows as $r) {
            $baseRowNo++;
            $stmtInsert->execute([
                $baseRowNo,
                'Wael',
                $r['project_name'] ?: null,
                $r['client'] ?: null,
                $r['main_contractor'] ?: null,
                $r['consultant'] ?: null,
                $r['scope_of_work'],
                $r['date_received'] ?: null,
                $r['submission_date'] ?: null,
                $r['quotation_no'] ?: null,
                $r['quotation_status'] ?: null,
                $r['total_sar'],
                $r['expected_closing'] ?: null,
                $r['quotation_valid_until'] ?: null,
                $r['contact_person'] ?: null,
                $r['contact_details'] ?: null,
                $r['decision_status'] ?: null,
                $r['missing_info'] ?: null,
                $r['next_step'] ?: null,
                $r['follow_up_date'] ?: null,
                $r['notes'] ?: null,
                $r['additional_notes'] ?: null
            ]);
            $inserted++;
        }

        $status = 'success';
        $message = "Successfully imported all {$inserted} sales records for Wael in exact sequence!";
    } catch (Exception $e) {
        $status = 'error';
        $message = "Error during import: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>MVS - Safe Import: Wael (Sales Pipeline)</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Inter', sans-serif; background: #F8FAFC; color: #0F172A; padding: 30px 20px; }
    .card { max-width: 1100px; margin: 0 auto; background: #fff; border-radius: 12px; border: 1px solid #E2E8F0; padding: 32px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    h1 { font-size: 1.45rem; color: #0F172A; margin-bottom: 6px; font-weight: 800; }
    p { color: #64748B; font-size: 0.92rem; margin-bottom: 20px; }
    .stats-bar { display: flex; flex-wrap: wrap; gap: 20px; background: #F1F5F9; padding: 14px 18px; border-radius: 8px; margin-bottom: 24px; font-size: 0.88rem; }
    .stats-bar strong { color: #0284C7; }
    .alert-success { background: #ECFDF5; border: 1px solid #A7F3D0; color: #065F46; padding: 14px 18px; border-radius: 8px; margin-bottom: 20px; font-weight: 600; }
    .alert-error { background: #FEF2F2; border: 1px solid #FECACA; color: #991B1B; padding: 14px 18px; border-radius: 8px; margin-bottom: 20px; font-weight: 600; }
    .btn { display: inline-flex; align-items: center; gap: 8px; font-weight: 600; padding: 12px 24px; border-radius: 8px; cursor: pointer; text-decoration: none; border: none; font-size: 0.95rem; }
    .btn-primary { background: #10B981; color: #fff; }
    .btn-primary:hover { background: #059669; }
    .btn-secondary { background: #E2E8F0; color: #334155; margin-left: 10px; }
    table { width: 100%; border-collapse: collapse; margin-top: 16px; font-size: 0.82rem; }
    th, td { padding: 9px 10px; border: 1px solid #E2E8F0; text-align: left; }
    th { background: #F8FAFC; font-weight: 700; color: #334155; }
    .sar { text-align: right; font-family: monospace; font-weight: 600; }
    .badge { display: inline-block; padding: 3px 8px; border-radius: 4px; font-size: 0.75rem; font-weight: 700; }
    .badge-danger { background: #FEE2E2; color: #991B1B; }
  </style>
</head>
<body>
  <div class="card">
    <h1>Safe Import: Wael (Sales Pipeline Register)</h1>
    <p>Sales Rep: <strong>Wael</strong> | File: <code>MVS PipeLines Tracking wael.xlsx</code> | Total Records: <strong><?= count($rows) ?></strong></p>

    <div class="stats-bar">
      <div>Total Bids: <strong><?= count($rows) ?></strong></div>
      <div>Total Bids Value: <strong><?= number_format($totalPipelineVal, 2) ?> SAR</strong></div>
      <div>Decision Status: <strong style="color:#DC2626;"><?= count($rows) ?> Lost (High Price)</strong></div>
      <div>Quotation Status: <strong>All Closed</strong></div>
    </div>

    <?php if ($status === 'success'): ?>
      <div class="alert-success">&#10003; <?= htmlspecialchars($message) ?></div>
      <div style="margin-bottom: 20px;">
        <a href="sales_portal.php" class="btn btn-primary">&rarr; Open Sales Portal to View Records</a>
        <a href="executive_portal.php" class="btn btn-secondary">&rarr; Open Executive Portal</a>
      </div>
    <?php elseif ($status === 'error'): ?>
      <div class="alert-error"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="POST">
      <button type="submit" class="btn btn-primary">&#10003; Click to Import All 7 Records into Live Database</button>
      <a href="sales_portal.php" class="btn btn-secondary">&larr; Return to Sales Portal</a>
    </form>

    <h3 style="margin-top: 26px; font-size: 1rem; color: #1E293B;">Data Preview (Strict Sequence):</h3>
    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Project / Scope</th>
          <th>Client / Contractor</th>
          <th>Submission Date</th>
          <th>Total SAR (incl. VAT)</th>
          <th>Quotation Status</th>
          <th>Decision Status</th>
          <th>Notes / Reason</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($rows as $idx => $r): ?>
          <tr>
            <td><?= $idx + 1 ?></td>
            <td>
              <strong><?= htmlspecialchars($r['project_name'] ?: $r['scope_of_work']) ?></strong>
              <?php if ($r['project_name'] && $r['scope_of_work'] !== $r['project_name']): ?>
                <br><small style="color:#64748B;"><?= htmlspecialchars($r['scope_of_work']) ?></small>
              <?php endif; ?>
            </td>
            <td>
              <?= htmlspecialchars($r['client'] ?: '-') ?>
              <?php if ($r['main_contractor']): ?>
                <br><small style="color:#64748B;">MC: <?= htmlspecialchars($r['main_contractor']) ?></small>
              <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($r['submission_date'] ?: '-') ?></td>
            <td class="sar"><?= number_format($r['total_sar'], 2) ?></td>
            <td><?= htmlspecialchars($r['quotation_status'] ?: '-') ?></td>
            <td><span class="badge badge-danger"><?= htmlspecialchars($r['decision_status'] ?: 'Lost') ?></span></td>
            <td><small style="color:#DC2626; font-weight:600;"><?= htmlspecialchars($r['notes'] ?: '-') ?></small></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</body>
</html>

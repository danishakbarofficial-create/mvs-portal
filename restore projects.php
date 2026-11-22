<?php
// MVS Project Pulse - One-Click Project Milestone Recovery Tool
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/db.php';

$user = Auth::getCurrentUser();
if (!$user) {
    header('Location: login.php');
    exit;
}

$pdo = Database::getConnection();
$message = '';
$status = '';

// Pre-defined master records from Project Tracking Report Asayil v4
$masterRecords = [
    [
        'row_no' => 1,
        'project_manager' => 'Muhammad Farooq',
        'project_name' => 'Air Base Infrastructure Project',
        'customer' => 'Ministry of Defense',
        'po_status' => 'In Progress',
        'po_no' => 'PO-MOD-9942',
        'po_date' => '2026-04-01',
        'total_po_value' => 2000000.00,
        'expected_closing' => '2027-10-01',
        'months_to_close' => 19,
        'milestone_no' => 'Milestone 1',
        'milestone_desc' => 'Advance Payment (20%) upon contract signing',
        'milestone_value' => 400000.00,
        'milestone_status' => 'Completed',
        'technical_report' => 'Submitted',
        'invoice_status' => 'Fully Invoiced',
        'invoice_no' => 'INV-2026-001',
        'invoice_date' => '2026-04-15',
        'invoice_amount' => 400000.00,
        'collection_date' => '2026-05-02',
        'collected_amount' => 400000.00,
        'collection_status' => 'Fully Collected',
        'pending_collection' => 0.00,
        'to_be_invoiced' => 0.00,
        'notes' => 'Key strategic infrastructure installation',
        'additional_notes' => 'Phase 1 advance cleared; Phase 2 collection underway.'
    ],
    [
        'row_no' => 2,
        'project_manager' => 'Muhammad Farooq',
        'project_name' => 'Air Base Infrastructure Project',
        'customer' => 'Ministry of Defense',
        'po_status' => 'In Progress',
        'po_no' => 'PO-MOD-9942',
        'po_date' => '2026-04-01',
        'total_po_value' => 2000000.00,
        'expected_closing' => '2027-10-01',
        'months_to_close' => 19,
        'milestone_no' => 'Milestone 2',
        'milestone_desc' => 'Delivery of Primary Material & Equipment (40%)',
        'milestone_value' => 800000.00,
        'milestone_status' => 'Completed',
        'technical_report' => 'Submitted',
        'invoice_status' => 'Fully Invoiced',
        'invoice_no' => 'INV-2026-014',
        'invoice_date' => '2026-06-20',
        'invoice_amount' => 800000.00,
        'collection_date' => '2026-07-10',
        'collected_amount' => 500000.00,
        'collection_status' => 'Partially Collected',
        'pending_collection' => 300000.00,
        'to_be_invoiced' => 0.00,
        'notes' => 'Key strategic infrastructure installation',
        'additional_notes' => 'Phase 1 advance cleared; Phase 2 collection underway.'
    ],
    [
        'row_no' => 3,
        'project_manager' => 'Muhammad Farooq',
        'project_name' => 'Air Base Infrastructure Project',
        'customer' => 'Ministry of Defense',
        'po_status' => 'In Progress',
        'po_no' => 'PO-MOD-9942',
        'po_date' => '2026-04-01',
        'total_po_value' => 2000000.00,
        'expected_closing' => '2027-10-01',
        'months_to_close' => 19,
        'milestone_no' => 'Milestone 3',
        'milestone_desc' => 'Installation & Fiber Testing (40%)',
        'milestone_value' => 800000.00,
        'milestone_status' => 'Completed',
        'technical_report' => 'In Progress',
        'invoice_status' => 'Not Invoiced',
        'invoice_no' => '',
        'invoice_date' => null,
        'invoice_amount' => 0.00,
        'collection_date' => null,
        'collected_amount' => 0.00,
        'collection_status' => 'Pending',
        'pending_collection' => 0.00,
        'to_be_invoiced' => 800000.00,
        'notes' => 'Key strategic infrastructure installation',
        'additional_notes' => 'Phase 1 advance cleared; Phase 2 collection underway.'
    ],
    [
        'row_no' => 4,
        'project_manager' => 'Muhammad Farooq',
        'project_name' => 'Seven Entertainment',
        'customer' => 'Seven Entertainment Saudi',
        'po_status' => 'In Progress',
        'po_no' => 'PO-SEVEN-2026',
        'po_date' => '2026-05-01',
        'total_po_value' => 1500000.00,
        'expected_closing' => '2027-05-01',
        'months_to_close' => 12,
        'milestone_no' => 'Milestone 1',
        'milestone_desc' => 'Initial Entertainment Scope & Technical Survey',
        'milestone_value' => 300000.00,
        'milestone_status' => 'In Progress',
        'technical_report' => 'In Progress',
        'invoice_status' => 'Not Invoiced',
        'invoice_no' => '',
        'invoice_date' => null,
        'invoice_amount' => 0.00,
        'collection_date' => null,
        'collected_amount' => 0.00,
        'collection_status' => 'Pending',
        'pending_collection' => 0.00,
        'to_be_invoiced' => 300000.00,
        'notes' => 'Entertainment venue deployment',
        'additional_notes' => 'Site surveying and initial procurement.'
    ]
];

if ($_SERVER['REQUEST_METHOD'] === 'POST' || isset($_GET['restore'])) {
    try {
        $stmt = $pdo->prepare("
            INSERT INTO v4_projects (
                row_no, project_manager, project_name, customer, po_status,
                po_no, po_date, total_po_value, expected_closing, months_to_close,
                milestone_no, milestone_desc, milestone_value, milestone_status,
                technical_report, invoice_status, invoice_no, invoice_date,
                invoice_amount, collection_date, collected_amount, collection_status,
                pending_collection, to_be_invoiced, notes, additional_notes
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $insertedCount = 0;
        foreach ($masterRecords as $r) {
            // Check if exact milestone already exists to avoid duplicates
            $chk = $pdo->prepare("SELECT id FROM v4_projects WHERE project_name = ? AND milestone_no = ?");
            $chk->execute([$r['project_name'], $r['milestone_no']]);
            $exists = $chk->fetch();

            if (!$exists) {
                $stmt->execute([
                    $r['row_no'], $r['project_manager'], $r['project_name'], $r['customer'],
                    $r['po_status'], $r['po_no'], $r['po_date'], $r['total_po_value'],
                    $r['expected_closing'], $r['months_to_close'], $r['milestone_no'],
                    $r['milestone_desc'], $r['milestone_value'], $r['milestone_status'],
                    $r['technical_report'], $r['invoice_status'], $r['invoice_no'],
                    $r['invoice_date'], $r['invoice_amount'], $r['collection_date'],
                    $r['collected_amount'], $r['collection_status'], $r['pending_collection'],
                    $r['to_be_invoiced'], $r['notes'], $r['additional_notes']
                ]);
                $insertedCount++;
            }
        }

        $status = 'success';
        $message = "Successfully restored {$insertedCount} project milestones into v4_projects!";
    } catch (Exception $e) {
        $status = 'error';
        $message = "Error during restore: " . $e->getMessage();
    }
}

// Current database count
$curCount = $pdo->query("SELECT COUNT(*) FROM v4_projects")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>MVS Project Pulse - Restore Projects Data</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Inter', sans-serif; background: #F8FAFC; color: #0F172A; padding: 40px 20px; }
    .card { max-width: 800px; margin: 0 auto; background: #fff; border-radius: 12px; border: 1px solid #E2E8F0; padding: 32px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    h1 { font-size: 1.5rem; color: #0F172A; margin-bottom: 8px; }
    p { color: #64748B; font-size: 0.95rem; margin-bottom: 24px; }
    .alert-success { background: #ECFDF5; border: 1px solid #A7F3D0; color: #065F46; padding: 14px 18px; border-radius: 8px; margin-bottom: 20px; font-weight: 600; }
    .alert-error { background: #FEF2F2; border: 1px solid #FECACA; color: #991B1B; padding: 14px 18px; border-radius: 8px; margin-bottom: 20px; font-weight: 600; }
    .btn { display: inline-flex; align-items: center; gap: 8px; font-weight: 600; padding: 12px 24px; border-radius: 8px; cursor: pointer; text-decoration: none; border: none; font-size: 0.95rem; }
    .btn-primary { background: #10B981; color: #fff; }
    .btn-primary:hover { background: #059669; }
    .btn-secondary { background: #E2E8F0; color: #334155; margin-left: 10px; }
    .badge { display: inline-block; padding: 4px 10px; border-radius: 20px; font-size: 0.8rem; font-weight: 700; background: #E2E8F0; color: #475569; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 0.88rem; }
    th, td { padding: 10px 12px; border: 1px solid #E2E8F0; text-align: left; }
    th { background: #F1F5F9; font-weight: 700; color: #334155; }
  </style>
</head>
<body>
  <div class="card">
    <h1>Project Milestones Recovery Tool</h1>
    <p>Current records in <code>v4_projects</code> table: <span class="badge" style="background: #0284C7; color:#fff;"><?= $curCount ?> rows</span></p>

    <?php if ($status === 'success'): ?>
      <div class="alert-success"><?= htmlspecialchars($message) ?></div>
    <?php elseif ($status === 'error'): ?>
      <div class="alert-error"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="POST">
      <button type="submit" class="btn btn-primary">
        &#8635; Click to Restore Master Project Milestones
      </button>
      <a href="pm_portal.php" class="btn btn-secondary">&larr; Return to PM Portal</a>
    </form>

    <h3 style="margin-top: 30px; font-size: 1.1rem; color: #1E293B;">Master Records Available for Recovery:</h3>
    <table>
      <thead>
        <tr>
          <th>Project Name</th>
          <th>Milestone #</th>
          <th>Milestone Description</th>
          <th>Milestone Value</th>
          <th>Invoice Status</th>
          <th>Collection Status</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($masterRecords as $r): ?>
          <tr>
            <td><strong><?= htmlspecialchars($r['project_name']) ?></strong></td>
            <td><?= htmlspecialchars($r['milestone_no']) ?></td>
            <td><?= htmlspecialchars($r['milestone_desc']) ?></td>
            <td><?= number_format($r['milestone_value'], 2) ?> SAR</td>
            <td><?= htmlspecialchars($r['invoice_status']) ?></td>
            <td><?= htmlspecialchars($r['collection_status']) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</body>
</html>

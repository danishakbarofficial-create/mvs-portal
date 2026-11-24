<?php
// MVS Project Pulse - Safe Import for Muhammad Farooq (EMAR Executive - SEVEN Entertainment Exit 15 NAHDA)
require_once __DIR__ . '/db.php';
header('Content-Type: text/html; charset=utf-8');

$pdo = Database::getConnection();
$message = '';
$status = '';

$projectName = 'SEVEN Entertainment Exit 15 NAHDA';
$customer = 'EMAR Executive';
$pmName = 'Muhammad Farooq';
$totalPoValue = 1379096.78;
$poNo = 'EM328-25';
$poDate = '2026-08-19';
$expectedClosing = '2027-02-01';
$monthsToClose = 7;

$rows = [
    [
        'milestone_no' => 'Milestone 1',
        'milestone_desc' => 'Oct - Nov 2025',
        'milestone_value' => 39628.34,
        'milestone_status' => 'Completed',
        'technical_report' => 'Submitted',
        'invoice_status' => 'Fully Invoiced',
        'invoice_no' => 'MVS-1494',
        'invoice_date' => '2026-01-12',
        'invoice_amount' => 39628.34,
        'collection_date' => '2026-02-10',
        'collected_amount' => 39628.34,
        'collection_status' => 'Fully Collected',
        'pending_collection' => 0.00,
        'to_be_invoiced' => 0.00,
        'notes' => 'Proforma: MVS-60 | Paid in full',
        'additional_notes' => 'Exit 15 NAHDA milestone 1'
    ],
    [
        'milestone_no' => 'Milestone 2',
        'milestone_desc' => 'Dec - Jan - Feb 2026',
        'milestone_value' => 51399.39,
        'milestone_status' => 'Completed',
        'technical_report' => 'Submitted',
        'invoice_status' => 'Fully Invoiced',
        'invoice_no' => 'MVS-1742',
        'invoice_date' => '2026-03-01',
        'invoice_amount' => 51399.39,
        'collection_date' => '2026-04-27',
        'collected_amount' => 51399.39,
        'collection_status' => 'Fully Collected',
        'pending_collection' => 0.00,
        'to_be_invoiced' => 0.00,
        'notes' => 'Proforma: MVS-66 | Paid in full',
        'additional_notes' => 'Exit 15 NAHDA milestone 2'
    ],
    [
        'milestone_no' => 'Milestone 3',
        'milestone_desc' => 'Mar 2026',
        'milestone_value' => 26090.31,
        'milestone_status' => 'Completed',
        'technical_report' => 'Submitted',
        'invoice_status' => 'Fully Invoiced',
        'invoice_no' => 'MVS-1743',
        'invoice_date' => '2026-04-01',
        'invoice_amount' => 26090.31,
        'collection_date' => '2026-07-13',
        'collected_amount' => 26090.31,
        'collection_status' => 'Fully Collected',
        'pending_collection' => 0.00,
        'to_be_invoiced' => 0.00,
        'notes' => 'Proforma: MVS-69 | Paid in full',
        'additional_notes' => 'Exit 15 NAHDA milestone 3'
    ],
    [
        'milestone_no' => 'Milestone 4',
        'milestone_desc' => 'Apr 2026',
        'milestone_value' => 8590.76,
        'milestone_status' => 'Completed',
        'technical_report' => 'Submitted',
        'invoice_status' => 'Fully Invoiced',
        'invoice_no' => 'MVS-1744',
        'invoice_date' => '2026-05-01',
        'invoice_amount' => 8590.76,
        'collection_date' => '2026-06-03',
        'collected_amount' => 8590.76,
        'collection_status' => 'Fully Collected',
        'pending_collection' => 0.00,
        'to_be_invoiced' => 0.00,
        'notes' => 'Proforma: MVS-71 | Paid in full',
        'additional_notes' => 'Exit 15 NAHDA milestone 4'
    ],
    [
        'milestone_no' => 'Milestone 5',
        'milestone_desc' => 'May 2026',
        'milestone_value' => 37472.07,
        'milestone_status' => 'Completed',
        'technical_report' => 'Submitted',
        'invoice_status' => 'Fully Invoiced',
        'invoice_no' => 'MVS-1745',
        'invoice_date' => '2026-06-01',
        'invoice_amount' => 37472.07,
        'collection_date' => '2026-07-13',
        'collected_amount' => 37472.07,
        'collection_status' => 'Fully Collected',
        'pending_collection' => 0.00,
        'to_be_invoiced' => 0.00,
        'notes' => 'Proforma: MVS-74 | Paid in full',
        'additional_notes' => 'Exit 15 NAHDA milestone 5'
    ],
    [
        'milestone_no' => 'Milestone 6',
        'milestone_desc' => 'Jun 2026',
        'milestone_value' => 81189.93,
        'milestone_status' => 'Completed',
        'technical_report' => 'Submitted',
        'invoice_status' => 'Fully Invoiced',
        'invoice_no' => 'MVS-1786',
        'invoice_date' => '2026-07-01',
        'invoice_amount' => 81189.93,
        'collection_date' => '2026-09-08',
        'collected_amount' => 8590.76,
        'collection_status' => 'Partially Collected',
        'pending_collection' => 72599.17,
        'to_be_invoiced' => 0.00,
        'notes' => 'Proforma: MVS-77 | Pending clearance',
        'additional_notes' => 'Exit 15 NAHDA milestone 6'
    ],
    [
        'milestone_no' => 'Milestone 7',
        'milestone_desc' => 'Jul 2026',
        'milestone_value' => 46476.65,
        'milestone_status' => 'Completed',
        'technical_report' => 'Submitted',
        'invoice_status' => 'Fully Invoiced',
        'invoice_no' => 'MVS-1843',
        'invoice_date' => '2026-08-18',
        'invoice_amount' => 46476.65,
        'collection_date' => '2026-10-29',
        'collected_amount' => 50000.00,
        'collection_status' => 'Partially Collected',
        'pending_collection' => 0.00,
        'to_be_invoiced' => 0.00,
        'notes' => 'Proforma: MVS-81 | Partially paid: 50,000 SAR received 29th Oct 2026',
        'additional_notes' => 'Exit 15 NAHDA milestone 7'
    ],
    [
        'milestone_no' => 'Milestone 8',
        'milestone_desc' => 'Aug 2026',
        'milestone_value' => 20131.57,
        'milestone_status' => 'Completed',
        'technical_report' => 'Submitted',
        'invoice_status' => 'Fully Invoiced',
        'invoice_no' => 'MVS-1861',
        'invoice_date' => '2026-09-01',
        'invoice_amount' => 20131.57,
        'collection_date' => '2026-11-12',
        'collected_amount' => 30000.00,
        'collection_status' => 'Partially Collected',
        'pending_collection' => 0.00,
        'to_be_invoiced' => 0.00,
        'notes' => 'Proforma: MVS-85 | 30,000 SAR received 12th Nov 2026',
        'additional_notes' => 'Exit 15 NAHDA milestone 8'
    ],
    [
        'milestone_no' => 'Milestone 9',
        'milestone_desc' => 'Sep 2026',
        'milestone_value' => 26707.09,
        'milestone_status' => 'Completed',
        'technical_report' => 'Submitted',
        'invoice_status' => 'Fully Invoiced',
        'invoice_no' => 'MVS-1960',
        'invoice_date' => '2026-10-06',
        'invoice_amount' => 26707.09,
        'collection_date' => '2026-11-12',
        'collected_amount' => 42957.25,
        'collection_status' => 'Partially Collected',
        'pending_collection' => 0.00,
        'to_be_invoiced' => 0.00,
        'notes' => 'Proforma: MVS-91 | 42,957.25 SAR received 12th Nov 2026',
        'additional_notes' => 'Exit 15 NAHDA milestone 9'
    ],
    [
        'milestone_no' => 'Milestone 10',
        'milestone_desc' => 'Oct 2026',
        'milestone_value' => 102181.00,
        'milestone_status' => 'Completed',
        'technical_report' => 'Submitted',
        'invoice_status' => 'Fully Invoiced',
        'invoice_no' => 'MVS-2017',
        'invoice_date' => '2026-11-15',
        'invoice_amount' => 102181.00,
        'collection_date' => null,
        'collected_amount' => 0.00,
        'collection_status' => 'Pending',
        'pending_collection' => 102181.00,
        'to_be_invoiced' => 0.00,
        'notes' => 'Proforma: MVS-00 | Tax Inv: MVS-2017 submitted 15th Nov 2026',
        'additional_notes' => 'Exit 15 NAHDA milestone 10'
    ]
];

if ($_SERVER['REQUEST_METHOD'] === 'POST' || isset($_GET['confirm'])) {
    try {
        $cleanPrevious = isset($_POST['clean_previous']) ? (bool)$_POST['clean_previous'] : true;

        if ($cleanPrevious) {
            // Delete previous test entries (e.g. dummy PO-MOD-1, client 1, or old incomplete rows)
            $del = $pdo->prepare("DELETE FROM v4_projects WHERE project_name LIKE '%SEVEN%NAHDA%' OR po_no = 'PO-MOD-1' OR po_no = ?");
            $del->execute([$poNo]);
        }

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

        $inserted = 0;
        foreach ($rows as $idx => $r) {
            $stmt->execute([
                $idx + 1, $pmName, $projectName, $customer,
                'In Progress', $poNo, $poDate, $totalPoValue,
                $expectedClosing, $monthsToClose, $r['milestone_no'],
                $r['milestone_desc'], $r['milestone_value'], $r['milestone_status'],
                $r['technical_report'], $r['invoice_status'], $r['invoice_no'],
                $r['invoice_date'], $r['invoice_amount'], $r['collection_date'],
                $r['collected_amount'], $r['collection_status'], $r['pending_collection'],
                $r['to_be_invoiced'], $r['notes'], $r['additional_notes']
            ]);
            $inserted++;
        }
        $status = 'success';
        $message = "Successfully synced all {$inserted} official milestones for Muhammad Farooq ({$customer} - PO: {$poNo})!";
    } catch (Exception $e) {
        $status = 'error';
        $message = "Error inserting records: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>MVS - Safe Import: SEVEN Entertainment Exit 15 NAHDA</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Inter', sans-serif; background: #F8FAFC; color: #0F172A; padding: 30px 20px; }
    .card { max-width: 1000px; margin: 0 auto; background: #fff; border-radius: 12px; border: 1px solid #E2E8F0; padding: 32px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
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
    th, td { padding: 8px 10px; border: 1px solid #E2E8F0; text-align: left; }
    th { background: #F8FAFC; font-weight: 700; color: #334155; }
    .sar { text-align: right; font-family: monospace; font-weight: 600; }
    .badge { display: inline-block; padding: 3px 8px; border-radius: 4px; font-size: 0.75rem; font-weight: 700; }
    .badge-success { background: #D1FAE5; color: #065F46; }
    .badge-warning { background: #FEF3C7; color: #92400E; }
    .badge-danger { background: #FEE2E2; color: #991B1B; }
  </style>
</head>
<body>
  <div class="card">
    <h1>Safe Import: SEVEN Entertainment Exit 15 NAHDA</h1>
    <p>Project Manager: <strong>Muhammad Farooq</strong> | Customer: <strong>EMAR Executive</strong> | PO No.: <strong>EM328-25</strong> | Contract PO Value: <strong>1,379,096.78 SAR</strong></p>

    <div class="stats-bar">
      <div>Total Milestones: <strong>10</strong></div>
      <div>Total Invoiced: <strong>439,867.11 SAR</strong></div>
      <div>Total Received: <strong style="color:#059669;">294,728.88 SAR</strong></div>
      <div>Pending Collection: <strong style="color:#B45309;">145,138.23 SAR</strong></div>
      <div>Backlog To Invoice: <strong>939,229.67 SAR</strong></div>
    </div>

    <?php if ($status === 'success'): ?>
      <div class="alert-success">&#10003; <?= htmlspecialchars($message) ?></div>
      <div style="margin-bottom: 20px;">
        <a href="pm_portal.php" class="btn btn-primary">&rarr; Open PM Portal to View Official Milestones</a>
      </div>
    <?php elseif ($status === 'error'): ?>
      <div class="alert-error"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="POST">
      <div style="margin-bottom: 14px;">
        <label style="font-weight: 600; font-size: 0.9rem; cursor: pointer;">
          <input type="checkbox" name="clean_previous" value="1" checked> 
          Clean &amp; replace dummy/test records for SEVEN Entertainment (removes dummy <code>PO-MOD-1</code> / <code>client 1</code> rows)
        </label>
      </div>
      <button type="submit" class="btn btn-primary">&#10003; Sync All 10 Milestones into Live Database</button>
      <a href="pm_portal.php" class="btn btn-secondary">&larr; Return to PM Portal</a>
    </form>

    <h3 style="margin-top: 26px; font-size: 1rem; color: #1E293B;">Milestone Deliverables &amp; Invoicing Preview:</h3>
    <table>
      <thead>
        <tr>
          <th>Sr.</th>
          <th>Milestone</th>
          <th>Scope Description</th>
          <th>Tax Invoice #</th>
          <th>Invoice Amount</th>
          <th>Invoice Date</th>
          <th>Status</th>
          <th>Date Received</th>
          <th>Received Amount</th>
          <th>Pending Collection</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($rows as $idx => $r): ?>
          <tr>
            <td><?= $idx + 1 ?></td>
            <td><strong><?= htmlspecialchars($r['milestone_no']) ?></strong></td>
            <td><?= htmlspecialchars($r['milestone_desc']) ?></td>
            <td><code><?= htmlspecialchars($r['invoice_no']) ?></code></td>
            <td class="sar"><?= number_format($r['invoice_amount'], 2) ?></td>
            <td><?= htmlspecialchars($r['invoice_date']) ?></td>
            <td>
              <?php if ($r['collection_status'] === 'Fully Collected'): ?>
                <span class="badge badge-success">Fully Collected</span>
              <?php elseif ($r['collection_status'] === 'Partially Collected'): ?>
                <span class="badge badge-warning">Partially Collected</span>
              <?php else: ?>
                <span class="badge badge-danger">Pending</span>
              <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($r['collection_date'] ?? '-') ?></td>
            <td class="sar" style="color:#059669;"><?= number_format($r['collected_amount'], 2) ?></td>
            <td class="sar" style="color:#DC2626;"><?= number_format($r['pending_collection'], 2) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</body>
</html>

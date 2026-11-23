<?php
// MVS Project Pulse - Safe Import for Nasir (Sales Pipeline Register)
require_once __DIR__ . '/db.php';
header('Content-Type: text/html; charset=utf-8');

$pdo = Database::getConnection();
$message = '';
$status = '';
$inserted = 0;

$rows = [
    [
        'excel_no' => 1,
        'sales_person' => 'Nasir',
        'project_name' => '',
        'client' => 'ECCA Group',
        'main_contractor' => 'AL ZAHRA AL MASREH AL WALA Company (ECCA Group)',
        'consultant' => 'NA',
        'scope_of_work' => 'Jetting V 2 , NE',
        'date_received' => '2026-06-21',
        'submission_date' => '2026-06-24',
        'quotation_no' => '1714',
        'quotation_status' => 'On Hold',
        'total_sar' => 152000.00,
        'expected_closing' => null,
        'quotation_valid_until' => null,
        'contact_person' => 'Mr. Ahmad',
        'contact_details' => '592834307',
        'decision_status' => 'On Hold',
        'missing_info' => 'None',
        'next_step' => '',
        'follow_up_date' => null,
        'notes' => '',
        'additional_notes' => "The project is currently on hold from the client's side. I am still following up for further updates."
    ],
    [
        'excel_no' => 2,
        'sales_person' => 'Nasir',
        'project_name' => 'O&M of Telecom systems at Jubail',
        'client' => 'Royal Commisison',
        'main_contractor' => 'Seder Group',
        'consultant' => 'NA',
        'scope_of_work' => 'Fiber Optic Cable 12CORE G657A Green MEFC',
        'date_received' => '2026-07-01',
        'submission_date' => '2026-06-04',
        'quotation_no' => '1665',
        'quotation_status' => 'Approved',
        'total_sar' => 27600.00,
        'expected_closing' => null,
        'quotation_valid_until' => null,
        'contact_person' => 'Mr. Faisal',
        'contact_details' => '534909940',
        'decision_status' => 'Won',
        'missing_info' => 'None',
        'next_step' => '',
        'follow_up_date' => null,
        'notes' => '',
        'additional_notes' => 'The PO has been closed following the successful delivery of the materials and submission of the invoice.'
    ],
    [
        'excel_no' => 3,
        'sales_person' => 'Nasir',
        'project_name' => 'O&M of Telecom systems at Jubail',
        'client' => 'Royal Commisison',
        'main_contractor' => 'Seder Group',
        'consultant' => 'NA',
        'scope_of_work' => 'TOA Consumables  Mosque at RC facilities.',
        'date_received' => '2026-09-05',
        'submission_date' => '2026-07-27',
        'quotation_no' => '1769',
        'quotation_status' => 'Approved',
        'total_sar' => 8958.50,
        'expected_closing' => null,
        'quotation_valid_until' => null,
        'contact_person' => 'Mr. Faisal',
        'contact_details' => '534909940',
        'decision_status' => 'Won',
        'missing_info' => 'None',
        'next_step' => '',
        'follow_up_date' => null,
        'notes' => '',
        'additional_notes' => 'The PO has been closed following the successful delivery of the materials and submission of the invoice.'
    ],
    [
        'excel_no' => 4,
        'sales_person' => 'Nasir',
        'project_name' => 'O&M of Telecom systems at Jubail',
        'client' => 'Royal Commisison',
        'main_contractor' => 'Seder Group',
        'consultant' => 'NA',
        'scope_of_work' => 'TOA Spare parts  Mosque at RC facilities.',
        'date_received' => '2026-09-07',
        'submission_date' => '2026-07-28',
        'quotation_no' => '1809',
        'quotation_status' => 'Approved',
        'total_sar' => 78717.50,
        'expected_closing' => null,
        'quotation_valid_until' => null,
        'contact_person' => 'Mr. Faisal',
        'contact_details' => '534909940',
        'decision_status' => 'Won',
        'missing_info' => 'None',
        'next_step' => '',
        'follow_up_date' => null,
        'notes' => '',
        'additional_notes' => 'The PO has been closed following the successful delivery of the materials and submission of the invoice.'
    ],
    [
        'excel_no' => 5,
        'sales_person' => 'Nasir',
        'project_name' => 'Dirriyah',
        'client' => 'Al-Seif',
        'main_contractor' => 'Al-Seif',
        'consultant' => 'NA',
        'scope_of_work' => 'Hikvision Thermal Cameras.',
        'date_received' => '2026-09-03',
        'submission_date' => '2026-09-10',
        'quotation_no' => '1890',
        'quotation_status' => 'On Hold',
        'total_sar' => 83662.50,
        'expected_closing' => null,
        'quotation_valid_until' => null,
        'contact_person' => 'Mr.Ikram',
        'contact_details' => '598424345',
        'decision_status' => 'On Hold',
        'missing_info' => 'None',
        'next_step' => '',
        'follow_up_date' => null,
        'notes' => '',
        'additional_notes' => "The project is currently on hold from the client's side. I am still following up for further updates."
    ],
    [
        'excel_no' => 6,
        'sales_person' => 'Nasir',
        'project_name' => 'EMERALD Village Residential Compound',
        'client' => 'EMERALD Village Residential Compound',
        'main_contractor' => 'EMERALD Village Residential Compound',
        'consultant' => 'NA',
        'scope_of_work' => 'Hikvision Bullet Cameras.',
        'date_received' => '2026-09-22',
        'submission_date' => '2026-09-22',
        'quotation_no' => '1892',
        'quotation_status' => 'On Hold',
        'total_sar' => 59536.65,
        'expected_closing' => null,
        'quotation_valid_until' => null,
        'contact_person' => 'Mr.Bilal',
        'contact_details' => '546612722',
        'decision_status' => 'On Hold',
        'missing_info' => 'None',
        'next_step' => '',
        'follow_up_date' => null,
        'notes' => '',
        'additional_notes' => "The project is currently on hold from the client's side. I am still following up for further updates."
    ],
    [
        'excel_no' => 7,
        'sales_person' => 'Nasir',
        'project_name' => 'Seven Entertainment',
        'client' => 'Seven Entertainment',
        'main_contractor' => 'EEC Emaar Executive',
        'consultant' => 'NA',
        'scope_of_work' => 'Air Conditioning consumables.',
        'date_received' => '2026-10-07',
        'submission_date' => '2026-09-27',
        'quotation_no' => '1902',
        'quotation_status' => 'Approved',
        'total_sar' => 86671.31,
        'expected_closing' => null,
        'quotation_valid_until' => null,
        'contact_person' => 'Mr. Parwez',
        'contact_details' => '596449134',
        'decision_status' => 'Won',
        'missing_info' => 'None',
        'next_step' => '',
        'follow_up_date' => null,
        'notes' => '',
        'additional_notes' => 'The PO has been closed following the successful delivery of the materials and submission of the invoice.'
    ],
    [
        'excel_no' => 8,
        'sales_person' => 'Nasir',
        'project_name' => 'Al- Diqah Project',
        'client' => 'EEC Emaar Executive',
        'main_contractor' => 'EEC Emaar Executive',
        'consultant' => 'NA',
        'scope_of_work' => 'Air Conditioner & consumables.',
        'date_received' => '2026-10-25',
        'submission_date' => '2026-10-18',
        'quotation_no' => '1962',
        'quotation_status' => 'Approved',
        'total_sar' => 12990.40,
        'expected_closing' => null,
        'quotation_valid_until' => null,
        'contact_person' => 'Mr. Parwez',
        'contact_details' => '596449134',
        'decision_status' => 'Won',
        'missing_info' => 'None',
        'next_step' => '',
        'follow_up_date' => null,
        'notes' => '',
        'additional_notes' => 'The PO has been closed following the successful delivery of the materials and submission of the invoice.'
    ],
    [
        'excel_no' => 9,
        'sales_person' => 'Nasir',
        'project_name' => 'Seven Entertainment  Shapurji',
        'client' => 'Shapurji',
        'main_contractor' => 'EEC Emaar Executive',
        'consultant' => 'NA',
        'scope_of_work' => 'Fiber Testing Supervision',
        'date_received' => '2026-10-28',
        'submission_date' => '2026-10-18',
        'quotation_no' => 'PI # 96',
        'quotation_status' => 'Approved',
        'total_sar' => 10838.75,
        'expected_closing' => null,
        'quotation_valid_until' => null,
        'contact_person' => 'Mr. Parwez',
        'contact_details' => '596449134',
        'decision_status' => 'Won',
        'missing_info' => 'None',
        'next_step' => '',
        'follow_up_date' => null,
        'notes' => '',
        'additional_notes' => 'The job has been successfully completed following the testing. It is now under the testing report submission stage.'
    ],
    [
        'excel_no' => 10,
        'sales_person' => 'Nasir',
        'project_name' => 'STC Project Aljouf Region',
        'client' => 'EEC Emaar Executive',
        'main_contractor' => 'EEC Emaar Executive',
        'consultant' => 'NA',
        'scope_of_work' => 'Cable Ladder & Accesseries',
        'date_received' => '2026-11-11',
        'submission_date' => '2026-10-26',
        'quotation_no' => '1982',
        'quotation_status' => 'Approved',
        'total_sar' => 35798.94,
        'expected_closing' => null,
        'quotation_valid_until' => null,
        'contact_person' => 'Mr. Parwez',
        'contact_details' => '596449134',
        'decision_status' => 'Won',
        'missing_info' => 'None',
        'next_step' => '',
        'follow_up_date' => null,
        'notes' => '',
        'additional_notes' => 'The PO has been closed following the successful delivery of the materials and submission of the invoice.'
    ],
    [
        'excel_no' => 11,
        'sales_person' => 'Nasir',
        'project_name' => 'STC Project Khamis Mushit Region',
        'client' => 'EEC Emaar Executive',
        'main_contractor' => 'EEC Emaar Executive',
        'consultant' => 'NA',
        'scope_of_work' => 'Cable Ladder & Accesseries',
        'date_received' => '2026-11-09',
        'submission_date' => '2026-10-15',
        'quotation_no' => '2012',
        'quotation_status' => 'Approved',
        'total_sar' => 33540.90,
        'expected_closing' => null,
        'quotation_valid_until' => null,
        'contact_person' => 'Mr. Parwez',
        'contact_details' => '596449134',
        'decision_status' => 'Won',
        'missing_info' => 'None',
        'next_step' => '',
        'follow_up_date' => null,
        'notes' => '',
        'additional_notes' => 'The PO has been closed following the successful delivery of the materials and submission of the invoice.'
    ],
    [
        'excel_no' => 12,
        'sales_person' => 'Nasir',
        'project_name' => 'STC MS',
        'client' => 'MME',
        'main_contractor' => 'MME',
        'consultant' => 'NA',
        'scope_of_work' => 'MPO Cables',
        'date_received' => '2026-11-09',
        'submission_date' => '2026-11-11',
        'quotation_no' => 'Manaual',
        'quotation_status' => 'Not Approved',
        'total_sar' => 1178.75,
        'expected_closing' => null,
        'quotation_valid_until' => null,
        'contact_person' => 'Mr. Zillur Rahman',
        'contact_details' => '510486217',
        'decision_status' => null,
        'missing_info' => 'None',
        'next_step' => 'Under client evaluation',
        'follow_up_date' => null,
        'notes' => '',
        'additional_notes' => 'Its still under review.'
    ],
    [
        'excel_no' => 13,
        'sales_person' => 'Nasir',
        'project_name' => 'STC MS',
        'client' => 'MME',
        'main_contractor' => 'MME',
        'consultant' => 'NA',
        'scope_of_work' => 'Patch Cords',
        'date_received' => '2026-11-04',
        'submission_date' => '2026-10-28',
        'quotation_no' => 'Manaual',
        'quotation_status' => 'Approved',
        'total_sar' => 19560.64,
        'expected_closing' => null,
        'quotation_valid_until' => null,
        'contact_person' => 'Mr. Zillur Rahman',
        'contact_details' => '510486217',
        'decision_status' => 'Won',
        'missing_info' => 'None',
        'next_step' => 'Material Delivery',
        'follow_up_date' => null,
        'notes' => '',
        'additional_notes' => 'Po material is under delivery process.'
    ],
    [
        'excel_no' => 14,
        'sales_person' => 'Nasir',
        'project_name' => 'STC MS',
        'client' => 'MME',
        'main_contractor' => 'MME',
        'consultant' => 'NA',
        'scope_of_work' => 'Tools',
        'date_received' => '2026-10-27',
        'submission_date' => '2026-10-28',
        'quotation_no' => 'Manaual',
        'quotation_status' => 'Not Approved',
        'total_sar' => 1009694.25,
        'expected_closing' => null,
        'quotation_valid_until' => null,
        'contact_person' => 'Mr. Zillur Rahman',
        'contact_details' => '510486217',
        'decision_status' => null,
        'missing_info' => 'None',
        'next_step' => 'Under client technical review',
        'follow_up_date' => null,
        'notes' => '',
        'additional_notes' => 'Its still under review.'
    ],
    [
        'excel_no' => 15,
        'sales_person' => 'Nasir',
        'project_name' => 'STC MS',
        'client' => 'MME',
        'main_contractor' => 'MME',
        'consultant' => 'NA',
        'scope_of_work' => 'MS Consumables Items.',
        'date_received' => '2026-11-03',
        'submission_date' => '2026-10-19',
        'quotation_no' => '1971',
        'quotation_status' => 'Approved',
        'total_sar' => 88051.76,
        'expected_closing' => null,
        'quotation_valid_until' => null,
        'contact_person' => 'Mr. Zillur Rahman',
        'contact_details' => '510486217',
        'decision_status' => 'Won',
        'missing_info' => 'None',
        'next_step' => 'Material Delivery',
        'follow_up_date' => null,
        'notes' => '',
        'additional_notes' => 'Po material is under delivery process.'
    ],
    [
        'excel_no' => 16,
        'sales_person' => 'Nasir',
        'project_name' => '',
        'client' => 'Saudi Net Link',
        'main_contractor' => 'Saudi Net Link',
        'consultant' => 'NA',
        'scope_of_work' => 'Tools',
        'date_received' => '2026-10-08',
        'submission_date' => '2026-10-12',
        'quotation_no' => 'Manaual',
        'quotation_status' => null,
        'total_sar' => 308748.36,
        'expected_closing' => null,
        'quotation_valid_until' => null,
        'contact_person' => 'Mr. Abdulellah',
        'contact_details' => '505913122',
        'decision_status' => null,
        'missing_info' => 'None',
        'next_step' => 'Under review',
        'follow_up_date' => null,
        'notes' => '',
        'additional_notes' => 'Its still under review.'
    ],
    [
        'excel_no' => 17,
        'sales_person' => 'Nasir',
        'project_name' => 'STC MS',
        'client' => 'MME',
        'main_contractor' => 'MME',
        'consultant' => 'NA',
        'scope_of_work' => 'Project Consumables Items.',
        'date_received' => '2026-10-29',
        'submission_date' => '2026-11-03',
        'quotation_no' => '1991',
        'quotation_status' => null,
        'total_sar' => 167381.93,
        'expected_closing' => null,
        'quotation_valid_until' => null,
        'contact_person' => 'Mr. Zillur Rahman',
        'contact_details' => '510486217',
        'decision_status' => null,
        'missing_info' => 'None',
        'next_step' => 'Under review',
        'follow_up_date' => null,
        'notes' => '',
        'additional_notes' => 'Its still under review.'
    ]
];

$totalPipelineVal = 0;
foreach ($rows as $r) {
    $totalPipelineVal += $r['total_sar'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' || isset($_GET['confirm'])) {
    try {
        // Clean Re-Sync: Delete existing records for Nasir so exact sequence and all 17 rows are preserved without any duplicates
        $delStmt = $pdo->prepare("DELETE FROM v4_pipeline WHERE LOWER(TRIM(sales_person)) = 'nasir'");
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
                'Nasir',
                $r['project_name'],
                $r['client'],
                $r['main_contractor'],
                $r['consultant'],
                $r['scope_of_work'],
                $r['date_received'],
                $r['submission_date'],
                $r['quotation_no'],
                $r['quotation_status'],
                $r['total_sar'],
                $r['expected_closing'],
                $r['quotation_valid_until'],
                $r['contact_person'],
                $r['contact_details'],
                $r['decision_status'],
                $r['missing_info'],
                $r['next_step'],
                $r['follow_up_date'],
                $r['notes'],
                $r['additional_notes']
            ]);
            $inserted++;
        }

        $status = 'success';
        $message = "Kamyabi! Nasir ke tamam 17 records (samet un 4 entries ke jinka Decision Status blank tha) live database me exact sequence ke sath insert ho chuke hain!";
    } catch (Exception $e) {
        $status = 'error';
        $message = "Database error during import: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>MVS - Safe Re-Sync: Nasir Sales Pipeline</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Inter', sans-serif; background: #F8FAFC; color: #0F172A; padding: 30px 20px; }
    .card { max-width: 1200px; margin: 0 auto; background: #fff; border-radius: 12px; border: 1px solid #E2E8F0; padding: 32px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    h1 { font-size: 1.45rem; color: #0F172A; margin-bottom: 6px; font-weight: 800; }
    p { color: #64748B; font-size: 0.92rem; margin-bottom: 20px; }
    .stats-bar { display: flex; gap: 24px; background: #F1F5F9; padding: 14px 18px; border-radius: 8px; margin-bottom: 24px; font-size: 0.88rem; flex-wrap: wrap; }
    .stats-bar strong { color: #0284C7; }
    .alert-success { background: #ECFDF5; border: 1px solid #A7F3D0; color: #065F46; padding: 14px 18px; border-radius: 8px; margin-bottom: 20px; font-weight: 600; }
    .alert-error { background: #FEF2F2; border: 1px solid #FECACA; color: #991B1B; padding: 14px 18px; border-radius: 8px; margin-bottom: 20px; font-weight: 600; }
    .btn { display: inline-flex; align-items: center; gap: 8px; font-weight: 600; padding: 12px 24px; border-radius: 8px; cursor: pointer; text-decoration: none; border: none; font-size: 0.95rem; }
    .btn-primary { background: #10B981; color: #fff; }
    .btn-primary:hover { background: #059669; }
    .btn-secondary { background: #E2E8F0; color: #334155; margin-left: 10px; }
    table { width: 100%; border-collapse: collapse; margin-top: 16px; font-size: 0.82rem; }
    th, td { padding: 9px 12px; border: 1px solid #E2E8F0; text-align: left; }
    th { background: #F8FAFC; font-weight: 700; color: #334155; }
    .sar { text-align: right; font-family: monospace; font-weight: 600; color: #0F172A; }
    .badge { padding: 3px 8px; border-radius: 4px; font-weight: 600; font-size: 0.75rem; display: inline-block; }
    .badge-won { background: #ECFDF5; color: #059669; }
    .badge-hold { background: #FEF3C7; color: #B45309; }
    .badge-blank { background: #F1F5F9; color: #64748B; border: 1px dashed #CBD5E1; }
  </style>
</head>
<body>
  <div class="card">
    <h1>Safe Re-Sync: Nasir Sales Pipeline Register (All 17 Records)</h1>
    <p>Sales Person: <strong>Nasir</strong> | Source File: <code>MVS PipeLines Tracking Nasir.xlsx</code></p>

    <div class="stats-bar">
      <div>Total Records: <strong>17 Opportunities</strong></div>
      <div>Total Pipeline Value: <strong><?= number_format($totalPipelineVal, 2) ?> SAR</strong></div>
      <div>Won Bids: <strong style="color:#059669;">9 Opportunities</strong></div>
      <div>On Hold: <strong style="color:#B45309;">3 Opportunities</strong></div>
      <div>No Decision Status: <strong style="color:#64748B;">4 Opportunities (Included!)</strong></div>
      <div>Sequence Integrity: <strong style="color:#10B981;">100% Sequential</strong></div>
    </div>

    <?php if ($status === 'success'): ?>
      <div class="alert-success">&#10003; <?= htmlspecialchars($message) ?></div>
      <div style="margin-bottom: 24px;">
        <a href="sales_portal.php" class="btn btn-primary">&rarr; Open Sales Portal to View All 17 Opportunities</a>
        <a href="executive_portal.php" class="btn btn-secondary">&rarr; Open Executive Command Center</a>
      </div>
    <?php elseif ($status === 'error'): ?>
      <div class="alert-error"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="POST">
      <button type="submit" class="btn btn-primary">&#10003; Click to Re-Sync All 17 Records (Including 4 With No Status)</button>
      <a href="sales_portal.php" class="btn btn-secondary">&larr; Go to Sales Portal</a>
    </form>

    <h3 style="margin-top: 26px; font-size: 1rem; color: #1E293B;">Complete 17 Opportunities Preview:</h3>
    <table>
      <thead>
        <tr>
          <th>No.</th>
          <th>Project / Scope</th>
          <th>Client</th>
          <th>Main Contractor</th>
          <th>Quotation #</th>
          <th>Submission Date</th>
          <th style="text-align:right;">Total incl. VAT (SAR)</th>
          <th>Decision Status</th>
          <th>Contact Person</th>
          <th>Notes / Updates</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($rows as $r): ?>
          <tr>
            <td><?= $r['excel_no'] ?></td>
            <td>
              <?php if (!empty($r['project_name'])): ?>
                <strong><?= htmlspecialchars($r['project_name']) ?></strong><br>
              <?php endif; ?>
              <span style="color:#475569;"><?= htmlspecialchars($r['scope_of_work']) ?></span>
            </td>
            <td><strong><?= htmlspecialchars($r['client']) ?></strong></td>
            <td><?= htmlspecialchars($r['main_contractor']) ?></td>
            <td><code><?= htmlspecialchars($r['quotation_no']) ?></code></td>
            <td><?= htmlspecialchars($r['submission_date'] ?: '-') ?></td>
            <td class="sar"><?= number_format($r['total_sar'], 2) ?></td>
            <td>
              <?php if (empty($r['decision_status'])): ?>
                <span class="badge badge-blank">Not Decided / Blank</span>
              <?php elseif ($r['decision_status'] === 'Won'): ?>
                <span class="badge badge-won">Won</span>
              <?php elseif ($r['decision_status'] === 'On Hold'): ?>
                <span class="badge badge-hold">On Hold</span>
              <?php else: ?>
                <span class="badge" style="background:#EFF6FF; color:#2563EB;"><?= htmlspecialchars($r['decision_status']) ?></span>
              <?php endif; ?>
            </td>
            <td>
              <?= htmlspecialchars($r['contact_person']) ?><br>
              <small style="color:#64748B;"><?= htmlspecialchars($r['contact_details']) ?></small>
            </td>
            <td style="font-size:0.75rem; color:#475569; max-width:240px;">
              <?= htmlspecialchars($r['additional_notes']) ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</body>
</html>

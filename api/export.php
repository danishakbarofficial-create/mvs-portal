<?php
// Export opportunities as CSV in Mounir's exact Daily Log format
require_once __DIR__ . '/../db.php';

try {
    $pdo = Database::getConnection();

    $where = ["1=1"];
    $params = [];

    if (!empty($_GET['month'])) {
        $where[] = "(visit_month = ? OR close_month = ?)";
        $params[] = $_GET['month'];
        $params[] = $_GET['month'];
    }

    if (!empty($_GET['salesperson'])) {
        $where[] = "salesperson = ?";
        $params[] = $_GET['salesperson'];
    }

    $stmt = $pdo->prepare("SELECT * FROM sales_opportunities WHERE " . implode(' AND ', $where) . " ORDER BY log_date DESC, id DESC");
    $stmt->execute($params);
    $rows = $stmt->fetchAll();

    $filename = 'MVS_Daily_Sales_Log_' . date('Ymd_His') . '.csv';

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');

    $out = fopen('php://output', 'w');
    // UTF-8 BOM for Excel
    fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF));

    // Mounir's Exact Headers
    fputcsv($out, [
        'Date',
        'Salesperson',
        'Client Name',
        'Company',
        'Location',
        'Contact Person',
        'Purpose',
        'Product Line',
        'Industry',
        'Lead Source',
        'Discussion Summary',
        'Opportunity Value (SAR)',
        'Stage',
        'Default Probability %',
        'Override Probability %',
        'Forecast Probability %',
        'Weighted Forecast (SAR)',
        'Expected / Actual Close Date',
        'Next Action',
        'Next Visit Date',
        'Visit Month',
        'Close Month',
        'Opportunity ID'
    ]);

    foreach ($rows as $r) {
        fputcsv($out, [
            $r['log_date'],
            $r['salesperson'],
            $r['client_name'],
            $r['company'],
            $r['location'],
            $r['contact_person'],
            $r['purpose'],
            $r['product_line'],
            $r['industry'],
            $r['lead_source'],
            $r['discussion_summary'],
            $r['opportunity_value'],
            $r['stage'],
            round($r['default_probability'] * 100, 0) . '%',
            $r['override_probability'] !== null ? (round($r['override_probability'] * 100, 0) . '%') : '',
            round($r['forecast_probability'] * 100, 0) . '%',
            $r['weighted_forecast'],
            $r['close_date'],
            $r['next_action'],
            $r['next_visit_date'],
            $r['visit_month'],
            $r['close_month'],
            $r['opportunity_id']
        ]);
    }

    fclose($out);
    exit;

} catch (Throwable $e) {
    http_response_code(500);
    echo "Error exporting data: " . $e->getMessage();
}

<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/../db.php';

try {
    $pdo = Database::getConnection();

    $stages = $pdo->query("SELECT * FROM master_stages ORDER BY display_order ASC")->fetchAll();
    $productLines = $pdo->query("SELECT * FROM master_product_lines ORDER BY id ASC")->fetchAll();
    $industries = $pdo->query("SELECT * FROM master_industries ORDER BY id ASC")->fetchAll();
    $leadSources = $pdo->query("SELECT * FROM master_lead_sources ORDER BY id ASC")->fetchAll();
    $reps = $pdo->query("SELECT * FROM sales_reps WHERE status = 'active' ORDER BY id ASC")->fetchAll();

    $st = $pdo->query("SELECT value FROM system_settings WHERE key = 'report_month'");
    $reportMonth = $st->fetchColumn() ?: '2026-03';

    echo json_encode([
        'success' => true,
        'report_month' => $reportMonth,
        'stages' => $stages,
        'product_lines' => $productLines,
        'industries' => $industries,
        'lead_sources' => $leadSources,
        'sales_reps' => $reps
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

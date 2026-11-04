<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

require_once __DIR__ . '/../db.php';

try {
    $pdo = Database::getConnection();
    $method = $_SERVER['REQUEST_METHOD'];

    if ($method === 'GET') {
        $year = $_GET['year'] ?? '2026';

        $st = $pdo->prepare("SELECT * FROM sales_targets WHERE target_month LIKE ? ORDER BY salesperson ASC, target_month ASC");
        $st->execute([$year . '-%']);
        $targets = $st->fetchAll();

        $stQual = $pdo->prepare("SELECT * FROM quality_targets WHERE target_month LIKE ? ORDER BY target_month ASC");
        $stQual->execute([$year . '-%']);
        $quality = $stQual->fetchAll();

        echo json_encode([
            'success' => true,
            'year' => $year,
            'targets' => $targets,
            'quality_targets' => $quality
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
    }

    if ($method === 'POST' || $method === 'PUT') {
        $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;

        if (!empty($input['salesperson']) && !empty($input['target_month'])) {
            $stmt = $pdo->prepare("
                INSERT INTO sales_targets (salesperson, target_month, revenue_target, visit_target)
                VALUES (?, ?, ?, ?)
                ON CONFLICT(salesperson, target_month) DO UPDATE SET
                    revenue_target = excluded.revenue_target,
                    visit_target = excluded.visit_target
            ");
            $stmt->execute([
                $input['salesperson'],
                $input['target_month'],
                (float)($input['revenue_target'] ?? 0),
                (int)($input['visit_target'] ?? 0)
            ]);

            echo json_encode(['success' => true, 'message' => 'Target updated successfully']);
            exit;
        }

        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Missing required fields']);
    }

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

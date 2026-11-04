<?php
// API: v4 Validation Lists
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../auth.php';
Auth::requireLogin();

$pdo = Database::getConnection();
$category = $_GET['category'] ?? null;

if ($category) {
    $stmt = $pdo->prepare("SELECT item_value FROM v4_lists WHERE category = ? ORDER BY display_order ASC");
    $stmt->execute([$category]);
    $items = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo json_encode(['success' => true, 'category' => $category, 'items' => $items]);
} else {
    $stmt = $pdo->query("SELECT category, item_value FROM v4_lists ORDER BY category ASC, display_order ASC");
    $all = $stmt->fetchAll();
    $grouped = [];
    foreach ($all as $row) {
        $cat = $row['category'];
        if (!isset($grouped[$cat])) {
            $grouped[$cat] = [];
        }
        $grouped[$cat][] = $row['item_value'];
    }
    echo json_encode(['success' => true, 'lists' => $grouped]);
}

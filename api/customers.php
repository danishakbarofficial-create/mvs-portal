<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'OPTIONS') {
    exit(0);
}

require_once __DIR__ . '/../db.php';

$pdo = Database::getConnection();
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

try {
    if ($method === 'GET') {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $st = $pdo->prepare("SELECT * FROM customers WHERE id = ?");
            $st->execute([$id]);
            $cust = $st->fetch();
            if (!$cust) {
                http_response_code(404);
                echo json_encode(['success' => false, 'error' => 'Customer not found']);
                exit;
            }

            // Linked opportunities
            $stOpp = $pdo->prepare("SELECT * FROM sales_opportunities WHERE company = ? OR client_name = ? ORDER BY log_date DESC");
            $stOpp->execute([$cust['company_name'], $cust['company_name']]);
            $cust['opportunities'] = $stOpp->fetchAll();

            // Linked projects
            $stPrj = $pdo->prepare("SELECT * FROM projects WHERE company_name = ? ORDER BY id DESC");
            $stPrj->execute([$cust['company_name']]);
            $cust['projects'] = $stPrj->fetchAll();

            echo json_encode(['success' => true, 'data' => $cust]);
            exit;
        }

        $search = trim($_GET['search'] ?? '');
        $industry = trim($_GET['industry'] ?? '');

        $where = ["1=1"];
        $params = [];

        if ($search) {
            $where[] = "(company_name LIKE ? OR contact_person LIKE ? OR city_region LIKE ? OR email LIKE ?)";
            $params = array_merge($params, ["%$search%", "%$search%", "%$search%", "%$search%"]);
        }

        if ($industry) {
            $where[] = "industry = ?";
            $params[] = $industry;
        }

        $sql = "
            SELECT c.*,
                (SELECT COUNT(*) FROM sales_opportunities WHERE company = c.company_name) as total_interactions,
                (SELECT COUNT(*) FROM sales_opportunities WHERE company = c.company_name AND stage = 'Closed Won') as total_won_deals,
                (SELECT COALESCE(SUM(opportunity_value), 0) FROM sales_opportunities WHERE company = c.company_name AND stage = 'Closed Won') as total_won_value,
                (SELECT COUNT(*) FROM projects WHERE company_name = c.company_name) as active_projects_count
            FROM customers c
            WHERE " . implode(' AND ', $where) . "
            ORDER BY c.company_name ASC
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $customers = $stmt->fetchAll();

        echo json_encode([
            'success' => true,
            'total' => count($customers),
            'data' => $customers
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
    }

    $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;

    if ($method === 'POST') {
        $company = trim($input['company_name'] ?? '');
        if (!$company) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Company Name is required']);
            exit;
        }

        $stmt = $pdo->prepare("
            INSERT INTO customers (
                company_name, industry, city_region, contact_person, designation,
                phone, email, address, account_status, notes
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $company,
            $input['industry'] ?? 'Telecom',
            $input['city_region'] ?? 'Riyadh',
            $input['contact_person'] ?? '',
            $input['designation'] ?? '',
            $input['phone'] ?? '',
            $input['email'] ?? '',
            $input['address'] ?? '',
            $input['account_status'] ?? 'Active',
            $input['notes'] ?? ''
        ]);

        echo json_encode(['success' => true, 'id' => $pdo->lastInsertId(), 'message' => 'Customer created successfully']);
        exit;
    }

    if ($method === 'PUT') {
        $id = $input['id'] ?? ($_GET['id'] ?? null);
        if (!$id) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Customer ID is required']);
            exit;
        }

        $stmt = $pdo->prepare("
            UPDATE customers SET
                company_name = ?,
                industry = ?,
                city_region = ?,
                contact_person = ?,
                designation = ?,
                phone = ?,
                email = ?,
                address = ?,
                account_status = ?,
                notes = ?
            WHERE id = ?
        ");

        $stmt->execute([
            $input['company_name'],
            $input['industry'] ?? '',
            $input['city_region'] ?? '',
            $input['contact_person'] ?? '',
            $input['designation'] ?? '',
            $input['phone'] ?? '',
            $input['email'] ?? '',
            $input['address'] ?? '',
            $input['account_status'] ?? 'Active',
            $input['notes'] ?? '',
            $id
        ]);

        echo json_encode(['success' => true, 'message' => 'Customer updated successfully']);
        exit;
    }

    if ($method === 'DELETE') {
        $id = $input['id'] ?? ($_GET['id'] ?? null);
        if (!$id) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Customer ID is required']);
            exit;
        }

        $stmt = $pdo->prepare("DELETE FROM customers WHERE id = ?");
        $stmt->execute([$id]);

        echo json_encode(['success' => true, 'message' => 'Customer deleted']);
        exit;
    }

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

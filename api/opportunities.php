<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, PATCH, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

require_once __DIR__ . '/../db.php';

$pdo = Database::getConnection();
$method = $_SERVER['REQUEST_METHOD'];

// Helper to calculate probability & forecast
function computeForecast(PDO $pdo, string $stage, float $val, ?float $override): array {
    $st = $pdo->prepare("SELECT default_probability FROM master_stages WHERE stage_name = ?");
    $st->execute([$stage]);
    $defProb = (float)($st->fetchColumn() ?: 0.0);

    $fcProb = ($override !== null && $override !== '' && is_numeric($override)) ? (float)$override : $defProb;
    $weighted = round($val * $fcProb, 2);

    return [
        'default_probability' => $defProb,
        'override_probability' => $override,
        'forecast_probability' => $fcProb,
        'weighted_forecast' => $weighted
    ];
}

try {
    if ($method === 'GET') {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $stmt = $pdo->prepare("SELECT * FROM sales_opportunities WHERE id = ?");
            $stmt->execute([$id]);
            $item = $stmt->fetch();
            if (!$item) {
                http_response_code(404);
                echo json_encode(['success' => false, 'error' => 'Opportunity not found']);
                exit;
            }
            echo json_encode(['success' => true, 'data' => $item]);
            exit;
        }

        // Filters
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

        if (!empty($_GET['product_line'])) {
            $where[] = "product_line = ?";
            $params[] = $_GET['product_line'];
        }

        if (!empty($_GET['stage'])) {
            $where[] = "stage = ?";
            $params[] = $_GET['stage'];
        }

        if (!empty($_GET['search'])) {
            $search = '%' . trim($_GET['search']) . '%';
            $where[] = "(client_name LIKE ? OR company LIKE ? OR contact_person LIKE ? OR discussion_summary LIKE ? OR opportunity_id LIKE ?)";
            $params[] = $search;
            $params[] = $search;
            $params[] = $search;
            $params[] = $search;
            $params[] = $search;
        }

        $sql = "SELECT * FROM sales_opportunities WHERE " . implode(' AND ', $where) . " ORDER BY log_date DESC, id DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $deals = $stmt->fetchAll();

        echo json_encode([
            'success' => true,
            'total' => count($deals),
            'data' => $deals
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
    }

    $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;

    if ($method === 'POST') {
        // Create Opportunity
        $logDate = $input['log_date'] ?? date('Y-m-d');
        $salesperson = $input['salesperson'] ?? 'Sales 1';
        $clientName = trim($input['client_name'] ?? '');
        $company = trim($input['company'] ?? '');
        $productLine = $input['product_line'] ?? 'VIAVI';
        $stage = $input['stage'] ?? 'Lead';
        $val = (float)($input['opportunity_value'] ?? 0);
        $override = isset($input['override_probability']) && $input['override_probability'] !== '' ? (float)$input['override_probability'] : null;

        if (!$company) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Company is required']);
            exit;
        }

        // Auto Opportunity ID: YYYYMMDD-####
        $datePrefix = str_replace('-', '', $logDate);
        $stCount = $pdo->prepare("SELECT COUNT(*) FROM sales_opportunities WHERE log_date = ?");
        $stCount->execute([$logDate]);
        $nextNum = ((int)$stCount->fetchColumn()) + 1;
        $oppId = $datePrefix . '-' . sprintf('%04d', $nextNum);

        $computed = computeForecast($pdo, $stage, $val, $override);

        $closeDate = !empty($input['close_date']) ? $input['close_date'] : null;
        $visitMonth = substr($logDate, 0, 7);
        $closeMonth = $closeDate ? substr($closeDate, 0, 7) : null;

        $stmt = $pdo->prepare("
            INSERT INTO sales_opportunities (
                opportunity_id, log_date, salesperson, client_name, company, location,
                contact_person, purpose, product_line, industry, lead_source,
                discussion_summary, opportunity_value, stage, default_probability,
                override_probability, forecast_probability, weighted_forecast,
                close_date, next_action, next_visit_date, visit_month, close_month
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $oppId,
            $logDate,
            $salesperson,
            $clientName ?: $company,
            $company,
            $input['location'] ?? 'Riyadh',
            $input['contact_person'] ?? '',
            $input['purpose'] ?? 'Client Visit & Assessment',
            $productLine,
            $input['industry'] ?? 'Telecom',
            $input['lead_source'] ?? 'Direct',
            $input['discussion_summary'] ?? '',
            $val,
            $stage,
            $computed['default_probability'],
            $computed['override_probability'],
            $computed['forecast_probability'],
            $computed['weighted_forecast'],
            $closeDate,
            $input['next_action'] ?? '',
            !empty($input['next_visit_date']) ? $input['next_visit_date'] : null,
            $visitMonth,
            $closeMonth
        ]);

        $newId = $pdo->lastInsertId();
        echo json_encode(['success' => true, 'id' => $newId, 'opportunity_id' => $oppId, 'message' => 'Opportunity logged successfully']);
        exit;
    }

    if ($method === 'PUT' || $method === 'PATCH') {
        $id = $input['id'] ?? ($_GET['id'] ?? null);
        if (!$id) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Opportunity ID is required']);
            exit;
        }

        // Fetch current record
        $stmt = $pdo->prepare("SELECT * FROM sales_opportunities WHERE id = ?");
        $stmt->execute([$id]);
        $current = $stmt->fetch();
        if (!$current) {
            http_response_code(404);
            echo json_encode(['success' => false, 'error' => 'Record not found']);
            exit;
        }

        // Fast stage change (Kanban drag & drop)
        $stage = $input['stage'] ?? $current['stage'];
        $val = isset($input['opportunity_value']) ? (float)$input['opportunity_value'] : (float)$current['opportunity_value'];
        $override = array_key_exists('override_probability', $input) 
            ? ($input['override_probability'] !== '' && $input['override_probability'] !== null ? (float)$input['override_probability'] : null)
            : $current['override_probability'];

        $computed = computeForecast($pdo, $stage, $val, $override);

        $logDate = $input['log_date'] ?? $current['log_date'];
        $closeDate = array_key_exists('close_date', $input) ? ($input['close_date'] ?: null) : $current['close_date'];
        $visitMonth = substr($logDate, 0, 7);
        $closeMonth = $closeDate ? substr($closeDate, 0, 7) : null;

        $stmt = $pdo->prepare("
            UPDATE sales_opportunities SET
                log_date = ?,
                salesperson = ?,
                client_name = ?,
                company = ?,
                location = ?,
                contact_person = ?,
                purpose = ?,
                product_line = ?,
                industry = ?,
                lead_source = ?,
                discussion_summary = ?,
                opportunity_value = ?,
                stage = ?,
                default_probability = ?,
                override_probability = ?,
                forecast_probability = ?,
                weighted_forecast = ?,
                close_date = ?,
                next_action = ?,
                next_visit_date = ?,
                visit_month = ?,
                close_month = ?,
                updated_at = CURRENT_TIMESTAMP
            WHERE id = ?
        ");

        $stmt->execute([
            $logDate,
            $input['salesperson'] ?? $current['salesperson'],
            $input['client_name'] ?? $current['client_name'],
            $input['company'] ?? $current['company'],
            $input['location'] ?? $current['location'],
            $input['contact_person'] ?? $current['contact_person'],
            $input['purpose'] ?? $current['purpose'],
            $input['product_line'] ?? $current['product_line'],
            $input['industry'] ?? $current['industry'],
            $input['lead_source'] ?? $current['lead_source'],
            $input['discussion_summary'] ?? $current['discussion_summary'],
            $val,
            $stage,
            $computed['default_probability'],
            $computed['override_probability'],
            $computed['forecast_probability'],
            $computed['weighted_forecast'],
            $closeDate,
            $input['next_action'] ?? $current['next_action'],
            array_key_exists('next_visit_date', $input) ? ($input['next_visit_date'] ?: null) : $current['next_visit_date'],
            $visitMonth,
            $closeMonth,
            $id
        ]);

        echo json_encode(['success' => true, 'message' => 'Updated successfully', 'stage' => $stage, 'weighted_forecast' => $computed['weighted_forecast']]);
        exit;
    }

    if ($method === 'DELETE') {
        $id = $input['id'] ?? ($_GET['id'] ?? null);
        if (!$id) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Opportunity ID is required']);
            exit;
        }

        $stmt = $pdo->prepare("DELETE FROM sales_opportunities WHERE id = ?");
        $stmt->execute([$id]);

        echo json_encode(['success' => true, 'message' => 'Deleted successfully']);
        exit;
    }

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

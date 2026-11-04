<?php
// API: v4 Sales Pipeline (Pre-Award)
if (!headers_sent()) {
    header('Content-Type: application/json; charset=utf-8');
}
require_once __DIR__ . '/../auth.php';

$user = Auth::requireLogin();
$pdo = Database::getConnection();
$method = $_SERVER['REQUEST_METHOD'];

// Handle GET
if ($method === 'GET') {
    $id = $_GET['id'] ?? null;
    if ($id) {
        $stmt = $pdo->prepare("SELECT * FROM v4_pipeline WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        if (!$row) {
            http_response_code(404);
            echo json_encode(['error' => 'Record not found']);
            exit;
        }
        // If sales rep, ensure they own it
        if ($user['role'] === 'sales' && strcasecmp(trim($row['sales_person']), trim($user['name'])) !== 0) {
            http_response_code(403);
            echo json_encode(['error' => 'Unauthorized']);
            exit;
        }
        echo json_encode(['success' => true, 'data' => $row]);
        exit;
    }

    $query = "SELECT * FROM v4_pipeline WHERE 1=1";
    $params = [];

    // Role-based scoping: Sales reps only see their own records
    if ($user['role'] === 'sales') {
        $query .= " AND LOWER(TRIM(sales_person)) = LOWER(TRIM(?))";
        $params[] = $user['name'];
    } elseif (!empty($_GET['sales_person'])) {
        $query .= " AND LOWER(TRIM(sales_person)) = LOWER(TRIM(?))";
        $params[] = $_GET['sales_person'];
    }

    if (!empty($_GET['decision_status'])) {
        $query .= " AND decision_status = ?";
        $params[] = $_GET['decision_status'];
    }

    if (!empty($_GET['quotation_status'])) {
        $query .= " AND quotation_status = ?";
        $params[] = $_GET['quotation_status'];
    }

    if (!empty($_GET['search'])) {
        $search = '%' . trim($_GET['search']) . '%';
        $query .= " AND (project_name LIKE ? OR client LIKE ? OR main_contractor LIKE ? OR quotation_no LIKE ?)";
        $params[] = $search;
        $params[] = $search;
        $params[] = $search;
        $params[] = $search;
    }

    $query .= " ORDER BY id DESC";

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $records = $stmt->fetchAll();

    echo json_encode([
        'success' => true,
        'count' => count($records),
        'role' => $user['role'],
        'user_name' => $user['name'],
        'data' => $records
    ]);
    exit;
}

function normalizePipelineConditions(array &$data): void {
    $qStatus = $data['quotation_status'] ?? 'Open';
    $decStatus = $data['decision_status'] ?? 'Information Gathering';

    if ($decStatus === 'Won' || $qStatus === 'Approved') {
        $data['quotation_status'] = 'Approved';
        $data['decision_status'] = 'Won';
        $data['missing_info'] = 'N/A';
    } elseif ($decStatus === 'Lost') {
        if (!in_array($qStatus, ['Not Approved', 'Closed', 'Cancelled'])) {
            $data['quotation_status'] = 'Not Approved';
        }
    } elseif ($qStatus === 'Not Approved' || $qStatus === 'Cancelled') {
        $data['decision_status'] = 'Lost';
    } elseif ($qStatus === 'On Hold' || $decStatus === 'On Hold') {
        $data['quotation_status'] = 'On Hold';
        $data['decision_status'] = 'On Hold';
    } elseif ($qStatus === 'Open') {
        if ($decStatus === 'Won' || $decStatus === 'Lost') {
            $data['decision_status'] = 'Information Gathering';
        }
    }
}

// Handle POST (Create)
if ($method === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
    normalizePipelineConditions($input);

    $salesPerson = ($user['role'] === 'sales') ? $user['name'] : ($input['sales_person'] ?? $user['name']);

    $stmt = $pdo->prepare("
        INSERT INTO v4_pipeline (
            sales_person, project_name, client, main_contractor, consultant,
            scope_of_work, date_received, submission_date, quotation_no, quotation_status,
            total_sar, expected_closing, quotation_valid_until, contact_person, contact_details,
            decision_status, missing_info, next_step, follow_up_date, notes, additional_notes
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $salesPerson,
        $input['project_name'] ?? 'Untitled Opportunity',
        $input['client'] ?? '',
        $input['main_contractor'] ?? '',
        $input['consultant'] ?? '',
        $input['scope_of_work'] ?? '',
        $input['date_received'] ?? date('Y-m-d'),
        $input['submission_date'] ?? null,
        $input['quotation_no'] ?? '',
        $input['quotation_status'] ?? 'Open',
        floatval($input['total_sar'] ?? 0),
        $input['expected_closing'] ?? null,
        $input['quotation_valid_until'] ?? null,
        $input['contact_person'] ?? '',
        $input['contact_details'] ?? '',
        $input['decision_status'] ?? 'Information Gathering',
        $input['missing_info'] ?? 'None - Complete',
        $input['next_step'] ?? '',
        $input['follow_up_date'] ?? null,
        $input['notes'] ?? '',
        $input['additional_notes'] ?? ''
    ]);

    $newId = $pdo->lastInsertId();
    echo json_encode(['success' => true, 'id' => $newId, 'message' => 'Pipeline opportunity added successfully']);
    exit;
}

// Handle PUT (Update)
if ($method === 'PUT') {
    $input = json_decode(file_get_contents('php://input'), true);
    $id = $input['id'] ?? $_GET['id'] ?? null;

    if (!$id) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing record ID']);
        exit;
    }

    // Check ownership if sales
    $check = $pdo->prepare("SELECT * FROM v4_pipeline WHERE id = ?");
    $check->execute([$id]);
    $existing = $check->fetch();

    if (!$existing) {
        http_response_code(404);
        echo json_encode(['error' => 'Record not found']);
        exit;
    }

    if ($user['role'] === 'sales' && strcasecmp(trim($existing['sales_person']), trim($user['name'])) !== 0) {
        http_response_code(403);
        echo json_encode(['error' => 'Unauthorized to edit this record']);
        exit;
    }

    normalizePipelineConditions($input);
    $salesPerson = ($user['role'] === 'sales') ? $existing['sales_person'] : ($input['sales_person'] ?? $existing['sales_person']);

    $stmt = $pdo->prepare("
        UPDATE v4_pipeline SET
            sales_person = ?,
            project_name = ?,
            client = ?,
            main_contractor = ?,
            consultant = ?,
            scope_of_work = ?,
            date_received = ?,
            submission_date = ?,
            quotation_no = ?,
            quotation_status = ?,
            total_sar = ?,
            expected_closing = ?,
            quotation_valid_until = ?,
            contact_person = ?,
            contact_details = ?,
            decision_status = ?,
            missing_info = ?,
            next_step = ?,
            follow_up_date = ?,
            notes = ?,
            additional_notes = ?,
            updated_at = CURRENT_TIMESTAMP
        WHERE id = ?
    ");

    $stmt->execute([
        $salesPerson,
        $input['project_name'] ?? $existing['project_name'],
        $input['client'] ?? $existing['client'],
        $input['main_contractor'] ?? $existing['main_contractor'],
        $input['consultant'] ?? $existing['consultant'],
        $input['scope_of_work'] ?? $existing['scope_of_work'],
        $input['date_received'] ?? $existing['date_received'],
        $input['submission_date'] ?? $existing['submission_date'],
        $input['quotation_no'] ?? $existing['quotation_no'],
        $input['quotation_status'] ?? $existing['quotation_status'],
        isset($input['total_sar']) ? floatval($input['total_sar']) : $existing['total_sar'],
        $input['expected_closing'] ?? $existing['expected_closing'],
        $input['quotation_valid_until'] ?? $existing['quotation_valid_until'],
        $input['contact_person'] ?? $existing['contact_person'],
        $input['contact_details'] ?? $existing['contact_details'],
        $input['decision_status'] ?? $existing['decision_status'],
        $input['missing_info'] ?? $existing['missing_info'],
        $input['next_step'] ?? $existing['next_step'],
        $input['follow_up_date'] ?? $existing['follow_up_date'],
        $input['notes'] ?? $existing['notes'],
        $input['additional_notes'] ?? $existing['additional_notes'],
        $id
    ]);

    echo json_encode(['success' => true, 'message' => 'Record updated successfully']);
    exit;
}

// Handle DELETE
if ($method === 'DELETE') {
    $id = $_GET['id'] ?? null;
    if (!$id) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing record ID']);
        exit;
    }

    if ($user['role'] !== 'executive') {
        http_response_code(403);
        echo json_encode(['error' => 'Only Executive management can delete records']);
        exit;
    }

    $stmt = $pdo->prepare("DELETE FROM v4_pipeline WHERE id = ?");
    $stmt->execute([$id]);
    echo json_encode(['success' => true, 'message' => 'Record deleted']);
    exit;
}

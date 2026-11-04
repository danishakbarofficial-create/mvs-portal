<?php
// API: v4 Awarded Projects & Milestones Register (Post-Award Operations & Financials)
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
        $stmt = $pdo->prepare("SELECT * FROM v4_projects WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        if (!$row) {
            http_response_code(404);
            echo json_encode(['error' => 'Record not found']);
            exit;
        }
        if ($user['role'] === 'pm' && $row['project_manager'] && strcasecmp(trim($row['project_manager']), trim($user['name'])) !== 0) {
            http_response_code(403);
            echo json_encode(['error' => 'Unauthorized']);
            exit;
        }
        echo json_encode(['success' => true, 'data' => $row]);
        exit;
    }

    $query = "SELECT * FROM v4_projects WHERE 1=1";
    $params = [];

    // Role-based scoping: PMs only see their assigned projects (or unassigned)
    if ($user['role'] === 'pm') {
        $query .= " AND (LOWER(TRIM(project_manager)) = LOWER(TRIM(?)) OR project_manager IS NULL OR project_manager = '')";
        $params[] = $user['name'];
    } elseif (!empty($_GET['project_manager'])) {
        $query .= " AND LOWER(TRIM(project_manager)) = LOWER(TRIM(?))";
        $params[] = $_GET['project_manager'];
    }

    if (!empty($_GET['project_name'])) {
        $query .= " AND project_name = ?";
        $params[] = $_GET['project_name'];
    }

    if (!empty($_GET['po_status'])) {
        $query .= " AND po_status = ?";
        $params[] = $_GET['po_status'];
    }

    if (!empty($_GET['milestone_status'])) {
        $query .= " AND milestone_status = ?";
        $params[] = $_GET['milestone_status'];
    }

    if (!empty($_GET['search'])) {
        $search = '%' . trim($_GET['search']) . '%';
        $query .= " AND (project_name LIKE ? OR customer LIKE ? OR po_no LIKE ? OR invoice_no LIKE ?)";
        $params[] = $search;
        $params[] = $search;
        $params[] = $search;
        $params[] = $search;
    }

    $query .= " ORDER BY project_name ASC, id ASC";

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

function computeMilestoneFormulas(array $data, ?array $existing = null): array {
    $milestoneVal = isset($data['milestone_value']) ? floatval($data['milestone_value']) : floatval($existing['milestone_value'] ?? 0);
    $invoiceAmt = isset($data['invoice_amount']) ? floatval($data['invoice_amount']) : floatval($existing['invoice_amount'] ?? 0);
    $collectedAmt = isset($data['collected_amount']) ? floatval($data['collected_amount']) : floatval($existing['collected_amount'] ?? 0);
    $invoiceNo = trim($data['invoice_no'] ?? ($existing['invoice_no'] ?? ''));
    $poDate = $data['po_date'] ?? ($existing['po_date'] ?? null);
    $expectedClosing = $data['expected_closing'] ?? ($existing['expected_closing'] ?? null);

    // v4 Excel Formula: Pending Collection = MAX(0, S5 - U5)
    $pendingCollection = max(0.0, $invoiceAmt - $collectedAmt);

    // v4 Excel Formula: To Be Invoiced = MAX(0, M5 - S5)
    $toBeInvoiced = max(0.0, $milestoneVal - $invoiceAmt);

    // v4 Excel Formula: Invoice Status = IF(C5="", "", IF(Q5="", "Not Invoiced", IF(S5>=M5, "Fully Invoiced", "Partially Invoiced")))
    if (empty($invoiceNo) || $invoiceAmt <= 0) {
        $invoiceStatus = 'Not Invoiced';
    } elseif ($invoiceAmt >= $milestoneVal && $milestoneVal > 0) {
        $invoiceStatus = 'Fully Invoiced';
    } else {
        $invoiceStatus = 'Partially Invoiced';
    }

    // Collection Status Condition
    if ($collectedAmt <= 0) {
        $collectionStatus = 'Pending';
    } elseif ($collectedAmt >= $invoiceAmt && $invoiceAmt > 0) {
        $collectionStatus = 'Fully Collected';
    } else {
        $collectionStatus = 'Partially Collected';
    }

    // Months to Close Formula: =(Year2 - Year1)*12 + Month2 - Month1 + 1
    $monthsToClose = null;
    if (!empty($poDate) && !empty($expectedClosing)) {
        try {
            $d1 = new DateTime($poDate);
            $d2 = new DateTime($expectedClosing);
            $diff = $d1->diff($d2);
            $monthsToClose = ($diff->y * 12) + $diff->m + ($diff->invert ? -$diff->m : 0) + 1;
        } catch (Exception $e) {
            $monthsToClose = null;
        }
    }

    return [
        'pending_collection' => $pendingCollection,
        'to_be_invoiced' => $toBeInvoiced,
        'invoice_status' => $invoiceStatus,
        'collection_status' => $collectionStatus,
        'months_to_close' => $monthsToClose
    ];
}

// Handle POST (Create Milestone or New Project Entry)
if ($method === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;

    $pmName = ($user['role'] === 'pm') ? $user['name'] : ($input['project_manager'] ?? $user['name']);
    $totalPo = floatval($input['total_po_value'] ?? 0);
    $milestoneVal = floatval($input['milestone_value'] ?? 0);
    $invoiceAmt = floatval($input['invoice_amount'] ?? 0);
    $collectedAmt = floatval($input['collected_amount'] ?? 0);

    // Run Excel Formula Engine
    $computed = computeMilestoneFormulas($input);

    $stmt = $pdo->prepare("
        INSERT INTO v4_projects (
            project_manager, project_name, customer, po_status, po_no,
            po_date, total_po_value, expected_closing, months_to_close,
            milestone_no, milestone_desc, milestone_value, milestone_status,
            technical_report, invoice_status, invoice_no, invoice_date,
            invoice_amount, collection_date, collected_amount, collection_status,
            pending_collection, to_be_invoiced, notes, additional_notes
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $pmName,
        $input['project_name'] ?? 'Untitled Project',
        $input['customer'] ?? '',
        $input['po_status'] ?? 'In Progress',
        $input['po_no'] ?? '',
        $input['po_date'] ?? date('Y-m-d'),
        $totalPo,
        $input['expected_closing'] ?? null,
        $computed['months_to_close'],
        $input['milestone_no'] ?? 'Milestone 1',
        $input['milestone_desc'] ?? '',
        $milestoneVal,
        $input['milestone_status'] ?? 'Pending',
        $input['technical_report'] ?? 'In Progress',
        $computed['invoice_status'],
        $input['invoice_no'] ?? '',
        $input['invoice_date'] ?? null,
        $invoiceAmt,
        $input['collection_date'] ?? null,
        $collectedAmt,
        $computed['collection_status'],
        $computed['pending_collection'],
        $computed['to_be_invoiced'],
        $input['notes'] ?? '',
        $input['additional_notes'] ?? ''
    ]);

    $newId = $pdo->lastInsertId();
    echo json_encode(['success' => true, 'id' => $newId, 'message' => 'Project milestone created successfully']);
    exit;
}

// Handle PUT (Update Milestone / Billing)
if ($method === 'PUT') {
    $input = json_decode(file_get_contents('php://input'), true);
    $id = $input['id'] ?? $_GET['id'] ?? null;

    if (!$id) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing record ID']);
        exit;
    }

    $check = $pdo->prepare("SELECT * FROM v4_projects WHERE id = ?");
    $check->execute([$id]);
    $existing = $check->fetch();

    if (!$existing) {
        http_response_code(404);
        echo json_encode(['error' => 'Record not found']);
        exit;
    }

    if ($user['role'] === 'pm' && $existing['project_manager'] && strcasecmp(trim($existing['project_manager']), trim($user['name'])) !== 0) {
        http_response_code(403);
        echo json_encode(['error' => 'Unauthorized to edit this project milestone']);
        exit;
    }

    $pmName = ($user['role'] === 'pm') ? $existing['project_manager'] : ($input['project_manager'] ?? $existing['project_manager']);
    $totalPo = isset($input['total_po_value']) ? floatval($input['total_po_value']) : floatval($existing['total_po_value']);
    $milestoneVal = isset($input['milestone_value']) ? floatval($input['milestone_value']) : floatval($existing['milestone_value']);
    $invoiceAmt = isset($input['invoice_amount']) ? floatval($input['invoice_amount']) : floatval($existing['invoice_amount']);
    $collectedAmt = isset($input['collected_amount']) ? floatval($input['collected_amount']) : floatval($existing['collected_amount']);

    // Run Excel Formula Engine
    $computed = computeMilestoneFormulas($input, $existing);

    $stmt = $pdo->prepare("
        UPDATE v4_projects SET
            project_manager = ?,
            project_name = ?,
            customer = ?,
            po_status = ?,
            po_no = ?,
            po_date = ?,
            total_po_value = ?,
            expected_closing = ?,
            months_to_close = ?,
            milestone_no = ?,
            milestone_desc = ?,
            milestone_value = ?,
            milestone_status = ?,
            technical_report = ?,
            invoice_status = ?,
            invoice_no = ?,
            invoice_date = ?,
            invoice_amount = ?,
            collection_date = ?,
            collected_amount = ?,
            collection_status = ?,
            pending_collection = ?,
            to_be_invoiced = ?,
            notes = ?,
            additional_notes = ?,
            updated_at = CURRENT_TIMESTAMP
        WHERE id = ?
    ");

    $stmt->execute([
        $pmName,
        $input['project_name'] ?? $existing['project_name'],
        $input['customer'] ?? $existing['customer'],
        $input['po_status'] ?? $existing['po_status'],
        $input['po_no'] ?? $existing['po_no'],
        $input['po_date'] ?? $existing['po_date'],
        $totalPo,
        $input['expected_closing'] ?? $existing['expected_closing'],
        $computed['months_to_close'],
        $input['milestone_no'] ?? $existing['milestone_no'],
        $input['milestone_desc'] ?? $existing['milestone_desc'],
        $milestoneVal,
        $input['milestone_status'] ?? $existing['milestone_status'],
        $input['technical_report'] ?? $existing['technical_report'],
        $computed['invoice_status'],
        $input['invoice_no'] ?? $existing['invoice_no'],
        $input['invoice_date'] ?? $existing['invoice_date'],
        $invoiceAmt,
        $input['collection_date'] ?? $existing['collection_date'],
        $collectedAmt,
        $computed['collection_status'],
        $computed['pending_collection'],
        $computed['to_be_invoiced'],
        $input['notes'] ?? $existing['notes'],
        $input['additional_notes'] ?? $existing['additional_notes'],
        $id
    ]);

    echo json_encode(['success' => true, 'message' => 'Milestone updated successfully']);
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
        echo json_encode(['error' => 'Only Executive management can delete project milestones']);
        exit;
    }

    $stmt = $pdo->prepare("DELETE FROM v4_projects WHERE id = ?");
    $stmt->execute([$id]);
    echo json_encode(['success' => true, 'message' => 'Milestone record deleted']);
    exit;
}

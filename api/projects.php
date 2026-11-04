<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, PATCH, OPTIONS');
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
            $st = $pdo->prepare("SELECT * FROM projects WHERE id = ?");
            $st->execute([$id]);
            $prj = $st->fetch();
            if (!$prj) {
                http_response_code(404);
                echo json_encode(['success' => false, 'error' => 'Project not found']);
                exit;
            }

            $stMs = $pdo->prepare("SELECT * FROM project_milestones WHERE project_id = ? ORDER BY id ASC");
            $stMs->execute([$id]);
            $prj['milestones'] = $stMs->fetchAll();

            echo json_encode(['success' => true, 'data' => $prj]);
            exit;
        }

        $search = trim($_GET['search'] ?? '');
        $status = trim($_GET['status'] ?? '');

        $where = ["1=1"];
        $params = [];

        if ($search) {
            $where[] = "(project_name LIKE ? OR project_code LIKE ? OR company_name LIKE ? OR project_manager LIKE ?)";
            $params = array_merge($params, ["%$search%", "%$search%", "%$search%", "%$search%"]);
        }

        if ($status) {
            $where[] = "status = ?";
            $params[] = $status;
        }

        $sql = "SELECT * FROM projects WHERE " . implode(' AND ', $where) . " ORDER BY progress_pct ASC, id DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $projects = $stmt->fetchAll();

        // Attach milestones to each project
        $stMs = $pdo->prepare("SELECT * FROM project_milestones WHERE project_id = ? ORDER BY id ASC");
        foreach ($projects as &$p) {
            $stMs->execute([$p['id']]);
            $p['milestones'] = $stMs->fetchAll();
        }

        // Summary stats
        $totalProjects = count($projects);
        $avgProgress = $totalProjects > 0 ? round(array_sum(array_column($projects, 'progress_pct')) / $totalProjects, 1) : 0;
        $onTrackCount = count(array_filter($projects, fn($p) => $p['status'] === 'On Track' || $p['status'] === 'Handover Stage'));

        echo json_encode([
            'success' => true,
            'stats' => [
                'total_projects' => $totalProjects,
                'avg_progress_pct' => $avgProgress,
                'on_track_count' => $onTrackCount
            ],
            'data' => $projects
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
    }

    $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;

    if ($method === 'POST') {
        $name = trim($input['project_name'] ?? '');
        $company = trim($input['company_name'] ?? '');

        if (!$name || !$company) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Project Name and Client Company are required']);
            exit;
        }

        // Auto Project Code: PRJ-YYYY-###
        $year = date('Y');
        $stCnt = $pdo->query("SELECT COUNT(*) FROM projects");
        $nextNum = ((int)$stCnt->fetchColumn()) + 1;
        $code = "PRJ-{$year}-" . sprintf('%03d', $nextNum);

        $stmt = $pdo->prepare("
            INSERT INTO projects (
                project_code, project_name, company_name, site_location, project_manager,
                progress_pct, status, start_date, target_end_date, scope_description
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $code,
            $name,
            $company,
            $input['site_location'] ?? 'Riyadh',
            $input['project_manager'] ?? 'Adrian Kim Mallilin',
            (float)($input['progress_pct'] ?? 0),
            $input['status'] ?? 'In Progress',
            $input['start_date'] ?? date('Y-m-d'),
            $input['target_end_date'] ?? null,
            $input['scope_description'] ?? ''
        ]);

        $newId = $pdo->lastInsertId();

        // Optional milestones array
        if (!empty($input['milestones']) && is_array($input['milestones'])) {
            $stMs = $pdo->prepare("INSERT INTO project_milestones (project_id, title, target_date, status, progress_pct) VALUES (?, ?, ?, ?, ?)");
            foreach ($input['milestones'] as $ms) {
                if (!empty($ms['title'])) {
                    $stMs->execute([$newId, $ms['title'], $ms['target_date'] ?? null, $ms['status'] ?? 'Pending', (float)($ms['progress_pct'] ?? 0)]);
                }
            }
        }

        echo json_encode(['success' => true, 'id' => $newId, 'project_code' => $code, 'message' => 'Project created successfully']);
        exit;
    }

    if ($method === 'PUT' || $method === 'PATCH') {
        $id = $input['id'] ?? ($_GET['id'] ?? null);
        if (!$id) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Project ID is required']);
            exit;
        }

        $stmt = $pdo->prepare("
            UPDATE projects SET
                project_name = ?,
                company_name = ?,
                site_location = ?,
                project_manager = ?,
                progress_pct = ?,
                status = ?,
                start_date = ?,
                target_end_date = ?,
                scope_description = ?,
                updated_at = CURRENT_TIMESTAMP
            WHERE id = ?
        ");

        $stmt->execute([
            $input['project_name'],
            $input['company_name'],
            $input['site_location'] ?? '',
            $input['project_manager'] ?? '',
            (float)($input['progress_pct'] ?? 0),
            $input['status'] ?? 'In Progress',
            $input['start_date'] ?? null,
            $input['target_end_date'] ?? null,
            $input['scope_description'] ?? '',
            $id
        ]);

        // If milestone progress is provided
        if (!empty($input['milestones']) && is_array($input['milestones'])) {
            $stUpMs = $pdo->prepare("UPDATE project_milestones SET status = ?, progress_pct = ? WHERE id = ? AND project_id = ?");
            foreach ($input['milestones'] as $ms) {
                if (!empty($ms['id'])) {
                    $stUpMs->execute([$ms['status'], (float)($ms['progress_pct'] ?? 0), $ms['id'], $id]);
                }
            }
        }

        echo json_encode(['success' => true, 'message' => 'Project progress updated successfully']);
        exit;
    }

    if ($method === 'DELETE') {
        $id = $input['id'] ?? ($_GET['id'] ?? null);
        if (!$id) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Project ID is required']);
            exit;
        }

        $pdo->prepare("DELETE FROM project_milestones WHERE project_id = ?")->execute([$id]);
        $pdo->prepare("DELETE FROM projects WHERE id = ?")->execute([$id]);

        echo json_encode(['success' => true, 'message' => 'Project deleted']);
        exit;
    }

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

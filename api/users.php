<?php
// MVS ICT & Telecom - User Management API (Admin Superuser Only)

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../auth.php';
require_once __DIR__ . '/../db.php';

// Strict Admin Gate: Only super administrators can access this endpoint
$currentUser = Auth::requireLogin(['admin']);
$pdo = Database::getConnection();

$method = $_SERVER['REQUEST_METHOD'];

// Helper to read JSON or Form input
$rawInput = file_get_contents('php://input');
$inputData = json_decode($rawInput, true);
if (!is_array($inputData)) {
    $inputData = $_POST;
}

$action = $_GET['action'] ?? ($inputData['action'] ?? '');

try {
    if ($method === 'GET') {
        // List all users
        $stmt = $pdo->query("SELECT id, username, name, email, role, status, created_at FROM users ORDER BY id ASC");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            'success' => true,
            'users' => $users,
            'current_user_id' => $currentUser['id']
        ]);
        exit;
    }

    if ($method === 'POST') {
        if ($action === 'create') {
            $username = trim($inputData['username'] ?? '');
            $name = trim($inputData['name'] ?? '');
            $email = trim($inputData['email'] ?? '');
            $role = trim($inputData['role'] ?? '');
            $password = trim($inputData['password'] ?? '');
            $status = trim($inputData['status'] ?? 'active');

            if ($username === '' || $name === '' || $password === '' || $role === '') {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Username, Full Name, Role and Password are required.']);
                exit;
            }

            $allowedRoles = ['sales', 'pm', 'executive', 'admin'];
            if (!in_array($role, $allowedRoles, true)) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Invalid role specified.']);
                exit;
            }

            if (!in_array($status, ['active', 'inactive'], true)) {
                $status = 'active';
            }

            if (strlen($password) < 6) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Password must be at least 6 characters.']);
                exit;
            }

            // Check if username already exists
            $chk = $pdo->prepare("SELECT COUNT(*) FROM users WHERE LOWER(username) = LOWER(?)");
            $chk->execute([$username]);
            if ($chk->fetchColumn() > 0) {
                http_response_code(409);
                echo json_encode(['success' => false, 'message' => 'Username already exists. Please choose another username.']);
                exit;
            }

            $hash = password_hash($password, PASSWORD_DEFAULT);
            $ins = $pdo->prepare("
                INSERT INTO users (username, name, email, password_hash, role, status)
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $ins->execute([$username, $name, $email, $hash, $role, $status]);
            $newId = (int)$pdo->lastInsertId();

            echo json_encode([
                'success' => true,
                'message' => 'User created successfully.',
                'user' => [
                    'id' => $newId,
                    'username' => $username,
                    'name' => $name,
                    'email' => $email,
                    'role' => $role,
                    'status' => $status
                ]
            ]);
            exit;
        }

        if ($action === 'update') {
            $id = (int)($inputData['id'] ?? 0);
            $name = trim($inputData['name'] ?? '');
            $email = trim($inputData['email'] ?? '');
            $role = trim($inputData['role'] ?? '');
            $status = trim($inputData['status'] ?? 'active');

            if ($id <= 0 || $name === '' || $role === '') {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Valid User ID, Name, and Role are required.']);
                exit;
            }

            $allowedRoles = ['sales', 'pm', 'executive', 'admin'];
            if (!in_array($role, $allowedRoles, true)) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Invalid role specified.']);
                exit;
            }

            // Prevent self-demotion or self-deactivation
            if ($id === (int)$currentUser['id']) {
                if ($role !== 'admin') {
                    http_response_code(400);
                    echo json_encode(['success' => false, 'message' => 'You cannot change your own admin role.']);
                    exit;
                }
                if ($status !== 'active') {
                    http_response_code(400);
                    echo json_encode(['success' => false, 'message' => 'You cannot deactivate your own account.']);
                    exit;
                }
            }

            $upd = $pdo->prepare("UPDATE users SET name = ?, email = ?, role = ?, status = ? WHERE id = ?");
            $upd->execute([$name, $email, $role, $status, $id]);

            echo json_encode([
                'success' => true,
                'message' => 'User updated successfully.'
            ]);
            exit;
        }

        if ($action === 'reset_password') {
            $id = (int)($inputData['id'] ?? 0);
            $newPassword = trim($inputData['new_password'] ?? '');

            if ($id <= 0 || strlen($newPassword) < 6) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'New password must be at least 6 characters.']);
                exit;
            }

            $hash = password_hash($newPassword, PASSWORD_DEFAULT);
            $upd = $pdo->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
            $upd->execute([$hash, $id]);

            echo json_encode([
                'success' => true,
                'message' => 'Password reset successfully.'
            ]);
            exit;
        }

        if ($action === 'change_admin_password') {
            $currentPassword = trim($inputData['current_password'] ?? '');
            $newPassword = trim($inputData['new_password'] ?? '');

            if (strlen($newPassword) < 6) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'New password must be at least 6 characters.']);
                exit;
            }

            // Verify current password
            $stmt = $pdo->prepare("SELECT password_hash FROM users WHERE id = ?");
            $stmt->execute([$currentUser['id']]);
            $existingHash = $stmt->fetchColumn();

            if (!$existingHash || !password_verify($currentPassword, $existingHash)) {
                http_response_code(401);
                echo json_encode(['success' => false, 'message' => 'Current password is incorrect.']);
                exit;
            }

            $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
            $upd = $pdo->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
            $upd->execute([$newHash, $currentUser['id']]);

            echo json_encode([
                'success' => true,
                'message' => 'Your admin password has been changed successfully.'
            ]);
            exit;
        }

        if ($action === 'delete') {
            $id = (int)($inputData['id'] ?? 0);

            if ($id <= 0) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Invalid user ID.']);
                exit;
            }

            if ($id === (int)$currentUser['id']) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Security Error: You cannot delete your own admin account.']);
                exit;
            }

            $del = $pdo->prepare("DELETE FROM users WHERE id = ?");
            $del->execute([$id]);

            echo json_encode([
                'success' => true,
                'message' => 'User deleted successfully.'
            ]);
            exit;
        }

        if ($action === 'toggle_status') {
            $id = (int)($inputData['id'] ?? 0);

            if ($id <= 0) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Invalid user ID.']);
                exit;
            }

            if ($id === (int)$currentUser['id']) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'You cannot deactivate your own account.']);
                exit;
            }

            $stmt = $pdo->prepare("SELECT status FROM users WHERE id = ?");
            $stmt->execute([$id]);
            $currentStatus = $stmt->fetchColumn();

            if (!$currentStatus) {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'User not found.']);
                exit;
            }

            $newStatus = ($currentStatus === 'active') ? 'inactive' : 'active';
            $upd = $pdo->prepare("UPDATE users SET status = ? WHERE id = ?");
            $upd->execute([$newStatus, $id]);

            echo json_encode([
                'success' => true,
                'status' => $newStatus,
                'message' => 'User status changed to ' . ucfirst($newStatus) . '.'
            ]);
            exit;
        }

        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid action specified.']);
        exit;
    }

    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Internal server error: ' . $e->getMessage()
    ]);
}


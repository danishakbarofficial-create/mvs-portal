<?php
// MVS ICT & Telecom - User Profile & Security API
// Accessible to any authenticated enterprise user (Admin, Executive, Sales, PM)

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../auth.php';
require_once __DIR__ . '/../db.php';

// Enforce login for any active role
$currentUser = Auth::requireLogin();
$userId = (int)$currentUser['id'];
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
    // -------------------------------------------------------------
    // GET: Retrieve Current User Profile
    // -------------------------------------------------------------
    if ($method === 'GET') {
        $stmt = $pdo->prepare("SELECT id, username, name, email, phone, role, status, created_at FROM users WHERE id = ? LIMIT 1");
        $stmt->execute([$userId]);
        $profile = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$profile) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'User profile not found.']);
            exit;
        }

        echo json_encode([
            'success' => true,
            'user' => $profile
        ]);
        exit;
    }

    // -------------------------------------------------------------
    // POST Actions: Update Profile or Change Password
    // -------------------------------------------------------------
    if ($method === 'POST') {
        if ($action === 'update_profile') {
            $name = trim($inputData['name'] ?? '');
            $email = trim($inputData['email'] ?? '');
            $phone = trim($inputData['phone'] ?? '');

            if ($name === '') {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Full Name is required.']);
                exit;
            }

            // Check if email already used by another user
            if ($email !== '') {
                $checkStmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ? LIMIT 1");
                $checkStmt->execute([$email, $userId]);
                if ($checkStmt->fetch()) {
                    http_response_code(409);
                    echo json_encode(['success' => false, 'message' => 'This email is already associated with another account.']);
                    exit;
                }
            }

            // Update database
            $updateStmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, phone = ? WHERE id = ?");
            $updateStmt->execute([$name, $email, $phone, $userId]);

            // Sync active session
            $_SESSION['name'] = $name;
            $_SESSION['email'] = $email;
            $_SESSION['phone'] = $phone;

            echo json_encode([
                'success' => true,
                'message' => 'Profile details updated successfully.',
                'user' => [
                    'id' => $userId,
                    'username' => $currentUser['username'],
                    'name' => $name,
                    'email' => $email,
                    'phone' => $phone,
                    'role' => $currentUser['role']
                ]
            ]);
            exit;
        }

        if ($action === 'change_password') {
            $currentPassword = (string)($inputData['current_password'] ?? '');
            $newPassword = (string)($inputData['new_password'] ?? '');
            $confirmPassword = (string)($inputData['confirm_password'] ?? '');

            if ($currentPassword === '' || $newPassword === '') {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Current password and new password are required.']);
                exit;
            }

            if ($newPassword !== $confirmPassword) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'New password and confirmation password do not match.']);
                exit;
            }

            if (strlen($newPassword) < 6) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'New password must be at least 6 characters long.']);
                exit;
            }

            // Fetch stored password hash
            $stmt = $pdo->prepare("SELECT password_hash FROM users WHERE id = ? LIMIT 1");
            $stmt->execute([$userId]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$row || !password_verify($currentPassword, $row['password_hash'])) {
                http_response_code(401);
                echo json_encode(['success' => false, 'message' => 'Incorrect current password.']);
                exit;
            }

            // Update password hash
            $newHash = password_hash($newPassword, PASSWORD_BCRYPT);
            $upStmt = $pdo->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
            $upStmt->execute([$newHash, $userId]);

            echo json_encode([
                'success' => true,
                'message' => 'Password changed successfully.'
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
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}


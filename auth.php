<?php
// MVS ICT & Telecom - Authentication & Role-Based Access Control

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/db.php';

class Auth {
    public static function getCurrentUser(): ?array {
        if (!isset($_SESSION['user_id'])) {
            return null;
        }
        return [
            'id' => $_SESSION['user_id'],
            'username' => $_SESSION['username'],
            'name' => $_SESSION['name'],
            'email' => $_SESSION['email'] ?? '',
            'phone' => $_SESSION['phone'] ?? '',
            'role' => $_SESSION['role']
        ];
    }

    public static function isLoggedIn(): bool {
        return isset($_SESSION['user_id']);
    }

    public static function getRole(): ?string {
        return $_SESSION['role'] ?? null;
    }

    public static function isExecutive(): bool {
        return self::getRole() === 'executive';
    }

    public static function isSales(): bool {
        return self::getRole() === 'sales';
    }

    public static function isPM(): bool {
        return self::getRole() === 'pm';
    }

    public static function isAdmin(): bool {
        return self::getRole() === 'admin';
    }

    public static function login(string $username, string $password): array {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE (username = ? OR email = ?) AND status = 'active' LIMIT 1");
        $stmt->execute([$username, $username]);
        $user = $stmt->fetch();

        if (!$user) {
            return ['success' => false, 'message' => 'User not found or inactive'];
        }

        if (!password_verify($password, $user['password_hash'])) {
            return ['success' => false, 'message' => 'Invalid password'];
        }

        // Set session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['phone'] = $user['phone'] ?? '';
        $_SESSION['role'] = $user['role'];

        return [
            'success' => true,
            'user' => [
                'id' => $user['id'],
                'username' => $user['username'],
                'name' => $user['name'],
                'role' => $user['role']
            ]
        ];
    }

    public static function logout(): void {
        if (session_status() === PHP_SESSION_ACTIVE) {
            $_SESSION = [];
            session_destroy();
        }
    }

    public static function requireLogin(array $allowedRoles = []): array {
        $user = self::getCurrentUser();
        $isCli = (php_sapi_name() === 'cli');
        $isApi = $isCli || (!empty($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json'))
              || (!empty($_SERVER['REQUEST_URI']) && str_contains($_SERVER['REQUEST_URI'], '/api/'));

        if (!$user) {
            if ($isApi) {
                if (!headers_sent()) {
                    header('Content-Type: application/json');
                    http_response_code(401);
                }
                echo json_encode(['error' => 'Authentication required', 'redirect' => 'login.php']);
                exit;
            }
            if (!headers_sent()) {
                header('Location: login.php');
            }
            exit;
        }

        if (!empty($allowedRoles)) {
            $isAllowed = in_array($user['role'], $allowedRoles, true) || ($user['role'] === 'admin');
            if (!$isAllowed) {
                if ($isApi) {
                    if (!headers_sent()) {
                        header('Content-Type: application/json');
                        http_response_code(403);
                    }
                    echo json_encode(['error' => 'Forbidden: Access denied for role ' . $user['role']]);
                    exit;
                }
                if (!headers_sent()) {
                    if ($user['role'] === 'sales') {
                        header('Location: sales_portal.php');
                        exit;
                    } elseif ($user['role'] === 'pm') {
                        header('Location: pm_portal.php');
                        exit;
                    } elseif ($user['role'] === 'executive') {
                        header('Location: executive_portal.php');
                        exit;
                    } elseif ($user['role'] === 'admin') {
                        header('Location: admin.php');
                        exit;
                    }
                }
                http_response_code(403);
                die('Access Denied: You do not have permission to view this section.');
            }
        }

        return $user;
    }
}

<?php
// MVS ICT & Telecom - Central Role Router
require_once __DIR__ . '/auth.php';

$user = Auth::getCurrentUser();

if (!$user) {
    header('Location: login.php');
    exit;
}

// Redirect according to authenticated role
if ($user['role'] === 'admin') {
    header('Location: admin.php');
} elseif ($user['role'] === 'sales') {
    header('Location: sales_portal.php');
} elseif ($user['role'] === 'pm') {
    header('Location: pm_portal.php');
} else {
    header('Location: executive_portal.php');
}
exit;

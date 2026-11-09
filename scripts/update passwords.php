<?php
$hash = password_hash('mvs123', PASSWORD_BCRYPT);
echo "Generated hash: " . $hash . "\n";
echo "Verify test: " . (password_verify('mvs123', $hash) ? 'OK' : 'FAIL') . "\n";

$dbPath = 'C:/laragon/www/MVS Project Pulse/data/mvs_sales.sqlite';
$pdo = new PDO('sqlite:' . $dbPath);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Update all users' password to mvs123
$stmt = $pdo->prepare("UPDATE users SET password_hash = ? WHERE status = 'active'");
$stmt->execute([$hash]);

echo "Updated rows: " . $stmt->rowCount() . "\n";

// Verify all users in database
$users = $pdo->query("SELECT id, username, name, email, role, password_hash FROM users")->fetchAll(PDO::FETCH_ASSOC);
foreach ($users as $u) {
    $ok = password_verify('mvs123', $u['password_hash']) ? 'VALID' : 'INVALID';
    echo "User: {$u['username']} ({$u['email']}) -> Password 'mvs123' is $ok\n";
}

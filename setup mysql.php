<?php
// MVS Project Pulse - Automated MySQL Database Setup for Hostinger
header('Content-Type: text/html; charset=utf-8');

$host = 'localhost';
$dbName = 'u370632335_mvs_portal';
$dbUser = 'u370632335_portal_user';
$dbPass = 'Mvs@123$';

$status = '';
$logs = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' || isset($_GET['auto'])) {
    try {
        $pdo = new PDO("mysql:host={$host};dbname={$dbName};charset=utf8mb4", $dbUser, $dbPass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
        $logs[] = "Successfully connected to MySQL database: <strong>{$dbName}</strong>";

        // 1. Users Table
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(100) UNIQUE NOT NULL,
                name VARCHAR(255) NOT NULL,
                email VARCHAR(255),
                password_hash VARCHAR(255) NOT NULL,
                role ENUM('sales', 'pm', 'executive') NOT NULL,
                status VARCHAR(50) DEFAULT 'active',
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ");
        $logs[] = "Table <code>users</code> initialized.";

        // Default password hash for 'mvs123'
        $defaultPwHash = password_hash('mvs123', PASSWORD_BCRYPT);

        $users = [
            ['asayil', 'Asayil', 'asayil@mvsksa.com', $defaultPwHash, 'sales'],
            ['nasir', 'Nasir', 'nasir@mvsksa.com', $defaultPwHash, 'sales'],
            ['ahmed', 'Ahmed', 'ahmed@mvsksa.com', $defaultPwHash, 'sales'],
            ['wael', 'Wael', 'wael@mvsksa.com', $defaultPwHash, 'sales'],
            ['shahbaz', 'Shahbaz', 'shahbaz@mvsksa.com', $defaultPwHash, 'sales'],
            ['farooq', 'Muhammad Farooq', 'farooq@mvsksa.com', $defaultPwHash, 'pm'],
            ['tahir', 'Tahir Anjum', 'tahir@mvsksa.com', $defaultPwHash, 'pm'],
            ['adrian', 'Adrian Kim Mallilin', 'adrian@mvsksa.com', $defaultPwHash, 'pm'],
            ['executive', 'Executive Management', 'management@mvsksa.com', $defaultPwHash, 'executive'],
            ['admin', 'System Admin', 'admin@mvsksa.com', $defaultPwHash, 'executive']
        ];

        $stmtUser = $pdo->prepare("
            INSERT INTO users (username, name, email, password_hash, role)
            VALUES (?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE name=VALUES(name), email=VALUES(email), role=VALUES(role)
        ");
        foreach ($users as $u) {
            $stmtUser->execute($u);
        }
        $logs[] = "Default team accounts seeded (All initial passwords: <code>mvs123</code>).";

        // 2. v4 Projects Table (Exact 26 columns)
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS v4_projects (
                id INT AUTO_INCREMENT PRIMARY KEY,
                row_no INT,
                project_manager VARCHAR(255),
                project_name VARCHAR(255),
                customer VARCHAR(255),
                po_status VARCHAR(100),
                po_no VARCHAR(100),
                po_date VARCHAR(50),
                total_po_value DOUBLE DEFAULT 0,
                expected_closing VARCHAR(50),
                months_to_close INT,
                milestone_no VARCHAR(100),
                milestone_desc TEXT,
                milestone_value DOUBLE DEFAULT 0,
                milestone_status VARCHAR(100),
                technical_report VARCHAR(100),
                invoice_status VARCHAR(100),
                invoice_no VARCHAR(100),
                invoice_date VARCHAR(50),
                invoice_amount DOUBLE DEFAULT 0,
                collection_date VARCHAR(50),
                collected_amount DOUBLE DEFAULT 0,
                collection_status VARCHAR(100),
                pending_collection DOUBLE DEFAULT 0,
                to_be_invoiced DOUBLE DEFAULT 0,
                notes TEXT,
                additional_notes TEXT,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ");
        $logs[] = "Table <code>v4_projects</code> (26 columns) initialized.";

        // 3. v4 Pipeline Table (Exact 22 columns)
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS v4_pipeline (
                id INT AUTO_INCREMENT PRIMARY KEY,
                row_no INT,
                sales_person VARCHAR(255),
                project_name VARCHAR(255),
                client VARCHAR(255),
                main_contractor VARCHAR(255),
                consultant VARCHAR(255),
                scope_of_work TEXT,
                date_received VARCHAR(50),
                submission_date VARCHAR(50),
                quotation_no VARCHAR(100),
                quotation_status VARCHAR(100),
                total_sar DOUBLE DEFAULT 0,
                expected_closing VARCHAR(50),
                quotation_valid_until VARCHAR(50),
                contact_person VARCHAR(255),
                contact_details VARCHAR(255),
                decision_status VARCHAR(100),
                missing_info VARCHAR(255),
                next_step TEXT,
                follow_up_date VARCHAR(50),
                notes TEXT,
                additional_notes TEXT,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ");
        $logs[] = "Table <code>v4_pipeline</code> initialized.";

        // 4. v4 Lists Table (Dropdown lookups)
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS v4_lists (
                id INT AUTO_INCREMENT PRIMARY KEY,
                category VARCHAR(100) NOT NULL,
                item_value VARCHAR(255) NOT NULL,
                display_order INT DEFAULT 0
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ");
        $logs[] = "Table <code>v4_lists</code> initialized.";

        // Seed core validation lists
        $listItems = [
            ['Quotation Status', 'Open', 1],
            ['Quotation Status', 'Submitted', 2],
            ['Quotation Status', 'In Negotiation', 3],
            ['Quotation Status', 'Awarded', 4],
            ['Quotation Status', 'Lost', 5],
            ['Decision Status', 'Information Gathering', 1],
            ['Decision Status', 'Under Client Review', 2],
            ['Decision Status', 'Technical Evaluation', 3],
            ['Decision Status', 'Commercial Negotiation', 4],
            ['Decision Status', 'Verbal Award', 5],
            ['PO Status', 'In Progress', 1],
            ['PO Status', 'On Hold', 2],
            ['PO Status', 'Completed', 3],
            ['PO Status', 'Cancelled', 4],
            ['Milestone Status', 'In Progress', 1],
            ['Milestone Status', 'Completed', 2],
            ['Milestone Status', 'Pending', 3],
            ['Milestone Status', 'On Hold', 4],
            ['Invoice Status', 'Not Invoiced', 1],
            ['Invoice Status', 'Partially Invoiced', 2],
            ['Invoice Status', 'Fully Invoiced', 3],
            ['Invoice Status', 'Overdue', 4],
            ['Collection Status', 'Pending', 1],
            ['Collection Status', 'Partially Collected', 2],
            ['Collection Status', 'Fully Collected', 3],
            ['Collection Status', 'Overdue', 4]
        ];

        $stList = $pdo->prepare("INSERT INTO v4_lists (category, item_value, display_order) VALUES (?, ?, ?)");
        $curListCount = $pdo->query("SELECT COUNT(*) FROM v4_lists")->fetchColumn();
        if ($curListCount == 0) {
            foreach ($listItems as $item) {
                $stList->execute($item);
            }
        }

        $status = 'success';
    } catch (PDOException $e) {
        $status = 'error';
        $logs[] = "MySQL Error: " . htmlspecialchars($e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>MVS Project Pulse - MySQL Initialization</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Inter', sans-serif; background: #F8FAFC; color: #0F172A; padding: 40px 20px; }
    .card { max-width: 750px; margin: 0 auto; background: #ffffff; border-radius: 12px; border: 1px solid #E2E8F0; padding: 36px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    h1 { font-size: 1.5rem; color: #0F172A; margin-bottom: 8px; font-weight: 800; }
    p { color: #64748B; font-size: 0.95rem; line-height: 1.5; margin-bottom: 24px; }
    .alert-success { background: #ECFDF5; border: 1px solid #A7F3D0; color: #065F46; padding: 16px 20px; border-radius: 8px; margin-bottom: 24px; font-weight: 600; }
    .alert-error { background: #FEF2F2; border: 1px solid #FECACA; color: #991B1B; padding: 16px 20px; border-radius: 8px; margin-bottom: 24px; font-weight: 600; }
    .btn { display: inline-flex; align-items: center; gap: 8px; font-weight: 600; padding: 14px 28px; border-radius: 8px; cursor: pointer; text-decoration: none; border: none; font-size: 1rem; }
    .btn-primary { background: #0284C7; color: #ffffff; }
    .btn-primary:hover { background: #0369A1; }
    .btn-secondary { background: #F1F5F9; color: #334155; margin-left: 12px; }
    .log-box { background: #0F172A; color: #F8FAFC; padding: 16px 20px; border-radius: 8px; font-family: monospace; font-size: 0.88rem; line-height: 1.6; margin-top: 20px; }
    .log-box strong { color: #38BDF8; }
    .log-box code { color: #4ADE80; }
  </style>
</head>
<body>
  <div class="card">
    <h1>MVS MySQL Database Initialization</h1>
    <p>Target Database: <code><?= htmlspecialchars($dbName) ?></code> on Hostinger.<br>
    Click the button below to connect and initialize all tables, schemas, and default credentials automatically.</p>

    <?php if ($status === 'success'): ?>
      <div class="alert-success">&#10003; MySQL Database Initialized Successfully! Your portal is now permanently backed by Hostinger MySQL.</div>
      <div style="margin-bottom: 24px;">
        <a href="login.php" class="btn btn-primary">&rarr; Go to Portal Login</a>
      </div>
    <?php elseif ($status === 'error'): ?>
      <div class="alert-error">&#10007; Connection Failed. Please ensure you clicked "Create" in Hostinger hPanel first.</div>
    <?php endif; ?>

    <form method="POST">
      <button type="submit" class="btn btn-primary">&#9881; Initialize MySQL Database Now</button>
      <a href="login.php" class="btn btn-secondary">Cancel</a>
    </form>

    <?php if (!empty($logs)): ?>
      <div class="log-box">
        <?php foreach ($logs as $log): ?>
          <div>&gt; <?= $log ?></div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</body>
</html>

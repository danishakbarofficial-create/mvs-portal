<?php
require_once __DIR__ . '/auth.php';

$error = '';
if (Auth::isLoggedIn()) {
    $role = Auth::getRole();
    if ($role === 'sales') {
        header('Location: sales_portal.php');
    } elseif ($role === 'pm') {
        header('Location: pm_portal.php');
    } else {
        header('Location: executive_portal.php');
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $res = Auth::login($username, $password);
    if ($res['success']) {
        $role = $res['user']['role'];
        if ($role === 'sales') {
            header('Location: sales_portal.php');
        } elseif ($role === 'pm') {
            header('Location: pm_portal.php');
        } else {
            header('Location: executive_portal.php');
        }
        exit;
    } else {
        $error = $res['message'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign In &bull; MVS ICT &amp; Telecom</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600&display=swap" rel="stylesheet">
  <style>
    :root {
      --color-primary: #172B4D;
      --color-primary-hover: #0E1E38;
      --color-accent: #2878D0;
      --color-accent-hover: #1E65B5;
      --color-bg-canvas: #F5F7FA;
      --color-surface: #FFFFFF;
      --color-text-primary: #172033;
      --color-text-secondary: #64748B;
      --color-border: #E2E8F0;
      --color-danger-bg: #FEF2F2;
      --color-danger-border: #FECACA;
      --color-danger-text: #991B1B;
      --radius-md: 8px;
      --radius-lg: 12px;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }

    body {
      background-color: var(--color-bg-canvas);
      background-image: 
        radial-gradient(at 0% 0%, rgba(40, 120, 208, 0.05) 0px, transparent 50%),
        radial-gradient(at 100% 100%, rgba(23, 43, 77, 0.04) 0px, transparent 50%);
      color: var(--color-text-primary);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 24px;
      -webkit-font-smoothing: antialiased;
    }

    .auth-wrapper {
      width: 100%;
      max-width: 440px;
    }

    .brand-header {
      text-align: center;
      margin-bottom: 28px;
    }

    .brand-badge {
      display: inline-flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 14px;
    }

    .brand-icon {
      width: 40px;
      height: 40px;
      background: linear-gradient(135deg, #2878D0 0%, #172B4D 100%);
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #FFFFFF;
      font-weight: 800;
      font-size: 1.1rem;
      box-shadow: 0 4px 10px rgba(40, 120, 208, 0.25);
    }

    .brand-logo-img {
      max-height: 102px;
      height: 102px;
      width: auto;
      object-fit: contain;
      filter: drop-shadow(0 4px 12px rgba(186, 150, 49, 0.22));
    }

    .brand-header h1 {
      font-size: 1.55rem;
      font-weight: 800;
      color: var(--color-text-primary);
      letter-spacing: -0.02em;
      margin-top: 10px;
    }

    .auth-card {
      background-color: var(--color-surface);
      border: 1px solid var(--color-border);
      border-radius: var(--radius-lg);
      padding: 32px 30px;
      box-shadow: 0 4px 6px -1px rgba(16, 24, 40, 0.05), 0 2px 4px -2px rgba(16, 24, 40, 0.05);
    }

    .alert-error {
      background-color: var(--color-danger-bg);
      border: 1px solid var(--color-danger-border);
      color: var(--color-danger-text);
      padding: 10px 14px;
      border-radius: var(--radius-md);
      font-size: 0.82rem;
      font-weight: 500;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .form-group {
      margin-bottom: 18px;
    }

    .form-label {
      display: block;
      font-size: 0.74rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.04em;
      color: var(--color-text-secondary);
      margin-bottom: 7px;
    }

    .form-input {
      width: 100%;
      background-color: #FFFFFF;
      border: 1px solid var(--color-border);
      border-radius: var(--radius-md);
      padding: 10px 13px;
      color: var(--color-text-primary);
      font-size: 0.9rem;
      outline: none;
      transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }

    .form-input:focus {
      border-color: var(--color-accent);
      box-shadow: 0 0 0 3px rgba(40, 120, 208, 0.15);
    }

    .btn-submit {
      width: 100%;
      background-color: #172B4D;
      color: #FFFFFF !important;
      border: 1px solid #172B4D;
      padding: 12px 16px;
      border-radius: var(--radius-md);
      font-size: 0.9rem;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.2s ease;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      margin-top: 22px;
      box-shadow: 0 1px 3px rgba(16, 24, 40, 0.1);
    }

    .btn-submit:hover {
      background-color: #0E1E38 !important;
      border-color: #0E1E38 !important;
      color: #FFFFFF !important;
      box-shadow: 0 4px 12px rgba(14, 30, 56, 0.25);
      transform: translateY(-1px);
    }

    .btn-submit:active {
      transform: translateY(0);
    }

    .demo-accounts-box {
      margin-top: 24px;
      padding-top: 20px;
      border-top: 1px solid var(--color-border);
    }

    .demo-accounts-title {
      font-size: 0.68rem;
      text-transform: uppercase;
      font-weight: 700;
      letter-spacing: 0.06em;
      color: var(--color-text-secondary);
      text-align: center;
      margin-bottom: 12px;
    }

    .quick-login-grid {
      display: grid;
      grid-template-columns: 1fr 1fr 1fr;
      gap: 8px;
    }

    .role-btn {
      background-color: #F8FAFC;
      border: 1px solid var(--color-border);
      color: var(--color-text-primary);
      padding: 9px 8px;
      border-radius: var(--radius-md);
      font-size: 0.76rem;
      font-weight: 600;
      cursor: pointer;
      text-align: center;
      transition: all 0.15s ease;
    }

    .role-btn:hover {
      background-color: #EFF6FF;
      border-color: #BFDBFE;
      color: var(--color-accent);
    }

    .role-btn span {
      display: block;
      font-size: 0.65rem;
      color: var(--color-text-secondary);
      font-weight: 400;
      margin-top: 2px;
    }

    .auth-footer {
      text-align: center;
      font-size: 0.72rem;
      color: var(--color-text-secondary);
      margin-top: 20px;
    }

    .auth-footer code {
      font-family: 'JetBrains Mono', monospace;
      color: var(--color-primary);
      background-color: #E2E8F0;
      padding: 1px 5px;
      border-radius: 4px;
      font-size: 0.72rem;
    }
  </style>
</head>
<body>

  <div class="auth-wrapper">
    <div class="brand-header">
      <div class="brand-badge">
        <img src="mvs_logo_full.png" alt="MVS Logo" class="brand-logo-img" onerror="this.outerHTML='<div class=\'brand-icon\'>MVS</div>'">
      </div>
      <h1>MVS ICT &amp; Telecom</h1>
    </div>

    <div class="auth-card">
      <?php if (!empty($error)): ?>
        <div class="alert-error">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10"></circle>
            <line x1="12" y1="8" x2="12" y2="12"></line>
            <line x1="12" y1="16" x2="12.01" y2="16"></line>
          </svg>
          <span><?= htmlspecialchars($error) ?></span>
        </div>
      <?php endif; ?>

      <form method="POST" action="login.php" id="loginForm">
        <div class="form-group">
          <label class="form-label" for="username">Corporate ID / Username</label>
          <input type="text" name="username" id="username" class="form-input" placeholder="e.g. executive, asayil, farooq" required autofocus>
        </div>

        <div class="form-group">
          <label class="form-label" for="password">Password</label>
          <input type="password" name="password" id="password" class="form-input" placeholder="••••••••" required>
        </div>

        <button type="submit" class="btn-submit">
          <span>Sign In to Enterprise Workspace</span>
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="5" y1="12" x2="19" y2="12"></line>
            <polyline points="12 5 19 12 12 19"></polyline>
          </svg>
        </button>
      </form>
    </div>

    <div class="auth-footer">
      Modern Vision Solutions Ltd &bull; Enterprise Secure Workspace
    </div>
  </div>
</body>
</html>

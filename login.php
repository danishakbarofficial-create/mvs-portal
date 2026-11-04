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
      --color-navy-dark: #071324;
      --color-navy: #16365C;
      --color-navy-light: #1E4678;
      --color-gold: #C5A869;
      --color-gold-light: #E0C78A;
      --color-accent: #2563EB;
      --color-text-primary: #0F172A;
      --color-text-secondary: #64748B;
      --color-border: #E2E8F0;
      --color-border-focus: #3B82F6;
      --radius-sm: 6px;
      --radius-md: 10px;
      --radius-lg: 16px;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }

    body {
      background-color: #071324;
      color: var(--color-text-primary);
      min-height: 100vh;
      display: flex;
      -webkit-font-smoothing: antialiased;
    }

    .split-layout {
      display: flex;
      width: 100%;
      min-height: 100vh;
    }

    /* ==========================================================================
       LEFT SIDE: Corporate Hero & Intelligence Showcase
       ========================================================================== */
    .hero-side {
      flex: 1.15;
      background: 
        radial-gradient(circle at 15% 20%, rgba(197, 168, 105, 0.12) 0%, transparent 45%),
        radial-gradient(circle at 85% 85%, rgba(37, 99, 235, 0.15) 0%, transparent 50%),
        linear-gradient(145deg, #071324 0%, #0D2038 50%, #16365C 100%);
      color: #FFFFFF;
      padding: 56px 64px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      position: relative;
      overflow: hidden;
      border-right: 1px solid rgba(255, 255, 255, 0.08);
    }

    /* Subtle grid background texture */
    .hero-side::before {
      content: '';
      position: absolute;
      inset: 0;
      background-image: 
        linear-gradient(to right, rgba(255, 255, 255, 0.03) 1px, transparent 1px),
        linear-gradient(to bottom, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
      background-size: 40px 40px;
      pointer-events: none;
    }

    .hero-content {
      position: relative;
      z-index: 2;
    }

    .hero-top-badge {
      display: inline-flex;
      align-items: center;
      gap: 10px;
      background: rgba(255, 255, 255, 0.06);
      border: 1px solid rgba(197, 168, 105, 0.3);
      padding: 6px 14px;
      border-radius: 999px;
      margin-bottom: 32px;
      backdrop-filter: blur(8px);
    }

    .hero-top-badge-dot {
      width: 7px;
      height: 7px;
      border-radius: 50%;
      background-color: var(--color-gold);
      box-shadow: 0 0 10px var(--color-gold);
    }

    .hero-top-badge-text {
      font-size: 0.73rem;
      font-weight: 700;
      letter-spacing: 0.08em;
      text-transform: uppercase;
      color: var(--color-gold-light);
    }

    .hero-brand-row {
      display: flex;
      align-items: center;
      gap: 18px;
      margin-bottom: 28px;
    }

    .hero-logo-img {
      height: 76px;
      width: auto;
      object-fit: contain;
      filter: drop-shadow(0 4px 14px rgba(197, 168, 105, 0.3));
    }

    .hero-title {
      font-size: 2.35rem;
      font-weight: 800;
      color: #FFFFFF;
      letter-spacing: -0.03em;
      line-height: 1.15;
      margin-bottom: 12px;
    }

    .hero-title span {
      background: linear-gradient(135deg, #FFFFFF 30%, var(--color-gold-light) 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    .hero-subtitle {
      font-size: 1.02rem;
      color: #94A3B8;
      line-height: 1.55;
      max-width: 520px;
      margin-bottom: 40px;
    }

    /* 3 Feature Highlights */
    .hero-features {
      display: flex;
      flex-direction: column;
      gap: 14px;
      max-width: 540px;
    }

    .feature-item {
      display: flex;
      align-items: flex-start;
      gap: 14px;
      background: rgba(255, 255, 255, 0.03);
      border: 1px solid rgba(255, 255, 255, 0.07);
      padding: 16px 18px;
      border-radius: var(--radius-md);
      backdrop-filter: blur(10px);
      transition: all 0.25s ease;
    }

    .feature-item:hover {
      background: rgba(255, 255, 255, 0.06);
      border-color: rgba(197, 168, 105, 0.35);
      transform: translateX(4px);
    }

    .feature-icon-box {
      width: 38px;
      height: 38px;
      border-radius: 9px;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }

    .feature-icon-blue { background: rgba(37, 99, 235, 0.2); color: #60A5FA; border: 1px solid rgba(37, 99, 235, 0.35); }
    .feature-icon-green { background: rgba(16, 185, 129, 0.2); color: #34D399; border: 1px solid rgba(16, 185, 129, 0.35); }
    .feature-icon-gold { background: rgba(197, 168, 105, 0.2); color: var(--color-gold-light); border: 1px solid rgba(197, 168, 105, 0.35); }

    .feature-text-box h4 {
      font-size: 0.9rem;
      font-weight: 700;
      color: #F8FAFC;
      margin-bottom: 3px;
    }

    .feature-text-box p {
      font-size: 0.79rem;
      color: #94A3B8;
      line-height: 1.45;
    }

    .hero-footer {
      position: relative;
      z-index: 2;
      padding-top: 36px;
      border-top: 1px solid rgba(255, 255, 255, 0.08);
      display: flex;
      align-items: center;
      justify-content: space-between;
      color: #64748B;
      font-size: 0.75rem;
    }

    .hero-footer-meta {
      display: flex;
      align-items: center;
      gap: 16px;
    }

    .hero-footer-meta span {
      display: flex;
      align-items: center;
      gap: 6px;
    }

    /* ==========================================================================
       RIGHT SIDE: Clean Enterprise Sign In Box
       ========================================================================== */
    .form-side {
      flex: 0.85;
      background-color: #FFFFFF;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 48px 40px;
      position: relative;
    }

    .form-container {
      width: 100%;
      max-width: 420px;
    }

    .form-header {
      margin-bottom: 30px;
    }

    .mobile-logo-wrap {
      display: none;
      margin-bottom: 20px;
      text-align: center;
    }

    .mobile-logo-wrap img {
      height: 60px;
      width: auto;
    }

    .form-header h2 {
      font-size: 1.75rem;
      font-weight: 800;
      color: var(--color-navy);
      letter-spacing: -0.02em;
      margin-bottom: 8px;
    }

    .form-header p {
      font-size: 0.88rem;
      color: var(--color-text-secondary);
      line-height: 1.5;
    }

    .alert-error {
      background-color: #FEF2F2;
      border: 1px solid #FECACA;
      color: #991B1B;
      padding: 11px 14px;
      border-radius: var(--radius-sm);
      font-size: 0.83rem;
      font-weight: 500;
      margin-bottom: 22px;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-label-row {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 7px;
    }

    .form-label {
      font-size: 0.75rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.04em;
      color: #334155;
    }

    .form-label-badge {
      font-size: 0.68rem;
      color: #94A3B8;
      font-weight: 500;
    }

    .input-wrapper {
      position: relative;
      display: flex;
      align-items: center;
    }

    .input-icon {
      position: absolute;
      left: 14px;
      color: #94A3B8;
      pointer-events: none;
      transition: color 0.15s ease;
    }

    .form-input {
      width: 100%;
      height: 46px;
      background-color: #F8FAFC;
      border: 1px solid #CBD5E1;
      border-radius: var(--radius-sm);
      padding: 0 44px 0 42px;
      color: #0F172A;
      font-size: 0.92rem;
      outline: none;
      transition: all 0.15s ease;
    }

    .form-input:focus {
      background-color: #FFFFFF;
      border-color: #16365C;
      box-shadow: 0 0 0 3px rgba(22, 54, 92, 0.12);
    }

    .form-input:focus + .input-icon,
    .input-wrapper:focus-within .input-icon {
      color: #16365C;
    }

    .eye-toggle-btn {
      position: absolute;
      right: 12px;
      background: none;
      border: none;
      color: #94A3B8;
      cursor: pointer;
      padding: 6px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 4px;
      transition: color 0.15s ease;
    }

    .eye-toggle-btn:hover {
      color: #0F172A;
    }

    .form-options-row {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-top: 10px;
      margin-bottom: 24px;
      font-size: 0.82rem;
    }

    .checkbox-label {
      display: flex;
      align-items: center;
      gap: 8px;
      color: #475569;
      cursor: pointer;
      user-select: none;
    }

    .checkbox-label input {
      accent-color: var(--color-navy);
      cursor: pointer;
    }

    .options-note {
      color: #94A3B8;
      font-size: 0.77rem;
    }

    .btn-submit {
      width: 100%;
      height: 48px;
      background-color: #16365C;
      color: #FFFFFF !important;
      border: 1px solid #16365C;
      border-radius: var(--radius-sm);
      font-size: 0.92rem;
      font-weight: 700;
      letter-spacing: 0.01em;
      cursor: pointer;
      transition: all 0.2s ease;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      box-shadow: 0 2px 4px rgba(22, 54, 92, 0.12);
    }

    .btn-submit:hover {
      background-color: #0D223B !important;
      border-color: #0D223B !important;
      transform: translateY(-1px);
      box-shadow: 0 4px 12px rgba(22, 54, 92, 0.25);
    }

    .btn-submit:active {
      transform: translateY(0);
    }

    .form-footer-box {
      margin-top: 36px;
      padding-top: 20px;
      border-top: 1px solid #F1F5F9;
      text-align: center;
    }

    .security-badge {
      display: inline-flex;
      align-items: center;
      gap: 7px;
      font-size: 0.76rem;
      color: #64748B;
      font-weight: 500;
      margin-bottom: 12px;
    }

    .security-badge svg {
      color: #10B981;
    }

    .copyright-text {
      font-size: 0.73rem;
      color: #94A3B8;
    }

    /* ==========================================================================
       RESPONSIVE DESIGN (Tablets & Mobile)
       ========================================================================== */
    @media (max-width: 1024px) {
      .hero-side {
        padding: 40px;
      }
      .hero-title {
        font-size: 1.95rem;
      }
    }

    @media (max-width: 860px) {
      .split-layout {
        flex-direction: column;
      }
      .hero-side {
        padding: 32px 24px;
        flex: none;
      }
      .hero-subtitle,
      .hero-features,
      .hero-footer {
        display: none;
      }
      .hero-brand-row {
        margin-bottom: 0;
      }
      .hero-logo-img {
        height: 52px;
      }
      .hero-title {
        font-size: 1.5rem;
        margin-bottom: 0;
      }
      .hero-top-badge {
        margin-bottom: 16px;
      }
      .form-side {
        padding: 36px 20px;
        flex: 1;
      }
    }
  </style>
</head>
<body>

  <div class="split-layout">
    <!-- LEFT PANEL: Corporate Showcase & System Highlights -->
    <div class="hero-side">
      <div class="hero-content">
        <div class="hero-top-badge">
          <span class="hero-top-badge-dot"></span>
          <span class="hero-top-badge-text">Enterprise ICT &bull; Kingdom of Saudi Arabia</span>
        </div>

        <div class="hero-brand-row">
          <img src="mvs_logo_full.png" alt="MVS & Partners Logo" class="hero-logo-img">
        </div>

        <h1 class="hero-title">
          MVS <span>ICT &amp; Telecom</span>
        </h1>
        <p class="hero-subtitle">
          Executive overview of sales pipeline, awarded PO contracts, milestone deliveries, invoicing, and cash collections.
        </p>

        <!-- 3 Feature Pillars (Exact Excel Dashboard Scope) -->
        <div class="hero-features">
          <div class="feature-item">
            <div class="feature-icon-box feature-icon-blue">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline>
                <polyline points="16 7 22 7 22 13"></polyline>
              </svg>
            </div>
            <div class="feature-text-box">
              <h4>Sales &amp; Pipeline Dashboard</h4>
              <p>Overview of pre-award sales pipeline, bid submissions, win rates, and expected closings.</p>
            </div>
          </div>

          <div class="feature-item">
            <div class="feature-icon-box feature-icon-green">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
                <line x1="12" y1="11" x2="12" y2="17"></line>
                <line x1="9" y1="14" x2="15" y2="14"></line>
              </svg>
            </div>
            <div class="feature-text-box">
              <h4>Projects, Invoicing &amp; Financial Health</h4>
              <p>Operations &amp; cash collection dashboard tracking awarded PO contracts, milestone billings, and cash flow.</p>
            </div>
          </div>

          <div class="feature-item">
            <div class="feature-icon-box feature-icon-gold">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="6" x2="12" y2="12"></line>
                <line x1="12" y1="12" x2="16" y2="16"></line>
              </svg>
            </div>
            <div class="feature-text-box">
              <h4>Pipeline &amp; Milestone Registers</h4>
              <p>Real-time registers tracking active client inquiries, tenders, milestone deliverables, and payments.</p>
            </div>
          </div>
        </div>
      </div>

      <div class="hero-footer">
        <div class="hero-footer-meta">
          <span>
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
              <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
            </svg>
            256-Bit TLS Secured
          </span>
          <span>&bull;</span>
          <span>Role-Scoped RBAC Governance</span>
        </div>
        <div>
          <span>Enterprise Release &bull; v4</span>
        </div>
      </div>
    </div>

    <!-- RIGHT PANEL: Enterprise Sign In Box -->
    <div class="form-side">
      <div class="form-container">
        <div class="mobile-logo-wrap">
          <img src="mvs_logo_full.png" alt="MVS & Partners Logo">
        </div>

        <div class="form-header">
          <h2>Sign In</h2>
          <p>Please enter your corporate credentials to access your designated workspace.</p>
        </div>

        <?php if (!empty($error)): ?>
          <div class="alert-error">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="12" cy="12" r="10"></circle>
              <line x1="12" y1="8" x2="12" y2="12"></line>
              <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
            <span><?= htmlspecialchars($error) ?></span>
          </div>
        <?php endif; ?>

        <form method="POST" action="login.php" id="loginForm">
          <div class="form-group">
            <div class="form-label-row">
              <label class="form-label" for="username">Corporate ID / Username</label>
              <span class="form-label-badge">Required</span>
            </div>
            <div class="input-wrapper">
              <svg class="input-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                <circle cx="12" cy="7" r="4"></circle>
              </svg>
              <input 
                type="text" 
                name="username" 
                id="username" 
                class="form-input" 
                placeholder="e.g. executive, asayil, farooq" 
                required 
                autofocus
                autocomplete="username"
              >
            </div>
          </div>

          <div class="form-group">
            <div class="form-label-row">
              <label class="form-label" for="passwordInput">Password</label>
              <span class="form-label-badge">Encrypted</span>
            </div>
            <div class="input-wrapper">
              <svg class="input-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
              </svg>
              <input 
                type="password" 
                name="password" 
                id="passwordInput" 
                class="form-input" 
                placeholder="Enter your corporate password" 
                required
                autocomplete="current-password"
              >
              <button 
                type="button" 
                class="eye-toggle-btn" 
                id="eyeToggleBtn" 
                onclick="togglePassword()" 
                aria-label="Toggle password visibility"
                title="Show / Hide password"
              >
                <!-- Eye Open Icon -->
                <svg id="eyeOpenIcon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                  <circle cx="12" cy="12" r="3"></circle>
                </svg>
                <!-- Eye Off Icon (hidden initially) -->
                <svg id="eyeCloseIcon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none;">
                  <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                  <line x1="1" y1="1" x2="23" y2="23"></line>
                </svg>
              </button>
            </div>
          </div>

          <div class="form-options-row">
            <label class="checkbox-label">
              <input type="checkbox" name="remember" value="1">
              <span>Remember this device</span>
            </label>
            <span class="options-note">Internal corporate access</span>
          </div>

          <button type="submit" class="btn-submit">
            <span>Sign In to Workspace</span>
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
              <line x1="5" y1="12" x2="19" y2="12"></line>
              <polyline points="12 5 19 12 12 19"></polyline>
            </svg>
          </button>
        </form>

        <div class="form-footer-box">
          <div class="security-badge">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
              <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
            </svg>
            <span>Authorized Enterprise Personnel Only</span>
          </div>
          <div class="copyright-text">
            &copy; <?= date('Y') ?> Modern Vision Solutions Ltd &bull; All rights reserved.
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    function togglePassword() {
      const pwd = document.getElementById('passwordInput');
      const openIcon = document.getElementById('eyeOpenIcon');
      const closeIcon = document.getElementById('eyeCloseIcon');
      
      if (pwd.type === 'password') {
        pwd.type = 'text';
        openIcon.style.display = 'none';
        closeIcon.style.display = 'block';
      } else {
        pwd.type = 'password';
        openIcon.style.display = 'block';
        closeIcon.style.display = 'none';
      }
    }
  </script>
</body>
</html>

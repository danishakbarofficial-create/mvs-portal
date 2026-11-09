<?php
require_once __DIR__ . '/auth.php';
$user = Auth::requireLogin(['admin']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Management &bull; MVS ICT &amp; Telecom</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/portal.css?v=<?= time() ?>">
  <style>
    /* Status & Role Badges */
    .role-badge-admin {
      background-color: #F3E8FF;
      color: #6B21A8;
      border: 1px solid #D8B4FE;
      font-weight: 700;
    }
    .role-badge-executive {
      background-color: #E2E8F0;
      color: #1E293B;
      border: 1px solid #CBD5E1;
      font-weight: 600;
    }
    .role-badge-sales {
      background-color: #DBEAFE;
      color: #1D4ED8;
      border: 1px solid #BFDBFE;
      font-weight: 600;
    }
    .role-badge-pm {
      background-color: #D1FAE5;
      color: #047857;
      border: 1px solid #A7F3D0;
      font-weight: 600;
    }

    .status-pill {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 3px 10px;
      border-radius: 9999px;
      font-size: 0.72rem;
      font-weight: 600;
      cursor: pointer;
      user-select: none;
      transition: all 0.15s ease;
      white-space: nowrap;
    }
    .status-pill.active {
      background-color: #ECFDF5;
      color: #065F46;
      border: 1px solid #A7F3D0;
    }
    .status-pill.active:hover {
      background-color: #D1FAE5;
      border-color: #6EE7B7;
    }
    .status-pill.inactive {
      background-color: #F8FAFC;
      color: #64748B;
      border: 1px solid #E2E8F0;
    }
    .status-pill.inactive:hover {
      background-color: #F1F5F9;
      color: #475569;
    }
    .status-pill .dot {
      width: 6px;
      height: 6px;
      border-radius: 50%;
    }
    .status-pill.active .dot {
      background-color: #10B981;
      box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.25);
    }
    .status-pill.inactive .dot {
      background-color: #94A3B8;
    }

    .user-avatar-circle {
      width: 32px;
      height: 32px;
      border-radius: 8px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-weight: 700;
      font-size: 0.76rem;
      color: #FFFFFF;
      flex-shrink: 0;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
      letter-spacing: 0.5px;
    }

    /* Action Buttons in Table */
    .btn-action {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      padding: 5px 9px;
      border-radius: var(--radius-sm);
      border: 1px solid var(--color-border);
      font-size: 0.72rem;
      font-weight: 600;
      cursor: pointer;
      background-color: var(--color-surface);
      color: var(--color-text-primary);
      transition: all 0.15s ease;
      font-family: inherit;
      white-space: nowrap;
    }
    .btn-action:hover {
      background-color: var(--color-accent-subtle);
      border-color: var(--color-accent);
      color: var(--color-accent-hover);
    }
    .btn-action-danger {
      color: #DC2626;
      border-color: #FECACA;
    }
    .btn-action-danger:hover {
      background-color: #FEF2F2 !important;
      border-color: #EF4444 !important;
      color: #B91C1C !important;
    }

    /* Modal Overlay & Card */
    .modal-overlay {
      position: fixed;
      inset: 0;
      background: rgba(15, 23, 42, 0.6);
      backdrop-filter: blur(4px);
      z-index: 1000;
      display: none;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }
    .modal-overlay.open {
      display: flex;
    }
    .modal-box {
      background: #FFFFFF;
      border-radius: var(--radius-lg);
      box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2), 0 10px 10px -5px rgba(0, 0, 0, 0.08);
      width: 100%;
      max-width: 480px;
      overflow: hidden;
      animation: modalSlideIn 0.2s ease-out;
    }
    @keyframes modalSlideIn {
      from { opacity: 0; transform: translateY(12px) scale(0.98); }
      to { opacity: 1; transform: translateY(0) scale(1); }
    }
    .modal-header {
      padding: 18px 24px;
      border-bottom: 1px solid var(--color-border);
      display: flex;
      align-items: center;
      justify-content: space-between;
      background: #F8FAFC;
    }
    .modal-title {
      font-size: 1rem;
      font-weight: 700;
      color: var(--color-text-primary);
    }
    .modal-close {
      background: none;
      border: none;
      color: var(--color-text-secondary);
      cursor: pointer;
      font-size: 1.25rem;
      line-height: 1;
      padding: 4px;
      border-radius: 4px;
    }
    .modal-close:hover { color: var(--color-text-primary); background: #E2E8F0; }
    .modal-body {
      padding: 24px;
      display: flex;
      flex-direction: column;
      gap: 16px;
    }
    .modal-footer {
      padding: 16px 24px;
      border-top: 1px solid var(--color-border);
      display: flex;
      align-items: center;
      justify-content: flex-end;
      gap: 12px;
      background: #F8FAFC;
    }

    /* Toast Notification */
    .toast-container {
      position: fixed;
      bottom: 24px;
      right: 24px;
      z-index: 2000;
      display: flex;
      flex-direction: column;
      gap: 8px;
    }
    .toast-msg {
      padding: 12px 18px;
      border-radius: var(--radius-md);
      font-size: 0.85rem;
      font-weight: 600;
      color: #FFFFFF;
      box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.2);
      animation: toastIn 0.25s ease-out;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .toast-success { background: #059669; }
    .toast-error { background: #DC2626; }
    @keyframes toastIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>

<div class="app-layout">
  <!-- Collapsible Sidebar -->
  <aside class="app-sidebar" id="appSidebar">
    <div class="sidebar-brand">
      <img src="mvs_logo_full.png" alt="MVS Logo" class="brand-logo-sidebar">
      <div class="brand-meta">
        <span class="brand-title">MVS ICT &amp; Telecom</span>
        <span class="brand-subtitle">Modern Vision Solutions Ltd</span>
      </div>
    </div>

    <nav class="sidebar-nav">
      <div class="nav-section-label">Governance</div>
      
      <a href="admin.php" class="nav-item active">
        <svg class="nav-item-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;flex-shrink:0;">
          <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
          <circle cx="9" cy="7" r="4"></circle>
          <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
          <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
        </svg>
        <span class="nav-item-text">User Management</span>
      </a>

      <div class="nav-section-label">System Portals</div>

      <a href="api/export master excel.php" class="nav-item" style="color: #107C41;">
        <svg class="nav-item-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;flex-shrink:0;">
          <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
          <polyline points="7 10 12 15 17 10"></polyline>
          <line x1="12" y1="15" x2="12" y2="3"></line>
        </svg>
        <span class="nav-item-text" style="font-weight: 600;">Download Master Excel</span>
      </a>

      <a href="executive_portal.php?tab=sales-dash" class="nav-item">
        <svg class="nav-item-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;flex-shrink:0;">
          <line x1="12" y1="20" x2="12" y2="10"></line>
          <line x1="18" y1="20" x2="18" y2="4"></line>
          <line x1="6" y1="20" x2="6" y2="16"></line>
        </svg>
        <span class="nav-item-text">Pipeline Dashboard</span>
      </a>

      <a href="executive_portal.php?tab=projects-dash" class="nav-item">
        <svg class="nav-item-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;flex-shrink:0;">
          <circle cx="12" cy="12" r="10"></circle>
          <polyline points="12 6 12 12 16 14"></polyline>
        </svg>
        <span class="nav-item-text">Projects Dashboard</span>
      </a>

      <a href="sales_portal.php" class="nav-item">
        <svg class="nav-item-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;flex-shrink:0;">
          <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
          <line x1="3" y1="9" x2="21" y2="9"></line>
          <line x1="9" y1="21" x2="9" y2="9"></line>
        </svg>
        <span class="nav-item-text">Pipeline Register</span>
      </a>

      <a href="pm_portal.php" class="nav-item">
        <svg class="nav-item-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;flex-shrink:0;">
          <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
        </svg>
        <span class="nav-item-text">Projects Register</span>
      </a>
    </nav>

    <div class="sidebar-footer">
      <div class="user-mini-card" onclick="openMyProfileModal()" style="cursor: pointer;" title="View &amp; Edit Profile">
        <div class="user-mini-avatar" style="background: linear-gradient(135deg, #7C3AED, #4F46E5);">AD</div>
        <div class="user-mini-info">
          <span class="user-mini-name"><?= htmlspecialchars($user['name']) ?></span>
          <span class="user-mini-role">Super Administrator</span>
        </div>
      </div>
      <button class="btn-sidebar-collapse" onclick="toggleSidebar()" title="Toggle Sidebar">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <line x1="3" y1="12" x2="21" y2="12"></line>
          <line x1="3" y1="6" x2="21" y2="6"></line>
          <line x1="3" y1="18" x2="21" y2="18"></line>
        </svg>
      </button>
    </div>
  </aside>

  <!-- Main Wrapper -->
  <div class="app-main-wrapper">
    <!-- Topbar -->
    <header class="app-topbar">
      <div class="topbar-left">
        <button class="mobile-menu-btn" onclick="toggleSidebar()">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="3" y1="12" x2="21" y2="12"></line>
            <line x1="3" y1="6" x2="21" y2="6"></line>
            <line x1="3" y1="18" x2="21" y2="18"></line>
          </svg>
        </button>
        <nav class="breadcrumb-nav">
          <span>Administration</span>
          <span class="breadcrumb-separator">/</span>
          <span class="breadcrumb-current">User Access &amp; Roles</span>
        </nav>
      </div>

      <div class="topbar-right">
        <div class="tenant-pill">
          <span class="tenant-dot"></span>
          <span>MVS ICT &amp; Telecom</span>
        </div>
        <span class="topbar-role-badge role-badge-admin">SUPER ADMIN</span>
        <button class="btn btn-secondary" onclick="openMyProfileModal()" style="font-size: 0.8rem; padding: 6px 12px; display: inline-flex; align-items: center; gap: 6px;">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
            <circle cx="12" cy="7" r="4"></circle>
          </svg>
          My Profile &amp; Settings
        </button>
        <a href="logout.php" class="btn btn-logout" title="Sign Out">Sign Out</a>
      </div>
    </header>

    <!-- Main Content -->
    <main class="portal-main">
      <div class="page-header-row">
        <div class="page-header-title-box">
          <h1 class="page-title">Enterprise User Management</h1>
          <p class="page-subtitle">Configure system users, assign role-based access permissions, and maintain institutional security.</p>
        </div>
        <div class="page-header-actions" style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
          <a href="api/export master excel.php?type=pipelines" class="btn" style="background-color: #107C41; color: #ffffff; border: 1px solid #0b5e31; display: inline-flex; align-items: center; gap: 6px; font-weight: 600; text-decoration: none; padding: 9px 14px; border-radius: 6px; box-shadow: 0 1px 2px rgba(0,0,0,0.05); font-size: 0.83rem;">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
              <polyline points="7 10 12 15 17 10"></polyline>
              <line x1="12" y1="15" x2="12" y2="3"></line>
            </svg>
            <span>Pipelines Excel</span>
          </a>
          <a href="api/export master excel.php?type=projects" class="btn" style="background-color: #107C41; color: #ffffff; border: 1px solid #0b5e31; display: inline-flex; align-items: center; gap: 6px; font-weight: 600; text-decoration: none; padding: 9px 14px; border-radius: 6px; box-shadow: 0 1px 2px rgba(0,0,0,0.05); font-size: 0.83rem;">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
              <polyline points="7 10 12 15 17 10"></polyline>
              <line x1="12" y1="15" x2="12" y2="3"></line>
            </svg>
            <span>Projects Excel</span>
          </a>
          <button class="btn btn-primary" onclick="openCreateUserModal()">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <line x1="12" y1="5" x2="12" y2="19"></line>
              <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Add New User
          </button>
        </div>
      </div>

      <!-- KPI Summary Cards (Linear / SaaS Style) -->
      <div class="kpi-grid">
        <div class="kpi-card highlight-blue">
          <div class="kpi-label">Total Users</div>
          <div class="kpi-val"><span id="kpiTotalUsers">--</span></div>
          <div class="kpi-sub">Registered accounts</div>
        </div>

        <div class="kpi-card highlight-won">
          <div class="kpi-label">Active Accounts</div>
          <div class="kpi-val" style="color: #059669;"><span id="kpiActiveUsers">--</span></div>
          <div class="kpi-sub">Enabled login access</div>
        </div>

        <div class="kpi-card highlight-blue">
          <div class="kpi-label">Sales Team</div>
          <div class="kpi-val" style="color: #2563EB;"><span id="kpiSalesUsers">--</span></div>
          <div class="kpi-sub">Commercial representatives</div>
        </div>

        <div class="kpi-card highlight-teal">
          <div class="kpi-label">Project PMs</div>
          <div class="kpi-val" style="color: #0D9488;"><span id="kpiPmUsers">--</span></div>
          <div class="kpi-sub">Delivery project managers</div>
        </div>

        <div class="kpi-card highlight-purple">
          <div class="kpi-label">Executive &amp; Admin</div>
          <div class="kpi-val" style="color: #7C3AED;"><span id="kpiExecUsers">--</span></div>
          <div class="kpi-sub">Governance &amp; administration</div>
        </div>
      </div>

      <!-- Action & Filter Bar (Matching Sales & PM Portals) -->
      <div class="action-bar">
        <div class="action-left">
          <div class="search-box">
            <svg class="search-box-icon" viewBox="0 0 24 24">
              <circle cx="11" cy="11" r="8"></circle>
              <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
            <input type="text" id="userSearchInput" placeholder="Search by name, username or email..." oninput="filterUserTable()">
          </div>

          <select id="roleFilter" class="filter-select" onchange="filterUserTable()">
            <option value="">All System Roles</option>
            <option value="sales">Sales Representatives</option>
            <option value="pm">Project Managers</option>
            <option value="executive">Executive Leadership</option>
            <option value="admin">System Administrators</option>
          </select>

          <select id="statusFilter" class="filter-select" onchange="filterUserTable()">
            <option value="">All Account Statuses</option>
            <option value="active">Active Only</option>
            <option value="inactive">Inactive Only</option>
          </select>
        </div>

        <div class="action-right">
          <span id="userCountBadge" class="badge badge-gray" style="font-size: 0.74rem; padding: 6px 12px; font-weight: 500;">
            Showing 10 users
          </span>
        </div>
      </div>

      <!-- Enterprise Data Table (Linear Quality) -->
      <div class="table-container">
        <table class="v4-table" id="userTable">
          <thead>
            <tr>
              <th style="width: 48px; text-align: center;">#</th>
              <th style="min-width: 220px;">User Details</th>
              <th style="min-width: 130px;">Username</th>
              <th style="min-width: 220px;">Official Email</th>
              <th style="min-width: 140px;">System Role</th>
              <th style="min-width: 110px;">Status</th>
              <th style="min-width: 120px;">Created Date</th>
              <th style="min-width: 200px; text-align: right;">Actions</th>
            </tr>
          </thead>
          <tbody id="userTableBody">
            <tr>
              <td colspan="8" style="text-align: center; padding: 40px; color: var(--color-text-secondary);">
                Loading enterprise users...
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </main>
  </div>
</div>

<!-- Modal: Add New User -->
<div class="modal-overlay" id="modalCreateUser">
  <div class="modal-box">
    <div class="modal-header">
      <h3 class="modal-title">Add New Enterprise User</h3>
      <button class="modal-close" onclick="closeModal('modalCreateUser')">&times;</button>
    </div>
    <form id="formCreateUser" onsubmit="handleCreateUser(event)">
      <div class="modal-body">
        <div class="form-group">
          <label class="form-label">Full Name *</label>
          <input type="text" id="createName" class="form-input" placeholder="e.g. Tariq Mansoor" required>
        </div>

        <div class="form-group">
          <label class="form-label">Username *</label>
          <input type="text" id="createUsername" class="form-input" placeholder="e.g. tariq" required>
        </div>

        <div class="form-group">
          <label class="form-label">Email Address</label>
          <input type="email" id="createEmail" class="form-input" placeholder="e.g. tariq@mvsksa.com">
        </div>

        <div class="form-group">
          <label class="form-label">Role Assignment *</label>
          <select id="createRole" class="form-select" required>
            <option value="sales">Sales Representative (Sales Pipeline Register)</option>
            <option value="pm">Project Manager (Projects &amp; Milestones)</option>
            <option value="executive">Executive Leadership (Command Center &amp; Dashboards)</option>
            <option value="admin">System Administrator (Full Control &amp; User Management)</option>
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Initial Password * (Min. 6 chars)</label>
          <input type="password" id="createPassword" class="form-input" placeholder="••••••••" required minlength="6">
        </div>

        <div class="form-group">
          <label class="form-label">Account Status</label>
          <select id="createStatus" class="form-select">
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="closeModal('modalCreateUser')">Cancel</button>
        <button type="submit" class="btn btn-primary" id="btnSubmitCreate">Create User</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal: Edit User -->
<div class="modal-overlay" id="modalEditUser">
  <div class="modal-box">
    <div class="modal-header">
      <h3 class="modal-title">Edit User Details</h3>
      <button class="modal-close" onclick="closeModal('modalEditUser')">&times;</button>
    </div>
    <form id="formEditUser" onsubmit="handleEditUser(event)">
      <input type="hidden" id="editUserId">
      <div class="modal-body">
        <div class="form-group">
          <label class="form-label">Username</label>
          <input type="text" id="editUsername" class="form-input" readonly style="background: #F1F5F9; cursor: not-allowed;">
        </div>

        <div class="form-group">
          <label class="form-label">Full Name *</label>
          <input type="text" id="editName" class="form-input" required>
        </div>

        <div class="form-group">
          <label class="form-label">Email Address</label>
          <input type="email" id="editEmail" class="form-input" placeholder="e.g. user@mvsksa.com">
        </div>

        <div class="form-group">
          <label class="form-label">Role Assignment *</label>
          <select id="editRole" class="form-select" required>
            <option value="sales">Sales Representative</option>
            <option value="pm">Project Manager</option>
            <option value="executive">Executive Leadership</option>
            <option value="admin">System Administrator</option>
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Account Status</label>
          <select id="editStatus" class="form-select">
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="closeModal('modalEditUser')">Cancel</button>
        <button type="submit" class="btn btn-primary" id="btnSubmitEdit">Save Changes</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal: Reset User Password -->
<div class="modal-overlay" id="modalResetPassword">
  <div class="modal-box">
    <div class="modal-header">
      <h3 class="modal-title">Reset User Password</h3>
      <button class="modal-close" onclick="closeModal('modalResetPassword')">&times;</button>
    </div>
    <form id="formResetPassword" onsubmit="handleResetPassword(event)">
      <input type="hidden" id="resetUserId">
      <div class="modal-body">
        <p style="font-size: 0.85rem; color: var(--color-text-secondary); margin: 0;">
          Set a new password for user: <strong id="resetTargetUsername" style="color: var(--color-text-primary);"></strong>
        </p>

        <div class="form-group">
          <label class="form-label">New Password * (Min. 6 chars)</label>
          <div style="display: flex; gap: 8px;">
            <input type="text" id="resetNewPassword" class="form-input" placeholder="Enter or generate..." required minlength="6">
            <button type="button" class="btn btn-secondary" onclick="generateRandomPass('resetNewPassword')" style="white-space: nowrap;">Generate</button>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="closeModal('modalResetPassword')">Cancel</button>
        <button type="submit" class="btn btn-primary" id="btnSubmitReset">Update Password</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal: Change Admin My Password -->
<div class="modal-overlay" id="modalChangeMyPassword">
  <div class="modal-box">
    <div class="modal-header">
      <h3 class="modal-title">Change My Admin Password</h3>
      <button class="modal-close" onclick="closeModal('modalChangeMyPassword')">&times;</button>
    </div>
    <form id="formChangeMyPassword" onsubmit="handleChangeMyPassword(event)">
      <div class="modal-body">
        <div class="form-group">
          <label class="form-label">Current Password *</label>
          <input type="password" id="myCurrentPassword" class="form-input" placeholder="Current admin password" required>
        </div>

        <div class="form-group">
          <label class="form-label">New Confidential Password *</label>
          <input type="password" id="myNewPassword" class="form-input" placeholder="Minimum 6 characters" required minlength="6">
        </div>

        <div class="form-group">
          <label class="form-label">Confirm New Password *</label>
          <input type="password" id="myConfirmPassword" class="form-input" placeholder="Re-type new password" required minlength="6">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="closeModal('modalChangeMyPassword')">Cancel</button>
        <button type="submit" class="btn btn-primary" id="btnSubmitMyPassword">Save New Password</button>
      </div>
    </form>
  </div>
</div>

<!-- Toast Container -->
<div class="toast-container" id="toastContainer"></div>

<script>
// State
let allUsers = [];
let currentUserId = <?= (int)$user['id'] ?>;

// Init
document.addEventListener('DOMContentLoaded', () => {
  loadUsers();
});

// Load Users from API
async function loadUsers() {
  try {
    const res = await fetch('api/users.php');
    if (!res.ok) {
      if (res.status === 401 || res.status === 403) {
        window.location.href = 'login.php';
        return;
      }
      throw new Error('Failed to load user list');
    }
    const data = await res.json();
    if (data.success) {
      allUsers = data.users || [];
      if (data.current_user_id) currentUserId = data.current_user_id;
      renderKPIs();
      filterUserTable();
    } else {
      showToast(data.message || 'Error loading users', 'error');
    }
  } catch (err) {
    showToast(err.message, 'error');
  }
}

// Render KPI Summary Cards
function renderKPIs() {
  const total = allUsers.length;
  const active = allUsers.filter(u => u.status === 'active').length;
  const sales = allUsers.filter(u => u.role === 'sales').length;
  const pm = allUsers.filter(u => u.role === 'pm').length;
  const exec = allUsers.filter(u => u.role === 'executive' || u.role === 'admin').length;

  document.getElementById('kpiTotalUsers').textContent = total;
  document.getElementById('kpiActiveUsers').textContent = active;
  document.getElementById('kpiSalesUsers').textContent = sales;
  document.getElementById('kpiPmUsers').textContent = pm;
  document.getElementById('kpiExecUsers').textContent = exec;
}

// Filter and Render Table
function filterUserTable() {
  const search = (document.getElementById('userSearchInput').value || '').trim().toLowerCase();
  const roleFilter = document.getElementById('roleFilter').value;
  const statusFilter = document.getElementById('statusFilter').value;

  const filtered = allUsers.filter(u => {
    const matchSearch = !search || 
      (u.name && u.name.toLowerCase().includes(search)) ||
      (u.username && u.username.toLowerCase().includes(search)) ||
      (u.email && u.email.toLowerCase().includes(search));

    const matchRole = !roleFilter || u.role === roleFilter;
    const matchStatus = !statusFilter || u.status === statusFilter;

    return matchSearch && matchRole && matchStatus;
  });

  const countBadge = document.getElementById('userCountBadge');
  if (countBadge) {
    countBadge.textContent = `Showing ${filtered.length} of ${allUsers.length} users`;
  }

  const tbody = document.getElementById('userTableBody');
  if (filtered.length === 0) {
    tbody.innerHTML = `
      <tr>
        <td colspan="8" style="text-align: center; padding: 45px; color: var(--color-text-secondary);">
          No users match your filter criteria.
        </td>
      </tr>
    `;
    return;
  }

  const roleMeta = {
    'admin': { label: 'Super Admin', badgeClass: 'role-badge-admin' },
    'executive': { label: 'Executive', badgeClass: 'role-badge-executive' },
    'pm': { label: 'Project Manager', badgeClass: 'role-badge-pm' },
    'sales': { label: 'Sales Rep', badgeClass: 'role-badge-sales' }
  };

  const avatarGradients = [
    'linear-gradient(135deg, #2563EB, #1D4ED8)',
    'linear-gradient(135deg, #059669, #047857)',
    'linear-gradient(135deg, #7C3AED, #6D28D9)',
    'linear-gradient(135deg, #D97706, #B45309)',
    'linear-gradient(135deg, #DB2777, #BE185D)',
    'linear-gradient(135deg, #0891B2, #0E7490)'
  ];

  tbody.innerHTML = filtered.map((u, idx) => {
    const isSelf = (u.id === currentUserId);
    const initials = (u.name || u.username).substring(0, 2).toUpperCase();
    const colorBg = avatarGradients[u.id % avatarGradients.length];
    const roleInfo = roleMeta[u.role] || { label: u.role, badgeClass: 'role-badge-sales' };

    return `
      <tr>
        <td style="color: var(--color-text-muted); font-size: 0.78rem; font-family: var(--font-mono); text-align: center;">${idx + 1}</td>
        <td>
          <div style="display: flex; align-items: center; gap: 11px;">
            <div class="user-avatar-circle" style="background: ${colorBg};">${initials}</div>
            <div>
              <div style="font-weight: 600; color: var(--color-text-primary); font-size: 0.85rem; display: flex; align-items: center; gap: 6px;">
                ${escapeHtml(u.name)}
                ${isSelf ? '<span class="badge" style="background: #EDE9FE; color: #6D28D9; border: 1px solid #DDD6FE; font-size: 0.62rem; font-weight: 700;">YOU</span>' : ''}
              </div>
            </div>
          </div>
        </td>
        <td>
          <code>${escapeHtml(u.username)}</code>
        </td>
        <td style="color: var(--color-text-secondary); font-size: 0.82rem;">
          ${u.email ? `<a href="mailto:${escapeHtml(u.email)}" style="color: var(--color-accent); text-decoration: none;">${escapeHtml(u.email)}</a>` : '<span style="color: var(--color-text-muted);">—</span>'}
        </td>
        <td>
          <span class="badge ${roleInfo.badgeClass}">
            ${roleInfo.label}
          </span>
        </td>
        <td>
          <span class="status-pill ${u.status}" onclick="${isSelf ? '' : `toggleUserStatus(${u.id})`}" title="${isSelf ? 'Cannot deactivate self' : 'Click to toggle status'}">
            <span class="dot"></span>
            ${u.status === 'active' ? 'Active' : 'Inactive'}
          </span>
        </td>
        <td style="font-size: 0.76rem; color: var(--color-text-muted); font-family: var(--font-mono);">
          ${u.created_at ? u.created_at.substring(0, 10) : '—'}
        </td>
        <td style="text-align: right;">
          <div style="display: inline-flex; gap: 6px; align-items: center; justify-content: flex-end;">
            <button class="btn-action" onclick="openEditUserModal(${u.id})" title="Edit user details">
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
              </svg>
              <span>Edit</span>
            </button>
            <button class="btn-action" onclick="openResetPasswordModal(${u.id}, '${escapeJsString(u.username)}')" title="Reset user password">
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="8" cy="15" r="4"></circle>
                <path d="m10.85 12.15 7.65-7.65a1.5 1.5 0 0 1 2.12 0l1.41 1.41a1.5 1.5 0 0 1 0 2.12l-1.41 1.42"></path>
                <path d="m15.5 8.5 2 2"></path>
              </svg>
              <span>Key</span>
            </button>
            ${!isSelf ? `
              <button class="btn-action btn-action-danger" onclick="deleteUser(${u.id}, '${escapeJsString(u.name || u.username)}')" title="Delete user permanently">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <polyline points="3 6 5 6 21 6"></polyline>
                  <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                </svg>
                <span>Del</span>
              </button>
            ` : ''}
          </div>
        </td>
      </tr>
    `;
  }).join('');
}

// Modal Handlers
function openModal(id) {
  document.getElementById(id).classList.add('open');
}
function closeModal(id) {
  document.getElementById(id).classList.remove('open');
}

function openCreateUserModal() {
  document.getElementById('formCreateUser').reset();
  openModal('modalCreateUser');
}

function openEditUserModal(userId) {
  const u = allUsers.find(x => x.id === userId);
  if (!u) return;
  document.getElementById('editUserId').value = u.id;
  document.getElementById('editUsername').value = u.username;
  document.getElementById('editName').value = u.name;
  document.getElementById('editEmail').value = u.email || '';
  document.getElementById('editRole').value = u.role;
  document.getElementById('editStatus').value = u.status;
  openModal('modalEditUser');
}

function openResetPasswordModal(userId, username) {
  document.getElementById('resetUserId').value = userId;
  document.getElementById('resetTargetUsername').textContent = username;
  document.getElementById('resetNewPassword').value = '';
  openModal('modalResetPassword');
}

function openChangeMyPasswordModal() {
  document.getElementById('formChangeMyPassword').reset();
  openModal('modalChangeMyPassword');
}

function generateRandomPass(elementId) {
  const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789!@#$%';
  let pass = '';
  for (let i = 0; i < 10; i++) {
    pass += chars.charAt(Math.floor(Math.random() * chars.length));
  }
  document.getElementById(elementId).value = pass;
}

// API Submissions
async function handleCreateUser(e) {
  e.preventDefault();
  const btn = document.getElementById('btnSubmitCreate');
  btn.disabled = true;
  btn.textContent = 'Creating...';

  const payload = {
    action: 'create',
    name: document.getElementById('createName').value.trim(),
    username: document.getElementById('createUsername').value.trim(),
    email: document.getElementById('createEmail').value.trim(),
    role: document.getElementById('createRole').value,
    password: document.getElementById('createPassword').value.trim(),
    status: document.getElementById('createStatus').value
  };

  try {
    const res = await fetch('api/users.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload)
    });
    const data = await res.json();
    if (res.ok && data.success) {
      showToast(data.message, 'success');
      closeModal('modalCreateUser');
      await loadUsers();
    } else {
      showToast(data.message || 'Failed to create user', 'error');
    }
  } catch (err) {
    showToast(err.message, 'error');
  } finally {
    btn.disabled = false;
    btn.textContent = 'Create User';
  }
}

async function handleEditUser(e) {
  e.preventDefault();
  const btn = document.getElementById('btnSubmitEdit');
  btn.disabled = true;
  btn.textContent = 'Saving...';

  const payload = {
    action: 'update',
    id: parseInt(document.getElementById('editUserId').value),
    name: document.getElementById('editName').value.trim(),
    email: document.getElementById('editEmail').value.trim(),
    role: document.getElementById('editRole').value,
    status: document.getElementById('editStatus').value
  };

  try {
    const res = await fetch('api/users.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload)
    });
    const data = await res.json();
    if (res.ok && data.success) {
      showToast(data.message, 'success');
      closeModal('modalEditUser');
      await loadUsers();
    } else {
      showToast(data.message || 'Failed to update user', 'error');
    }
  } catch (err) {
    showToast(err.message, 'error');
  } finally {
    btn.disabled = false;
    btn.textContent = 'Save Changes';
  }
}

async function handleResetPassword(e) {
  e.preventDefault();
  const btn = document.getElementById('btnSubmitReset');
  btn.disabled = true;
  btn.textContent = 'Updating...';

  const payload = {
    action: 'reset_password',
    id: parseInt(document.getElementById('resetUserId').value),
    new_password: document.getElementById('resetNewPassword').value.trim()
  };

  try {
    const res = await fetch('api/users.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload)
    });
    const data = await res.json();
    if (res.ok && data.success) {
      showToast(data.message, 'success');
      closeModal('modalResetPassword');
    } else {
      showToast(data.message || 'Failed to reset password', 'error');
    }
  } catch (err) {
    showToast(err.message, 'error');
  } finally {
    btn.disabled = false;
    btn.textContent = 'Update Password';
  }
}

async function handleChangeMyPassword(e) {
  e.preventDefault();
  const currentPass = document.getElementById('myCurrentPassword').value.trim();
  const newPass = document.getElementById('myNewPassword').value.trim();
  const confirmPass = document.getElementById('myConfirmPassword').value.trim();

  if (newPass !== confirmPass) {
    showToast('New password and confirmation do not match', 'error');
    return;
  }

  const btn = document.getElementById('btnSubmitMyPassword');
  btn.disabled = true;
  btn.textContent = 'Saving...';

  const payload = {
    action: 'change_admin_password',
    current_password: currentPass,
    new_password: newPass
  };

  try {
    const res = await fetch('api/users.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload)
    });
    const data = await res.json();
    if (res.ok && data.success) {
      showToast(data.message, 'success');
      closeModal('modalChangeMyPassword');
    } else {
      showToast(data.message || 'Failed to change admin password', 'error');
    }
  } catch (err) {
    showToast(err.message, 'error');
  } finally {
    btn.disabled = false;
    btn.textContent = 'Save New Password';
  }
}

async function toggleUserStatus(userId) {
  try {
    const res = await fetch('api/users.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ action: 'toggle_status', id: userId })
    });
    const data = await res.json();
    if (res.ok && data.success) {
      showToast(data.message, 'success');
      await loadUsers();
    } else {
      showToast(data.message || 'Failed to toggle status', 'error');
    }
  } catch (err) {
    showToast(err.message, 'error');
  }
}

async function deleteUser(userId, userName) {
  if (!confirm(`Are you sure you want to permanently delete user "${userName}"?`)) {
    return;
  }

  try {
    const res = await fetch('api/users.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ action: 'delete', id: userId })
    });
    const data = await res.json();
    if (res.ok && data.success) {
      showToast(data.message, 'success');
      await loadUsers();
    } else {
      showToast(data.message || 'Failed to delete user', 'error');
    }
  } catch (err) {
    showToast(err.message, 'error');
  }
}

// Helpers
function showToast(msg, type = 'success') {
  const container = document.getElementById('toastContainer');
  const toast = document.createElement('div');
  toast.className = `toast-msg toast-${type}`;
  toast.textContent = msg;
  container.appendChild(toast);
  setTimeout(() => {
    toast.style.opacity = '0';
    toast.style.transition = 'opacity 0.3s ease';
    setTimeout(() => toast.remove(), 300);
  }, 3500);
}

function escapeHtml(str) {
  if (!str) return '';
  return String(str).replace(/[&<>"']/g, m => ({
    '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;'
  })[m]);
}

function escapeJsString(str) {
  if (!str) return '';
  return String(str).replace(/'/g, "\\'").replace(/"/g, '\\"');
}

function toggleSidebar() {
  const sidebar = document.getElementById('appSidebar');
  sidebar.classList.toggle('collapsed');
}
</script>

<?php require_once __DIR__ . '/profile modal.php'; ?>

</body>
</html>

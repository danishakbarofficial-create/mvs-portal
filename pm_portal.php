<?php
require_once __DIR__ . '/auth.php';
$user = Auth::requireLogin(['pm', 'executive']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Projects &bull; MVS ICT &amp; Telecom</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/portal.css?v=<?= time() ?>">
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
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
      <?php if ($user['role'] === 'executive' || $user['role'] === 'admin'): ?>
      <div class="nav-section-label">Executive Portals</div>
      
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
        <span class="nav-item-text">Pipeline</span>
      </a>
      <?php endif; ?>

      <div class="nav-section-label"><?= ($user['role'] === 'pm') ? 'Operations Workspace' : 'Projects Register' ?></div>
      <a href="javascript:void(0)" onclick="switchTab('projects-dash')" class="nav-item active" id="nav-item-projects-dash">
        <svg class="nav-item-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;flex-shrink:0;">
          <circle cx="12" cy="12" r="10"></circle>
          <polyline points="12 6 12 12 16 14"></polyline>
        </svg>
        <span class="nav-item-text">Projects Dashboard</span>
      </a>

      <a href="javascript:void(0)" onclick="switchTab('projects-register')" class="nav-item" id="nav-item-projects-register">
        <svg class="nav-item-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;flex-shrink:0;">
          <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
        </svg>
        <span class="nav-item-text"><?= ($user['role'] === 'pm') ? 'My Projects & Milestones' : 'Projects Register' ?></span>
      </a>

      <?php if ($user['role'] === 'admin'): ?>
      <div class="nav-section-label" style="color: #A78BFA; margin-top: 12px;">Administration</div>
      <a href="admin.php" class="nav-item">
        <svg class="nav-item-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;flex-shrink:0;">
          <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
          <circle cx="9" cy="7" r="4"></circle>
          <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
          <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
        </svg>
        <span class="nav-item-text">User Management</span>
      </a>
      <?php endif; ?>

      <div class="nav-section-label">Actions</div>
      <a href="javascript:void(0)" onclick="openCreateModal()" class="nav-item">
        <svg class="nav-item-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;flex-shrink:0;">
          <line x1="12" y1="5" x2="12" y2="19"></line>
          <line x1="5" y1="12" x2="19" y2="12"></line>
        </svg>
        <span class="nav-item-text">+ Add Milestone / PO</span>
      </a>
    </nav>

    <div class="sidebar-footer">
      <div class="user-mini-card">
        <div class="user-mini-avatar"><?= strtoupper(substr($user['name'], 0, 1)) ?></div>
        <div class="user-mini-info">
          <span class="user-mini-name"><?= htmlspecialchars($user['name']) ?></span>
          <span class="user-mini-role"><?= htmlspecialchars($user['role']) ?></span>
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
          <span>Operations</span>
          <span class="breadcrumb-separator">/</span>
          <span class="breadcrumb-current">Project Delivery &amp; Milestones</span>
        </nav>
      </div>

      <div class="topbar-right">
        <div class="tenant-pill">
          <span class="tenant-dot"></span>
          <span>MVS ICT &amp; Telecom</span>
        </div>
        <span class="topbar-role-badge role-badge-pm"><?= ($user['role'] === 'executive') ? 'Executive View' : 'Project Manager' ?></span>
        <a href="logout.php" class="btn btn-logout" title="Sign Out">Sign Out</a>
      </div>
    </header>

    <!-- Main Content Canvas -->
    <main class="portal-main">
      <div class="exec-nav-tabs">
        <button class="exec-tab-btn active" id="btn-tab-projects-dash" onclick="switchTab('projects-dash')">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10"></circle>
            <polyline points="12 6 12 12 16 14"></polyline>
          </svg>
          <span>Projects Dashboard</span>
        </button>
        <button class="exec-tab-btn" id="btn-tab-projects-register" onclick="switchTab('projects-register')">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
          </svg>
          <span>Milestones Register</span>
        </button>
      </div>

      <!-- ===================================================================
           TAB 1: Projects Dashboard (Faithful to Excel 'Projects Dashboard' Sheet)
           =================================================================== -->
      <div class="tab-content active" id="tab-projects-dash">
        <div class="action-bar" style="margin-bottom: 20px;">
          <div class="action-left">
            <label style="font-size:0.82rem;font-weight:600;color:var(--color-text-secondary);display:flex;align-items:center;gap:10px;">
              <span>Select Project:</span>
              <select id="dashProjectSelect" onchange="loadDashboardMetrics(this.value)" style="width:360px;">
                <option value="All My Projects">All My Projects</option>
              </select>
            </label>
          </div>
          <div class="action-right">
            <button class="btn btn-primary" onclick="openCreateModal()">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
              </svg>
              <span>+ Add Milestone / PO</span>
            </button>
          </div>
        </div>

        <!-- 5 Top KPI Cards -->
        <div class="kpi-grid" style="grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));">
          <div class="kpi-card highlight-blue">
            <div class="kpi-label">Awarded PO Value</div>
            <div class="kpi-val" id="dashTotalPoVal">0 <span class="curr">SAR</span></div>
            <div class="kpi-sub">Total contracted commitments</div>
          </div>
          <div class="kpi-card">
            <div class="kpi-label">Total Invoiced</div>
            <div class="kpi-val" id="dashTotalInvoiced">0 <span class="curr">SAR</span></div>
            <div class="kpi-sub">Billed milestone deliveries</div>
          </div>
          <div class="kpi-card highlight-review">
            <div class="kpi-label">To Be Invoiced / Backlog</div>
            <div class="kpi-val" id="dashBacklogVal">0 <span class="curr">SAR</span></div>
            <div class="kpi-sub">Unbilled contracted balance</div>
          </div>
          <div class="kpi-card highlight-won">
            <div class="kpi-label">Cash Collected</div>
            <div class="kpi-val" id="dashCashCollected">0 <span class="curr">SAR</span></div>
            <div class="kpi-sub">Received payments to bank</div>
          </div>
          <div class="kpi-card highlight-review">
            <div class="kpi-label">Pending Collection</div>
            <div class="kpi-val" id="dashPendingCollection">0 <span class="curr">SAR</span></div>
            <div class="kpi-sub">Billed awaiting settlement</div>
          </div>
        </div>

        <!-- 2 Top Progress Tracking Bars -->
        <div class="dash-grid-2">
          <div class="dash-progress-card">
            <div class="dash-progress-meta">
              <span class="dash-progress-title">Invoiced of PO Target</span>
              <span class="dash-progress-val" id="invoicedPctText">0%</span>
            </div>
            <div class="progress-bar-bg">
              <div class="progress-bar-fill" id="invoicedBar" style="width: 0%; background: #0284C7;"></div>
            </div>
          </div>
          <div class="dash-progress-card">
            <div class="dash-progress-meta">
              <span class="dash-progress-title">Cash Collected of Invoiced</span>
              <span class="dash-progress-val" id="collectedPctText">0%</span>
            </div>
            <div class="progress-bar-bg">
              <div class="progress-bar-fill" id="collectedBar" style="width: 0%; background: #10B981;"></div>
            </div>
          </div>
        </div>

        <!-- Section: Project & Cash Flow Visual Analytics -->
        <div class="dash-section-header">
          <h3>
            <span>Project &amp; Cash Flow Visual Analytics</span>
            <span class="section-tag">Visual Overview</span>
          </h3>
        </div>

        <!-- Row 1: Donut (Revenue Realization) + Grouped Bar (Cash Flow & Collections) -->
        <div class="charts-row">
          <div class="chart-card">
            <div class="chart-title">
              <span>Contract PO Revenue Realization</span>
              <span class="section-tag" style="font-size:0.68rem;">Billed vs Unbilled PO Balance</span>
            </div>
            <div class="chart-container" style="position:relative; height:280px;">
              <canvas id="projDonutRevenue"></canvas>
            </div>
          </div>

          <div class="chart-card">
            <div class="chart-title">
              <span>Invoice Cash Flow &amp; Collections (SAR)</span>
              <span class="section-tag" style="font-size:0.68rem;">Milestone Cash Flow Cycle</span>
            </div>
            <div class="chart-container" style="position:relative; height:280px;">
              <canvas id="projBarMilestoneCash"></canvas>
            </div>
          </div>
        </div>

        <!-- Row 2: Bar (Milestone Deliveries by PO Status) + Bar (Contract Realization & Cash Flow) -->
        <div class="charts-row">
          <div class="chart-card">
            <div class="chart-title">
              <span>Milestone Deliveries by PO Status</span>
              <span class="section-tag" style="font-size:0.68rem;">Delivery Governance</span>
            </div>
            <div class="chart-container" style="position:relative; height:280px;">
              <canvas id="projBarPoStatus"></canvas>
            </div>
          </div>

          <div class="chart-card">
            <div class="chart-title">
              <span>Contract Realization &amp; Cash Flow</span>
              <span class="section-tag" style="font-size:0.68rem;">Collected vs Due vs Backlog</span>
            </div>
            <div class="chart-container" style="position:relative; height:280px;">
              <canvas id="projBarContractRealization"></canvas>
            </div>
          </div>
        </div>

        <!-- 2-Column Grid: PO Status + Invoicing Status (Side-by-Side Excel Cards) -->
        <div class="dash-grid-2" style="margin-bottom: 24px;">
          <!-- Table 1: Projects by PO Status -->
          <div class="excel-table-box">
            <div class="excel-table-title">Projects by PO Status</div>
            <div class="table-container" style="border:none;box-shadow:none;border-radius:0;">
              <table class="excel-grid-table">
                <thead>
                  <tr>
                    <th>PO Status</th>
                    <th class="text-center" style="width:100px;">Milestone</th>
                    <th class="text-right">Value (SAR)</th>
                    <th class="text-right" style="width:110px;">% of Total</th>
                  </tr>
                </thead>
                <tbody id="poStatusBody">
                  <tr><td colspan="4" class="loading-td">Loading PO status...</td></tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Table 2: Milestone Invoicing by Status -->
          <div class="excel-table-box">
            <div class="excel-table-title">Milestone Invoicing by Status</div>
            <div class="table-container" style="border:none;box-shadow:none;border-radius:0;">
              <table class="excel-grid-table">
                <thead>
                  <tr>
                    <th>Invoice Status</th>
                    <th class="text-center" style="width:100px;">Count</th>
                    <th class="text-right">Value (SAR)</th>
                    <th class="text-right" style="width:110px;">% of Total</th>
                  </tr>
                </thead>
                <tbody id="invoiceStatusBody">
                  <tr><td colspan="4" class="loading-td">Loading invoicing status...</td></tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Table 3: Contract Billing & Cash Flow Lifecycle -->
        <div class="excel-table-box" style="margin-bottom: 24px;">
          <div class="excel-table-title">Contract Billing &amp; Cash Flow Lifecycle</div>
          <div class="table-container" style="border:none;box-shadow:none;border-radius:0;">
            <table class="excel-grid-table">
              <thead>
                <tr>
                  <th>Contract &amp; Billing Stage</th>
                  <th class="text-right" style="width:260px;">Amount (SAR)</th>
                  <th class="text-right" style="width:180px;">% of Contract</th>
                </tr>
              </thead>
              <tbody id="lifecycleBody">
                <tr><td colspan="3" class="loading-td">Loading lifecycle...</td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- ===================================================================
           TAB 2: My Projects & Milestones Register
           =================================================================== -->
      <div class="tab-content" id="tab-projects-register">
        <div class="page-header-row">
          <div class="page-header-title-box">
            <h1><?= ($user['role'] === 'pm') ? 'My Projects &amp; Milestones' : 'Projects Register' ?></h1>
            <p>Post-award operational delivery tracking, milestone deliverables, invoicing, and cash collections.</p>
          </div>
          <div class="page-header-actions" style="display:flex;gap:8px;align-items:center;">
            <a href="api/export master excel.php?type=projects" class="btn" style="background-color: #107C41; color: #ffffff; border: 1px solid #0b5e31; display: inline-flex; align-items: center; gap: 6px; font-weight: 600; text-decoration: none; padding: 8px 14px; border-radius: 6px; font-size: 0.85rem;">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                <polyline points="7 10 12 15 17 10"></polyline>
                <line x1="12" y1="15" x2="12" y2="3"></line>
              </svg>
              <span>Download Projects Excel</span>
            </a>
            <button class="btn btn-secondary" onclick="exportCsv()">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                <polyline points="7 10 12 15 17 10"></polyline>
                <line x1="12" y1="15" x2="12" y2="3"></line>
              </svg>
              <span>Export CSV</span>
            </button>
            <button class="btn btn-primary" onclick="openCreateModal()">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
              </svg>
              <span>Add Milestone / PO</span>
            </button>
          </div>
        </div>

        <!-- Filter & Action Bar -->
        <div class="action-bar">
          <div class="action-left">
            <div class="search-box">
              <svg class="search-box-icon" viewBox="0 0 24 24">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
              </svg>
              <input type="text" id="searchInput" placeholder="Search project, customer, PO, invoice..." onkeyup="filterProjects()">
            </div>
            <select id="projectSelect" onchange="filterProjects()">
              <option value="">All My Projects</option>
            </select>
            <select id="milestoneStatusFilter" onchange="filterProjects()">
              <option value="">All Milestone Statuses</option>
              <option value="Completed">Completed</option>
              <option value="In Progress">In Progress</option>
              <option value="Pending">Pending</option>
              <option value="On Hold">On Hold</option>
            </select>
          </div>
        </div>

        <!-- v4 Projects Data Table (26 Columns) -->
        <div class="table-container">
          <table class="v4-table" id="projectsTable">
            <thead>
              <tr>
                <th>No.</th>
                <th>Project Manager</th>
                <th>Project Name</th>
                <th>Customer</th>
                <th>PO No.</th>
                <th style="text-align:right;">Total PO (SAR)</th>
                <th>Milestone #</th>
                <th>Milestone Description</th>
                <th style="text-align:right;">Milestone SAR</th>
                <th>Milestone Status</th>
                <th>Tech Report</th>
                <th>Invoice Status</th>
                <th>Invoice No.</th>
                <th style="text-align:right;">Invoiced SAR</th>
                <th style="text-align:right;">Collected SAR</th>
                <th style="text-align:right;">Pending SAR</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody id="projectsTableBody">
              <tr>
                <td colspan="17" class="loading-td">Loading project milestones...</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </main>
  </div>
</div>

<!-- Create/Edit Milestone Modal (Exact 26 v4 Columns) -->
<div class="modal-overlay" id="milestoneModal">
  <div class="modal-box">
    <div class="modal-header">
      <div class="modal-header-meta">
        <span class="modal-badge">OPERATIONS &amp; DELIVERY</span>
        <h2 id="modalTitle">Update Project Milestone &amp; Billing</h2>
      </div>
      <button type="button" class="modal-close-btn" onclick="closeModal()" title="Close">&times;</button>
    </div>
    <div class="modal-body">
      <form id="milestoneForm" onsubmit="saveMilestone(event)">
        <input type="hidden" id="editId" name="id">

        <!-- Card 1: Contract & PO Info -->
        <div class="form-card">
          <div class="form-card-header">
            <div class="form-card-icon icon-blue">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
              </svg>
            </div>
            <div class="form-card-title-box">
              <span class="form-card-title">1. Contract PO &amp; Client Information</span>
              <span class="form-card-desc">Awarded project parameters, client entity, and total contract PO valuation</span>
            </div>
          </div>

          <div class="form-row col-3">
            <div class="form-group">
              <label>Project Name <span class="req-star">*</span></label>
              <input type="text" id="projectName" required placeholder="e.g. Air Base Infrastructure Project">
            </div>
            <div class="form-group">
              <label>Customer / Client <span class="req-star">*</span></label>
              <input type="text" id="customer" required placeholder="e.g. Ministry of Defense">
            </div>
            <div class="form-group">
              <label>Project Manager</label>
              <input type="text" id="projectManager" value="<?= htmlspecialchars($user['name']) ?>" <?= ($user['role'] === 'pm') ? 'readonly style="background-color: #F1F5F9; color: #475569;"' : '' ?>>
            </div>
          </div>

          <div class="form-row col-4">
            <div class="form-group">
              <label>PO No. <span class="req-star">*</span></label>
              <input type="text" id="poNo" placeholder="e.g. PO-MOD-9942" required>
            </div>
            <div class="form-group">
              <label>PO Status</label>
              <select id="poStatus">
                <option value="In Progress">In Progress</option>
                <option value="On Hold">On Hold</option>
                <option value="Completed">Completed</option>
                <option value="Cancelled">Cancelled</option>
              </select>
            </div>
            <div class="form-group">
              <label>Total PO Value (SAR) <span class="req-star">*</span></label>
              <div class="input-with-prefix">
                <span class="input-prefix">SAR</span>
                <input type="number" step="0.01" id="totalPoValue" required placeholder="2000000">
              </div>
            </div>
            <div class="form-group">
              <label>PO Award Date</label>
              <input type="date" id="poDate">
            </div>
          </div>
        </div>

        <!-- Card 2: Milestone Deliverables -->
        <div class="form-card">
          <div class="form-card-header">
            <div class="form-card-icon icon-emerald">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
                <line x1="16" y1="13" x2="8" y2="13"></line>
                <line x1="16" y1="17" x2="8" y2="17"></line>
              </svg>
            </div>
            <div class="form-card-title-box">
              <span class="form-card-title">2. Milestone Deliverables &amp; Execution</span>
              <span class="form-card-desc">Milestone scope item, stage valuation, and technical report submission</span>
            </div>
          </div>

          <div class="form-row col-4">
            <div class="form-group">
              <label>Milestone #</label>
              <input type="text" id="milestoneNo" placeholder="e.g. Milestone 1">
            </div>
            <div class="form-group">
              <label>Milestone Value (SAR) <span class="req-star">*</span></label>
              <div class="input-with-prefix">
                <span class="input-prefix">SAR</span>
                <input type="number" step="0.01" id="milestoneValue" required placeholder="400000" oninput="recalcLive()">
              </div>
            </div>
            <div class="form-group">
              <label>Milestone Status</label>
              <select id="milestoneStatus">
                <option value="In Progress">In Progress</option>
                <option value="Completed">Completed</option>
                <option value="Pending">Pending</option>
                <option value="On Hold">On Hold</option>
              </select>
            </div>
            <div class="form-group">
              <label>Technical Report</label>
              <select id="technicalReport">
                <option value="In Progress">In Progress</option>
                <option value="Submitted">Submitted</option>
                <option value="Resubmitted">Resubmitted</option>
                <option value="N/A">N/A</option>
              </select>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label>Milestone Description &amp; Scope</label>
              <input type="text" id="milestoneDesc" placeholder="e.g. Advance Payment (20%) upon contract signing or Material Delivery">
            </div>
          </div>
        </div>

        <!-- Card 3: Invoicing & Cash Collections -->
        <div class="form-card" style="margin-bottom: 0;">
          <div class="form-card-header">
            <div class="form-card-icon icon-purple">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="1" x2="12" y2="23"></line>
                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
              </svg>
            </div>
            <div class="form-card-title-box">
              <span class="form-card-title">3. Invoicing, Cash Collections &amp; Financials</span>
              <span class="form-card-desc">Billing invoices, collections received, and calculated pending balance</span>
            </div>
          </div>

          <div class="form-row col-4">
            <div class="form-group">
              <label>Invoice Status</label>
              <select id="invoiceStatus">
                <option value="Not Invoiced">Not Invoiced</option>
                <option value="Partially Invoiced">Partially Invoiced</option>
                <option value="Fully Invoiced">Fully Invoiced</option>
                <option value="Overdue">Overdue</option>
              </select>
            </div>
            <div class="form-group">
              <label>Invoice No.</label>
              <input type="text" id="invoiceNo" placeholder="e.g. INV-2026-001">
            </div>
            <div class="form-group">
              <label>Invoice Date</label>
              <input type="date" id="invoiceDate">
            </div>
            <div class="form-group">
              <label>Invoice Amount (SAR)</label>
              <div class="input-with-prefix">
                <span class="input-prefix">SAR</span>
                <input type="number" step="0.01" id="invoiceAmount" placeholder="0.00" oninput="recalcLive()">
              </div>
            </div>
          </div>

          <div class="form-row col-4">
            <div class="form-group">
              <label>Collection Status</label>
              <select id="collectionStatus">
                <option value="Pending">Pending</option>
                <option value="Partially Collected">Partially Collected</option>
                <option value="Fully Collected">Fully Collected</option>
                <option value="Overdue">Overdue</option>
              </select>
            </div>
            <div class="form-group">
              <label>Collection Date</label>
              <input type="date" id="collectionDate">
            </div>
            <div class="form-group">
              <label>Collected Amount (SAR)</label>
              <div class="input-with-prefix">
                <span class="input-prefix">SAR</span>
                <input type="number" step="0.01" id="collectedAmount" placeholder="0.00" oninput="recalcLive()">
              </div>
            </div>
            <div class="form-group">
              <label>Pending Collection (SAR)</label>
              <input type="text" id="pendingCollectionDisplay" readonly value="0.00" style="background:#F8FAFC;color:#B45309;font-weight:700;">
            </div>
          </div>

          <div class="form-row col-3">
            <div class="form-group">
              <label>To Be Invoiced / Backlog (SAR)</label>
              <input type="text" id="toBeInvoicedDisplay" readonly value="0.00" style="background:#F8FAFC;color:#1E40AF;font-weight:700;">
            </div>
            <div class="form-group">
              <label>Operational Delivery Remarks</label>
              <input type="text" id="notes" placeholder="Delivery notes, site milestones, logistics">
            </div>
            <div class="form-group">
              <label>Billing &amp; Collection Notes</label>
              <input type="text" id="additionalNotes" placeholder="Client payment certs, finance remarks">
            </div>
          </div>
        </div>

        <div class="modal-footer" style="margin-top: 18px;">
          <div style="font-size: 0.72rem; color: var(--color-text-secondary); display: flex; align-items: center; gap: 4px;">
            <span class="req-star">*</span> Required fields for project milestone delivery &amp; financials
          </div>
          <div style="display: flex; gap: 10px;">
            <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
            <button type="submit" class="btn btn-primary" id="saveBtn">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                <polyline points="17 21 17 13 7 13 7 21"></polyline>
                <polyline points="7 3 7 8 15 8"></polyline>
              </svg>
              <span>Save Milestone Record</span>
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  let allRecords = [];
  let projDonutRevenueInstance = null;
  let projBarPoStatusInstance = null;
  let projBarMilestoneCashInstance = null;
  let projBarContractRealizationInstance = null;

  function toggleSidebar() {
    const sb = document.getElementById('appSidebar');
    if (window.innerWidth <= 1024) {
      sb.classList.toggle('mobile-open');
    } else {
      sb.classList.toggle('collapsed');
    }
  }

  function switchTab(tabId) {
    document.querySelectorAll('.exec-tab-btn').forEach(b => b.classList.remove('active'));
    document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
    document.querySelectorAll('.sidebar-nav .nav-item').forEach(n => n.classList.remove('active'));

    const btn = document.getElementById('btn-tab-' + tabId);
    if (btn) btn.classList.add('active');

    const content = document.getElementById('tab-' + tabId);
    if (content) content.classList.add('active');

    const nav = document.getElementById('nav-item-' + tabId);
    if (nav) nav.classList.add('active');

    if (window.innerWidth <= 1024) {
      document.getElementById('appSidebar').classList.remove('mobile-open');
    }
  }

  async function loadDashboardMetrics(projectName = '') {
    try {
      const url = projectName ? `api/v4_dashboard.php?project_name=${encodeURIComponent(projectName)}` : 'api/v4_dashboard.php';
      const res = await fetch(url);
      const json = await res.json();
      if (json.success && json.projects_dashboard) {
        if (json.projects_list && json.projects_list.length > 0) {
          const select = document.getElementById('dashProjectSelect');
          if (select) {
            const currentVal = projectName || select.value || json.projects_list[0];
            select.innerHTML = json.projects_list.map(p => 
              `<option value="${escapeHtml(p)}" ${p === currentVal ? 'selected' : ''}>${escapeHtml(p)}</option>`
            ).join('');
          }
        }
        renderProjectsDashboard(json.projects_dashboard);
      }
    } catch (err) {
      console.error('Failed to load dashboard metrics:', err);
    }
  }

  function renderProjectsDashboard(p) {
    document.getElementById('dashTotalPoVal').innerHTML = formatSar(p.total_po_value) + ' <span class="curr">SAR</span>';
    document.getElementById('dashTotalInvoiced').innerHTML = formatSar(p.total_invoiced) + ' <span class="curr">SAR</span>';
    document.getElementById('dashBacklogVal').innerHTML = formatSar(p.to_be_invoiced_backlog) + ' <span class="curr">SAR</span>';
    document.getElementById('dashCashCollected').innerHTML = formatSar(p.total_cash_collected) + ' <span class="curr">SAR</span>';
    document.getElementById('dashPendingCollection').innerHTML = formatSar(p.pending_collection) + ' <span class="curr">SAR</span>';

    document.getElementById('invoicedPctText').innerText = p.invoiced_pct + '%';
    document.getElementById('invoicedBar').style.width = Math.min(100, p.invoiced_pct) + '%';

    document.getElementById('collectedPctText').innerText = p.collection_pct + '%';
    document.getElementById('collectedBar').style.width = Math.min(100, p.collection_pct) + '%';

    // 1. Projects by PO Status Table (Exact Excel Row Colors)
    const poTbody = document.getElementById('poStatusBody');
    if (poTbody && p.po_status_table) {
      const rowClassMap = {
        'In Progress': 'row-in-progress',
        'On Hold': 'row-on-hold',
        'Completed': 'row-completed',
        'Cancelled': 'row-cancelled'
      };

      let rowsHtml = p.po_status_table.map(row => {
        const rowCls = rowClassMap[row.po_status] || '';
        return `
          <tr class="${rowCls}">
            <td><strong>${escapeHtml(row.po_status)}</strong></td>
            <td class="text-center">${row.milestone_count}</td>
            <td class="sar-cell">${formatSarDec(row.value)}</td>
            <td class="text-right">${formatPct(row.pct_of_total)}</td>
          </tr>
        `;
      }).join('');

      const totalCount = p.po_status_table.reduce((sum, r) => sum + (r.milestone_count || 0), 0);
      const totalVal = p.po_status_table.reduce((sum, r) => sum + (r.value || 0), 0);
      const totalPct = totalVal > 0 ? '100.0%' : '0.0%';

      rowsHtml += `
        <tr class="excel-total-row">
          <td><strong>Total Active Projects</strong></td>
          <td class="text-center">${totalCount}</td>
          <td class="sar-cell">${formatSarDec(totalVal)}</td>
          <td class="text-right">${totalPct}</td>
        </tr>
      `;
      poTbody.innerHTML = rowsHtml;
    }

    // 2. Milestone Invoicing by Status Table (Exact Excel Row Colors)
    const invTbody = document.getElementById('invoiceStatusBody');
    if (invTbody && p.invoice_status_table) {
      const invRowClassMap = {
        'Not Invoiced': 'row-not-invoiced',
        'Partially Invoiced': 'row-partially-invoiced',
        'Fully Invoiced': 'row-fully-invoiced',
        'Overdue': 'row-overdue'
      };

      let rowsHtml = p.invoice_status_table.map(row => {
        const rowCls = invRowClassMap[row.invoice_status] || '';
        return `
          <tr class="${rowCls}">
            <td><strong>${escapeHtml(row.invoice_status)}</strong></td>
            <td class="text-center">${row.count}</td>
            <td class="sar-cell">${formatSarDec(row.value)}</td>
            <td class="text-right">${formatPct(row.pct_of_total)}</td>
          </tr>
        `;
      }).join('');

      const totalInvCount = p.invoice_status_table.reduce((sum, r) => sum + (r.count || 0), 0);
      const totalInvVal = p.invoice_status_table.reduce((sum, r) => sum + (r.value || 0), 0);
      const totalInvPct = totalInvVal > 0 ? '100.0%' : '0.0%';

      rowsHtml += `
        <tr class="excel-total-row">
          <td><strong>Total Milestones</strong></td>
          <td class="text-center">${totalInvCount}</td>
          <td class="sar-cell">${formatSarDec(totalInvVal)}</td>
          <td class="text-right">${totalInvPct}</td>
        </tr>
      `;
      invTbody.innerHTML = rowsHtml;
    }

    // 3. Contract Billing & Cash Flow Lifecycle Table (Exact Excel Row Structure)
    const lcTbody = document.getElementById('lifecycleBody');
    if (lcTbody && p.lifecycle_table) {
      lcTbody.innerHTML = p.lifecycle_table.map((row, idx) => {
        const isTotalRow = (idx === p.lifecycle_table.length - 1) || row.stage.includes('Total Awarded');
        if (isTotalRow) {
          return `
            <tr class="excel-total-row">
              <td><strong>${escapeHtml(row.stage)}</strong></td>
              <td class="sar-cell">${formatSarDec(row.amount)}</td>
              <td class="text-right">${formatPct(row.pct_of_contract)}</td>
            </tr>
          `;
        }
        return `
          <tr>
            <td><strong>${escapeHtml(row.stage)}</strong></td>
            <td class="sar-cell">${formatSarDec(row.amount)}</td>
            <td class="text-right">${formatPct(row.pct_of_contract)}</td>
          </tr>
        `;
      }).join('');
    }

    // Chart 1: Donut (Contract PO Revenue Realization)
    if (p.chart_revenue_realization && document.getElementById('projDonutRevenue')) {
      const ctxDonut = document.getElementById('projDonutRevenue').getContext('2d');
      if (projDonutRevenueInstance) projDonutRevenueInstance.destroy();
      projDonutRevenueInstance = new Chart(ctxDonut, {
        type: 'doughnut',
        data: {
          labels: p.chart_revenue_realization.labels,
          datasets: [{
            data: p.chart_revenue_realization.values,
            backgroundColor: ['#0284C7', '#1E293B'],
            borderWidth: 0,
            hoverOffset: 4
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          cutout: '62%',
          plugins: {
            legend: {
              position: 'bottom',
              labels: {
                color: '#475569',
                font: { family: 'Inter', size: 11, weight: '500' },
                padding: 12,
                usePointStyle: true
              }
            },
            tooltip: {
              callbacks: {
                label: function(ctx) {
                  return ' ' + ctx.label + ': ' + formatSar(ctx.raw) + ' SAR';
                }
              }
            }
          }
        }
      });
    }

    // Chart 2: Milestone Deliveries by PO Status
    if (p.chart_po_status && document.getElementById('projBarPoStatus')) {
      const ctxPo = document.getElementById('projBarPoStatus').getContext('2d');
      if (projBarPoStatusInstance) projBarPoStatusInstance.destroy();
      projBarPoStatusInstance = new Chart(ctxPo, {
        type: 'bar',
        data: {
          labels: p.chart_po_status.labels,
          datasets: [{
            label: 'Milestone Items',
            data: p.chart_po_status.counts,
            backgroundColor: '#10B981',
            borderRadius: 6,
            maxBarThickness: 46
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          scales: {
            x: { ticks: { color: '#64748B', font: { family: 'Inter', size: 11 } }, grid: { display: false } },
            y: { 
              beginAtZero: true, 
              ticks: { 
                stepSize: 1, 
                precision: 0, 
                color: '#64748B', 
                font: { family: 'Inter', size: 11 } 
              }, 
              grid: { color: '#EDF2F7' } 
            }
          },
          plugins: {
            legend: { display: false },
            tooltip: {
              callbacks: {
                label: function(ctx) {
                  return ' Milestone Items: ' + ctx.raw;
                }
              }
            }
          }
        }
      });
    }

    // Chart 3: Grouped Bar (Invoice Cash Flow & Collections SAR)
    if (p.chart_milestone_cashflow && document.getElementById('projBarMilestoneCash')) {
      const ctxCash = document.getElementById('projBarMilestoneCash').getContext('2d');
      if (projBarMilestoneCashInstance) projBarMilestoneCashInstance.destroy();
      projBarMilestoneCashInstance = new Chart(ctxCash, {
        type: 'bar',
        data: {
          labels: p.chart_milestone_cashflow.labels,
          datasets: [
            {
              label: 'Invoiced Amount (SAR)',
              data: p.chart_milestone_cashflow.invoiced,
              backgroundColor: '#8B5CF6',
              borderRadius: 4,
              maxBarThickness: 32
            },
            {
              label: 'Received Payment (SAR)',
              data: p.chart_milestone_cashflow.received,
              backgroundColor: '#10B981',
              borderRadius: 4,
              maxBarThickness: 32
            },
            {
              label: 'Pending Balance (SAR)',
              data: p.chart_milestone_cashflow.pending,
              backgroundColor: '#F59E0B',
              borderRadius: 4,
              maxBarThickness: 32
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          scales: {
            x: { ticks: { color: '#64748B', font: { family: 'Inter', size: 11 } }, grid: { display: false } },
            y: { 
              beginAtZero: true, 
              ticks: { 
                color: '#64748B', 
                font: { family: 'Inter', size: 11 },
                callback: function(v) { return formatSar(v); }
              }, 
              grid: { color: '#EDF2F7' } 
            }
          },
          plugins: {
            legend: {
              position: 'bottom',
              labels: {
                color: '#475569',
                font: { family: 'Inter', size: 11, weight: '500' },
                padding: 12,
                usePointStyle: true
              }
            },
            tooltip: {
              callbacks: {
                label: function(ctx) {
                  return ' ' + ctx.dataset.label + ': ' + formatSar(ctx.raw) + ' SAR';
                }
              }
            }
          }
        }
      });
    }

    // Chart 4: Bar (Contract Realization & Cash Flow)
    if (p.chart_contract_realization && document.getElementById('projBarContractRealization')) {
      const ctxReal = document.getElementById('projBarContractRealization').getContext('2d');
      if (projBarContractRealizationInstance) projBarContractRealizationInstance.destroy();
      projBarContractRealizationInstance = new Chart(ctxReal, {
        type: 'bar',
        data: {
          labels: p.chart_contract_realization.labels,
          datasets: [{
            label: 'Amount (SAR)',
            data: p.chart_contract_realization.values,
            backgroundColor: ['#10B981', '#F59E0B', '#C53030'],
            borderRadius: 6,
            maxBarThickness: 56
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          scales: {
            x: { ticks: { color: '#64748B', font: { family: 'Inter', size: 11 } }, grid: { display: false } },
            y: { 
              beginAtZero: true, 
              ticks: { 
                color: '#64748B', 
                font: { family: 'Inter', size: 11 },
                callback: function(v) { return formatSar(v); }
              }, 
              grid: { color: '#EDF2F7' } 
            }
          },
          plugins: {
            legend: { display: false },
            tooltip: {
              callbacks: {
                label: function(ctx) {
                  return ' Amount: ' + formatSar(ctx.raw) + ' SAR';
                }
              }
            }
          }
        }
      });
    }
  }

  function getPoStatusBadge(status) {
    status = status || 'In Progress';
    let cls = 'badge-blue';
    if (status === 'Completed') cls = 'badge-green';
    else if (status === 'On Hold') cls = 'badge-amber';
    else if (status === 'Cancelled') cls = 'badge-red';
    return `<span class="badge ${cls}">${escapeHtml(status)}</span>`;
  }

  async function loadProjects() {
    try {
      const res = await fetch('api/v4_projects.php');
      const json = await res.json();
      if (json.success) {
        allRecords = json.data;
        populateProjectDropdown(allRecords);
        renderProjects(allRecords);
        calculateStats(allRecords);
      }
    } catch (err) {
      console.error(err);
      document.getElementById('projectsTableBody').innerHTML = '<tr><td colspan="17" class="error-td">Failed to load project records.</td></tr>';
    }
  }

  function populateProjectDropdown(records) {
    const select = document.getElementById('projectSelect');
    const uniqueProjects = [...new Set(records.map(r => r.project_name).filter(Boolean))];
    select.innerHTML = '<option value="">All My Projects</option>' + uniqueProjects.map(p => `<option value="${escapeHtml(p)}">${escapeHtml(p)}</option>`).join('');
  }

  function calculateStats(records) {
    const poMap = {};
    let totalInvoiced = 0;
    let totalCollected = 0;
    let totalPending = 0;

    records.forEach(r => {
      if (r.project_name && r.total_po_value > 0) {
        poMap[r.project_name] = Math.max(poMap[r.project_name] || 0, parseFloat(r.total_po_value) || 0);
      }
      totalInvoiced += parseFloat(r.invoice_amount) || 0;
      totalCollected += parseFloat(r.collected_amount) || 0;
      totalPending += parseFloat(r.pending_collection) || 0;
    });

    const totalPoVal = Object.values(poMap).reduce((acc, v) => acc + v, 0);
    const backlog = Math.max(0, totalPoVal - totalInvoiced);

    document.getElementById('myPoVal').innerHTML = formatSar(totalPoVal) + ' <span class="curr">SAR</span>';
    document.getElementById('myCollectedVal').innerHTML = formatSar(totalCollected) + ' <span class="curr">SAR</span>';
    document.getElementById('myPendingVal').innerHTML = formatSar(totalPending) + ' <span class="curr">SAR</span>';
    document.getElementById('myBacklogVal').innerHTML = formatSar(backlog) + ' <span class="curr">SAR</span>';
  }

  function formatSar(num) {
    return Number(num).toLocaleString('en-US', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
  }

  function formatSarDec(num) {
    return Number(num || 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
  }

  function formatPct(num) {
    return Number(num || 0).toFixed(1) + '%';
  }

  function renderProjects(records) {
    const tbody = document.getElementById('projectsTableBody');
    if (records.length === 0) {
      tbody.innerHTML = '<tr><td colspan="17" class="empty-td">No project milestones found for this filter.</td></tr>';
      return;
    }

    tbody.innerHTML = records.map((r, idx) => {
      const msStatusBadge = getMilestoneBadge(r.milestone_status);
      const invStatusBadge = getInvoiceBadge(r.invoice_status);
      const poSar = Number(r.total_po_value || 0).toLocaleString('en-US');
      const msSar = Number(r.milestone_value || 0).toLocaleString('en-US');
      const invSar = Number(r.invoice_amount || 0).toLocaleString('en-US');
      const colSar = Number(r.collected_amount || 0).toLocaleString('en-US');
      const penSar = Number(r.pending_collection || 0).toLocaleString('en-US');

      return `
        <tr>
          <td>${idx + 1}</td>
          <td><strong>${escapeHtml(r.project_manager || '-')}</strong></td>
          <td><strong class="proj-title">${escapeHtml(r.project_name || '')}</strong></td>
          <td>${escapeHtml(r.customer || '')}</td>
          <td><code>${escapeHtml(r.po_no || '-')}</code></td>
          <td class="sar-cell">${poSar}</td>
          <td><span class="badge badge-purple">${escapeHtml(r.milestone_no || '-')}</span></td>
          <td><span class="scope-text" title="${escapeHtml(r.milestone_desc || '')}">${escapeHtml(r.milestone_desc || '-')}</span></td>
          <td class="sar-cell">${msSar}</td>
          <td>${msStatusBadge}</td>
          <td><span class="missing-badge">${escapeHtml(r.technical_report || '-')}</span></td>
          <td>${invStatusBadge}</td>
          <td><code>${escapeHtml(r.invoice_no || '-')}</code></td>
          <td class="sar-cell">${invSar}</td>
          <td class="sar-cell" style="color:#059669;font-weight:600;">${colSar}</td>
          <td class="sar-cell" style="color:#B45309;font-weight:600;">${penSar}</td>
          <td>
            <button class="btn-action edit" onclick="openEditModal(${r.id})">Update</button>
          </td>
        </tr>
      `;
    }).join('');
  }

  function getMilestoneBadge(status) {
    status = status || 'Pending';
    let cls = 'badge-gray';
    if (status === 'Completed') cls = 'badge-green';
    else if (status === 'In Progress') cls = 'badge-blue';
    else if (status === 'On Hold') cls = 'badge-amber';
    return `<span class="badge ${cls}">${escapeHtml(status)}</span>`;
  }

  function getInvoiceBadge(status) {
    status = status || 'Not Invoiced';
    let cls = 'badge-gray';
    if (status === 'Fully Invoiced') cls = 'badge-green';
    else if (status === 'Partially Invoiced') cls = 'badge-amber';
    else if (status === 'Overdue') cls = 'badge-red';
    return `<span class="badge ${cls}">${escapeHtml(status)}</span>`;
  }

  function escapeHtml(str) {
    if (!str) return '';
    return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
  }

  function filterProjects() {
    const q = document.getElementById('searchInput').value.toLowerCase().trim();
    const proj = document.getElementById('projectSelect').value;
    const msStatus = document.getElementById('milestoneStatusFilter').value;

    const filtered = allRecords.filter(r => {
      const matchesQ = !q || (r.project_name && r.project_name.toLowerCase().includes(q))
                          || (r.customer && r.customer.toLowerCase().includes(q))
                          || (r.po_no && r.po_no.toLowerCase().includes(q))
                          || (r.invoice_no && r.invoice_no.toLowerCase().includes(q));
      const matchesProj = !proj || (r.project_name === proj);
      const matchesMs = !msStatus || (r.milestone_status === msStatus);
      return matchesQ && matchesProj && matchesMs;
    });

    renderProjects(filtered);
  }

  function recalcLive() {
    const msVal = parseFloat(document.getElementById('milestoneValue').value) || 0;
    const inv = parseFloat(document.getElementById('invoiceAmount').value) || 0;
    const col = parseFloat(document.getElementById('collectedAmount').value) || 0;
    const invNo = (document.getElementById('invoiceNo').value || '').trim();

    const pending = Math.max(0, inv - col);
    document.getElementById('pendingCollectionDisplay').value = pending.toLocaleString('en-US', { minimumFractionDigits: 2 });

    const backlog = Math.max(0, msVal - inv);
    const backlogEl = document.getElementById('toBeInvoicedDisplay');
    if (backlogEl) {
      backlogEl.value = backlog.toLocaleString('en-US', { minimumFractionDigits: 2 });
    }

    const invStatusSel = document.getElementById('invoiceStatus');
    if (!invNo || inv <= 0) {
      invStatusSel.value = 'Not Invoiced';
    } else if (inv >= msVal && msVal > 0) {
      invStatusSel.value = 'Fully Invoiced';
    } else {
      invStatusSel.value = 'Partially Invoiced';
    }

    const colStatusSel = document.getElementById('collectionStatus');
    if (col <= 0) {
      colStatusSel.value = 'Pending';
    } else if (col >= inv && inv > 0) {
      colStatusSel.value = 'Fully Collected';
    } else {
      colStatusSel.value = 'Partially Collected';
    }
  }

  function openCreateModal() {
    document.getElementById('milestoneForm').reset();
    document.getElementById('editId').value = '';
    document.getElementById('modalTitle').innerText = 'Add New Project Milestone';
    document.getElementById('poDate').value = new Date().toISOString().split('T')[0];
    recalcLive();
    document.getElementById('milestoneModal').classList.add('active');
  }

  function openEditModal(id) {
    const rec = allRecords.find(r => r.id == id);
    if (!rec) return;

    document.getElementById('editId').value = rec.id;
    document.getElementById('modalTitle').innerText = 'Update Milestone #' + rec.id + ' (' + (rec.project_name || '') + ')';
    document.getElementById('projectName').value = rec.project_name || '';
    document.getElementById('customer').value = rec.customer || '';
    document.getElementById('projectManager').value = rec.project_manager || '';
    document.getElementById('poNo').value = rec.po_no || '';
    document.getElementById('poStatus').value = rec.po_status || 'In Progress';
    document.getElementById('totalPoValue').value = rec.total_po_value || 0;
    document.getElementById('poDate').value = rec.po_date ? rec.po_date.split(' ')[0] : '';
    document.getElementById('milestoneNo').value = rec.milestone_no || '';
    document.getElementById('milestoneDesc').value = rec.milestone_desc || '';
    document.getElementById('milestoneValue').value = rec.milestone_value || 0;
    document.getElementById('milestoneStatus').value = rec.milestone_status || 'In Progress';
    document.getElementById('technicalReport').value = rec.technical_report || 'In Progress';
    document.getElementById('invoiceStatus').value = rec.invoice_status || 'Not Invoiced';
    document.getElementById('invoiceNo').value = rec.invoice_no || '';
    document.getElementById('invoiceDate').value = rec.invoice_date ? rec.invoice_date.split(' ')[0] : '';
    document.getElementById('invoiceAmount').value = rec.invoice_amount || 0;
    document.getElementById('collectionStatus').value = rec.collection_status || 'Pending';
    document.getElementById('collectionDate').value = rec.collection_date ? rec.collection_date.split(' ')[0] : '';
    document.getElementById('collectedAmount').value = rec.collected_amount || 0;
    document.getElementById('notes').value = rec.notes || '';
    document.getElementById('additionalNotes').value = rec.additional_notes || '';

    recalcLive();
    document.getElementById('milestoneModal').classList.add('active');
  }

  function closeModal() {
    document.getElementById('milestoneModal').classList.remove('active');
  }

  async function saveMilestone(e) {
    e.preventDefault();
    const id = document.getElementById('editId').value;
    const isEdit = Boolean(id);

    const payload = {
      project_name: document.getElementById('projectName').value,
      customer: document.getElementById('customer').value,
      project_manager: document.getElementById('projectManager').value,
      po_no: document.getElementById('poNo').value,
      po_status: document.getElementById('poStatus').value,
      total_po_value: parseFloat(document.getElementById('totalPoValue').value) || 0,
      po_date: document.getElementById('poDate').value,
      milestone_no: document.getElementById('milestoneNo').value,
      milestone_desc: document.getElementById('milestoneDesc').value,
      milestone_value: parseFloat(document.getElementById('milestoneValue').value) || 0,
      milestone_status: document.getElementById('milestoneStatus').value,
      technical_report: document.getElementById('technicalReport').value,
      invoice_status: document.getElementById('invoiceStatus').value,
      invoice_no: document.getElementById('invoiceNo').value,
      invoice_date: document.getElementById('invoiceDate').value,
      invoice_amount: parseFloat(document.getElementById('invoiceAmount').value) || 0,
      collection_status: document.getElementById('collectionStatus').value,
      collection_date: document.getElementById('collectionDate').value,
      collected_amount: parseFloat(document.getElementById('collectedAmount').value) || 0,
      notes: document.getElementById('notes').value,
      additional_notes: document.getElementById('additionalNotes').value
    };

    if (isEdit) {
      payload.id = id;
    }

    const method = isEdit ? 'PUT' : 'POST';
    const btn = document.getElementById('saveBtn');
    btn.innerText = 'Saving...';
    btn.disabled = true;

    try {
      const res = await fetch('api/v4_projects.php', {
        method: method,
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
      });
      const json = await res.json();
      if (json.success) {
        closeModal();
        await loadProjects();
        const activeProj = document.getElementById('dashProjectSelect')?.value || '';
        await loadDashboardMetrics(activeProj);
      } else {
        alert('Error: ' + (json.error || json.message));
      }
    } catch (err) {
      alert('Request failed: ' + err.message);
    } finally {
      btn.innerText = 'Save Milestone Record';
      btn.disabled = false;
    }
  }

  function exportCsv() {
    if (allRecords.length === 0) return alert('No data to export');
    const headers = ["No.", "PM", "Project Name", "Customer", "PO No", "Total PO SAR", "Milestone #", "Milestone Description", "Milestone SAR", "Status", "Technical Report", "Invoice Status", "Invoice No", "Invoiced SAR", "Collected SAR", "Pending SAR"];
    const rows = allRecords.map((r, i) => [
      i + 1,
      `"${(r.project_manager||'').replace(/"/g, '""')}"`,
      `"${(r.project_name||'').replace(/"/g, '""')}"`,
      `"${(r.customer||'').replace(/"/g, '""')}"`,
      `"${(r.po_no||'').replace(/"/g, '""')}"`,
      r.total_po_value || 0,
      `"${(r.milestone_no||'').replace(/"/g, '""')}"`,
      `"${(r.milestone_desc||'').replace(/"/g, '""')}"`,
      r.milestone_value || 0,
      `"${(r.milestone_status||'').replace(/"/g, '""')}"`,
      `"${(r.technical_report||'').replace(/"/g, '""')}"`,
      `"${(r.invoice_status||'').replace(/"/g, '""')}"`,
      `"${(r.invoice_no||'').replace(/"/g, '""')}"`,
      r.invoice_amount || 0,
      r.collected_amount || 0,
      r.pending_collection || 0
    ]);

    const csvContent = "data:text/csv;charset=utf-8," + [headers.join(','), ...rows.map(e => e.join(','))].join('\n');
    const encodedUri = encodeURI(csvContent);
    const link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", `MVS_Awarded_Projects_${new Date().toISOString().split('T')[0]}.csv`);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
  }

  document.addEventListener('DOMContentLoaded', () => {
    loadProjects();
    loadDashboardMetrics();
  });
</script>
</body>
</html>

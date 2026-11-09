<?php
require_once __DIR__ . '/auth.php';
$user = Auth::requireLogin(['executive']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Executive Command Center &bull; MVS ICT &amp; Telecom</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/portal.css?v=<?= time() ?>">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    .dash-section-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin: 24px 0 12px 0;
    }
    .dash-section-header h3 {
      font-size: 0.95rem;
      font-weight: 700;
      color: var(--color-text-primary);
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .dash-section-header .section-tag {
      font-size: 0.68rem;
      text-transform: uppercase;
      font-weight: 700;
      letter-spacing: 0.05em;
      color: var(--color-accent);
      background-color: var(--color-accent-subtle);
      border: 1px solid var(--color-accent-border);
      padding: 2px 7px;
      border-radius: var(--radius-sm);
    }
    .dash-grid-2 {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 18px;
      margin-bottom: 22px;
    }
    @media (max-width: 1024px) {
      .dash-grid-2 {
        grid-template-columns: 1fr;
      }
    }
    .pct-bar-cell {
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .pct-bar-track {
      flex: 1;
      height: 6px;
      background-color: var(--color-border-subtle);
      border-radius: 999px;
      overflow: hidden;
      min-width: 60px;
    }
    .pct-bar-fill {
      height: 100%;
      background-color: var(--color-accent);
      border-radius: 999px;
    }
    .stage-dot {
      display: inline-block;
      width: 8px;
      height: 8px;
      border-radius: 999px;
      margin-right: 8px;
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
      <div class="nav-section-label">Portals &amp; Views</div>
      
      <a href="javascript:void(0)" onclick="switchTab('sales-dash')" class="nav-item active" id="nav-item-sales-dash">
        <svg class="nav-item-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;flex-shrink:0;">
          <line x1="12" y1="20" x2="12" y2="10"></line>
          <line x1="18" y1="20" x2="18" y2="4"></line>
          <line x1="6" y1="20" x2="6" y2="16"></line>
        </svg>
        <span class="nav-item-text">Pipeline Dashboard</span>
      </a>

      <a href="javascript:void(0)" onclick="switchTab('projects-dash')" class="nav-item" id="nav-item-projects-dash">
        <svg class="nav-item-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;flex-shrink:0;">
          <circle cx="12" cy="12" r="10"></circle>
          <polyline points="12 6 12 12 16 14"></polyline>
        </svg>
        <span class="nav-item-text">Projects Dashboard</span>
      </a>

      <a href="javascript:void(0)" onclick="switchTab('all-pipeline')" class="nav-item" id="nav-item-all-pipeline">
        <svg class="nav-item-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;flex-shrink:0;">
          <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
          <line x1="3" y1="9" x2="21" y2="9"></line>
          <line x1="9" y1="21" x2="9" y2="9"></line>
        </svg>
        <span class="nav-item-text">Pipeline</span>
      </a>

      <a href="javascript:void(0)" onclick="switchTab('all-projects')" class="nav-item" id="nav-item-all-projects">
        <svg class="nav-item-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;flex-shrink:0;">
          <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
        </svg>
        <span class="nav-item-text">Projects</span>
      </a>

      <div class="nav-section-label" style="color: #107C41; margin-top: 12px;">Master Excel Export</div>
      <a href="api/export master excel.php" class="nav-item" style="color: #107C41;">
        <svg class="nav-item-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;flex-shrink:0;">
          <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
          <polyline points="7 10 12 15 17 10"></polyline>
          <line x1="12" y1="15" x2="12" y2="3"></line>
        </svg>
        <span class="nav-item-text" style="font-weight: 600;">Download Master Excel</span>
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
    </nav>

    <div class="sidebar-footer">
      <div class="user-mini-card" onclick="openMyProfileModal()" style="cursor: pointer;" title="View &amp; Edit Profile">
        <div class="user-mini-avatar" <?= ($user['role'] === 'admin') ? 'style="background: linear-gradient(135deg, #7C3AED, #4F46E5);"' : '' ?>><?= ($user['role'] === 'admin') ? 'AD' : 'EX' ?></div>
        <div class="user-mini-info">
          <span class="user-mini-name"><?= htmlspecialchars($user['name']) ?></span>
          <span class="user-mini-role"><?= ($user['role'] === 'admin') ? 'Super Admin' : 'Executive' ?></span>
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
          <span>Governance</span>
          <span class="breadcrumb-separator">/</span>
          <span class="breadcrumb-current">Executive Command Center</span>
        </nav>
      </div>

      <div class="topbar-right">
        <div class="tenant-pill">
          <span class="tenant-dot"></span>
          <span>MVS ICT &amp; Telecom</span>
        </div>
        <span class="topbar-role-badge <?= ($user['role'] === 'admin') ? 'role-badge-admin' : 'role-badge-exec' ?>"><?= ($user['role'] === 'admin') ? 'SUPER ADMIN' : 'Executive Leadership' ?></span>
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

    <!-- Main Content Canvas -->
    <main class="portal-main">
      <div class="page-header-row" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;">
        <div class="page-header-title-box">
          <h1>Executive Command Center</h1>
          <p>Institutional oversight of commercial pipeline, project deliverables, and cash collections.</p>
        </div>
        <div class="page-header-actions" style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
          <a href="api/export master excel.php?type=pipelines" class="btn" style="background-color: #107C41; color: #ffffff; border: 1px solid #0b5e31; display: inline-flex; align-items: center; gap: 6px; font-weight: 600; text-decoration: none; padding: 8px 14px; border-radius: 6px; box-shadow: 0 1px 2px rgba(0,0,0,0.05); font-size: 0.83rem;">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
              <polyline points="7 10 12 15 17 10"></polyline>
              <line x1="12" y1="15" x2="12" y2="3"></line>
            </svg>
            <span>Pipelines Excel</span>
          </a>
          <a href="api/export master excel.php?type=projects" class="btn" style="background-color: #107C41; color: #ffffff; border: 1px solid #0b5e31; display: inline-flex; align-items: center; gap: 6px; font-weight: 600; text-decoration: none; padding: 8px 14px; border-radius: 6px; box-shadow: 0 1px 2px rgba(0,0,0,0.05); font-size: 0.83rem;">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
              <polyline points="7 10 12 15 17 10"></polyline>
              <line x1="12" y1="15" x2="12" y2="3"></line>
            </svg>
            <span>Projects Excel</span>
          </a>
          <a href="api/export master excel.php" class="btn btn-secondary" style="display: inline-flex; align-items: center; gap: 6px; font-weight: 600; text-decoration: none; padding: 8px 12px; border-radius: 6px; font-size: 0.83rem;" title="Download complete master workbook with both Pipelines & Projects">
            <span>Combined Master</span>
          </a>
        </div>
      </div>

      <!-- Segmented Navigation Tabs -->
      <nav class="exec-nav-tabs">
        <button class="exec-tab-btn active" onclick="switchTab('sales-dash')">
          Pipeline Dashboard
        </button>
        <button class="exec-tab-btn" onclick="switchTab('projects-dash')">
          Projects Dashboard
        </button>
        <button class="exec-tab-btn" onclick="switchTab('all-pipeline')">
          Pipeline
        </button>
        <button class="exec-tab-btn" onclick="switchTab('all-projects')">
          Projects
        </button>
      </nav>

      <!-- ===================================================================
           TAB 1: Pipeline Dashboard (Excel 'Pipeline Dashboard' Sheet)
           =================================================================== -->
      <div class="tab-content active" id="tab-sales-dash">
        <!-- Pipeline Intelligence Filters -->
        <div class="action-bar" style="margin-bottom: 20px; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px; background:#FFFFFF; padding:12px 18px; border-radius:10px; border:1px solid var(--color-border); box-shadow:0 1px 3px rgba(0,0,0,0.03);">
          <div style="display:flex; align-items:center; flex-wrap:wrap; gap:14px;">
            <label style="font-size:0.82rem;font-weight:600;color:var(--color-text-secondary);display:flex;align-items:center;gap:8px;">
              <span>Sales Rep:</span>
              <select id="dashSalesPersonSelect" onchange="loadSalesDashboardFiltered()" style="min-width:160px;padding:7px 12px;border-radius:6px;border:1px solid #CBD5E1;font-size:0.85rem;background:#FFFFFF;color:#1E293B;font-weight:500;">
                <option value="">All Sales Reps</option>
              </select>
            </label>

            <label style="font-size:0.82rem;font-weight:600;color:var(--color-text-secondary);display:flex;align-items:center;gap:8px;">
              <span>Decision Status:</span>
              <select id="dashSalesStatusSelect" onchange="loadSalesDashboardFiltered()" style="min-width:180px;padding:7px 12px;border-radius:6px;border:1px solid #CBD5E1;font-size:0.85rem;background:#FFFFFF;color:#1E293B;font-weight:500;">
                <option value="">All Decision Statuses</option>
              </select>
            </label>

            <label style="font-size:0.82rem;font-weight:600;color:var(--color-text-secondary);display:flex;align-items:center;gap:8px;">
              <span>Client / Contractor:</span>
              <select id="dashSalesClientSelect" onchange="loadSalesDashboardFiltered()" style="min-width:180px;padding:7px 12px;border-radius:6px;border:1px solid #CBD5E1;font-size:0.85rem;background:#FFFFFF;color:#1E293B;font-weight:500;">
                <option value="">All Clients</option>
              </select>
            </label>
          </div>

          <button type="button" class="btn btn-secondary" onclick="resetSalesDashboardFilters()" style="font-size:0.82rem;padding:7px 14px;display:inline-flex;align-items:center;gap:6px;cursor:pointer;font-weight:600;" title="Reset all pipeline filters">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
              <path d="M3 3v5h5"></path>
            </svg>
            <span>Reset Filters</span>
          </button>
        </div>

        <!-- 4 Top KPI Cards (B4:H5) -->
        <div class="kpi-grid">
          <div class="kpi-card highlight-blue">
            <div class="kpi-label">Total Active Pipeline (SAR)</div>
            <div class="kpi-val" id="dashTotalPipeline">0 <span class="curr">SAR</span></div>
            <div class="kpi-sub">Sum of all open pre-award inquiries</div>
          </div>
          <div class="kpi-card highlight-won">
            <div class="kpi-label">Won Projects Value (SAR)</div>
            <div class="kpi-val" id="dashWonVal">0 <span class="curr">SAR</span></div>
            <div class="kpi-sub">Awarded bids ready for operational handover</div>
          </div>
          <div class="kpi-card highlight-review">
            <div class="kpi-label">Under Pricing / Review (SAR)</div>
            <div class="kpi-val" id="dashUnderPricingVal">0 <span class="curr">SAR</span></div>
            <div class="kpi-sub">Tenders in technical &amp; pricing prep</div>
          </div>
          <div class="kpi-card">
            <div class="kpi-label">Win Rate (% Decided Bids)</div>
            <div class="kpi-val" id="dashWinRate">0%</div>
            <div class="kpi-sub">Conversion of decided bids (Won / Won+Lost)</div>
          </div>
        </div>

        <!-- Charts Row -->
        <div class="charts-row">
          <div class="chart-card">
            <div class="chart-title">
              <span>Pipeline Bids by Decision Status</span>
            </div>
            <div class="chart-container">
              <canvas id="statusChart"></canvas>
            </div>
          </div>
          <div class="chart-card">
            <div class="chart-title">
              <span>Pipeline Value by Sales Person (SAR)</span>
            </div>
            <div class="chart-container">
              <canvas id="repChart"></canvas>
            </div>
          </div>
        </div>

        <!-- Table 1: Pipeline Bids by Status -->
        <div class="excel-table-box" style="margin-bottom: 24px;">
          <div class="excel-table-title">Pipeline Bids by Status</div>
          <div class="table-container" style="border:none;box-shadow:none;border-radius:0;">
            <table class="excel-grid-table" id="tableSalesStatus">
              <thead>
                <tr>
                  <th>Pipeline Status</th>
                  <th class="text-center" style="width:110px;">Bid Count</th>
                  <th class="text-right">Value (SAR)</th>
                  <th class="text-right" style="width:120px;">% of Total</th>
                  <th style="width:180px;">Expected Closing Range</th>
                </tr>
              </thead>
              <tbody id="salesStatusBody">
                <tr><td colspan="5" class="loading-td">Loading status breakdown...</td></tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Excel Table 2: Pipeline by Sales Representative -->
        <div class="excel-table-box" style="margin-bottom: 24px;">
          <div class="excel-table-title">Pipeline by Sales Representative</div>
          <div class="table-container" style="border:none;box-shadow:none;border-radius:0;">
            <table class="excel-grid-table" id="tableSalesReps">
              <thead>
                <tr>
                  <th>Sales Person</th>
                  <th class="text-center" style="width:120px;">Active Leads</th>
                  <th class="text-right">Pipeline Value (SAR)</th>
                  <th class="text-right" style="width:120px;">Share %</th>
                </tr>
              </thead>
              <tbody id="salesRepsBody">
                <tr><td colspan="4" class="loading-td">Loading sales team pipeline...</td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- ===================================================================
           TAB 2: Projects Dashboard (Faithful to Excel 'Projects Dashboard' Sheet)
           =================================================================== -->
      <div class="tab-content" id="tab-projects-dash">
        <div class="action-bar" style="margin-bottom: 20px;">
          <div class="action-left">
            <label style="font-size:0.82rem;font-weight:600;color:var(--color-text-secondary);display:flex;align-items:center;gap:10px;">
              <span>Select Project:</span>
              <select id="dashProjectSelect" onchange="loadDashboardMetrics(this.value)" style="width:360px;">
                <option value="All Projects">All Projects</option>
              </select>
            </label>
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
            <div class="kpi-sub">Remaining unbilled scope</div>
          </div>
          <div class="kpi-card highlight-won">
            <div class="kpi-label">Total Cash Collected</div>
            <div class="kpi-val" id="dashCashCollected">0 <span class="curr">SAR</span></div>
            <div class="kpi-sub">Actual bank receipts credited</div>
          </div>
          <div class="kpi-card" style="border-left: 3.5px solid #F59E0B;">
            <div class="kpi-label">Pending Collection</div>
            <div class="kpi-val" id="dashPendingCollection" style="color:#B45309;">0 <span class="curr">SAR</span></div>
            <div class="kpi-sub">Invoiced balance awaiting payment</div>
          </div>
        </div>

        <!-- Financial Progress Bars -->
        <div class="progress-box">
          <div class="progress-header">
            <span>Invoicing Progress (Invoiced vs PO Value)</span>
            <span id="invoicedPctText">0%</span>
          </div>
          <div class="progress-track">
            <div class="progress-bar-fill fill-blue" id="invoicedBar" style="width: 0%;"></div>
          </div>

          <div class="progress-header">
            <span>Cash Collection Efficiency (Collected vs Invoiced)</span>
            <span id="collectedPctText">0%</span>
          </div>
          <div class="progress-track" style="margin-bottom:0;">
            <div class="progress-bar-fill fill-green" id="collectedBar" style="width: 0%;"></div>
          </div>
        </div>

        <!-- Visual Analytics: 4 Graphs from Projects Dashboard -->
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
                  <tr><td colspan="4" class="loading-td">Loading PO statuses...</td></tr>
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
                  <tr><td colspan="4" class="loading-td">Loading invoicing statuses...</td></tr>
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

        <!-- Excel Table 4: Project Manager Delivery & Cash Collection Performance -->
        <div class="excel-table-box" style="margin-bottom: 24px;">
          <div class="excel-table-title">
            <span>Project Manager Delivery &amp; Cash Collection Performance</span>
            <span class="section-tag" style="background:#F1F5F9;color:#475569;font-size:0.72rem;padding:3px 8px;border-radius:4px;">PM Operations Governance</span>
          </div>
          <div class="table-container" style="border:none;box-shadow:none;border-radius:0;">
            <table class="excel-grid-table">
              <thead>
                <tr>
                  <th>Project Manager</th>
                  <th class="text-center">Projects Count</th>
                  <th class="text-center">Milestones</th>
                  <th class="text-right">Contract Value (SAR)</th>
                  <th class="text-right">Invoiced (SAR)</th>
                  <th class="text-right">Collected (SAR)</th>
                  <th class="text-right">Pending (SAR)</th>
                  <th class="text-right" style="width:140px;">Collection Efficiency</th>
                </tr>
              </thead>
              <tbody id="pmTableBody">
                <tr><td colspan="8" class="loading-td">Loading PM performance...</td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- ===================================================================
           TAB 3: Master Pipeline Register
           =================================================================== -->
      <div class="tab-content" id="tab-all-pipeline">
        <div class="action-bar">
          <div class="action-left">
            <div class="search-box">
              <svg class="search-box-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
              </svg>
              <input type="text" id="masterPipelineSearch" placeholder="Search project, client, sales rep..." onkeyup="filterMasterPipeline()">
            </div>
            <select id="masterRepFilter" onchange="filterMasterPipeline()">
              <option value="">All Sales Persons</option>
              <option value="Asayil">Asayil</option>
              <option value="Nasir">Nasir</option>
              <option value="Ahmed">Ahmed</option>
              <option value="Wael">Wael</option>
              <option value="Shahbaz">Shahbaz</option>
            </select>
            <select id="masterStatusFilter" onchange="filterMasterPipeline()">
              <option value="">All Decision Statuses</option>
              <option value="Information Gathering">Information Gathering</option>
              <option value="Under Review">Under Review</option>
              <option value="Pricing in Progress">Pricing in Progress</option>
              <option value="Quotation Submitted">Quotation Submitted</option>
              <option value="Won">Won</option>
              <option value="Lost">Lost</option>
              <option value="On Hold">On Hold</option>
            </select>
          </div>
          <div class="action-right" style="display:flex;gap:8px;align-items:center;">
            <a href="api/export master excel.php?type=pipelines" class="btn" style="background-color: #107C41; color: #ffffff; border: 1px solid #0b5e31; display: inline-flex; align-items: center; gap: 6px; font-weight: 600; text-decoration: none; font-size: 0.82rem; padding: 7px 12px; border-radius: 6px;">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                <polyline points="7 10 12 15 17 10"></polyline>
                <line x1="12" y1="15" x2="12" y2="3"></line>
              </svg>
              <span>Download Pipelines Excel</span>
            </a>
            <button class="btn btn-secondary" onclick="exportMasterPipelineCsv()">Export CSV</button>
          </div>
        </div>

        <div class="table-container">
          <table class="v4-table" id="masterPipelineTable">
            <thead>
              <tr>
                <th>No.</th>
                <th>Sales Person</th>
                <th>Project Name</th>
                <th>Client</th>
                <th>Main Contractor</th>
                <th>Quotation No.</th>
                <th style="text-align:right;">Total SAR (incl. VAT)</th>
                <th>Decision Status</th>
                <th>Missing Info</th>
                <th>Next Step</th>
                <th>Expected Closing</th>
              </tr>
            </thead>
            <tbody id="masterPipelineBody">
              <tr><td colspan="11" class="loading-td">Loading master pipeline...</td></tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- ===================================================================
           TAB 4: Master Projects & Milestones
           =================================================================== -->
      <div class="tab-content" id="tab-all-projects">
        <div class="action-bar">
          <div class="action-left">
            <div class="search-box">
              <svg class="search-box-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
              </svg>
              <input type="text" id="masterProjectsSearch" placeholder="Search project, customer, PM..." onkeyup="filterMasterProjects()">
            </div>
            <select id="masterPmFilter" onchange="filterMasterProjects()">
              <option value="">All Project Managers</option>
              <option value="Muhammad Farooq">Muhammad Farooq</option>
              <option value="Tahir Anjum">Tahir Anjum</option>
              <option value="Adrian Kim Mallilin">Adrian Kim Mallilin</option>
            </select>
          </div>
          <div class="action-right" style="display:flex;gap:8px;align-items:center;">
            <a href="api/export master excel.php?type=projects" class="btn" style="background-color: #107C41; color: #ffffff; border: 1px solid #0b5e31; display: inline-flex; align-items: center; gap: 6px; font-weight: 600; text-decoration: none; font-size: 0.82rem; padding: 7px 12px; border-radius: 6px;">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                <polyline points="7 10 12 15 17 10"></polyline>
                <line x1="12" y1="15" x2="12" y2="3"></line>
              </svg>
              <span>Download Projects Excel</span>
            </a>
            <button class="btn btn-secondary" onclick="exportMasterProjectsCsv()">Export CSV</button>
          </div>
        </div>

        <div class="table-container">
          <table class="v4-table" id="masterProjectsTable">
            <thead>
              <tr>
                <th>No.</th>
                <th>PM</th>
                <th>Project Name</th>
                <th>Customer</th>
                <th>PO No.</th>
                <th style="text-align:right;">Total PO SAR</th>
                <th>Milestone #</th>
                <th>Milestone Description</th>
                <th style="text-align:right;">Milestone SAR</th>
                <th>Status</th>
                <th>Tech Report</th>
                <th>Invoice Status</th>
                <th>Invoice No.</th>
                <th style="text-align:right;">Invoiced SAR</th>
                <th>Collection Status</th>
                <th style="text-align:right;">Collected SAR</th>
                <th style="text-align:right;">Pending SAR</th>
              </tr>
            </thead>
            <tbody id="masterProjectsBody">
              <tr><td colspan="17" class="loading-td">Loading master projects...</td></tr>
            </tbody>
          </table>
        </div>
      </div>
    </main>
  </div>
</div>

<script>
  let statusChartInstance = null;
  let repChartInstance = null;
  let projDonutRevenueInstance = null;
  let projBarPoStatusInstance = null;
  let projBarMilestoneCashInstance = null;
  let projBarContractRealizationInstance = null;
  let allPipelineRecords = [];
  let allProjectRecords = [];

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

    const btn = Array.from(document.querySelectorAll('.exec-tab-btn')).find(b => b.getAttribute('onclick') && b.getAttribute('onclick').includes(tabId));
    if (btn) btn.classList.add('active');
    
    const target = document.getElementById('tab-' + tabId);
    if (target) target.classList.add('active');

    const navItem = document.getElementById('nav-item-' + tabId);
    if (navItem) navItem.classList.add('active');

    if (tabId === 'all-pipeline' && allPipelineRecords.length === 0) {
      loadMasterPipeline();
    } else if (tabId === 'all-projects' && allProjectRecords.length === 0) {
      loadMasterProjects();
    }
  }

  async function loadDashboardMetrics(projectName = 'All Projects') {
    try {
      const isFiltered = (projectName && projectName !== 'All Projects' && projectName !== 'Company-Wide (All Projects)' && projectName !== 'Company-Wide Projects');
      let params = new URLSearchParams();
      if (isFiltered) params.append('project_name', projectName);

      const rep = document.getElementById('dashSalesPersonSelect')?.value;
      const status = document.getElementById('dashSalesStatusSelect')?.value;
      const client = document.getElementById('dashSalesClientSelect')?.value;

      if (rep) params.append('sales_person', rep);
      if (status) params.append('decision_status', status);
      if (client) params.append('client', client);

      const url = 'api/v4_dashboard.php' + (params.toString() ? '?' + params.toString() : '');
      const res = await fetch(url);
      const data = await res.json();
      if (data.success) {
        if (data.sales_dashboard) {
          renderSalesDashboard(data.sales_dashboard);
        }
        if (data.projects_dashboard) {
          renderProjectsDashboard(data.projects_dashboard);
        }
        if (document.getElementById('dashProjectSelect') && document.getElementById('dashProjectSelect').options.length <= 1) {
          populateProjectSelector(data.projects_list);
        }
        populatePipelineFilterOptions(data);
      }
    } catch (err) {
      console.error(err);
    }
  }

  function populatePipelineFilterOptions(data) {
    const repSel = document.getElementById('dashSalesPersonSelect');
    if (repSel && repSel.options.length <= 1 && data.sales_people) {
      data.sales_people.forEach(p => {
        const opt = document.createElement('option');
        opt.value = p;
        opt.textContent = p;
        repSel.appendChild(opt);
      });
    }

    const stSel = document.getElementById('dashSalesStatusSelect');
    if (stSel && stSel.options.length <= 1 && data.decision_statuses) {
      data.decision_statuses.forEach(s => {
        const opt = document.createElement('option');
        opt.value = s;
        opt.textContent = s;
        stSel.appendChild(opt);
      });
    }

    const clSel = document.getElementById('dashSalesClientSelect');
    if (clSel && clSel.options.length <= 1 && data.clients_list) {
      data.clients_list.forEach(c => {
        const opt = document.createElement('option');
        opt.value = c;
        opt.textContent = c;
        clSel.appendChild(opt);
      });
    }
  }

  function loadSalesDashboardFiltered() {
    loadDashboardMetrics(document.getElementById('dashProjectSelect')?.value || 'All Projects');
  }

  function resetSalesDashboardFilters() {
    if (document.getElementById('dashSalesPersonSelect')) document.getElementById('dashSalesPersonSelect').value = '';
    if (document.getElementById('dashSalesStatusSelect')) document.getElementById('dashSalesStatusSelect').value = '';
    if (document.getElementById('dashSalesClientSelect')) document.getElementById('dashSalesClientSelect').value = '';
    loadSalesDashboardFiltered();
  }

  function populateProjectSelector(list) {
    const sel = document.getElementById('dashProjectSelect');
    sel.innerHTML = list.map(p => `<option value="${escapeHtml(p)}">${escapeHtml(p)}</option>`).join('');
  }

  function renderSalesDashboard(s) {
    document.getElementById('dashTotalPipeline').innerHTML = formatSar(s.total_pipeline) + ' <span class="curr">SAR</span>';
    document.getElementById('dashWonVal').innerHTML = formatSar(s.won_value) + ' <span class="curr">SAR</span>';
    document.getElementById('dashUnderPricingVal').innerHTML = formatSar(s.under_pricing_value) + ' <span class="curr">SAR</span>';
    document.getElementById('dashWinRate').innerText = s.win_rate_pct + '%';

    // Status Donut Chart with Semantic Status Colors
    const statusLabels = s.by_status.map(x => x.status);
    const statusCounts = s.by_status.map(x => x.count);
    const statusColors = statusLabels.map(st => getDecisionStatusColor(st));

    const ctxStatus = document.getElementById('statusChart').getContext('2d');
    if (statusChartInstance) statusChartInstance.destroy();
    statusChartInstance = new Chart(ctxStatus, {
      type: 'doughnut',
      data: {
        labels: statusLabels,
        datasets: [{
          data: statusCounts,
          backgroundColor: statusColors,
          borderWidth: 2,
          borderColor: '#FFFFFF'
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { 
            position: 'right', 
            labels: { 
              color: '#475569', 
              font: { family: 'Inter', size: 11, weight: '500' },
              padding: 10,
              usePointStyle: true
            } 
          }
        }
      }
    });

    // Rep Bar Chart
    const repLabels = s.by_rep.map(x => x.salesperson);
    const repVals = s.by_rep.map(x => x.total_val);

    const ctxRep = document.getElementById('repChart').getContext('2d');
    if (repChartInstance) repChartInstance.destroy();
    repChartInstance = new Chart(ctxRep, {
      type: 'bar',
      data: {
        labels: repLabels,
        datasets: [{
          label: 'Total Pipeline (SAR)',
          data: repVals,
          backgroundColor: '#2878D0',
          borderRadius: 6
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          x: { ticks: { color: '#64748B', font: { family: 'Inter', size: 11 } }, grid: { display: false } },
          y: { ticks: { color: '#64748B', font: { family: 'Inter', size: 11 } }, grid: { color: '#EDF2F7' } }
        },
        plugins: { legend: { display: false } }
      }
    });

    // Excel Table 1: Pipeline Bids by Status
    const statusTbody = document.getElementById('salesStatusBody');
    if (s.by_status_table && s.by_status_table.length > 0) {
      const salesRowClassMap = {
        'Information Gathering': 'row-info-gathering',
        'Under Review': 'row-under-review',
        'Pricing in Progress': 'row-pricing',
        'Quotation Submitted': 'row-submitted',
        'Won': 'row-won',
        'Lost': 'row-lost',
        'On Hold': 'row-on-hold'
      };

      const totalStatusCount = s.by_status_table.reduce((sum, row) => sum + (parseInt(row.count, 10) || 0), 0);
      const totalStatusVal = s.by_status_table.reduce((sum, row) => sum + (parseFloat(row.total_val) || 0), 0);

      statusTbody.innerHTML = s.by_status_table.map(row => {
        const rowCls = salesRowClassMap[row.status] || '';
        return `
          <tr class="${rowCls}">
            <td><strong>${escapeHtml(row.status)}</strong></td>
            <td class="text-center">${row.count}</td>
            <td class="sar-cell">${formatSarDec(row.total_val, 2)}</td>
            <td class="text-right">${formatPct(row.pct_of_total)}</td>
            <td>${escapeHtml(row.expected_closing || '-')}</td>
          </tr>
        `;
      }).join('') + `
        <tr class="excel-total-row">
          <td><strong>Total Active Pipeline</strong></td>
          <td class="text-center">${totalStatusCount}</td>
          <td class="sar-cell">${formatSarDec(totalStatusVal, 2)}</td>
          <td class="text-right">${totalStatusVal > 0 ? '100.0%' : '0.0%'}</td>
          <td>-</td>
        </tr>
      `;
    }

    // Excel Table 2: Pipeline by Sales Representative
    const repTbody = document.getElementById('salesRepsBody');
    if (repTbody) {
      if (s.by_rep_table && s.by_rep_table.length > 0) {
        const totalLeads = s.by_rep_table.reduce((sum, r) => sum + (parseInt(r.inquiries_count || r.active_leads || 0, 10)), 0);
        const totalPipe = parseFloat(s.total_pipeline || 0);

        repTbody.innerHTML = s.by_rep_table.map(r => {
          const pipeVal = parseFloat(r.total_pipeline || 0);
          const leads = parseInt(r.inquiries_count || r.active_leads || 0, 10);
          const sharePct = totalPipe > 0 ? ((pipeVal / totalPipe) * 100).toFixed(1) + '%' : '0.0%';
          return `
            <tr>
              <td><strong>${escapeHtml(r.salesperson)}</strong></td>
              <td class="text-center">${leads}</td>
              <td class="sar-cell">${formatSarDec(pipeVal, 2)}</td>
              <td class="text-right">${sharePct}</td>
            </tr>
          `;
        }).join('') + `
          <tr class="excel-total-row">
            <td><strong>Total Team Pipeline</strong></td>
            <td class="text-center">${totalLeads}</td>
            <td class="sar-cell">${formatSarDec(totalPipe, 2)}</td>
            <td class="text-right">${totalPipe > 0 ? '100.0%' : '0.0%'}</td>
          </tr>
        `;
      } else {
        repTbody.innerHTML = `<tr><td colspan="4" class="text-center" style="padding:20px;color:var(--color-text-muted);">No pipeline inquiries recorded yet. Team members can start adding bids from the Pipeline tab.</td></tr>`;
      }
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
            <td class="sar-cell">${formatSarDec(row.value, 2)}</td>
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
          <td class="sar-cell">${formatSarDec(totalVal, 2)}</td>
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
            <td class="sar-cell">${formatSarDec(row.value, 2)}</td>
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
          <td class="sar-cell">${formatSarDec(totalInvVal, 2)}</td>
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
              <td class="sar-cell">${formatSarDec(row.amount, 2)}</td>
              <td class="text-right">${formatPct(row.pct_of_contract)}</td>
            </tr>
          `;
        }
        return `
          <tr>
            <td><strong>${escapeHtml(row.stage)}</strong></td>
            <td class="sar-cell">${formatSarDec(row.amount, 2)}</td>
            <td class="text-right">${formatPct(row.pct_of_contract)}</td>
          </tr>
        `;
      }).join('');
    }

    // 4. Excel Table 4: PM Performance Table
    const pmTbody = document.getElementById('pmTableBody');
    if (p.pm_table) {
      let totalPmProj = 0, totalPmMs = 0, totalPmVal = 0, totalPmInv = 0, totalPmCol = 0, totalPmPend = 0;
      let rows = p.pm_table.map(r => {
        const pCnt = parseInt(r.projects_count, 10) || 0;
        const mCnt = parseInt(r.milestones_count, 10) || 0;
        const val = parseFloat(r.total_value) || 0;
        const inv = parseFloat(r.invoiced) || 0;
        const col = parseFloat(r.collected) || 0;
        const pend = parseFloat(r.pending) || 0;

        totalPmProj += pCnt;
        totalPmMs += mCnt;
        totalPmVal += val;
        totalPmInv += inv;
        totalPmCol += col;
        totalPmPend += pend;

        return `
          <tr>
            <td><strong>${escapeHtml(r.pm)}</strong></td>
            <td class="text-center">${pCnt}</td>
            <td class="text-center">${mCnt}</td>
            <td class="sar-cell">${formatSarDec(val, 2)}</td>
            <td class="sar-cell">${formatSarDec(inv, 2)}</td>
            <td class="sar-cell" style="color:#059669;font-weight:600;">${formatSarDec(col, 2)}</td>
            <td class="sar-cell" style="color:#B45309;font-weight:600;">${formatSarDec(pend, 2)}</td>
            <td class="text-right" style="font-weight:700;">${r.collection_efficiency}%</td>
          </tr>
        `;
      }).join('');

      const totalPmEff = (totalPmInv > 0) ? Math.round((totalPmCol / totalPmInv) * 100) : 0;
      rows += `
        <tr class="excel-total-row">
          <td><strong>Total PM Delivery</strong></td>
          <td class="text-center">${totalPmProj}</td>
          <td class="text-center">${totalPmMs}</td>
          <td class="sar-cell">${formatSarDec(totalPmVal, 2)}</td>
          <td class="sar-cell">${formatSarDec(totalPmInv, 2)}</td>
          <td class="sar-cell">${formatSarDec(totalPmCol, 2)}</td>
          <td class="sar-cell">${formatSarDec(totalPmPend, 2)}</td>
          <td class="text-right">${totalPmEff}%</td>
        </tr>
      `;
      pmTbody.innerHTML = rows;
    }

    // =====================================================================
    // Excel v4 Projects Dashboard: 4 Interactive Charts
    // =====================================================================
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

    // Chart 2: Column (Milestone Deliveries by PO Status)
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
            backgroundColor: '#C53030',
            borderRadius: 6,
            maxBarThickness: 48
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

  async function loadMasterPipeline() {
    try {
      const res = await fetch('api/v4_pipeline.php');
      const json = await res.json();
      if (json.success) {
        allPipelineRecords = json.data;
        renderMasterPipeline(allPipelineRecords);
      }
    } catch (err) {
      console.error(err);
    }
  }

  function renderMasterPipeline(records) {
    const tbody = document.getElementById('masterPipelineBody');
    if (records.length === 0) {
      tbody.innerHTML = '<tr><td colspan="11" class="empty-td">No pipeline records found.</td></tr>';
      return;
    }

    tbody.innerHTML = records.map((r, i) => `
      <tr>
        <td>${i + 1}</td>
        <td><strong>${escapeHtml(r.sales_person || '')}</strong></td>
        <td><strong class="proj-title">${escapeHtml(r.project_name || '')}</strong></td>
        <td>${escapeHtml(r.client || '')}</td>
        <td>${escapeHtml(r.main_contractor || '-')}</td>
        <td><code>${escapeHtml(r.quotation_no || '-')}</code></td>
        <td class="sar-cell">${Number(r.total_sar || 0).toLocaleString('en-US', {minimumFractionDigits: 2})}</td>
        <td>${getStatusBadge(r.decision_status)}</td>
        <td><span class="missing-badge">${escapeHtml(r.missing_info || 'None')}</span></td>
        <td><span class="next-step-pill">${escapeHtml(r.next_step || '-')}</span></td>
        <td>${r.expected_closing || '-'}</td>
      </tr>
    `).join('');
  }

  function filterMasterPipeline() {
    const q = document.getElementById('masterPipelineSearch').value.toLowerCase().trim();
    const rep = document.getElementById('masterRepFilter').value;
    const status = document.getElementById('masterStatusFilter').value;

    const filtered = allPipelineRecords.filter(r => {
      const matchQ = !q || (r.project_name && r.project_name.toLowerCase().includes(q))
                        || (r.client && r.client.toLowerCase().includes(q))
                        || (r.sales_person && r.sales_person.toLowerCase().includes(q));
      const matchRep = !rep || (r.sales_person === rep);
      const matchStatus = !status || (r.decision_status === status);
      return matchQ && matchRep && matchStatus;
    });
    renderMasterPipeline(filtered);
  }

  async function loadMasterProjects() {
    try {
      const res = await fetch('api/v4_projects.php');
      const json = await res.json();
      if (json.success) {
        allProjectRecords = json.data;
        renderMasterProjects(allProjectRecords);
      }
    } catch (err) {
      console.error(err);
    }
  }

  function renderMasterProjects(records) {
    const tbody = document.getElementById('masterProjectsBody');
    if (records.length === 0) {
      tbody.innerHTML = '<tr><td colspan="17" class="empty-td">No project records found.</td></tr>';
      return;
    }

    tbody.innerHTML = records.map((r, i) => `
      <tr>
        <td>${i + 1}</td>
        <td><strong>${escapeHtml(r.project_manager || '-')}</strong></td>
        <td><strong class="proj-title">${escapeHtml(r.project_name || '')}</strong></td>
        <td>${escapeHtml(r.customer || '')}</td>
        <td><code>${escapeHtml(r.po_no || '-')}</code></td>
        <td class="sar-cell">${Number(r.total_po_value || 0).toLocaleString('en-US')}</td>
        <td><span class="badge badge-purple">${escapeHtml(r.milestone_no || '-')}</span></td>
        <td><span class="scope-text">${escapeHtml(r.milestone_desc || '-')}</span></td>
        <td class="sar-cell">${Number(r.milestone_value || 0).toLocaleString('en-US')}</td>
        <td>${getMilestoneBadge(r.milestone_status)}</td>
        <td><span class="missing-badge">${escapeHtml(r.technical_report || '-')}</span></td>
        <td>${getInvoiceBadge(r.invoice_status)}</td>
        <td><code>${escapeHtml(r.invoice_no || '-')}</code></td>
        <td class="sar-cell">${Number(r.invoice_amount || 0).toLocaleString('en-US')}</td>
        <td>${getCollectionBadge(r.collection_status)}</td>
        <td class="sar-cell" style="color:#059669;font-weight:600;">${Number(r.collected_amount || 0).toLocaleString('en-US')}</td>
        <td class="sar-cell" style="color:#B45309;font-weight:600;">${Number(r.pending_collection || 0).toLocaleString('en-US')}</td>
      </tr>
    `).join('');
  }

  function filterMasterProjects() {
    const q = document.getElementById('masterProjectsSearch').value.toLowerCase().trim();
    const pm = document.getElementById('masterPmFilter').value;

    const filtered = allProjectRecords.filter(r => {
      const matchQ = !q || (r.project_name && r.project_name.toLowerCase().includes(q))
                        || (r.customer && r.customer.toLowerCase().includes(q))
                        || (r.project_manager && r.project_manager.toLowerCase().includes(q));
      const matchPm = !pm || (r.project_manager === pm);
      return matchQ && matchPm;
    });
    renderMasterProjects(filtered);
  }

  function getDecisionStatusColor(status) {
    const s = (status || '').trim().toLowerCase();
    if (s === 'won') return '#10B981';                  // Emerald Green (Won)
    if (s === 'lost') return '#EF4444';                 // Red (Lost)
    if (s === 'quotation submitted') return '#2563EB';  // Royal Blue
    if (s === 'pricing in progress') return '#F59E0B';  // Amber
    if (s === 'under review') return '#8B5CF6';         // Purple / Violet
    if (s === 'information gathering') return '#0EA5E9';// Cyan / Sky Blue
    if (s === 'on hold') return '#64748B';              // Slate Gray
    return '#94A3B8';
  }

  function getStatusBadge(status) {
    status = status || 'Information Gathering';
    const s = status.trim().toLowerCase();
    let cls = 'badge-gray';
    if (s === 'won') cls = 'badge-green';
    else if (s === 'lost') cls = 'badge-red';
    else if (s === 'quotation submitted') cls = 'badge-blue';
    else if (s === 'pricing in progress') cls = 'badge-amber';
    else if (s === 'under review') cls = 'badge-purple';
    else if (s === 'information gathering') cls = 'badge-cyan';
    else if (s === 'on hold') cls = 'badge-gray';
    return `<span class="badge ${cls}">${escapeHtml(status)}</span>`;
  }

  function getPoStatusBadge(status) {
    status = status || 'In Progress';
    let cls = 'badge-blue';
    if (status === 'Completed') cls = 'badge-green';
    else if (status === 'On Hold') cls = 'badge-amber';
    else if (status === 'Cancelled') cls = 'badge-red';
    return `<span class="badge ${cls}">${escapeHtml(status)}</span>`;
  }

  function getMilestoneBadge(status) {
    status = status || 'Pending';
    let cls = 'badge-gray';
    if (status === 'Completed') cls = 'badge-green';
    else if (status === 'In Progress') cls = 'badge-amber';
    else if (status === 'On Hold') cls = 'badge-gray';
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

  function getCollectionBadge(status) {
    status = status || 'Pending';
    let cls = 'badge-blue';
    if (status === 'Fully Collected') cls = 'badge-green';
    else if (status === 'Partially Collected') cls = 'badge-amber';
    else if (status === 'Overdue') cls = 'badge-red';
    return `<span class="badge ${cls}">${escapeHtml(status)}</span>`;
  }

  function formatSar(num) {
    return Number(num).toLocaleString('en-US', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
  }

  function formatSarDec(num, dec = 2) {
    return Number(num || 0).toLocaleString('en-US', { minimumFractionDigits: dec, maximumFractionDigits: dec });
  }

  function formatPct(num) {
    return Number(num || 0).toFixed(1) + '%';
  }

  function escapeHtml(str) {
    if (!str) return '';
    return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
  }

  function exportMasterPipelineCsv() {
    if (allPipelineRecords.length === 0) return alert('No data to export');
    const headers = ["No.", "Sales Person", "Project Name", "Client", "Main Contractor", "Quotation No", "Total SAR", "Decision Status", "Expected Closing", "Next Step"];
    const rows = allPipelineRecords.map((r, i) => [
      i + 1,
      `"${(r.sales_person||'').replace(/"/g, '""')}"`,
      `"${(r.project_name||'').replace(/"/g, '""')}"`,
      `"${(r.client||'').replace(/"/g, '""')}"`,
      `"${(r.main_contractor||'').replace(/"/g, '""')}"`,
      `"${(r.quotation_no||'').replace(/"/g, '""')}"`,
      r.total_sar || 0,
      `"${(r.decision_status||'').replace(/"/g, '""')}"`,
      `"${(r.expected_closing||'').replace(/"/g, '""')}"`,
      `"${(r.next_step||'').replace(/"/g, '""')}"`
    ]);
    downloadCsv(headers, rows, 'Master_Sales_Pipeline');
  }

  function exportMasterProjectsCsv() {
    if (allProjectRecords.length === 0) return alert('No data to export');
    const headers = ["No.", "PM", "Project Name", "Customer", "PO No", "Total PO SAR", "Milestone #", "Milestone Description", "Milestone SAR", "Status", "Tech Report", "Invoice No", "Invoiced SAR", "Collected SAR", "Pending SAR"];
    const rows = allProjectRecords.map((r, i) => [
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
      `"${(r.invoice_no||'').replace(/"/g, '""')}"`,
      r.invoice_amount || 0,
      r.collected_amount || 0,
      r.pending_collection || 0
    ]);
    downloadCsv(headers, rows, 'Master_Awarded_Projects');
  }

  function downloadCsv(headers, rows, prefix) {
    const csvContent = "data:text/csv;charset=utf-8," + [headers.join(','), ...rows.map(e => e.join(','))].join('\n');
    const encodedUri = encodeURI(csvContent);
    const link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", `${prefix}_${new Date().toISOString().split('T')[0]}.csv`);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
  }

  document.addEventListener('DOMContentLoaded', () => {
    const params = new URLSearchParams(window.location.search);
    const tab = params.get('tab');
    if (tab) {
      switchTab(tab);
    }
    loadDashboardMetrics();
  });
</script>

<?php require_once __DIR__ . '/profile modal.php'; ?>

</body>
</html>

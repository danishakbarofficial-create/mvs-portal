<?php
require_once __DIR__ . '/auth.php';
$user = Auth::requireLogin(['sales', 'executive']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pipeline &bull; MVS ICT &amp; Telecom</title>
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
      <?php endif; ?>

      <div class="nav-section-label"><?= ($user['role'] === 'sales') ? 'Commercial Workspace' : 'Sales Pipeline' ?></div>
      <a href="javascript:void(0)" onclick="switchTab('sales-dash')" class="nav-item active" id="nav-item-sales-dash">
        <svg class="nav-item-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;flex-shrink:0;">
          <line x1="12" y1="20" x2="12" y2="10"></line>
          <line x1="18" y1="20" x2="18" y2="4"></line>
          <line x1="6" y1="20" x2="6" y2="16"></line>
        </svg>
        <span class="nav-item-text">Pipeline Dashboard</span>
      </a>

      <a href="javascript:void(0)" onclick="switchTab('sales-register')" class="nav-item" id="nav-item-sales-register">
        <svg class="nav-item-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;flex-shrink:0;">
          <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
          <line x1="3" y1="9" x2="21" y2="9"></line>
          <line x1="9" y1="21" x2="9" y2="9"></line>
        </svg>
        <span class="nav-item-text"><?= ($user['role'] === 'sales') ? 'My Sales Pipeline' : 'Pipeline Register' ?></span>
      </a>

      <?php if ($user['role'] === 'executive' || $user['role'] === 'admin'): ?>
      <a href="pm_portal.php" class="nav-item">
        <svg class="nav-item-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;flex-shrink:0;">
          <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
        </svg>
        <span class="nav-item-text">Projects</span>
      </a>
      <?php endif; ?>

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
        <span class="nav-item-text">+ New Inquiry / Bid</span>
      </a>
    </nav>

    <div class="sidebar-footer">
      <div class="user-mini-card" onclick="openMyProfileModal()" style="cursor: pointer;" title="View &amp; Edit Profile">
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
          <span>Commercial</span>
          <span class="breadcrumb-separator">/</span>
          <span class="breadcrumb-current">Sales Pipeline Register</span>
        </nav>
      </div>

      <div class="topbar-right">
        <div class="tenant-pill">
          <span class="tenant-dot"></span>
          <span>MVS ICT &amp; Telecom</span>
        </div>
        <span class="topbar-role-badge role-badge-sales"><?= ($user['role'] === 'executive') ? 'Executive View' : 'Sales Representative' ?></span>
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
      <div class="exec-nav-tabs">
        <button class="exec-tab-btn active" id="btn-tab-sales-dash" onclick="switchTab('sales-dash')">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="12" y1="20" x2="12" y2="10"></line>
            <line x1="18" y1="20" x2="18" y2="4"></line>
            <line x1="6" y1="20" x2="6" y2="16"></line>
          </svg>
          <span>Pipeline Dashboard</span>
        </button>
        <button class="exec-tab-btn" id="btn-tab-sales-register" onclick="switchTab('sales-register')">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
            <line x1="3" y1="9" x2="21" y2="9"></line>
            <line x1="9" y1="21" x2="9" y2="9"></line>
          </svg>
          <span>Pipeline Register</span>
        </button>
      </div>

      <!-- ===================================================================
           TAB 1: Commercial & Sales Pipeline Dashboard (Scoped to Sales Rep)
           =================================================================== -->
      <div class="tab-content active" id="tab-sales-dash">
        <div class="action-bar" style="margin-bottom: 20px;">
          <div class="action-left">
            <span style="font-size:0.85rem;font-weight:600;color:var(--color-text-secondary);">
              My Sales Performance &amp; Active Bids
            </span>
          </div>
          <div class="action-right">
            <button class="btn btn-primary" onclick="openCreateModal()">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
              </svg>
              <span>+ New Inquiry / Bid</span>
            </button>
          </div>
        </div>

        <!-- 4 Top KPI Cards -->
        <div class="kpi-grid">
          <div class="kpi-card highlight-blue">
            <div class="kpi-label">Total Active Pipeline</div>
            <div class="kpi-val" id="dashTotalPipeline">0 <span class="curr">SAR</span></div>
            <div class="kpi-sub">Total value of all active inquiries</div>
          </div>
          <div class="kpi-card highlight-won">
            <div class="kpi-label">Won Projects Value</div>
            <div class="kpi-val" id="dashWonVal">0 <span class="curr">SAR</span></div>
            <div class="kpi-sub">Successfully approved contracts</div>
          </div>
          <div class="kpi-card highlight-review">
            <div class="kpi-label">Under Pricing / Review</div>
            <div class="kpi-val" id="dashUnderPricingVal">0 <span class="curr">SAR</span></div>
            <div class="kpi-sub">Bids in active commercial prep</div>
          </div>
          <div class="kpi-card highlight-green">
            <div class="kpi-label">Win Rate (% Decided)</div>
            <div class="kpi-val" id="dashWinRate">0%</div>
            <div class="kpi-sub">Ratio of won vs lost bids</div>
          </div>
        </div>

        <!-- 2 Visual Charts -->
        <div class="charts-row">
          <div class="chart-card">
            <div class="chart-title">
              <span>My Inquiries by Pipeline Status</span>
            </div>
            <div class="chart-container">
              <canvas id="statusChart"></canvas>
            </div>
          </div>
          <div class="chart-card">
            <div class="chart-title">
              <span>Pipeline Value by Stage (SAR)</span>
            </div>
            <div class="chart-container">
              <canvas id="stageValChart"></canvas>
            </div>
          </div>
        </div>

        <!-- Table 1: Pipeline Bids by Status (Excel Themed) -->
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
      </div>

      <!-- ===================================================================
           TAB 2: My Opportunities & Bids Register
           =================================================================== -->
      <div class="tab-content" id="tab-sales-register">
        <div class="page-header-row">
          <div class="page-header-title-box">
            <h1><?= ($user['role'] === 'sales') ? 'My Sales Pipeline' : 'Pipeline Register' ?></h1>
            <p>Real-time register of pre-award inquiries, client tenders, quotations, and expected closings.</p>
          </div>
          <div class="page-header-actions" style="display:flex;gap:8px;align-items:center;">
            <a href="api/export master excel.php?type=pipelines" class="btn" style="background-color: #107C41; color: #ffffff; border: 1px solid #0b5e31; display: inline-flex; align-items: center; gap: 6px; font-weight: 600; text-decoration: none; padding: 8px 14px; border-radius: 6px; font-size: 0.85rem;">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                <polyline points="7 10 12 15 17 10"></polyline>
                <line x1="12" y1="15" x2="12" y2="3"></line>
              </svg>
              <span>Download Pipelines Excel</span>
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
              <span>New Inquiry / Bid</span>
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
              <input type="text" id="searchInput" placeholder="Search project, client, quotation..." onkeyup="filterPipeline()">
            </div>
            <select id="statusFilter" onchange="filterPipeline()">
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
        </div>

        <!-- v4 Pipeline Data Table -->
        <div class="table-container">
          <table class="v4-table" id="pipelineTable">
            <thead>
              <tr>
                <th>No.</th>
                <th>Sales Person</th>
                <th>Project Name</th>
                <th>Client</th>
                <th>Scope of Work</th>
                <th>Quotation No.</th>
                <th style="text-align:right;">Total SAR (incl. VAT)</th>
                <th>Decision Status</th>
                <th>Missing Info</th>
                <th>Submission Date</th>
                <th>Expected Closing</th>
                <th>Next Step</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody id="pipelineTableBody">
              <tr>
                <td colspan="13" class="loading-td">Loading pipeline records...</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </main>
  </div>
</div>

<!-- Create/Edit Opportunity Modal (All 22 v4 Columns) -->
<div class="modal-overlay" id="opportunityModal">
  <div class="modal-box">
    <div class="modal-header">
      <div class="modal-header-meta">
        <span class="modal-badge">COMMERCIAL PIPELINE</span>
        <h2 id="modalTitle">Log New Inquiry / Bid</h2>
      </div>
      <button type="button" class="modal-close-btn" onclick="closeModal()" title="Close">&times;</button>
    </div>
    <div class="modal-body">
      <form id="opportunityForm" onsubmit="saveOpportunity(event)">
        <input type="hidden" id="editId" name="id">

        <!-- Card 1: Project & Client Details -->
        <div class="form-card">
          <div class="form-card-header">
            <div class="form-card-icon icon-blue">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
              </svg>
            </div>
            <div class="form-card-title-box">
              <span class="form-card-title">1. Project &amp; Client Information</span>
              <span class="form-card-desc">Core project scope, client entity and commercial ownership</span>
            </div>
          </div>

          <div class="form-row col-2">
            <div class="form-group">
              <label>Project Name <span class="req-star">*</span></label>
              <input type="text" id="projectName" required placeholder="e.g. CCTV / Cameras Expansion Project">
            </div>
            <div class="form-group">
              <label>Client / Organization <span class="req-star">*</span></label>
              <input type="text" id="client" required placeholder="e.g. Al Tamimi Company">
            </div>
          </div>

          <div class="form-row col-3">
            <div class="form-group">
              <label>Sales Representative</label>
              <input type="text" id="salesPerson" value="<?= htmlspecialchars($user['name']) ?>" <?= ($user['role'] === 'sales') ? 'readonly style="background-color: #F1F5F9; color: #475569;"' : '' ?>>
            </div>
            <div class="form-group">
              <label>Main Contractor</label>
              <input type="text" id="mainContractor" placeholder="e.g. Al Bawani">
            </div>
            <div class="form-group">
              <label>Consultant</label>
              <input type="text" id="consultant" placeholder="e.g. Dar Al Handasah">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label>Brief Scope &amp; Technical Work</label>
              <input type="text" id="scopeOfWork" placeholder="Short description of technical scope (e.g. Supply & installation of optical distribution frames)">
            </div>
          </div>
        </div>

        <!-- Card 2: Quotation & Commercial Valuation -->
        <div class="form-card">
          <div class="form-card-header">
            <div class="form-card-icon icon-emerald">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="1" x2="12" y2="23"></line>
                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
              </svg>
            </div>
            <div class="form-card-title-box">
              <span class="form-card-title">2. Quotation &amp; Commercial Valuation</span>
              <span class="form-card-desc">Quotation numbering, pricing in Saudi Riyals and submission timeline</span>
            </div>
          </div>

          <div class="form-row col-3">
            <div class="form-group">
              <label>Quotation No.</label>
              <input type="text" id="quotationNo" placeholder="e.g. QT-2026-089">
            </div>
            <div class="form-group">
              <label>Quotation Status</label>
              <select id="quotationStatus" onchange="onQuotationStatusChange()">
                <option value="Open">Open</option>
                <option value="Approved">Approved</option>
                <option value="On Hold">On Hold</option>
                <option value="Closed">Closed</option>
                <option value="Not Approved">Not Approved</option>
                <option value="Cancelled">Cancelled</option>
              </select>
            </div>
            <div class="form-group">
              <label>Total incl. VAT (SAR) <span class="req-star">*</span></label>
              <div class="input-with-prefix">
                <span class="input-prefix">SAR</span>
                <input type="number" step="0.01" id="totalSar" placeholder="0.00" required>
              </div>
            </div>
          </div>

          <div class="form-row col-4">
            <div class="form-group">
              <label>Date Received</label>
              <input type="date" id="dateReceived">
            </div>
            <div class="form-group">
              <label>Submission Date</label>
              <input type="date" id="submissionDate">
            </div>
            <div class="form-group">
              <label>Quotation Valid Until</label>
              <input type="date" id="quotationValidUntil">
            </div>
            <div class="form-group">
              <label>Expected Closing Month</label>
              <input type="month" id="expectedClosing">
            </div>
          </div>
        </div>

        <!-- Card 3: Decision Governance & Closing Actions -->
        <div class="form-card" style="margin-bottom: 0;">
          <div class="form-card-header">
            <div class="form-card-icon icon-purple">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"></circle>
                <polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"></polygon>
              </svg>
            </div>
            <div class="form-card-title-box">
              <span class="form-card-title">3. Decision Governance &amp; Follow-Up</span>
              <span class="form-card-desc">Decision status, client point of contact, missing information and next steps</span>
            </div>
          </div>

          <div class="form-row col-2">
            <div class="form-group">
              <label>Client Contact Person</label>
              <input type="text" id="contactPerson" placeholder="Name of decision maker / project engineer">
            </div>
            <div class="form-group">
              <label>Contact Details (Phone / Email)</label>
              <input type="text" id="contactDetails" placeholder="e.g. +966 50 123 4567 / name@client.com">
            </div>
          </div>

          <div class="form-row col-3">
            <div class="form-group">
              <label>Decision Status</label>
              <select id="decisionStatus" onchange="onDecisionStatusChange()">
                <option value="Information Gathering">Information Gathering</option>
                <option value="Under Review">Under Review</option>
                <option value="Pricing in Progress">Pricing in Progress</option>
                <option value="Quotation Submitted">Quotation Submitted</option>
                <option value="Won">Won</option>
                <option value="Lost">Lost</option>
                <option value="On Hold">On Hold</option>
              </select>
            </div>
            <div class="form-group">
              <label>Missing Information</label>
              <select id="missingInfo">
                <option value="None - Complete">None - Complete</option>
                <option value="Scope of Work">Scope of Work</option>
                <option value="BOQ / Item List">BOQ / Item List</option>
                <option value="Budget / Pricing Details">Budget / Pricing Details</option>
                <option value="Drawings & Specifications">Drawings &amp; Specifications</option>
                <option value="Timeline / Schedule">Timeline / Schedule</option>
                <option value="Client Approval">Client Approval</option>
                <option value="Multiple Items (see Notes)">Multiple Items (see Notes)</option>
              </select>
            </div>
            <div class="form-group">
              <label>Follow-up Date</label>
              <input type="date" id="followUpDate">
            </div>
          </div>

          <div class="form-row col-2">
            <div class="form-group">
              <label>Immediate Next Step</label>
              <input type="text" id="nextStep" placeholder="e.g. Submit revised price proposal after technical review">
            </div>
            <div class="form-group">
              <label>Internal Notes</label>
              <input type="text" id="notes" placeholder="General background notes, discount rationale, etc.">
            </div>
          </div>
        </div>

        <div class="modal-footer" style="margin-top: 18px;">
          <div style="font-size: 0.72rem; color: var(--color-text-secondary); display: flex; align-items: center; gap: 4px;">
            <span class="req-star">*</span> Required fields for sales pipeline governance
          </div>
          <div style="display: flex; gap: 10px;">
            <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
            <button type="submit" class="btn btn-primary" id="saveBtn">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                <polyline points="17 21 17 13 7 13 7 21"></polyline>
                <polyline points="7 3 7 8 15 8"></polyline>
              </svg>
              <span>Save Opportunity</span>
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  let allRecords = [];
  let statusChartInstance = null;
  let stageValChartInstance = null;

  function switchTab(tabId) {
    document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
    document.querySelectorAll('.exec-tab-btn').forEach(el => el.classList.remove('active'));
    document.querySelectorAll('.sidebar .nav-item').forEach(el => el.classList.remove('active'));

    const targetTab = document.getElementById('tab-' + tabId);
    if (targetTab) targetTab.classList.add('active');

    const btn = document.getElementById('btn-tab-' + tabId);
    if (btn) btn.classList.add('active');

    const navItem = document.getElementById('nav-item-' + tabId);
    if (navItem) navItem.classList.add('active');
  }

  async function loadDashboardMetrics() {
    try {
      const res = await fetch('api/v4_dashboard.php');
      const json = await res.json();
      if (json.success && json.sales_dashboard) {
        renderSalesDashboard(json.sales_dashboard);
      }
    } catch (err) {
      console.error('Failed to load dashboard metrics:', err);
    }
  }

  function renderSalesDashboard(s) {
    const totalPipe = document.getElementById('dashTotalPipeline');
    if (totalPipe) totalPipe.innerHTML = formatSar(s.total_pipeline) + ' <span class="curr">SAR</span>';

    const wonVal = document.getElementById('dashWonVal');
    if (wonVal) wonVal.innerHTML = formatSar(s.won_value ?? s.won_val ?? 0) + ' <span class="curr">SAR</span>';

    const underPricingVal = document.getElementById('dashUnderPricingVal');
    if (underPricingVal) underPricingVal.innerHTML = formatSar(s.under_pricing_value ?? s.under_pricing_val ?? 0) + ' <span class="curr">SAR</span>';

    const winRate = document.getElementById('dashWinRate');
    if (winRate) winRate.innerText = (s.win_rate_pct ?? s.win_rate ?? 0) + '%';

    // Populate Status breakdown table (Exact Excel Row Colors & Total Bar)
    const tbody = document.getElementById('salesStatusBody');
    if (tbody && s.by_status_table) {
      const salesRowClassMap = {
        'Information Gathering': 'row-info-gathering',
        'Under Review': 'row-under-review',
        'Pricing in Progress': 'row-pricing',
        'Quotation Submitted': 'row-submitted',
        'Won': 'row-won',
        'Lost': 'row-lost',
        'On Hold': 'row-on-hold'
      };

      const totalCount = s.by_status_table.reduce((sum, row) => sum + (parseInt(row.count, 10) || 0), 0);
      const totalVal = s.by_status_table.reduce((sum, row) => sum + (parseFloat(row.total_val) || 0), 0);

      tbody.innerHTML = s.by_status_table.map(row => {
        const rowCls = salesRowClassMap[row.status] || '';
        return `
          <tr class="${rowCls}">
            <td><strong>${escapeHtml(row.status)}</strong></td>
            <td class="text-center">${row.count}</td>
            <td class="sar-cell">${formatSarDec(row.total_val)}</td>
            <td class="text-right">${formatPct(row.pct_of_total)}</td>
            <td>${escapeHtml(row.expected_closing || '-')}</td>
          </tr>
        `;
      }).join('') + `
        <tr class="excel-total-row">
          <td><strong>Total Active Pipeline</strong></td>
          <td class="text-center">${totalCount}</td>
          <td class="sar-cell">${formatSarDec(totalVal)}</td>
          <td class="text-right">${totalVal > 0 ? '100.0%' : '0.0%'}</td>
          <td>-</td>
        </tr>
      `;
    }

    // Chart 1: Doughnut (Inquiries by Status)
    if (s.by_status && document.getElementById('statusChart')) {
      const ctx = document.getElementById('statusChart').getContext('2d');
      if (statusChartInstance) statusChartInstance.destroy();

      const labels = s.by_status.map(x => x.status);
      const dataCounts = s.by_status.map(x => x.count);
      const bgColors = [
        '#0284C7', // Info Gathering
        '#8B5CF6', // Under Review
        '#F59E0B', // Pricing in Progress
        '#3B82F6', // Quotation Submitted
        '#10B981', // Won
        '#EF4444', // Lost
        '#64748B'  // On Hold
      ];

      statusChartInstance = new Chart(ctx, {
        type: 'doughnut',
        data: {
          labels: labels,
          datasets: [{
            data: dataCounts,
            backgroundColor: bgColors.slice(0, labels.length),
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
                boxWidth: 12,
                font: { family: 'Inter', size: 11, weight: '500' },
                color: '#475569',
                padding: 10
              }
            },
            tooltip: {
              callbacks: {
                label: function(ctx) {
                  return ' ' + ctx.label + ': ' + ctx.raw + ' bids';
                }
              }
            }
          },
          cutout: '68%'
        }
      });
    }

    // Chart 2: Bar (Value by Stage in SAR)
    if (s.by_status && document.getElementById('stageValChart')) {
      const ctx = document.getElementById('stageValChart').getContext('2d');
      if (stageValChartInstance) stageValChartInstance.destroy();

      const labels = s.by_status.map(x => x.status);
      const dataVals = s.by_status.map(x => x.total_val);

      stageValChartInstance = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: labels,
          datasets: [{
            label: 'Pipeline Value (SAR)',
            data: dataVals,
            backgroundColor: '#0EA5E9',
            borderRadius: 6,
            maxBarThickness: 40
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          scales: {
            x: {
              ticks: {
                color: '#64748B',
                font: { family: 'Inter', size: 10 }
              },
              grid: { display: false }
            },
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
                  return ' Value: ' + formatSar(ctx.raw) + ' SAR';
                }
              }
            }
          }
        }
      });
    }
  }

  function toggleSidebar() {
    const sb = document.getElementById('appSidebar');
    if (window.innerWidth <= 1024) {
      sb.classList.toggle('mobile-open');
    } else {
      sb.classList.toggle('collapsed');
    }
  }

  async function loadPipeline() {
    try {
      const res = await fetch('api/v4_pipeline.php');
      const json = await res.json();
      if (json.success) {
        allRecords = json.data;
        renderPipeline(allRecords);
        calculateStats(allRecords);
      }
    } catch (err) {
      console.error(err);
      document.getElementById('pipelineTableBody').innerHTML = '<tr><td colspan="13" class="error-td">Failed to load pipeline records.</td></tr>';
    }
  }

  function calculateStats(records) {
    let totalPipeline = 0;
    let wonVal = 0;
    let pricingVal = 0;

    records.forEach(r => {
      const val = parseFloat(r.total_sar) || 0;
      totalPipeline += val;
      const status = (r.decision_status || '').toLowerCase();
      if (status === 'won' || (r.quotation_status || '').toLowerCase() === 'approved') {
        wonVal += val;
      } else if (status.includes('pricing') || status.includes('review') || status.includes('gathering')) {
        pricingVal += val;
      }
    });

    const p = document.getElementById('myPipelineVal');
    if (p) p.innerHTML = formatSar(totalPipeline) + ' <span class="curr">SAR</span>';
    const w = document.getElementById('myWonVal');
    if (w) w.innerHTML = formatSar(wonVal) + ' <span class="curr">SAR</span>';
    const pr = document.getElementById('myPricingVal');
    if (pr) pr.innerHTML = formatSar(pricingVal) + ' <span class="curr">SAR</span>';
    const c = document.getElementById('myTotalCount');
    if (c) c.innerText = records.length;
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

  function renderPipeline(records) {
    const tbody = document.getElementById('pipelineTableBody');
    if (records.length === 0) {
      tbody.innerHTML = '<tr><td colspan="13" class="empty-td">No opportunities found for this view.</td></tr>';
      return;
    }

    tbody.innerHTML = records.map((r, idx) => {
      const statusBadge = getStatusBadge(r.decision_status);
      const sarFormatted = Number(r.total_sar || 0).toLocaleString('en-US', { minimumFractionDigits: 2 });
      return `
        <tr>
          <td>${idx + 1}</td>
          <td><strong>${escapeHtml(r.sales_person || '')}</strong></td>
          <td><strong class="proj-title">${escapeHtml(r.project_name || '')}</strong></td>
          <td>${escapeHtml(r.client || '')}</td>
          <td><span class="scope-text" title="${escapeHtml(r.scope_of_work || '')}">${escapeHtml(r.scope_of_work || '-')}</span></td>
          <td><code>${escapeHtml(r.quotation_no || '-')}</code></td>
          <td class="sar-cell">${sarFormatted}</td>
          <td>${statusBadge}</td>
          <td><span class="missing-badge">${escapeHtml(r.missing_info || 'None')}</span></td>
          <td>${formatDate(r.submission_date)}</td>
          <td>${r.expected_closing || '-'}</td>
          <td><span class="next-step-pill">${escapeHtml(r.next_step || '-')}</span></td>
          <td>
            <button class="btn-action edit" onclick="openEditModal(${r.id})">Edit</button>
          </td>
        </tr>
      `;
    }).join('');
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

  function formatDate(d) {
    if (!d) return '-';
    return d.split(' ')[0];
  }

  function escapeHtml(str) {
    if (!str) return '';
    return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
  }

  function filterPipeline() {
    const q = document.getElementById('searchInput').value.toLowerCase().trim();
    const status = document.getElementById('statusFilter').value;

    const filtered = allRecords.filter(r => {
      const matchesQ = !q || (r.project_name && r.project_name.toLowerCase().includes(q))
                          || (r.client && r.client.toLowerCase().includes(q))
                          || (r.quotation_no && r.quotation_no.toLowerCase().includes(q));
      const matchesStatus = !status || (r.decision_status === status);
      return matchesQ && matchesStatus;
    });

    renderPipeline(filtered);
  }

  const V4_DECISION_BY_QUOTATION = {
    'Open': ['Information Gathering', 'Under Review', 'Pricing in Progress', 'Quotation Submitted'],
    'Approved': ['Won'],
    'Not Approved': ['Lost'],
    'Closed': ['Won', 'Lost'],
    'On Hold': ['On Hold'],
    'Cancelled': ['Lost']
  };

  const V4_MISSING_INFO_DEFAULT = [
    'None - Complete',
    'Scope of Work',
    'BOQ / Item List',
    'Budget / Pricing Details',
    'Drawings & Specifications',
    'Timeline / Schedule',
    'Client Approval',
    'Multiple Items (see Notes)'
  ];

  function onQuotationStatusChange(preferredDecision = null, preferredMissing = null) {
    const qStatus = document.getElementById('quotationStatus').value;
    const decSelect = document.getElementById('decisionStatus');

    const allowedDecisions = V4_DECISION_BY_QUOTATION[qStatus] || [
      'Information Gathering', 'Under Review', 'Pricing in Progress', 'Quotation Submitted', 'Won', 'Lost', 'On Hold'
    ];

    decSelect.innerHTML = allowedDecisions.map(d => `<option value="${d}">${d}</option>`).join('');

    if (preferredDecision && allowedDecisions.includes(preferredDecision)) {
      decSelect.value = preferredDecision;
    } else {
      decSelect.value = allowedDecisions[0];
    }

    updateMissingInfoOptions(preferredMissing);
  }

  function onDecisionStatusChange() {
    const dec = document.getElementById('decisionStatus').value;
    const qSelect = document.getElementById('quotationStatus');

    if (dec === 'Won') {
      qSelect.value = 'Approved';
    } else if (dec === 'Lost') {
      if (!['Not Approved', 'Closed', 'Cancelled'].includes(qSelect.value)) {
        qSelect.value = 'Not Approved';
      }
    } else if (dec === 'On Hold') {
      qSelect.value = 'On Hold';
    } else {
      if (['Approved', 'Not Approved'].includes(qSelect.value)) {
        qSelect.value = 'Open';
      }
    }

    updateMissingInfoOptions();
  }

  function updateMissingInfoOptions(preferredMissing = null) {
    const qStatus = document.getElementById('quotationStatus').value;
    const decStatus = document.getElementById('decisionStatus').value;
    const missingSelect = document.getElementById('missingInfo');

    if (qStatus === 'Approved' || decStatus === 'Won') {
      missingSelect.innerHTML = '<option value="N/A">N/A</option>';
      missingSelect.value = 'N/A';
    } else {
      missingSelect.innerHTML = V4_MISSING_INFO_DEFAULT.map(m => `<option value="${m}">${m}</option>`).join('');
      if (preferredMissing && preferredMissing !== 'N/A' && V4_MISSING_INFO_DEFAULT.includes(preferredMissing)) {
        missingSelect.value = preferredMissing;
      } else {
        missingSelect.value = 'None - Complete';
      }
    }
  }

  function openCreateModal() {
    document.getElementById('opportunityForm').reset();
    document.getElementById('editId').value = '';
    document.getElementById('modalTitle').innerText = 'Log New Inquiry / Bid';
    document.getElementById('dateReceived').value = new Date().toISOString().split('T')[0];
    document.getElementById('quotationStatus').value = 'Open';
    onQuotationStatusChange('Information Gathering', 'None - Complete');
    document.getElementById('opportunityModal').classList.add('active');
  }

  function openEditModal(id) {
    const rec = allRecords.find(r => r.id == id);
    if (!rec) return;

    document.getElementById('editId').value = rec.id;
    document.getElementById('modalTitle').innerText = 'Edit Inquiry / Bid #' + rec.id;
    document.getElementById('projectName').value = rec.project_name || '';
    document.getElementById('client').value = rec.client || '';
    document.getElementById('salesPerson').value = rec.sales_person || '';
    document.getElementById('mainContractor').value = rec.main_contractor || '';
    document.getElementById('consultant').value = rec.consultant || '';
    document.getElementById('scopeOfWork').value = rec.scope_of_work || '';
    document.getElementById('quotationNo').value = rec.quotation_no || '';
    document.getElementById('totalSar').value = rec.total_sar || 0;
    document.getElementById('expectedClosing').value = rec.expected_closing ? rec.expected_closing.substring(0, 7) : '';
    document.getElementById('dateReceived').value = rec.date_received ? rec.date_received.split(' ')[0] : '';
    document.getElementById('submissionDate').value = rec.submission_date ? rec.submission_date.split(' ')[0] : '';
    document.getElementById('quotationValidUntil').value = rec.quotation_valid_until ? rec.quotation_valid_until.split(' ')[0] : '';
    document.getElementById('contactPerson').value = rec.contact_person || '';
    document.getElementById('contactDetails').value = rec.contact_details || '';
    document.getElementById('followUpDate').value = rec.follow_up_date ? rec.follow_up_date.split(' ')[0] : '';
    document.getElementById('nextStep').value = rec.next_step || '';
    document.getElementById('notes').value = rec.notes || '';

    const qStatus = rec.quotation_status || 'Open';
    document.getElementById('quotationStatus').value = qStatus;
    onQuotationStatusChange(rec.decision_status, rec.missing_info);
    document.getElementById('opportunityModal').classList.add('active');
  }

  function closeModal() {
    document.getElementById('opportunityModal').classList.remove('active');
  }

  async function saveOpportunity(e) {
    e.preventDefault();
    const id = document.getElementById('editId').value;
    const isEdit = Boolean(id);

    const payload = {
      project_name: document.getElementById('projectName').value,
      client: document.getElementById('client').value,
      sales_person: document.getElementById('salesPerson').value,
      main_contractor: document.getElementById('mainContractor').value,
      consultant: document.getElementById('consultant').value,
      scope_of_work: document.getElementById('scopeOfWork').value,
      quotation_no: document.getElementById('quotationNo').value,
      quotation_status: document.getElementById('quotationStatus').value,
      total_sar: parseFloat(document.getElementById('totalSar').value) || 0,
      expected_closing: document.getElementById('expectedClosing').value,
      date_received: document.getElementById('dateReceived').value,
      submission_date: document.getElementById('submissionDate').value,
      quotation_valid_until: document.getElementById('quotationValidUntil').value,
      contact_person: document.getElementById('contactPerson').value,
      contact_details: document.getElementById('contactDetails').value,
      decision_status: document.getElementById('decisionStatus').value,
      missing_info: document.getElementById('missingInfo').value,
      follow_up_date: document.getElementById('followUpDate').value,
      next_step: document.getElementById('nextStep').value,
      notes: document.getElementById('notes').value
    };

    if (isEdit) {
      payload.id = id;
    }

    const method = isEdit ? 'PUT' : 'POST';
    const btn = document.getElementById('saveBtn');
    btn.innerText = 'Saving...';
    btn.disabled = true;

    try {
      const res = await fetch('api/v4_pipeline.php', {
        method: method,
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
      });
      const json = await res.json();
      if (json.success) {
        closeModal();
        await loadPipeline();
        await loadDashboardMetrics();
      } else {
        alert('Error: ' + (json.error || json.message));
      }
    } catch (err) {
      alert('Request failed: ' + err.message);
    } finally {
      btn.innerText = 'Save Opportunity';
      btn.disabled = false;
    }
  }

  function exportCsv() {
    if (allRecords.length === 0) return alert('No data to export');
    const headers = ["No.", "Sales Person", "Project Name", "Client", "Main Contractor", "Consultant", "Scope", "Quotation No", "Quotation Status", "Total SAR", "Decision Status", "Expected Closing", "Next Step"];
    const rows = allRecords.map((r, i) => [
      i + 1,
      `"${(r.sales_person||'').replace(/"/g, '""')}"`,
      `"${(r.project_name||'').replace(/"/g, '""')}"`,
      `"${(r.client||'').replace(/"/g, '""')}"`,
      `"${(r.main_contractor||'').replace(/"/g, '""')}"`,
      `"${(r.consultant||'').replace(/"/g, '""')}"`,
      `"${(r.scope_of_work||'').replace(/"/g, '""')}"`,
      `"${(r.quotation_no||'').replace(/"/g, '""')}"`,
      `"${(r.quotation_status||'').replace(/"/g, '""')}"`,
      r.total_sar || 0,
      `"${(r.decision_status||'').replace(/"/g, '""')}"`,
      `"${(r.expected_closing||'').replace(/"/g, '""')}"`,
      `"${(r.next_step||'').replace(/"/g, '""')}"`
    ]);

    const csvContent = "data:text/csv;charset=utf-8," + [headers.join(','), ...rows.map(e => e.join(','))].join('\n');
    const encodedUri = encodeURI(csvContent);
    const link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", `MVS_Sales_Pipeline_${new Date().toISOString().split('T')[0]}.csv`);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
  }

  document.addEventListener('DOMContentLoaded', () => {
    loadPipeline();
    loadDashboardMetrics();
  });
</script>

<?php require_once __DIR__ . '/profile modal.php'; ?>

</body>
</html>

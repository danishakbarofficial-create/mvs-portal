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
      <a href="pm_portal.php" class="nav-item active">
        <svg class="nav-item-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;flex-shrink:0;">
          <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
        </svg>
        <span class="nav-item-text"><?= ($user['role'] === 'pm') ? 'My Projects & Milestones' : 'Projects' ?></span>
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
      <div class="page-header-row">
        <div class="page-header-title-box">
          <h1>Projects</h1>
          <p>Post-award operational delivery tracking, milestone deliverables, invoicing, and cash collections.</p>
        </div>
        <div class="page-header-actions">
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

      <!-- Top Stats Row -->
      <div class="kpi-grid">
        <div class="kpi-card highlight-blue">
          <div class="kpi-label">Awarded PO Value</div>
          <div class="kpi-val" id="myPoVal">0 <span class="curr">SAR</span></div>
          <div class="kpi-sub">Total contracted value of projects</div>
        </div>
        <div class="kpi-card highlight-won">
          <div class="kpi-label">Cash Collected</div>
          <div class="kpi-val" id="myCollectedVal">0 <span class="curr">SAR</span></div>
          <div class="kpi-sub">Payments received into bank</div>
        </div>
        <div class="kpi-card highlight-review">
          <div class="kpi-label">Pending Collection</div>
          <div class="kpi-val" id="myPendingVal">0 <span class="curr">SAR</span></div>
          <div class="kpi-sub">Invoiced but pending client payment</div>
        </div>
        <div class="kpi-card">
          <div class="kpi-label">To Be Invoiced (Backlog)</div>
          <div class="kpi-val" id="myBacklogVal">0 <span class="curr">SAR</span></div>
          <div class="kpi-sub">Remaining unbilled PO milestones</div>
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

  function toggleSidebar() {
    const sb = document.getElementById('appSidebar');
    if (window.innerWidth <= 1024) {
      sb.classList.toggle('mobile-open');
    } else {
      sb.classList.toggle('collapsed');
    }
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

  document.addEventListener('DOMContentLoaded', loadProjects);
</script>
</body>
</html>

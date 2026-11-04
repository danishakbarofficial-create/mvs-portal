// MVS ICT & Telecom - Sales Pipeline & Command Center Client Script

const state = {
  currentMonth: '2026-03',
  currentYear: '2026',
  activeTab: 'dashboard',
  meta: {
    stages: [],
    product_lines: [],
    industries: [],
    lead_sources: [],
    sales_reps: []
  },
  dashboard: null,
  opportunities: [],
  scorecards: null,
  targets: null,
  charts: {
    trend: null,
    product: null
  }
};

// Formatting helpers
function formatSAR(val) {
  if (val === null || val === undefined || isNaN(val)) return 'SAR 0';
  return 'SAR ' + Number(val).toLocaleString('en-US', { maximumFractionDigits: 0 });
}

function formatNumber(val) {
  if (val === null || val === undefined || isNaN(val)) return '0';
  return Number(val).toLocaleString('en-US');
}

function formatPct(val) {
  if (val === null || val === undefined || isNaN(val)) return '0%';
  return Number(val).toFixed(1) + '%';
}

function showToast(message, type = 'info') {
  const container = document.getElementById('toast-container');
  const toast = document.createElement('div');
  toast.className = 'toast';
  toast.innerHTML = `<span>${type === 'success' ? '✓' : 'ℹ'}</span> <span>${message}</span>`;
  container.appendChild(toast);
  setTimeout(() => {
    toast.style.opacity = '0';
    setTimeout(() => toast.remove(), 300);
  }, 3500);
}

// Master metadata loader
async function loadMeta() {
  try {
    const res = await fetch('api/meta.php');
    const data = await res.json();
    if (data.success) {
      state.meta = data;
      if (data.report_month) {
        state.currentMonth = data.report_month;
        const sel = document.getElementById('monthSelector');
        if (sel) sel.value = state.currentMonth;
      }
      populateFormDropdowns();
    }
  } catch (err) {
    console.error('Failed to load metadata:', err);
  }
}

function populateFormDropdowns() {
  // Sales Reps
  const repSelects = document.querySelectorAll('.select-reps');
  repSelects.forEach(sel => {
    const currentVal = sel.value;
    sel.innerHTML = '<option value="">All Sales Reps</option>' + 
      state.meta.sales_reps.map(r => `<option value="${r.name}">${r.name}</option>`).join('');
    if (currentVal) sel.value = currentVal;
  });

  const repFormSelect = document.getElementById('modal_salesperson');
  if (repFormSelect) {
    repFormSelect.innerHTML = state.meta.sales_reps.map(r => `<option value="${r.name}">${r.name} (${r.code})</option>`).join('');
  }

  // Product Lines
  const prodSelects = document.querySelectorAll('.select-products');
  prodSelects.forEach(sel => {
    const currentVal = sel.value;
    sel.innerHTML = '<option value="">All Product Lines</option>' + 
      state.meta.product_lines.map(p => `<option value="${p.name}">${p.name}</option>`).join('');
    if (currentVal) sel.value = currentVal;
  });

  const prodFormSelect = document.getElementById('modal_product_line');
  if (prodFormSelect) {
    prodFormSelect.innerHTML = state.meta.product_lines.map(p => `<option value="${p.name}">${p.name}</option>`).join('');
  }

  // Stages
  const stageSelects = document.querySelectorAll('.select-stages');
  stageSelects.forEach(sel => {
    const currentVal = sel.value;
    sel.innerHTML = '<option value="">All Stages</option>' + 
      state.meta.stages.map(s => `<option value="${s.stage_name}">${s.stage_name} (${(s.default_probability * 100)}%)</option>`).join('');
    if (currentVal) sel.value = currentVal;
  });

  const stageFormSelect = document.getElementById('modal_stage');
  if (stageFormSelect) {
    stageFormSelect.innerHTML = state.meta.stages.map(s => `<option value="${s.stage_name}">${s.stage_name} (${(s.default_probability * 100)}%)</option>`).join('');
  }

  // Industries
  const indSelect = document.getElementById('modal_industry');
  if (indSelect) {
    indSelect.innerHTML = state.meta.industries.map(i => `<option value="${i.name}">${i.name}</option>`).join('');
  }

  // Sources
  const srcSelect = document.getElementById('modal_lead_source');
  if (srcSelect) {
    srcSelect.innerHTML = state.meta.lead_sources.map(s => `<option value="${s.name}">${s.name}</option>`).join('');
  }
}

// 1. Dashboard Tab
async function loadDashboard() {
  try {
    const res = await fetch(`api/dashboard.php?month=${state.currentMonth}`);
    const data = await res.json();
    if (!data.success) throw new Error(data.error);

    state.dashboard = data;
    renderDashboardUI(data);
  } catch (err) {
    console.error('Error loading dashboard:', err);
    showToast('Failed to load dashboard data', 'error');
  }
}

function renderDashboardUI(data) {
  const k = data.kpis;

  // Header and pill
  const labelEl = document.getElementById('dashboardReportLabel');
  if (labelEl) labelEl.textContent = data.report_month_label;

  // KPI 1: Visits
  document.getElementById('kpi_mtd_visits').textContent = `${formatNumber(k.mtd_visits)} / ${formatNumber(k.team_visit_target)}`;
  document.getElementById('kpi_visit_attainment').textContent = formatPct(k.visit_attainment_pct);
  const vBar = document.getElementById('bar_visit_attainment');
  if (vBar) vBar.style.width = Math.min(100, k.visit_attainment_pct) + '%';

  // KPI 2: Revenue Attainment
  document.getElementById('kpi_closed_won').textContent = formatSAR(k.mtd_closed_won);
  document.getElementById('kpi_revenue_target').textContent = 'Target: ' + formatSAR(k.team_revenue_target);
  document.getElementById('kpi_rev_attainment').textContent = formatPct(k.revenue_attainment_pct);
  const rBar = document.getElementById('bar_rev_attainment');
  if (rBar) rBar.style.width = Math.min(100, k.revenue_attainment_pct) + '%';

  // KPI 3: Open Pipeline
  document.getElementById('kpi_open_pipeline').textContent = formatSAR(k.open_pipeline);
  document.getElementById('kpi_weighted_forecast').textContent = 'Weighted: ' + formatSAR(k.total_open_weighted);

  // KPI 4: Best Estimate & Coverage
  document.getElementById('kpi_best_estimate').textContent = formatSAR(k.best_estimate_revenue);
  document.getElementById('kpi_forecast_coverage').textContent = (k.forecast_coverage || 0).toFixed(2) + 'x';
  document.getElementById('kpi_coverage_target').textContent = `Target: ${(k.forecast_coverage_target || 1.1).toFixed(1)}x`;

  // KPI 5: Target Gap
  document.getElementById('kpi_target_gap').textContent = formatSAR(k.target_gap);
  document.getElementById('kpi_win_rate').textContent = formatPct(k.win_rate_pct);

  // KPI 6: YTD Attainment
  document.getElementById('kpi_ytd_won').textContent = formatSAR(k.ytd_closed_won);
  document.getElementById('kpi_ytd_target').textContent = 'YTD Target: ' + formatSAR(k.ytd_revenue_target);
  document.getElementById('kpi_ytd_rev_attainment').textContent = formatPct(k.ytd_rev_attainment_pct);

  // Render Product Breakdown List
  const prodContainer = document.getElementById('dashboard_product_breakdown');
  if (prodContainer) {
    prodContainer.innerHTML = data.product_breakdown.map(p => `
      <div style="margin-bottom: 0.85rem;">
        <div style="display: flex; justify-content: space-between; font-size: 0.8rem; font-weight: 700; margin-bottom: 0.25rem;">
          <span>${p.product_line} <span style="font-weight: 500; color: var(--corp-muted); font-size: 0.725rem;">(${p.active_deals_count} active)</span></span>
          <span>${formatSAR(p.open_pipeline)}</span>
        </div>
        <div class="progress-track">
          <div class="progress-bar bg-gold" style="width: ${Math.min(100, (p.open_pipeline / (k.open_pipeline || 1)) * 100)}%;"></div>
        </div>
      </div>
    `).join('');
  }

  // Render Stage Funnel Cards
  const stageContainer = document.getElementById('dashboard_stage_breakdown');
  if (stageContainer) {
    stageContainer.innerHTML = data.stage_breakdown.map(s => `
      <div style="display: flex; align-items: center; justify-content: space-between; padding: 0.55rem 0.75rem; background: var(--corp-bg); border-radius: var(--radius-md); margin-bottom: 0.5rem; border-left: 4px solid ${s.color_badge};">
        <div>
          <div style="font-size: 0.8rem; font-weight: 700; color: var(--corp-navy);">${s.stage_name}</div>
          <div style="font-size: 0.7rem; color: var(--corp-muted);">${s.deal_count} deals &bull; Prob: ${s.default_probability * 100}%</div>
        </div>
        <div style="text-align: right;">
          <div style="font-size: 0.825rem; font-weight: 800; color: var(--corp-navy);">${formatSAR(s.total_value)}</div>
          <div style="font-size: 0.7rem; color: var(--mvs-gold-dark); font-weight: 600;">Wtd: ${formatSAR(s.total_weighted)}</div>
        </div>
      </div>
    `).join('');
  }

  // Render Chart: Monthly Trend
  renderTrendChart(data.trend);
}

function renderTrendChart(trendData) {
  const ctx = document.getElementById('monthlyTrendChart');
  if (!ctx) return;

  const labels = trendData.map(t => t.month_label);
  const targets = trendData.map(t => t.revenue_target);
  const actuals = trendData.map(t => t.actual_won);

  if (state.charts.trend) {
    state.charts.trend.destroy();
  }

  if (window.Chart) {
    state.charts.trend = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: labels,
        datasets: [
          {
            label: 'Actual Won Revenue (SAR)',
            data: actuals,
            backgroundColor: '#10B981',
            borderRadius: 4,
            barPercentage: 0.6
          },
          {
            label: 'Revenue Quota Target (SAR)',
            data: targets,
            backgroundColor: '#CBD5E1',
            borderRadius: 4,
            barPercentage: 0.6
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'top',
            labels: { font: { family: 'Plus Jakarta Sans', weight: '600' } }
          },
          tooltip: {
            callbacks: {
              label: (ctx) => `${ctx.dataset.label}: SAR ${Number(ctx.raw).toLocaleString()}`
            }
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              callback: (val) => 'SAR ' + (val / 1000000).toFixed(1) + 'M',
              font: { family: 'Plus Jakarta Sans' }
            },
            grid: { color: '#F1F5F9' }
          },
          x: {
            grid: { display: false },
            ticks: { font: { family: 'Plus Jakarta Sans' } }
          }
        }
      }
    });
  }
}

// 2. Visual Pipeline (Kanban) Tab
async function loadPipeline() {
  try {
    const res = await fetch(`api/opportunities.php`);
    const data = await res.json();
    if (!data.success) throw new Error(data.error);

    state.opportunities = data.data;
    renderKanbanUI(data.data);
  } catch (err) {
    console.error('Error loading pipeline:', err);
    showToast('Failed to load opportunities for pipeline', 'error');
  }
}

function renderKanbanUI(deals) {
  const board = document.getElementById('kanbanBoard');
  if (!board) return;

  const stages = state.meta.stages;

  board.innerHTML = stages.map(stage => {
    const stageDeals = deals.filter(d => d.stage === stage.stage_name);
    const totalVal = stageDeals.reduce((sum, d) => sum + Number(d.opportunity_value || 0), 0);
    const weightedVal = stageDeals.reduce((sum, d) => sum + Number(d.weighted_forecast || 0), 0);

    return `
      <div class="kanban-col" data-stage="${stage.stage_name}" ondragover="handleDragOver(event)" ondrop="handleDrop(event, '${stage.stage_name}')">
        <div class="kanban-col-header" style="border-top: 3px solid ${stage.color_badge};">
          <div class="col-title-line">
            <span class="col-name">${stage.stage_name}</span>
            <span class="col-badge" style="background: ${stage.color_badge};">${stageDeals.length}</span>
          </div>
          <div class="col-sub">
            <span>Prob: ${(stage.default_probability * 100)}%</span>
            <span style="font-weight: 700; color: var(--corp-navy);">${formatSAR(totalVal)}</span>
          </div>
        </div>
        <div class="kanban-cards-container">
          ${stageDeals.map(d => `
            <div class="deal-card" draggable="true" ondragstart="handleDragStart(event, ${d.id})" onclick="openEditModal(${d.id})">
              <div class="deal-company">${d.company}</div>
              <div class="deal-client">👤 ${d.client_name || d.contact_person || 'Client'}</div>
              <div class="deal-tags">
                <span class="tag-pill" style="background:#FAF4E5; color:var(--mvs-gold-dark); font-weight:700;">${d.product_line}</span>
                <span class="tag-pill">${d.salesperson}</span>
              </div>
              <div class="deal-metrics">
                <span class="deal-val">${formatSAR(d.opportunity_value)}</span>
                <span class="deal-weighted">Wtd: ${formatSAR(d.weighted_forecast)}</span>
              </div>
              <div class="deal-footer">
                <span>🗓️ ${d.close_date || d.log_date}</span>
                <span style="font-weight:700; color:var(--corp-slate);">${Math.round(d.forecast_probability * 100)}%</span>
              </div>
            </div>
          `).join('')}
          ${stageDeals.length === 0 ? `<div style="text-align: center; color: var(--corp-muted); font-size: 0.75rem; padding: 2rem 0.5rem; font-style: italic;">No opportunities in this stage</div>` : ''}
        </div>
      </div>
    `;
  }).join('');
}

// Drag & Drop Handlers for Kanban
let draggedDealId = null;

function handleDragStart(e, id) {
  draggedDealId = id;
  e.dataTransfer.setData('text/plain', id);
}

function handleDragOver(e) {
  e.preventDefault();
  e.dataTransfer.dropEffect = 'move';
}

async function handleDrop(e, newStage) {
  e.preventDefault();
  if (!draggedDealId) return;

  const id = draggedDealId;
  draggedDealId = null;

  try {
    const res = await fetch(`api/opportunities.php?id=${id}`, {
      method: 'PATCH',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ id, stage: newStage })
    });
    const result = await res.json();
    if (result.success) {
      showToast(`Deal moved to ${newStage}! Probability updated.`, 'success');
      loadPipeline();
      loadDashboard();
      if (state.activeTab === 'scorecards') loadScorecards();
    } else {
      showToast(result.error || 'Failed to update stage', 'error');
    }
  } catch (err) {
    showToast('Network error while moving deal', 'error');
  }
}

// 3. Daily Sales Log Tab
async function loadLogs() {
  try {
    const rep = document.getElementById('log_filter_rep')?.value || '';
    const stage = document.getElementById('log_filter_stage')?.value || '';
    const prod = document.getElementById('log_filter_product')?.value || '';
    const search = document.getElementById('log_search')?.value || '';

    let url = `api/opportunities.php?1=1`;
    if (rep) url += `&salesperson=${encodeURIComponent(rep)}`;
    if (stage) url += `&stage=${encodeURIComponent(stage)}`;
    if (prod) url += `&product_line=${encodeURIComponent(prod)}`;
    if (search) url += `&search=${encodeURIComponent(search)}`;

    const res = await fetch(url);
    const data = await res.json();
    if (!data.success) throw new Error(data.error);

    renderLogTable(data.data);
  } catch (err) {
    console.error('Error loading log table:', err);
    showToast('Failed to load daily logs', 'error');
  }
}

function renderLogTable(rows) {
  const tbody = document.getElementById('logTableBody');
  const countBadge = document.getElementById('logCountBadge');
  if (countBadge) countBadge.textContent = `${rows.length} Records`;
  if (!tbody) return;

  if (rows.length === 0) {
    tbody.innerHTML = `<tr><td colspan="10" style="text-align: center; padding: 3rem; color: var(--corp-muted);">No activity logs found matching the filter criteria.</td></tr>`;
    return;
  }

  tbody.innerHTML = rows.map(r => {
    const stageBadgeClass = r.stage === 'Closed Won' ? 'badge-won' : (r.stage === 'Closed Lost' ? 'badge-lost' : (r.stage === 'Negotiation' ? 'badge-nego' : (r.stage === 'Proposal Sent' ? 'badge-prop' : 'badge-lead')));

    return `
      <tr>
        <td style="font-family: monospace; font-size: 0.75rem; font-weight: 700; color: var(--mvs-gold-dark);">${r.opportunity_id || '-'}</td>
        <td style="white-space: nowrap;">${r.log_date}</td>
        <td style="font-weight: 700; color: var(--corp-navy);">${r.company}</td>
        <td>${r.client_name || r.contact_person || '-'}</td>
        <td><span class="tag-pill" style="background:#FAF4E5; color:var(--mvs-gold-dark); font-weight:700;">${r.product_line}</span></td>
        <td><span class="tag-pill">${r.salesperson}</span></td>
        <td style="font-weight: 800; color: var(--corp-navy); text-align: right;">${formatSAR(r.opportunity_value)}</td>
        <td><span class="badge ${stageBadgeClass}">${r.stage}</span></td>
        <td style="text-align: right; font-weight: 700; color: var(--mvs-gold-dark);">${formatSAR(r.weighted_forecast)}</td>
        <td style="white-space: nowrap; text-align: center;">
          <button class="btn btn-secondary btn-sm" onclick="openEditModal(${r.id})" title="Edit / View Details">✏️</button>
          <button class="btn btn-secondary btn-sm" onclick="confirmDelete(${r.id}, '${r.company}')" title="Delete" style="color: var(--danger);">🗑️</button>
        </td>
      </tr>
    `;
  }).join('');
}

// 4. Salesperson Scorecards Tab
async function loadScorecards() {
  try {
    const res = await fetch(`api/kpis.php?month=${state.currentMonth}`);
    const data = await res.json();
    if (!data.success) throw new Error(data.error);

    state.scorecards = data;
    renderScorecardsUI(data);
  } catch (err) {
    console.error('Error loading scorecards:', err);
    showToast('Failed to load salesperson scorecards', 'error');
  }
}

function renderScorecardsUI(data) {
  const container = document.getElementById('scorecardsContainer');
  const totalsEl = document.getElementById('teamTotalsSummary');
  if (!container) return;

  const totals = data.team_totals;
  if (totalsEl) {
    totalsEl.innerHTML = `
      <div style="display: flex; flex-wrap: wrap; gap: 2rem; background: var(--white); padding: 1.25rem 1.5rem; border-radius: var(--radius-lg); border: 1px solid var(--corp-border); margin-bottom: 1.5rem; box-shadow: var(--shadow-sm);">
        <div><div style="font-size:0.7rem; color:var(--corp-muted); text-transform:uppercase; font-weight:700;">Team Visits</div><div style="font-size:1.3rem; font-weight:800;">${totals.total_visits} / ${totals.total_visit_target} (${totals.avg_visit_attainment_pct}%)</div></div>
        <div><div style="font-size:0.7rem; color:var(--corp-muted); text-transform:uppercase; font-weight:700;">Closed Won</div><div style="font-size:1.3rem; font-weight:800; color:var(--success);">${formatSAR(totals.total_closed_won)}</div></div>
        <div><div style="font-size:0.7rem; color:var(--corp-muted); text-transform:uppercase; font-weight:700;">Revenue Target</div><div style="font-size:1.3rem; font-weight:800;">${formatSAR(totals.total_revenue_target)}</div></div>
        <div><div style="font-size:0.7rem; color:var(--corp-muted); text-transform:uppercase; font-weight:700;">Team Attainment</div><div style="font-size:1.3rem; font-weight:800; color:var(--mvs-gold-dark);">${totals.avg_revenue_attainment_pct}%</div></div>
        <div><div style="font-size:0.7rem; color:var(--corp-muted); text-transform:uppercase; font-weight:700;">Active Open Pipeline</div><div style="font-size:1.3rem; font-weight:800;">${formatSAR(totals.total_open_pipeline)}</div></div>
      </div>
    `;
  }

  container.innerHTML = data.scorecards.map((s, idx) => `
    <div class="card" style="box-shadow: var(--shadow-md);">
      <div class="card-header" style="background: ${idx === 0 ? '#FAF4E5' : 'var(--white)'};">
        <div>
          <div style="display:flex; align-items:center; gap:0.5rem;">
            <span class="card-title" style="font-size:1.1rem;">${s.name}</span>
            <span class="tag-pill" style="font-weight:700;">${s.code}</span>
            ${idx === 0 ? '<span class="badge badge-won">🥇 Top Performer</span>' : ''}
          </div>
          <div style="font-size:0.75rem; color:var(--corp-muted);">${s.email}</div>
        </div>
        <div style="text-align: right;">
          <div style="font-size:0.75rem; color:var(--corp-muted); text-transform:uppercase; font-weight:700;">Rev Attainment</div>
          <div style="font-size:1.4rem; font-weight:800; color:${s.revenue_attainment_pct >= 90 ? 'var(--success)' : 'var(--corp-navy)'};">${s.revenue_attainment_pct}%</div>
        </div>
      </div>
      <div class="card-body">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.25rem;">
          <div style="background: var(--corp-bg); padding: 0.85rem; border-radius: var(--radius-md);">
            <div style="font-size: 0.7rem; color: var(--corp-muted); font-weight: 700; text-transform: uppercase;">Visits MTD</div>
            <div style="font-size: 1.15rem; font-weight: 800;">${s.mtd_visits} / ${s.visit_target} <span style="font-size:0.75rem; color:var(--corp-muted);">(${s.visit_attainment_pct}%)</span></div>
            <div class="progress-track"><div class="progress-bar bg-blue" style="width: ${Math.min(100, s.visit_attainment_pct)}%;"></div></div>
          </div>
          <div style="background: var(--corp-bg); padding: 0.85rem; border-radius: var(--radius-md);">
            <div style="font-size: 0.7rem; color: var(--corp-muted); font-weight: 700; text-transform: uppercase;">Closed Won</div>
            <div style="font-size: 1.15rem; font-weight: 800; color: var(--success);">${formatSAR(s.mtd_closed_won)}</div>
            <div style="font-size: 0.7rem; color: var(--corp-muted);">Target: ${formatSAR(s.revenue_target)}</div>
          </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.75rem; border-top: 1px solid var(--corp-border); padding-top: 1rem; text-align: center;">
          <div>
            <div style="font-size: 0.675rem; color: var(--corp-muted); text-transform: uppercase; font-weight: 700;">Open Pipeline</div>
            <div style="font-size: 0.95rem; font-weight: 800;">${formatSAR(s.open_pipeline)}</div>
            <div style="font-size: 0.675rem; color: var(--corp-muted);">${s.open_deals_count} active deals</div>
          </div>
          <div>
            <div style="font-size: 0.675rem; color: var(--corp-muted); text-transform: uppercase; font-weight: 700;">Weighted Wtd</div>
            <div style="font-size: 0.95rem; font-weight: 800; color: var(--mvs-gold-dark);">${formatSAR(s.weighted_forecast)}</div>
          </div>
          <div>
            <div style="font-size: 0.675rem; color: var(--corp-muted); text-transform: uppercase; font-weight: 700;">YTD Win Rate</div>
            <div style="font-size: 0.95rem; font-weight: 800; color: var(--info);">${s.ytd_win_rate_pct}%</div>
            <div style="font-size: 0.675rem; color: var(--corp-muted);">${s.ytd_won_deals} deals won</div>
          </div>
        </div>
      </div>
    </div>
  `).join('');
}

// 5. Targets Tab
async function loadTargets() {
  try {
    const res = await fetch(`api/targets.php?year=${state.currentYear}`);
    const data = await res.json();
    if (!data.success) throw new Error(data.error);

    renderTargetsTable(data);
  } catch (err) {
    console.error('Error loading targets:', err);
    showToast('Failed to load targets', 'error');
  }
}

function renderTargetsTable(data) {
  const container = document.getElementById('targetsTableContainer');
  if (!container) return;

  const reps = ['Sales 1', 'Sales 2', 'Sales 3', 'Sales 4', 'Sales 5'];
  const months = ['2026-01', '2026-02', '2026-03', '2026-04', '2026-05', '2026-06', '2026-07', '2026-08', '2026-09', '2026-10', '2026-11', '2026-12'];

  let html = `
    <div class="card" style="margin-bottom: 2rem;">
      <div class="card-header"><span class="card-title">💰 Monthly Revenue Quotas (SAR) - Year 2026</span></div>
      <div class="table-responsive">
        <table class="data-table">
          <thead>
            <tr>
              <th>Sales Rep</th>
              ${months.map(m => `<th>${new Date(m + '-01').toLocaleDateString('en-US', { month: 'short' })}</th>`).join('')}
            </tr>
          </thead>
          <tbody>
            ${reps.map(rep => `
              <tr>
                <td style="font-weight: 700;">${rep}</td>
                ${months.map(m => {
                  const target = data.targets.find(t => t.salesperson === rep && t.target_month === m);
                  const rev = target ? target.revenue_target : 0;
                  return `<td><input type="number" class="form-control" style="width: 110px; font-size: 0.75rem; padding: 0.3rem 0.5rem;" value="${rev}" onchange="saveTargetCell('${rep}', '${m}', this.value, null)"></td>`;
                }).join('')}
              </tr>
            `).join('')}
          </tbody>
        </table>
      </div>
    </div>

    <div class="card">
      <div class="card-header"><span class="card-title">🚗 Monthly Visit Targets (# Visits) - Year 2026</span></div>
      <div class="table-responsive">
        <table class="data-table">
          <thead>
            <tr>
              <th>Sales Rep</th>
              ${months.map(m => `<th>${new Date(m + '-01').toLocaleDateString('en-US', { month: 'short' })}</th>`).join('')}
            </tr>
          </thead>
          <tbody>
            ${reps.map(rep => `
              <tr>
                <td style="font-weight: 700;">${rep}</td>
                ${months.map(m => {
                  const target = data.targets.find(t => t.salesperson === rep && t.target_month === m);
                  const vis = target ? target.visit_target : 0;
                  return `<td><input type="number" class="form-control" style="width: 80px; font-size: 0.75rem; padding: 0.3rem 0.5rem;" value="${vis}" onchange="saveTargetCell('${rep}', '${m}', null, this.value)"></td>`;
                }).join('')}
              </tr>
            `).join('')}
          </tbody>
        </table>
      </div>
    </div>
  `;

  container.innerHTML = html;
}

async function saveTargetCell(rep, month, rev, vis) {
  try {
    const payload = {
      salesperson: rep,
      target_month: month
    };
    if (rev !== null) payload.revenue_target = Number(rev);
    if (vis !== null) payload.visit_target = Number(vis);

    const res = await fetch('api/targets.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload)
    });
    const result = await res.json();
    if (result.success) {
      showToast(`Target updated for ${rep} (${month})`, 'success');
      loadDashboard();
    }
  } catch (err) {
    showToast('Failed to update target', 'error');
  }
}

// Modal Handlers (Add / Edit Opportunity)
function openNewModal() {
  document.getElementById('modal_id').value = '';
  document.getElementById('modalTitle').textContent = 'Log New Client Visit & Sales Opportunity';
  document.getElementById('modal_log_date').value = new Date().toISOString().split('T')[0];
  document.getElementById('modal_company').value = '';
  document.getElementById('modal_client_name').value = '';
  document.getElementById('modal_contact_person').value = '';
  document.getElementById('modal_location').value = 'Riyadh';
  document.getElementById('modal_purpose').value = 'Client Visit & Needs Assessment';
  document.getElementById('modal_value').value = '';
  document.getElementById('modal_override_prob').value = '';
  document.getElementById('modal_close_date').value = '';
  document.getElementById('modal_next_action').value = '';
  document.getElementById('modal_next_visit').value = '';
  document.getElementById('modal_summary').value = '';

  updateModalCalculations();
  document.getElementById('opportunityModal').classList.add('active');
}

async function openEditModal(id) {
  try {
    const res = await fetch(`api/opportunities.php?id=${id}`);
    const data = await res.json();
    if (!data.success) throw new Error(data.error);

    const d = data.data;
    document.getElementById('modal_id').value = d.id;
    document.getElementById('modalTitle').textContent = `Edit Opportunity: ${d.company} (${d.opportunity_id || ''})`;
    document.getElementById('modal_log_date').value = d.log_date || '';
    document.getElementById('modal_salesperson').value = d.salesperson || 'Sales 1';
    document.getElementById('modal_company').value = d.company || '';
    document.getElementById('modal_client_name').value = d.client_name || '';
    document.getElementById('modal_contact_person').value = d.contact_person || '';
    document.getElementById('modal_location').value = d.location || '';
    document.getElementById('modal_purpose').value = d.purpose || '';
    document.getElementById('modal_product_line').value = d.product_line || 'VIAVI';
    document.getElementById('modal_industry').value = d.industry || 'Telecom';
    document.getElementById('modal_lead_source').value = d.lead_source || 'Direct';
    document.getElementById('modal_value').value = d.opportunity_value || 0;
    document.getElementById('modal_stage').value = d.stage || 'Lead';
    document.getElementById('modal_override_prob').value = d.override_probability !== null ? (d.override_probability * 100) : '';
    document.getElementById('modal_close_date').value = d.close_date || '';
    document.getElementById('modal_next_action').value = d.next_action || '';
    document.getElementById('modal_next_visit').value = d.next_visit_date || '';
    document.getElementById('modal_summary').value = d.discussion_summary || '';

    updateModalCalculations();
    document.getElementById('opportunityModal').classList.add('active');
  } catch (err) {
    showToast('Failed to load opportunity details', 'error');
  }
}

function closeModal() {
  document.getElementById('opportunityModal').classList.remove('active');
}

function updateModalCalculations() {
  const stageName = document.getElementById('modal_stage')?.value;
  const stageObj = state.meta.stages.find(s => s.stage_name === stageName);
  const defProb = stageObj ? stageObj.default_probability : 0.10;

  const overrideVal = document.getElementById('modal_override_prob')?.value;
  let finalProb = defProb;
  if (overrideVal !== '' && !isNaN(overrideVal)) {
    finalProb = Number(overrideVal) / 100;
  }

  const val = Number(document.getElementById('modal_value')?.value || 0);
  const weighted = Math.round(val * finalProb);

  const calcEl = document.getElementById('modalCalcDisplay');
  if (calcEl) {
    calcEl.innerHTML = `
      <div><strong>Probability:</strong> ${(finalProb * 100).toFixed(0)}% ${overrideVal ? '<span style="color:var(--mvs-gold-dark); font-size:0.75rem;">(Manual Override)</span>' : ''}</div>
      <div><strong>Weighted Forecast:</strong> <span style="color:var(--mvs-gold-dark); font-weight:800;">SAR ${weighted.toLocaleString()}</span></div>
    `;
  }
}

async function saveOpportunityForm(e) {
  e.preventDefault();
  const id = document.getElementById('modal_id').value;
  const method = id ? 'PUT' : 'POST';

  const overrideProbRaw = document.getElementById('modal_override_prob').value;
  const overrideProb = overrideProbRaw !== '' ? (Number(overrideProbRaw) / 100) : null;

  const payload = {
    id: id || undefined,
    log_date: document.getElementById('modal_log_date').value,
    salesperson: document.getElementById('modal_salesperson').value,
    company: document.getElementById('modal_company').value,
    client_name: document.getElementById('modal_client_name').value,
    contact_person: document.getElementById('modal_contact_person').value,
    location: document.getElementById('modal_location').value,
    purpose: document.getElementById('modal_purpose').value,
    product_line: document.getElementById('modal_product_line').value,
    industry: document.getElementById('modal_industry').value,
    lead_source: document.getElementById('modal_lead_source').value,
    opportunity_value: Number(document.getElementById('modal_value').value || 0),
    stage: document.getElementById('modal_stage').value,
    override_probability: overrideProb,
    close_date: document.getElementById('modal_close_date').value,
    next_action: document.getElementById('modal_next_action').value,
    next_visit_date: document.getElementById('modal_next_visit').value,
    discussion_summary: document.getElementById('modal_summary').value
  };

  try {
    const res = await fetch('api/opportunities.php' + (id ? `?id=${id}` : ''), {
      method: method,
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload)
    });
    const result = await res.json();

    if (result.success) {
      showToast(id ? 'Opportunity updated successfully!' : 'Opportunity logged successfully!', 'success');
      closeModal();
      refreshActiveTab();
      loadDashboard();
    } else {
      showToast(result.error || 'Failed to save opportunity', 'error');
    }
  } catch (err) {
    showToast('Network error while saving', 'error');
  }
}

async function confirmDelete(id, name) {
  if (!confirm(`Are you sure you want to delete the opportunity for "${name}"?`)) return;

  try {
    const res = await fetch(`api/opportunities.php?id=${id}`, { method: 'DELETE' });
    const result = await res.json();
    if (result.success) {
      showToast('Opportunity deleted', 'success');
      refreshActiveTab();
      loadDashboard();
    }
  } catch (err) {
    showToast('Failed to delete opportunity', 'error');
  }
}

function exportCsv() {
  window.location.href = `api/export.php?month=${state.currentMonth}`;
}

// ==================== CUSTOMER DATABASE (CRM) ====================
async function loadCustomers() {
  try {
    const search = document.getElementById('cust_search')?.value || '';
    const ind = document.getElementById('cust_filter_industry')?.value || '';

    let url = `api/customers.php?1=1`;
    if (search) url += `&search=${encodeURIComponent(search)}`;
    if (ind) url += `&industry=${encodeURIComponent(ind)}`;

    const res = await fetch(url);
    const data = await res.json();
    if (!data.success) throw new Error(data.error);

    renderCustomersUI(data.data);
  } catch (err) {
    console.error('Error loading customers:', err);
    showToast('Failed to load customer database', 'error');
  }
}

function renderCustomersUI(customers) {
  const grid = document.getElementById('customersGrid');
  const badge = document.getElementById('custCountBadge');
  if (badge) badge.textContent = `${customers.length} Accounts`;
  if (!grid) return;

  if (customers.length === 0) {
    grid.innerHTML = `<div style="grid-column: 1 / -1; text-align: center; padding: 3rem; color: var(--corp-muted);">No customers found matching the search.</div>`;
    return;
  }

  grid.innerHTML = customers.map(c => `
    <div class="card" style="box-shadow: var(--shadow-sm); display: flex; flex-direction: column; justify-content: space-between;">
      <div class="card-header" style="background: var(--white); align-items: flex-start;">
        <div>
          <div style="font-size: 1rem; font-weight: 800; color: var(--corp-navy); margin-bottom: 0.25rem;">${c.company_name}</div>
          <div style="display: flex; gap: 0.4rem; flex-wrap: wrap;">
            <span class="tag-pill" style="background:#FAF4E5; color:var(--mvs-gold-dark); font-weight:700;">${c.industry || 'Enterprise'}</span>
            <span class="tag-pill">${c.city_region || 'Saudi Arabia'}</span>
          </div>
        </div>
        <span class="badge ${c.account_status.includes('Active') ? 'badge-won' : 'badge-lead'}">${c.account_status}</span>
      </div>
      <div class="card-body" style="padding: 1.1rem; flex: 1;">
        <div style="margin-bottom: 0.85rem; font-size: 0.8rem; color: var(--corp-slate);">
          <div style="font-weight: 700;">👤 ${c.contact_person || 'Contact Person TBD'} ${c.designation ? `<span style="font-weight:400; color:var(--corp-muted);">(${c.designation})</span>` : ''}</div>
          ${c.phone ? `<div style="color:var(--corp-muted); margin-top:0.2rem;">📞 ${c.phone}</div>` : ''}
          ${c.email ? `<div style="color:var(--corp-muted);">✉️ ${c.email}</div>` : ''}
        </div>

        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.5rem; background: var(--corp-bg); padding: 0.65rem; border-radius: var(--radius-md); text-align: center; margin-bottom: 0.85rem;">
          <div>
            <div style="font-size: 0.65rem; color: var(--corp-muted); text-transform: uppercase; font-weight: 700;">Visits/Logs</div>
            <div style="font-size: 0.95rem; font-weight: 800; color: var(--corp-navy);">${c.total_interactions}</div>
          </div>
          <div>
            <div style="font-size: 0.65rem; color: var(--corp-muted); text-transform: uppercase; font-weight: 700;">Won Deals</div>
            <div style="font-size: 0.95rem; font-weight: 800; color: var(--success);">${c.total_won_deals}</div>
          </div>
          <div>
            <div style="font-size: 0.65rem; color: var(--corp-muted); text-transform: uppercase; font-weight: 700;">Projects</div>
            <div style="font-size: 0.95rem; font-weight: 800; color: var(--mvs-gold-dark);">${c.active_projects_count}</div>
          </div>
        </div>

        ${c.notes ? `<div style="font-size: 0.75rem; color: var(--corp-muted); font-style: italic; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">${c.notes}</div>` : ''}
      </div>
      <div style="padding: 0.75rem 1.1rem; border-top: 1px solid var(--corp-border); display: flex; justify-content: space-between; align-items: center; background: #F8FAFC;">
        <button class="btn btn-outline-gold btn-sm" onclick="viewCustomerDetail(${c.id})">🔍 View History</button>
        <div style="display: flex; gap: 0.35rem;">
          <button class="btn btn-secondary btn-sm" onclick="openEditCustomerModal(${c.id})">✏️</button>
          <button class="btn btn-secondary btn-sm" onclick="deleteCustomer(${c.id}, '${c.company_name}')" style="color:var(--danger);">🗑️</button>
        </div>
      </div>
    </div>
  `).join('');
}

function openNewCustomerModal() {
  document.getElementById('cust_modal_id').value = '';
  document.getElementById('custModalTitle').textContent = 'Add New Customer Account';
  document.getElementById('cust_modal_name').value = '';
  document.getElementById('cust_modal_industry').value = 'Telecom';
  document.getElementById('cust_modal_city').value = 'Riyadh';
  document.getElementById('cust_modal_contact').value = '';
  document.getElementById('cust_modal_designation').value = '';
  document.getElementById('cust_modal_phone').value = '';
  document.getElementById('cust_modal_email').value = '';
  document.getElementById('cust_modal_status').value = 'Active Client';
  document.getElementById('cust_modal_notes').value = '';
  document.getElementById('customerModal').classList.add('active');
}

async function openEditCustomerModal(id) {
  try {
    const res = await fetch(`api/customers.php?id=${id}`);
    const data = await res.json();
    if (!data.success) throw new Error(data.error);

    const c = data.data;
    document.getElementById('cust_modal_id').value = c.id;
    document.getElementById('custModalTitle').textContent = `Edit Customer: ${c.company_name}`;
    document.getElementById('cust_modal_name').value = c.company_name || '';
    document.getElementById('cust_modal_industry').value = c.industry || 'Telecom';
    document.getElementById('cust_modal_city').value = c.city_region || '';
    document.getElementById('cust_modal_contact').value = c.contact_person || '';
    document.getElementById('cust_modal_designation').value = c.designation || '';
    document.getElementById('cust_modal_phone').value = c.phone || '';
    document.getElementById('cust_modal_email').value = c.email || '';
    document.getElementById('cust_modal_status').value = c.account_status || 'Active Client';
    document.getElementById('cust_modal_notes').value = c.notes || '';
    document.getElementById('customerModal').classList.add('active');
  } catch (err) {
    showToast('Failed to load customer details', 'error');
  }
}

function closeCustomerModal() {
  document.getElementById('customerModal').classList.remove('active');
}

async function saveCustomerForm(e) {
  e.preventDefault();
  const id = document.getElementById('cust_modal_id').value;
  const method = id ? 'PUT' : 'POST';

  const payload = {
    id: id || undefined,
    company_name: document.getElementById('cust_modal_name').value,
    industry: document.getElementById('cust_modal_industry').value,
    city_region: document.getElementById('cust_modal_city').value,
    contact_person: document.getElementById('cust_modal_contact').value,
    designation: document.getElementById('cust_modal_designation').value,
    phone: document.getElementById('cust_modal_phone').value,
    email: document.getElementById('cust_modal_email').value,
    account_status: document.getElementById('cust_modal_status').value,
    notes: document.getElementById('cust_modal_notes').value
  };

  try {
    const res = await fetch('api/customers.php', {
      method: method,
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload)
    });
    const result = await res.json();
    if (result.success) {
      showToast(id ? 'Customer updated!' : 'Customer added!', 'success');
      closeCustomerModal();
      loadCustomers();
    } else {
      showToast(result.error || 'Failed to save', 'error');
    }
  } catch (err) {
    showToast('Network error while saving customer', 'error');
  }
}

async function deleteCustomer(id, name) {
  if (!confirm(`Are you sure you want to delete customer account for "${name}"?`)) return;
  try {
    const res = await fetch(`api/customers.php?id=${id}`, { method: 'DELETE' });
    const result = await res.json();
    if (result.success) {
      showToast('Customer deleted', 'success');
      loadCustomers();
    }
  } catch (err) {
    showToast('Failed to delete customer', 'error');
  }
}

async function viewCustomerDetail(id) {
  try {
    const res = await fetch(`api/customers.php?id=${id}`);
    const data = await res.json();
    if (!data.success) throw new Error(data.error);

    const c = data.data;
    document.getElementById('custDetailTitle').textContent = c.company_name;
    document.getElementById('custDetailIndustryBadge').textContent = `${c.industry || 'Enterprise'} • ${c.city_region || 'KSA'}`;

    const opps = c.opportunities || [];
    const prjs = c.projects || [];

    const body = document.getElementById('custDetailBody');
    body.innerHTML = `
      <div style="background: var(--corp-bg); padding: 1rem; border-radius: var(--radius-md); margin-bottom: 1.25rem;">
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.75rem; font-size: 0.825rem;">
          <div><strong>Primary Contact:</strong> ${c.contact_person || 'N/A'} ${c.designation ? `(${c.designation})` : ''}</div>
          <div><strong>Status:</strong> <span class="badge badge-won">${c.account_status}</span></div>
          <div><strong>Phone:</strong> ${c.phone || 'N/A'}</div>
          <div><strong>Email:</strong> ${c.email || 'N/A'}</div>
          <div style="grid-column: span 2;"><strong>Notes:</strong> ${c.notes || 'None'}</div>
        </div>
      </div>

      <h4 style="font-size: 0.95rem; font-weight: 800; margin-bottom: 0.75rem; color: var(--corp-navy);">Commercial Opportunities & Visits History (${opps.length})</h4>
      ${opps.length === 0 ? `<p style="font-size:0.8rem; color:var(--corp-muted); margin-bottom: 1rem;">No logged opportunities yet.</p>` : `
        <div class="table-responsive" style="margin-bottom: 1.5rem; max-height: 220px; overflow-y: auto;">
          <table class="data-table">
            <thead><tr><th>Date</th><th>Product</th><th>Stage</th><th>Value (SAR)</th><th>Rep</th></tr></thead>
            <tbody>
              ${opps.map(o => `<tr><td>${o.log_date}</td><td><span class="tag-pill">${o.product_line}</span></td><td><span class="badge ${o.stage==='Closed Won'?'badge-won':(o.stage==='Closed Lost'?'badge-lost':'badge-lead')}">${o.stage}</span></td><td style="font-weight:700;">${formatSAR(o.opportunity_value)}</td><td>${o.salesperson}</td></tr>`).join('')}
            </tbody>
          </table>
        </div>
      `}

      <h4 style="font-size: 0.95rem; font-weight: 800; margin-bottom: 0.75rem; color: var(--corp-navy);">Active Site Projects (${prjs.length})</h4>
      ${prjs.length === 0 ? `<p style="font-size:0.8rem; color:var(--corp-muted);">No ongoing site projects for this account.</p>` : `
        <div class="table-responsive">
          <table class="data-table">
            <thead><tr><th>Project Code</th><th>Project Name</th><th>Location</th><th>Progress %</th><th>Status</th></tr></thead>
            <tbody>
              ${prjs.map(p => `<tr><td style="font-weight:700; font-family:monospace;">${p.project_code}</td><td style="font-weight:600;">${p.project_name}</td><td>${p.site_location}</td><td><strong style="color:var(--success);">${p.progress_pct}%</strong></td><td><span class="badge badge-won">${p.status}</span></td></tr>`).join('')}
            </tbody>
          </table>
        </div>
      `}
    `;

    document.getElementById('customerDetailModal').classList.add('active');
  } catch (err) {
    showToast('Failed to load customer profile', 'error');
  }
}

function closeCustomerDetailModal() {
  document.getElementById('customerDetailModal').classList.remove('active');
}

// ==================== PROJECT PROGRESS & DELIVERY % ====================
async function loadProjects() {
  try {
    const res = await fetch('api/projects.php');
    const data = await res.json();
    if (!data.success) throw new Error(data.error);

    renderProjectsUI(data);
  } catch (err) {
    console.error('Error loading projects:', err);
    showToast('Failed to load project progress', 'error');
  }
}

function renderProjectsUI(data) {
  const container = document.getElementById('projectsContainer');
  const stats = data.stats;

  if (document.getElementById('prj_stat_total')) document.getElementById('prj_stat_total').textContent = stats.total_projects;
  if (document.getElementById('prj_stat_avg')) document.getElementById('prj_stat_avg').textContent = `${stats.avg_progress_pct}%`;
  if (document.getElementById('prj_stat_ontrack')) document.getElementById('prj_stat_ontrack').textContent = `${stats.on_track_count} / ${stats.total_projects}`;

  if (!container) return;

  container.innerHTML = data.data.map(p => {
    const prog = p.progress_pct || 0;
    const progColor = prog >= 75 ? 'var(--success)' : (prog >= 50 ? 'var(--mvs-gold-dark)' : 'var(--warning)');
    const barClass = prog >= 75 ? 'bg-green' : (prog >= 50 ? 'bg-gold' : 'bg-amber');

    return `
      <div class="card" style="box-shadow: var(--shadow-sm); overflow: visible;">
        <div class="card-header" style="background: var(--white); align-items: flex-start; gap: 1rem;">
          <div style="flex: 1;">
            <div style="display: flex; align-items: center; gap: 0.6rem; margin-bottom: 0.35rem;">
              <span style="font-family: monospace; font-size: 0.75rem; font-weight: 800; background: #EEF2FF; color: #4338CA; padding: 0.15rem 0.5rem; border-radius: var(--radius-sm); border: 1px solid #C7D2FE;">${p.project_code}</span>
              <h3 style="font-size: 1.05rem; font-weight: 800; color: var(--corp-navy); margin: 0;">${p.project_name}</h3>
              <span class="badge ${p.status === 'Critical Delay' ? 'badge-lost' : (p.status === 'Handover Stage' ? 'badge-won' : 'badge-nego')}">${p.status}</span>
            </div>
            <div style="display: flex; gap: 1.25rem; font-size: 0.8rem; color: var(--corp-muted); flex-wrap: wrap;">
              <span>🏢 <strong>Client:</strong> ${p.company_name}</span>
              <span>📍 <strong>Site:</strong> ${p.site_location || 'Saudi Arabia'}</span>
              <span>👷 <strong>Project Lead:</strong> <span style="color:var(--corp-navy); font-weight:700;">${p.project_manager}</span></span>
              ${p.target_end_date ? `<span>🗓️ <strong>Target Handover:</strong> ${p.target_end_date}</span>` : ''}
            </div>
          </div>
          <div style="text-align: right; min-width: 130px;">
            <div style="font-size: 0.7rem; color: var(--corp-muted); text-transform: uppercase; font-weight: 700;">Site Delivery %</div>
            <div style="font-size: 1.6rem; font-weight: 800; color: ${progColor}; line-height: 1.1;">${prog}%</div>
            <button class="btn btn-secondary btn-sm" onclick="openEditProjectModal(${p.id})" style="margin-top: 0.35rem;">✏️ Update %</button>
          </div>
        </div>

        <div class="card-body" style="padding: 1.1rem 1.5rem;">
          <!-- Overall Progress Bar -->
          <div style="margin-bottom: 1.25rem;">
            <div style="display: flex; justify-content: space-between; font-size: 0.75rem; font-weight: 700; color: var(--corp-slate); margin-bottom: 0.35rem;">
              <span>Overall Physical Execution Progress</span>
              <span>${prog}% Complete</span>
            </div>
            <div class="progress-track" style="height: 8px;">
              <div class="progress-bar ${barClass}" style="width: ${prog}%;"></div>
            </div>
          </div>

          <!-- Scope Deliverables Note -->
          ${p.scope_description ? `
            <div style="font-size: 0.8rem; color: var(--corp-slate); background: #F8FAFC; border-left: 3px solid var(--mvs-gold); padding: 0.65rem 0.85rem; border-radius: var(--radius-sm); margin-bottom: 1rem;">
              <strong>Deliverable Scope:</strong> ${p.scope_description}
            </div>
          ` : ''}

          <!-- Milestones Track -->
          ${p.milestones && p.milestones.length > 0 ? `
            <div>
              <div style="font-size: 0.725rem; font-weight: 700; color: var(--corp-muted); text-transform: uppercase; margin-bottom: 0.5rem; letter-spacing: 0.04em;">Key Delivery Milestones:</div>
              <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 0.65rem;">
                ${p.milestones.map(m => {
                  const mDone = m.status === 'Completed';
                  const mProg = m.status === 'In Progress';
                  return `
                    <div style="background: ${mDone ? '#ECFDF5' : (mProg ? '#FFFBEB' : '#F1F5F9')}; border: 1px solid ${mDone ? '#A7F3D0' : (mProg ? '#FDE68A' : '#E2E8F0')}; border-radius: var(--radius-md); padding: 0.55rem 0.75rem; font-size: 0.75rem;">
                      <div style="display: flex; justify-content: space-between; align-items: center; font-weight: 700; color: ${mDone ? '#065F46' : (mProg ? '#92400E' : 'var(--corp-slate)')}; margin-bottom: 0.2rem;">
                        <span>${mDone ? '✓ ' : (mProg ? '⏳ ' : '○ ')}${m.title}</span>
                        <span>${m.progress_pct || (mDone ? 100 : 0)}%</span>
                      </div>
                      ${m.target_date ? `<div style="font-size: 0.675rem; color: var(--corp-muted);">Target: ${m.target_date}</div>` : ''}
                    </div>
                  `;
                }).join('')}
              </div>
            </div>
          ` : ''}
        </div>
      </div>
    `;
  }).join('');
}

function openNewProjectModal() {
  document.getElementById('prj_modal_id').value = '';
  document.getElementById('prjModalTitle').textContent = 'Add New Project • Pillar 2 Execution';
  document.getElementById('prj_modal_name').value = '';
  document.getElementById('prj_modal_company').value = '';
  document.getElementById('prj_modal_location').value = 'Riyadh';
  document.getElementById('prj_modal_pm').value = 'Adrian Kim Mallilin';
  document.getElementById('prj_modal_progress').value = '0';
  document.getElementById('prj_modal_status').value = 'In Progress';
  document.getElementById('prj_modal_target_end').value = '';
  document.getElementById('prj_modal_scope').value = '';
  document.getElementById('projectModal').classList.add('active');
}

async function openEditProjectModal(id) {
  try {
    const res = await fetch(`api/projects.php?id=${id}`);
    const data = await res.json();
    if (!data.success) throw new Error(data.error);

    const p = data.data;
    document.getElementById('prj_modal_id').value = p.id;
    document.getElementById('prjModalTitle').textContent = `Update Project Progress: ${p.project_name} (${p.project_code})`;
    document.getElementById('prj_modal_name').value = p.project_name || '';
    document.getElementById('prj_modal_company').value = p.company_name || '';
    document.getElementById('prj_modal_location').value = p.site_location || '';
    document.getElementById('prj_modal_pm').value = p.project_manager || '';
    document.getElementById('prj_modal_progress').value = p.progress_pct || 0;
    document.getElementById('prj_modal_status').value = p.status || 'In Progress';
    document.getElementById('prj_modal_target_end').value = p.target_end_date || '';
    document.getElementById('prj_modal_scope').value = p.scope_description || '';
    document.getElementById('projectModal').classList.add('active');
  } catch (err) {
    showToast('Failed to load project details', 'error');
  }
}

function closeProjectModal() {
  document.getElementById('projectModal').classList.remove('active');
}

async function saveProjectForm(e) {
  e.preventDefault();
  const id = document.getElementById('prj_modal_id').value;
  const method = id ? 'PUT' : 'POST';

  const payload = {
    id: id || undefined,
    project_name: document.getElementById('prj_modal_name').value,
    company_name: document.getElementById('prj_modal_company').value,
    site_location: document.getElementById('prj_modal_location').value,
    project_manager: document.getElementById('prj_modal_pm').value,
    progress_pct: Number(document.getElementById('prj_modal_progress').value || 0),
    status: document.getElementById('prj_modal_status').value,
    target_end_date: document.getElementById('prj_modal_target_end').value,
    scope_description: document.getElementById('prj_modal_scope').value
  };

  try {
    const res = await fetch('api/projects.php', {
      method: method,
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload)
    });
    const result = await res.json();
    if (result.success) {
      showToast(id ? 'Project progress updated!' : 'Project created!', 'success');
      closeProjectModal();
      loadProjects();
    } else {
      showToast(result.error || 'Failed to save project', 'error');
    }
  } catch (err) {
    showToast('Network error while saving project', 'error');
  }
}

function switchTab(tabId) {
  state.activeTab = tabId;
  document.querySelectorAll('.nav-tab-btn').forEach(btn => {
    btn.classList.toggle('active', btn.getAttribute('data-tab') === tabId);
  });
  document.querySelectorAll('.tab-content').forEach(content => {
    content.classList.toggle('active', content.id === `tab_${tabId}`);
  });
  refreshActiveTab();
}

function refreshActiveTab() {
  if (state.activeTab === 'dashboard') loadDashboard();
  else if (state.activeTab === 'pipeline') loadPipeline();
  else if (state.activeTab === 'log') loadLogs();
  else if (state.activeTab === 'scorecards') loadScorecards();
  else if (state.activeTab === 'customers') loadCustomers();
  else if (state.activeTab === 'projects') loadProjects();
  else if (state.activeTab === 'targets') loadTargets();
}

// Initialization on DOM Ready
document.addEventListener('DOMContentLoaded', async () => {
  await loadMeta();
  loadDashboard();

  // Tab navigation listeners
  document.querySelectorAll('.nav-tab-btn').forEach(btn => {
    btn.addEventListener('click', () => switchTab(btn.getAttribute('data-tab')));
  });

  // Month selector listener
  const monthSel = document.getElementById('monthSelector');
  if (monthSel) {
    monthSel.addEventListener('change', (e) => {
      state.currentMonth = e.target.value;
      refreshActiveTab();
      if (state.activeTab !== 'dashboard') loadDashboard();
    });
  }

  // Live calculation listeners in modal
  document.getElementById('modal_value')?.addEventListener('input', updateModalCalculations);
  document.getElementById('modal_stage')?.addEventListener('change', updateModalCalculations);
  document.getElementById('modal_override_prob')?.addEventListener('input', updateModalCalculations);

  // Form submits
  document.getElementById('opportunityForm')?.addEventListener('submit', saveOpportunityForm);
  document.getElementById('customerForm')?.addEventListener('submit', saveCustomerForm);
  document.getElementById('projectForm')?.addEventListener('submit', saveProjectForm);

  // Table search & filter listeners
  document.getElementById('log_search')?.addEventListener('input', debounce(loadLogs, 300));
  document.getElementById('log_filter_rep')?.addEventListener('change', loadLogs);
  document.getElementById('log_filter_stage')?.addEventListener('change', loadLogs);
  document.getElementById('log_filter_product')?.addEventListener('change', loadLogs);

  // Customer search & filter listeners
  document.getElementById('cust_search')?.addEventListener('input', debounce(loadCustomers, 300));
  document.getElementById('cust_filter_industry')?.addEventListener('change', loadCustomers);
});

function debounce(func, wait) {
  let timeout;
  return function executedFunction(...args) {
    const later = () => {
      clearTimeout(timeout);
      func(...args);
    };
    clearTimeout(timeout);
    timeout = setTimeout(later, wait);
  };
}

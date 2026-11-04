<?php
// API: v4 Executive Dashboards (Sales Pipeline Dashboard + Projects Cashflow Dashboard)
// Faithfully matches formulas & tables from 'Project_Tracking_Report Asayil v4.xlsx'
if (!headers_sent()) {
    header('Content-Type: application/json; charset=utf-8');
}
require_once __DIR__ . '/../auth.php';

$user = Auth::requireLogin(['executive', 'pm', 'sales']);
$pdo = Database::getConnection();

// =========================================================================
// 1. SALES DASHBOARD (Scoping: Sales reps see only their own data)
// =========================================================================
$salesMetrics = null;

if ($user['role'] !== 'pm') {
    $salesMetrics = [];
    $isSales = ($user['role'] === 'sales');
    $salesWhere = "";
    $salesParams = [];

    if ($isSales) {
        $salesWhere = " WHERE LOWER(TRIM(sales_person)) = LOWER(TRIM(?))";
        $salesParams = [$user['name']];
    }

    // Total Active Pipeline
    $stmt = $pdo->prepare("SELECT SUM(total_sar) as total_pipeline FROM v4_pipeline" . $salesWhere);
    $stmt->execute($salesParams);
    $totalPipeline = floatval($stmt->fetch()['total_pipeline'] ?? 0);
    $salesMetrics['total_pipeline'] = $totalPipeline;

    // Won Projects Value
    $wonSql = "SELECT SUM(total_sar) as won_value FROM v4_pipeline WHERE (LOWER(decision_status) = 'won' OR LOWER(quotation_status) = 'approved')";
    $wonParams = [];
    if ($isSales) {
        $wonSql .= " AND LOWER(TRIM(sales_person)) = LOWER(TRIM(?))";
        $wonParams[] = $user['name'];
    }
    $stmt = $pdo->prepare($wonSql);
    $stmt->execute($wonParams);
    $wonVal = floatval($stmt->fetch()['won_value'] ?? 0);
    $salesMetrics['won_value'] = $wonVal;

    // Under Pricing / Review Value
    $prSql = "SELECT SUM(total_sar) as pricing_value FROM v4_pipeline WHERE LOWER(decision_status) IN ('pricing in progress', 'under review', 'information gathering')";
    $prParams = [];
    if ($isSales) {
        $prSql .= " AND LOWER(TRIM(sales_person)) = LOWER(TRIM(?))";
        $prParams[] = $user['name'];
    }
    $stmt = $pdo->prepare($prSql);
    $stmt->execute($prParams);
    $salesMetrics['under_pricing_value'] = floatval($stmt->fetch()['pricing_value'] ?? 0);

    // Win Rate (% of Decided Bids) = Won / (Won + Lost)
    $lostSql = "SELECT SUM(total_sar) as lost_value FROM v4_pipeline WHERE LOWER(decision_status) = 'lost'";
    $lostParams = [];
    if ($isSales) {
        $lostSql .= " AND LOWER(TRIM(sales_person)) = LOWER(TRIM(?))";
        $lostParams[] = $user['name'];
    }
    $stmt = $pdo->prepare($lostSql);
    $stmt->execute($lostParams);
    $lostVal = floatval($stmt->fetch()['lost_value'] ?? 0);
    $decidedValue = $wonVal + $lostVal;
    $salesMetrics['win_rate_pct'] = ($decidedValue > 0) ? round(($wonVal / $decidedValue) * 100, 1) : 0;

    $dealsSql = "SELECT COUNT(*) FROM v4_pipeline" . $salesWhere;
    $stmt = $pdo->prepare($dealsSql);
    $stmt->execute($salesParams);
    $salesMetrics['total_deals'] = intval($stmt->fetchColumn());

    // Excel Table 1: Pipeline Bids by Status
    $v4Statuses = [
        'Information Gathering',
        'Under Review',
        'Pricing in Progress',
        'Quotation Submitted',
        'Won',
        'Lost',
        'On Hold'
    ];

    $statusTable = [];
    foreach ($v4Statuses as $st) {
        $stSql = "SELECT COUNT(*) as count, SUM(total_sar) as total_val, MIN(expected_closing) as min_closing, MAX(expected_closing) as max_closing FROM v4_pipeline WHERE ";
        $stParams = [];
        if ($st === 'Information Gathering') {
            $stSql .= "(decision_status = ? OR decision_status IS NULL OR TRIM(decision_status) = '')";
        } else {
            $stSql .= "decision_status = ?";
        }
        $stParams[] = $st;

        if ($isSales) {
            $stSql .= " AND LOWER(TRIM(sales_person)) = LOWER(TRIM(?))";
            $stParams[] = $user['name'];
        }

        $stmt = $pdo->prepare($stSql);
        $stmt->execute($stParams);
        $row = $stmt->fetch();
        $cnt = intval($row['count'] ?? 0);
        $val = floatval($row['total_val'] ?? 0);
        $pct = ($totalPipeline > 0) ? round(($val / $totalPipeline) * 100, 1) : 0;
        
        $minC = $row['min_closing'] ? substr($row['min_closing'], 0, 7) : '';
        $maxC = $row['max_closing'] ? substr($row['max_closing'], 0, 7) : '';
        $closingRange = '-';
        if ($minC && $maxC) {
            $minFormatted = date('M Y', strtotime($minC . '-01'));
            $maxFormatted = date('M Y', strtotime($maxC . '-01'));
            $closingRange = ($minFormatted === $maxFormatted) ? $minFormatted : "$minFormatted - $maxFormatted";
        } elseif ($minC) {
            $closingRange = date('M Y', strtotime($minC . '-01'));
        }

        $statusTable[] = [
            'status' => $st,
            'count' => $cnt,
            'total_val' => $val,
            'pct_of_total' => $pct,
            'expected_closing' => $closingRange
        ];
    }
    $salesMetrics['by_status_table'] = $statusTable;
    $salesMetrics['by_status'] = array_map(function($x) {
        return ['status' => $x['status'], 'count' => $x['count'], 'total_val' => $x['total_val']];
    }, $statusTable);

    // Excel Table 2: Pipeline by Sales Representative
    $repSql = "
        SELECT 
            COALESCE(sales_person, 'Unassigned') as salesperson,
            COUNT(*) as total_inquiries,
            SUM(total_sar) as total_pipeline,
            SUM(CASE WHEN LOWER(decision_status) = 'won' OR LOWER(quotation_status) = 'approved' THEN total_sar ELSE 0 END) as won_val,
            SUM(CASE WHEN LOWER(decision_status) = 'lost' THEN total_sar ELSE 0 END) as lost_val
        FROM v4_pipeline
    ";
    $repParams = [];
    if ($isSales) {
        $repSql .= " WHERE LOWER(TRIM(sales_person)) = LOWER(TRIM(?))";
        $repParams[] = $user['name'];
    }
    $repSql .= " GROUP BY sales_person ORDER BY total_pipeline DESC, total_inquiries DESC";

    $stmt = $pdo->prepare($repSql);
    $stmt->execute($repParams);
    $repRows = $stmt->fetchAll();
    $repTable = [];
    foreach ($repRows as $r) {
        $wonV = floatval($r['won_val']);
        $lostV = floatval($r['lost_val']);
        $dec = $wonV + $lostV;
        $winRate = ($dec > 0) ? round(($wonV / $dec) * 100, 1) : 0;
        $pipe = floatval($r['total_pipeline']);
        $inq = intval($r['total_inquiries']);

        $repTable[] = [
            'salesperson' => $r['salesperson'],
            'inquiries_count' => $inq,
            'active_leads' => $inq,
            'total_pipeline' => $pipe,
            'won_val' => $wonV,
            'win_rate' => $winRate,
            'share_pct' => ($totalPipeline > 0) ? round(($pipe / $totalPipeline) * 100, 1) : 0.0
        ];
    }
    $salesMetrics['by_rep_table'] = $repTable;
    $salesMetrics['by_rep'] = array_map(function($x) {
        return ['salesperson' => $x['salesperson'], 'count' => $x['inquiries_count'], 'total_val' => $x['total_pipeline']];
    }, $repTable);
}


// =========================================================================
// 2. PROJECTS DASHBOARD (Scoping: PMs see only their assigned projects)
// =========================================================================
$projectsMetrics = null;
$projectsList = [];

if ($user['role'] !== 'sales') {
    $isPm = ($user['role'] === 'pm');
    $projectConditions = [];
    $projectParams = [];

    if ($isPm) {
        $projectConditions[] = "(LOWER(TRIM(project_manager)) = LOWER(TRIM(?)) OR project_manager IS NULL OR project_manager = '')";
        $projectParams[] = $user['name'];
    }

    $selectedProject = $_GET['project_name'] ?? null;
    if ($selectedProject && $selectedProject !== 'All Projects' && $selectedProject !== 'All My Projects' && $selectedProject !== 'Company-Wide (All Projects)' && $selectedProject !== 'Company-Wide Projects') {
        $projectConditions[] = "project_name = ?";
        $projectParams[] = $selectedProject;
    }

    $projectFilter = "";
    if (!empty($projectConditions)) {
        $projectFilter = " WHERE " . implode(" AND ", $projectConditions);
    }

    // B5: Awarded PO Value
    $stmt = $pdo->prepare("SELECT SUM(milestone_value) as sum_ms, SUM(DISTINCT total_po_value) as sum_po FROM v4_projects $projectFilter");
    $stmt->execute($projectParams);
    $b5Row = $stmt->fetch();
    $totalPoValue = floatval($b5Row['sum_ms'] ?? 0);
    if ($totalPoValue == 0) {
        $totalPoValue = floatval($b5Row['sum_po'] ?? 0);
    }

    // D5: Invoiced Amount & H5: Cash Collected
    $stmt = $pdo->prepare("
        SELECT
            SUM(invoice_amount) as total_invoiced,
            SUM(collected_amount) as total_collected
        FROM v4_projects $projectFilter
    ");
    $stmt->execute($projectParams);
    $projAgg = $stmt->fetch();

    $totalInvoiced = floatval($projAgg['total_invoiced'] ?? 0);
    $totalCollected = floatval($projAgg['total_collected'] ?? 0);

    // F5: Backlog (To Be Invoiced) = MAX(0, Total PO - Invoiced)
    $toBeInvoiced = max(0.0, $totalPoValue - $totalInvoiced);

    // J5: Pending Collection = MAX(0, Invoiced - Collected)
    $pendingCollection = max(0.0, $totalInvoiced - $totalCollected);

    $projectsMetrics = [
        'selected_project' => ($selectedProject && $selectedProject !== 'All Projects' && $selectedProject !== 'All My Projects' && $selectedProject !== 'Company-Wide (All Projects)') ? $selectedProject : ($isPm ? 'All My Projects' : 'All Projects'),
        'total_po_value' => $totalPoValue,
        'total_invoiced' => $totalInvoiced,
        'to_be_invoiced_backlog' => $toBeInvoiced,
        'total_cash_collected' => $totalCollected,
        'pending_collection' => $pendingCollection,
        'collection_pct' => ($totalInvoiced > 0) ? round(($totalCollected / $totalInvoiced) * 100, 1) : 0,
        'invoiced_pct' => ($totalPoValue > 0) ? round(($totalInvoiced / $totalPoValue) * 100, 1) : 0
    ];

    // Excel Table 1: Projects by PO Status
    $poStatuses = ['In Progress', 'On Hold', 'Completed', 'Cancelled'];
    $poStatusTable = [];
    $totalMsCount = 0;
    $totalMsVal = 0;

    foreach ($poStatuses as $pst) {
        $pstConditions = ["po_status = ?"];
        $pstParams = [$pst];
        if ($isPm) {
            $pstConditions[] = "(LOWER(TRIM(project_manager)) = LOWER(TRIM(?)) OR project_manager IS NULL OR project_manager = '')";
            $pstParams[] = $user['name'];
        }
        if ($selectedProject && $selectedProject !== 'All Projects' && $selectedProject !== 'All My Projects' && $selectedProject !== 'Company-Wide (All Projects)' && $selectedProject !== 'Company-Wide Projects') {
            $pstConditions[] = "project_name = ?";
            $pstParams[] = $selectedProject;
        }
        $sql = "SELECT COUNT(*) as count, SUM(milestone_value) as val FROM v4_projects WHERE " . implode(" AND ", $pstConditions);
        $stmt = $pdo->prepare($sql);
        $stmt->execute($pstParams);
        $r = $stmt->fetch();
        $cnt = intval($r['count'] ?? 0);
        $val = floatval($r['val'] ?? 0);
        $totalMsCount += $cnt;
        $totalMsVal += $val;

        $poStatusTable[] = [
            'po_status' => $pst,
            'milestone_count' => $cnt,
            'value' => $val
        ];
    }
    // Add % of total
    foreach ($poStatusTable as &$pstRow) {
        $pstRow['pct_of_total'] = ($totalMsVal > 0) ? round(($pstRow['value'] / $totalMsVal) * 100, 1) : 0;
    }
    unset($pstRow);
    $projectsMetrics['po_status_table'] = $poStatusTable;

    // Excel Table 2: Milestone Invoicing by Status
    $invStatuses = ['Not Invoiced', 'Partially Invoiced', 'Fully Invoiced', 'Overdue'];
    $invStatusTable = [];
    $totalInvMsCount = 0;
    $totalInvMsVal = 0;

    foreach ($invStatuses as $ist) {
        $istConditions = ["invoice_status = ?"];
        $istParams = [$ist];
        if ($isPm) {
            $istConditions[] = "(LOWER(TRIM(project_manager)) = LOWER(TRIM(?)) OR project_manager IS NULL OR project_manager = '')";
            $istParams[] = $user['name'];
        }
        if ($selectedProject && $selectedProject !== 'All Projects' && $selectedProject !== 'All My Projects' && $selectedProject !== 'Company-Wide (All Projects)' && $selectedProject !== 'Company-Wide Projects') {
            $istConditions[] = "project_name = ?";
            $istParams[] = $selectedProject;
        }
        $sql = "SELECT COUNT(*) as count, SUM(milestone_value) as val FROM v4_projects WHERE " . implode(" AND ", $istConditions);
        $stmt = $pdo->prepare($sql);
        $stmt->execute($istParams);
        $r = $stmt->fetch();
        $cnt = intval($r['count'] ?? 0);
        $val = floatval($r['val'] ?? 0);
        $totalInvMsCount += $cnt;
        $totalInvMsVal += $val;

        $invStatusTable[] = [
            'invoice_status' => $ist,
            'count' => $cnt,
            'value' => $val
        ];
    }
    foreach ($invStatusTable as &$istRow) {
        $istRow['pct_of_total'] = ($totalInvMsVal > 0) ? round(($istRow['value'] / $totalInvMsVal) * 100, 1) : 0;
    }
    unset($istRow);
    $projectsMetrics['invoice_status_table'] = $invStatusTable;

    // Excel Table 3: Contract Billing & Cash Flow Lifecycle (Exact v4 Rows B29:E34)
    $lifecycleTable = [
        [
            'stage' => 'Collected Cash (Paid by Clients)',
            'amount' => $totalCollected,
            'pct_of_contract' => ($totalPoValue > 0) ? round(($totalCollected / $totalPoValue) * 100, 1) : 0,
            'color' => '#10B981'
        ],
        [
            'stage' => 'Pending Collection (Billed, Due)',
            'amount' => $pendingCollection,
            'pct_of_contract' => ($totalPoValue > 0) ? round(($pendingCollection / $totalPoValue) * 100, 1) : 0,
            'color' => '#F59E0B'
        ],
        [
            'stage' => 'To Be Invoiced / Backlog',
            'amount' => $toBeInvoiced,
            'pct_of_contract' => ($totalPoValue > 0) ? round(($toBeInvoiced / $totalPoValue) * 100, 1) : 0,
            'color' => '#2878D0'
        ],
        [
            'stage' => 'Total Invoiced Amount (Billed)',
            'amount' => $totalInvoiced,
            'pct_of_contract' => ($totalPoValue > 0) ? round(($totalInvoiced / $totalPoValue) * 100, 1) : 0,
            'color' => '#64748B'
        ],
        [
            'stage' => 'Total Awarded PO Value',
            'amount' => $totalPoValue,
            'pct_of_contract' => 100.0,
            'color' => '#172B4D'
        ]
    ];
    $projectsMetrics['lifecycle_table'] = $lifecycleTable;

    // List of available projects for selector
    if ($isPm) {
        $stmtPl = $pdo->prepare("SELECT DISTINCT project_name FROM v4_projects WHERE (LOWER(TRIM(project_manager)) = LOWER(TRIM(?)) OR project_manager IS NULL OR project_manager = '') AND project_name IS NOT NULL AND project_name != '' ORDER BY project_name ASC");
        $stmtPl->execute([$user['name']]);
        $projectsList = $stmtPl->fetchAll(PDO::FETCH_COLUMN);
        array_unshift($projectsList, 'All My Projects');
    } else {
        $projectsList = $pdo->query("SELECT DISTINCT project_name FROM v4_projects WHERE project_name IS NOT NULL AND project_name != '' ORDER BY project_name ASC")->fetchAll(PDO::FETCH_COLUMN);
        array_unshift($projectsList, 'All Projects');
    }

    // Excel Table 4: Project Manager Delivery & Collection Performance
    $pmSql = "
        SELECT
            COALESCE(project_manager, 'Unassigned') as pm,
            COUNT(DISTINCT project_name) as projects_count,
            COUNT(*) as milestone_count,
            SUM(milestone_value) as total_milestone_val,
            SUM(invoice_amount) as invoiced,
            SUM(collected_amount) as collected,
            SUM(pending_collection) as pending
        FROM v4_projects
    ";
    $pmParams = [];
    if ($isPm) {
        $pmSql .= " WHERE (LOWER(TRIM(project_manager)) = LOWER(TRIM(?)) OR project_manager IS NULL OR project_manager = '')";
        $pmParams = [$user['name']];
    }
    $pmSql .= " GROUP BY project_manager ORDER BY total_milestone_val DESC";
    $stmt = $pdo->prepare($pmSql);
    $stmt->execute($pmParams);
    $pmRows = $stmt->fetchAll();
    $pmTable = [];
    foreach ($pmRows as $r) {
        $inv = floatval($r['invoiced']);
        $col = floatval($r['collected']);
        $eff = ($inv > 0) ? round(($col / $inv) * 100, 1) : 0;

        $pmTable[] = [
            'pm' => $r['pm'],
            'projects_count' => intval($r['projects_count']),
            'milestones_count' => intval($r['milestone_count']),
            'total_value' => floatval($r['total_milestone_val']),
            'invoiced' => $inv,
            'collected' => $col,
            'pending' => floatval($r['pending']),
            'collection_efficiency' => $eff
        ];
    }
    $projectsMetrics['pm_table'] = $pmTable;

    // =========================================================================
    // 3. EXCEL PROJECTS DASHBOARD 4 CHARTS (Exact to v4 Excel Charts)
    // =========================================================================
    // Chart 1: Contract PO Revenue Realization (Donut: Billed Scope % vs Unbilled PO Balance %)
    $billedPct = ($totalPoValue > 0) ? round(($totalInvoiced / $totalPoValue) * 100, 1) : 0;
    $unbilledPct = ($totalPoValue > 0) ? round(($toBeInvoiced / $totalPoValue) * 100, 1) : 0;
    $projectsMetrics['chart_revenue_realization'] = [
        'labels' => ["Billed Scope ($billedPct%)", "Unbilled PO Balance ($unbilledPct%)"],
        'values' => [$totalInvoiced, $toBeInvoiced],
        'colors' => ['#0284C7', '#334155'],
        'percentages' => [$billedPct, $unbilledPct]
    ];

    // Chart 2: Milestone Deliveries by PO Status (Column Chart)
    $projectsMetrics['chart_po_status'] = [
        'labels' => array_column($poStatusTable, 'po_status'),
        'counts' => array_column($poStatusTable, 'milestone_count'),
        'color' => '#10B981'
    ];

    // Chart 3: Invoice Cash Flow & Collections (SAR) (Grouped Column by Milestone)
    $msSql = "SELECT milestone_no, milestone_desc, invoice_amount, collected_amount, pending_collection, invoice_date, expected_closing 
              FROM v4_projects $projectFilter ORDER BY id ASC";
    $stmtMs = $pdo->prepare($msSql);
    $stmtMs->execute($projectParams);
    $msRows = $stmtMs->fetchAll(PDO::FETCH_ASSOC);

    $msLabels = [];
    $msInvoiced = [];
    $msReceived = [];
    $msPending = [];

    foreach ($msRows as $r) {
        if (empty($r['milestone_no']) && empty($r['milestone_desc'])) continue;
        $dateStr = '';
        if (!empty($r['invoice_date'])) {
            $dateStr = date('M Y', strtotime($r['invoice_date']));
        } elseif (!empty($r['expected_closing'])) {
            $dateStr = date('M Y', strtotime($r['expected_closing']));
        }
        $msName = !empty($r['milestone_no']) ? $r['milestone_no'] : substr($r['milestone_desc'], 0, 15);
        $lbl = $msName . ($dateStr ? " ($dateStr)" : "");
        $msLabels[] = $lbl;
        $msInvoiced[] = floatval($r['invoice_amount']);
        $msReceived[] = floatval($r['collected_amount']);
        $msPending[] = floatval($r['pending_collection']);
    }

    $projectsMetrics['chart_milestone_cashflow'] = [
        'labels' => $msLabels,
        'invoiced' => $msInvoiced,
        'received' => $msReceived,
        'pending' => $msPending
    ];

    // Chart 4: Contract Realization & Cash Flow (SAR) (Bar Chart)
    $projectsMetrics['chart_contract_realization'] = [
        'labels' => ['Collected Cash (Paid by Clients)', 'Pending Collection (Billed, Due)', 'To Be Invoiced / Backlog'],
        'values' => [$totalCollected, $pendingCollection, $toBeInvoiced],
        'color' => '#C53030'
    ];
}

echo json_encode([
    'success' => true,
    'user' => [
        'name' => $user['name'],
        'role' => $user['role']
    ],
    'sales_dashboard' => $salesMetrics,
    'projects_dashboard' => $projectsMetrics,
    'projects_list' => $projectsList
]);

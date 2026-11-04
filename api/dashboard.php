<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

require_once __DIR__ . '/../db.php';

try {
    $pdo = Database::getConnection();

    // Get selected month from query, e.g. '2026-03'
    $month = $_GET['month'] ?? null;
    if (!$month) {
        $st = $pdo->query("SELECT value FROM system_settings WHERE key = 'report_month'");
        $month = $st->fetchColumn() ?: '2026-03';
    }

    $year = substr($month, 0, 4);
    $monthStart = $month . '-01';
    $nextMonthTime = strtotime($monthStart . ' +1 month');
    $nextMonth = date('Y-m', $nextMonthTime);
    $yearStart = $year . '-01-01';

    // 1. MTD Actual Visits (Count of logs in visit_month)
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM sales_opportunities WHERE visit_month = ?");
    $stmt->execute([$month]);
    $mtdVisits = (int)$stmt->fetchColumn();

    // 2. MTD Visit Target (Team Total from targets table)
    $stmt = $pdo->prepare("SELECT SUM(visit_target) FROM sales_targets WHERE target_month = ?");
    $stmt->execute([$month]);
    $teamVisitTarget = (int)($stmt->fetchColumn() ?: 0);
    $visitAttainment = $teamVisitTarget > 0 ? round(($mtdVisits / $teamVisitTarget) * 100, 1) : 0;

    // 3. MTD Closed Won Revenue (close_month = selected month AND stage = 'Closed Won')
    $stmt = $pdo->prepare("SELECT COALESCE(SUM(opportunity_value), 0) FROM sales_opportunities WHERE stage = 'Closed Won' AND close_month = ?");
    $stmt->execute([$month]);
    $mtdClosedWon = (float)$stmt->fetchColumn();

    // 4. MTD Revenue Target (Team Total from targets table)
    $stmt = $pdo->prepare("SELECT SUM(revenue_target) FROM sales_targets WHERE target_month = ?");
    $stmt->execute([$month]);
    $teamRevenueTarget = (float)($stmt->fetchColumn() ?: 0);
    $revenueAttainment = $teamRevenueTarget > 0 ? round(($mtdClosedWon / $teamRevenueTarget) * 100, 1) : 0;

    // 5. Open Pipeline (SAR) (All active deals NOT Closed Won & NOT Closed Lost)
    $stmt = $pdo->query("SELECT COALESCE(SUM(opportunity_value), 0) FROM sales_opportunities WHERE stage NOT IN ('Closed Won', 'Closed Lost')");
    $openPipeline = (float)$stmt->fetchColumn();

    // 6. Weighted Forecast (SAR) for open pipeline closing in report month (or total active open weighted)
    // Excel calculates Weighted Forecast for current report month expected closes:
    $stmt = $pdo->prepare("SELECT COALESCE(SUM(weighted_forecast), 0) FROM sales_opportunities WHERE stage NOT IN ('Closed Won', 'Closed Lost') AND close_month = ?");
    $stmt->execute([$month]);
    $mtdWeightedForecast = (float)$stmt->fetchColumn();

    // Total Open Weighted Forecast overall:
    $stmt = $pdo->query("SELECT COALESCE(SUM(weighted_forecast), 0) FROM sales_opportunities WHERE stage NOT IN ('Closed Won', 'Closed Lost')");
    $totalOpenWeightedForecast = (float)$stmt->fetchColumn();

    // 7. Best Estimate Revenue (SAR) = Closed Won + Weighted Forecast
    $bestEstimateRevenue = $mtdClosedWon + $mtdWeightedForecast;

    // 8. Forecast Coverage = Best Estimate Revenue / Revenue Target
    $forecastCoverage = $teamRevenueTarget > 0 ? round($bestEstimateRevenue / $teamRevenueTarget, 2) : 0;

    // Target Coverage Ratio against 1.1x target
    $stmt = $pdo->prepare("SELECT forecast_coverage_target, win_rate_target, avg_deal_size_target FROM quality_targets WHERE target_month = ?");
    $stmt->execute([$month]);
    $qualityTargets = $stmt->fetch() ?: [
        'forecast_coverage_target' => 1.1,
        'win_rate_target' => 0.25,
        'avg_deal_size_target' => 400000
    ];

    // 9. Target Gap = Revenue Target - Best Estimate Revenue
    $targetGap = max(0, $teamRevenueTarget - $bestEstimateRevenue);

    // 10. Win Rate (YTD) = Won / (Won + Lost)
    $stmt = $pdo->prepare("
        SELECT 
            SUM(CASE WHEN stage = 'Closed Won' THEN 1 ELSE 0 END) as won_count,
            SUM(CASE WHEN stage = 'Closed Lost' THEN 1 ELSE 0 END) as lost_count
        FROM sales_opportunities 
        WHERE stage IN ('Closed Won', 'Closed Lost') AND close_date >= ? AND close_date < ?
    ");
    $stmt->execute([$yearStart, $nextMonth . '-01']);
    $counts = $stmt->fetch();
    $wonCount = (int)($counts['won_count'] ?? 0);
    $lostCount = (int)($counts['lost_count'] ?? 0);
    $totalDecided = $wonCount + $lostCount;
    $winRate = $totalDecided > 0 ? round(($wonCount / $totalDecided) * 100, 1) : 0;

    // 11. Average Deal Size (YTD Won Deals)
    $stmt = $pdo->prepare("SELECT AVG(opportunity_value) FROM sales_opportunities WHERE stage = 'Closed Won' AND close_date >= ? AND close_date < ?");
    $stmt->execute([$yearStart, $nextMonth . '-01']);
    $avgDealSize = (float)($stmt->fetchColumn() ?: 0);

    // 12. YTD Numbers
    $stmt = $pdo->prepare("SELECT COALESCE(SUM(opportunity_value), 0) FROM sales_opportunities WHERE stage = 'Closed Won' AND close_date >= ? AND close_date < ?");
    $stmt->execute([$yearStart, $nextMonth . '-01']);
    $ytdClosedWon = (float)$stmt->fetchColumn();

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM sales_opportunities WHERE log_date >= ? AND log_date < ?");
    $stmt->execute([$yearStart, $nextMonth . '-01']);
    $ytdVisits = (int)$stmt->fetchColumn();

    $stmt = $pdo->prepare("SELECT SUM(revenue_target), SUM(visit_target) FROM sales_targets WHERE target_month >= ? AND target_month <= ?");
    $stmt->execute([$year . '-01', $month]);
    $ytdTargets = $stmt->fetch();
    $ytdRevenueTarget = (float)($ytdTargets['SUM(revenue_target)'] ?? 0);
    $ytdVisitTarget = (int)($ytdTargets['SUM(visit_target)'] ?? 0);
    $ytdRevAttainment = $ytdRevenueTarget > 0 ? round(($ytdClosedWon / $ytdRevenueTarget) * 100, 1) : 0;
    $ytdVisitAttainment = $ytdVisitTarget > 0 ? round(($ytdVisits / $ytdVisitTarget) * 100, 1) : 0;

    // 13. Breakdown by Product Line
    $stmt = $pdo->query("
        SELECT 
            p.name as product_line,
            COALESCE(SUM(CASE WHEN o.stage NOT IN ('Closed Won', 'Closed Lost') THEN o.opportunity_value ELSE 0 END), 0) as open_pipeline,
            COALESCE(SUM(CASE WHEN o.stage NOT IN ('Closed Won', 'Closed Lost') THEN o.weighted_forecast ELSE 0 END), 0) as weighted_pipeline,
            COALESCE(SUM(CASE WHEN o.stage = 'Closed Won' THEN o.opportunity_value ELSE 0 END), 0) as won_revenue,
            COUNT(CASE WHEN o.stage NOT IN ('Closed Won', 'Closed Lost') THEN 1 END) as active_deals_count
        FROM master_product_lines p
        LEFT JOIN sales_opportunities o ON p.name = o.product_line
        GROUP BY p.name
        ORDER BY open_pipeline DESC, won_revenue DESC
    ");
    $productBreakdown = $stmt->fetchAll();

    // 14. Breakdown by Pipeline Stage
    $stmt = $pdo->query("
        SELECT 
            s.stage_name,
            s.default_probability,
            s.color_badge,
            s.display_order,
            COUNT(o.id) as deal_count,
            COALESCE(SUM(o.opportunity_value), 0) as total_value,
            COALESCE(SUM(o.weighted_forecast), 0) as total_weighted
        FROM master_stages s
        LEFT JOIN sales_opportunities o ON s.stage_name = o.stage
        GROUP BY s.id, s.stage_name, s.default_probability, s.color_badge, s.display_order
        ORDER BY s.display_order ASC
    ");
    $stageBreakdown = $stmt->fetchAll();

    // 15. 12-Month Performance Trend for 2026
    $trend = [];
    $allMonthsStmt = $pdo->query("SELECT DISTINCT target_month FROM sales_targets WHERE target_month LIKE '$year-%' ORDER BY target_month ASC");
    $monthsList = $allMonthsStmt->fetchAll(PDO::FETCH_COLUMN);

    foreach ($monthsList as $m) {
        $stRev = $pdo->prepare("SELECT SUM(revenue_target), SUM(visit_target) FROM sales_targets WHERE target_month = ?");
        $stRev->execute([$m]);
        $mTargets = $stRev->fetch();

        $stWon = $pdo->prepare("SELECT COALESCE(SUM(opportunity_value), 0), COUNT(*) FROM sales_opportunities WHERE stage = 'Closed Won' AND close_month = ?");
        $stWon->execute([$m]);
        $wonData = $stWon->fetch();

        $stVis = $pdo->prepare("SELECT COUNT(*) FROM sales_opportunities WHERE visit_month = ?");
        $stVis->execute([$m]);
        $actVisits = (int)$stVis->fetchColumn();

        $trend[] = [
            'month' => $m,
            'month_label' => date('M Y', strtotime($m . '-01')),
            'revenue_target' => (float)($mTargets['SUM(revenue_target)'] ?? 0),
            'actual_won' => (float)$wonData['COALESCE(SUM(opportunity_value), 0)'],
            'visit_target' => (int)($mTargets['SUM(visit_target)'] ?? 0),
            'actual_visits' => $actVisits
        ];
    }

    echo json_encode([
        'success' => true,
        'report_month' => $month,
        'report_month_label' => date('F Y', strtotime($monthStart)),
        'year' => $year,
        'kpis' => [
            'mtd_visits' => $mtdVisits,
            'team_visit_target' => $teamVisitTarget,
            'visit_attainment_pct' => $visitAttainment,

            'mtd_closed_won' => $mtdClosedWon,
            'team_revenue_target' => $teamRevenueTarget,
            'revenue_attainment_pct' => $revenueAttainment,

            'open_pipeline' => $openPipeline,
            'mtd_weighted_forecast' => $mtdWeightedForecast,
            'total_open_weighted' => $totalOpenWeightedForecast,
            'best_estimate_revenue' => $bestEstimateRevenue,
            'forecast_coverage' => $forecastCoverage,
            'forecast_coverage_target' => (float)$qualityTargets['forecast_coverage_target'],
            'target_gap' => $targetGap,

            'win_rate_pct' => $winRate,
            'win_rate_target' => (float)$qualityTargets['win_rate_target'] * 100,

            'avg_deal_size' => round($avgDealSize, 0),
            'avg_deal_size_target' => (float)$qualityTargets['avg_deal_size_target'],

            'ytd_closed_won' => $ytdClosedWon,
            'ytd_revenue_target' => $ytdRevenueTarget,
            'ytd_rev_attainment_pct' => $ytdRevAttainment,
            'ytd_visits' => $ytdVisits,
            'ytd_visit_target' => $ytdVisitTarget,
            'ytd_visit_attainment_pct' => $ytdVisitAttainment
        ],
        'product_breakdown' => $productBreakdown,
        'stage_breakdown' => $stageBreakdown,
        'trend' => $trend
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

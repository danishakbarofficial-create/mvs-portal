<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/../db.php';

try {
    $pdo = Database::getConnection();

    $month = $_GET['month'] ?? null;
    if (!$month) {
        $st = $pdo->query("SELECT value FROM system_settings WHERE key = 'report_month'");
        $month = $st->fetchColumn() ?: '2026-03';
    }

    $year = substr($month, 0, 4);
    $yearStart = $year . '-01-01';
    $nextMonthTime = strtotime($month . '-01 +1 month');
    $nextMonthStart = date('Y-m-01', $nextMonthTime);

    $repsStmt = $pdo->query("SELECT name, code, email FROM sales_reps WHERE status = 'active' ORDER BY id ASC");
    $reps = $repsStmt->fetchAll();

    $scorecards = [];
    $totalActualVisits = 0;
    $totalVisitTargets = 0;
    $totalPipelineCreated = 0;
    $totalClosedWon = 0;
    $totalRevTargets = 0;
    $totalOpenPipeline = 0;
    $totalWeightedForecast = 0;

    foreach ($reps as $rep) {
        $name = $rep['name'];

        // 1. MTD Visits
        $st = $pdo->prepare("SELECT COUNT(*) FROM sales_opportunities WHERE salesperson = ? AND visit_month = ?");
        $st->execute([$name, $month]);
        $visits = (int)$st->fetchColumn();

        // 2. Visit Target & Revenue Target
        $st = $pdo->prepare("SELECT visit_target, revenue_target FROM sales_targets WHERE salesperson = ? AND target_month = ?");
        $st->execute([$name, $month]);
        $targets = $st->fetch() ?: ['visit_target' => 0, 'revenue_target' => 0];
        $visitTarget = (int)($targets['visit_target'] ?? 0);
        $revTarget = (float)($targets['revenue_target'] ?? 0);

        $visitAttainment = $visitTarget > 0 ? round(($visits / $visitTarget) * 100, 1) : 0;

        // 3. MTD Pipeline Created
        $st = $pdo->prepare("SELECT COALESCE(SUM(opportunity_value), 0) FROM sales_opportunities WHERE salesperson = ? AND visit_month = ?");
        $st->execute([$name, $month]);
        $pipelineCreated = (float)$st->fetchColumn();

        // 4. MTD Closed Won Revenue
        $st = $pdo->prepare("SELECT COALESCE(SUM(opportunity_value), 0) FROM sales_opportunities WHERE salesperson = ? AND stage = 'Closed Won' AND close_month = ?");
        $st->execute([$name, $month]);
        $closedWon = (float)$st->fetchColumn();

        $revAttainment = $revTarget > 0 ? round(($closedWon / $revTarget) * 100, 1) : 0;

        // 5. Open Pipeline & Weighted Forecast
        $st = $pdo->prepare("
            SELECT 
                COALESCE(SUM(opportunity_value), 0) as open_pipeline,
                COALESCE(SUM(weighted_forecast), 0) as weighted_forecast,
                COUNT(*) as open_count
            FROM sales_opportunities 
            WHERE salesperson = ? AND stage NOT IN ('Closed Won', 'Closed Lost')
        ");
        $st->execute([$name]);
        $openData = $st->fetch();
        $openPipeline = (float)$openData['open_pipeline'];
        $weightedForecast = (float)$openData['weighted_forecast'];
        $openDealsCount = (int)$openData['open_count'];

        // 6. YTD Win Rate & Avg Deal Size
        $st = $pdo->prepare("
            SELECT 
                SUM(CASE WHEN stage = 'Closed Won' THEN 1 ELSE 0 END) as won_count,
                SUM(CASE WHEN stage = 'Closed Lost' THEN 1 ELSE 0 END) as lost_count,
                SUM(CASE WHEN stage = 'Closed Won' THEN opportunity_value ELSE 0 END) as won_val
            FROM sales_opportunities 
            WHERE salesperson = ? AND stage IN ('Closed Won', 'Closed Lost') AND close_date >= ? AND close_date < ?
        ");
        $st->execute([$name, $yearStart, $nextMonthStart]);
        $ytdData = $st->fetch();
        $wonCount = (int)($ytdData['won_count'] ?? 0);
        $lostCount = (int)($ytdData['lost_count'] ?? 0);
        $wonVal = (float)($ytdData['won_val'] ?? 0);
        $totalDecided = $wonCount + $lostCount;
        $winRate = $totalDecided > 0 ? round(($wonCount / $totalDecided) * 100, 1) : 0;
        $avgDealSize = $wonCount > 0 ? round($wonVal / $wonCount, 0) : 0;

        // Rollups
        $totalActualVisits += $visits;
        $totalVisitTargets += $visitTarget;
        $totalPipelineCreated += $pipelineCreated;
        $totalClosedWon += $closedWon;
        $totalRevTargets += $revTarget;
        $totalOpenPipeline += $openPipeline;
        $totalWeightedForecast += $weightedForecast;

        $scorecards[] = [
            'name' => $name,
            'code' => $rep['code'],
            'email' => $rep['email'],
            'mtd_visits' => $visits,
            'visit_target' => $visitTarget,
            'visit_attainment_pct' => $visitAttainment,
            'mtd_pipeline_created' => $pipelineCreated,
            'mtd_closed_won' => $closedWon,
            'revenue_target' => $revTarget,
            'revenue_attainment_pct' => $revAttainment,
            'open_pipeline' => $openPipeline,
            'open_deals_count' => $openDealsCount,
            'weighted_forecast' => $weightedForecast,
            'ytd_win_rate_pct' => $winRate,
            'ytd_avg_deal_size' => $avgDealSize,
            'ytd_won_deals' => $wonCount
        ];
    }

    // Sort by revenue attainment or closed won descending
    usort($scorecards, fn($a, $b) => $b['revenue_attainment_pct'] <=> $a['revenue_attainment_pct']);

    echo json_encode([
        'success' => true,
        'report_month' => $month,
        'team_totals' => [
            'total_visits' => $totalActualVisits,
            'total_visit_target' => $totalVisitTargets,
            'avg_visit_attainment_pct' => $totalVisitTargets > 0 ? round(($totalActualVisits / $totalVisitTargets) * 100, 1) : 0,
            'total_pipeline_created' => $totalPipelineCreated,
            'total_closed_won' => $totalClosedWon,
            'total_revenue_target' => $totalRevTargets,
            'avg_revenue_attainment_pct' => $totalRevTargets > 0 ? round(($totalClosedWon / $totalRevTargets) * 100, 1) : 0,
            'total_open_pipeline' => $totalOpenPipeline,
            'total_weighted_forecast' => $totalWeightedForecast
        ],
        'scorecards' => $scorecards
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

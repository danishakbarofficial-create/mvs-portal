<?php
// MVS ICT & Telecom - Database Connection & Initialization
// Supports SQLite out-of-the-box with optional MySQL configuration

class Database {
    private static ?PDO $instance = null;
    private static string $dbFile = __DIR__ . '/data/mvs_sales.sqlite';

    public static function getConnection(): PDO {
        if (self::$instance === null) {
            $dataDir = __DIR__ . '/data';
            if (!is_dir($dataDir)) {
                mkdir($dataDir, 0777, true);
            }

            $isNew = !file_exists(self::$dbFile);
            self::$instance = new PDO('sqlite:' . self::$dbFile, null, null, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_TIMEOUT => 5
            ]);

            self::$instance->exec('PRAGMA foreign_keys = ON;');
            self::$instance->exec('PRAGMA journal_mode = WAL;');

            if ($isNew || filesize(self::$dbFile) === 0) {
                self::initSchema();
                self::seedInitialData();
            }
        }
        return self::$instance;
    }

    private static function initSchema(): void {
        $pdo = self::$instance;

        // Sales Reps
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS sales_reps (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL UNIQUE,
                code TEXT,
                email TEXT,
                phone TEXT,
                status TEXT DEFAULT 'active',
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            );
        ");

        // Master Lists / Settings
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS master_stages (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                stage_name TEXT NOT NULL UNIQUE,
                default_probability REAL NOT NULL,
                display_order INTEGER DEFAULT 0,
                color_badge TEXT DEFAULT '#64748B'
            );
        ");

        $pdo->exec("
            CREATE TABLE IF NOT EXISTS master_product_lines (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL UNIQUE,
                description TEXT
            );
        ");

        $pdo->exec("
            CREATE TABLE IF NOT EXISTS master_industries (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL UNIQUE
            );
        ");

        $pdo->exec("
            CREATE TABLE IF NOT EXISTS master_lead_sources (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL UNIQUE
            );
        ");

        // Targets Table (by rep, month e.g. '2026-03')
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS sales_targets (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                salesperson TEXT NOT NULL,
                target_month TEXT NOT NULL, -- YYYY-MM
                revenue_target REAL DEFAULT 0,
                visit_target INTEGER DEFAULT 0,
                UNIQUE(salesperson, target_month)
            );
        ");

        // Quality Targets by Month
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS quality_targets (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                target_month TEXT NOT NULL UNIQUE,
                win_rate_target REAL DEFAULT 0.25,
                avg_deal_size_target REAL DEFAULT 400000,
                forecast_coverage_target REAL DEFAULT 1.1
            );
        ");

        // Daily Sales Log / Opportunities
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS sales_opportunities (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                opportunity_id TEXT UNIQUE,
                log_date DATE NOT NULL,
                salesperson TEXT NOT NULL,
                client_name TEXT NOT NULL,
                company TEXT NOT NULL,
                location TEXT,
                contact_person TEXT,
                purpose TEXT,
                product_line TEXT NOT NULL,
                industry TEXT,
                lead_source TEXT,
                discussion_summary TEXT,
                opportunity_value REAL DEFAULT 0,
                stage TEXT NOT NULL,
                default_probability REAL DEFAULT 0,
                override_probability REAL,
                forecast_probability REAL DEFAULT 0,
                weighted_forecast REAL DEFAULT 0,
                close_date DATE,
                next_action TEXT,
                next_visit_date DATE,
                visit_month TEXT, -- YYYY-MM
                close_month TEXT, -- YYYY-MM
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
            );
        ");

        // System Settings
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS system_settings (
                key TEXT PRIMARY KEY,
                value TEXT
            );
        ");

        // Customer Database (Client CRM)
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS customers (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                company_name TEXT NOT NULL UNIQUE,
                industry TEXT,
                city_region TEXT,
                contact_person TEXT,
                designation TEXT,
                phone TEXT,
                email TEXT,
                address TEXT,
                account_status TEXT DEFAULT 'Active',
                notes TEXT,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            );
        ");

        // Projects & Delivery Progress (Pillar 2 - No Cashflow)
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS projects (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                project_code TEXT UNIQUE,
                project_name TEXT NOT NULL,
                company_name TEXT NOT NULL,
                site_location TEXT,
                project_manager TEXT,
                progress_pct REAL DEFAULT 0,
                status TEXT DEFAULT 'In Progress', -- 'On Track', 'In Progress', 'Critical Delay', 'Handover Stage', 'Completed'
                start_date DATE,
                target_end_date DATE,
                scope_description TEXT,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
            );
        ");

        // Project Milestones
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS project_milestones (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                project_id INTEGER NOT NULL,
                title TEXT NOT NULL,
                target_date DATE,
                status TEXT DEFAULT 'Pending',
                progress_pct REAL DEFAULT 0,
                FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE
            );
        ");

        // Users & Roles for v4 Portal
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                username TEXT UNIQUE NOT NULL,
                name TEXT NOT NULL,
                email TEXT,
                password_hash TEXT NOT NULL,
                role TEXT NOT NULL CHECK(role IN ('sales', 'pm', 'executive', 'admin')),
                status TEXT DEFAULT 'active',
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            );
        ");

        // v4 Pipeline Table (Exact 22 columns from v4 Excel)
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS v4_pipeline (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                row_no INTEGER,
                sales_person TEXT,
                project_name TEXT,
                client TEXT,
                main_contractor TEXT,
                consultant TEXT,
                scope_of_work TEXT,
                date_received TEXT,
                submission_date TEXT,
                quotation_no TEXT,
                quotation_status TEXT,
                total_sar REAL DEFAULT 0,
                expected_closing TEXT,
                quotation_valid_until TEXT,
                contact_person TEXT,
                contact_details TEXT,
                decision_status TEXT,
                missing_info TEXT,
                next_step TEXT,
                follow_up_date TEXT,
                notes TEXT,
                additional_notes TEXT,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
            );
        ");

        // v4 Projects Table (Exact 26 columns from v4 Excel)
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS v4_projects (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                row_no INTEGER,
                project_manager TEXT,
                project_name TEXT,
                customer TEXT,
                po_status TEXT,
                po_no TEXT,
                po_date TEXT,
                total_po_value REAL DEFAULT 0,
                expected_closing TEXT,
                months_to_close INTEGER,
                milestone_no TEXT,
                milestone_desc TEXT,
                milestone_value REAL DEFAULT 0,
                milestone_status TEXT,
                technical_report TEXT,
                invoice_status TEXT,
                invoice_no TEXT,
                invoice_date TEXT,
                invoice_amount REAL DEFAULT 0,
                collection_date TEXT,
                collected_amount REAL DEFAULT 0,
                collection_status TEXT,
                pending_collection REAL DEFAULT 0,
                to_be_invoiced REAL DEFAULT 0,
                notes TEXT,
                additional_notes TEXT,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
            );
        ");

        // v4 Validation Lists
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS v4_lists (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                category TEXT NOT NULL,
                item_value TEXT NOT NULL,
                display_order INTEGER DEFAULT 0
            );
        ");
    }

    private static function seedInitialData(): void {
        $pdo = self::$instance;

        // Stages with Mounir's exact probabilities & colors
        $stages = [
            ['Lead', 0.10, 1, '#94A3B8'],
            ['Contacted', 0.20, 2, '#60A5FA'],
            ['Meeting Scheduled', 0.30, 3, '#818CF8'],
            ['Qualified', 0.50, 4, '#F59E0B'],
            ['Proposal Sent', 0.65, 5, '#EC4899'],
            ['Negotiation', 0.80, 6, '#F97316'],
            ['Closed Won', 1.00, 7, '#10B981'],
            ['Closed Lost', 0.00, 8, '#EF4444']
        ];
        $st = $pdo->prepare("INSERT OR IGNORE INTO master_stages (stage_name, default_probability, display_order, color_badge) VALUES (?, ?, ?, ?)");
        foreach ($stages as $s) {
            $st->execute($s);
        }

        // Product Lines from Excel
        $productLines = ['VIAVI', 'Jetting', 'Cabinets', 'Low Current', 'Tools', 'Passive', 'Services', 'Other'];
        $st = $pdo->prepare("INSERT OR IGNORE INTO master_product_lines (name) VALUES (?)");
        foreach ($productLines as $p) {
            $st->execute([$p]);
        }

        // Industries
        $industries = ['Telecom', 'Contractor', 'Government', 'Private', 'Utilities', 'Industrial'];
        $st = $pdo->prepare("INSERT OR IGNORE INTO master_industries (name) VALUES (?)");
        foreach ($industries as $i) {
            $st->execute([$i]);
        }

        // Lead Sources
        $sources = ['Cold Call', 'Referral', 'Existing Account', 'Tender', 'Partner', 'Inbound', 'Event'];
        $st = $pdo->prepare("INSERT OR IGNORE INTO master_lead_sources (name) VALUES (?)");
        foreach ($sources as $s) {
            $st->execute([$s]);
        }

        // Sales Reps
        $reps = [
            ['Sales 1', 'REP-01', 'sales1@mvsksa.com', '+966 50 111 2231'],
            ['Sales 2', 'REP-02', 'sales2@mvsksa.com', '+966 50 222 3342'],
            ['Sales 3', 'REP-03', 'sales3@mvsksa.com', '+966 50 333 4453'],
            ['Sales 4', 'REP-04', 'sales4@mvsksa.com', '+966 50 444 5564'],
            ['Sales 5', 'REP-05', 'sales5@mvsksa.com', '+966 50 555 6675']
        ];
        $st = $pdo->prepare("INSERT OR IGNORE INTO sales_reps (name, code, email, phone) VALUES (?, ?, ?, ?)");
        foreach ($reps as $r) {
            $st->execute($r);
        }

        // Seed 2026 Targets exactly from Mounir's Excel file
        $revTargets = [
            'Sales 1' => [500000, 525000, 550000, 575000, 600000, 625000, 650000, 675000, 700000, 725000, 750000, 775000],
            'Sales 2' => [580000, 605000, 630000, 655000, 680000, 705000, 730000, 755000, 780000, 805000, 830000, 855000],
            'Sales 3' => [660000, 685000, 710000, 735000, 760000, 785000, 810000, 835000, 860000, 885000, 910000, 935000],
            'Sales 4' => [740000, 765000, 790000, 815000, 840000, 865000, 890000, 915000, 940000, 965000, 990000, 1015000],
            'Sales 5' => [820000, 845000, 870000, 895000, 920000, 945000, 970000, 995000, 1020000, 1045000, 1070000, 1095000]
        ];

        $visTargets = [
            'Sales 1' => [18, 19, 20, 18, 19, 20, 18, 19, 20, 18, 19, 20],
            'Sales 2' => [20, 21, 22, 20, 21, 22, 20, 21, 22, 20, 21, 22],
            'Sales 3' => [22, 23, 24, 22, 23, 24, 22, 23, 24, 22, 23, 24],
            'Sales 4' => [24, 25, 26, 24, 25, 26, 24, 25, 26, 24, 25, 26],
            'Sales 5' => [26, 27, 28, 26, 27, 28, 26, 27, 28, 26, 27, 28]
        ];

        $stTarget = $pdo->prepare("INSERT OR REPLACE INTO sales_targets (salesperson, target_month, revenue_target, visit_target) VALUES (?, ?, ?, ?)");
        for ($m = 1; $m <= 12; $m++) {
            $mStr = sprintf('2026-%02d', $m);
            foreach ($revTargets as $rep => $vals) {
                $rev = $vals[$m - 1];
                $vis = $visTargets[$rep][$m - 1];
                $stTarget->execute([$rep, $mStr, $rev, $vis]);
            }
        }

        // Quality Targets
        $stQual = $pdo->prepare("INSERT OR REPLACE INTO quality_targets (target_month, win_rate_target, avg_deal_size_target, forecast_coverage_target) VALUES (?, ?, ?, ?)");
        $avgDealSizes = [350000, 365000, 380000, 395000, 410000, 425000, 440000, 455000, 470000, 485000, 500000, 515000];
        for ($m = 1; $m <= 12; $m++) {
            $mStr = sprintf('2026-%02d', $m);
            $stQual->execute([$mStr, 0.25, $avgDealSizes[$m - 1], 1.1]);
        }

        // Default System Settings
        $pdo->exec("INSERT OR IGNORE INTO system_settings (key, value) VALUES ('report_month', '2026-03')");
        $pdo->exec("INSERT OR IGNORE INTO system_settings (key, value) VALUES ('app_name', 'MVS ICT & Telecom')");

        // Seed realistic ICT & Telecom sample opportunities (Saudi market)
        self::seedSampleOpportunities();
        self::seedCustomersAndProjects();
    }

    private static function seedSampleOpportunities(): void {
        $pdo = self::$instance;
        $sampleDeals = [
            // Closed Won Deals in March 2026
            [
                '2026-03-04', 'Sales 1', 'Eng. Sultan Al-Harbi', 'stc (Saudi Telecom)', 'Riyadh HQ', 'Eng. Sultan Al-Harbi',
                'Technical demo & procurement sign-off', 'VIAVI', 'Telecom', 'Existing Account',
                'Awarded tender for 40x VIAVI SmartOTDR optical testers for 5G FTTx expansion.',
                480000, 'Closed Won', null, '2026-03-24', 'Deliver initial batch & warranty certificate', '2026-04-10'
            ],
            [
                '2026-03-08', 'Sales 2', 'Tariq Mansoor', 'Mobily (Etihad Etisalat)', 'Jeddah Regional', 'Tariq Mansoor',
                'Commercial negotiation on fiber blowing machinery', 'Jetting', 'Telecom', 'Referral',
                'Final PO received for 3x Jetting TriggAIR & SuperJet cable blowing machines with accessories.',
                520000, 'Closed Won', null, '2026-03-26', 'Issue invoice and coordinate customs dispatch', '2026-04-15'
            ],
            [
                '2026-03-12', 'Sales 3', 'Abdulrahman Al-Otaibi', 'Salam Telecom (ITC)', 'Riyadh', 'Abdulrahman Al-Otaibi',
                'Data center cabinet delivery agreement', 'Cabinets', 'Telecom', 'Existing Account',
                'Signed contract for 65x customized 42U Server Racks and intelligent PDU systems.',
                610000, 'Closed Won', null, '2026-03-28', 'Site delivery inspection and acceptance testing', '2026-04-05'
            ],
            [
                '2026-03-02', 'Sales 4', 'Majed Al-Ghamdi', 'Saudi Electricity Company (SEC)', 'Khobar', 'Majed Al-Ghamdi',
                'Substation Low Current & CCTV framework', 'Low Current', 'Utilities', 'Tender',
                'Won direct procurement for substation perimeter surveillance and access control package.',
                720000, 'Closed Won', null, '2026-03-29', 'Project kick-off and factory acceptance test', '2026-04-20'
            ],
            [
                '2026-03-15', 'Sales 5', 'Fahad Al-Zahrani', 'Saudi Aramco Contracting Partner', 'Dhahran', 'Fahad Al-Zahrani',
                'Industrial fiber splicing & testing tools kit', 'Tools', 'Industrial', 'Partner',
                'Secured order for 12x heavy-duty fusion splicers and fiber termination toolkits.',
                790000, 'Closed Won', null, '2026-03-30', 'Deliver tools to Dhahran logistics depot', '2026-04-12'
            ],

            // Active High-Value Pipeline in Negotiation / Proposal Sent (March & April)
            [
                '2026-03-18', 'Sales 1', 'Hassan Al-Shehri', 'Zain KSA', 'Riyadh', 'Hassan Al-Shehri',
                'Final price review on VIAVI MTS-5800 100G testers', 'VIAVI', 'Telecom', 'Tender',
                'Client requested final 4% discount to match budget cap. Approval requested from GM.',
                650000, 'Negotiation', null, '2026-04-15', 'Submit revised BAFO price proposal', '2026-04-02'
            ],
            [
                '2026-03-20', 'Sales 2', 'Khaled Al-Dossary', 'Nesma & Partners', 'NEOM Site', 'Khaled Al-Dossary',
                'Optical micro-duct blowing equipment package', 'Jetting', 'Contractor', 'Inbound',
                'Proposal submitted for NEOM Spine fiber optic blowing equipment. Client reviewed specs.',
                850000, 'Negotiation', null, '2026-04-25', 'Arrange live field trial at project camp', '2026-04-08'
            ],
            [
                '2026-03-22', 'Sales 3', 'Bader Al-Mutairi', 'Ministry of Communications (MCIT)', 'Riyadh', 'Bader Al-Mutairi',
                'Edge data center cabinet clusters', 'Cabinets', 'Government', 'Referral',
                'Detailed compliance matrix submitted. Qualified technically, waiting for tender board decision.',
                940000, 'Proposal Sent', null, '2026-05-10', 'Follow up with procurement committee secretary', '2026-04-07'
            ],
            [
                '2026-03-25', 'Sales 4', 'Saud Al-Qahtani', 'Red Sea Global', 'Umluj', 'Saud Al-Qahtani',
                'Smart hospitality low current cabling & IoT', 'Low Current', 'Private', 'Partner',
                'Technical proposal presented. Architect approved bill of quantities.',
                780000, 'Proposal Sent', null, '2026-05-20', 'Send commercial quote to general contractor', '2026-04-14'
            ],
            [
                '2026-03-27', 'Sales 5', 'Waleed Al-Enazi', 'Marafiq Power & Water', 'Jubail', 'Waleed Al-Enazi',
                'Passive fiber distribution hubs (ODF) supply', 'Passive', 'Utilities', 'Existing Account',
                'Presented ODF rack solutions for Jubail seawater cooling facility modernization.',
                560000, 'Qualified', null, '2026-05-30', 'Conduct site measurements and cable entry review', '2026-04-16'
            ],
            [
                '2026-03-10', 'Sales 1', 'Nasser Al-Subaie', 'Al-Fanar Construction', 'Riyadh', 'Nasser Al-Subaie',
                'Fiber test equipment hire & sales package', 'VIAVI', 'Contractor', 'Cold Call',
                'Introduced VIAVI OneExpert DSL/Fiber analyzers. Client requested product brochure and demo.',
                320000, 'Meeting Scheduled', null, '2026-06-15', 'Schedule hands-on product demonstration', '2026-04-05'
            ],
            [
                '2026-03-14', 'Sales 2', 'Omar Bakhsh', 'Baud Telecom Company (BTC)', 'Jeddah', 'Omar Bakhsh',
                'Jetting spare parts & replacement lubricants', 'Jetting', 'Contractor', 'Existing Account',
                'Discussed annual service contract and spares bundle for existing fleet.',
                210000, 'Contacted', null, '2026-06-20', 'Share official quote for spares catalog', '2026-04-11'
            ],
            [
                '2026-03-28', 'Sales 3', 'Ibrahim Al-Binali', 'Rawabi Telecom', 'Khobar', 'Ibrahim Al-Binali',
                'Pre-terminated trunk cables and MPO cassettes', 'Passive', 'Telecom', 'Event',
                'Met at LEAP 2026 exhibition. Showed high interest in rapid deployment optical solutions.',
                290000, 'Lead', null, '2026-07-01', 'Send company profile and technical catalog', '2026-04-06'
            ],
            [
                '2026-03-05', 'Sales 4', 'Rami Kabbani', 'Consolidated Contractors Co (CCC)', 'Riyadh', 'Rami Kabbani',
                'Optical Loss Test Sets (OLTS) requirement', 'VIAVI', 'Contractor', 'Referral',
                'Lost to direct competitor due to delivery lead time constraint.',
                180000, 'Closed Lost', null, '2026-03-18', 'Maintain contact for Q3 maintenance requirements', '2026-05-01'
            ]
        ];

        $stInsert = $pdo->prepare("
            INSERT INTO sales_opportunities (
                opportunity_id, log_date, salesperson, client_name, company, location, contact_person,
                purpose, product_line, industry, lead_source, discussion_summary, opportunity_value,
                stage, default_probability, override_probability, forecast_probability, weighted_forecast,
                close_date, next_action, next_visit_date, visit_month, close_month
            ) VALUES (
                ?, ?, ?, ?, ?, ?, ?,
                ?, ?, ?, ?, ?, ?,
                ?, ?, ?, ?, ?,
                ?, ?, ?, ?, ?
            )
        ");

        $stageProbMap = [
            'Lead' => 0.10,
            'Contacted' => 0.20,
            'Meeting Scheduled' => 0.30,
            'Qualified' => 0.50,
            'Proposal Sent' => 0.65,
            'Negotiation' => 0.80,
            'Closed Won' => 1.00,
            'Closed Lost' => 0.00
        ];

        foreach ($sampleDeals as $idx => $deal) {
            $date = $deal[0];
            $oppId = str_replace('-', '', $date) . '-' . sprintf('%04d', $idx + 1);
            $stage = $deal[12];
            $val = (float)$deal[11];
            $override = $deal[13];
            $defProb = $stageProbMap[$stage] ?? 0.0;
            $fcProb = ($override !== null && $override !== '') ? (float)$override : $defProb;
            $weighted = round($val * $fcProb, 2);
            $closeDate = $deal[14];
            $nextAction = $deal[15];
            $nextVisitDate = $deal[16];
            $visitMonth = substr($date, 0, 7);
            $closeMonth = $closeDate ? substr($closeDate, 0, 7) : null;

            $stInsert->execute([
                $oppId, $date, $deal[1], $deal[2], $deal[3], $deal[4], $deal[5],
                $deal[6], $deal[7], $deal[8], $deal[9], $deal[10], $val,
                $stage, $defProb, $override, $fcProb, $weighted,
                $closeDate, $nextAction, $nextVisitDate, $visitMonth, $closeMonth
            ]);
        }
    }

    private static function seedCustomersAndProjects(): void {
        $pdo = self::$instance;

        // Seed Customers
        $customers = [
            ['stc (Saudi Telecom)', 'Telecom', 'Riyadh HQ', 'Eng. Sultan Al-Harbi', 'VP Infrastructure Procurement', '+966 11 455 5555', 'sultan.harbi@stc.com.sa', 'Al-Mursalat, Riyadh', 'Active Key Account', 'Tier-1 national telecom operator. Active VIAVI & FTTx equipment framework.'],
            ['Mobily (Etihad Etisalat)', 'Telecom', 'Jeddah Regional', 'Tariq Mansoor', 'Director Outside Plant', '+966 12 600 0000', 't.mansoor@mobily.com.sa', 'King Abdulaziz Rd, Jeddah', 'Active Key Account', 'Major fiber blowing machinery and tools client.'],
            ['Salam Telecom (ITC)', 'Telecom', 'Riyadh', 'Abdulrahman Al-Otaibi', 'Head of Core Datacenters', '+966 11 834 8000', 'a.otaibi@salam.sa', 'Olaya Towers, Riyadh', 'Active Client', 'Server racks, PDU, and high-density fiber patch panels.'],
            ['Saudi Electricity Company (SEC)', 'Utilities', 'Khobar', 'Majed Al-Ghamdi', 'Substation Automation Lead', '+966 13 858 6000', 'm.ghamdi@se.com.sa', 'Dammam Coastal Rd', 'Active Client', 'Substation security, perimeter surveillance, and industrial cabling.'],
            ['Saudi Aramco Partner / Contractor', 'Industrial', 'Dhahran', 'Fahad Al-Zahrani', 'Contracting Procurement Mgr', '+966 13 872 0115', 'f.zahrani@contractor-aramco.com', 'Dhahran Tech Valley', 'Active Partner', 'High-spec fusion splicers, optical safety gear, and industrial toolkits.'],
            ['Zain KSA', 'Telecom', 'Riyadh', 'Hassan Al-Shehri', 'Transmission Networks Mgr', '+966 11 500 0000', 'h.shehri@sa.zain.com', 'Granada Business Park, Riyadh', 'Prospect / Negotiation', 'Active RFQ for 100G Ethernet / optical test sets.'],
            ['Nesma & Partners', 'Contractor', 'NEOM / Tabuk', 'Khaled Al-Dossary', 'Project Procurement Director', '+966 12 651 8888', 'k.dossary@nesma.com', 'NEOM Community 1', 'Active Contractor', 'Contractor on mega NEOM Spine infrastructure projects.'],
            ['Red Sea Global', 'Private', 'Umluj / Tabuk', 'Saud Al-Qahtani', 'Smart Destination Lead', '+966 11 920 0000', 's.qahtani@redseaglobal.com', 'Riyadh Diplomatic Quarter', 'Qualified Lead', 'Hospitality fiber networks and low current IoT infrastructure.'],
            ['Seven Entertainment (SEVEN)', 'Government', 'Riyadh / Multi-Site', 'Rami Al-Saleh', 'Entertainment Infrastructure Director', '+966 11 200 7777', 'r.saleh@seven.sa', 'KAFD, Riyadh', 'Active Client', 'Major leisure and entertainment complex fiber & structured cabling.'],
            ['Marafiq Power & Water', 'Utilities', 'Jubail Industrial', 'Waleed Al-Enazi', 'Telecom Maintenance Head', '+966 13 340 1111', 'w.enazi@marafiq.com.sa', 'Jubail Industrial City', 'Active Client', 'Optical distribution frames and plant monitoring.']
        ];

        $stCust = $pdo->prepare("
            INSERT OR IGNORE INTO customers (
                company_name, industry, city_region, contact_person, designation,
                phone, email, address, account_status, notes
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        foreach ($customers as $c) {
            $stCust->execute($c);
        }

        // Seed Projects (Pillar 2 - Delivery Progress & Milestones, No Cashflow)
        $projects = [
            [
                'PRJ-2026-001', 'Seven Entertainment & Leisure Complexes (Track 1)', 'Seven Entertainment (SEVEN)',
                'Riyadh Exit 10 & Al-Hamra', 'Adrian Kim Mallilin', 68.0, 'On Track',
                '2026-01-10', '2026-08-30',
                'Supply, installation, and certification of high-capacity optical fiber backbone and low current distribution for entertainment pavilions.'
            ],
            [
                'PRJ-2026-002', 'Salam CCS Core Datacenter Expansion (Track 2)', 'Salam Telecom (ITC)',
                'Riyadh Datacenter Hub', 'Mohammed Saleh', 75.0, 'On Track',
                '2026-01-15', '2026-06-30',
                'Delivery & integration of 65x customized 42U Server Racks, intelligent switched PDUs, and MPO trunk optical cabling.'
            ],
            [
                'PRJ-2026-003', 'Dune Corridor DCP Spine Infrastructure (Track 3)', 'Nesma & Partners',
                'Western Province / Coastal Spine', 'Rami Maaz', 42.0, 'In Progress',
                '2026-02-01', '2026-10-15',
                'Micro-duct installation and Jetting optical fiber cable blowing over a 48 km trunk route.'
            ],
            [
                'PRJ-2026-004', 'Ritz Carlton ICT & Low Current Modernization', 'Private',
                'Riyadh Hotel & Conference Center', 'Tahir Anjum', 88.0, 'Handover Stage',
                '2025-11-01', '2026-04-30',
                'Comprehensive network room cabling, server rack clean-up, and Wi-Fi 6 AP connectivity certification.'
            ],
            [
                'PRJ-2026-005', 'stc 5G FTTx VIAVI SmartOTDR Deployment', 'stc (Saudi Telecom)',
                'Kingdom-wide (Multi-Region)', 'Ali Haidar', 90.0, 'Handover Stage',
                '2026-02-15', '2026-04-20',
                'Calibration, batch handover, and field engineer training for 40x VIAVI SmartOTDR handheld testers.'
            ]
        ];

        $stPrj = $pdo->prepare("
            INSERT OR IGNORE INTO projects (
                project_code, project_name, company_name, site_location, project_manager,
                progress_pct, status, start_date, target_end_date, scope_description
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        foreach ($projects as $p) {
            $stPrj->execute($p);
        }

        // Seed Milestones
        $stFindPrj = $pdo->prepare("SELECT id FROM projects WHERE project_code = ?");

        $milestones = [
            'PRJ-2026-001' => [
                ['Site Survey, Cable Route Approval & Submittals', '2026-02-01', 'Completed', 100],
                ['Material Delivery (Fiber, Conduits, ODF)', '2026-03-15', 'Completed', 100],
                ['Cable Pulling & Sub-duct Installation', '2026-05-10', 'In Progress', 70],
                ['Fusion Splicing & OTDR Loss Testing', '2026-07-15', 'Pending', 0],
                ['Final Handover, As-Built Drawings & PAC', '2026-08-30', 'Pending', 0]
            ],
            'PRJ-2026-002' => [
                ['Cabinet & PDU Factory Inspection & Approval', '2026-02-10', 'Completed', 100],
                ['Site Delivery of 65x Server Racks to Data Center', '2026-03-01', 'Completed', 100],
                ['Floor Anchoring, Grounding & PDU Power Wiring', '2026-04-10', 'In Progress', 85],
                ['MPO Pre-Terminated Trunk Cabling & Patching', '2026-05-20', 'In Progress', 40],
                ['Acceptance Testing & Client Handover', '2026-06-30', 'Pending', 0]
            ],
            'PRJ-2026-003' => [
                ['Mobilization, Trench Clearance & Route Stakeout', '2026-02-28', 'Completed', 100],
                ['Micro-duct Jointing & Air Pressure Testing', '2026-04-30', 'In Progress', 60],
                ['Jetting Cable Blowing Machinery Deployment (48 km)', '2026-07-31', 'Pending', 0],
                ['Chamber Splicing & Bidirectional OTDR Certification', '2026-09-15', 'Pending', 0],
                ['Final Acceptance & Provisional Handover', '2026-10-15', 'Pending', 0]
            ]
        ];

        $stMs = $pdo->prepare("INSERT INTO project_milestones (project_id, title, target_date, status, progress_pct) VALUES (?, ?, ?, ?, ?)");
        foreach ($milestones as $code => $msList) {
            $stFindPrj->execute([$code]);
            $pId = $stFindPrj->fetchColumn();
            if ($pId) {
                // Check if already seeded
                $stChk = $pdo->prepare("SELECT COUNT(*) FROM project_milestones WHERE project_id = ?");
                $stChk->execute([$pId]);
                if ((int)$stChk->fetchColumn() === 0) {
                    foreach ($msList as $ms) {
                        $stMs->execute([$pId, $ms[0], $ms[1], $ms[2], $ms[3]]);
                    }
                }
            }
        }
    }
}

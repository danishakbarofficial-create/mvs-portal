<?php
$b64 = trim(file_get_contents(__DIR__ . '/logo_base64.txt'));
$logoSrc = 'data:image/png;base64,' . $b64;

$html = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Proposal – MVS ICT & Telecom (Commercial & Executive Edition)</title>
<meta name="description" content="MVS ICT & Telecom - Centralized Project Progress, Sales Pipeline, Cost & Cash Flow Monitoring Dashboard">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Tajawal:wght@500;700&display=swap" rel="stylesheet">
<style>
  @page {
    size: A4;
    margin: 12mm 15mm 12mm 15mm;
  }
  @page cover {
    margin: 0;
  }

  :root {
    --gold: #BA9631;
    --gold-light: #D4B04C;
    --gold-dark: #97761F;
    --gold-subtle: #FAF8F2;
    --gold-border: #E8DECA;
    --charcoal: #1C1E21;
    --charcoal-light: #2A2D32;
    --slate: #4A4F55;
    --muted: #6B7280;
    --line: #E5E7EB;
    --card-bg: #FAF9F6;
    --white: #FFFFFF;
    --green: #15803D;
    --green-bg: #DCFCE7;
    --blue: #1D4ED8;
    --blue-bg: #DBEAFE;
    --amber: #B45309;
    --amber-bg: #FEF3C7;
  }

  * { box-sizing: border-box; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
  
  body {
    font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    color: var(--charcoal);
    font-size: 8.8pt;
    line-height: 1.48;
    margin: 0;
    background: #ffffff;
  }

  /* ================= COVER PAGE ================= */
  .cover {
    page: cover;
    height: 297mm;
    width: 210mm;
    position: relative;
    overflow: hidden;
    background: radial-gradient(circle at 85% 15%, #2D271B 0%, #17181A 45%, #0F1012 100%);
    color: var(--white);
    page-break-after: always;
  }
  .cover-glow {
    position: absolute;
    width: 130mm;
    height: 130mm;
    border-radius: 50%;
    right: -40mm;
    top: -30mm;
    background: radial-gradient(circle, rgba(186,150,49,0.28), rgba(186,150,49,0) 70%);
  }
  .cover-glow-2 {
    position: absolute;
    width: 140mm;
    height: 140mm;
    border-radius: 50%;
    left: -50mm;
    bottom: -40mm;
    background: radial-gradient(circle, rgba(186,150,49,0.18), rgba(186,150,49,0) 70%);
  }
  .cover-grid {
    position: absolute;
    inset: 0;
    opacity: 0.04;
    background-image: linear-gradient(#fff 1px, transparent 1px), linear-gradient(90deg, #fff 1px, transparent 1px);
    background-size: 10mm 10mm;
  }
  .cover-inner {
    position: relative;
    padding: 22mm 20mm 18mm 20mm;
    height: 100%;
    display: flex;
    flex-direction: column;
  }
  .cover-brand {
    display: flex;
    align-items: center;
    gap: 16px;
  }
  .cover-logo-img {
    height: 58px;
    width: auto;
    object-fit: contain;
    filter: drop-shadow(0 4px 12px rgba(186,150,49,0.25));
  }
  .cover-brand-text {
    border-left: 1.5px solid rgba(186,150,49,0.45);
    padding-left: 14px;
  }
  .cover-brand-en {
    font-size: 10.5pt;
    font-weight: 700;
    letter-spacing: 2px;
    color: #F5E8C7;
    text-transform: uppercase;
  }
  .cover-brand-ar {
    font-family: 'Tajawal', sans-serif;
    font-size: 9.5pt;
    color: #C7CBD1;
    margin-top: 2px;
  }
  .cover-badge {
    margin-top: 32mm;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 8pt;
    font-weight: 600;
    letter-spacing: 2.2px;
    text-transform: uppercase;
    color: var(--gold-light);
    border: 1px solid rgba(186,150,49,0.45);
    background: rgba(186,150,49,0.1);
    padding: 5px 13px;
    border-radius: 20px;
    width: max-content;
  }
  .cover-title {
    font-size: 36pt;
    line-height: 1.05;
    margin: 5mm 0 3mm 0;
    font-weight: 800;
    letter-spacing: -0.5px;
  }
  .cover-title span {
    background: linear-gradient(110deg, #F3DE9C 0%, #BA9631 50%, #8C6F1E 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
  }
  .cover-sub {
    font-size: 12pt;
    color: #D6D9DE;
    font-weight: 400;
    line-height: 1.4;
    max-width: 150mm;
  }
  .cover-rule {
    width: 45mm;
    height: 3px;
    border-radius: 3px;
    background: linear-gradient(90deg, var(--gold-light), var(--gold-dark));
    margin: 6mm 0;
  }
  .cover-projects {
    display: flex;
    gap: 7px;
    flex-wrap: wrap;
    margin-top: 1mm;
    max-width: 160mm;
  }
  .cover-pill {
    font-size: 8pt;
    font-weight: 500;
    padding: 5px 12px;
    border-radius: 6px;
    background: rgba(255,255,255,0.06);
    border: 1px solid rgba(186,150,49,0.3);
    color: #E2E4E8;
  }
  .cover-pill b {
    color: var(--gold-light);
    margin-right: 4px;
  }
  .cover-meta {
    margin-top: auto;
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 15px;
    border-top: 1px solid rgba(186,150,49,0.3);
    padding-top: 6mm;
  }
  .cover-meta-k {
    font-size: 7pt;
    text-transform: uppercase;
    letter-spacing: 2px;
    color: #9A9FA8;
  }
  .cover-meta-v {
    font-size: 10.5pt;
    font-weight: 700;
    color: #FFFFFF;
    margin-top: 3px;
  }
  .cover-meta-v small {
    display: block;
    font-weight: 500;
    color: var(--gold-light);
    font-size: 8pt;
  }
  .cover-confidential {
    position: absolute;
    bottom: 8mm;
    right: 20mm;
    font-size: 7pt;
    letter-spacing: 2px;
    color: #6C727A;
    text-transform: uppercase;
  }

  /* ================= LETTERHEAD RUNNING HEADERS & FOOTERS ================= */
  .running-header {
    border-bottom: 2px solid var(--gold);
    padding-bottom: 2.5mm;
    margin-bottom: 4mm;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
  .rh-left {
    display: flex;
    align-items: center;
    gap: 10px;
  }
  .rh-logo {
    height: 34px;
    width: auto;
    object-fit: contain;
  }
  .rh-en-title {
    font-size: 9pt;
    font-weight: 800;
    color: var(--charcoal);
    line-height: 1.2;
    letter-spacing: 0.2px;
  }
  .rh-en-cr {
    font-size: 7.2pt;
    font-weight: 600;
    color: var(--slate);
    letter-spacing: 0.5px;
  }
  .rh-right {
    text-align: right;
    font-family: 'Tajawal', sans-serif;
  }
  .rh-ar-title {
    font-size: 9pt;
    font-weight: 700;
    color: var(--charcoal);
    line-height: 1.2;
  }
  .rh-ar-cr {
    font-size: 7.5pt;
    color: var(--slate);
    direction: rtl;
  }

  .running-footer {
    border-top: 1.5px solid var(--gold);
    padding-top: 2mm;
    margin-top: auto;
    font-size: 7.2pt;
    color: var(--slate);
  }
  .rf-tagline {
    text-align: center;
    font-size: 7.6pt;
    font-weight: 600;
    color: var(--gold-dark);
    letter-spacing: 1.5px;
    margin-bottom: 1.8mm;
    text-transform: uppercase;
  }
  .rf-details {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 8px;
  }
  .rf-item {
    display: flex;
    align-items: center;
    gap: 4px;
  }
  .rf-item b {
    color: var(--charcoal);
  }

  /* ================= CONTENT STYLES ================= */
  .page {
    page-break-after: always;
    height: 273mm;
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    position: relative;
    overflow: hidden;
  }
  .page:last-child {
    page-break-after: avoid;
  }

  h2 {
    font-size: 12.5pt;
    color: var(--charcoal);
    margin: 0 0 2.5mm 0;
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 800;
    page-break-after: avoid;
  }
  h2 .num {
    display: inline-flex;
    width: 23px;
    height: 23px;
    border-radius: 5px;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, var(--gold), var(--gold-dark));
    color: #fff;
    font-size: 8.5pt;
    font-weight: 700;
  }
  h3 {
    font-size: 9.5pt;
    color: var(--gold-dark);
    margin: 3mm 0 1.5mm 0;
    font-weight: 700;
    page-break-after: avoid;
    letter-spacing: 0.1px;
  }
  p {
    margin: 0 0 2.5mm 0;
    color: var(--slate);
  }
  p b, p strong {
    color: var(--charcoal);
  }
  ul, ol {
    margin: 0 0 2.5mm 0;
    padding-left: 4.5mm;
    color: var(--slate);
  }
  li {
    margin-bottom: 1.2mm;
  }
  li b, li strong {
    color: var(--charcoal);
  }

  /* TABLES */
  table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    margin: 2mm 0 3mm 0;
    font-size: 8pt;
    border: 1px solid var(--gold-border);
    border-radius: 6px;
    overflow: hidden;
  }
  th {
    background: var(--charcoal);
    color: #FFFFFF;
    text-align: left;
    font-weight: 700;
    padding: 5.5px 8px;
    font-size: 7.5pt;
    letter-spacing: 0.3px;
    text-transform: uppercase;
    border-bottom: 2px solid var(--gold);
  }
  td {
    padding: 5px 8px;
    border-top: 1px solid var(--gold-border);
    vertical-align: middle;
    color: var(--slate);
  }
  tr:nth-child(even) td {
    background: var(--gold-subtle);
  }
  td:first-child {
    font-weight: 600;
    color: var(--charcoal);
  }

  /* BADGES */
  .badge {
    display: inline-block;
    padding: 2px 7px;
    border-radius: 4px;
    font-size: 7pt;
    font-weight: 700;
    letter-spacing: 0.3px;
    text-transform: uppercase;
  }
  .badge-won { background: #DEF7EC; color: #03543F; border: 1px solid #31C48D; }
  .badge-quoted { background: #E1EFFE; color: #1E429F; border: 1px solid #76A9FA; }
  .badge-neg { background: #FEF08A; color: #854D0E; border: 1px solid #FACC15; }
  .badge-lead { background: #F3F4F6; color: #374151; border: 1px solid #D1D5DB; }

  /* CALLOUT / EXECUTIVE BOX */
  .exec-box {
    background: linear-gradient(135deg, #FAF7EE 0%, #F5EEDC 100%);
    border-left: 3.5px solid var(--gold);
    border-radius: 7px;
    padding: 3mm 4.5mm;
    margin: 2.5mm 0;
    border-top: 1px solid var(--gold-border);
    border-right: 1px solid var(--gold-border);
    border-bottom: 1px solid var(--gold-border);
  }
  .exec-box p {
    color: var(--charcoal);
    margin: 0;
    font-size: 8.4pt;
  }

  /* KPI TILES */
  .kpi-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 2.5mm;
    margin: 2.5mm 0 3mm 0;
  }
  .kpi-tile {
    border: 1px solid var(--gold-border);
    border-radius: 7px;
    padding: 2.8mm 2mm;
    text-align: center;
    background: #FFFFFF;
    box-shadow: 0 1px 2px rgba(0,0,0,0.02);
  }
  .kpi-ic {
    width: 25px;
    height: 25px;
    margin: 0 auto 1.5mm auto;
    border-radius: 5px;
    background: linear-gradient(135deg, var(--gold-light), var(--gold-dark));
    color: #fff;
    font-weight: 800;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 9pt;
  }
  .kpi-t {
    font-weight: 700;
    color: var(--charcoal);
    font-size: 8.2pt;
    line-height: 1.2;
  }
  .kpi-d {
    font-size: 7pt;
    color: var(--muted);
    margin-top: 1.5px;
  }

  /* ARCHITECTURE DIAGRAM */
  .diagram-box {
    background: #FAFAFA;
    border: 1px solid var(--gold-border);
    border-radius: 8px;
    padding: 3.5mm;
    margin: 2mm 0 3.5mm 0;
    text-align: center;
  }
  .node-mgmt {
    display: inline-block;
    background: linear-gradient(135deg, #1C1E21, #2D3136);
    color: #FFFFFF;
    padding: 2.8mm 6mm;
    border-radius: 7px;
    font-weight: 700;
    font-size: 8.8pt;
    border-bottom: 2.5px solid var(--gold);
    min-width: 95mm;
  }
  .node-mgmt small {
    display: block;
    font-weight: 400;
    color: var(--gold-light);
    font-size: 7.2pt;
    margin-top: 0.8mm;
  }
  .node-connector {
    width: 2px;
    height: 4mm;
    background: var(--gold);
    margin: 0 auto;
  }
  .node-hline {
    height: 2px;
    background: var(--gold);
    margin: 0 auto;
    width: 140mm;
  }
  .node-children {
    display: flex;
    justify-content: space-between;
    width: 155mm;
    margin: 0 auto;
  }
  .node-col {
    width: 48mm;
    display: flex;
    flex-direction: column;
    align-items: center;
  }
  .node-pillar {
    background: #FFFFFF;
    border: 1.5px solid var(--gold);
    border-radius: 6px;
    color: var(--charcoal);
    padding: 2mm 2.5mm;
    width: 47mm;
    font-weight: 700;
    font-size: 8pt;
    box-shadow: 0 1px 3px rgba(186,150,49,0.1);
  }
  .node-pillar small {
    display: block;
    font-weight: 500;
    font-size: 7pt;
    color: var(--slate);
    margin-top: 0.8mm;
  }

  /* 3-COLUMN CARDS */
  .three-cards {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 3mm;
    margin: 2.5mm 0 3.5mm 0;
  }
  .c-card {
    border: 1px solid var(--gold-border);
    border-radius: 7px;
    padding: 3mm 3.5mm;
    background: #FFFFFF;
  }
  .c-card.p1 { border-top: 3px solid #2563EB; }
  .c-card.p2 { border-top: 3px solid var(--gold); }
  .c-card.p3 { border-top: 3px solid #059669; }
  .c-card h4 {
    margin: 0 0 1.5mm 0;
    font-size: 8.5pt;
    font-weight: 800;
    color: var(--charcoal);
  }
  .c-card ul {
    margin: 0;
    padding-left: 3.5mm;
    font-size: 7.6pt;
  }
  .c-card li {
    margin-bottom: 1mm;
  }

  /* BENEFITS GRID */
  .benefits-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2mm;
    margin: 2mm 0 3mm 0;
  }
  .benefit-card {
    display: flex;
    gap: 2.5mm;
    border: 1px solid var(--gold-border);
    border-radius: 6px;
    padding: 2mm 3mm;
    background: #FFFFFF;
  }
  .b-num {
    flex: 0 0 18px;
    height: 18px;
    border-radius: 50%;
    background: var(--gold);
    color: #FFFFFF;
    font-size: 7.5pt;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .benefit-card b {
    color: var(--charcoal);
    display: block;
    font-size: 8pt;
  }
  .benefit-card span {
    font-size: 7.2pt;
    color: var(--slate);
    line-height: 1.3;
    display: block;
  }

  /* TIMELINE */
  .tl-container {
    margin: 1.5mm 0 3mm 0;
  }
  .tl-phase {
    display: flex;
    gap: 3mm;
    margin-bottom: 2mm;
  }
  .tl-badge {
    flex: 0 0 auto;
    width: 23mm;
    text-align: center;
    border-radius: 5px;
    padding: 1.8mm 0;
    font-weight: 700;
    font-size: 7.5pt;
    background: var(--gold-subtle);
    border: 1px solid var(--gold-border);
    color: var(--charcoal);
  }
  .tl-phase.core .tl-badge {
    background: linear-gradient(135deg, var(--gold), var(--gold-dark));
    color: #FFFFFF;
    border: none;
  }
  .tl-content {
    flex: 1;
    border-bottom: 1px dashed var(--gold-border);
    padding-bottom: 1.5mm;
    font-size: 8pt;
  }
  .tl-content b {
    color: var(--charcoal);
  }
  .tl-dur {
    flex: 0 0 18mm;
    text-align: right;
    font-weight: 700;
    color: var(--gold-dark);
    font-size: 8pt;
  }
  .tl-banner {
    background: var(--charcoal);
    color: #FFFFFF;
    border-radius: 6px;
    padding: 2.5mm 4mm;
    font-weight: 600;
    text-align: center;
    border: 1px solid var(--gold);
    font-size: 8.5pt;
  }
  .tl-banner span {
    color: var(--gold-light);
    font-weight: 800;
  }

  /* SIGNATURE SECTION */
  .sign-area {
    margin-top: 4mm;
    padding-top: 3mm;
    border-top: 1.5px solid var(--gold-border);
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
  }
  .sign-box {
    text-align: right;
  }
  .sign-line {
    width: 48mm;
    border-top: 1.5px solid var(--charcoal);
    margin-bottom: 1.5mm;
    margin-left: auto;
  }
  .sign-name {
    font-weight: 800;
    color: var(--charcoal);
    font-size: 10pt;
  }
  .sign-role {
    color: var(--gold-dark);
    font-weight: 600;
    font-size: 8pt;
  }
</style>
</head>
<body>

<!-- ==================== PAGE 1: COVER PAGE ==================== -->
<section class="cover">
  <div class="cover-grid"></div>
  <div class="cover-glow"></div>
  <div class="cover-glow-2"></div>
  <div class="cover-inner">
    
    <div class="cover-brand">
      <img src="{$logoSrc}" alt="MVS Logo" class="cover-logo-img">
      <div class="cover-brand-text">
        <div class="cover-brand-en">Modern Vision Solutions Ltd</div>
        <div class="cover-brand-ar">شركة رؤية الحلول العصرية المحدودة</div>
      </div>
    </div>

    <div class="cover-badge">Executive Proposal &bull; Commercial Edition</div>
    
    <h1 class="cover-title">Modern Vision <span>Solutions</span></h1>
    <div class="cover-sub">Centralized Sales Pipeline, Project Milestones, Executive Financials &amp; Cash Flow Dashboard</div>
    
    <div class="cover-rule"></div>
    
    <div class="cover-projects">
      <div class="cover-pill"><b>Track 1:</b> Seven Entertainment</div>
      <div class="cover-pill"><b>Track 2:</b> Salam CCS</div>
      <div class="cover-pill"><b>Track 3:</b> Dune Corridor Project (DCP)</div>
      <div class="cover-pill" style="border: 1px dashed rgba(212,176,76,0.7); background: rgba(186,150,49,0.15); color: #F5E8C7;"><b>Track 4+:</b> Future Projects (Instant Onboarding &bull; Infinite Scale)</div>
      <div class="cover-pill"><b>Commercial:</b> Sales Pipeline &amp; Quotation Closings</div>
      <div class="cover-pill"><b>Cash Flow:</b> Executive Summary, Petty Cash &amp; Purchases</div>
    </div>

    <div class="cover-meta">
      <div>
        <div class="cover-meta-k">Prepared for</div>
        <div class="cover-meta-v">Chief Executive Officer<small>MVS Executive Leadership</small></div>
      </div>
      <div>
        <div class="cover-meta-k">Prepared by</div>
        <div class="cover-meta-v">Danish Akbar<small>IT Support</small></div>
      </div>
      <div>
        <div class="cover-meta-k">Document Status</div>
        <div class="cover-meta-v">Official Proposal<small>Version 2.0 &bull; Commercial &amp; Cashflow Update</small></div>
      </div>
    </div>

  </div>
  <div class="cover-confidential">MVS Confidential &bull; Internal Use Only</div>
</section>

<!-- ==================== PAGE 2: EXECUTIVE SUMMARY & OPERATIONAL CHALLENGES ==================== -->
<div class="page">
  <div class="running-header">
    <div class="rh-left">
      <img src="{$logoSrc}" alt="MVS Logo" class="rh-logo">
      <div>
        <div class="rh-en-title">Modern Vision Solutions Ltd</div>
        <div class="rh-en-cr">CR: 7036282569</div>
      </div>
    </div>
    <div class="rh-right">
      <div class="rh-ar-title">شركة رؤية الحلول العصرية المحدودة</div>
      <div class="rh-ar-cr">س.ت : ٧٠٣٦٢٨٢٥٦٩</div>
    </div>
  </div>

  <h2><span class="num">1</span>Executive Summary</h2>
  <p>Modern Vision Solutions Ltd is expanding its commercial footprint across major enterprise infrastructure and technology contracts, actively delivering <strong>Seven Entertainment</strong>, <strong>Salam CCS</strong>, and the <strong>Dune Corridor Project (DCP)</strong>. Engineered with an extensible multi-project core, the platform provides <strong>instant onboarding for any new contract (Track 4+)</strong> as soon as a quotation is won or a PO is awarded. In direct response to recent executive directives from the <strong>Chief Executive Officer</strong>, this upgraded system architecture establishes a unified corporate command platform integrating three vital business pillars:</p>
  
  <div class="kpi-grid">
    <div class="kpi-tile">
      <div class="kpi-ic">&#128188;</div>
      <div class="kpi-t">Sales Pipeline</div>
      <div class="kpi-d">Quotations, closings &amp; win rates</div>
    </div>
    <div class="kpi-tile">
      <div class="kpi-ic">&#9874;</div>
      <div class="kpi-t">Field Execution</div>
      <div class="kpi-d">Milestones, progress % &amp; blockers</div>
    </div>
    <div class="kpi-tile">
      <div class="kpi-ic">&#128178;</div>
      <div class="kpi-t">Executive Summary</div>
      <div class="kpi-d">PO value, invoiced, received &amp; remaining</div>
    </div>
    <div class="kpi-tile">
      <div class="kpi-ic">&#128181;</div>
      <div class="kpi-t">Cash Flow Control</div>
      <div class="kpi-d">Petty cash, purchases &amp; allowances</div>
    </div>
  </div>

  <div class="exec-box">
    <p><strong>CEO Directive Alignment:</strong> The system incorporates a dedicated <strong>Sales Pipeline Tracker</strong> (quotation numbers, client opportunities, quote values, and closing forecasts), built with open architecture to seamlessly adapt to the CEO's designated reporting template. Simultaneously, it introduces an <strong>Executive Financial Summary</strong> coupled with daily <strong>Cash Flow Governance</strong> (petty cash logs, site purchases, and technician allowances).<br><br>
    <strong>In-House Advantage:</strong> Developed internally by MVS IT utilizing high-performance enterprise PHP (Laravel) + MySQL. <strong>Zero recurring software subscription fees</strong>, with Phase 1 operational deployment delivered within <strong>4 weeks</strong> of executive sanction.</p>
  </div>

  <h2><span class="num">2</span>Current Operational Challenges &amp; Bottlenecks</h2>
  <table>
    <thead>
      <tr>
        <th style="width:38%">Current Bottleneck</th>
        <th>Impact on Operations, Sales &amp; Cash Flow</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>No Central Sales Pipeline for Quotations</td>
        <td>Quotations and expected closings are scattered across emails and offline sheets; future incoming revenue is difficult to forecast accurately.</td>
      </tr>
      <tr>
        <td>Fragmented Field Updates Across Projects</td>
        <td>Site progress across Seven Entertainment, Salam CCS, Dune Corridor (DCP), and newly acquired projects lacks a standardized reporting cadence, delaying consolidated management reporting.</td>
      </tr>
      <tr>
        <td>Manual Reconciliation of Invoicing &amp; Cash Receipts</td>
        <td>Difficult to instantly see the real-time gap between Total PO Value, Certified Invoices, Collected Cash, and Overdue Client Receivables.</td>
      </tr>
      <tr>
        <td>Unstructured Site Petty Cash &amp; Purchases</td>
        <td>Site cash expenditures, urgent purchases, and vendor receipts are logged retrospectively, risking cash leakage and budget overruns.</td>
      </tr>
      <tr>
        <td>Untracked Site Allowances &amp; Field Logistics</td>
        <td>Technician per-diems, lodging, travel, and site allowances are disconnected from the specific project cost ledger.</td>
      </tr>
      <tr>
        <td>Late Detection of Blockers &amp; Material Delays</td>
        <td>Site constraints and client approvals reach executive leadership only after project delivery dates have been compromised.</td>
      </tr>
    </tbody>
  </table>

  <div class="running-footer">
    <div class="rf-tagline">Where Vision Becomes a Landmark</div>
    <div class="rf-details">
      <div class="rf-item"><b>T:</b> +966 536 700 701 / +966 506 586 534</div>
      <div class="rf-item"><b>E:</b> info@mvsksa.com &bull; www.mvsksa.com</div>
      <div class="rf-item"><b>A:</b> Al Rashid Tower, King Saud Rd, Riyadh, KSA</div>
    </div>
  </div>
</div>

<!-- ==================== PAGE 3: ARCHITECTURE & 3-PILLAR FOUNDATION ==================== -->
<div class="page">
  <div class="running-header">
    <div class="rh-left">
      <img src="{$logoSrc}" alt="MVS Logo" class="rh-logo">
      <div>
        <div class="rh-en-title">Modern Vision Solutions Ltd</div>
        <div class="rh-en-cr">CR: 7036282569</div>
      </div>
    </div>
    <div class="rh-right">
      <div class="rh-ar-title">شركة رؤية الحلول العصرية المحدودة</div>
      <div class="rh-ar-cr">س.ت : ٧٠٣٦٢٨٢٥٦٩</div>
    </div>
  </div>

  <h2><span class="num">3</span>System Architecture &amp; The 3-Pillar Model</h2>
  <p>The system is engineered as an encrypted web portal accessible across desktop workstations, tablets, and smartphones. It establishes a unified operational pipeline from lead acquisition to project closeout:</p>

  <div class="diagram-box">
    <div class="node-mgmt">
      EXECUTIVE MANAGEMENT DASHBOARD (CEO / Board)
      <small>Sales Pipeline &bull; Consolidated Revenue &bull; Cash Flow &bull; Petty Cash Ledgers &bull; Project Health</small>
    </div>
    <div class="node-connector"></div>
    <div class="node-hline"></div>
    <div class="node-children">
      <div class="node-col">
        <div class="node-connector"></div>
        <div class="node-pillar" style="border-color:#2563EB;">
          PILLAR 1: Sales Pipeline
          <small>Quotations &bull; Closings &bull; Win Rates</small>
        </div>
      </div>
      <div class="node-col">
        <div class="node-connector"></div>
        <div class="node-pillar" style="border-color:var(--gold);">
          PILLAR 2: Project Delivery
          <small>Role-Isolated PM Dashboards</small>
        </div>
      </div>
      <div class="node-col">
        <div class="node-connector"></div>
        <div class="node-pillar" style="border-color:#059669;">
          PILLAR 3: Finance &amp; Cash Flow
          <small>Invoiced, Received, Petty Cash</small>
        </div>
      </div>
    </div>
  </div>

  <div class="three-cards">
    <div class="c-card p1">
      <h4>Pillar 1: Sales Pipeline</h4>
      <ul>
        <li><strong>Quotation Registry:</strong> Central logging of Quote #, Client, Scope, and Date.</li>
        <li><strong>Expected Closings:</strong> Projected close date and probability weighting (SAR).</li>
        <li><strong>Status Stages:</strong> Lead &rarr; Quoted &rarr; Negotiation &rarr; Won &rarr; Contract.</li>
        <li><strong>Flexible Data Ingestion:</strong> Formatted to adopt CEO's template layout.</li>
      </ul>
    </div>

    <div class="c-card p2">
      <h4>Pillar 2: Field Delivery (PMs)</h4>
      <ul>
        <li><strong>Strict Data Isolation:</strong> PMs view and update only their assigned project.</li>
        <li><strong>Active Tracks:</strong> Seven Entertainment, Salam CCS, and Dune Corridor (DCP).</li>
        <li><strong>Track 4+ Ready:</strong> 1-click addition of new projects with custom milestones.</li>
        <li><strong>Blocker Logging:</strong> Rapid escalation of site bottlenecks and client hold-ups.</li>
      </ul>
    </div>

    <div class="c-card p3">
      <h4>Pillar 3: Cash Flow Hub</h4>
      <ul>
        <li><strong>Executive Summary:</strong> PO Value, Invoiced, Received, and Remaining.</li>
        <li><strong>Petty Cash Sub-Ledger:</strong> PM cash issuance, vouchers &amp; receipt verification.</li>
        <li><strong>Purchases &amp; Materials:</strong> Local vendor bills &amp; emergency site supplies.</li>
        <li><strong>Allowances:</strong> Technician per-diems, transport, accommodation &amp; labor.</li>
      </ul>
    </div>
  </div>

  <h3>3.4 Automated Executive Reporting &amp; Alerts</h3>
  <ul>
    <li><strong>CEO Weekly Flash Report:</strong> One-page PDF generated every Thursday night summarizing new quotations won, invoiced amounts, cash collected, and open receivables.</li>
    <li><strong>Cash Outflow Monitoring:</strong> Instant warning when a site PM's petty cash or site purchase burn rate exceeds baseline project allowances.</li>
    <li><strong>Aged Receivables Flag:</strong> Automated notification for certified client invoices outstanding beyond 30, 60, or 90 days.</li>
  </ul>

  <div class="running-footer">
    <div class="rf-tagline">Where Vision Becomes a Landmark</div>
    <div class="rf-details">
      <div class="rf-item"><b>T:</b> +966 536 700 701 / +966 506 586 534</div>
      <div class="rf-item"><b>E:</b> info@mvsksa.com &bull; www.mvsksa.com</div>
      <div class="rf-item"><b>A:</b> Al Rashid Tower, King Saud Rd, Riyadh, KSA</div>
    </div>
  </div>
</div>

<!-- ==================== PAGE 4: COMMERCIAL PIPELINE & EXECUTIVE FINANCIAL SUMMARY ==================== -->
<div class="page">
  <div class="running-header">
    <div class="rh-left">
      <img src="{$logoSrc}" alt="MVS Logo" class="rh-logo">
      <div>
        <div class="rh-en-title">Modern Vision Solutions Ltd</div>
        <div class="rh-en-cr">CR: 7036282569</div>
      </div>
    </div>
    <div class="rh-right">
      <div class="rh-ar-title">شركة رؤية الحلول العصرية المحدودة</div>
      <div class="rh-ar-cr">س.ت : ٧٠٣٦٢٨٢٥٦٩</div>
    </div>
  </div>

  <h2><span class="num">4</span>Commercial &amp; Executive Modules (CEO Focus)</h2>
  
  <h3>4.1 Sales Pipeline &amp; Quotation Management Module</h3>
  <p>To eliminate quotation blindness and provide reliable commercial forecasting, all business opportunities are registered under a unified sales pipeline:</p>

  <table>
    <thead>
      <tr>
        <th style="width:18%">Quotation #</th>
        <th style="width:25%">Client / Project Opportunity</th>
        <th style="width:16%">Value (SAR)</th>
        <th style="width:18%">Expected Closing</th>
        <th style="width:12%">Win Prob.</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>MVS-QT-2026-088</td>
        <td>Seven Entertainment &ndash; Phase II Expansion</td>
        <td>SAR 1,850,000</td>
        <td>End of Oct 2026</td>
        <td>85%</td>
        <td><span class="badge badge-neg">Negotiation</span></td>
      </tr>
      <tr>
        <td>MVS-QT-2026-092</td>
        <td>Dammam Municipality &ndash; Gate Sensors</td>
        <td>SAR 420,000</td>
        <td>Mid Nov 2026</td>
        <td>70%</td>
        <td><span class="badge badge-quoted">Quoted</span></td>
      </tr>
      <tr>
        <td>MVS-QT-2026-095</td>
        <td>Cinema Tech &ndash; DCP Multi-Hall Upgrade</td>
        <td>SAR 960,000</td>
        <td>Nov 2026</td>
        <td>90%</td>
        <td><span class="badge badge-won">Won / Awarded</span></td>
      </tr>
      <tr>
        <td>MVS-QT-2026-101</td>
        <td>Riyadh Commercial Complex &ndash; Fiber Cabling</td>
        <td>SAR 640,000</td>
        <td>Dec 2026</td>
        <td>40%</td>
        <td><span class="badge badge-lead">Lead / Review</span></td>
      </tr>
    </tbody>
  </table>

  <div class="exec-box" style="margin-top:1.5mm;">
    <p><strong>Adaptability to CEO Template:</strong> The module architecture is designed with custom field mapping. As soon as the CEO provides the finalized Excel or document format, the table structure will be customized to mirror the exact column names, formulas, and stage codes requested.</p>
  </div>

  <h3>4.2 Executive Financial Summary (Portfolio Level)</h3>
  <p>Consolidated macro view giving the CEO real-time clarity over contract values, billed revenue, bank receipts, and pending receivables across all operations:</p>

  <table>
    <thead>
      <tr>
        <th style="width:22%">Project Scope</th>
        <th style="width:16%">Contract Value</th>
        <th style="width:15%">Invoiced to Date</th>
        <th style="width:15%">Received (Cash)</th>
        <th style="width:16%">Remaining to Bill</th>
        <th>Overdue Receiv.</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>Track 1: Seven Entertainment</td>
        <td>SAR 2,450,000</td>
        <td>SAR 1,470,000 (60%)</td>
        <td>SAR 1,120,000</td>
        <td>SAR 980,000</td>
        <td>SAR 350,000 (45d)</td>
      </tr>
      <tr>
        <td>Track 2: Salam CCS</td>
        <td>SAR 1,100,000</td>
        <td>SAR 770,000 (70%)</td>
        <td>SAR 660,000</td>
        <td>SAR 330,000</td>
        <td>SAR 110,000 (20d)</td>
      </tr>
      <tr>
        <td>Track 3: Dune Corridor Project (DCP)</td>
        <td>SAR 850,000</td>
        <td>SAR 340,000 (40%)</td>
        <td>SAR 340,000</td>
        <td>SAR 510,000</td>
        <td>SAR 0 (Current)</td>
      </tr>
      <tr style="background:#FAF8F2; font-style:italic; font-size:7.4pt;">
        <td style="color:var(--gold-dark); font-weight:700;">Track 4+: New Awarded Projects</td>
        <td colspan="5" style="text-align:center; color:var(--slate); font-weight:500;">
          + Instant Modular Integration: Automatically populated into Financial Summary upon PO award
        </td>
      </tr>
      <tr style="font-weight:700; background:#F5EEDC;">
        <td>TOTAL PORTFOLIO</td>
        <td style="color:var(--charcoal);">SAR 4,400,000</td>
        <td style="color:#2563EB;">SAR 2,580,000</td>
        <td style="color:#059669;">SAR 2,120,000</td>
        <td style="color:#D97706;">SAR 1,820,000</td>
        <td style="color:#DC2626;">SAR 460,000</td>
      </tr>
    </tbody>
  </table>

  <ul>
    <li><strong>Remaining Revenue Pipeline:</strong> Contract Value minus Invoiced Amount &mdash; indicates certified revenue yet to be earned upon milestone completions.</li>
    <li><strong>Cash Collection Efficiency:</strong> Realized cash received vs certified invoices issued (identifying bottlenecks with client finance departments).</li>
  </ul>

  <div class="running-footer">
    <div class="rf-tagline">Where Vision Becomes a Landmark</div>
    <div class="rf-details">
      <div class="rf-item"><b>T:</b> +966 536 700 701 / +966 506 586 534</div>
      <div class="rf-item"><b>E:</b> info@mvsksa.com &bull; www.mvsksa.com</div>
      <div class="rf-item"><b>A:</b> Al Rashid Tower, King Saud Rd, Riyadh, KSA</div>
    </div>
  </div>
</div>

<!-- ==================== PAGE 5: CASH FLOW, PETTY CASH & SITE EXPENSES ==================== -->
<div class="page">
  <div class="running-header">
    <div class="rh-left">
      <img src="{$logoSrc}" alt="MVS Logo" class="rh-logo">
      <div>
        <div class="rh-en-title">Modern Vision Solutions Ltd</div>
        <div class="rh-en-cr">CR: 7036282569</div>
      </div>
    </div>
    <div class="rh-right">
      <div class="rh-ar-title">شركة رؤية الحلول العصرية المحدودة</div>
      <div class="rh-ar-cr">س.ت : ٧٠٣٦٢٨٢٥٦٩</div>
    </div>
  </div>

  <h2><span class="num">5</span>Cash Flow &amp; Site Expense Governance</h2>
  <p>To eliminate informal site cash handling and give executive management total transparency over operational outgoings, the system incorporates four dedicated expense modules:</p>

  <table>
    <thead>
      <tr>
        <th style="width:24%">Expense Stream</th>
        <th style="width:38%">Operational Workflow &amp; Controls</th>
        <th>Management Impact</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>1. Site Petty Cash Float</td>
        <td>Assigned to site PM (e.g. SAR 5,000 float). Each expenditure requires digital voucher entry + receipt photo upload. Replenished only upon Accounts review.</td>
        <td>Prevents untracked site spending; real-time running balance visible to CEO.</td>
      </tr>
      <tr>
        <td>2. Materials &amp; Purchases</td>
        <td>Emergency tools, consumable cables, hardware brackets, fittings, and local vendor supplies logged with tax invoice copies.</td>
        <td>Distinguishes budgeted project procurement from unplanned site purchases.</td>
      </tr>
      <tr>
        <td>3. Site Allowances &amp; Per-Diems</td>
        <td>Technician food allowances, out-of-town per diems, lodging, site transportation, and emergency overtime labor payouts.</td>
        <td>Enforces approved company allowance caps per person per day.</td>
      </tr>
      <tr>
        <td>4. Net Operational Cash Flow</td>
        <td>Compares Cash Inflows (client collections) against Cash Outflows (petty cash + vendor purchases + labor allowances) per project.</td>
        <td>Ensures every active project remains cash-flow positive during execution.</td>
      </tr>
    </tbody>
  </table>

  <h3>Progress Measurement Framework by Project Type</h3>
  <table>
    <thead>
      <tr>
        <th style="width:32%">Project Track</th>
        <th style="width:36%">Primary Physical Metric</th>
        <th>Financial &amp; Billing Milestone</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>Track 1: Seven Entertainment</td>
        <td>Linear meters of cabling pulled (m) vs scheduled layout; cinema containment &amp; terminations.</td>
        <td>Certified interim inspection certificate (IPC) submitted per completed hall / zone.</td>
      </tr>
      <tr>
        <td>Track 2: Salam CCS</td>
        <td>Hardware delivery &rarr; CCS network connectivity &rarr; System integration &rarr; Client acceptance.</td>
        <td>20% advance &rarr; 40% delivery &rarr; 30% testing &amp; integration &rarr; 10% final PAC.</td>
      </tr>
      <tr>
        <td>Track 3: Dune Corridor Project (DCP)</td>
        <td>DCP units delivered, calibrated, audio-visual testing completed, and handed over.</td>
        <td>Invoice certified upon client acceptance sheet sign-off per cinema hall.</td>
      </tr>
      <tr style="background:#FAF8F2;">
        <td style="color:var(--gold-dark); font-weight:700;">Track 4+: New Projects</td>
        <td><strong>Configurable Metrics Engine:</strong> Linear meters, equipment units, civil stages, or % completion.</td>
        <td>Custom milestone billing schedule mapped directly from client PO terms.</td>
      </tr>
    </tbody>
  </table>

  <div class="exec-box">
    <p><strong>Dual Reconciliation (Field &amp; Finance):</strong> Project Managers submit physical progress and site expense vouchers from mobile devices. The Accounts Department validates bills and reconciles payments, ensuring the CEO sees 100% verified data at all times.</p>
  </div>

  <div class="running-footer">
    <div class="rf-tagline">Where Vision Becomes a Landmark</div>
    <div class="rf-details">
      <div class="rf-item"><b>T:</b> +966 536 700 701 / +966 506 586 534</div>
      <div class="rf-item"><b>E:</b> info@mvsksa.com &bull; www.mvsksa.com</div>
      <div class="rf-item"><b>A:</b> Al Rashid Tower, King Saud Rd, Riyadh, KSA</div>
    </div>
  </div>
</div>

<!-- ==================== PAGE 6: STRATEGIC BENEFITS, ACCESS MATRIX & ROADMAP ==================== -->
<div class="page">
  <div class="running-header">
    <div class="rh-left">
      <img src="{$logoSrc}" alt="MVS Logo" class="rh-logo">
      <div>
        <div class="rh-en-title">Modern Vision Solutions Ltd</div>
        <div class="rh-en-cr">CR: 7036282569</div>
      </div>
    </div>
    <div class="rh-right">
      <div class="rh-ar-title">شركة رؤية الحلول العصرية المحدودة</div>
      <div class="rh-ar-cr">س.ت : ٧٠٣٦٢٨٢٥٦٩</div>
    </div>
  </div>

  <h2><span class="num">6</span>Strategic Benefits &amp; Role Governance</h2>
  
  <div class="benefits-grid">
    <div class="benefit-card">
      <div class="b-num">1</div>
      <div>
        <b>Total Commercial Visibility</b>
        <span>Live tracking of all submitted quotations, expected closing dates, and win pipeline.</span>
      </div>
    </div>
    <div class="benefit-card">
      <div class="b-num">2</div>
      <div>
        <b>Accelerated Cash Collections</b>
        <span>Instant alert for aged receivables (30/60/90+ days), preventing client payment delays.</span>
      </div>
    </div>
    <div class="benefit-card">
      <div class="b-num">3</div>
      <div>
        <b>Rigid Petty Cash Control</b>
        <span>Every site Riyal accounted for with digital vouchers, receipts, and audit verification.</span>
      </div>
    </div>
    <div class="benefit-card">
      <div class="b-num">4</div>
      <div>
        <b>Role-Isolated Confidentiality</b>
        <span>PMs access only their project; executive financial margins remain confidential.</span>
      </div>
    </div>
  </div>

  <h2><span class="num">7</span>Role-Based Access Matrix</h2>
  <table>
    <thead>
      <tr>
        <th style="width:24%">User Role</th>
        <th style="width:36%">Accessible Scope</th>
        <th>Permitted System Actions</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>CEO / Executive Board</td>
        <td>Entire Company Portfolio (All Projects &amp; Quotations)</td>
        <td>Full visibility, quotation approval, budget setting, cashflow audit, all reports</td>
      </tr>
      <tr>
        <td>Sales &amp; Estimation</td>
        <td>Sales Pipeline &amp; Quotation Register</td>
        <td>Log new quotations, update client closing status, record win/loss rationale</td>
      </tr>
      <tr>
        <td>Project Managers (PM)</td>
        <td><strong>Assigned Project ONLY</strong></td>
        <td>Submit milestone progress %, log petty cash vouchers, upload receipts, report risks</td>
      </tr>
      <tr>
        <td>Accounts Department</td>
        <td>Financial Invoicing &amp; Cash Flow Hub</td>
        <td>Record client payments, certify tax invoices, approve petty cash replenishments</td>
      </tr>
    </tbody>
  </table>

  <h2><span class="num">8</span>Implementation Roadmap</h2>
  <div class="tl-container">
    <div class="tl-phase">
      <div class="tl-badge">Phase 0</div>
      <div class="tl-content">
        <b>Data Audit &amp; CEO Format Setup:</b> Collate POs, active contracts, and adopt the CEO's designated Sales Pipeline format. Establish project baselines.
      </div>
      <div class="tl-dur">1 Week</div>
    </div>
    <div class="tl-phase core">
      <div class="tl-badge">Phase 1 (MVP)</div>
      <div class="tl-content">
        <b>Core Platform &amp; 3 Pillars:</b> Sales Pipeline tracker, PM milestone entry, Executive Financial Summary, and Petty Cash/Purchases logging engine.
      </div>
      <div class="tl-dur">3&ndash;4 Weeks</div>
    </div>
    <div class="tl-phase">
      <div class="tl-badge">Phase 2</div>
      <div class="tl-content">
        <b>Automated Reporting &amp; Forecasts:</b> Automated weekly PDF executive brief for CEO, aged receivables analysis, and cash flow projections.
      </div>
      <div class="tl-dur">1&ndash;2 Weeks</div>
    </div>
  </div>

  <div class="tl-banner">
    Target Delivery for Operational Platform (Phase 0 + 1): <span>4 Weeks from Sanction</span>
  </div>

  <div class="running-footer">
    <div class="rf-tagline">Where Vision Becomes a Landmark</div>
    <div class="rf-details">
      <div class="rf-item"><b>T:</b> +966 536 700 701 / +966 506 586 534</div>
      <div class="rf-item"><b>E:</b> info@mvsksa.com &bull; www.mvsksa.com</div>
      <div class="rf-item"><b>A:</b> Al Rashid Tower, King Saud Rd, Riyadh, KSA</div>
    </div>
  </div>
</div>

<!-- ==================== PAGE 7: RESOURCE IMPACT, KEY DECISIONS & SIGN-OFF ==================== -->
<div class="page">
  <div class="running-header">
    <div class="rh-left">
      <img src="{$logoSrc}" alt="MVS Logo" class="rh-logo">
      <div>
        <div class="rh-en-title">Modern Vision Solutions Ltd</div>
        <div class="rh-en-cr">CR: 7036282569</div>
      </div>
    </div>
    <div class="rh-right">
      <div class="rh-ar-title">شركة رؤية الحلول العصرية المحدودة</div>
      <div class="rh-ar-cr">س.ت : ٧٠٣٦٢٨٢٥٦٩</div>
    </div>
  </div>

  <h2><span class="num">9</span>Resource Impact &amp; Zero Software Cost</h2>
  <table>
    <thead>
      <tr>
        <th style="width:26%">Component</th>
        <th>Specification &amp; Corporate Advantage</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>Software Development</td>
        <td><strong>100% In-House by MVS IT:</strong> Zero software licensing, vendor fees, or consultant retainers.</td>
      </tr>
      <tr>
        <td>Technology Stack</td>
        <td>Laravel (PHP 8.2) + MySQL &bull; Robust, enterprise-grade, industry-standard data protection.</td>
      </tr>
      <tr>
        <td>Hosting &amp; Deployment</td>
        <td>Hosted securely on internal MVS office server or private VPS cloud with automated daily backup.</td>
      </tr>
      <tr>
        <td>PM Time Commitment</td>
        <td>Requires only 10&ndash;15 minutes weekly per PM via mobile form; immediate time-saving vs manual Excel.</td>
      </tr>
    </tbody>
  </table>

  <h2><span class="num">10</span>Key Decisions &amp; Inputs Required from CEO</h2>
  <ol>
    <li><strong>Sales Pipeline Template:</strong> Share the preferred quotation &amp; closing format to finalize column structures.</li>
    <li><strong>Formal Sanction:</strong> Approval to initiate Phase 0 data baseline collation and Phase 1 platform setup.</li>
    <li><strong>PM Nominations:</strong> Confirm appointed project owners for Seven Entertainment, Salam CCS, Dune Corridor Project (DCP), and designate managers for incoming Track 4+ projects.</li>
    <li><strong>Petty Cash Limits:</strong> Establish standard site petty cash float amounts (e.g. SAR 5,000 / project).</li>
    <li><strong>Accounts Collaboration:</strong> Nominate an Accounts representative to support initial PO and invoice data entry.</li>
  </ol>

  <h2><span class="num">11</span>Next Steps</h2>
  <p>Upon executive green light, IT will initiate Phase 0 immediately, configure the database with the CEO's pipeline format, and deliver the working prototype for CEO review in 4 weeks.</p>

  <div class="sign-area">
    <div style="font-size: 8pt; color: var(--slate); max-width: 105mm;">
      <p style="margin: 0;"><em>Respectfully submitted for executive review and approval. Modern Vision Solutions Ltd &mdash; Internal Technology Initiative.</em></p>
    </div>
    <div class="sign-box">
      <div class="sign-line"></div>
      <div class="sign-name">Danish Akbar</div>
      <div class="sign-role">IT Support &bull; Modern Vision Solutions Ltd</div>
    </div>
  </div>

  <div class="running-footer">
    <div class="rf-tagline">Where Vision Becomes a Landmark</div>
    <div class="rf-details">
      <div class="rf-item"><b>T:</b> +966 536 700 701 / +966 506 586 534</div>
      <div class="rf-item"><b>E:</b> info@mvsksa.com &bull; www.mvsksa.com</div>
      <div class="rf-item"><b>A:</b> Al Rashid Tower, King Saud Rd, Riyadh, KSA</div>
    </div>
  </div>
</div>

</body>
</html>
HTML;

file_put_contents(__DIR__ . '/MVS_Project_Pulse_Proposal.html', $html);
echo "HTML written successfully (" . strlen($html) . " bytes)\n";



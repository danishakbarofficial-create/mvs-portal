<?php
$b64 = trim(file_get_contents(__DIR__ . '/logo_base64.txt'));
$logoSrc = 'data:image/png;base64,' . $b64;

$html = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Executive Proposal – MVS ICT & Telecom</title>
<meta name="description" content="MVS ICT & Telecom - Centralized Sales Pipeline, Project Milestones, Executive Financials & Cash Flow Command Center">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Tajawal:wght@500;700&display=swap" rel="stylesheet">
<style>
  @page {
    size: A4;
    margin: 11mm 14mm 11mm 14mm;
  }
  @page cover {
    margin: 0;
  }

  :root {
    --mvs-gold: #BA9631;
    --mvs-gold-light: #D4B04C;
    --mvs-gold-dark: #8F7020;
    --mvs-gold-subtle: #FAF8F3;
    --mvs-gold-border: #E8E0D0;
    
    --corp-dark: #0F172A;
    --corp-navy: #1E293B;
    --corp-slate: #334155;
    --corp-muted: #64748B;
    --corp-border: #E2E8F0;
    --corp-bg-subtle: #F8FAFC;
    
    --white: #FFFFFF;
    
    --badge-won-bg: #ECFDF5;
    --badge-won-txt: #065F46;
    --badge-won-brd: #A7F3D0;
    
    --badge-neg-bg: #FFFBEB;
    --badge-neg-txt: #92400E;
    --badge-neg-brd: #FDE68A;
    
    --badge-quoted-bg: #F1F5F9;
    --badge-quoted-txt: #334155;
    --badge-quoted-brd: #CBD5E1;

    --badge-rfq-bg: #EFF6FF;
    --badge-rfq-txt: #1E40AF;
    --badge-rfq-brd: #BFDBFE;
  }

  * { box-sizing: border-box; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
  
  body {
    font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    color: var(--corp-slate);
    font-size: 8.4pt;
    line-height: 1.44;
    margin: 0;
    background: #ffffff;
    font-feature-settings: "cv02", "cv03", "cv04", "cv11";
  }

  /* ================= PAGE 1: COVER ================= */
  .cover {
    page: cover;
    height: 297mm;
    width: 210mm;
    position: relative;
    overflow: hidden;
    background: radial-gradient(circle at 82% 16%, #1D1A14 0%, #0F141F 48%, #090C12 100%);
    color: var(--white);
    page-break-after: always;
  }
  .cover-glow {
    position: absolute;
    width: 140mm;
    height: 140mm;
    border-radius: 50%;
    right: -40mm;
    top: -30mm;
    background: radial-gradient(circle, rgba(186,150,49,0.24), rgba(186,150,49,0) 70%);
  }
  .cover-glow-2 {
    position: absolute;
    width: 150mm;
    height: 150mm;
    border-radius: 50%;
    left: -50mm;
    bottom: -40mm;
    background: radial-gradient(circle, rgba(186,150,49,0.15), rgba(186,150,49,0) 70%);
  }
  .cover-grid {
    position: absolute;
    inset: 0;
    opacity: 0.035;
    background-image: linear-gradient(#fff 1px, transparent 1px), linear-gradient(90deg, #fff 1px, transparent 1px);
    background-size: 10mm 10mm;
  }
  .cover-inner {
    position: relative;
    padding: 24mm 20mm 20mm 20mm;
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
    height: 60px;
    width: auto;
    object-fit: contain;
    filter: drop-shadow(0 4px 12px rgba(186,150,49,0.25));
  }
  .cover-brand-text {
    border-left: 1.5px solid rgba(186,150,49,0.45);
    padding-left: 14px;
  }
  .cover-brand-en {
    font-size: 11pt;
    font-weight: 700;
    letter-spacing: 2px;
    color: #F6ECCB;
    text-transform: uppercase;
  }
  .cover-brand-ar {
    font-family: 'Tajawal', sans-serif;
    font-size: 10pt;
    color: #C8CCD2;
    margin-top: 2px;
  }
  .cover-badge {
    margin-top: 36mm;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 7.8pt;
    font-weight: 600;
    letter-spacing: 2.2px;
    text-transform: uppercase;
    color: var(--mvs-gold-light);
    border: 1px solid rgba(186,150,49,0.4);
    background: rgba(186,150,49,0.08);
    padding: 5px 14px;
    border-radius: 20px;
    width: max-content;
  }
  .cover-title {
    font-size: 38pt;
    line-height: 1.05;
    margin: 6mm 0 3mm 0;
    font-weight: 800;
    letter-spacing: -0.5px;
  }
  .cover-title span {
    background: linear-gradient(110deg, #F6ECCB 0%, #BA9631 52%, #94721E 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
  }
  .cover-sub {
    font-size: 12.5pt;
    color: #D3D7DE;
    font-weight: 400;
    line-height: 1.4;
    max-width: 155mm;
  }
  .cover-rule {
    width: 45mm;
    height: 2.5px;
    border-radius: 2px;
    background: linear-gradient(90deg, var(--mvs-gold-light), var(--mvs-gold-dark));
    margin: 6mm 0 7mm 0;
  }
  .cover-projects {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    max-width: 165mm;
  }
  .cover-pill {
    font-size: 8pt;
    font-weight: 500;
    padding: 5px 12px;
    border-radius: 6px;
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(186,150,49,0.32);
    color: #E2E8F0;
  }
  .cover-pill b {
    color: var(--mvs-gold-light);
    margin-right: 4px;
  }
  .cover-meta {
    margin-top: auto;
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 15px;
    border-top: 1px solid rgba(186,150,49,0.25);
    padding-top: 6mm;
  }
  .cover-meta-k {
    font-size: 7pt;
    text-transform: uppercase;
    letter-spacing: 2px;
    color: #9499A3;
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
    color: var(--mvs-gold-light);
    font-size: 8pt;
  }
  .cover-confidential {
    position: absolute;
    bottom: 8mm;
    right: 20mm;
    font-size: 7pt;
    letter-spacing: 2px;
    color: #646973;
    text-transform: uppercase;
  }

  /* ================= RUNNING HEADERS & FOOTERS ================= */
  .running-header {
    border-bottom: 1.5px solid var(--mvs-gold);
    padding-bottom: 2.5mm;
    margin-bottom: 3.5mm;
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
    height: 32px;
    width: auto;
    object-fit: contain;
  }
  .rh-en-title {
    font-size: 8.8pt;
    font-weight: 800;
    color: var(--corp-dark);
    line-height: 1.2;
    letter-spacing: 0.2px;
  }
  .rh-en-cr {
    font-size: 7pt;
    font-weight: 600;
    color: var(--corp-muted);
  }
  .rh-right {
    text-align: right;
    font-family: 'Tajawal', sans-serif;
  }
  .rh-ar-title {
    font-size: 8.8pt;
    font-weight: 700;
    color: var(--corp-dark);
    line-height: 1.2;
  }
  .rh-ar-cr {
    font-size: 7.2pt;
    color: var(--corp-muted);
    direction: rtl;
  }

  .running-footer {
    border-top: 1px solid var(--corp-border);
    padding-top: 2mm;
    margin-top: auto;
    font-size: 7pt;
    color: var(--corp-muted);
  }
  .rf-tagline {
    text-align: center;
    font-size: 7.2pt;
    font-weight: 600;
    color: var(--mvs-gold-dark);
    letter-spacing: 1.5px;
    margin-bottom: 1.5mm;
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
    color: var(--corp-dark);
  }

  /* ================= CONTENT STYLES ================= */
  .page {
    page-break-after: always;
    height: 275mm;
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
    font-size: 10.8pt;
    color: var(--corp-dark);
    margin: 0 0 2mm 0;
    display: flex;
    align-items: center;
    gap: 7px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.3px;
  }
  h2 .num {
    display: inline-flex;
    width: 20px;
    height: 20px;
    border-radius: 4px;
    align-items: center;
    justify-content: center;
    background: var(--corp-dark);
    color: var(--mvs-gold-light);
    font-size: 8pt;
    font-weight: 800;
    border: 1px solid rgba(186,150,49,0.35);
  }
  h3 {
    font-size: 8.6pt;
    color: var(--corp-dark);
    margin: 2.5mm 0 1.2mm 0;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 6px;
  }
  h3::before {
    content: "";
    display: inline-block;
    width: 3.5px;
    height: 10px;
    background: var(--mvs-gold);
    border-radius: 1px;
  }
  p {
    margin: 0 0 2mm 0;
    color: var(--corp-slate);
  }
  p b, p strong {
    color: var(--corp-dark);
  }

  /* 3-PILLAR TOP STRIP (ELEGANT CORPORATE PALETTE) */
  .pillar-strip {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 2.5mm;
    margin: 1mm 0 2.5mm 0;
  }
  .p-strip-box {
    border: 1px solid var(--corp-border);
    border-top: 2.5px solid var(--corp-dark);
    border-radius: 6px;
    padding: 2.2mm 2.8mm;
    background: #FFFFFF;
    display: flex;
    align-items: center;
    gap: 2.5mm;
    box-shadow: 0 1px 2px rgba(15,23,42,0.02);
  }
  .p-strip-box.p-active {
    border-top-color: var(--mvs-gold);
  }
  .p-strip-icon {
    width: 24px;
    height: 24px;
    border-radius: 5px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 9pt;
    background: var(--corp-bg-subtle);
    color: var(--corp-dark);
    border: 1px solid var(--corp-border);
  }
  .p-strip-title {
    font-size: 7.8pt;
    font-weight: 700;
    color: var(--corp-dark);
    line-height: 1.15;
  }
  .p-strip-sub {
    font-size: 6.8pt;
    color: var(--corp-muted);
  }

  /* TABLES: CRISP INSTITUTIONAL LAYOUT */
  table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    margin: 1.5mm 0 2mm 0;
    font-size: 7.6pt;
    border: 1px solid var(--corp-border);
    border-radius: 5px;
    overflow: hidden;
  }
  th {
    background: var(--corp-navy);
    color: #FFFFFF;
    text-align: left;
    font-weight: 700;
    padding: 5px 7px;
    font-size: 7pt;
    letter-spacing: 0.4px;
    text-transform: uppercase;
    border-bottom: 1.5px solid var(--mvs-gold);
  }
  td {
    padding: 4.2px 7px;
    border-top: 1px solid var(--corp-border);
    vertical-align: middle;
    color: var(--corp-slate);
  }
  tr:nth-child(even) td {
    background: var(--corp-bg-subtle);
  }
  td:first-child {
    font-weight: 600;
    color: var(--corp-dark);
  }
  .num-cell {
    font-variant-numeric: tabular-nums;
  }

  /* BADGES (SUBTLE, RESTYLED CORPORATE PILLS) */
  .badge {
    display: inline-block;
    padding: 1.5px 6.5px;
    border-radius: 4px;
    font-size: 6.5pt;
    font-weight: 700;
    letter-spacing: 0.2px;
    text-transform: uppercase;
  }
  .badge-won { background: var(--badge-won-bg); color: var(--badge-won-txt); border: 1px solid var(--badge-won-brd); }
  .badge-neg { background: var(--badge-neg-bg); color: var(--badge-neg-txt); border: 1px solid var(--badge-neg-brd); }
  .badge-quoted { background: var(--badge-quoted-bg); color: var(--badge-quoted-txt); border: 1px solid var(--badge-quoted-brd); }
  .badge-rfq { background: var(--badge-rfq-bg); color: var(--badge-rfq-txt); border: 1px solid var(--badge-rfq-brd); }
  .term-tag { display: block; font-weight: 700; color: var(--corp-dark); font-size: 7.2pt; line-height: 1.2; }
  .term-sub { display: block; font-size: 6.4pt; color: var(--corp-muted); line-height: 1.15; font-weight: 500; }

  /* CALLOUT / EXECUTIVE BOX */
  .exec-box {
    background: var(--mvs-gold-subtle);
    border-left: 3px solid var(--mvs-gold);
    border-radius: 6px;
    padding: 2.2mm 3.5mm;
    margin: 1.5mm 0;
    border-top: 1px solid var(--mvs-gold-border);
    border-right: 1px solid var(--mvs-gold-border);
    border-bottom: 1px solid var(--mvs-gold-border);
  }
  .exec-box p {
    color: var(--corp-dark);
    margin: 0;
    font-size: 7.7pt;
  }

  /* 4-GRID CASHFLOW TILES (PROFESSIONAL SLATE & GOLD) */
  .cf-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 2mm;
    margin: 1.5mm 0 2mm 0;
  }
  .cf-tile {
    border: 1px solid var(--corp-border);
    border-radius: 5px;
    padding: 2.2mm;
    background: #FFFFFF;
    text-align: left;
    border-top: 2px solid var(--corp-dark);
  }
  .cf-t {
    font-weight: 700;
    color: var(--corp-dark);
    font-size: 7.6pt;
    display: flex;
    align-items: center;
    gap: 4px;
  }
  .cf-d {
    font-size: 6.8pt;
    color: var(--corp-muted);
    margin-top: 1.5px;
    line-height: 1.25;
  }

  /* ================= SYSTEM WORKFLOW INFOGRAPHIC ================= */
  .flow-container {
    margin: 1.5mm 0 2.5mm 0;
    border: 1px solid var(--corp-border);
    border-radius: 7px;
    padding: 2.5mm 3mm 2.5mm 3mm;
    background: #F8FAFC;
  }
  .flow-pipeline {
    display: grid;
    grid-template-columns: 1fr 14px 1fr 14px 1fr 14px 1.05fr 14px 1.05fr;
    align-items: center;
    gap: 1mm;
  }
  .flow-box {
    background: #FFFFFF;
    border: 1px solid var(--corp-border);
    border-top: 2.5px solid var(--corp-navy);
    border-radius: 5px;
    padding: 2mm 1.5mm;
    text-align: center;
    box-shadow: 0 1px 2px rgba(15,23,42,0.03);
  }
  .flow-box.fb-po {
    border-top-color: var(--mvs-gold);
    background: #FFFDF9;
    border-color: #EAD9B4;
  }
  .flow-box.fb-proj {
    border-top-color: #059669;
    background: #F0FDF4;
    border-color: #A7F3D0;
  }
  .fb-num {
    display: inline-flex;
    width: 15px;
    height: 15px;
    border-radius: 50%;
    background: var(--corp-dark);
    color: var(--mvs-gold-light);
    font-size: 6.8pt;
    font-weight: 800;
    align-items: center;
    justify-content: center;
    margin-bottom: 1.5px;
  }
  .fb-po .fb-num {
    background: var(--mvs-gold);
    color: #FFFFFF;
  }
  .fb-proj .fb-num {
    background: #059669;
    color: #FFFFFF;
  }
  .fb-title {
    font-size: 7.2pt;
    font-weight: 800;
    color: var(--corp-dark);
    line-height: 1.15;
  }
  .fb-sub {
    font-size: 6pt;
    color: var(--corp-muted);
    margin-top: 1px;
    line-height: 1.15;
  }
  .fb-by {
    display: inline-block;
    margin-top: 2px;
    font-size: 5.6pt;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.2px;
    color: var(--mvs-gold-dark);
    background: rgba(186,150,49,0.08);
    padding: 0.8px 4px;
    border-radius: 3px;
  }
  .fb-proj .fb-by {
    color: #065F46;
    background: rgba(5,150,105,0.1);
  }
  .flow-arrow {
    text-align: center;
    color: var(--mvs-gold);
    font-size: 11pt;
    font-weight: 900;
    opacity: 0.8;
  }

  /* CONNECTOR BAR */
  .flow-sync-bar {
    display: flex;
    align-items: center;
    gap: 8px;
    margin: 2mm 0 1.6mm 0;
  }
  .fs-line {
    flex: 1;
    height: 1px;
    background: linear-gradient(90deg, transparent, var(--mvs-gold), transparent);
  }
  .fs-label {
    font-size: 6.2pt;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1.2px;
    color: var(--mvs-gold-dark);
    display: flex;
    align-items: center;
    gap: 4px;
  }

  /* CEO MASTER BANNER */
  .flow-ceo-banner {
    background: radial-gradient(circle at 90% 15%, #1E293B 0%, #0F172A 100%);
    border: 1.5px solid var(--mvs-gold);
    border-radius: 6px;
    padding: 2.2mm 3mm;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 2.5mm;
    color: #FFFFFF;
    box-shadow: 0 2px 6px rgba(15,23,42,0.1);
  }
  .fcb-left {
    flex: 0 0 38%;
  }
  .fcb-badge {
    display: inline-block;
    font-size: 5.8pt;
    font-weight: 800;
    letter-spacing: 1px;
    text-transform: uppercase;
    color: var(--mvs-gold-light);
    border: 1px solid rgba(186,150,49,0.4);
    background: rgba(186,150,49,0.12);
    padding: 1px 5px;
    border-radius: 10px;
    margin-bottom: 1.5px;
  }
  .fcb-title {
    font-size: 8.4pt;
    font-weight: 800;
    color: #FFFFFF;
    line-height: 1.15;
  }
  .fcb-sub {
    font-size: 6.2pt;
    color: #CBD5E1;
    margin-top: 1px;
  }
  .fcb-pillars {
    flex: 1;
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5mm;
  }
  .fcb-p {
    background: rgba(255,255,255,0.06);
    border: 1px solid rgba(255,255,255,0.1);
    border-left: 2px solid var(--mvs-gold);
    border-radius: 4px;
    padding: 1.5mm 1.8mm;
    text-align: left;
  }
  .fcb-p b {
    display: block;
    font-size: 6.6pt;
    font-weight: 700;
    color: var(--mvs-gold-light);
    line-height: 1.15;
  }
  .fcb-p span {
    display: block;
    font-size: 5.6pt;
    color: #E2E8F0;
    margin-top: 1px;
    line-height: 1.15;
  }

  /* 2x2 VALUE CARDS ON PAGE 3 */
  .grid-2x2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2.2mm;
    margin: 1.5mm 0 2.5mm 0;
  }
  .val-card {
    border: 1px solid var(--corp-border);
    border-radius: 6px;
    padding: 2.4mm 3mm;
    background: #FFFFFF;
    display: flex;
    gap: 2.5mm;
    align-items: flex-start;
  }
  .val-num {
    flex: 0 0 18px;
    height: 18px;
    border-radius: 4px;
    background: var(--corp-dark);
    color: var(--mvs-gold-light);
    font-size: 7.5pt;
    font-weight: 800;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-top: 1px;
    border: 1px solid rgba(186,150,49,0.3);
  }
  .val-card b {
    color: var(--corp-dark);
    font-size: 7.8pt;
    display: block;
  }
  .val-card span {
    font-size: 7pt;
    color: var(--corp-muted);
    line-height: 1.3;
    display: block;
  }

  /* TIMELINE ROW */
  .tl-row {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 2mm;
    margin: 1.5mm 0 2.5mm 0;
  }
  .tl-step {
    border: 1px solid var(--corp-border);
    border-radius: 5px;
    padding: 2mm 2.5mm;
    background: var(--corp-bg-subtle);
  }
  .tl-step.active {
    background: #FFFFFF;
    border-top: 2.5px solid var(--mvs-gold);
    box-shadow: 0 1px 3px rgba(15,23,42,0.03);
  }
  .tl-step b {
    color: var(--corp-dark);
    font-size: 7.6pt;
    display: block;
  }
  .tl-step span {
    color: var(--corp-muted);
    font-size: 6.8pt;
    display: block;
    margin-top: 1px;
  }

  /* SIGNATURE SECTION */
  .sign-area {
    margin-top: auto;
    padding-top: 2.5mm;
    border-top: 1px solid var(--corp-border);
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
  }
  .sign-box {
    text-align: right;
  }
  .sign-line {
    width: 45mm;
    border-top: 1.5px solid var(--corp-dark);
    margin-bottom: 1.5mm;
    margin-left: auto;
  }
  .sign-name {
    font-weight: 800;
    color: var(--corp-dark);
    font-size: 9.5pt;
  }
  .sign-role {
    color: var(--mvs-gold-dark);
    font-weight: 600;
    font-size: 7.8pt;
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

    <div class="cover-badge">Executive Brief &bull; C-Suite Edition</div>
    
    <h1 class="cover-title">MVS Project <span>Pulse</span></h1>
    <div class="cover-sub">Project Details, Commercial Sales Pipeline &amp; Executive Summary Command Center</div>
    
    <div class="cover-rule"></div>
    
    <div class="cover-projects">
      <div class="cover-pill"><b>Track 1:</b> Seven Entertainment</div>
      <div class="cover-pill"><b>Track 2:</b> Salam CCS</div>
      <div class="cover-pill"><b>Track 3:</b> Dune Corridor Project (DCP)</div>
      <div class="cover-pill" style="border: 1px dashed rgba(212,176,76,0.65); background: rgba(186,150,49,0.12); color: #F6ECCB;"><b>Track 4+:</b> Future Projects (Instant Onboarding &bull; Infinite Scale)</div>
      <div class="cover-pill"><b>Sales Pipeline:</b> RFQ, Quotations, Follow-up, PO Award &bull; Payment Terms</div>
      <div class="cover-pill"><b>Project Details:</b> Progress %, Project Value, Scope</div>
      <div class="cover-pill"><b>Executive Summary:</b> Project Value, Invoiced, Received, Remaining</div>
      <div class="cover-pill"><b>Cash Flow:</b> Petty Cash, Purchases, Allowances</div>
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
        <div class="cover-meta-k">Document Scope</div>
        <div class="cover-meta-v">Executive Summary<small>3-Page Fast Track &bull; Version 2.0</small></div>
      </div>
    </div>

  </div>
  <div class="cover-confidential">MVS Confidential &bull; Internal Executive Document</div>
</section>

<!-- ==================== PAGE 2: THE 3-PILLAR EXECUTIVE COMMAND DASHBOARD ==================== -->
<div class="page">
  <h2><span class="num">1</span>Executive Command Dashboard &bull; Live Operations Screen</h2>
  <p style="margin-bottom:1.5mm;">A centralized command screen designed for executive leadership &mdash; connecting <strong>Pre-Award Sales Deals</strong>, <strong>Active Site Execution</strong>, and <strong>Real-Time Cash Flow</strong> without relying on manual spreadsheets:</p>

  <div class="pillar-strip">
    <div class="p-strip-box p-active">
      <div>
        <div class="p-strip-title">Pillar 1: Sales Pipeline</div>
        <div class="p-strip-sub">Incoming Deals &bull; Quotes, Bids &amp; Payment Terms</div>
      </div>
    </div>
    <div class="p-strip-box p-active">
      <div>
        <div class="p-strip-title">Pillar 2: Active Projects</div>
        <div class="p-strip-sub">Site Delivery &bull; Live Progress %, Scope &amp; Milestones</div>
      </div>
    </div>
    <div class="p-strip-box p-active">
      <div>
        <div class="p-strip-title">Pillar 3: Cash Flow</div>
        <div class="p-strip-sub">Financial Health &bull; Invoiced, Received &amp; Overdue Alerts</div>
      </div>
    </div>
  </div>

  <h3>Pillar 1: Sales Pipeline (Pre-Award Deals &bull; RFQ &rarr; Quotation &rarr; Follow-up &rarr; PO Award)</h3>
  <table>
    <thead>
      <tr>
        <th style="width:17%">Quotation &amp; RFQ Ref</th>
        <th style="width:20%">Client Opportunity</th>
        <th style="width:15%">Value (SAR)</th>
        <th style="width:19%">Payment Terms</th>
        <th style="width:16%">Commercial Stage</th>
        <th style="width:13%">Closing &bull; Prob.</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>
          <div style="font-weight:700; color:var(--corp-dark);">MVS-QT-2026-088</div>
          <div style="font-size:6.6pt; color:var(--corp-muted); font-weight:500;">RFQ: RFQ-2026-104</div>
        </td>
        <td>
          <div style="font-weight:700; color:var(--corp-dark);">Client Name 1</div>
          <span class="badge" style="background:#EEF2FF; color:#3730A3; border:1px solid #C7D2FE; font-size:5.8pt; margin-top:2px;">Services / Contracting</span>
        </td>
        <td class="num-cell" style="font-weight:700; color:var(--corp-dark);">SAR 1,850,000</td>
        <td>
          <span class="term-tag">50% Adv / 50% Delivery</span>
          <span class="term-sub">Advance cashflow secured</span>
        </td>
        <td><span class="badge badge-neg">Follow-up / Neg.</span></td>
        <td>End Oct <small style="font-weight:700; color:var(--corp-dark);">(85%)</small></td>
      </tr>
      <tr>
        <td>
          <div style="font-weight:700; color:var(--corp-dark);">MVS-QT-2026-092</div>
          <div style="font-size:6.6pt; color:var(--corp-muted); font-weight:500;">RFQ: RFQ-2026-112</div>
        </td>
        <td>
          <div style="font-weight:700; color:var(--corp-dark);">Client Name 2</div>
          <span class="badge" style="background:#F0FDF4; color:#166534; border:1px solid #BBF7D0; font-size:5.8pt; margin-top:2px;">Product Supply (Box Moving)</span>
        </td>
        <td class="num-cell" style="font-weight:700; color:var(--corp-dark);">SAR 740,000</td>
        <td>
          <span class="term-tag">30 Days Credit</span>
          <span class="term-sub">Aged receivable alert set</span>
        </td>
        <td><span class="badge badge-quoted">Quote Submitted</span></td>
        <td>Mid Nov <small style="font-weight:700; color:var(--corp-dark);">(75%)</small></td>
      </tr>
      <tr>
        <td>
          <div style="font-weight:700; color:var(--corp-dark);">MVS-QT-2026-095</div>
          <div style="font-size:6.6pt; color:#065F46; font-weight:700;">PO: PO-2026-8841</div>
        </td>
        <td>
          <div style="font-weight:700; color:var(--corp-dark);">Client Name 3</div>
          <span class="badge" style="background:#EEF2FF; color:#3730A3; border:1px solid #C7D2FE; font-size:5.8pt; margin-top:2px;">Turnkey Services</span>
        </td>
        <td class="num-cell" style="font-weight:700; color:var(--corp-dark);">SAR 960,000</td>
        <td>
          <span class="term-tag" style="color:#065F46;">100% Full Advance</span>
          <span class="term-sub">Immediate mobilization</span>
        </td>
        <td><span class="badge badge-won">PO Awarded (Won)</span></td>
        <td>Awarded <small style="font-weight:700; color:#065F46;">(100%)</small></td>
      </tr>
    </tbody>
  </table>

  <div class="exec-box">
    <p><strong>Dual-Stream Classification (Services vs Box-Moving):</strong> Categorizes deals into <strong>Services / Projects</strong> (requiring PM assignment &amp; site progress tracking) and <strong>Product Supply / Box-Moving</strong> (direct delivery note &amp; fast invoicing). Agreed <strong>Payment Terms</strong> (Advance %, Credit days) automatically trigger upfront invoice alerts upon PO award.</p>
  </div>

  <h3>Pillars 2 &amp; 3: Active Project Execution &amp; Invoicing (Value, Received &amp; Aged Overdue)</h3>
  <table>
    <thead>
      <tr>
        <th style="width:23%">Project Details (Track &amp; Scope)</th>
        <th style="width:12%">Progress %</th>
        <th style="width:15%">Project Value</th>
        <th style="width:14%">Invoiced</th>
        <th style="width:14%">Received</th>
        <th style="width:12%">Remaining</th>
        <th>Overdue</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>Track 1: Seven Entertainment</td>
        <td class="num-cell"><span style="color:#065F46; font-weight:700;">68%</span> <small style="color:var(--corp-muted);">Active</small></td>
        <td class="num-cell" style="font-weight:600; color:var(--corp-dark);">SAR 2,450,000</td>
        <td class="num-cell">SAR 1,470,000 <small>(60%)</small></td>
        <td class="num-cell" style="font-weight:600; color:var(--corp-dark);">SAR 1,120,000</td>
        <td class="num-cell">SAR 980,000</td>
        <td><span style="color:#991B1B; font-weight:600;">SAR 350,000</span> <small style="color:var(--corp-muted);">(45d)</small></td>
      </tr>
      <tr>
        <td>Track 2: Salam CCS</td>
        <td class="num-cell"><span style="color:#065F46; font-weight:700;">75%</span> <small style="color:var(--corp-muted);">Active</small></td>
        <td class="num-cell" style="font-weight:600; color:var(--corp-dark);">SAR 1,100,000</td>
        <td class="num-cell">SAR 770,000 <small>(70%)</small></td>
        <td class="num-cell" style="font-weight:600; color:var(--corp-dark);">SAR 660,000</td>
        <td class="num-cell">SAR 330,000</td>
        <td><span style="color:#991B1B; font-weight:600;">SAR 110,000</span> <small style="color:var(--corp-muted);">(20d)</small></td>
      </tr>
      <tr>
        <td>Track 3: Dune Corridor (DCP)</td>
        <td class="num-cell"><span style="color:#065F46; font-weight:700;">42%</span> <small style="color:var(--corp-muted);">Active</small></td>
        <td class="num-cell" style="font-weight:600; color:var(--corp-dark);">SAR 850,000</td>
        <td class="num-cell">SAR 340,000 <small>(40%)</small></td>
        <td class="num-cell" style="font-weight:600; color:var(--corp-dark);">SAR 340,000</td>
        <td class="num-cell">SAR 510,000</td>
        <td><span style="color:#065F46; font-weight:600;">SAR 0</span> <small style="color:var(--corp-muted);">(Current)</small></td>
      </tr>
      <tr style="background:#FAF8F3; font-style:italic; font-size:7.2pt;">
        <td style="color:var(--mvs-gold-dark); font-weight:700;">Track 4+: New Awarded Projects</td>
        <td class="num-cell" style="color:var(--mvs-gold-dark); font-weight:700;">Auto-sync</td>
        <td colspan="5" style="text-align:center; color:var(--corp-slate); font-weight:600;">
          + Instant Onboarding: Automatically adds Progress %, Project Value, Invoiced &amp; Received upon PO award
        </td>
      </tr>
      <tr style="font-weight:800; background:#F1F5F9; border-top:2px solid var(--corp-dark); border-bottom:2px solid var(--corp-dark);">
        <td style="color:var(--corp-dark); letter-spacing:0.3px;">TOTAL PORTFOLIO</td>
        <td class="num-cell" style="color:#065F46; font-weight:800;">64% Avg</td>
        <td class="num-cell" style="color:var(--corp-dark);">SAR 4,400,000</td>
        <td class="num-cell" style="color:var(--corp-dark);">SAR 2,580,000</td>
        <td class="num-cell" style="color:var(--corp-dark);">SAR 2,120,000</td>
        <td class="num-cell" style="color:var(--corp-slate);">SAR 1,820,000</td>
        <td class="num-cell" style="color:#991B1B;">SAR 460,000</td>
      </tr>
    </tbody>
  </table>

  <h3>Site Cash Flow Governance (Petty Cash Float, Emergency Purchases &amp; Allowances)</h3>
  <div class="cf-grid">
    <div class="cf-tile">
      <div class="cf-t">Petty Cash Float</div>
      <div class="cf-d">PM digital vouchers &amp; receipt photo upload; live running balance verification.</div>
    </div>
    <div class="cf-tile">
      <div class="cf-t">Materials &amp; Purchases</div>
      <div class="cf-d">Local emergency tools, cables &amp; consumables tagged directly to project PO.</div>
    </div>
    <div class="cf-tile">
      <div class="cf-t">Site Allowances</div>
      <div class="cf-d">Technician per-diems, lodging, travel &amp; site labor overtime strictly tracked.</div>
    </div>
    <div class="cf-tile">
      <div class="cf-t">Net Cash Flow</div>
      <div class="cf-d">Cash Inflow (bank receipts) vs Outflow (expenses) ensuring positive project liquidity.</div>
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

<!-- ==================== PAGE 3: IMPLEMENTATION, ZERO COST & CEO DECISIONS ==================== -->
<div class="page">
  <h2><span class="num">2</span>How It Works &bull; Deal-to-Execution Flowchart</h2>
  <p style="margin-bottom:1.5mm;">From client inquiry to contract award and executive oversight &mdash; live automation across all departments:</p>

  <div class="flow-container">
    <!-- ROW 1: 5-STEP LIFECYCLE -->
    <div class="flow-pipeline">
      <div class="flow-box">
        <div class="fb-num">1</div>
        <div class="fb-title">RFQ Received</div>
        <div class="fb-sub">Services / Box-Moving</div>
        <div class="fb-by">Sales Team</div>
      </div>
      <div class="flow-arrow">&rarr;</div>

      <div class="flow-box">
        <div class="fb-num">2</div>
        <div class="fb-title">Quotation Sent</div>
        <div class="fb-sub">Pricing &amp; Scope Defined</div>
        <div class="fb-by">Sales Team</div>
      </div>
      <div class="flow-arrow">&rarr;</div>

      <div class="flow-box">
        <div class="fb-num">3</div>
        <div class="fb-title">Follow-up</div>
        <div class="fb-sub">Negotiations &amp; Revisions</div>
        <div class="fb-by">Sales Team</div>
      </div>
      <div class="flow-arrow">&rarr;</div>

      <div class="flow-box fb-po">
        <div class="fb-num">4</div>
        <div class="fb-title">PO Awarded</div>
        <div class="fb-sub">Advance &bull; Credit Terms</div>
        <div class="fb-by">Contract Won</div>
      </div>
      <div class="flow-arrow">&rarr;</div>

      <div class="flow-box fb-proj">
        <div class="fb-num">5</div>
        <div class="fb-title">Active Project</div>
        <div class="fb-sub">Site Progress &amp; Expenses</div>
        <div class="fb-by">Site PMs</div>
      </div>
    </div>

    <!-- CONNECTOR BAR -->
    <div class="flow-sync-bar">
      <div class="fs-line"></div>
      <div class="fs-label">&darr; All Data Automatically Synchronizes in Real-Time &darr;</div>
      <div class="fs-line"></div>
    </div>

    <!-- CEO MASTER BANNER -->
    <div class="flow-ceo-banner">
      <div class="fcb-left">
        <span class="fcb-badge">&starf; Live Executive View</span>
        <div class="fcb-title">Executive Dashboard</div>
        <div class="fcb-sub">Instant transparency &bull; Zero manual chasing</div>
      </div>
      <div class="fcb-pillars">
        <div class="fcb-p">
          <b>1. Sales Pipeline</b>
          <span>Deals, Values &amp; Payment Terms</span>
        </div>
        <div class="fcb-p">
          <b>2. Active Projects</b>
          <span>Live Site Progress % &amp; Milestones</span>
        </div>
        <div class="fcb-p">
          <b>3. Cash Flow</b>
          <span>Invoiced, Received &amp; Overdue</span>
        </div>
      </div>
    </div>
  </div>

  <div class="grid-2x2">
    <div class="val-card">
      <div class="val-num">1</div>
      <div>
        <b>100% In-House &bull; Zero Software Licensing</b>
        <span>Developed internally on enterprise PHP (Laravel) + MySQL. Full IP ownership by MVS with zero monthly subscriptions.</span>
      </div>
    </div>
    <div class="val-card">
      <div class="val-num">2</div>
      <div>
        <b>Strict Role-Isolated Confidentiality</b>
        <span>Sales sees only commercial deals; PMs see only their assigned site; company margins and total liquidity remain strictly private to CEO.</span>
      </div>
    </div>
  </div>

  <h3>Rapid 4-Week Delivery Roadmap</h3>
  <div class="tl-row">
    <div class="tl-step">
      <b>Phase 0: Baseline (Weeks 1&ndash;2)</b>
      <span>Collate active RFQs &amp; POs, standardize payment terms (Advance, Credit, Milestones), and initialize Project Details.</span>
    </div>
    <div class="tl-step active">
      <b>Phase 1: Core MVP (Weeks 3&ndash;4)</b>
      <span>Deploy Sales Lifecycle (RFQ-to-PO workflow), Project Progress %, Executive Invoiced/Received, and Petty Cash floats.</span>
    </div>
    <div class="tl-step">
      <b>Phase 2: Automated Briefs (Week 5)</b>
      <span>Auto-generated weekly PDF flash report sent to CEO every Thursday with collection forecasts.</span>
    </div>
  </div>

  <h2><span class="num">3</span>Immediate Action Items (Approvals to Start Build)</h2>
  <table style="margin-top:1.5mm;">
    <thead>
      <tr>
        <th style="width:28%">Decision Item</th>
        <th style="width:42%">Action / Input Needed</th>
        <th>Next Immediate Step</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>1. Sales Workflow &amp; Terms</td>
        <td>Confirm standard payment term options (Advance %, 30/60d Credit) and follow-up stages.</td>
        <td>IT will configure the full RFQ-to-PO pipeline and payment terms logic in 24 hours.</td>
      </tr>
      <tr>
        <td>2. Project Sanction</td>
        <td>Executive approval to commence Phase 0 data setup and Phase 1 build.</td>
        <td>Development initiates immediately upon sanction.</td>
      </tr>
      <tr>
        <td>3. Petty Cash Standard Float</td>
        <td>Confirm site petty cash limits (Recommended: SAR 5,000 / PM).</td>
        <td>Accounts sets up digital float audit and replenishment rules.</td>
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

</body>
</html>
HTML;

file_put_contents(__DIR__ . '/MVS_Project_Pulse_Proposal.html', $html);
echo "Executive HTML written successfully (" . strlen($html) . " bytes)\n";


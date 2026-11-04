import os
import pptx
from pptx.util import Inches, Pt
from pptx.dml.color import RGBColor
from pptx.enum.shapes import MSO_SHAPE
from pptx.enum.text import PP_ALIGN, MSO_ANCHOR

# ---------------------------------------------------------------------------
# COLOR PALETTE & STYLES (MVS BRAND IDENTITY & TEMPLATE CODES)
# ---------------------------------------------------------------------------
COLOR_PRIMARY_NAVY = RGBColor(45, 26, 82)    # #2D1A52 (Deep Royal Navy/Purple from Template)
COLOR_CYAN         = RGBColor(0, 175, 228)   # #00AFE4 (MVS Brand Accent Cyan)
COLOR_GOLD         = RGBColor(186, 150, 49)  # #BA9631 (MVS Gold)
COLOR_GOLD_LIGHT   = RGBColor(212, 176, 76)  # #D4B04C
COLOR_DARK         = RGBColor(15, 23, 42)    # #0F172A
COLOR_SLATE        = RGBColor(70, 84, 105)   # #465469 (Secondary Text)
COLOR_MUTED        = RGBColor(100, 116, 139) # #64748B
COLOR_LIGHT_BG     = RGBColor(248, 250, 252) # #F8FAFC (Card Background)
COLOR_BORDER       = RGBColor(225, 232, 239) # #E1E8EF (Divider & Border)
COLOR_WHITE        = RGBColor(255, 255, 255)

# Status & Accent Fills
COLOR_WON_BG       = RGBColor(236, 253, 245) # #ECFDF5
COLOR_WON_TXT      = RGBColor(6, 95, 70)     # #065F46
COLOR_NEG_BG       = RGBColor(255, 251, 235) # #FFFBEB
COLOR_NEG_TXT      = RGBColor(146, 64, 14)   # #92400E
COLOR_QUOTED_BG    = RGBColor(239, 246, 255) # #EFF6FF
COLOR_QUOTED_TXT   = RGBColor(30, 64, 175)   # #1E40AF
COLOR_ALERT_TXT    = RGBColor(153, 27, 27)   # #991B1B
COLOR_ROW_ALT      = RGBColor(250, 250, 250)

FONT_NAME = "Aptos"
BASE_DIR = os.path.dirname(os.path.abspath(__file__))
TEMPLATE_FILE = os.path.join(BASE_DIR, "MVS Weekly Meeting _ Format to develop.pptx")
OUTPUT_FILE   = os.path.join(BASE_DIR, "MVS_Project_Pulse_Executive_Proposal.pptx")
LOGO_PATH     = os.path.join(BASE_DIR, "extracted_slide_img.png")

TOTAL_SLIDES = 8

CURRENT_WEEK_STR = "2026 Week 41, 08/10/2026"

def apply_header_footer(slide, category_text, title_text, subtitle_text, slide_num):
    """
    Renders identical header elements matching 'MVS Weekly Meeting _ Format to develop.pptx':
    1. Vertical solid Cyan accent bar (0.08" x 1.00")
    2. Header text hierarchy (Category, Title, Week & Date, Subtitle)
    3. MVS Emblem Logo top-right
    4. Full-width separator rule
    5. Clean footer with MVS branding and pagination
    """
    # 1. Left Cyan Accent Bar (Exact master coordinates)
    bar = slide.shapes.add_shape(MSO_SHAPE.RECTANGLE, Inches(0.50), Inches(0.45), Inches(0.08), Inches(1.00))
    bar.fill.solid()
    bar.fill.fore_color.rgb = COLOR_CYAN
    bar.line.fill.background()

    # 2. Header Text Frame
    tb = slide.shapes.add_textbox(Inches(0.74), Inches(0.38), Inches(9.50), Inches(1.18))
    tf = tb.text_frame
    tf.word_wrap = True
    tf.margin_left = tf.margin_top = tf.margin_right = tf.margin_bottom = 0

    # Paragraph 1: Category / Breadcrumb
    p1 = tf.paragraphs[0]
    p1.space_after = Pt(1)
    r1 = p1.add_run()
    r1.text = f"M V S   \u00b7   {category_text}"
    r1.font.name = FONT_NAME
    r1.font.size = Pt(10.5)
    r1.font.bold = True
    r1.font.color.rgb = COLOR_CYAN

    # Paragraph 2: Slide Title
    p2 = tf.add_paragraph()
    p2.space_after = Pt(2)
    r2 = p2.add_run()
    r2.text = title_text
    r2.font.name = FONT_NAME
    r2.font.size = Pt(22)
    r2.font.bold = True
    r2.font.color.rgb = COLOR_PRIMARY_NAVY

    # Paragraph 3: Week & Date + Subtitle (Exact Original Format)
    p3 = tf.add_paragraph()
    r3_date = p3.add_run()
    r3_date.text = CURRENT_WEEK_STR
    r3_date.font.name = FONT_NAME
    r3_date.font.size = Pt(11)
    r3_date.font.bold = True
    r3_date.font.color.rgb = COLOR_SLATE

    if subtitle_text:
        r3_sub = p3.add_run()
        r3_sub.text = f"   \u2014   {subtitle_text}"
        r3_sub.font.name = FONT_NAME
        r3_sub.font.size = Pt(10.5)
        r3_sub.font.bold = False
        r3_sub.font.color.rgb = COLOR_SLATE

    # 3. Logo Top Right
    if os.path.exists(LOGO_PATH):
        slide.shapes.add_picture(LOGO_PATH, Inches(11.53), Inches(0.12), width=Inches(1.165), height=Inches(1.286))

    # 4. Dividing Line
    line = slide.shapes.add_shape(MSO_SHAPE.RECTANGLE, Inches(0.60), Inches(1.68), Inches(12.13), Pt(1))
    line.fill.solid()
    line.fill.fore_color.rgb = COLOR_BORDER
    line.line.fill.background()

    # 5. Footers
    ft_left = slide.shapes.add_textbox(Inches(0.60), Inches(7.00), Inches(8.00), Inches(0.35))
    ft_left.text_frame.margin_left = ft_left.text_frame.margin_top = 0
    p_fl = ft_left.text_frame.paragraphs[0]
    r_fl = p_fl.add_run()
    r_fl.text = "Modern Vision Solutions 2026 \u00b7 MVS ICT & Telecom \u2014 Executive Command Center"
    r_fl.font.name = FONT_NAME
    r_fl.font.size = Pt(8.5)
    r_fl.font.color.rgb = COLOR_MUTED

    ft_right = slide.shapes.add_textbox(Inches(8.50), Inches(7.00), Inches(4.23), Inches(0.35))
    ft_right.text_frame.margin_right = ft_right.text_frame.margin_top = 0
    p_fr = ft_right.text_frame.paragraphs[0]
    p_fr.alignment = PP_ALIGN.RIGHT
    r_fr = p_fr.add_run()
    r_fr.text = f"Slide {slide_num} of {TOTAL_SLIDES}  |  {CURRENT_WEEK_STR}  |  Confidential"
    r_fr.font.name = FONT_NAME
    r_fr.font.size = Pt(8.5)
    r_fr.font.color.rgb = COLOR_MUTED

def add_card(slide, left, top, width, height, bg_color=COLOR_LIGHT_BG, border_color=COLOR_BORDER, border_pt=1):
    shape = slide.shapes.add_shape(MSO_SHAPE.ROUNDED_RECTANGLE, left, top, width, height)
    shape.fill.solid()
    shape.fill.fore_color.rgb = bg_color
    if border_color:
        shape.line.color.rgb = border_color
        shape.line.width = Pt(border_pt)
    else:
        shape.line.fill.background()
    return shape

def set_cell_rich(cell, items, align=PP_ALIGN.LEFT, bg_color=None, valign=MSO_ANCHOR.MIDDLE, pad_tb=0.03, pad_lr=0.08):
    """
    Sets multiple formatted lines in a table cell.
    items: string OR list of tuples: (text, is_bold, font_size, color)
    """
    if bg_color:
        cell.fill.solid()
        cell.fill.fore_color.rgb = bg_color
    cell.vertical_anchor = valign
    cell.margin_left = Inches(pad_lr)
    cell.margin_right = Inches(pad_lr)
    cell.margin_top = Inches(pad_tb)
    cell.margin_bottom = Inches(pad_tb)

    tf = cell.text_frame
    tf.word_wrap = True

    if isinstance(items, str):
        items = [(items, False, Pt(8.5), COLOR_DARK)]

    for idx, item in enumerate(items):
        text, bold, size, col = item
        if idx == 0:
            p = tf.paragraphs[0]
        else:
            p = tf.add_paragraph()
            p.space_before = Pt(1.5)
        p.text = text
        p.alignment = align
        if p.runs:
            r = p.runs[0]
            r.font.name = FONT_NAME
            r.font.size = size
            r.font.bold = bold
            r.font.color.rgb = col

# ===========================================================================
# SLIDE BUILDERS
# ===========================================================================

def build_slide_1(slide):
    """
    SLIDE 1: Executive Brief / Cover Title Slide matching original template
    """
    apply_header_footer(slide, "Weekly Review", "Projects Setup", 
                        "Executive Proposal \u00b7 MVS ICT & Telecom", 1)

    # Left Hero Container
    hero = add_card(slide, Inches(0.60), Inches(1.88), Inches(7.50), Inches(4.90),
                    bg_color=RGBColor(245, 247, 250), border_color=COLOR_BORDER)
    tf = hero.text_frame
    tf.word_wrap = True
    tf.margin_left = Inches(0.35)
    tf.margin_right = Inches(0.35)
    tf.margin_top = Inches(0.25)
    tf.margin_bottom = Inches(0.18)

    # Hero Badge
    p = tf.paragraphs[0]
    p.space_after = Pt(4)
    r = p.add_run()
    r.text = f"MVS ICT PROJECTS REVIEW  \u2022  {CURRENT_WEEK_STR}"
    r.font.name = FONT_NAME
    r.font.size = Pt(8.8)
    r.font.bold = True
    r.font.color.rgb = COLOR_GOLD

    # Original Template Hero Title
    p2 = tf.add_paragraph()
    p2.space_after = Pt(2)
    r2 = p2.add_run()
    r2.text = "MVS ICT Weekly Projects Review"
    r2.font.name = FONT_NAME
    r2.font.size = Pt(17)
    r2.font.bold = True
    r2.font.color.rgb = COLOR_PRIMARY_NAVY

    # Original Template Subtitle
    p_sub_orig = tf.add_paragraph()
    p_sub_orig.space_after = Pt(6)
    r_so = p_sub_orig.add_run()
    r_so.text = "Projects, Leads, and Tasks \u2014 Executive Command Center"
    r_so.font.name = FONT_NAME
    r_so.font.size = Pt(11)
    r_so.font.bold = True
    r_so.font.color.rgb = COLOR_CYAN

    # Hero Subtitle
    p3 = tf.add_paragraph()
    p3.space_after = Pt(10)
    r3 = p3.add_run()
    r3.text = "A single pane of glass connecting Pre-Award Sales Deals, Active Site Delivery, and Real-Time Cash Flow without manual spreadsheets."
    r3.font.name = FONT_NAME
    r3.font.size = Pt(9.5)
    r3.font.color.rgb = COLOR_SLATE

    # Active Project Track Pills Headline
    p4 = tf.add_paragraph()
    p4.space_after = Pt(4)
    r4 = p4.add_run()
    r4.text = "Active Multi-Track Project Portfolio:"
    r4.font.name = FONT_NAME
    r4.font.size = Pt(10)
    r4.font.bold = True
    r4.font.color.rgb = COLOR_PRIMARY_NAVY

    # Project Tracks Bullets
    tracks = [
        ("Track 1: Seven Entertainment (SEVEN)", "Major entertainment destination contracting & fit-out execution"),
        ("Track 2: Salam CCS", "Corporate telecom & infrastructure delivery scope"),
        ("Track 3: Dune Corridor Project (DCP)", "Active strategic development site & operations"),
        ("Track 4+: New Awarded Projects", "Instant onboarding upon PO award \u2022 Infinite architectural scale")
    ]
    for t_title, t_desc in tracks:
        p_t = tf.add_paragraph()
        p_t.space_after = Pt(3.5)
        r_b = p_t.add_run()
        r_b.text = f"\u25b8 {t_title}: "
        r_b.font.name = FONT_NAME
        r_b.font.size = Pt(9.3)
        r_b.font.bold = True
        r_b.font.color.rgb = COLOR_GOLD if "Track 4+" in t_title else COLOR_PRIMARY_NAVY

        r_d = p_t.add_run()
        r_d.text = t_desc
        r_d.font.name = FONT_NAME
        r_d.font.size = Pt(8.8)
        r_d.font.color.rgb = COLOR_SLATE

    # Core Capabilities Box
    p_c = tf.add_paragraph()
    p_c.space_before = Pt(6)
    r_c = p_c.add_run()
    r_c.text = "Integrated Modules: Sales Pipeline (RFQ \u2192 PO) \u2022 Live Progress % \u2022 Cash Flow & Petty Cash Floats"
    r_c.font.name = FONT_NAME
    r_c.font.size = Pt(8.8)
    r_c.font.bold = True
    r_c.font.color.rgb = COLOR_CYAN

    # Right Column Metadata Cards (4 stacked cards)
    meta_cards = [
        ("PREPARED FOR", "Chief Executive Officer", "MVS Executive Leadership & C-Suite", COLOR_PRIMARY_NAVY),
        ("PREPARED BY", "Danish Akbar", "IT Support \u00b7 Systems Development", COLOR_CYAN),
        ("DOCUMENT SCOPE", "Executive Summary", "3-Page Fast Track \u2022 Version 2.0", COLOR_GOLD),
        ("ENTERPRISE STACK", "100% In-House Solution", "Zero Software Licensing \u2022 Zero Monthly Subscriptions", COLOR_WON_TXT),
    ]

    top_m = Inches(1.88)
    card_h = Inches(1.13)
    gap = Inches(0.12)
    for i, (k, v, s, acc_color) in enumerate(meta_cards):
        c_top = top_m + i * (card_h + gap)
        m_card = add_card(slide, Inches(8.30), c_top, Inches(4.43), card_h,
                          bg_color=COLOR_WHITE, border_color=COLOR_BORDER)
        
        # Color accent strip on left of card
        acc = slide.shapes.add_shape(MSO_SHAPE.RECTANGLE, Inches(8.30), c_top, Inches(0.08), card_h)
        acc.fill.solid()
        acc.fill.fore_color.rgb = acc_color
        acc.line.fill.background()

        mtf = m_card.text_frame
        mtf.word_wrap = True
        mtf.margin_left = Inches(0.25)
        mtf.margin_top = Inches(0.12)
        mtf.margin_right = Inches(0.15)
        mtf.margin_bottom = Inches(0.08)

        mp1 = mtf.paragraphs[0]
        mr1 = mp1.add_run()
        mr1.text = k
        mr1.font.name = FONT_NAME
        mr1.font.size = Pt(8)
        mr1.font.bold = True
        mr1.font.color.rgb = COLOR_MUTED

        mp2 = mtf.add_paragraph()
        mr2 = mp2.add_run()
        mr2.text = v
        mr2.font.name = FONT_NAME
        mr2.font.size = Pt(12)
        mr2.font.bold = True
        mr2.font.color.rgb = COLOR_PRIMARY_NAVY

        mp3 = mtf.add_paragraph()
        mr3 = mp3.add_run()
        mr3.text = s
        mr3.font.name = FONT_NAME
        mr3.font.size = Pt(8.5)
        mr3.font.color.rgb = COLOR_SLATE


def build_slide_2(slide):
    """
    SLIDE 2: Executive Command Dashboard & 3-Pillar Foundation
    """
    apply_header_footer(slide, "System Architecture", "Executive Command Dashboard",
                        "Live Operations Screen \u00b7 The 3-Pillar Unified Management Model", 2)

    # 3 Pillar Headline Cards side-by-side
    pillar_w = Inches(3.85)
    pillar_h = Inches(3.40)
    top_pos = Inches(1.88)

    pillars_data = [
        ("Pillar 1: Sales Pipeline", "Commercial Deals & Pre-Award Bids", COLOR_CYAN, [
            ("RFQ Logging & Triage", "Captures inbound commercial opportunities with target dates."),
            ("Deal Classification", "Services / Projects vs Box-Moving (Product Supply)."),
            ("Quotation & Revisions", "Live tracking of submitted quotes and price iterations."),
            ("Agreed Payment Terms", "Advance %, Credit terms (30/60d) and milestones."),
            ("PO Award & Sync", "Auto-triggers project initiation upon winning contract.")
        ]),
        ("Pillar 2: Active Projects", "Site Delivery & Milestones", COLOR_PRIMARY_NAVY, [
            ("Multi-Track Portfolio", "Seven Entertainment, Salam CCS, DCP & Future Tracks."),
            ("Live Progress %", "Real-time task and stage completion vs contract schedule."),
            ("Scope & Deliverables", "Clear visibility of site handover milestones and blockers."),
            ("Project Manager Assign", "Direct operational accountability per site manager."),
            ("Instant Track 4+ Scalability", "Add newly awarded contracts with one click.")
        ]),
        ("Pillar 3: Cash Flow", "Executive Financial Health", COLOR_GOLD, [
            ("Contract Financials", "Project Value, Total Invoiced, Received & Remaining."),
            ("Aged Overdue Alerts", "Automatic flags for overdue client receivables (20d, 45d)."),
            ("Petty Cash Floats", "Digital vouchers and mandatory photo receipts per PM."),
            ("Emergency Purchases", "Tagged to POs to eliminate unaccounted cash leakage."),
            ("Net Liquidity View", "Real-time bank receipts vs site outflow intelligence.")
        ])
    ]

    for i, (p_title, p_sub, accent_c, items) in enumerate(pillars_data):
        left_pos = Inches(0.60) + i * (pillar_w + Inches(0.29))
        card = add_card(slide, left_pos, top_pos, pillar_w, pillar_h, bg_color=COLOR_WHITE, border_color=COLOR_BORDER)

        # Top Accent Header Box
        hdr_bar = slide.shapes.add_shape(MSO_SHAPE.RECTANGLE, left_pos, top_pos, pillar_w, Inches(0.60))
        hdr_bar.fill.solid()
        hdr_bar.fill.fore_color.rgb = accent_c
        hdr_bar.line.fill.background()
        htf = hdr_bar.text_frame
        htf.margin_left = Inches(0.18)
        htf.margin_top = Inches(0.08)
        hp = htf.paragraphs[0]
        hr = hp.add_run()
        hr.text = p_title
        hr.font.name = FONT_NAME
        hr.font.size = Pt(11)
        hr.font.bold = True
        hr.font.color.rgb = COLOR_WHITE

        hp2 = htf.add_paragraph()
        hr2 = hp2.add_run()
        hr2.text = p_sub
        hr2.font.name = FONT_NAME
        hr2.font.size = Pt(8)
        hr2.font.color.rgb = COLOR_WHITE

        # Card Content Frame
        ctb = slide.shapes.add_textbox(left_pos + Inches(0.15), top_pos + Inches(0.68), pillar_w - Inches(0.30), pillar_h - Inches(0.75))
        ctf = ctb.text_frame
        ctf.word_wrap = True
        ctf.margin_left = ctf.margin_top = ctf.margin_right = ctf.margin_bottom = 0

        for idx, (it_title, it_desc) in enumerate(items):
            p = ctf.paragraphs[0] if idx == 0 else ctf.add_paragraph()
            p.space_after = Pt(3.5)
            r_b = p.add_run()
            r_b.text = f"\u2022 {it_title}: "
            r_b.font.name = FONT_NAME
            r_b.font.size = Pt(8.3)
            r_b.font.bold = True
            r_b.font.color.rgb = COLOR_PRIMARY_NAVY

            r_d = p.add_run()
            r_d.text = it_desc
            r_d.font.name = FONT_NAME
            r_d.font.size = Pt(8.1)
            r_d.font.color.rgb = COLOR_SLATE

    # Bottom Master Callout Banner
    bot_card = add_card(slide, Inches(0.60), Inches(5.42), Inches(12.13), Inches(1.38),
                        bg_color=RGBColor(245, 248, 252), border_color=COLOR_CYAN, border_pt=1.5)
    btf = bot_card.text_frame
    btf.word_wrap = True
    btf.margin_left = Inches(0.25)
    btf.margin_right = Inches(0.25)
    btf.margin_top = Inches(0.14)
    btf.margin_bottom = Inches(0.10)

    bp1 = btf.paragraphs[0]
    br1 = bp1.add_run()
    br1.text = "EXECUTIVE STRATEGIC VALUE \u2014 INSTANT TRANSPARENCY WITHOUT SPREADSHEET CHAOS"
    br1.font.name = FONT_NAME
    br1.font.size = Pt(9.5)
    br1.font.bold = True
    br1.font.color.rgb = COLOR_PRIMARY_NAVY

    bp2 = btf.add_paragraph()
    bp2.space_before = Pt(3)
    br2 = bp2.add_run()
    br2.text = (
        "Replaces fragmented WhatsApp messages, delayed phone check-ins, and manual Excel tracking with a unified, "
        "role-isolated web portal. Executive leadership gains real-time visibility over commercial opportunities, active site milestones, "
        "and corporate liquidity from any desktop or mobile device \u2014 enabling faster decisions and zero operational blind spots."
    )
    br2.font.name = FONT_NAME
    br2.font.size = Pt(9.2)
    br2.font.color.rgb = COLOR_SLATE


def build_slide_3(slide):
    """
    SLIDE 3: Pillar 1 — Commercial Sales Pipeline
    """
    apply_header_footer(slide, "Commercial Operations", "Pillar 1: Commercial Sales Pipeline",
                        "Pre-Award Deals \u00b7 RFQ \u2192 Quotation \u2192 Follow-up \u2192 PO Award & Payment Terms", 3)

    # Pre-Award Deals Table
    tbl_left = Inches(0.60)
    tbl_top  = Inches(1.88)
    tbl_w    = Inches(12.13)
    tbl_h    = Inches(2.25)

    shape = slide.shapes.add_table(5, 6, tbl_left, tbl_top, tbl_w, tbl_h)
    tbl = shape.table

    # Column Widths
    tbl.columns[0].width = Inches(2.30)  # Quote & RFQ Ref
    tbl.columns[1].width = Inches(2.70)  # Client Opportunity
    tbl.columns[2].width = Inches(1.70)  # Value (SAR)
    tbl.columns[3].width = Inches(2.33)  # Payment Terms
    tbl.columns[4].width = Inches(1.70)  # Commercial Stage
    tbl.columns[5].width = Inches(1.40)  # Closing & Prob.

    headers = ["Quotation & RFQ Ref", "Client Opportunity & Type", "Value (SAR)", "Payment Terms", "Commercial Stage", "Closing & Prob."]
    for col_idx, h in enumerate(headers):
        align = PP_ALIGN.RIGHT if col_idx == 2 else (PP_ALIGN.CENTER if col_idx in [4, 5] else PP_ALIGN.LEFT)
        set_cell_rich(tbl.cell(0, col_idx), [(h, True, Pt(9), COLOR_WHITE)], align=align, bg_color=COLOR_PRIMARY_NAVY)

    # Row 1
    set_cell_rich(tbl.cell(1, 0), [("MVS-QT-2026-088", True, Pt(8.5), COLOR_PRIMARY_NAVY), 
                                   ("RFQ: RFQ-2026-104", False, Pt(7.5), COLOR_MUTED)])
    set_cell_rich(tbl.cell(1, 1), [("Client Name 1", True, Pt(8.5), COLOR_DARK), 
                                   ("Services / Contracting", False, Pt(7.5), COLOR_CYAN)])
    set_cell_rich(tbl.cell(1, 2), [("SAR 1,850,000", True, Pt(9), COLOR_DARK)], align=PP_ALIGN.RIGHT)
    set_cell_rich(tbl.cell(1, 3), [("50% Adv / 50% Delivery", True, Pt(8.2), COLOR_DARK), 
                                   ("Advance cashflow secured", False, Pt(7.5), COLOR_WON_TXT)])
    set_cell_rich(tbl.cell(1, 4), [("Follow-up / Neg.", True, Pt(8.5), COLOR_NEG_TXT)], align=PP_ALIGN.CENTER, bg_color=COLOR_NEG_BG)
    set_cell_rich(tbl.cell(1, 5), [("End Oct", True, Pt(8.5), COLOR_DARK), 
                                   ("(85% Prob.)", False, Pt(7.5), COLOR_MUTED)], align=PP_ALIGN.CENTER)

    # Row 2
    set_cell_rich(tbl.cell(2, 0), [("MVS-QT-2026-092", True, Pt(8.5), COLOR_PRIMARY_NAVY), 
                                   ("RFQ: RFQ-2026-112", False, Pt(7.5), COLOR_MUTED)])
    set_cell_rich(tbl.cell(2, 1), [("Client Name 2", True, Pt(8.5), COLOR_DARK), 
                                   ("Product Supply (Box Moving)", False, Pt(7.5), COLOR_WON_TXT)])
    set_cell_rich(tbl.cell(2, 2), [("SAR 740,000", True, Pt(9), COLOR_DARK)], align=PP_ALIGN.RIGHT)
    set_cell_rich(tbl.cell(2, 3), [("30 Days Credit", True, Pt(8.2), COLOR_DARK), 
                                   ("Aged receivable alert set", False, Pt(7.5), COLOR_SLATE)])
    set_cell_rich(tbl.cell(2, 4), [("Quote Submitted", True, Pt(8.5), COLOR_QUOTED_TXT)], align=PP_ALIGN.CENTER, bg_color=COLOR_QUOTED_BG)
    set_cell_rich(tbl.cell(2, 5), [("Mid Nov", True, Pt(8.5), COLOR_DARK), 
                                   ("(75% Prob.)", False, Pt(7.5), COLOR_MUTED)], align=PP_ALIGN.CENTER)

    # Row 3
    set_cell_rich(tbl.cell(3, 0), [("MVS-QT-2026-095", True, Pt(8.5), COLOR_PRIMARY_NAVY), 
                                   ("PO: PO-2026-8841", True, Pt(7.5), COLOR_WON_TXT)])
    set_cell_rich(tbl.cell(3, 1), [("Client Name 3", True, Pt(8.5), COLOR_DARK), 
                                   ("Turnkey Services", False, Pt(7.5), COLOR_CYAN)])
    set_cell_rich(tbl.cell(3, 2), [("SAR 960,000", True, Pt(9), COLOR_DARK)], align=PP_ALIGN.RIGHT)
    set_cell_rich(tbl.cell(3, 3), [("100% Full Advance", True, Pt(8.2), COLOR_WON_TXT), 
                                   ("Immediate mobilization", False, Pt(7.5), COLOR_WON_TXT)])
    set_cell_rich(tbl.cell(3, 4), [("PO Awarded (Won)", True, Pt(8.5), COLOR_WON_TXT)], align=PP_ALIGN.CENTER, bg_color=COLOR_WON_BG)
    set_cell_rich(tbl.cell(3, 5), [("Awarded", True, Pt(8.5), COLOR_WON_TXT), 
                                   ("(100% Won)", False, Pt(7.5), COLOR_WON_TXT)], align=PP_ALIGN.CENTER)

    # Row 4 (Total Summary Row)
    set_cell_rich(tbl.cell(4, 0), [("TOTAL PIPELINE VALUE", True, Pt(9), COLOR_PRIMARY_NAVY)], bg_color=COLOR_ROW_ALT)
    set_cell_rich(tbl.cell(4, 1), [("3 Active Commercial Opportunities", False, Pt(8.5), COLOR_SLATE)], bg_color=COLOR_ROW_ALT)
    set_cell_rich(tbl.cell(4, 2), [("SAR 3,550,000", True, Pt(9.5), COLOR_PRIMARY_NAVY)], align=PP_ALIGN.RIGHT, bg_color=COLOR_ROW_ALT)
    set_cell_rich(tbl.cell(4, 3), [("Blended Terms (Adv + Credit)", False, Pt(8.2), COLOR_SLATE)], bg_color=COLOR_ROW_ALT)
    set_cell_rich(tbl.cell(4, 4), [("High Win Conversion", True, Pt(8.5), COLOR_WON_TXT)], align=PP_ALIGN.CENTER, bg_color=COLOR_ROW_ALT)
    set_cell_rich(tbl.cell(4, 5), [("SAR 2.98M Weighted", True, Pt(8.5), COLOR_PRIMARY_NAVY)], align=PP_ALIGN.CENTER, bg_color=COLOR_ROW_ALT)

    # 2 Bottom Strategic Insight Cards
    c1 = add_card(slide, Inches(0.60), Inches(4.38), Inches(5.95), Inches(2.40),
                  bg_color=COLOR_WHITE, border_color=COLOR_BORDER)
    c1_tf = c1.text_frame
    c1_tf.word_wrap = True
    c1_tf.margin_left = c1_tf.margin_right = Inches(0.22)
    c1_tf.margin_top = Inches(0.16)

    cp1 = c1_tf.paragraphs[0]
    cr1 = cp1.add_run()
    cr1.text = "1. Dual-Stream Classification (Services vs Box-Moving)"
    cr1.font.name = FONT_NAME
    cr1.font.size = Pt(10.5)
    cr1.font.bold = True
    cr1.font.color.rgb = COLOR_PRIMARY_NAVY

    cp2 = c1_tf.add_paragraph()
    cp2.space_before = Pt(5)
    cr2 = cp2.add_run()
    cr2.text = (
        "\u25b8 Services / Contracting: Long-cycle turnkey contracts requiring dedicated PM assignment, "
        "engineering deliverables, site progress % tracking, and labor allocation.\n"
        "\u25b8 Product Supply / Box-Moving: Rapid hardware & equipment supply with simple delivery notes, "
        "standard credit terms, and fast direct invoicing without site labor overhead."
    )
    cr2.font.name = FONT_NAME
    cr2.font.size = Pt(8.8)
    cr2.font.color.rgb = COLOR_SLATE

    c2 = add_card(slide, Inches(6.78), Inches(4.38), Inches(5.95), Inches(2.40),
                  bg_color=COLOR_WHITE, border_color=COLOR_BORDER)
    c2_tf = c2.text_frame
    c2_tf.word_wrap = True
    c2_tf.margin_left = c2_tf.margin_right = Inches(0.22)
    c2_tf.margin_top = Inches(0.16)

    c2p1 = c2_tf.paragraphs[0]
    c2r1 = c2p1.add_run()
    c2r1.text = "2. Automated Upfront Payment Term Invoicing"
    c2r1.font.name = FONT_NAME
    c2r1.font.size = Pt(10.5)
    c2r1.font.bold = True
    c2r1.font.color.rgb = COLOR_PRIMARY_NAVY

    c2p2 = c2_tf.add_paragraph()
    c2p2.space_before = Pt(5)
    c2r2 = c2p2.add_run()
    c2r2.text = (
        "\u25b8 Contractual Terms Locked: Advance %, Credit Days (30d/60d), or Milestones are finalized during quotation.\n"
        "\u25b8 Instant Trigger on PO Award: System automatically notifies Accounts to generate upfront mobilization invoices "
        "the moment a deal is won.\n"
        "\u25b8 Zero Cash Lag: Prevents delayed billing where teams begin mobilization work before invoicing "
        "the contractual client deposit."
    )
    c2r2.font.name = FONT_NAME
    c2r2.font.size = Pt(8.8)
    c2r2.font.color.rgb = COLOR_SLATE


def build_slide_4(slide):
    """
    SLIDE 4: Pillars 2 & 3 — Active Projects & Executive Financials
    """
    apply_header_footer(slide, "Executive Financials", "Pillars 2 & 3: Active Projects & Cash Flow",
                        "Live Portfolio Performance \u00b7 Project Value, Invoiced, Received & Overdue Aging", 4)

    # Top KPI Strip (4 Summary Metric Cards)
    kpi_w = Inches(2.88)
    kpi_h = Inches(1.05)
    kpi_top = Inches(1.88)

    kpis = [
        ("TOTAL PORTFOLIO VALUE", "SAR 4,400,000", "3 Active Executing Tracks", COLOR_PRIMARY_NAVY),
        ("TOTAL INVOICED TO DATE", "SAR 2,580,000", "58.6% of Total Contract Value", COLOR_CYAN),
        ("CASH RECEIVED (INFLOW)", "SAR 2,120,000", "82.2% Collection Efficiency", COLOR_WON_TXT),
        ("AGED OVERDUE RECEIVABLES", "SAR 460,000", "Immediate Collection Action", COLOR_ALERT_TXT)
    ]

    for i, (k_lbl, k_val, k_sub, k_col) in enumerate(kpis):
        k_left = Inches(0.60) + i * (kpi_w + Inches(0.20))
        k_card = add_card(slide, k_left, kpi_top, kpi_w, kpi_h, bg_color=COLOR_WHITE, border_color=COLOR_BORDER)
        
        # Color bar top
        kbar = slide.shapes.add_shape(MSO_SHAPE.RECTANGLE, k_left, kpi_top, kpi_w, Inches(0.06))
        kbar.fill.solid()
        kbar.fill.fore_color.rgb = k_col
        kbar.line.fill.background()

        ktf = k_card.text_frame
        ktf.word_wrap = True
        ktf.margin_left = ktf.margin_right = Inches(0.15)
        ktf.margin_top = Inches(0.10)

        kp1 = ktf.paragraphs[0]
        kr1 = kp1.add_run()
        kr1.text = k_lbl
        kr1.font.name = FONT_NAME
        kr1.font.size = Pt(7.5)
        kr1.font.bold = True
        kr1.font.color.rgb = COLOR_MUTED

        kp2 = ktf.add_paragraph()
        kr2 = kp2.add_run()
        kr2.text = k_val
        kr2.font.name = FONT_NAME
        kr2.font.size = Pt(13.5)
        kr2.font.bold = True
        kr2.font.color.rgb = k_col

        kp3 = ktf.add_paragraph()
        kr3 = kp3.add_run()
        kr3.text = k_sub
        kr3.font.name = FONT_NAME
        kr3.font.size = Pt(7.8)
        kr3.font.color.rgb = COLOR_SLATE

    # Active Project Performance Table
    tbl_top = Inches(3.12)
    tbl_w   = Inches(12.13)
    tbl_h   = Inches(3.65)

    shape = slide.shapes.add_table(6, 7, Inches(0.60), tbl_top, tbl_w, tbl_h)
    tbl = shape.table

    # Column Widths
    tbl.columns[0].width = Inches(2.80)  # Project Details
    tbl.columns[1].width = Inches(1.30)  # Progress %
    tbl.columns[2].width = Inches(1.70)  # Project Value
    tbl.columns[3].width = Inches(1.70)  # Invoiced
    tbl.columns[4].width = Inches(1.65)  # Received
    tbl.columns[5].width = Inches(1.48)  # Remaining
    tbl.columns[6].width = Inches(1.50)  # Overdue

    headers = ["Project Track & Scope", "Progress %", "Project Value", "Invoiced", "Received", "Remaining", "Aged Overdue"]
    for col_idx, h in enumerate(headers):
        align = PP_ALIGN.LEFT if col_idx == 0 else (PP_ALIGN.CENTER if col_idx == 1 else PP_ALIGN.RIGHT)
        set_cell_rich(tbl.cell(0, col_idx), [(h, True, Pt(9), COLOR_WHITE)], align=align, bg_color=COLOR_PRIMARY_NAVY)

    # Row 1: Seven Entertainment
    set_cell_rich(tbl.cell(1, 0), [("Track 1: Seven Entertainment", True, Pt(8.5), COLOR_PRIMARY_NAVY),
                                   ("Entertainment Destination Fit-out", False, Pt(7.5), COLOR_MUTED)])
    set_cell_rich(tbl.cell(1, 1), [("68%", True, Pt(9), COLOR_WON_TXT),
                                   ("Active Delivery", False, Pt(7.5), COLOR_MUTED)], align=PP_ALIGN.CENTER)
    set_cell_rich(tbl.cell(1, 2), [("SAR 2,450,000", True, Pt(8.8), COLOR_DARK)], align=PP_ALIGN.RIGHT)
    set_cell_rich(tbl.cell(1, 3), [("SAR 1,470,000", False, Pt(8.5), COLOR_DARK),
                                   ("(60.0% Billed)", False, Pt(7.5), COLOR_MUTED)], align=PP_ALIGN.RIGHT)
    set_cell_rich(tbl.cell(1, 4), [("SAR 1,120,000", True, Pt(8.8), COLOR_WON_TXT),
                                   ("(Collected)", False, Pt(7.5), COLOR_WON_TXT)], align=PP_ALIGN.RIGHT)
    set_cell_rich(tbl.cell(1, 5), [("SAR 980,000", False, Pt(8.5), COLOR_SLATE),
                                   ("(Unbilled)", False, Pt(7.5), COLOR_MUTED)], align=PP_ALIGN.RIGHT)
    set_cell_rich(tbl.cell(1, 6), [("SAR 350,000", True, Pt(8.8), COLOR_ALERT_TXT),
                                   ("(45 days overdue)", False, Pt(7.5), COLOR_ALERT_TXT)], align=PP_ALIGN.RIGHT)

    # Row 2: Salam CCS
    set_cell_rich(tbl.cell(2, 0), [("Track 2: Salam CCS", True, Pt(8.5), COLOR_PRIMARY_NAVY),
                                   ("Corporate Cloud Services & Infra", False, Pt(7.5), COLOR_MUTED)])
    set_cell_rich(tbl.cell(2, 1), [("75%", True, Pt(9), COLOR_WON_TXT),
                                   ("Active Delivery", False, Pt(7.5), COLOR_MUTED)], align=PP_ALIGN.CENTER)
    set_cell_rich(tbl.cell(2, 2), [("SAR 1,100,000", True, Pt(8.8), COLOR_DARK)], align=PP_ALIGN.RIGHT)
    set_cell_rich(tbl.cell(2, 3), [("SAR 770,000", False, Pt(8.5), COLOR_DARK),
                                   ("(70.0% Billed)", False, Pt(7.5), COLOR_MUTED)], align=PP_ALIGN.RIGHT)
    set_cell_rich(tbl.cell(2, 4), [("SAR 660,000", True, Pt(8.8), COLOR_WON_TXT),
                                   ("(Collected)", False, Pt(7.5), COLOR_WON_TXT)], align=PP_ALIGN.RIGHT)
    set_cell_rich(tbl.cell(2, 5), [("SAR 330,000", False, Pt(8.5), COLOR_SLATE),
                                   ("(Unbilled)", False, Pt(7.5), COLOR_MUTED)], align=PP_ALIGN.RIGHT)
    set_cell_rich(tbl.cell(2, 6), [("SAR 110,000", True, Pt(8.8), COLOR_ALERT_TXT),
                                   ("(20 days overdue)", False, Pt(7.5), COLOR_ALERT_TXT)], align=PP_ALIGN.RIGHT)

    # Row 3: DCP
    set_cell_rich(tbl.cell(3, 0), [("Track 3: Dune Corridor (DCP)", True, Pt(8.5), COLOR_PRIMARY_NAVY),
                                   ("Active Strategic Development Corridor", False, Pt(7.5), COLOR_MUTED)])
    set_cell_rich(tbl.cell(3, 1), [("42%", True, Pt(9), COLOR_WON_TXT),
                                   ("Active Delivery", False, Pt(7.5), COLOR_MUTED)], align=PP_ALIGN.CENTER)
    set_cell_rich(tbl.cell(3, 2), [("SAR 850,000", True, Pt(8.8), COLOR_DARK)], align=PP_ALIGN.RIGHT)
    set_cell_rich(tbl.cell(3, 3), [("SAR 340,000", False, Pt(8.5), COLOR_DARK),
                                   ("(40.0% Billed)", False, Pt(7.5), COLOR_MUTED)], align=PP_ALIGN.RIGHT)
    set_cell_rich(tbl.cell(3, 4), [("SAR 340,000", True, Pt(8.8), COLOR_WON_TXT),
                                   ("(Collected)", False, Pt(7.5), COLOR_WON_TXT)], align=PP_ALIGN.RIGHT)
    set_cell_rich(tbl.cell(3, 5), [("SAR 510,000", False, Pt(8.5), COLOR_SLATE),
                                   ("(Unbilled)", False, Pt(7.5), COLOR_MUTED)], align=PP_ALIGN.RIGHT)
    set_cell_rich(tbl.cell(3, 6), [("SAR 0", True, Pt(8.8), COLOR_WON_TXT),
                                   ("(Current / Nil Overdue)", False, Pt(7.5), COLOR_WON_TXT)], align=PP_ALIGN.RIGHT)

    # Row 4: Future Tracks Onboarding - merge cols 2-6 for ultra-clean layout
    set_cell_rich(tbl.cell(4, 0), [("Track 4+: New Awarded Projects", True, Pt(8.5), COLOR_GOLD),
                                   ("Future Portfolio Expansion", False, Pt(7.5), COLOR_MUTED)], bg_color=RGBColor(254, 252, 246))
    set_cell_rich(tbl.cell(4, 1), [("Auto-sync", True, Pt(8.5), COLOR_GOLD),
                                   ("On PO Award", False, Pt(7.5), COLOR_MUTED)], align=PP_ALIGN.CENTER, bg_color=RGBColor(254, 252, 246))
    
    # Merge cells 2 to 6
    tbl.cell(4, 2).merge(tbl.cell(4, 6))
    set_cell_rich(tbl.cell(4, 2), [
        ("+ Instant Onboarding: Automatically adds Progress %, Project Value, Invoiced & Received upon PO award.", True, Pt(8.5), COLOR_PRIMARY_NAVY),
        ("Infinite Architectural Scale \u2022 Seamlessly activates without software restructuring or custom coding.", False, Pt(7.8), COLOR_SLATE)
    ], bg_color=RGBColor(254, 252, 246))

    # Row 5: Total Portfolio
    set_cell_rich(tbl.cell(5, 0), [("TOTAL PORTFOLIO SUMMARY", True, Pt(9), COLOR_PRIMARY_NAVY)], bg_color=COLOR_ROW_ALT)
    set_cell_rich(tbl.cell(5, 1), [("64% Avg", True, Pt(9), COLOR_WON_TXT)], align=PP_ALIGN.CENTER, bg_color=COLOR_ROW_ALT)
    set_cell_rich(tbl.cell(5, 2), [("SAR 4,400,000", True, Pt(9.5), COLOR_PRIMARY_NAVY)], align=PP_ALIGN.RIGHT, bg_color=COLOR_ROW_ALT)
    set_cell_rich(tbl.cell(5, 3), [("SAR 2,580,000", True, Pt(9.5), COLOR_PRIMARY_NAVY)], align=PP_ALIGN.RIGHT, bg_color=COLOR_ROW_ALT)
    set_cell_rich(tbl.cell(5, 4), [("SAR 2,120,000", True, Pt(9.5), COLOR_WON_TXT)], align=PP_ALIGN.RIGHT, bg_color=COLOR_ROW_ALT)
    set_cell_rich(tbl.cell(5, 5), [("SAR 1,820,000", True, Pt(9), COLOR_SLATE)], align=PP_ALIGN.RIGHT, bg_color=COLOR_ROW_ALT)
    set_cell_rich(tbl.cell(5, 6), [("SAR 460,000", True, Pt(9.5), COLOR_ALERT_TXT)], align=PP_ALIGN.RIGHT, bg_color=COLOR_ROW_ALT)


def build_slide_5(slide):
    """
    SLIDE 5: Site Cash Flow & Expense Governance
    """
    apply_header_footer(slide, "Site Financial Control", "Site Cash Flow & Expense Governance",
                        "Petty Cash Float, Emergency Purchases & Site Allowances Audit", 5)

    card_w = Inches(5.95)
    card_h = Inches(2.35)
    top_row1 = Inches(1.88)
    top_row2 = Inches(4.45)
    left_c1  = Inches(0.60)
    left_c2  = Inches(6.78)

    cards = [
        (left_c1, top_row1, "1. Petty Cash Float Control", COLOR_CYAN, [
            ("Standard Float Assignment", "Pre-authorized revolving cash float assigned per Site PM (e.g. SAR 5,000)."),
            ("Digital Voucher Submission", "Mobile & desktop submission for site consumables, daily transport, and minor tools."),
            ("Mandatory Receipt Photo Upload", "Requires high-resolution invoice/receipt capture prior to float reimbursement approval."),
            ("Live Float Balance Audit", "Accounts and C-Suite verify unspent cash balances across all sites in real-time.")
        ]),
        (left_c2, top_row1, "2. Materials & Emergency Purchases", COLOR_PRIMARY_NAVY, [
            ("Emergency Site Consumables", "Tracks unplanned local hardware purchases, emergency wiring, and urgent site tools."),
            ("Mandatory PO Tagging", "Spend must link to an approved Project PO code to eliminate cost leakage."),
            ("Supplier Invoice Matching", "Direct ledger entry preventing double-invoicing or duplicate reimbursement."),
            ("Real-Time Project Costing", "Expenditures immediately adjust actual project gross margin against original budget.")
        ]),
        (left_c1, top_row2, "3. Site Allowances & Technician Per-Diems", COLOR_GOLD, [
            ("Technician Daily Per-Diems", "Standardized daily meal, travel, and site accommodation allowances."),
            ("Timesheet Integration", "Reconciled against verified on-site biometric or PM-approved muster attendance."),
            ("Overtime Pre-Authorization", "Overtime hours tracked and validated against billable customer variation orders."),
            ("Frictionless Month-End Payroll", "Eliminates disputed manual claims and delays in technician expense settlements.")
        ]),
        (left_c2, top_row2, "4. Net Cash Flow & Liquidity Intelligence", COLOR_WON_TXT, [
            ("Inflow vs Outflow Comparison", "Monitors actual customer cash received vs cumulative site operational outflow."),
            ("Working Capital Protection", "Automated flags alert leadership if site expenditures approach received client funds."),
            ("Positive Project Liquidity", "Guarantees site execution remains self-funded without company cash drain."),
            ("Executive Audit Readiness", "Instant export of digital receipts, vouchers, and approvals for corporate audit.")
        ])
    ]

    for left_pos, top_pos, title, accent_c, bullets in cards:
        card = add_card(slide, left_pos, top_pos, card_w, card_h, bg_color=COLOR_WHITE, border_color=COLOR_BORDER)
        
        # Left Accent Border
        acc = slide.shapes.add_shape(MSO_SHAPE.RECTANGLE, left_pos, top_pos, Inches(0.08), card_h)
        acc.fill.solid()
        acc.fill.fore_color.rgb = accent_c
        acc.line.fill.background()

        ctf = card.text_frame
        ctf.word_wrap = True
        ctf.margin_left = Inches(0.22)
        ctf.margin_right = Inches(0.18)
        ctf.margin_top = Inches(0.14)

        cp1 = ctf.paragraphs[0]
        cr1 = cp1.add_run()
        cr1.text = title
        cr1.font.name = FONT_NAME
        cr1.font.size = Pt(10.5)
        cr1.font.bold = True
        cr1.font.color.rgb = COLOR_PRIMARY_NAVY

        for idx, (b_title, b_desc) in enumerate(bullets):
            p = ctf.add_paragraph()
            p.space_before = Pt(3)
            r_b = p.add_run()
            r_b.text = f"\u25b8 {b_title}: "
            r_b.font.name = FONT_NAME
            r_b.font.size = Pt(8.5)
            r_b.font.bold = True
            r_b.font.color.rgb = COLOR_PRIMARY_NAVY

            r_d = p.add_run()
            r_d.text = b_desc
            r_d.font.name = FONT_NAME
            r_d.font.size = Pt(8.3)
            r_d.font.color.rgb = COLOR_SLATE


def build_slide_6(slide):
    """
    SLIDE 6: How It Works — Deal-to-Execution Flowchart
    """
    apply_header_footer(slide, "Operational Workflow", "Deal-to-Execution Flowchart",
                        "5-Stage Departmental Lifecycle & Real-Time Executive Synchronization", 6)

    # 5 Process Flow Boxes
    box_w = Inches(2.20)
    box_h = Inches(2.05)
    gap   = Inches(0.28)
    top_p = Inches(1.88)

    steps = [
        ("1", "RFQ Received", "Commercial Inquiry", "Sales Team", 
         "Customer RFQ logged with scope and classified into Services or Box-Moving.", COLOR_CYAN),
        ("2", "Quotation Sent", "Pricing & Terms", "Sales Team", 
         "Commercial proposal issued with locked payment terms and milestones.", COLOR_PRIMARY_NAVY),
        ("3", "Follow-up", "Negotiations", "Sales Team", 
         "Clarifications logged with win probability rating (e.g. 75%, 85%).", COLOR_GOLD),
        ("4", "PO Awarded", "Contract Won", "Deal Closed", 
         "Contract awarded; system auto-triggers upfront invoice request to Accounts.", COLOR_WON_TXT),
        ("5", "Active Project", "Site Execution", "Site PMs", 
         "Project track initialized; live progress % and petty cash governance live.", COLOR_PRIMARY_NAVY)
    ]

    for i, (num, title, sub, actor, desc, col) in enumerate(steps):
        left_pos = Inches(0.60) + i * (box_w + gap)
        box = add_card(slide, left_pos, top_p, box_w, box_h, bg_color=COLOR_WHITE, border_color=COLOR_BORDER)

        # Header bar of box
        hbar = slide.shapes.add_shape(MSO_SHAPE.RECTANGLE, left_pos, top_p, box_w, Inches(0.55))
        hbar.fill.solid()
        hbar.fill.fore_color.rgb = col
        hbar.line.fill.background()

        htf = hbar.text_frame
        htf.margin_left = Inches(0.10)
        htf.margin_top = Inches(0.06)
        hp = htf.paragraphs[0]
        hr = hp.add_run()
        hr.text = f"Step {num}: {title}"
        hr.font.name = FONT_NAME
        hr.font.size = Pt(9)
        hr.font.bold = True
        hr.font.color.rgb = COLOR_WHITE

        hp2 = htf.add_paragraph()
        hr2 = hp2.add_run()
        hr2.text = f"{sub} \u2022 {actor}"
        hr2.font.name = FONT_NAME
        hr2.font.size = Pt(7.5)
        hr2.font.color.rgb = RGBColor(230, 240, 255)

        # Body text of box
        btb = slide.shapes.add_textbox(left_pos + Inches(0.10), top_p + Inches(0.62), box_w - Inches(0.20), box_h - Inches(0.70))
        btf = btb.text_frame
        btf.word_wrap = True
        btf.margin_left = btf.margin_top = btf.margin_right = btf.margin_bottom = 0
        bp = btf.paragraphs[0]
        br = bp.add_run()
        br.text = desc
        br.font.name = FONT_NAME
        br.font.size = Pt(8.2)
        br.font.color.rgb = COLOR_SLATE

        # Arrow connector between boxes
        if i < 4:
            arrow_left = left_pos + box_w + Inches(0.05)
            arr = slide.shapes.add_shape(MSO_SHAPE.RIGHT_ARROW, arrow_left, top_p + Inches(0.85), Inches(0.18), Inches(0.25))
            arr.fill.solid()
            arr.fill.fore_color.rgb = COLOR_CYAN
            arr.line.fill.background()

    # Synchronization Banner
    sync_bar = add_card(slide, Inches(0.60), Inches(4.15), Inches(12.13), Inches(0.40),
                        bg_color=RGBColor(240, 246, 253), border_color=COLOR_CYAN)
    stf = sync_bar.text_frame
    stf.margin_top = Inches(0.08)
    sp = stf.paragraphs[0]
    sp.alignment = PP_ALIGN.CENTER
    sr = sp.add_run()
    sr.text = "\u25bc   ALL DEPARTMENT DATA AUTOMATICALLY SYNCHRONIZES IN REAL-TIME INTO THE EXECUTIVE DASHBOARD   \u25bc"
    sr.font.name = FONT_NAME
    sr.font.size = Pt(8.5)
    sr.font.bold = True
    sr.font.color.rgb = COLOR_PRIMARY_NAVY

    # Bottom Master Executive Command Box (3 Pillars Displayed)
    bot_card = add_card(slide, Inches(0.60), Inches(4.75), Inches(12.13), Inches(2.05),
                        bg_color=COLOR_WHITE, border_color=COLOR_PRIMARY_NAVY, border_pt=1.5)
    
    # Left Dark Accent Title Box
    title_box = slide.shapes.add_shape(MSO_SHAPE.RECTANGLE, Inches(0.60), Inches(4.75), Inches(2.80), Inches(2.05))
    title_box.fill.solid()
    title_box.fill.fore_color.rgb = COLOR_PRIMARY_NAVY
    title_box.line.fill.background()
    ttf = title_box.text_frame
    ttf.word_wrap = True
    ttf.margin_left = ttf.margin_right = Inches(0.18)
    ttf.margin_top = Inches(0.20)
    tp1 = ttf.paragraphs[0]
    tr1 = tp1.add_run()
    tr1.text = "EXECUTIVE DASHBOARD"
    tr1.font.name = FONT_NAME
    tr1.font.size = Pt(11)
    tr1.font.bold = True
    tr1.font.color.rgb = COLOR_CYAN

    tp2 = ttf.add_paragraph()
    tp2.space_before = Pt(4)
    tr2 = tp2.add_run()
    tr2.text = "Instant Transparency \u2022 Zero Chasing\nConsolidated C-Suite intelligence from Sales, Sites & Finance in a single command portal."
    tr2.font.name = FONT_NAME
    tr2.font.size = Pt(8.5)
    tr2.font.color.rgb = RGBColor(220, 230, 245)

    # 3 Summary Pillar Blocks on Right
    p_w = Inches(2.95)
    p_h = Inches(1.85)
    p_top = Inches(4.85)
    
    p_items = [
        (Inches(3.60), "1. Commercial Sales View", [
            ("Pipeline Deals", "Active RFQs & weighted values."),
            ("Quotation Success", "Win rates and conversion cycle."),
            ("Payment Terms", "Contractual advance & credit terms.")
        ]),
        (Inches(6.65), "2. Active Project View", [
            ("Live Progress %", "Physical site completion tracking."),
            ("Milestone Delivery", "Timelines, deliverables & handovers."),
            ("Track 4+ Setup", "Immediate project addition on PO award.")
        ]),
        (Inches(9.70), "3. Cash Flow & Liquidity", [
            ("Invoiced vs Received", "Actual cash collected vs billed."),
            ("Aged Overdue Debt", "Flags unpaid invoices over 20d/45d."),
            ("Site Float Control", "Audited digital receipts per PM.")
        ])
    ]

    for p_left, p_hdr, p_bullets in p_items:
        p_box = add_card(slide, p_left, p_top, p_w, p_h, bg_color=RGBColor(248, 250, 252), border_color=COLOR_BORDER)
        ptf = p_box.text_frame
        ptf.word_wrap = True
        ptf.margin_left = ptf.margin_right = Inches(0.15)
        ptf.margin_top = Inches(0.12)

        p_hp = ptf.paragraphs[0]
        p_hr = p_hp.add_run()
        p_hr.text = p_hdr
        p_hr.font.name = FONT_NAME
        p_hr.font.size = Pt(9.5)
        p_hr.font.bold = True
        p_hr.font.color.rgb = COLOR_PRIMARY_NAVY

        for b_t, b_d in p_bullets:
            p_bp = ptf.add_paragraph()
            p_bp.space_before = Pt(3)
            p_br = p_bp.add_run()
            p_br.text = f"\u2022 {b_t}: "
            p_br.font.name = FONT_NAME
            p_br.font.size = Pt(8.2)
            p_br.font.bold = True
            p_br.font.color.rgb = COLOR_PRIMARY_NAVY

            p_dr = p_bp.add_run()
            p_dr.text = b_d
            p_dr.font.name = FONT_NAME
            p_dr.font.size = Pt(8)
            p_dr.font.color.rgb = COLOR_SLATE


def build_slide_7(slide):
    """
    SLIDE 7: Enterprise Architecture & Rapid 4-Week Roadmap
    """
    apply_header_footer(slide, "Technology & Implementation", "Architecture & Rapid 4-Week Roadmap",
                        "Zero Software Licensing, Role Confidentiality & Fast-Track Rollout", 7)

    col_w = Inches(5.95)
    left_c1 = Inches(0.60)
    left_c2 = Inches(6.78)

    # Left Column: Strategic Enterprise Strengths (3 Stacked Cards)
    left_cards = [
        ("100% In-House & Zero Software Licensing", COLOR_WON_TXT,
         "Built entirely internally on enterprise PHP (Laravel) + MySQL. Full proprietary IP ownership belongs to MVS with ZERO monthly SaaS subscriptions or per-user licensing fees."),
        ("Strict Role-Isolated Confidentiality", COLOR_PRIMARY_NAVY,
         "Sales sees only commercial deals; Site PMs see only their assigned track; corporate profit margins, overall cash liquidity, and balance sheets remain strictly private to the CEO."),
        ("Infinite Scale & Rapid Extensibility", COLOR_CYAN,
         "Effortlessly onboards Track 4, Track 5, and future contracts with instant configuration. Modular architecture ready for automated ERP/accounting integration.")
    ]

    card_h = Inches(1.50)
    gap    = Inches(0.18)
    for i, (c_title, c_color, c_desc) in enumerate(left_cards):
        c_top = Inches(1.88) + i * (card_h + gap)
        sc = add_card(slide, left_c1, c_top, col_w, card_h, bg_color=COLOR_WHITE, border_color=COLOR_BORDER)
        
        # Left Accent Strip
        acc = slide.shapes.add_shape(MSO_SHAPE.RECTANGLE, left_c1, c_top, Inches(0.08), card_h)
        acc.fill.solid()
        acc.fill.fore_color.rgb = c_color
        acc.line.fill.background()

        stf = sc.text_frame
        stf.word_wrap = True
        stf.margin_left = Inches(0.20)
        stf.margin_right = Inches(0.15)
        stf.margin_top = Inches(0.14)

        sp1 = stf.paragraphs[0]
        sr1 = sp1.add_run()
        sr1.text = c_title
        sr1.font.name = FONT_NAME
        sr1.font.size = Pt(10)
        sr1.font.bold = True
        sr1.font.color.rgb = COLOR_PRIMARY_NAVY

        sp2 = stf.add_paragraph()
        sp2.space_before = Pt(4)
        sr2 = sp2.add_run()
        sr2.text = c_desc
        sr2.font.name = FONT_NAME
        sr2.font.size = Pt(8.5)
        sr2.font.color.rgb = COLOR_SLATE

    # Right Column: Rapid 4-Week Delivery Roadmap (3 Timeline Cards)
    timeline_steps = [
        ("Phase 0: Baseline & Schema Setup", "Weeks 1 \u2013 2", COLOR_CYAN,
         "Collate existing historical RFQs, active contracts & customer terms. Standardize payment terms logic (Advance %, Credit Days, Milestones) and initialize the multi-track database schema."),
        ("Phase 1: Core MVP Command Launch", "Weeks 3 \u2013 4", COLOR_PRIMARY_NAVY,
         "Deploy Commercial Sales Pipeline (RFQ-to-PO workflow), Live Project Progress % tracking, Executive Invoicing/Received dashboard, and digital Petty Cash float management."),
        ("Phase 2: Automated Executive Briefs", "Week 5", COLOR_GOLD,
         "Roll out auto-generated weekly PDF flash reports dispatched to the CEO every Thursday afternoon with collection forecasts, aged debtor alerts, and project status milestones.")
    ]

    for i, (t_title, t_duration, t_color, t_desc) in enumerate(timeline_steps):
        t_top = Inches(1.88) + i * (card_h + gap)
        tc = add_card(slide, left_c2, t_top, col_w, card_h, bg_color=COLOR_WHITE, border_color=COLOR_BORDER)
        
        # Header strip on timeline card
        t_bar = slide.shapes.add_shape(MSO_SHAPE.RECTANGLE, left_c2, t_top, col_w, Inches(0.38))
        t_bar.fill.solid()
        t_bar.fill.fore_color.rgb = t_color
        t_bar.line.fill.background()

        ttf = t_bar.text_frame
        ttf.margin_left = Inches(0.15)
        ttf.margin_top = Inches(0.06)
        tp = ttf.paragraphs[0]
        tr = tp.add_run()
        tr.text = f"{t_title}   \u2022   {t_duration}"
        tr.font.name = FONT_NAME
        tr.font.size = Pt(9.5)
        tr.font.bold = True
        tr.font.color.rgb = COLOR_WHITE

        # Description
        dtb = slide.shapes.add_textbox(left_c2 + Inches(0.15), t_top + Inches(0.44), col_w - Inches(0.30), card_h - Inches(0.50))
        dtf = dtb.text_frame
        dtf.word_wrap = True
        dtf.margin_left = dtf.margin_top = dtf.margin_right = dtf.margin_bottom = 0
        dp = dtf.paragraphs[0]
        dr = dp.add_run()
        dr.text = t_desc
        dr.font.name = FONT_NAME
        dr.font.size = Pt(8.5)
        dr.font.color.rgb = COLOR_SLATE


def build_slide_8(slide):
    """
    SLIDE 8: Immediate Action Items (CEO Approvals) & Sign-Off
    """
    apply_header_footer(slide, "Action & Sign-Off", "Immediate Action Items & Sign-Off",
                        "Executive Decisions & Inputs Required to Commence Build", 8)

    # Action Items Table
    tbl_left = Inches(0.60)
    tbl_top  = Inches(1.88)
    tbl_w    = Inches(12.13)
    tbl_h    = Inches(2.70)

    shape = slide.shapes.add_table(4, 3, tbl_left, tbl_top, tbl_w, tbl_h)
    tbl = shape.table

    tbl.columns[0].width = Inches(3.20)  # Decision Item
    tbl.columns[1].width = Inches(4.70)  # Action / Input Needed
    tbl.columns[2].width = Inches(4.23)  # Next Immediate Step

    headers = ["Executive Decision Item", "Action / Input Needed from CEO", "Immediate Next Technical Step"]
    for col_idx, h in enumerate(headers):
        set_cell_rich(tbl.cell(0, col_idx), [(h, True, Pt(9), COLOR_WHITE)], bg_color=COLOR_PRIMARY_NAVY)

    # Row 1
    set_cell_rich(tbl.cell(1, 0), [("1. Sales Workflow & Terms", True, Pt(8.5), COLOR_PRIMARY_NAVY)])
    set_cell_rich(tbl.cell(1, 1), [("Confirm standard payment term options (Advance %, 30/60d Credit) and commercial follow-up stages.", False, Pt(8.5), COLOR_DARK)])
    set_cell_rich(tbl.cell(1, 2), [("IT will configure the complete RFQ-to-PO pipeline and payment terms logic in 24 hours.", False, Pt(8.3), COLOR_SLATE)])

    # Row 2
    set_cell_rich(tbl.cell(2, 0), [("2. Project Sanction", True, Pt(8.5), COLOR_PRIMARY_NAVY)])
    set_cell_rich(tbl.cell(2, 1), [("Executive sanction to commence Phase 0 data collation and Phase 1 technical development.", False, Pt(8.5), COLOR_DARK)])
    set_cell_rich(tbl.cell(2, 2), [("Application scaffolding and database migration initiate immediately upon approval.", False, Pt(8.3), COLOR_SLATE)])

    # Row 3
    set_cell_rich(tbl.cell(3, 0), [("3. Petty Cash Float Limits", True, Pt(8.5), COLOR_PRIMARY_NAVY)])
    set_cell_rich(tbl.cell(3, 1), [("Confirm standard site petty cash ceiling (Recommended: SAR 5,000 per Site Project Manager).", False, Pt(8.5), COLOR_DARK)])
    set_cell_rich(tbl.cell(3, 2), [("Accounts and IT establish digital voucher audit workflow and receipt verification rules.", False, Pt(8.3), COLOR_SLATE)])

    # Bottom Formal Sign-Off Container
    sign_card = add_card(slide, Inches(0.60), Inches(4.80), Inches(12.13), Inches(2.00),
                         bg_color=COLOR_WHITE, border_color=COLOR_PRIMARY_NAVY, border_pt=1.5)
    
    # 3 Sign-Off Columns inside container
    col_w = Inches(3.80)
    top_pos = Inches(4.95)

    sign_blocks = [
        (Inches(0.80), "PREPARED & SUBMITTED BY", "Danish Akbar", "IT Support \u00b7 Systems Development", 
         "Modern Vision Solutions Ltd\nRiyadh, Kingdom of Saudi Arabia", COLOR_CYAN),
        (Inches(4.85), "EXECUTIVE SANCTION & APPROVAL", "Chief Executive Officer", "Executive Leadership", 
         "Approved to proceed with Phase 0 & Phase 1\nDate: October 2026", COLOR_GOLD),
        (Inches(8.90), "DOCUMENT GOVERNANCE", "MVS ICT & Telecom", "Executive Command Center \u2022 v2.0", 
         "Al Rashid Tower, King Saud Rd, Riyadh\nConfidential \u2022 Internal Distribution Only", COLOR_PRIMARY_NAVY)
    ]

    for s_left, s_tag, s_name, s_role, s_sub, s_col in sign_blocks:
        sb = add_card(slide, s_left, top_pos, col_w, Inches(1.70), bg_color=RGBColor(248, 250, 252), border_color=COLOR_BORDER)
        
        # Color bar top
        sbar = slide.shapes.add_shape(MSO_SHAPE.RECTANGLE, s_left, top_pos, col_w, Inches(0.06))
        sbar.fill.solid()
        sbar.fill.fore_color.rgb = s_col
        sbar.line.fill.background()

        stf = sb.text_frame
        stf.word_wrap = True
        stf.margin_left = stf.margin_right = Inches(0.15)
        stf.margin_top = Inches(0.12)

        sp1 = stf.paragraphs[0]
        sr1 = sp1.add_run()
        sr1.text = s_tag
        sr1.font.name = FONT_NAME
        sr1.font.size = Pt(7.5)
        sr1.font.bold = True
        sr1.font.color.rgb = COLOR_MUTED

        sp2 = stf.add_paragraph()
        sr2 = sp2.add_run()
        sr2.text = s_name
        sr2.font.name = FONT_NAME
        sr2.font.size = Pt(11)
        sr2.font.bold = True
        sr2.font.color.rgb = COLOR_PRIMARY_NAVY

        sp3 = stf.add_paragraph()
        sr3 = sp3.add_run()
        sr3.text = s_role
        sr3.font.name = FONT_NAME
        sr3.font.size = Pt(8.5)
        sr3.font.bold = True
        sr3.font.color.rgb = s_col

        sp4 = stf.add_paragraph()
        sp4.space_before = Pt(4)
        sr4 = sp4.add_run()
        sr4.text = s_sub
        sr4.font.name = FONT_NAME
        sr4.font.size = Pt(7.8)
        sr4.font.color.rgb = COLOR_SLATE


# ===========================================================================
# MAIN EXECUTION
# ===========================================================================

def generate_presentation():
    print(f"Loading template from: {TEMPLATE_FILE}")
    prs = pptx.Presentation(TEMPLATE_FILE)

    # Remove existing sample slide from template
    rId = prs.slides._sldIdLst[0].rId
    prs.part.drop_rel(rId)
    del prs.slides._sldIdLst[0]
    print("Cleared initial template slide.")

    # Use Blank layout from master (Layout index 4)
    blank_layout = prs.slide_layouts[4]

    builders = [
        ("Slide 1: Executive Title & Cover", build_slide_1),
        ("Slide 2: Executive Command Dashboard", build_slide_2),
        ("Slide 3: Commercial Sales Pipeline (Pillar 1)", build_slide_3),
        ("Slide 4: Active Projects & Financials (Pillars 2 & 3)", build_slide_4),
        ("Slide 5: Site Cash Flow & Expense Governance", build_slide_5),
        ("Slide 6: Deal-to-Execution Flowchart", build_slide_6),
        ("Slide 7: Enterprise Architecture & Roadmap", build_slide_7),
        ("Slide 8: Immediate Action Items & Sign-Off", build_slide_8)
    ]

    for title, builder_func in builders:
        print(f"Building {title}...")
        slide = prs.slides.add_slide(blank_layout)
        builder_func(slide)

    print(f"Saving generated presentation to: {OUTPUT_FILE}")
    prs.save(OUTPUT_FILE)
    print("Presentation generated successfully!")

if __name__ == "__main__":
    generate_presentation()


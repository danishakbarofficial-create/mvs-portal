import os
import pptx
from pptx.util import Inches, Pt
from pptx.dml.color import RGBColor
from pptx.enum.shapes import MSO_SHAPE
from pptx.enum.text import PP_ALIGN, MSO_ANCHOR

# ---------------------------------------------------------------------------
# COLOR PALETTE & STYLES (MVS BRAND IDENTITY)
# ---------------------------------------------------------------------------
COLOR_PRIMARY_NAVY = RGBColor(45, 26, 82)    # #2D1A52 (Deep Royal Navy/Purple)
COLOR_CYAN         = RGBColor(0, 175, 228)   # #00AFE4 (MVS Brand Accent Cyan)
COLOR_GOLD         = RGBColor(186, 150, 49)  # #BA9631 (MVS Gold)
COLOR_GOLD_LIGHT   = RGBColor(212, 176, 76)  # #D4B04C
COLOR_DARK         = RGBColor(15, 23, 42)    # #0F172A
COLOR_SLATE        = RGBColor(70, 84, 105)   # #465469 (Secondary Text)
COLOR_MUTED        = RGBColor(100, 116, 139) # #64748B
COLOR_LIGHT_BG     = RGBColor(248, 250, 252) # #F8FAFC
COLOR_BORDER       = RGBColor(225, 232, 239) # #E1E8EF
COLOR_WHITE        = RGBColor(255, 255, 255)

# Status & Card Fills
COLOR_WON_BG       = RGBColor(236, 253, 245) # #ECFDF5
COLOR_WON_TXT      = RGBColor(6, 95, 70)     # #065F46
COLOR_ALERT_BG     = RGBColor(254, 242, 242) # #FEF2F2
COLOR_ALERT_TXT    = RGBColor(153, 27, 27)   # #991B1B
COLOR_CARD_ALT     = RGBColor(241, 245, 249) # #F1F5F9

FONT_NAME = "Aptos"
BASE_DIR = os.path.dirname(os.path.abspath(__file__))
TEMPLATE_FILE = os.path.join(BASE_DIR, "MVS Weekly Meeting _ Format to develop.pptx")
OUTPUT_PPTX   = os.path.join(BASE_DIR, "MVS_Project_Pulse_GM_Roadmap.pptx")
LOGO_PATH     = os.path.join(BASE_DIR, "extracted_slide_img.png")

TOTAL_SLIDES = 5
CURRENT_DATE_STR = "October 2026 \u00b7 Executive Review"

def apply_header_footer(slide, category_text, title_text, subtitle_text, slide_num):
    """
    Renders corporate header & footer matching MVS Weekly Meeting master standard
    """
    # 1. Left Cyan Accent Bar
    bar = slide.shapes.add_shape(MSO_SHAPE.RECTANGLE, Inches(0.50), Inches(0.45), Inches(0.08), Inches(1.00))
    bar.fill.solid()
    bar.fill.fore_color.rgb = COLOR_CYAN
    bar.line.fill.background()

    # 2. Header Text Box
    tb = slide.shapes.add_textbox(Inches(0.74), Inches(0.38), Inches(9.80), Inches(1.18))
    tf = tb.text_frame
    tf.word_wrap = True
    tf.margin_left = tf.margin_top = tf.margin_right = tf.margin_bottom = 0

    # Category / Breadcrumb
    p1 = tf.paragraphs[0]
    p1.space_after = Pt(1)
    r1 = p1.add_run()
    r1.text = f"M V S   \u00b7   {category_text}"
    r1.font.name = FONT_NAME
    r1.font.size = Pt(10.5)
    r1.font.bold = True
    r1.font.color.rgb = COLOR_CYAN

    # Title
    p2 = tf.add_paragraph()
    p2.space_after = Pt(2)
    r2 = p2.add_run()
    r2.text = title_text
    r2.font.name = FONT_NAME
    r2.font.size = Pt(21)
    r2.font.bold = True
    r2.font.color.rgb = COLOR_PRIMARY_NAVY

    # Subtitle
    p3 = tf.add_paragraph()
    r3_date = p3.add_run()
    r3_date.text = CURRENT_DATE_STR
    r3_date.font.name = FONT_NAME
    r3_date.font.size = Pt(10.5)
    r3_date.font.bold = True
    r3_date.font.color.rgb = COLOR_SLATE

    if subtitle_text:
        r3_sub = p3.add_run()
        r3_sub.text = f"   \u2014   {subtitle_text}"
        r3_sub.font.name = FONT_NAME
        r3_sub.font.size = Pt(10)
        r3_sub.font.bold = False
        r3_sub.font.color.rgb = COLOR_SLATE

    # 3. Logo Top Right
    if os.path.exists(LOGO_PATH):
        slide.shapes.add_picture(LOGO_PATH, Inches(11.53), Inches(0.12), width=Inches(1.165), height=Inches(1.286))

    # 4. Dividing Rule
    line = slide.shapes.add_shape(MSO_SHAPE.RECTANGLE, Inches(0.60), Inches(1.68), Inches(12.13), Pt(1))
    line.fill.solid()
    line.fill.fore_color.rgb = COLOR_BORDER
    line.line.fill.background()

    # 5. Footers
    ft_left = slide.shapes.add_textbox(Inches(0.60), Inches(7.00), Inches(8.00), Inches(0.35))
    ft_left.text_frame.margin_left = ft_left.text_frame.margin_top = 0
    p_fl = ft_left.text_frame.paragraphs[0]
    r_fl = p_fl.add_run()
    r_fl.text = "Modern Vision Solutions \u00b7 MVS ICT & Telecom \u2014 Commercial Lifecycle & Execution Roadmap"
    r_fl.font.name = FONT_NAME
    r_fl.font.size = Pt(8.5)
    r_fl.font.color.rgb = COLOR_MUTED

    ft_right = slide.shapes.add_textbox(Inches(8.50), Inches(7.00), Inches(4.23), Inches(0.35))
    ft_right.text_frame.margin_right = ft_right.text_frame.margin_top = 0
    p_fr = ft_right.text_frame.paragraphs[0]
    p_fr.alignment = PP_ALIGN.RIGHT
    r_fr = p_fr.add_run()
    r_fr.text = f"Slide {slide_num} of {TOTAL_SLIDES}  |  Executive Review  |  Confidential"
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

# ===========================================================================
# SLIDE 1: EXECUTIVE BRIEF & STRATEGIC SHIFT (EXECUTIVE DIRECTIVE)
# ===========================================================================
def build_slide_1(slide):
    apply_header_footer(slide, "Executive Directive & Architecture", 
                        "Commercial Lifecycle Transition & Executive Overview",
                        "Aligning MVS ICT & Telecom with the Corporate Operational Blueprint", 1)

    top_pos = Inches(1.88)

    # Left Container: The Strategic Shift (Why we eliminate daily logs)
    w_left = Inches(5.85)
    h_top = Inches(4.90)
    card_left = add_card(slide, Inches(0.60), top_pos, w_left, h_top, bg_color=COLOR_WHITE, border_color=COLOR_PRIMARY_NAVY, border_pt=1.5)
    
    # Left Card Header Strip
    bar_left = slide.shapes.add_shape(MSO_SHAPE.RECTANGLE, Inches(0.60), top_pos, w_left, Inches(0.48))
    bar_left.fill.solid()
    bar_left.fill.fore_color.rgb = COLOR_PRIMARY_NAVY
    bar_left.line.fill.background()
    ltf = bar_left.text_frame
    ltf.margin_left = Inches(0.18)
    ltf.margin_top = Inches(0.10)
    lp = ltf.paragraphs[0]
    lr = lp.add_run()
    lr.text = "\u25c6 THE EXECUTIVE DIRECTIVE: DUAL-REGISTER ARCHITECTURE"
    lr.font.name = FONT_NAME
    lr.font.size = Pt(9.5)
    lr.font.bold = True
    lr.font.color.rgb = COLOR_CYAN

    # Left Content Text
    ltb = slide.shapes.add_textbox(Inches(0.80), top_pos + Inches(0.60), w_left - Inches(0.40), h_top - Inches(0.75))
    tf_l = ltb.text_frame
    tf_l.word_wrap = True
    tf_l.margin_left = tf_l.margin_top = tf_l.margin_right = tf_l.margin_bottom = 0

    points = [
        ("The Problem with Legacy Daily Logs:", 
         "Traditional sales logs required tedious micro-logging of everyday sales visits. Sales engineers spent time typing routine diary notes rather than driving commercial velocity."),
        ("Dual-Register Foundation (Asayil Sheet Baseline):", 
         "MVS operations split cleanly into 2 interconnected modules: (1) 'Projects Under Study Register' for pre-pricing & RFP evaluation, and (2) 'Priced Projects Register' for live commercial tracking."),
        ("Strict 5-Stage Decision Gatekeeper:", 
         "Sales owners must classify deals into 5 strict stages: (1) Information Gathering, (2) Under Review, (3) Pricing in Progress, (4) Quotation Submitted, (5) On Hold."),
        ("End-to-End Cash Realization Lifecycle:", 
         "Under Study \u2794 Submission Date \u2794 Quotation Status \u2794 PO Award \u2794 Execution Duration \u2794 Invoicing \u2794 Cash Collection.")
    ]

    for idx, (p_title, p_desc) in enumerate(points):
        p = tf_l.paragraphs[0] if idx == 0 else tf_l.add_paragraph()
        if idx > 0:
            p.space_before = Pt(8)
        r_title = p.add_run()
        r_title.text = f"\u25b8 {p_title} "
        r_title.font.name = FONT_NAME
        r_title.font.size = Pt(9)
        r_title.font.bold = True
        r_title.font.color.rgb = COLOR_PRIMARY_NAVY

        r_body = p.add_run()
        r_body.text = p_desc
        r_body.font.name = FONT_NAME
        r_body.font.size = Pt(8.5)
        r_body.font.color.rgb = COLOR_SLATE

    # Right Container: 3 Core Pillars of Value for GM
    w_right = Inches(6.05)
    left_r = Inches(6.68)
    card_r = add_card(slide, left_r, top_pos, w_right, h_top, bg_color=COLOR_WHITE, border_color=COLOR_BORDER)

    bar_right = slide.shapes.add_shape(MSO_SHAPE.RECTANGLE, left_r, top_pos, w_right, Inches(0.48))
    bar_right.fill.solid()
    bar_right.fill.fore_color.rgb = COLOR_GOLD
    bar_right.line.fill.background()
    rtf = bar_right.text_frame
    rtf.margin_left = Inches(0.18)
    rtf.margin_top = Inches(0.10)
    rp = rtf.paragraphs[0]
    rr = rp.add_run()
    rr.text = "\u25c6 EXECUTIVE OUTCOMES DELIVERED TO LEADERSHIP"
    rr.font.name = FONT_NAME
    rr.font.size = Pt(9.5)
    rr.font.bold = True
    rr.font.color.rgb = COLOR_WHITE

    # 3 Pillars on Right
    p_boxes = [
        ("1. Zero Lost Tenders & Missing Information Control", COLOR_CYAN,
         "Standardized Missing Information tracker (BOQ, Specs, Budget, Drawings) ensures zero incomplete quotations are released. 5-stage gate stops deals from stalling."),
        ("2. Pre-Sales Technical Accountability & Execution Window", COLOR_PRIMARY_NAVY,
         "RFP compliance matrices are locked to designated Sales Owners. Captures 'Estimated Execution Duration' prior to operational handover, preventing delivery disputes."),
        ("3. 3 Dedicated Dashboards: Quotation, PO & Collection", COLOR_WON_TXT,
         "Leadership gets instant clarity via 3 live executive command tabs: Quotations status, PO award conversion %, and Billing vs. Actual Cash Collection (SAR).")
    ]

    box_h = Inches(1.28)
    gap_y = Inches(0.14)
    for idx, (b_title, b_col, b_desc) in enumerate(p_boxes):
        y_pos = top_pos + Inches(0.58) + idx * (box_h + gap_y)
        sb = add_card(slide, left_r + Inches(0.18), y_pos, w_right - Inches(0.36), box_h, bg_color=COLOR_LIGHT_BG, border_color=COLOR_BORDER)
        
        # Left Accent
        acc = slide.shapes.add_shape(MSO_SHAPE.RECTANGLE, left_r + Inches(0.18), y_pos, Inches(0.08), box_h)
        acc.fill.solid()
        acc.fill.fore_color.rgb = b_col
        acc.line.fill.background()

        sbtf = sb.text_frame
        sbtf.word_wrap = True
        sbtf.margin_left = Inches(0.18)
        sbtf.margin_top = Inches(0.12)
        sbtf.margin_right = Inches(0.12)

        sp1 = sbtf.paragraphs[0]
        sr1 = sp1.add_run()
        sr1.text = b_title
        sr1.font.name = FONT_NAME
        sr1.font.size = Pt(9.5)
        sr1.font.bold = True
        sr1.font.color.rgb = COLOR_PRIMARY_NAVY

        sp2 = sbtf.add_paragraph()
        sp2.space_before = Pt(3)
        sr2 = sp2.add_run()
        sr2.text = b_desc
        sr2.font.name = FONT_NAME
        sr2.font.size = Pt(8.3)
        sr2.font.color.rgb = COLOR_SLATE

# ===========================================================================
# SLIDE 2: THE 7-STAGE COMMERCIAL LIFECYCLE FLOWCHART
# ===========================================================================
def build_slide_2(slide):
    apply_header_footer(slide, "Process Architecture", 
                        "The 7-Stage End-to-End Commercial Lifecycle",
                        "From Project Study & Missing Info to Quotation Submission, PO Award & Cash Collection", 2)

    top_pos = Inches(1.88)
    card_w = Inches(1.57)
    card_h = Inches(3.05)
    gap_x  = Inches(0.16)

    stages = [
        ("1. Pre-RFQ / RFP", "Lead & Intake", COLOR_CYAN, [
            ("Sales Owner", "Assigned sales engineer."),
            ("Client & Contact", "Name, Entity, Designation."),
            ("Missing Info", "Scope, BOQ, Drawings, Budget."),
            ("5 Decision Gates", "Gathering, Review, Pricing...")
        ]),
        ("2. RFQ / RFP", "Technical Prep", COLOR_PRIMARY_NAVY, [
            ("Submission Date", "Official customer deadline."),
            ("Routing Type", "Fast RFQ vs Technical RFP."),
            ("Tech Pack", "Datasheets & Compliance."),
            ("Pricing & VAT", "Commercial evaluation SAR.")
        ]),
        ("3. Quotation", "Commercial Offer", COLOR_GOLD, [
            ("Quotation No.", "Official MVS Quote reference."),
            ("Quote Status", "Open, On Hold, Approved..."),
            ("Price Validity", "Validity expiration clock."),
            ("Commercial SAR", "Margin & total pricing lock.")
        ]),
        ("4. Follow-up", "Negotiation", COLOR_PRIMARY_NAVY, [
            ("Client Response", "Scope & pricing discussion."),
            ("Aging Clock", "Flags inactive quotes > 10d."),
            ("Win Probability", "High / Medium / Tender rate."),
            ("Next Step Log", "Action & follow-up date.")
        ]),
        ("5. PO Award", "Contract Won", COLOR_WON_TXT, [
            ("PO Status", "Open, Approved, Closed..."),
            ("PO Number", "Verified customer PO code."),
            ("Execution Window", "Est. Execution Duration locked."),
            ("Resp. Person", "Handover to Operations lead.")
        ]),
        ("6. Invoicing", "Billing Event", COLOR_PRIMARY_NAVY, [
            ("Invoice No.", "Official tax invoice logged."),
            ("Invoice Date", "Tax date & terms tracking."),
            ("Billed SAR", "Progress milestone amount."),
            ("Invoice Status", "Not Invoiced, Partial, Paid.")
        ]),
        ("7. Collection", "Cash Realized", COLOR_GOLD_LIGHT, [
            ("Collection Date", "Exact bank receipt date."),
            ("Collected SAR", "Realized cash in bank."),
            ("Collection Status", "Pending, Partial, Collected."),
            ("Outstanding Bal.", "Residual receivables tracking.")
        ])
    ]

    for idx, (s_num, s_sub, s_col, bullets) in enumerate(stages):
        x_pos = Inches(0.60) + idx * (card_w + gap_x)
        c = add_card(slide, x_pos, top_pos, card_w, card_h, bg_color=COLOR_WHITE, border_color=COLOR_BORDER)

        # Header Strip
        hbar = slide.shapes.add_shape(MSO_SHAPE.RECTANGLE, x_pos, top_pos, card_w, Inches(0.55))
        hbar.fill.solid()
        hbar.fill.fore_color.rgb = s_col
        hbar.line.fill.background()

        htf = hbar.text_frame
        htf.margin_left = Inches(0.08)
        htf.margin_top = Inches(0.06)
        hp1 = htf.paragraphs[0]
        hr1 = hp1.add_run()
        hr1.text = s_num
        hr1.font.name = FONT_NAME
        hr1.font.size = Pt(8.8)
        hr1.font.bold = True
        hr1.font.color.rgb = COLOR_WHITE

        hp2 = htf.add_paragraph()
        hr2 = hp2.add_run()
        hr2.text = s_sub
        hr2.font.name = FONT_NAME
        hr2.font.size = Pt(7.5)
        hr2.font.color.rgb = RGBColor(230, 240, 255)

        # Body bullets
        btb = slide.shapes.add_textbox(x_pos + Inches(0.06), top_pos + Inches(0.60), card_w - Inches(0.12), card_h - Inches(0.68))
        btf = btb.text_frame
        btf.word_wrap = True
        btf.margin_left = btf.margin_top = btf.margin_right = btf.margin_bottom = 0

        for b_idx, (b_hdr, b_sub) in enumerate(bullets):
            p = btf.paragraphs[0] if b_idx == 0 else btf.add_paragraph()
            if b_idx > 0:
                p.space_before = Pt(3)
            r_h = p.add_run()
            r_h.text = f"\u2022 {b_hdr}\n"
            r_h.font.name = FONT_NAME
            r_h.font.size = Pt(7.5)
            r_h.font.bold = True
            r_h.font.color.rgb = COLOR_PRIMARY_NAVY

            r_s = p.add_run()
            r_s.text = f"  {b_sub}"
            r_s.font.name = FONT_NAME
            r_s.font.size = Pt(7.0)
            r_s.font.color.rgb = COLOR_SLATE

        # Arrow connector between cards
        if idx < 6:
            arr = slide.shapes.add_shape(MSO_SHAPE.CHEVRON, x_pos + card_w + Inches(0.03), top_pos + Inches(1.35), Inches(0.10), Inches(0.35))
            arr.fill.solid()
            arr.fill.fore_color.rgb = COLOR_CYAN
            arr.line.fill.background()

    # Bottom Banner: The RFQ vs RFP Critical Operational Distinction
    bot_card = add_card(slide, Inches(0.60), Inches(5.12), Inches(12.13), Inches(1.68), 
                        bg_color=RGBColor(248, 250, 252), border_color=COLOR_PRIMARY_NAVY, border_pt=1.2)
    
    # Left tag box
    t_tag = slide.shapes.add_shape(MSO_SHAPE.RECTANGLE, Inches(0.60), Inches(5.12), Inches(2.70), Inches(1.68))
    t_tag.fill.solid()
    t_tag.fill.fore_color.rgb = COLOR_PRIMARY_NAVY
    t_tag.line.fill.background()
    tt = t_tag.text_frame
    tt.margin_left = Inches(0.15)
    tt.margin_top = Inches(0.15)
    ttp1 = tt.paragraphs[0]
    ttr1 = ttp1.add_run()
    ttr1.text = "CRITICAL DISTINCTION\nRFQ vs. RFP"
    ttr1.font.name = FONT_NAME
    ttr1.font.size = Pt(11)
    ttr1.font.bold = True
    ttr1.font.color.rgb = COLOR_CYAN

    ttp2 = tt.add_paragraph()
    ttp2.space_before = Pt(4)
    ttr2 = ttp2.add_run()
    ttr2.text = "Tailored workflows prevent technical bottlenecks and ensure zero bid disqualification."
    ttr2.font.name = FONT_NAME
    ttr2.font.size = Pt(8.2)
    ttr2.font.color.rgb = RGBColor(220, 230, 245)

    # 2 Comparison Columns on right of bottom banner (Total available width: 12.13 - 2.80 = 9.33 inches)
    # Column 1: left=3.50, width=4.40
    # Column 2: left=8.10, width=4.40
    comp_items = [
        (Inches(3.50), Inches(4.35), "Standard RFQ (Commercial Inquiry)", COLOR_CYAN, [
            "Straightforward Bill of Materials (BoQ) or box-moving products (e.g. VIAVI units, Jetting tools, standard cabinets).",
            "Pre-Sales action: Rapid price check with vendor \u2794 Issue commercial quotation within 24-48 hours.",
            "Minimal technical documentation required \u2014 optimized for fast turnaround."
        ]),
        (Inches(8.15), Inches(4.35), "Technical RFP (Tender / Complex Solution)", COLOR_GOLD, [
            "Comprehensive turnkey projects (Low Current, Telecom infrastructure, custom enterprise deployments).",
            "Pre-Sales action: Rigorous technical compliance matrix, engineering datasheets, client vendor approvals.",
            "Technical sign-off required prior to releasing commercial quotation to eliminate contractual risk."
        ])
    ]

    for c_x, c_w, c_hdr, c_col, c_bullets in comp_items:
        tb = slide.shapes.add_textbox(c_x, Inches(5.18), c_w, Inches(1.55))
        tf = tb.text_frame
        tf.word_wrap = True
        tf.margin_left = tf.margin_top = tf.margin_right = tf.margin_bottom = 0

        p1 = tf.paragraphs[0]
        r1 = p1.add_run()
        r1.text = f"\u25c6 {c_hdr}"
        r1.font.name = FONT_NAME
        r1.font.size = Pt(9.2)
        r1.font.bold = True
        r1.font.color.rgb = COLOR_PRIMARY_NAVY

        for b in c_bullets:
            pb = tf.add_paragraph()
            pb.space_before = Pt(2)
            rb = pb.add_run()
            rb.text = f"\u2022 {b}"
            rb.font.name = FONT_NAME
            rb.font.size = Pt(7.5)
            rb.font.color.rgb = COLOR_SLATE

# ===========================================================================
# SLIDE 3: EXECUTIVE GOVERNANCE & PROBLEM SOLVING (THE 4 PILLARS)
# ===========================================================================
def build_slide_3(slide):
    apply_header_footer(slide, "Executive Controls & Intelligence", 
                        "Strategic Value-Adds & Management Controls",
                        "How the System Protects Revenue, Eliminates Chasing & Solves Real Sales Bottlenecks", 3)

    top_pos = Inches(1.88)
    card_w = Inches(5.85)
    card_h = Inches(2.35)
    gap_y  = Inches(0.20)

    left_c1 = Inches(0.60)
    left_c2 = Inches(6.88)

    features = [
        (left_c1, top_pos, "1. 5-Stage Decision Gate & Missing Info Engine", COLOR_PRIMARY_NAVY, [
            ("The Challenge", "Projects sit in limbo because sales engineers forget what information is missing from the client."),
            ("The Solution", "Mandatory 5-stage gate (Information Gathering \u2794 Under Review \u2794 Pricing \u2794 Submitted \u2794 On Hold) paired with a Missing Info checklist (Scope, BOQ, Drawings, Budget)."),
            ("Executive Benefit", "Executive leadership gets an instant filter on proposal bottlenecks without micromanaging daily sales notes.")
        ]),
        (left_c2, top_pos, "2. Execution Duration & Responsible Person Handover", COLOR_GOLD, [
            ("The Challenge", "Once a PO is signed, sales moves on and delivery timelines are unclear to Operations and Finance."),
            ("The Solution", "System locks 'Estimated Execution Duration' and designates a 'Responsible Person' at the exact point of PO award before commercial handoff."),
            ("Executive Benefit", "Clear accountability bridges the gap between commercial sales and operational site delivery.")
        ]),
        (left_c1, top_pos + card_h + gap_y, "3. 3 Dedicated Command Dashboards (Quotes \u00b7 POs \u00b7 Collections)", COLOR_CYAN, [
            ("The Challenge", "Executives are overwhelmed when quotations, won orders, and unpaid invoices are dumped into a single cluttered sheet."),
            ("The Solution", "3 dedicated live executive dashboard tabs: (1) Quotations (Volume & Approval Rate), (2) Purchase Orders (Conversion % & Value), and (3) Invoicing & Collection."),
            ("Executive Benefit", "Executive decision-makers can jump straight to their relevant focus area with automated status breakdowns and visual charts.")
        ]),
        (left_c2, top_pos + card_h + gap_y, "4. Realized Cash Collection & Debtor Aging (Cashflow Focus)", COLOR_WON_TXT, [
            ("The Challenge", "Winning a PO is meaningless if billed invoices remain uncollected for 90+ days in the market."),
            ("The Solution", "System tracks every billed invoice against exact Collection Dates, Collected Cash (SAR), and Outstanding Receivables with automated collection status."),
            ("Executive Benefit", "Gives executive leadership immediate clarity on realized liquidity, bank deposits, and client overdue debts.")
        ])
    ]

    for x_pos, y_pos, f_title, f_col, f_bullets in features:
        fc = add_card(slide, x_pos, y_pos, card_w, card_h, bg_color=COLOR_WHITE, border_color=COLOR_BORDER)

        # Header bar
        f_bar = slide.shapes.add_shape(MSO_SHAPE.RECTANGLE, x_pos, y_pos, card_w, Inches(0.40))
        f_bar.fill.solid()
        f_bar.fill.fore_color.rgb = f_col if f_col != COLOR_ALERT_TXT else COLOR_PRIMARY_NAVY
        f_bar.line.fill.background()

        ftf = f_bar.text_frame
        ftf.margin_left = Inches(0.15)
        ftf.margin_top = Inches(0.08)
        fp = ftf.paragraphs[0]
        fr = fp.add_run()
        fr.text = f_title
        fr.font.name = FONT_NAME
        fr.font.size = Pt(9.5)
        fr.font.bold = True
        fr.font.color.rgb = COLOR_WHITE

        # Bullets
        ftb = slide.shapes.add_textbox(x_pos + Inches(0.15), y_pos + Inches(0.48), card_w - Inches(0.30), card_h - Inches(0.55))
        btf = ftb.text_frame
        btf.word_wrap = True
        btf.margin_left = btf.margin_top = btf.margin_right = btf.margin_bottom = 0

        for b_idx, (b_t, b_d) in enumerate(f_bullets):
            p = btf.paragraphs[0] if b_idx == 0 else btf.add_paragraph()
            if b_idx > 0:
                p.space_before = Pt(3)
            r_t = p.add_run()
            r_t.text = f"\u25b8 {b_t}: "
            r_t.font.name = FONT_NAME
            r_t.font.size = Pt(8.2)
            r_t.font.bold = True
            r_t.font.color.rgb = COLOR_PRIMARY_NAVY

            r_d = p.add_run()
            r_d.text = b_d
            r_d.font.name = FONT_NAME
            r_d.font.size = Pt(8.0)
            r_d.font.color.rgb = COLOR_SLATE

# ===========================================================================
# SLIDE 4: STRATEGIC SYSTEM RECOMMENDATIONS FOR SALES ACCELERATION
# ===========================================================================
def build_slide_4(slide):
    apply_header_footer(slide, "Strategic Recommendations & Value Enhancement", 
                        "System Recommendations to Accelerate Sales & Cash Velocity",
                        "High-Impact Enhancements to Protect Revenue, Expand Pipeline & Maximize Win Rates", 4)

    top_pos = Inches(1.88)
    card_w = Inches(5.85)
    card_h = Inches(2.35)
    gap_y  = Inches(0.20)

    left_c1 = Inches(0.60)
    left_c2 = Inches(6.88)

    recommendations = [
        (left_c1, top_pos, "1. Win / Loss Analytics & Competitor Pricing", COLOR_PRIMARY_NAVY, [
            ("Core Enhancement", "Mandatory structured outcome reason required for all closed deals: Competitor Price, Technical Specs, Client Budget Shelved, or Delayed Bid."),
            ("Impact on Sales", "Provides exact market intelligence on why tenders are won or lost, preventing recurring commercial bidding mistakes."),
            ("Executive Benefit", "Equips executive management with objective quarterly data to fine-tune vendor discount requests and product margins.")
        ]),
        (left_c2, top_pos, "2. Government Tender & Portal Tracking (Etimad / STC)", COLOR_GOLD, [
            ("Core Enhancement", "Mandatory 'Tender / Portal Ref #' field in Study Register linked to Etimad, STC Rawafed, and Aramco Ariba portals."),
            ("Impact on Sales", "Ensures technical submission deadlines, query periods, and bid bond validities are never missed on high-value public tenders."),
            ("Executive Benefit", "Systematic pipeline capture of Saudi Vision 2030 megaprojects with structured milestone alerts.")
        ]),
        (left_c1, top_pos + card_h + gap_y, "3. Retention Money & Milestone Balance Visibility", COLOR_CYAN, [
            ("Core Enhancement", "Dedicated 'Retention Balance (5-10%)' tracker separating contractual warranty retentions from aged overdue receivables."),
            ("Impact on Sales", "Enables sales engineers to trigger automatic release claims immediately upon project handover & PAC / FAC issuance."),
            ("Executive Benefit", "Gives executive leadership and Finance accurate visibility of true realizable cashflow vs. deferred contract holdbacks.")
        ]),
        (left_c2, top_pos + card_h + gap_y, "4. Fast-Track RFQ Automation & Vendor Pricing Library", COLOR_WON_TXT, [
            ("Core Enhancement", "Pre-approved price list & margin templates for standard volume products (VIAVI test kits, Jetting tools, standard cabinets)."),
            ("Impact on Sales", "Cuts standard quotation turnaround from 48 hours down to under 2 hours, winning deals before competitors respond."),
            ("Executive Benefit", "Dramatically increases quotation volume, win ratios, and sales velocity without increasing administrative overhead.")
        ])
    ]

    for x_pos, y_pos, r_title, r_col, r_bullets in recommendations:
        fc = add_card(slide, x_pos, y_pos, card_w, card_h, bg_color=COLOR_WHITE, border_color=COLOR_BORDER)

        # Header bar
        r_bar = slide.shapes.add_shape(MSO_SHAPE.RECTANGLE, x_pos, y_pos, card_w, Inches(0.40))
        r_bar.fill.solid()
        r_bar.fill.fore_color.rgb = r_col
        r_bar.line.fill.background()

        rtf = r_bar.text_frame
        rtf.margin_left = Inches(0.15)
        rtf.margin_top = Inches(0.08)
        rp = rtf.paragraphs[0]
        rr = rp.add_run()
        rr.text = r_title
        rr.font.name = FONT_NAME
        rr.font.size = Pt(9.5)
        rr.font.bold = True
        rr.font.color.rgb = COLOR_WHITE

        # Bullets
        rtb = slide.shapes.add_textbox(x_pos + Inches(0.15), y_pos + Inches(0.48), card_w - Inches(0.30), card_h - Inches(0.55))
        btf = rtb.text_frame
        btf.word_wrap = True
        btf.margin_left = btf.margin_top = btf.margin_right = btf.margin_bottom = 0

        for b_idx, (b_t, b_d) in enumerate(r_bullets):
            p = btf.paragraphs[0] if b_idx == 0 else btf.add_paragraph()
            if b_idx > 0:
                p.space_before = Pt(3)
            r_t = p.add_run()
            r_t.text = f"\u25b8 {b_t}: "
            r_t.font.name = FONT_NAME
            r_t.font.size = Pt(8.2)
            r_t.font.bold = True
            r_t.font.color.rgb = COLOR_PRIMARY_NAVY

            r_d = p.add_run()
            r_d.text = b_d
            r_d.font.name = FONT_NAME
            r_d.font.size = Pt(8.0)
            r_d.font.color.rgb = COLOR_SLATE

# ===========================================================================
# SLIDE 5: RAPID 4-WEEK ROADMAP & EXECUTIVE SIGN-OFF
# ===========================================================================
def build_slide_5(slide):
    apply_header_footer(slide, "Delivery Plan & Next Steps", 
                        "Implementation Roadmap & Immediate Next Steps",
                        "Phased Fast-Track Rollout, Live Pilot Integration & Executive Decision Matrix", 5)

    top_pos = Inches(1.88)
    
    # 4 Roadmap Phase Cards
    col_w = Inches(2.88)
    gap_x = Inches(0.20)
    card_h = Inches(2.75)

    phases = [
        ("Phase 1: Dual Registers", "Week 1 (Immediate)", COLOR_CYAN, [
            ("Projects Under Study", "5-stage decision gates, missing info engine & sales owner assignments."),
            ("Priced Projects Register", "Submission date, pricing with VAT calculation, quote validity countdown."),
            ("Quotation Status Tracker", "Standardized dropdown status workflow (Open, On Hold, Approved, Cancelled).")
        ]),
        ("Phase 2: Cash & Execution", "Week 2", COLOR_PRIMARY_NAVY, [
            ("PO Award & Duration", "Customer PO verification, locked Execution Duration & ops handover."),
            ("Milestone Invoicing", "Tax invoice registration, milestone tracking, and payment terms."),
            ("Collection Tracker", "Realized cash received (SAR), bank collection dates & pending receivables.")
        ]),
        ("Phase 3: Executive Dashboards", "Week 3", COLOR_GOLD, [
            ("Quotation Dashboard", "Total quoted value, live pipeline counts & win/loss approval rate %."),
            ("PO & Order Dashboard", "PO volume, contractual value, and Quotation-to-PO conversion %."),
            ("Collection Command Tab", "Live billed vs realized cashflow, debtor aging & 1-Click executive PDF.")
        ]),
        ("Phase 4: Pilot & Launch", "Week 4", COLOR_WON_TXT, [
            ("Live Data Migration", "Import active proposals from existing project register & open client RFQs."),
            ("Sales Team Enablement", "30-min hands-on walk-through showing frictionless updates (zero diary logs)."),
            ("Leadership Sign-Off", "MVS ICT & Telecom goes live as primary commercial command center.")
        ])
    ]

    for idx, (p_title, p_time, p_col, deliverables) in enumerate(phases):
        x_pos = Inches(0.60) + idx * (col_w + gap_x)
        c = add_card(slide, x_pos, top_pos, col_w, card_h, bg_color=COLOR_WHITE, border_color=COLOR_BORDER)

        # Header Strip
        hbar = slide.shapes.add_shape(MSO_SHAPE.RECTANGLE, x_pos, top_pos, col_w, Inches(0.50))
        hbar.fill.solid()
        hbar.fill.fore_color.rgb = p_col
        hbar.line.fill.background()

        htf = hbar.text_frame
        htf.margin_left = Inches(0.12)
        htf.margin_top = Inches(0.06)
        hp = htf.paragraphs[0]
        hr = hp.add_run()
        hr.text = f"{p_title}\n"
        hr.font.name = FONT_NAME
        hr.font.size = Pt(8.8)
        hr.font.bold = True
        hr.font.color.rgb = COLOR_WHITE

        hr_sub = hp.add_run()
        hr_sub.text = p_time
        hr_sub.font.name = FONT_NAME
        hr_sub.font.size = Pt(7.5)
        hr_sub.font.color.rgb = RGBColor(230, 240, 255)

        # Deliverables
        dtb = slide.shapes.add_textbox(x_pos + Inches(0.10), top_pos + Inches(0.58), col_w - Inches(0.20), card_h - Inches(0.65))
        dtf = dtb.text_frame
        dtf.word_wrap = True
        dtf.margin_left = dtf.margin_top = dtf.margin_right = dtf.margin_bottom = 0

        for d_idx, (d_hdr, d_desc) in enumerate(deliverables):
            p = dtf.paragraphs[0] if d_idx == 0 else dtf.add_paragraph()
            if d_idx > 0:
                p.space_before = Pt(4)
            r_h = p.add_run()
            r_h.text = f"\u25b8 {d_hdr}\n"
            r_h.font.name = FONT_NAME
            r_h.font.size = Pt(8.0)
            r_h.font.bold = True
            r_h.font.color.rgb = COLOR_PRIMARY_NAVY

            r_d = p.add_run()
            r_d.text = f"  {d_desc}"
            r_d.font.name = FONT_NAME
            r_d.font.size = Pt(7.5)
            r_d.font.color.rgb = COLOR_SLATE

    # Bottom Sign-Off Container
    sign_top = Inches(4.80)
    sign_card = add_card(slide, Inches(0.60), sign_top, Inches(12.13), Inches(2.00), 
                         bg_color=COLOR_WHITE, border_color=COLOR_PRIMARY_NAVY, border_pt=1.5)

    # 3 Sign-off Columns
    s_col_w = Inches(3.80)
    s_gap_x = Inches(0.35)

    sign_boxes = [
        ("PREPARED & PRESENTED BY", "Danish Akbar", "IT Support",
         "Modern Vision Solutions Ltd\nRiyadh, Kingdom of Saudi Arabia", COLOR_PRIMARY_NAVY),
        ("EXECUTIVE SANCTION & APPROVAL", "Executive Leadership", "Executive Management",
         "Approved to proceed with Phase 1 Deployment\nTarget Pilot Date: October 2026", COLOR_GOLD),
        ("OPERATIONAL COMMITMENT", "MVS ICT & Telecom", "Commercial Revenue Command Center",
         "Zero SaaS Licensing \u2022 100% In-House Proprietary\nConfidential \u2022 Internal Distribution Only", COLOR_CYAN)
    ]

    for idx, (s_tag, s_name, s_role, s_extra, tag_col) in enumerate(sign_boxes):
        s_left = Inches(0.85) + idx * (s_col_w + s_gap_x)
        stb = slide.shapes.add_textbox(s_left, sign_top + Inches(0.15), s_col_w, Inches(1.70))
        stf = stb.text_frame
        stf.word_wrap = True
        stf.margin_left = stf.margin_top = stf.margin_right = stf.margin_bottom = 0

        p1 = stf.paragraphs[0]
        r1 = p1.add_run()
        r1.text = s_tag
        r1.font.name = FONT_NAME
        r1.font.size = Pt(8.5)
        r1.font.bold = True
        r1.font.color.rgb = tag_col

        p2 = stf.add_paragraph()
        p2.space_before = Pt(4)
        r2 = p2.add_run()
        r2.text = s_name
        r2.font.name = FONT_NAME
        r2.font.size = Pt(12)
        r2.font.bold = True
        r2.font.color.rgb = COLOR_PRIMARY_NAVY

        p3 = stf.add_paragraph()
        r3 = p3.add_run()
        r3.text = s_role
        r3.font.name = FONT_NAME
        r3.font.size = Pt(9.5)
        r3.font.bold = False
        r3.font.color.rgb = COLOR_SLATE

        p4 = stf.add_paragraph()
        p4.space_before = Pt(4)
        r4 = p4.add_run()
        r4.text = s_extra
        r4.font.name = FONT_NAME
        r4.font.size = Pt(8.2)
        r4.font.color.rgb = COLOR_MUTED

# ===========================================================================
# MAIN EXECUTION BUILDER
# ===========================================================================
def main():
    print(f"Loading template: {TEMPLATE_FILE}")
    if os.path.exists(TEMPLATE_FILE):
        prs = pptx.Presentation(TEMPLATE_FILE)
        # Clear existing slides
        for i in range(len(prs.slides) - 1, -1, -1):
            rId = prs.slides._sldIdLst[i].rId
            prs.part.drop_rel(rId)
            del prs.slides._sldIdLst[i]
    else:
        prs = pptx.Presentation()
        prs.slide_width = Inches(13.333)
        prs.slide_height = Inches(7.50)

    # Use Blank layout (index 4) so no default 'Click to add title / subtitle' placeholders appear
    blank_layout = prs.slide_layouts[4] if len(prs.slide_layouts) > 4 else prs.slide_layouts[-1]

    print("Building Slide 1: Executive Directive & Dual-Register Architecture...")
    s1 = prs.slides.add_slide(blank_layout)
    build_slide_1(s1)

    print("Building Slide 2: The 7-Stage End-to-End Commercial Lifecycle...")
    s2 = prs.slides.add_slide(blank_layout)
    build_slide_2(s2)

    print("Building Slide 3: Strategic Value-Adds & Management Controls...")
    s3 = prs.slides.add_slide(blank_layout)
    build_slide_3(s3)

    print("Building Slide 4: Strategic Recommendations & Sales Acceleration...")
    s4 = prs.slides.add_slide(blank_layout)
    build_slide_4(s4)

    print("Building Slide 5: Phased 4-Week Delivery Roadmap & Sign-Off...")
    s5 = prs.slides.add_slide(blank_layout)
    build_slide_5(s5)

    prs.save(OUTPUT_PPTX)
    print(f"\n[SUCCESS] Saved updated 5-Slide GM Roadmap Presentation: {OUTPUT_PPTX}")

if __name__ == "__main__":
    main()

import os
import copy
import io
import pptx
from pptx.util import Inches, Pt
from pptx.dml.color import RGBColor
from pptx.enum.text import PP_ALIGN

BASE_DIR = r"C:\laragon\www\MVS Project Pulse"
TEMPLATE_PATH = os.path.join(BASE_DIR, "Presentation", "MVS Weekly Tasks and IT Assets Presentation Week 46.pptx")

OUTPUT_PPTX_WEEK47 = os.path.join(BASE_DIR, "Presentation", "MVS Weekly Tasks and IT Assets Presentation Week 47.pptx")
OUTPUT_PPTX_LATEST = os.path.join(BASE_DIR, "Presentation", "MVS Weekly Tasks and IT Assets Presentation.pptx")
DOWNLOADS_DIR = r"C:\Users\danis\Downloads\Week 47 presentations"
os.makedirs(DOWNLOADS_DIR, exist_ok=True)
OUTPUT_PPTX_DOWNLOADS = os.path.join(DOWNLOADS_DIR, "MVS Weekly Tasks and IT Assets Presentation Week 47.pptx")

ASSET_IMG_PATH = os.path.join(BASE_DIR, "Presentation", "updated asset dashboard week 46.png")

prs = pptx.Presentation(TEMPLATE_PATH)

# Color Constants
COLOR_CYAN = RGBColor(0, 175, 228)       # 00AFE4
COLOR_NAVY = RGBColor(45, 26, 82)        # 2D1A52
COLOR_DARK = RGBColor(30, 41, 59)        # 1E293B
COLOR_GRAY = RGBColor(71, 85, 105)       # 475569

COLOR_GREEN_BG = RGBColor(236, 253, 245) # ECFDF5
COLOR_GREEN_TEXT = RGBColor(6, 95, 70)   # 065F46

COLOR_BLUE_BG = RGBColor(224, 242, 254)  # E0F2FE
COLOR_BLUE_TEXT = RGBColor(3, 105, 161)  # 0369A1

COLOR_AMBER_BG = RGBColor(254, 243, 199) # FEF3C7
COLOR_AMBER_TEXT = RGBColor(146, 64, 14) # 92400E

COLOR_PURPLE_BG = RGBColor(243, 232, 255) # F3E8FF
COLOR_PURPLE_TEXT = RGBColor(107, 33, 168) # 6B21A8

# Helper function for concise bullets
def populate_bullets(shape, bullet_items, font_size=8.6, space_after=3.0, line_spacing=1.14):
    tf = shape.text_frame
    tf.word_wrap = True
    p_elements = tf._txBody.xpath("./a:p")
    for p_elem in p_elements[1:]:
        p_elem.getparent().remove(p_elem)
        
    p_first = tf.paragraphs[0]
    p_first.text = ""
    
    for idx, (label, desc) in enumerate(bullet_items):
        p = p_first if idx == 0 else tf.add_paragraph()
        p.space_after = Pt(space_after)
        p.line_spacing = line_spacing
        
        # Bullet symbol
        r1 = p.add_run()
        r1.text = "•  "
        r1.font.color.rgb = COLOR_CYAN
        r1.font.bold = True
        r1.font.size = Pt(font_size)
        
        # Headline label
        r2 = p.add_run()
        r2.text = label + ": "
        r2.font.color.rgb = COLOR_DARK
        r2.font.bold = True
        r2.font.size = Pt(font_size)
        
        # Description
        r3 = p.add_run()
        r3.text = desc
        r3.font.color.rgb = COLOR_GRAY
        r3.font.bold = False
        r3.font.size = Pt(font_size)

def set_badge(text_shape, text, text_color, font_size=7.8, bg_shape=None, bg_color=None, left=None, width=None):
    if bg_shape:
        if left is not None:
            bg_shape.left = left
        if width is not None:
            bg_shape.width = width
        if bg_color:
            bg_shape.fill.solid()
            bg_shape.fill.fore_color.rgb = bg_color
            
    if left is not None:
        text_shape.left = left
    if width is not None:
        text_shape.width = width
        
    tf = text_shape.text_frame
    tf.word_wrap = False
    p = tf.paragraphs[0]
    p.text = ""
    p.alignment = PP_ALIGN.CENTER
    run = p.add_run()
    run.text = text
    run.font.color.rgb = text_color
    run.font.bold = True
    run.font.size = Pt(font_size)

def set_footer(slide, slide_num, total_slides=4):
    for shape in slide.shapes:
        if shape.name == "TextBox 5" and shape.has_text_frame:
            shape.text_frame.word_wrap = False
            shape.text_frame.paragraphs[0].text = f"Modern Vision Solutions 2026  •  MVS ICT & Telecom{' ' * 68}Slide {slide_num} of {total_slides}"
            p = shape.text_frame.paragraphs[0]
            if p.runs:
                p.runs[0].font.size = Pt(8.0)
                p.runs[0].font.color.rgb = RGBColor(148, 163, 184)

# -------------------------------------------------------------
# SLIDE 1: Title Slide (Week 47, 19/11/2026)
# -------------------------------------------------------------
s1 = prs.slides[0]
for shape in s1.shapes:
    if shape.name == "TextBox 2" and shape.has_text_frame:
        tf = shape.text_frame
        tf.paragraphs[0].text = "M V S   ◆   Weekly Review"
        tf.paragraphs[0].runs[0].font.color.rgb = COLOR_CYAN
        tf.paragraphs[0].runs[0].font.bold = True
        
        tf.paragraphs[1].text = "Weekly Progress & IT Assets"
        tf.paragraphs[1].runs[0].font.color.rgb = COLOR_NAVY
        tf.paragraphs[1].runs[0].font.bold = True
        
        tf.paragraphs[2].text = "2026 Week 47, 19/11/2026"
        tf.paragraphs[2].runs[0].font.color.rgb = COLOR_CYAN
        tf.paragraphs[2].runs[0].font.bold = True

    if shape.name == "TextBox 5" and shape.has_text_frame:
        tf = shape.text_frame
        tf.paragraphs[0].text = "MVS ICT & Telecom"
        tf.paragraphs[0].runs[0].font.bold = True
        tf.paragraphs[1].text = "Weekly Presentation"
        tf.paragraphs[2].text = "Presenter: Danish Akbar"
        tf.paragraphs[3].text = "Position: IT Support"

# -------------------------------------------------------------
# SLIDE 2: Operations & Progress (Week 47, Slide 2 of 4)
# -------------------------------------------------------------
s2 = prs.slides[1]
set_footer(s2, 2, 4)

logo_blob = None
for shape in s2.shapes:
    if shape.name == "Picture 3":
        logo_blob = shape.image.blob

# Header Title
for shape in s2.shapes:
    if shape.name == "TextBox 2" and shape.has_text_frame:
        tf = shape.text_frame
        tf.paragraphs[0].text = "M V S   ◆   WEEKLY OPERATIONS"
        tf.paragraphs[0].runs[0].font.color.rgb = COLOR_CYAN
        tf.paragraphs[0].runs[0].font.bold = True
        
        tf.paragraphs[1].text = "Weekly Progress & Next Steps (Week 47)"
        tf.paragraphs[1].runs[0].font.color.rgb = COLOR_NAVY
        tf.paragraphs[1].runs[0].font.bold = True

# Shape dictionary for s2 badges
s2_shapes = {s.name: s for s in s2.shapes}

# Card 1: MVS Sales & Projects Portal
if "TextBox 8" in s2_shapes:
    tf = s2_shapes["TextBox 8"].text_frame
    p = tf.paragraphs[0]
    p.text = ""
    r1 = p.add_run()
    r1.text = "◆  "
    r1.font.color.rgb = COLOR_CYAN
    r2 = p.add_run()
    r2.text = "MVS Sales & Projects Portal"
    r2.font.color.rgb = COLOR_NAVY
    r2.font.bold = True
    r2.font.size = Pt(10.5)
    s2_shapes["TextBox 8"].width = 3450000

if "TextBox 10" in s2_shapes and "Rounded Rectangle 9" in s2_shapes:
    set_badge(s2_shapes["TextBox 10"], "✓ LIVE & ADOPTION", COLOR_GREEN_TEXT, 7.6, s2_shapes["Rounded Rectangle 9"], COLOR_GREEN_BG, 4120000, 1720000)

if "TextBox 11" in s2_shapes:
    bullets1 = [
        ("Portal Status", "Live and functional at portal.mvsksa.com with SSL security."),
        ("Sales Team Onboarding", "Login credentials distributed to all sales team members for active data entry."),
        ("PM Team Alignment", "Conducting system walkthrough and demo with Project Managers today."),
        ("Feature Enhancements", "Requested updates (26-column sync & formulas) targeted live by next week (ETA: 26 Nov).")
    ]
    populate_bullets(s2_shapes["TextBox 11"], bullets1, font_size=8.5, space_after=3.0)

# Card 2: Salam CCS — KPI Framework
if "TextBox 14" in s2_shapes:
    tf = s2_shapes["TextBox 14"].text_frame
    p = tf.paragraphs[0]
    p.text = ""
    r1 = p.add_run()
    r1.text = "◆  "
    r1.font.color.rgb = COLOR_CYAN
    r2 = p.add_run()
    r2.text = "Salam CCS — KPI Framework"
    r2.font.color.rgb = COLOR_NAVY
    r2.font.bold = True
    r2.font.size = Pt(10.5)
    s2_shapes["TextBox 14"].width = 3600000

if "TextBox 16" in s2_shapes and "Rounded Rectangle 15" in s2_shapes:
    set_badge(s2_shapes["TextBox 16"], "● IN PROGRESS (ETA: 26 NOV)", COLOR_BLUE_TEXT, 7.2, s2_shapes["Rounded Rectangle 15"], COLOR_BLUE_BG, 9750000, 1830000)

if "TextBox 17" in s2_shapes:
    bullets2 = [
        ("Data Pipeline", "Patrolling and supervisor logs integrated from Mr. Tahir Anjum."),
        ("Scoring & Logic", "Developing calculation formulas for SLA compliance, CMR/PMR, and team rankings."),
        ("Review Milestone", "Draft KPI model & executive scorecard targeted for management review (ETA: 26 Nov).")
    ]
    populate_bullets(s2_shapes["TextBox 17"], bullets2, font_size=8.5, space_after=3.0)

# Card 3: FO Route Survey Application (DCP-1)
if "TextBox 20" in s2_shapes:
    tf = s2_shapes["TextBox 20"].text_frame
    p = tf.paragraphs[0]
    p.text = ""
    r1 = p.add_run()
    r1.text = "◆  "
    r1.font.color.rgb = COLOR_CYAN
    r2 = p.add_run()
    r2.text = "FO Route Survey (DCP-1)"
    r2.font.color.rgb = COLOR_NAVY
    r2.font.bold = True
    r2.font.size = Pt(10.5)
    s2_shapes["TextBox 20"].width = 3450000

if "TextBox 22" in s2_shapes and "Rounded Rectangle 21" in s2_shapes:
    set_badge(s2_shapes["TextBox 22"], "◆ SUBMISSION PHASE", COLOR_AMBER_TEXT, 7.5, s2_shapes["Rounded Rectangle 21"], COLOR_AMBER_BG, 4120000, 1720000)

if "TextBox 23" in s2_shapes:
    bullets3 = [
        ("Original Scope", "Initial project scope survey documentation is being submitted today (19 Nov)."),
        ("Additional Scope", "Final supplementary route scope scheduled for submission by Monday (23 Nov)."),
        ("Project Completion", "Marks full survey completion, pending any further data requests from Tawasul.")
    ]
    populate_bullets(s2_shapes["TextBox 23"], bullets3, font_size=8.5, space_after=3.0)

# Card 4: IT Infrastructure & Asset Governance
if "TextBox 26" in s2_shapes:
    tf = s2_shapes["TextBox 26"].text_frame
    p = tf.paragraphs[0]
    p.text = ""
    r1 = p.add_run()
    r1.text = "◆  "
    r1.font.color.rgb = COLOR_CYAN
    r2 = p.add_run()
    r2.text = "IT Infrastructure & Assets"
    r2.font.color.rgb = COLOR_NAVY
    r2.font.bold = True
    r2.font.size = Pt(10.5)
    s2_shapes["TextBox 26"].width = 3600000

if "TextBox 28" in s2_shapes and "Rounded Rectangle 27" in s2_shapes:
    set_badge(s2_shapes["TextBox 28"], "✓ EXPANDING TEAM", COLOR_GREEN_TEXT, 7.5, s2_shapes["Rounded Rectangle 27"], COLOR_GREEN_BG, 10050000, 1530000)

if "TextBox 29" in s2_shapes:
    bullets4 = [
        ("Team Capacity", "Onboarding an additional technical team resource this Sunday (22 Nov) to boost output."),
        ("Asset Custody", "Active management of 83 IT hardware units (79 active, 4 buffer) and 50 telecom lines."),
        ("Monitor Deployment", "Procured & deployed 6 LG 27\" UltraGear monitors from Jarir Bookstore (5,148 SAR)."),
        ("Operations Support", "Ongoing network stability, workstation maintenance, and boardroom AV readiness.")
    ]
    populate_bullets(s2_shapes["TextBox 29"], bullets4, font_size=8.5, space_after=3.0)

# -------------------------------------------------------------
# CREATE NEW SLIDE 3: Strategic Initiatives (Salam Automation & Odoo ERP)
# -------------------------------------------------------------
blank_layout = prs.slide_layouts[4]
s3 = prs.slides.add_slide(blank_layout)

# Clone base shapes from Slide 2 into new Slide 3 (excluding Picture 3 so we add it cleanly with rels)
for shape in s2.shapes:
    if shape.name != "Picture 3":
        new_sp = copy.deepcopy(shape._element)
        s3.shapes._spTree.append(new_sp)

# Add clean logo to s3
if logo_blob:
    new_logo = s3.shapes.add_picture(io.BytesIO(logo_blob), 10769600, 127000, 939800, 1028700)
    new_logo.name = "Picture 3"

# Reorder s3 to be at index 2 (Slide 3 of 4)
sldIdLst = prs.slides._sldIdLst
new_sld_id = sldIdLst[-1]
sldIdLst.remove(new_sld_id)
sldIdLst.insert(2, new_sld_id)

# Customize shapes on Slide 3
for shape in s3.shapes:
    if shape.name == "TextBox 2" and shape.has_text_frame:
        tf = shape.text_frame
        tf.paragraphs[0].text = "M V S   ◆   STRATEGIC INITIATIVES"
        tf.paragraphs[0].runs[0].font.color.rgb = COLOR_CYAN
        tf.paragraphs[0].runs[0].font.bold = True
        
        tf.paragraphs[1].text = "Salam Automation & Odoo ERP Implementation"
        tf.paragraphs[1].runs[0].font.color.rgb = COLOR_NAVY
        tf.paragraphs[1].runs[0].font.bold = True

set_footer(s3, 3, 4)

# Delete Cards 3 and 4 shapes from Slide 3
card_delete_names = {
    "Rounded Rectangle 18", "Rounded Rectangle 19", "TextBox 20", "Rounded Rectangle 21", "TextBox 22", "TextBox 23",
    "Rounded Rectangle 24", "Rounded Rectangle 25", "TextBox 26", "Rounded Rectangle 27", "TextBox 28", "TextBox 29"
}

for shape in list(s3.shapes):
    if shape.name in card_delete_names:
        sp = shape._element
        sp.getparent().remove(sp)

s3_shapes = {s.name: s for s in s3.shapes}

# Configure Left Card: Salam Automation & Dashboard
CARD_HEIGHT = 4950000
if "Rounded Rectangle 6" in s3_shapes:
    s3_shapes["Rounded Rectangle 6"].height = CARD_HEIGHT

if "TextBox 8" in s3_shapes:
    tf = s3_shapes["TextBox 8"].text_frame
    p = tf.paragraphs[0]
    p.text = ""
    r1 = p.add_run()
    r1.text = "◆  "
    r1.font.color.rgb = COLOR_CYAN
    r2 = p.add_run()
    r2.text = "Salam Automation & Dashboard"
    r2.font.color.rgb = COLOR_NAVY
    r2.font.bold = True
    r2.font.size = Pt(11.5)
    s3_shapes["TextBox 8"].width = 3300000

if "TextBox 10" in s3_shapes and "Rounded Rectangle 9" in s3_shapes:
    set_badge(s3_shapes["TextBox 10"], "● ROADMAP & PLANNING", COLOR_BLUE_TEXT, 7.8, s3_shapes["Rounded Rectangle 9"], COLOR_BLUE_BG, 3850000, 1990000)

if "TextBox 11" in s3_shapes:
    s3_shapes["TextBox 11"].height = 4300000
    bullets_salam = [
        ("Roadmap & Execution Plan", "Currently actively working on the detailed automation roadmap and finalizing the implementation plan."),
        ("Real-Time Operations Dashboard", "Designing executive dashboard view for live operational oversight, patrolling coverage, and SLA visibility."),
        ("Automated Data Pipelines", "Establishing automated data intake from field logs to eliminate manual delays and reporting bottlenecks."),
        ("Performance Analytics Engine", "Structuring automated scoring models for PMR/CMR resolutions, preventive maintenance, and team rankings."),
        ("Target Review Milestone", "Targeting initial dashboard framework review with Salam CCS stakeholders by Next Thursday (ETA: 26 Nov).")
    ]
    populate_bullets(s3_shapes["TextBox 11"], bullets_salam, font_size=9.2, space_after=9.0, line_spacing=1.2)

# Configure Right Card: Odoo ERP Implementation
if "Rounded Rectangle 12" in s3_shapes:
    s3_shapes["Rounded Rectangle 12"].height = CARD_HEIGHT

if "TextBox 14" in s3_shapes:
    tf = s3_shapes["TextBox 14"].text_frame
    p = tf.paragraphs[0]
    p.text = ""
    r1 = p.add_run()
    r1.text = "◆  "
    r1.font.color.rgb = COLOR_CYAN
    r2 = p.add_run()
    r2.text = "Odoo ERP Implementation"
    r2.font.color.rgb = COLOR_NAVY
    r2.font.bold = True
    r2.font.size = Pt(11.5)
    s3_shapes["TextBox 14"].width = 3300000

if "TextBox 16" in s3_shapes and "Rounded Rectangle 15" in s3_shapes:
    set_badge(s3_shapes["TextBox 16"], "● IN PROGRESS (ETA: 26 NOV)", COLOR_PURPLE_TEXT, 7.2, s3_shapes["Rounded Rectangle 15"], COLOR_PURPLE_BG, 9550000, 2030000)

if "TextBox 17" in s3_shapes:
    s3_shapes["TextBox 17"].height = 4300000
    bullets_odoo = [
        ("Commercial Templates Kickoff", "Work started on standardizing layout templates for Quotations, Purchase Orders (PO), and Proforma Invoices."),
        ("Product Part Configuration", "Actively configuring the product catalog structure, technical items master, and product part definitions."),
        ("Odoo Partner Coordination", "In continuous technical communication with Odoo implementation partner for specialized custom requirements."),
        ("Tag-Based Description System", "Engineering dynamic tag-based item descriptions for accurate quotation generation and category management."),
        ("Zero-Disruption Rollout Plan", "Targeting initial template & workflow sign-off by Next Thursday (ETA: 26 Nov), with zero disruption to active data.")
    ]
    populate_bullets(s3_shapes["TextBox 17"], bullets_odoo, font_size=9.2, space_after=9.0, line_spacing=1.2)

# -------------------------------------------------------------
# SLIDE 4: IT Assets & Telecom Inventory Dashboard (Slide 4 of 4)
# -------------------------------------------------------------
s4 = prs.slides[3]
set_footer(s4, 4, 4)

if os.path.exists(ASSET_IMG_PATH):
    for s in list(s4.shapes):
        if s.name == "Picture 7":
            target_h = 4750000
            target_w = int(target_h * 1.3788)
            target_t = 1422400 + int((4978400 - target_h) / 2)
            target_l = 482600 + int((11226800 - target_w) / 2)

            sp = s._element
            sp.getparent().remove(sp)
            new_pic = s4.shapes.add_picture(ASSET_IMG_PATH, target_l, target_t, target_w, target_h)
            new_pic.name = "Picture 7"

# Save PPTX outputs
prs.save(OUTPUT_PPTX_WEEK47)
prs.save(OUTPUT_PPTX_LATEST)
prs.save(OUTPUT_PPTX_DOWNLOADS)
print(f"Successfully generated Week 47 PPTX: {OUTPUT_PPTX_WEEK47}")
print(f"Successfully generated Downloads PPTX: {OUTPUT_PPTX_DOWNLOADS}")

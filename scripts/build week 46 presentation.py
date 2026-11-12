import os
import pptx
from pptx.util import Inches, Pt
from pptx.dml.color import RGBColor
from pptx.enum.text import PP_ALIGN

BASE_DIR = r"C:\laragon\www\MVS Project Pulse"
TEMPLATE_PATH = os.path.join(BASE_DIR, "Presentation", "MVS Weekly Tasks and IT Assets Presentation Week 45.pptx")

OUTPUT_PPTX_PULSE = os.path.join(BASE_DIR, "Presentation", "MVS Weekly Tasks and IT Assets Presentation Week 46.pptx")
OUTPUT_PPTX_LATEST = os.path.join(BASE_DIR, "Presentation", "MVS Weekly Tasks and IT Assets Presentation.pptx")
OUTPUT_PPTX_DOWNLOADS = r"C:\Users\danis\Downloads\Week 46 presentations\MVS Weekly Tasks and IT Assets Presentation Week 46.pptx"

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

# -------------------------------------------------------------
# SLIDE 1: Title Slide (Week 46, 12/11/2026)
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
        
        tf.paragraphs[2].text = "2026 Week 46, 12/11/2026"
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
# SLIDE 2: Operations & Progress (Week 46)
# -------------------------------------------------------------
s2 = prs.slides[1]

# Header Title
for shape in s2.shapes:
    if shape.name == "TextBox 2" and shape.has_text_frame:
        tf = shape.text_frame
        tf.paragraphs[0].text = "M V S   ◆   WEEKLY OPERATIONS"
        tf.paragraphs[0].runs[0].font.color.rgb = COLOR_CYAN
        tf.paragraphs[0].runs[0].font.bold = True
        
        tf.paragraphs[1].text = "Weekly Progress & Next Steps (Week 46)"
        tf.paragraphs[1].runs[0].font.color.rgb = COLOR_NAVY
        tf.paragraphs[1].runs[0].font.bold = True

# Helper function for concise bullets
def populate_bullets(shape, bullet_items, font_size=9.0, space_after=3.5):
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
        p.line_spacing = 1.14
        
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

# --- CARD 1 (Top-Left): MVS Sales & Projects Portal ---
for shape in s2.shapes:
    if shape.name == "TextBox 8": # Title
        tf = shape.text_frame
        p = tf.paragraphs[0]
        p.text = ""
        r1 = p.add_run()
        r1.text = "◆  "
        r1.font.color.rgb = COLOR_CYAN
        r2 = p.add_run()
        r2.text = "MVS Sales & Projects Web Portal"
        r2.font.color.rgb = COLOR_NAVY
        r2.font.bold = True
        
    elif shape.name == "Rounded Rectangle 9": # Badge shape
        shape.fill.solid()
        shape.fill.fore_color.rgb = COLOR_GREEN_BG
        shape.left = 4220000
        shape.width = 1620000
        
    elif shape.name == "TextBox 10": # Badge text
        shape.left = 4220000
        shape.width = 1620000
        tf = shape.text_frame
        p = tf.paragraphs[0]
        p.text = "✓ LIVE & OPERATIONAL"
        p.alignment = PP_ALIGN.CENTER
        if p.runs:
            p.runs[0].font.color.rgb = COLOR_GREEN_TEXT
            p.runs[0].font.bold = True
            p.runs[0].font.size = Pt(8.0)
            
    elif shape.name == "TextBox 11": # Concise bullets
        bullets1 = [
            ("Live Deployment", "Operational at portal.mvsksa.com with active SSL encryption."),
            ("Team Onboarding", "All user accounts created with official MVS emails and default credentials."),
            ("Live Data Entry", "Sample records cleared; live pipelines and projects being actively entered."),
            ("Master Excel Sync", "Dynamic export engine providing clean OneDrive master tracking files."),
            ("Odoo ERP Kickoff", "Initiating setup for Quotations, POs, Proforma Invoices, and operational workflows.")
        ]
        populate_bullets(shape, bullets1, font_size=8.8, space_after=3.0)

# --- CARD 2 (Top-Right): Salam CCS — KPI Framework ---
for shape in s2.shapes:
    if shape.name == "TextBox 14": # Title
        tf = shape.text_frame
        p = tf.paragraphs[0]
        p.text = ""
        r1 = p.add_run()
        r1.text = "◆  "
        r1.font.color.rgb = COLOR_CYAN
        r2 = p.add_run()
        r2.text = "Salam CCS — KPI Framework"
        r2.font.color.rgb = COLOR_NAVY
        r2.font.bold = True
        
    elif shape.name == "Rounded Rectangle 15": # Badge shape
        shape.fill.solid()
        shape.fill.fore_color.rgb = COLOR_BLUE_BG
        shape.left = 10142400
        shape.width = 1440000
        
    elif shape.name == "TextBox 16": # Badge text
        shape.left = 10142400
        shape.width = 1440000
        tf = shape.text_frame
        p = tf.paragraphs[0]
        p.text = "● IN PROGRESS (40%)"
        p.alignment = PP_ALIGN.CENTER
        if p.runs:
            p.runs[0].font.color.rgb = COLOR_BLUE_TEXT
            p.runs[0].font.bold = True
            p.runs[0].font.size = Pt(8.5)
            
    elif shape.name == "TextBox 17": # Concise bullets
        bullets2 = [
            ("Data Integration", "Received patrolling and supervisor tracking data from Mr. Tahir Anjum."),
            ("Scoring Models", "Structuring calculation formulas for SLA compliance, CMR/PMR, and team rankings."),
            ("Executive Scorecard", "Designing consolidated dashboard view for weekly management review.")
        ]
        populate_bullets(shape, bullets2)

# --- CARD 3 (Bottom-Left): FO Route Survey Application (DCP-1) ---
for shape in s2.shapes:
    if shape.name == "TextBox 20": # Title
        tf = shape.text_frame
        p = tf.paragraphs[0]
        p.text = ""
        r1 = p.add_run()
        r1.text = "◆  "
        r1.font.color.rgb = COLOR_CYAN
        r2 = p.add_run()
        r2.text = "FO Route Survey Application (DCP-1)"
        r2.font.color.rgb = COLOR_NAVY
        r2.font.bold = True

    elif shape.name == "Rounded Rectangle 21": # Badge shape
        shape.fill.solid()
        shape.fill.fore_color.rgb = COLOR_AMBER_BG
        shape.left = 4150000
        shape.width = 1690000

    elif shape.name == "TextBox 22": # Badge text
        shape.left = 4150000
        shape.width = 1690000
        tf = shape.text_frame
        p = tf.paragraphs[0]
        p.text = "◆ ONGOING SUPPORT"
        p.alignment = PP_ALIGN.CENTER
        if p.runs:
            p.runs[0].font.color.rgb = COLOR_AMBER_TEXT
            p.runs[0].font.bold = True
            p.runs[0].font.size = Pt(8.0)

    elif shape.name == "TextBox 23": # Concise bullets
        bullets3 = [
            ("Survey Operations", "Ongoing QA alignment checks, utility classifications, and highway mapping."),
            ("Field & Data Backups", "Continuous KMZ exports, database integrity backups, and survey team sync.")
        ]
        populate_bullets(shape, bullets3)

# --- CARD 4 (Bottom-Right): IT Infrastructure & Asset Governance ---
for shape in s2.shapes:
    if shape.name == "TextBox 26": # Title
        tf = shape.text_frame
        p = tf.paragraphs[0]
        p.text = ""
        r1 = p.add_run()
        r1.text = "◆  "
        r1.font.color.rgb = COLOR_CYAN
        r2 = p.add_run()
        r2.text = "IT Infrastructure & Asset Governance"
        r2.font.color.rgb = COLOR_NAVY
        r2.font.bold = True

    elif shape.name == "TextBox 29": # Concise bullets
        bullets4 = [
            ("Hardware & SIM Custody", "Managing 83 hardware units (79 in use, 4 in stock) and 50 telecom lines."),
            ("Week 46 Procurement", "Procured & deployed 6 LG 27\" UltraGear monitors and cables (5,148 SAR, Jarir)."),
            ("New Team Resource", "Planning onboarding of an additional technical resource to expand team capacity."),
            ("Daily IT Support", "Workstation maintenance, network support, and meeting room AV readiness.")
        ]
        populate_bullets(shape, bullets4, font_size=8.8, space_after=3.0)

# -------------------------------------------------------------
# SLIDE 3: IT Assets & Telecom Inventory Dashboard (Updated Image)
# -------------------------------------------------------------
s3 = prs.slides[2]
if os.path.exists(ASSET_IMG_PATH):
    for s in list(s3.shapes):
        if s.name == "Picture 7":
            # Target image: 1405 x 1019 -> aspect ratio 1.3788
            target_h = 4750000
            target_w = int(target_h * 1.3788)
            target_t = 1422400 + int((4978400 - target_h) / 2)
            target_l = 482600 + int((11226800 - target_w) / 2)

            sp = s._element
            sp.getparent().remove(sp)
            new_pic = s3.shapes.add_picture(ASSET_IMG_PATH, target_l, target_t, target_w, target_h)
            new_pic.name = "Picture 7"
            print("Successfully inserted updated asset management dashboard image on Slide 3!")

# Save PPTX outputs
prs.save(OUTPUT_PPTX_PULSE)
prs.save(OUTPUT_PPTX_LATEST)
prs.save(OUTPUT_PPTX_DOWNLOADS)
print(f"Successfully generated: {OUTPUT_PPTX_PULSE}")
print(f"Successfully generated: {OUTPUT_PPTX_DOWNLOADS}")

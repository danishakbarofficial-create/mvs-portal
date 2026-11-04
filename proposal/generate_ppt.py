import os
import pptx
from pptx.util import Inches, Pt
from pptx.dml.color import RGBColor
from pptx.enum.shapes import MSO_SHAPE
from pptx.enum.text import PP_ALIGN, MSO_ANCHOR
from pptx.enum.dml import MSO_THEME_COLOR

# ---------------------------------------------------------
# CONSTANTS & PALETTE (MVS BRAND IDENTITY)
# ---------------------------------------------------------
COLOR_PRIMARY_NAVY = RGBColor(45, 26, 82)    # #2D1A52 (Deep Purple-Navy from MVS Template)
COLOR_CYAN         = RGBColor(0, 175, 228)   # #00AFE4 (Bright Cyan / MVS Accent)
COLOR_GOLD         = RGBColor(186, 150, 49)  # #BA9631 (MVS Gold)
COLOR_GOLD_LIGHT   = RGBColor(212, 176, 76)  # #D4B04C
COLOR_DARK         = RGBColor(15, 23, 42)    # #0F172A
COLOR_SLATE        = RGBColor(70, 84, 105)   # #465469 (Secondary text)
COLOR_MUTED        = RGBColor(100, 116, 139) # #64748B
COLOR_LIGHT_BG     = RGBColor(248, 250, 252) # #F8FAFC (Card background)
COLOR_BORDER       = RGBColor(225, 232, 239) # #E1E8EF (Divider & border)
COLOR_WHITE        = RGBColor(255, 255, 255)

# Status Badge Colors
COLOR_WON_BG       = RGBColor(236, 253, 245) # #ECFDF5
COLOR_WON_TXT      = RGBColor(6, 95, 70)     # #065F46
COLOR_NEG_BG       = RGBColor(255, 251, 235) # #FFFBEB
COLOR_NEG_TXT      = RGBColor(146, 64, 14)   # #92400E
COLOR_QUOTED_BG    = RGBColor(239, 246, 255) # #EFF6FF
COLOR_QUOTED_TXT   = RGBColor(30, 64, 175)   # #1E40AF
COLOR_ALERT_TXT    = RGBColor(153, 27, 27)   # #991B1B

FONT_NAME = "Aptos"
LOGO_PATH = os.path.join(os.path.dirname(__file__), "extracted_slide_img.png")

TOTAL_SLIDES = 8

def apply_header(slide, category_text, title_text, subtitle_text, slide_num):
    """
    Applies standard header and footer matching 'MVS Weekly Meeting _ Format to develop.pptx'.
    """
    # 1. Left Cyan Accent Bar (Matches Master shape bg object 16: left=0.5", top=0.45", w=0.08", h=1.0")
    bar = slide.shapes.add_shape(MSO_SHAPE.RECTANGLE, Inches(0.50), Inches(0.45), Inches(0.08), Inches(1.00))
    bar.fill.solid()
    bar.fill.fore_color.rgb = COLOR_CYAN
    bar.line.fill.background()

    # 2. Header Text Box (Matches object 2: left=0.74", top=0.38", w=7.5", h=1.15")
    tb = slide.shapes.add_textbox(Inches(0.74), Inches(0.38), Inches(9.50), Inches(1.15))
    tf = tb.text_frame
    tf.word_wrap = True
    tf.margin_left = tf.margin_top = tf.margin_right = tf.margin_bottom = 0

    # Paragraph 1: Category / Breadcrumb
    p1 = tf.paragraphs[0]
    p1.space_after = Pt(2)
    r1 = p1.add_run()
    r1.text = f"M V S   \u00b7   {category_text}"
    r1.font.name = FONT_NAME
    r1.font.size = Pt(11)
    r1.font.bold = True
    r1.font.color.rgb = COLOR_CYAN

    # Paragraph 2: Title
    p2 = tf.add_paragraph()
    p2.space_after = Pt(2)
    r2 = p2.add_run()
    r2.text = title_text
    r2.font.name = FONT_NAME
    r2.font.size = Pt(24)
    r2.font.bold = True
    r2.font.color.rgb = COLOR_PRIMARY_NAVY

    # Paragraph 3: Subtitle
    p3 = tf.add_paragraph()
    r3 = p3.add_run()
    r3.text = subtitle_text
    r3.font.name = FONT_NAME
    r3.font.size = Pt(11.5)
    r3.font.color.rgb = COLOR_SLATE

    # 3. Logo (Matches object 5: left=11.53", top=0.12", w=1.165", h=1.286")
    if os.path.exists(LOGO_PATH):
        slide.shapes.add_picture(LOGO_PATH, Inches(11.53), Inches(0.12), width=Inches(1.165), height=Inches(1.286))

    # 4. Dividing Line (Matches object 3: left=0.60", top=1.68", w=12.13", h=0)
    line = slide.shapes.add_shape(MSO_SHAPE.RECTANGLE, Inches(0.60), Inches(1.68), Inches(12.13), Pt(1))
    line.fill.solid()
    line.fill.fore_color.rgb = COLOR_BORDER
    line.line.fill.background()

    # 5. Footer (Matches object 6 + clean pagination)
    ft_left = slide.shapes.add_textbox(Inches(0.60), Inches(7.00), Inches(8.00), Inches(0.35))
    ft_left.text_frame.margin_left = ft_left.text_frame.margin_top = 0
    p_fl = ft_left.text_frame.paragraphs[0]
    r_fl = p_fl.add_run()
    r_fl.text = "Modern Vision Solutions Ltd \u00b7 MVS ICT & Telecom \u2014 Executive Proposal"
    r_fl.font.name = FONT_NAME
    r_fl.font.size = Pt(8.5)
    r_fl.font.color.rgb = COLOR_MUTED

    ft_right = slide.shapes.add_textbox(Inches(8.73), Inches(7.00), Inches(4.00), Inches(0.35))
    ft_right.text_frame.margin_right = ft_right.text_frame.margin_top = 0
    p_fr = ft_right.text_frame.paragraphs[0]
    p_fr.alignment = PP_ALIGN.RIGHT
    r_fr = p_fr.add_run()
    r_fr.text = f"Slide {slide_num} of {TOTAL_SLIDES}  |  MVS Confidential \u00b7 C-Suite Edition"
    r_fr.font.name = FONT_NAME
    r_fr.font.size = Pt(8.5)
    r_fr.font.color.rgb = COLOR_MUTED

def style_card(shape, bg_color=COLOR_LIGHT_BG, border_color=COLOR_BORDER, border_width_pt=1):
    shape.fill.solid()
    shape.fill.fore_color.rgb = bg_color
    if border_color:
        shape.line.color.rgb = border_color
        shape.line.width = Pt(border_width_pt)
    else:
        shape.line.fill.background()

def set_cell_props(cell, text, bold=False, font_size=Pt(8.5), color=COLOR_DARK, align=PP_ALIGN.LEFT, bg_color=None):
    if bg_color:
        cell.fill.solid()
        cell.fill.fore_color.rgb = bg_color
    cell.vertical_anchor = MSO_ANCHOR.MIDDLE
    cell.margin_left = Inches(0.08)
    cell.margin_right = Inches(0.08)
    cell.margin_top = Inches(0.04)
    cell.margin_bottom = Inches(0.04)
    p = cell.text_frame.paragraphs[0]
    p.text = text
    p.alignment = align
    if len(p.runs) > 0:
        p.runs[0].font.name = FONT_NAME
        p.runs[0].font.size = font_size
        p.runs[0].font.bold = bold
        p.runs[0].font.color.rgb = color

print("Module loaded successfully.")


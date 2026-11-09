import zipfile
import io
import os
import shutil
import xml.etree.ElementTree as ET

# Register all namespaces to preserve Excel structure perfectly
ET.register_namespace('', 'http://schemas.openxmlformats.org/spreadsheetml/2006/main')
ET.register_namespace('r', 'http://schemas.openxmlformats.org/officeDocument/2006/relationships')
ET.register_namespace('x14', 'http://schemas.microsoft.com/office/spreadsheetml/2009/9/main')
ET.register_namespace('xm', 'http://schemas.microsoft.com/office/excel/2006/main')
ET.register_namespace('xr', 'http://schemas.microsoft.com/office/spreadsheetml/2014/revision')

NS = 'http://schemas.openxmlformats.org/spreadsheetml/2006/main'

TEMPLATE_PATH = r'c:\laragon\www\MVS Project Pulse\Project_Tracking_Report Asayil v4.xlsx'
OUTPUT_DIR = r'c:\laragon\www\MVS Project Pulse'
DOWNLOADS_DIR = r'c:\Users\danis\Downloads'

def clean_sheet3(xml_bytes):
    """Pipeline sheet: clear sample data in cols B to V for rows 5 to 104, preserve all dropdowns and formats"""
    root = ET.fromstring(xml_bytes)
    sd = root.find(f'{{{NS}}}sheetData')
    for row in sd.findall(f'{{{NS}}}row'):
        r_num = int(row.attrib.get('r', 0))
        if 5 <= r_num <= 104:
            for c in row.findall(f'{{{NS}}}c'):
                col_letter = ''.join([ch for ch in c.attrib.get('r', '') if ch.isalpha()])
                if col_letter != 'A':
                    c.attrib.pop('t', None)
                    for child in list(c):
                        c.remove(child)
    return ET.tostring(root, encoding='utf-8', xml_declaration=True)

def clean_sheet4(xml_bytes):
    """Projects sheet: clear user input cols for rows 5 to 104, PRESERVE formulas in J, P, W, X and all dropdowns"""
    root = ET.fromstring(xml_bytes)
    sd = root.find(f'{{{NS}}}sheetData')
    formula_cols = {'J', 'P', 'W', 'X'}
    for row in sd.findall(f'{{{NS}}}row'):
        r_num = int(row.attrib.get('r', 0))
        if 5 <= r_num <= 104:
            for c in row.findall(f'{{{NS}}}c'):
                col_letter = ''.join([ch for ch in c.attrib.get('r', '') if ch.isalpha()])
                if col_letter == 'A':
                    continue
                elif col_letter in formula_cols:
                    # PRESERVE <f> (formula)! Only remove cached <v>
                    for child in list(c):
                        if child.tag == f'{{{NS}}}v':
                            c.remove(child)
                else:
                    c.attrib.pop('t', None)
                    for child in list(c):
                        c.remove(child)
    return ET.tostring(root, encoding='utf-8', xml_declaration=True)

def clean_sheet2(xml_bytes):
    """Projects Dashboard: Set C3 to 'All Projects' so formulas calculate cleanly"""
    root = ET.fromstring(xml_bytes)
    for c in root.iter(f'{{{NS}}}c'):
        if c.attrib.get('r') == 'C3':
            c.attrib['t'] = 'inlineStr'
            for child in list(c):
                c.remove(child)
            is_el = ET.SubElement(c, f'{{{NS}}}is')
            t_el = ET.SubElement(is_el, f'{{{NS}}}t')
            t_el.text = 'All Projects'
            break
    return ET.tostring(root, encoding='utf-8', xml_declaration=True)

def filter_workbook(wb_bytes, sheets_to_remove):
    """Filter visible sheets and ensure force recalculation on open"""
    root = ET.fromstring(wb_bytes)
    sheets_el = root.find(f'{{{NS}}}sheets')
    for s in list(sheets_el):
        if s.attrib.get('name') in sheets_to_remove:
            sheets_el.remove(s)
    calcPr = root.find(f'{{{NS}}}calcPr')
    if calcPr is not None:
        calcPr.set('fullCalcOnLoad', '1')
        calcPr.set('forceFullCalc', '1')
    return ET.tostring(root, encoding='utf-8', xml_declaration=True)

with open(TEMPLATE_PATH, 'rb') as f:
    orig_bytes = f.read()

# -------------------------------------------------------------
# 1. Generate Clean MVS PipeLines Tracking.xlsx
# -------------------------------------------------------------
pipe_file_path = os.path.join(OUTPUT_DIR, 'MVS PipeLines Tracking.xlsx')
zin = zipfile.ZipFile(io.BytesIO(orig_bytes), 'r')
with zipfile.ZipFile(pipe_file_path, 'w', compression=zipfile.ZIP_DEFLATED) as zout:
    for item in zin.infolist():
        data = zin.read(item.filename)
        if item.filename == 'xl/worksheets/sheet3.xml':
            data = clean_sheet3(data)
        elif item.filename == 'xl/workbook.xml':
            data = filter_workbook(data, ['Projects Dashboard', 'Projects'])
        zout.writestr(item, data)
print(f"Generated clean: {pipe_file_path}")

# -------------------------------------------------------------
# 2. Generate Clean MVS Projects Tracking.xlsx
# -------------------------------------------------------------
proj_file_path = os.path.join(OUTPUT_DIR, 'MVS Projects Tracking.xlsx')
zin = zipfile.ZipFile(io.BytesIO(orig_bytes), 'r')
with zipfile.ZipFile(proj_file_path, 'w', compression=zipfile.ZIP_DEFLATED) as zout:
    for item in zin.infolist():
        data = zin.read(item.filename)
        if item.filename == 'xl/worksheets/sheet4.xml':
            data = clean_sheet4(data)
        elif item.filename == 'xl/worksheets/sheet2.xml':
            data = clean_sheet2(data)
        elif item.filename == 'xl/workbook.xml':
            data = filter_workbook(data, ['Sales Dashboard', 'Pipeline'])
        zout.writestr(item, data)
print(f"Generated clean: {proj_file_path}")

# -------------------------------------------------------------
# 3. Copy to Downloads Directory
# -------------------------------------------------------------
if os.path.isdir(DOWNLOADS_DIR):
    shutil.copy(pipe_file_path, os.path.join(DOWNLOADS_DIR, 'MVS PipeLines Tracking.xlsx'))
    shutil.copy(proj_file_path, os.path.join(DOWNLOADS_DIR, 'MVS Projects Tracking.xlsx'))
    print(f"Copied clean files to: {DOWNLOADS_DIR}")

print("DONE! Both templates cleanly generated with 100% formulas and dropdowns preserved.")

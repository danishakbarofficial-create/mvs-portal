import openpyxl
import shutil
import os

TEMPLATE_PATH = r'c:\laragon\www\MVS Project Pulse\Project_Tracking_Report Asayil v4.xlsx'
OUTPUT_DIR = r'c:\laragon\www\MVS Project Pulse'
DOWNLOADS_DIR = r'c:\Users\danis\Downloads'

# ---------------------------------------------------------
# 1. PipeLines Clean Workbook
# ---------------------------------------------------------
print("Generating clean MVS PipeLines Tracking.xlsx...")
wb_pipe = openpyxl.load_workbook(TEMPLATE_PATH, data_only=False)

# Keep only Sales Dashboard, Pipeline, Lists
del wb_pipe['Projects Dashboard']
del wb_pipe['Projects']

ws_pipe = wb_pipe['Pipeline']
# Clear rows 5 to 104 in Pipeline (cols B to V)
for r in range(5, 105):
    ws_pipe.cell(r, 1).value = r - 4  # Sequence 1 to 100
    for c in range(2, 23):            # Col B to V
        ws_pipe.cell(r, c).value = None

pipe_out_path = os.path.join(OUTPUT_DIR, 'MVS PipeLines Tracking.xlsx')
wb_pipe.save(pipe_out_path)
print(f"Saved: {pipe_out_path}")

# ---------------------------------------------------------
# 2. Projects Clean Workbook
# ---------------------------------------------------------
print("Generating clean MVS Projects Tracking.xlsx...")
wb_proj = openpyxl.load_workbook(TEMPLATE_PATH, data_only=False)

# Keep only Projects Dashboard, Projects, Lists
del wb_proj['Sales Dashboard']
del wb_proj['Pipeline']

ws_proj = wb_proj['Projects']
# Clear input columns in Projects, preserve formulas in J, P, W, X
cols_to_clear = [2, 3, 4, 5, 6, 7, 8, 9, 11, 12, 13, 14, 15, 17, 18, 19, 20, 21, 22, 25, 26]
for r in range(5, 105):
    ws_proj.cell(r, 1).value = r - 4  # Sequence 1 to 100
    for c in cols_to_clear:
        ws_proj.cell(r, c).value = None

# Set default project selector to 'All Projects'
ws_proj_dash = wb_proj['Projects Dashboard']
ws_proj_dash['C3'].value = 'All Projects'

proj_out_path = os.path.join(OUTPUT_DIR, 'MVS Projects Tracking.xlsx')
wb_proj.save(proj_out_path)
print(f"Saved: {proj_out_path}")

# ---------------------------------------------------------
# 3. Copy to Downloads Directory for immediate access
# ---------------------------------------------------------
if os.path.isdir(DOWNLOADS_DIR):
    shutil.copy(pipe_out_path, os.path.join(DOWNLOADS_DIR, 'MVS PipeLines Tracking.xlsx'))
    shutil.copy(proj_out_path, os.path.join(DOWNLOADS_DIR, 'MVS Projects Tracking.xlsx'))
    print(f"Copied clean files to Downloads: {DOWNLOADS_DIR}")

print("All clean templates successfully generated!")

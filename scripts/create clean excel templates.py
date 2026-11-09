import win32com.client
import os
import shutil

TEMPLATE_PATH = r'c:\laragon\www\MVS Project Pulse\Project_Tracking_Report Asayil v4.xlsx'
OUTPUT_DIR = r'c:\laragon\www\MVS Project Pulse'
DOWNLOADS_DIR = r'c:\Users\danis\Downloads'

excel = win32com.client.Dispatch('Excel.Application')
excel.Visible = False
excel.DisplayAlerts = False

try:
    # -------------------------------------------------------------
    # 1. Generate Clean MVS PipeLines Tracking.xlsx
    # -------------------------------------------------------------
    print("Generating clean MVS PipeLines Tracking.xlsx via Microsoft Excel engine...")
    pipe_out_path = os.path.join(OUTPUT_DIR, 'MVS PipeLines Tracking.xlsx')
    if os.path.exists(pipe_out_path):
        os.remove(pipe_out_path)

    wb_pipe = excel.Workbooks.Open(TEMPLATE_PATH)
    # Delete Projects-related sheets
    wb_pipe.Worksheets('Projects Dashboard').Delete()
    wb_pipe.Worksheets('Projects').Delete()

    # Clear input data in Pipeline (Cols B to V, rows 5 to 104)
    ws_pipe = wb_pipe.Worksheets('Pipeline')
    ws_pipe.Range('B5:V104').ClearContents()

    wb_pipe.SaveAs(pipe_out_path)
    wb_pipe.Close()
    print(f"Saved: {pipe_out_path}")

    # -------------------------------------------------------------
    # 2. Generate Clean MVS Projects Tracking.xlsx
    # -------------------------------------------------------------
    print("Generating clean MVS Projects Tracking.xlsx via Microsoft Excel engine...")
    proj_out_path = os.path.join(OUTPUT_DIR, 'MVS Projects Tracking.xlsx')
    if os.path.exists(proj_out_path):
        os.remove(proj_out_path)

    wb_proj = excel.Workbooks.Open(TEMPLATE_PATH)
    # Delete Pipelines-related sheets
    wb_proj.Worksheets('Sales Dashboard').Delete()
    wb_proj.Worksheets('Pipeline').Delete()

    ws_proj = wb_proj.Worksheets('Projects')
    # Clear only user input columns in Projects, keeping formula columns J, P, W, X intact:
    ws_proj.Range('B5:I104').ClearContents()
    ws_proj.Range('K5:O104').ClearContents()
    ws_proj.Range('Q5:V104').ClearContents()
    ws_proj.Range('Y5:Z104').ClearContents()

    # Set default project selector on Dashboard to 'All Projects'
    ws_dash = wb_proj.Worksheets('Projects Dashboard')
    ws_dash.Range('C3').Value = 'All Projects'

    wb_proj.SaveAs(proj_out_path)
    wb_proj.Close()
    print(f"Saved: {proj_out_path}")

    # -------------------------------------------------------------
    # 3. Copy both to Downloads folder for instant access
    # -------------------------------------------------------------
    if os.path.isdir(DOWNLOADS_DIR):
        pipe_download = os.path.join(DOWNLOADS_DIR, 'MVS PipeLines Tracking.xlsx')
        proj_download = os.path.join(DOWNLOADS_DIR, 'MVS Projects Tracking.xlsx')
        if os.path.exists(pipe_download): os.remove(pipe_download)
        if os.path.exists(proj_download): os.remove(proj_download)
        shutil.copy(pipe_out_path, pipe_download)
        shutil.copy(proj_out_path, proj_download)
        print(f"Copied clean native workbooks to: {DOWNLOADS_DIR}")

    print("ALL CLEAN WORKBOOKS GENERATED PERFECTLY WITH NATIVE MICROSOFT EXCEL!")

finally:
    excel.Quit()

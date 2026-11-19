import time
import win32com.client
from PIL import ImageGrab

excel = win32com.client.Dispatch('Excel.Application')
excel.Visible = False
excel.DisplayAlerts = False
try:
    wb = excel.Workbooks.Open(r'C:\laragon\www\MVS Asset list\MVS Master Asset Inventory.xlsx')
    excel.CalculateFull()
    ws = wb.Sheets('Dashboard')
    ws.Activate()
    excel.ActiveWindow.DisplayGridlines = False
    excel.ActiveWindow.Zoom = 150
    rng = ws.Range('B2:J29')
    rng.CopyPicture(1, 2) # 1 = xlScreen, 2 = xlBitmap
    time.sleep(0.8)
    img = ImageGrab.grabclipboard()
    if img:
        out1 = r'C:\laragon\www\MVS Project Pulse\Presentation\updated asset dashboard week 47.png'
        out2 = r'C:\laragon\www\MVS Project Pulse\Presentation\updated asset dashboard.png'
        img.save(out1)
        img.save(out2)
        print('Captured successfully! Size:', img.size)
    else:
        print('Clipboard is empty')
finally:
    wb.Close(False)
    excel.Quit()

import openpyxl

def update_inventory_files():
    files = [
        r"C:\laragon\www\MVS Asset list\MVS Master Asset Inventory.xlsx",
        r"C:\laragon\www\MVS Asset list\MVS Master Asset Inventory Updated.xlsx"
    ]
    for path in files:
        wb = openpyxl.load_workbook(path)
        if "Dashboard" in wb.sheetnames:
            ws = wb["Dashboard"]
            ws.cell(29, 2, "Week 47 (Nov 2026)")
            ws.cell(29, 3, "Meeting Room 1 Audio (Conference Mic & Speaker Set)")
            ws.cell(29, 5, "1 Set")
            ws.cell(29, 6, "Meeting Room 1")
            ws.cell(29, 7, "In Sourcing")
            ws.cell(29, 8, "Vendor Quotations")
            ws.cell(29, 9, "Active Sourcing (See Tab)")
            wb.save(path)
            print(f"Updated Dashboard in: {path}")

if __name__ == "__main__":
    update_inventory_files()

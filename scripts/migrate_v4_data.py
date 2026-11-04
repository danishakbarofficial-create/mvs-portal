import openpyxl
import sqlite3
import os
import datetime

DB_PATH = os.path.join(os.path.dirname(__file__), '..', 'data', 'mvs_sales.sqlite')
EXCEL_PATH = os.path.join(os.path.dirname(__file__), '..', 'Project_Tracking_Report Asayil v4.xlsx')

def migrate():
    con = sqlite3.connect(DB_PATH)
    cur = con.cursor()

    # 1. Users Table
    cur.execute("""
    CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT UNIQUE NOT NULL,
        name TEXT NOT NULL,
        email TEXT,
        password_hash TEXT NOT NULL,
        role TEXT NOT NULL CHECK(role IN ('sales', 'pm', 'executive')),
        status TEXT DEFAULT 'active',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    );
    """)

    # Seed Default Users (Password for all initially: mvs123)
    # Generated with password_hash('mvs123', PASSWORD_BCRYPT)
    DEFAULT_PW_HASH = "$2y$10$ZkjHjBuxU6y7sVDoBNRxtOwTi1UjHFmZb0og0dihyCXsZVgEN8.fW"

    users_seed = [
        # Sales
        ('asayil', 'Asayil', 'asayil@mvsksa.com', DEFAULT_PW_HASH, 'sales'),
        ('nasir', 'Nasir', 'nasir@mvsksa.com', DEFAULT_PW_HASH, 'sales'),
        ('ahmed', 'Ahmed', 'ahmed@mvsksa.com', DEFAULT_PW_HASH, 'sales'),
        ('wael', 'Wael', 'wael@mvsksa.com', DEFAULT_PW_HASH, 'sales'),
        ('shahbaz', 'Shahbaz', 'shahbaz@mvsksa.com', DEFAULT_PW_HASH, 'sales'),
        # PMs
        ('farooq', 'Muhammad Farooq', 'farooq@mvsksa.com', DEFAULT_PW_HASH, 'pm'),
        ('tahir', 'Tahir Anjum', 'tahir@mvsksa.com', DEFAULT_PW_HASH, 'pm'),
        ('adrian', 'Adrian Kim Mallilin', 'adrian@mvsksa.com', DEFAULT_PW_HASH, 'pm'),
        # Executive
        ('executive', 'Executive Management', 'management@mvsksa.com', DEFAULT_PW_HASH, 'executive'),
        ('admin', 'System Admin', 'admin@mvsksa.com', DEFAULT_PW_HASH, 'executive')
    ]

    for u in users_seed:
        cur.execute("""
            INSERT INTO users (username, name, email, password_hash, role)
            VALUES (?, ?, ?, ?, ?)
            ON CONFLICT(username) DO UPDATE SET
                name=excluded.name,
                email=excluded.email,
                role=excluded.role
        """, u)

    # 2. v4_pipeline Table (exact 22 columns from v4)
    cur.execute("""
    CREATE TABLE IF NOT EXISTS v4_pipeline (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        row_no INTEGER,
        sales_person TEXT,
        project_name TEXT,
        client TEXT,
        main_contractor TEXT,
        consultant TEXT,
        scope_of_work TEXT,
        date_received TEXT,
        submission_date TEXT,
        quotation_no TEXT,
        quotation_status TEXT,
        total_sar REAL DEFAULT 0,
        expected_closing TEXT,
        quotation_valid_until TEXT,
        contact_person TEXT,
        contact_details TEXT,
        decision_status TEXT,
        missing_info TEXT,
        next_step TEXT,
        follow_up_date TEXT,
        notes TEXT,
        additional_notes TEXT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
    );
    """)

    # 3. v4_projects Table (exact 26 columns from v4)
    cur.execute("""
    CREATE TABLE IF NOT EXISTS v4_projects (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        row_no INTEGER,
        project_manager TEXT,
        project_name TEXT,
        customer TEXT,
        po_status TEXT,
        po_no TEXT,
        po_date TEXT,
        total_po_value REAL DEFAULT 0,
        expected_closing TEXT,
        months_to_close INTEGER,
        milestone_no TEXT,
        milestone_desc TEXT,
        milestone_value REAL DEFAULT 0,
        milestone_status TEXT,
        technical_report TEXT,
        invoice_status TEXT,
        invoice_no TEXT,
        invoice_date TEXT,
        invoice_amount REAL DEFAULT 0,
        collection_date TEXT,
        collected_amount REAL DEFAULT 0,
        collection_status TEXT,
        pending_collection REAL DEFAULT 0,
        to_be_invoiced REAL DEFAULT 0,
        notes TEXT,
        additional_notes TEXT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
    );
    """)

    # 4. v4_lists Table
    cur.execute("""
    CREATE TABLE IF NOT EXISTS v4_lists (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        category TEXT NOT NULL,
        item_value TEXT NOT NULL,
        display_order INTEGER DEFAULT 0
    );
    """)

    # Load Workbook
    wb = openpyxl.load_workbook(EXCEL_PATH, data_only=True)

    # Clean old v4 records for fresh reload
    cur.execute("DELETE FROM v4_pipeline;")
    cur.execute("DELETE FROM v4_projects;")
    cur.execute("DELETE FROM v4_lists;")

    # Populate v4_lists from 'Lists' sheet
    ws_l = wb['Lists']
    for col in range(1, 20):
        category = ws_l.cell(4, col).value
        if category and isinstance(category, str) and category.strip():
            cat_name = category.strip()
            order = 1
            for r in range(5, 50):
                val = ws_l.cell(r, col).value
                if val is not None and str(val).strip() != '':
                    if isinstance(val, (datetime.datetime, datetime.date)):
                        val_str = val.strftime('%Y-%m')
                    else:
                        val_str = str(val).strip()
                    cur.execute("""
                        INSERT INTO v4_lists (category, item_value, display_order)
                        VALUES (?, ?, ?)
                    """, (cat_name, val_str, order))
                    order += 1

    # Populate v4_pipeline
    ws_p = wb['Pipeline']
    for r in range(5, ws_p.max_row + 1):
        vals = [ws_p.cell(r, c).value for c in range(1, 23)]
        # Must have project name or client or sales person to be a real record
        if any(vals[1:5]):
            def fmt(v):
                if v is None:
                    return None
                if isinstance(v, (datetime.datetime, datetime.date)):
                    return v.strftime('%Y-%m-%d')
                return str(v).strip()
            def fmt_num(v):
                if v is None or v == '':
                    return 0.0
                try:
                    return float(v)
                except:
                    return 0.0

            cur.execute("""
                INSERT INTO v4_pipeline (
                    row_no, sales_person, project_name, client, main_contractor,
                    consultant, scope_of_work, date_received, submission_date,
                    quotation_no, quotation_status, total_sar, expected_closing,
                    quotation_valid_until, contact_person, contact_details,
                    decision_status, missing_info, next_step, follow_up_date,
                    notes, additional_notes
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            """, (
                vals[0] if isinstance(vals[0], int) else (r - 4),
                fmt(vals[1]),
                fmt(vals[2]),
                fmt(vals[3]),
                fmt(vals[4]),
                fmt(vals[5]),
                fmt(vals[6]),
                fmt(vals[7]),
                fmt(vals[8]),
                fmt(vals[9]),
                fmt(vals[10]),
                fmt_num(vals[11]),
                fmt(vals[12]),
                fmt(vals[13]),
                fmt(vals[14]),
                fmt(vals[15]),
                fmt(vals[16]),
                fmt(vals[17]),
                fmt(vals[18]),
                fmt(vals[19]),
                fmt(vals[20]),
                fmt(vals[21])
            ))

    # Populate v4_projects
    ws_pr = wb['Projects']
    for r in range(5, ws_pr.max_row + 1):
        vals = [ws_pr.cell(r, c).value for c in range(1, 27)]
        # Filter out completely blank or placeholder template rows
        if any(vals[2:7]):
            def fmt(v):
                if v is None:
                    return None
                if isinstance(v, (datetime.datetime, datetime.date)):
                    return v.strftime('%Y-%m-%d')
                return str(v).strip()
            def fmt_num(v):
                if v is None or v == '':
                    return 0.0
                try:
                    return float(v)
                except:
                    return 0.0

            pm_name = fmt(vals[1])
            # If PM is empty in Excel for Air Base, assign Muhammad Farooq as default
            if not pm_name and vals[2] and 'Air Base' in str(vals[2]):
                pm_name = 'Muhammad Farooq'

            cur.execute("""
                INSERT INTO v4_projects (
                    row_no, project_manager, project_name, customer, po_status,
                    po_no, po_date, total_po_value, expected_closing, months_to_close,
                    milestone_no, milestone_desc, milestone_value, milestone_status,
                    technical_report, invoice_status, invoice_no, invoice_date,
                    invoice_amount, collection_date, collected_amount, collection_status,
                    pending_collection, to_be_invoiced, notes, additional_notes
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            """, (
                vals[0] if isinstance(vals[0], int) else (r - 4),
                pm_name,
                fmt(vals[2]),
                fmt(vals[3]),
                fmt(vals[4]),
                fmt(vals[5]),
                fmt(vals[6]),
                fmt_num(vals[7]),
                fmt(vals[8]),
                int(vals[9]) if isinstance(vals[9], (int, float)) else None,
                fmt(vals[10]),
                fmt(vals[11]),
                fmt_num(vals[12]),
                fmt(vals[13]),
                fmt(vals[14]),
                fmt(vals[15]),
                fmt(vals[16]),
                fmt(vals[17]),
                fmt_num(vals[18]),
                fmt(vals[19]),
                fmt_num(vals[20]),
                fmt(vals[21]),
                fmt_num(vals[22]),
                fmt_num(vals[23]),
                fmt(vals[24]),
                fmt(vals[25])
            ))

    con.commit()
    print("Migration successful!")
    print("Users count:", cur.execute("SELECT count(*) FROM users").fetchone()[0])
    print("Pipeline rows:", cur.execute("SELECT count(*) FROM v4_pipeline").fetchone()[0])
    print("Projects rows:", cur.execute("SELECT count(*) FROM v4_projects").fetchone()[0])
    print("Lists items:", cur.execute("SELECT count(*) FROM v4_lists").fetchone()[0])
    con.close()

if __name__ == '__main__':
    migrate()


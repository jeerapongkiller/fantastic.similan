# Fantastic Similan TMS — Claude Code Guidelines
> Legacy Tour Management System (TMS) | PHP + Vuexy + MySQLi | Phang-Nga, Thailand

---

## ⚠️ READ FIRST — Production System

```
ระบบนี้ลูกค้า (Fantastic Similan Travel) ใช้งานจริงอยู่บน production
- ไม่มีระบบ AI / LINE webhook / Gemini / Google Maps (เป็น TMS รุ่นเก่า)
- ทุกการแก้ไขกระทบข้อมูลจริงของลูกค้า → แก้ให้น้อยที่สุด (minimal change)
- ห้าม refactor ใหญ่ / เปลี่ยนโครงสร้าง / เปลี่ยนชื่อไฟล์ หรือฟังก์ชันที่ถูกเรียกใช้อยู่
- ก่อนแก้ booking / invoice / receipt / order → แจ้งผู้ใช้ก่อนทุกครั้ง
```

### Database Connection Warning
`controllers/DB.php` สลับ local / production ด้วยการ comment บรรทัด config:

| Env | Host | Database |
|---|---|---|
| Production | remote host (ดูใน DB.php) | `dbxwoccqevnkts` |
| Local | `localhost` | `fantastic_similan` |

```
❗ ตรวจ DB.php ทุกครั้งก่อนทดสอบ — ถ้ายังชี้ไป production
   การทดสอบ create/edit/delete จะเขียนลงข้อมูลจริงของลูกค้า
❗ ห้ามรัน SQL ที่แก้ไขข้อมูล (INSERT/UPDATE/DELETE/ALTER) กับ production
   โดยไม่ได้รับอนุญาตจากผู้ใช้
❗ ห้าม commit / แสดง password ใน DB.php
```

---

## Project Overview

| Item | Detail |
|---|---|
| System Name | Fantastic Similan Travel (TMS) |
| Purpose | Tour Operations Management (booking, order, invoice, receipt) |
| Server | XAMPP (local), PHP 7.x–8.x, MariaDB/MySQL |
| Timezone | Asia/Bangkok (set in `config/env.php`) |
| UI Template | Vuexy Admin Dashboard (Bootstrap 4 + Feather Icons) |
| Entry Point | `index.php?pages=module/action` |
| PDF | mPDF (`library/mpdf`) |

---

## Architecture

### Routing Pattern
```
index.php → require config/env.php → include 'layouts/' . $_GET['pages'] . '-main.php'
ถ้าไม่มี $_SESSION['supplier']['id'] หรือ layout ไม่พบ → กลับไปหน้า login

ตัวอย่าง:
index.php?pages=booking/list
index.php?pages=invoice/create
index.php?pages=order-boat/manage
```

### Folder Structure
```
fantastic.similan/
├── index.php              # Entry point + routing + session check
├── config/env.php         # session_start, timezone, company info, $hostPageUrl
├── controllers/           # Business logic (class extends DB)
│   ├── DB.php             # MySQLi connection ($this->connection)
│   ├── Booking.php, Order.php, Invoice.php, Receipt.php, Report.php ...
├── layouts/
│   ├── header.php, footer.php, main-menu.php
│   └── [feature]/[action]-main.php   # Full <head> + CSS/JS includes + page include
├── pages/
│   └── [feature]/
│       ├── list.php, create.php, manage.php, print.php ...
│       └── function/      # AJAX endpoints (POST + 'action' param)
├── library/mpdf/          # PDF generation
├── app-assets/            # Vuexy template assets (DO NOT MODIFY)
├── assets/css/style.css   # Shared custom CSS
└── storage/uploads/       # File uploads
```

### Main Modules
```
Booking     : booking
Orders      : order-boat, order-driver, order-guide, order-job, order-job-boat,
              order-pickup, order-dropoff, order-agent
Financial   : invoice, receipt, quotation, report
Master data : agent, boat, boat-type, captain, crew, car, car-category, car-type,
              driver, driver-assistant, guide, hotel, park, place, province, zone,
              bank, bank-account, branch, category-items, extra_charge, tour,
              allotments, supplier, user, review, backup
```

### Layout Convention (MUST FOLLOW)
```
layouts/[feature]/[action]-main.php   ← <head>, vendor CSS/JS, menu, include page
pages/[feature]/[action].php          ← Content + page CSS + JavaScript

Example:
layouts/invoice/list-main.php
pages/invoice/list.php
```

> โฟลเดอร์ที่ขึ้นต้นด้วย `_` (เช่น `pages/_invoice`, `layouts/_order-boat`) เป็นเวอร์ชันเก่าที่ถูกลบแล้ว — ไม่ต้องอ้างอิง

---

## Language & Communication

- **Reply in Thai** for all explanations
- **Code and variable names in English**
- **Inline comments in English**
- **UI strings** — follow the existing page (mostly English/Thai mixed)
- If unsure about requirements → ASK before writing code

---

## PHP Guidelines

### Controller Pattern (existing)
```php
<?php
require_once __DIR__ . '/DB.php';

class Invoice extends DB
{
    public $response = false;

    public function __construct()
    {
        parent::__construct();
    }

    public function getById($id)
    {
        $query = "SELECT * FROM invoices WHERE id = ?";
        $statement = $this->connection->prepare($query);
        $statement->bind_param("i", $id);
        $statement->execute();
        return $statement->get_result()->fetch_assoc();
    }
}
```

- Connection property คือ `$this->connection` (ไม่ใช่ `$this->conn`)
- ใช้ prepared statements สำหรับ query ใหม่เสมอ
- โค้ดเก่าบางส่วนยังใช้ raw query — แก้เฉพาะจุดที่เกี่ยวกับงาน ไม่ต้องไล่ migrate ทั้งไฟล์ (เสี่ยงกระทบ production)
- ห้ามสร้าง `new mysqli(...)` ใหม่ — ใช้ class ที่ extends DB

### AJAX Endpoint Pattern (pages/[module]/function/)
```php
<?php
include_once('../../../config/env.php');
include_once('../../../controllers/Invoice.php');

$invObj = new Invoice();

if (isset($_POST['action']) && $_POST['action'] == "create") {
    $branch = !empty($_POST['branch']) ? (int) $_POST['branch'] : 0;
    $note   = !empty($_POST['note']) ? trim($_POST['note']) : '';
    // ...
    $response = $invObj->insert_data(/* ... */);
    echo $response;
}
```

- Endpoint รับข้อมูลแบบ `$_POST` (form data) พร้อม `action` — ทำตาม pattern นี้ ไม่ต้องเปลี่ยนเป็น JSON body
- Response format ให้ตรงกับที่ JS ฝั่ง page คาดหวังอยู่เดิม (อ่าน JS ก่อนแก้)
- Cast ตัวเลขด้วย `(int)` / `(float)` และ validate วันที่ก่อนใช้งาน

---

## CSS Guidelines

### Workflow
```
1. อ่าน assets/css/style.css ก่อน — มี class อยู่แล้วใช้ซ้ำ
2. ใช้หลายหน้า → เพิ่มใน assets/css/style.css
3. ใช้หน้าเดียว → เขียนใน <style> ของ page หรือ layout นั้น
```

### Rules
```
✅ ใช้ Vuexy/Bootstrap classes ก่อน (card, btn, badge, table)
✅ Class ใหม่ใช้ prefix ชื่อ module: .invoice__total-row, .order__boat-card
❌ ห้าม override .card, .btn-*, .table-*, .badge-* (Vuexy core)
❌ ห้ามแก้ app-assets/
❌ หลีกเลี่ยง !important
```

---

## JavaScript Guidelines

```
✅ jQuery + $.ajax() (POST form data / FormData + action)
✅ SweetAlert2 / Select2 / Flatpickr / DataTables (โหลดใน layout แล้ว)
✅ feather.replace() หลังเพิ่ม DOM ใหม่
✅ ใส่ error handling ทุก AJAX call
✅ const / let สำหรับโค้ดใหม่
❌ console.log() ค้างใน production
❌ echo ค่า $_GET / $_POST ลง JS ตรงๆ — ใช้ json_encode()
```

> ถ้าต้องใช้ library เพิ่ม ให้ตรวจก่อนว่า layout ของหน้านั้นโหลดไว้หรือยัง

---

## Security Rules

```php
// Output encoding
echo htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8');

// Safe JSON to JS
const data = <?php echo json_encode($data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>;

// Auth (session started in config/env.php)
if (empty($_SESSION['supplier']['id'])) { exit; }
```

---

## Business Logic

### Financial Calculations
```
VAT 7% | Withholding Tax 1% / 3% | Discount: baht หรือ percent
Invoice no: IN-0000001 (running number จาก checkinvno())
```
> การคำนวณเงินและ running number ต้องแม่นยำ 100% — ถ้าไม่แน่ใจสูตรเดิม ให้อ่านโค้ดเดิมหรือถามก่อน ห้ามเดา

### Personnel Assignment
```
Driver / Driver Assistant → car jobs (order-driver, order-pickup, order-dropoff)
Captain / Crew            → boat (order-boat, order-job-boat)
Guide                     → tour group (order-guide)
```

### Date Handling
```php
// Store: Y-m-d  |  Display: d/m/Y
echo date('d/m/Y', strtotime($date));
```

---

## Print Pages

- `pages/[module]/print.php` — ใช้ mPDF (`library/mpdf`) หรือ HTML print
- ข้อมูลบริษัทสำหรับหัวเอกสารอยู่ใน `$main_document` (`config/env.php`)

---

## What NOT to Do

```
❌ ทดสอบ create/edit/delete ขณะ DB.php ชี้ไป production
❌ แก้ไข / ลบข้อมูลใน production database โดยไม่ได้รับอนุญาต
❌ Refactor ใหญ่ หรือเปลี่ยน function signature ที่ถูกเรียกหลายที่
❌ แก้ app-assets/ (Vuexy core)
❌ Commit password / credential
❌ เปลี่ยน URL pattern ?pages=module/action
❌ เพิ่มฟีเจอร์ AI / LINE / Gemini (ไม่มีในระบบนี้ เว้นแต่ผู้ใช้ขอ)
```

---

## Priority Order

```
1. Production Safety — ไม่ทำให้ระบบที่ลูกค้าใช้อยู่พัง / ข้อมูลเสีย
2. Data Accuracy     — booking, invoice, receipt ต้องแม่นยำ 100%
3. Security          — prepared statements, output encoding, session check
4. Consistency       — ทำตาม pattern เดิมของโปรเจกต์
5. Mobile Support / Performance — เมื่อจำเป็น
```

---

## Quick Reference

```
Entry point  : index.php?pages=[module]/[action]
App config   : config/env.php (session, timezone, company info)
DB class     : controllers/DB.php ($this->connection)
DB local     : fantastic_similan
DB production: dbxwoccqevnkts  ⚠️ live customer data
Auth check   : $_SESSION['supplier']['id']
Timezone     : Asia/Bangkok
Icons        : Feather Icons
UI libs      : Select2, Flatpickr, DataTables, SweetAlert2
PDF          : mPDF (library/mpdf)
```

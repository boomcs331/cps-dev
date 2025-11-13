# ฟีเจอร์แสดงรายละเอียดการรับเข้าพร้อม QR Code (ออฟไลน์)

## คุณสมบัติหลัก

### 1. แสดงรายละเอียดการรับเข้าวัตถุดิบ
- ข้อมูลใบรับเข้า (เลขที่ใบรับ, วันที่, ผู้จำหน่าย)
- ข้อมูลวัตถุดิบ (รหัส, ชื่อ, หน่วยนับ, คลัง)
- สรุปจำนวน (จำนวนรับเข้า, จำนวนต่อกล่อง, กล่องเต็ม, ชิ้นในกล่องสุดท้าย)

### 2. QR Code สำหรับแต่ละกล่อง
- สร้าง QR Code อัตโนมัติสำหรับทุกกล่อง
- แสดงข้อมูลกล่อง (หมายเลขกล่อง, จำนวนชิ้น, สถานะ)
- รองรับการใช้งานแบบออฟไลน์ (ไม่ต้องเชื่อมต่ออินเทอร์เน็ต)

### 3. การพิมพ์
- รองรับการพิมพ์ที่เหมาะสม
- ปรับขนาด QR Code อัตโนมัติเมื่อพิมพ์
- จัดเรียง layout ให้เหมาะกับกระดาษ A4

### 4. การใช้งานแบบออฟไลน์
- ใช้ QR Code library ที่เก็บไว้ในโปรเจค
- ไม่ต้องพึ่งพา CDN หรือการเชื่อมต่ออินเทอร์เน็ต
- ทำงานได้แม้ไม่มีเน็ต

## ไฟล์ที่เกี่ยวข้อง

### Controllers
- `controllers/MaterialController.php` - เพิ่ม method `receiptDetail()`

### Models  
- `models/Material.php` - เพิ่ม method `getReceiptDetail()`

### Views
- `views/materials/receipt/detail.php` - หน้าแสดงรายละเอียดการรับเข้า

### Assets
- `lib/js/qrcode/qrcode.min.js` - QR Code library (ออฟไลน์)
- `lib/css/receipt-detail.css` - CSS สำหรับหน้ารายละเอียด

### Routes
- `/materials/receiptDetail/{id}` - URL สำหรับเข้าถึงหน้ารายละเอียด

## วิธีการใช้งาน

### 1. เข้าถึงหน้ารายละเอียด
```
http://localhost/cps-dev/?url=materials/receiptDetail/1
```

### 2. จากหน้า Materials
1. ไปที่แท็บ "รับเข้าวัตถุดิบ"
2. คลิกปุ่ม "QR Code" ในคอลัมน์จัดการ
3. ระบบจะแสดงหน้ารายละเอียดพร้อม QR Code

### 3. การพิมพ์
- คลิกปุ่ม "พิมพ์" ที่มุมขวาบน
- ระบบจะปรับ layout อัตโนมัติสำหรับการพิมพ์

## โครงสร้างข้อมูล

### ตาราง material_receipts
- `id` - รหัสใบรับเข้า
- `receipt_no` - เลขที่ใบรับ
- `receipt_date` - วันที่รับเข้า
- `supplier_name` - ชื่อผู้จำหน่าย
- `location_id` - รหัสคลัง

### ตาราง material_receipt_items
- `receipt_id` - รหัสใบรับเข้า (FK)
- `material_id` - รหัสวัตถุดิบ (FK)
- `received_qty` - จำนวนที่รับเข้า
- `packing_qty` - จำนวนต่อกล่อง
- `full_box_count` - จำนวนกล่องเต็ม
- `partial_box_qty` - จำนวนชิ้นในกล่องสุดท้าย
- `total_box_count` - จำนวนกล่องรวม
- `lot_no` - หมายเลข Lot

### ตาราง material_stock_lots
- `receipt_item_id` - รหัสรายการรับเข้า (FK)
- `material_id` - รหัสวัตถุดิบ (FK)
- `lot_no` - หมายเลข Lot
- `pack_no` - หมายเลขกล่อง
- `pack_size` - จำนวนชิ้นในกล่อง
- `qr_code` - รหัส QR Code
- `status` - สถานะ (AVAILABLE, USED, RESERVED, etc.)

## การทดสอบ

### 1. เพิ่มข้อมูลตัวอย่าง
```sql
-- รันไฟล์ test_data.sql เพื่อเพิ่มข้อมูลตัวอย่าง
```

### 2. ทดสอบการแสดงผล
1. เข้าสู่ระบบ
2. ไปที่หน้า Materials
3. คลิกแท็บ "รับเข้าวัตถุดิบ"
4. คลิกปุ่ม "QR Code" ในรายการใดรายการหนึ่ง

### 3. ทดสอบการพิมพ์
1. เข้าหน้ารายละเอียดการรับเข้า
2. คลิกปุ่ม "พิมพ์"
3. ตรวจสอบว่า layout ปรับตัวถูกต้อง

## คุณสมบัติเพิ่มเติม

### 1. Responsive Design
- รองรับการแสดงผลบนหน้าจอขนาดต่างๆ
- ปรับ layout อัตโนมัติสำหรับมือถือและแท็บเล็ต

### 2. Animation
- QR Code แสดงผลแบบ fade-in
- Hover effects บน QR Code cards

### 3. Error Handling
- จัดการ error เมื่อไม่สามารถสร้าง QR Code ได้
- แสดงข้อความแจ้งเตือนที่เหมาะสม

### 4. Copy to Clipboard
- คลิกที่รหัส QR Code เพื่อคัดลอก
- แสดง notification เมื่อคัดลอกสำเร็จ

## การปรับแต่ง

### 1. ขนาด QR Code
แก้ไขใน `views/materials/receipt/detail.php`:
```javascript
QRCode.toCanvas(canvas, qrData, {
    width: 120,    // ปรับขนาดความกว้าง
    height: 120,   // ปรับขนาดความสูง
    margin: 2,     // ปรับขอบ
    // ...
});
```

### 2. สี QR Code
```javascript
color: {
    dark: '#000000',    // สีของ QR Code
    light: '#FFFFFF'    // สีพื้นหลัง
}
```

### 3. การจัดเรียงกล่อง
แก้ไขใน `lib/css/receipt-detail.css`:
```css
.qr-grid {
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
}
```

## ข้อกำหนดระบบ

### Browser Support
- Chrome 60+
- Firefox 55+
- Safari 11+
- Edge 79+

### PHP Requirements
- PHP 7.4+
- PDO Extension
- MySQL/MariaDB

### JavaScript Libraries
- QRCode.js (รวมอยู่ในโปรเจค)
- Bootstrap 5
- Font Awesome 6
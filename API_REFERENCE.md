# API Reference - E-Perpus Library System

## Overview
Complete REST API for library management with 4 modules: Students, Books, Loans, Returns, and Fines.

**Base URL:** `http://127.0.0.1:8000/api`

---

## 1. STUDENT API (`/api/students`)

### Get All Students
```http
GET /api/students?page=1&per_page=15
```

### Create Student
```http
POST /api/students
Content-Type: application/json

{
  "nim": "210001",
  "nama": "John Doe",
  "email": "john@example.com",
  "no_telepon": "081234567890",
  "jurusan": "Teknik Informatika",
  "angkatan": 2021,
  "status": "aktif"
}
```

### Get Student by ID
```http
GET /api/students/{id}
```

### Update Student
```http
PUT /api/students/{id}
Content-Type: application/json

{
  "nama": "Jane Doe",
  "email": "jane@example.com",
  "no_telepon": "081234567891"
}
```

### Delete Student
```http
DELETE /api/students/{id}
```

### Search Students
```http
POST /api/students/search
Content-Type: application/json

{
  "query": "210001",
  "limit": 10
}
```

---

## 2. BOOK API (`/api/books`)

### Get Book Stock by Name
```http
POST /api/books/stock
Content-Type: application/json

{
  "book_name": "Laravel"
}
```

### Search Books
```http
POST /api/books/search
Content-Type: application/json

{
  "keyword": "programming",
  "limit": 5
}
```

### Get Available Books
```http
POST /api/books/available
Content-Type: application/json

{
  "limit": 10
}
```

### Get Books by Category
```http
POST /api/books/category
Content-Type: application/json

{
  "category": "programming",
  "limit": 10
}
```

### Get Popular Books
```http
POST /api/books/popular
Content-Type: application/json

{
  "limit": 5
}
```

### Get Book Statistics
```http
GET /api/books/stats
```

---

## 3. LOAN API (`/api/loans`)

### Get All Loans
```http
GET /api/loans?page=1&per_page=15&status=dipinjam
```
Status filter: `dipinjam`, `dikembalikan`, `pending`

### Create Loan
```http
POST /api/loans
Content-Type: application/json

{
  "mahasiswa_id": 1,
  "buku_id": 1,
  "tanggal_kembali_rencana": "2026-06-15"
}
```

### Get Loan by ID
```http
GET /api/loans/{id}
```

### Update Loan
```http
PUT /api/loans/{id}
Content-Type: application/json

{
  "tanggal_kembali_rencana": "2026-06-20",
  "status": "dipinjam"
}
```

### Delete Loan
```http
DELETE /api/loans/{id}
```

### Search Loans
```http
POST /api/loans/search
Content-Type: application/json

{
  "query": "john",
  "limit": 10
}
```

### Get Active Loans
```http
POST /api/loans/active
Content-Type: application/json

{
  "limit": 50
}
```

---

## 4. RETURN API (`/api/returns`) ⭐

### Process Return (Auto-calculate Fine)
```http
POST /api/returns/process
Content-Type: application/json

{
  "peminjaman_id": 1,
  "tanggal_kembali": "2026-06-16",
  "kondisi_buku": "baik"
}
```

Response:
```json
{
  "success": true,
  "peminjaman_id": 1,
  "hari_terlambat": 1,
  "denda_amount": 5000,
  "denda_formatted": "Rp 5.000",
  "payment_required": true,
  "denda_id": 1
}
```

### Get All Returns
```http
GET /api/returns?page=1&per_page=15
```

### Get Return by ID
```http
GET /api/returns/{id}
```

### Confirm Payment (Mark Fine as Paid)
```http
POST /api/returns/confirm-payment/{denda_id}
Content-Type: application/json

{
  "payment_method": "cash",
  "payment_notes": "Dibayar langsung"
}
```

---

## 5. FINE API (`/api/fines`)

### Get All Fines
```http
GET /api/fines?page=1&per_page=15&status=belum
```
Status filter: `belum`, `terbayar`

### Get Fine by ID
```http
GET /api/fines/{id}
```

### Calculate Fine
```http
POST /api/fines/calculate
Content-Type: application/json

{
  "peminjaman_id": 1
}
```

### Record Fine Payment
```http
POST /api/fines/pay
Content-Type: application/json

{
  "denda_id": 1,
  "payment_method": "transfer"
}
```

### Get Unpaid Fines
```http
POST /api/fines/unpaid
Content-Type: application/json

{
  "limit": 50
}
```

Response:
```json
{
  "count": 5,
  "total_unpaid": 25000,
  "total_unpaid_formatted": "Rp 25.000",
  "fines": [...]
}
```

### Get Fine Statistics
```http
GET /api/fines/stats
```

Response:
```json
{
  "total_fines": 50000,
  "total_fines_formatted": "Rp 50.000",
  "paid_fines": 30000,
  "paid_fines_formatted": "Rp 30.000",
  "unpaid_fines": 20000,
  "unpaid_fines_formatted": "Rp 20.000",
  "total_records": 10,
  "paid_records": 7,
  "unpaid_records": 3,
  "payment_rate": 70
}
```

---

## Key Features

### Auto Fine Calculation
- Fine rate: **Rp 5,000 per day** (configurable in controllers)
- Calculated automatically when book is returned
- Formula: `(return_date - due_date) × daily_rate`
- If no late days → fine = Rp 0 (marked as terbayar)

### Book Stock Management
- Auto-decremented when loan created
- Auto-incremented when return processed

### Audit Trail
- Every return and payment creates History record
- Tracks action, timestamp, and user ID

### Status Tracking
- **Loan Status:** dipinjam, dikembalikan, pending
- **Fine Status:** belum (unpaid), terbayar (paid)

---

## Testing with Postman

1. **Create Student**
   ```
   POST http://127.0.0.1:8000/api/students
   ```

2. **Create Loan**
   ```
   POST http://127.0.0.1:8000/api/loans
   ```

3. **Process Return** (auto-calculates fine)
   ```
   POST http://127.0.0.1:8000/api/returns/process
   ```

4. **Check Fine Generated**
   ```
   GET http://127.0.0.1:8000/api/fines/{fine_id}
   ```

5. **Pay Fine**
   ```
   POST http://127.0.0.1:8000/api/fines/pay
   ```

6. **Verify All Fines Stats**
   ```
   GET http://127.0.0.1:8000/api/fines/stats
   ```

---

## Error Responses

### 404 Not Found
```json
{
  "success": false,
  "message": "Resource tidak ditemukan"
}
```

### 422 Validation Error
```json
{
  "success": false,
  "message": "Validation error description"
}
```

### 400 Bad Request
```json
{
  "success": false,
  "message": "Error description"
}
```

---

## Notes

- All timestamps in UTC (ISO 8601 format)
- Pagination default: 15 items per page
- Search limit default: 10 items
- All money amounts in Indonesian Rupiah (Rp)
- API is public (no authentication required)

# Software Requirements Specification (SRS)
## SiPusaka - Sistem Informasi Perpustakaan Digital

**Version:** 1.0  
**Date:** May 28, 2026  
**Status:** Active Development  
**Prepared by:** Development Team

---

## Table of Contents
1. [Introduction](#1-introduction)
2. [Overall Description](#2-overall-description)
3. [System Features](#3-system-features)
4. [External Interface Requirements](#4-external-interface-requirements)
5. [System Requirements](#5-system-requirements)
6. [Database Requirements](#6-database-requirements)
7. [Appendices](#7-appendices)

---

## 1. Introduction

### 1.1 Purpose
Dokumen ini menjelaskan spesifikasi kebutuhan software untuk SiPusaka (Sistem Informasi Perpustakaan Digital). Dokumen ini ditujukan untuk development team, QA team, dan stakeholders untuk memahami requirements sistem secara detail.

### 1.2 Scope
SiPusaka adalah web-based application yang mengelola:
- Koleksi buku fisik dan digital (e-book)
- Peminjaman dan pengembalian buku
- Manajemen mahasiswa dan admin
- Review dan rating buku
- Chat support
- Reporting dan analytics

### 1.3 Definitions, Acronyms, and Abbreviations
- **SRS:** Software Requirements Specification
- **UI:** User Interface
- **API:** Application Programming Interface
- **CRUD:** Create, Read, Update, Delete
- **QR:** Quick Response
- **PDF:** Portable Document Format
- **SMTP:** Simple Mail Transfer Protocol
- **RBAC:** Role-Based Access Control
- **NIM:** Nomor Induk Mahasiswa

### 1.4 References
- PRD.md - Product Requirements Document
- Laravel 11.x Documentation
- MySQL 8.0 Documentation
- Bootstrap 5 Documentation

### 1.5 Overview
Dokumen ini terbagi menjadi beberapa section yang menjelaskan functional requirements, non-functional requirements, interface requirements, dan database design.

---

## 2. Overall Description

### 2.1 Product Perspective
SiPusaka adalah standalone web application yang dibangun menggunakan Laravel framework. Sistem ini mengintegrasikan:
- Web server (Apache/Nginx)
- Database server (MySQL)
- Email service (SMTP)
- File storage system
- QR Code generation library

### 2.2 Product Functions
**Major Functions:**
1. Katalog dan pencarian buku
2. Peminjaman buku dengan QR Code
3. E-book reader dengan PDF streaming
4. Review dan rating system
5. Admin dashboard dan management
6. Email notifications
7. Chat support system
8. Reporting dan export data

### 2.3 User Classes and Characteristics

#### **Public User**
- **Technical Expertise:** Basic
- **Frequency of Use:** Occasional
- **Functions:** Browse katalog, view detail buku, registrasi

#### **Mahasiswa**
- **Technical Expertise:** Basic to Intermediate
- **Frequency of Use:** Regular (weekly)
- **Functions:** Pinjam buku, baca e-book, review, chat support

#### **Admin**
- **Technical Expertise:** Intermediate
- **Frequency of Use:** Daily
- **Functions:** Manage buku, approve peminjaman, scan QR, manage denda

#### **Super Admin**
- **Technical Expertise:** Advanced
- **Frequency of Use:** Weekly
- **Functions:** User management, system configuration, full access

### 2.4 Operating Environment
- **Client:** Web browser (Chrome, Firefox, Safari, Edge)
- **Server:** Linux/Windows Server
- **Web Server:** Apache 2.4+ or Nginx 1.18+
- **Database:** MySQL 8.0+
- **PHP:** 8.2+
- **Framework:** Laravel 11.x

### 2.5 Design and Implementation Constraints
- Must use Laravel framework
- Must support responsive design
- Must comply with data privacy regulations
- File upload limited to 50MB
- Session timeout after 2 hours of inactivity

### 2.6 Assumptions and Dependencies
- Users have stable internet connection
- Email service is available and configured
- QR Code scanner devices are available for admin
- PDF files are properly formatted

---

## 3. System Features

### 3.1 User Authentication and Authorization

#### 3.1.1 Admin Login
**Priority:** High  
**Description:** Admin dapat login menggunakan email dan password

**Functional Requirements:**
- **FR-AUTH-001:** System harus menyediakan form login dengan field email dan password
- **FR-AUTH-002:** System harus validasi credentials terhadap database
- **FR-AUTH-003:** System harus hash password menggunakan bcrypt
- **FR-AUTH-004:** System harus create session setelah login berhasil
- **FR-AUTH-005:** System harus redirect ke dashboard setelah login
- **FR-AUTH-006:** System harus tampilkan error message jika credentials salah
- **FR-AUTH-007:** System harus implement CSRF protection
- **FR-AUTH-008:** System harus logout user setelah 2 jam inactivity

**Input:**
- Email (string, required, valid email format)
- Password (string, required, min 8 characters)

**Output:**
- Success: Redirect to admin dashboard
- Failure: Error message "Email atau password salah"

**Error Handling:**
- Invalid email format: "Format email tidak valid"
- Empty fields: "Email dan password wajib diisi"
- Account not found: "Akun tidak ditemukan"

#### 3.1.2 Mahasiswa Login
**Priority:** High  
**Description:** Mahasiswa dapat login menggunakan NIM dan referral token

**Functional Requirements:**
- **FR-AUTH-009:** System harus menyediakan form login dengan field NIM dan token
- **FR-AUTH-010:** System harus validasi NIM dan token terhadap database
- **FR-AUTH-011:** System harus check status mahasiswa (harus Approved)
- **FR-AUTH-012:** System harus create session setelah login berhasil
- **FR-AUTH-013:** System harus redirect ke mahasiswa dashboard
- **FR-AUTH-014:** System harus tampilkan error jika status bukan Approved

**Input:**
- NIM (string, required, numeric)
- Referral Token (string, required, 6 characters, uppercase)

**Output:**
- Success: Redirect to mahasiswa dashboard
- Failure: Error message dengan alasan spesifik

---

### 3.2 Katalog dan Pencarian Buku

#### 3.2.1 Display Katalog
**Priority:** High  
**Description:** Menampilkan semua buku dalam grid layout

**Functional Requirements:**
- **FR-CAT-001:** System harus fetch semua buku dari database
- **FR-CAT-002:** System harus tampilkan cover, judul, penerbit, jenis
- **FR-CAT-003:** System harus tampilkan badge status (Tersedia/Habis)
- **FR-CAT-004:** System harus implement pagination (12 items per page)
- **FR-CAT-005:** System harus increment view_count saat buku diklik
- **FR-CAT-006:** System harus support filter by jenis buku
- **FR-CAT-007:** System harus responsive untuk mobile/tablet/desktop

**Query Parameters:**
- page (integer, optional, default: 1)
- jenis (string, optional, values: Fisik|Ebook|Hybrid)

**Output:**
- List of books dengan pagination
- Total count
- Current page info

#### 3.2.2 Search Buku
**Priority:** High  
**Description:** Pencarian buku berdasarkan judul atau penerbit

**Functional Requirements:**
- **FR-CAT-008:** System harus accept search query via GET parameter
- **FR-CAT-009:** System harus search di field nama_buku dan penerbit
- **FR-CAT-010:** System harus case-insensitive search
- **FR-CAT-011:** System harus support partial matching
- **FR-CAT-012:** System harus return empty result jika tidak ada match
- **FR-CAT-013:** System harus highlight search term di hasil

**Input:**
- q (string, required, min 3 characters)

**Output:**
- Filtered list of books
- Search term
- Result count

#### 3.2.3 Detail Buku
**Priority:** High  
**Description:** Menampilkan informasi lengkap buku

**Functional Requirements:**
- **FR-CAT-014:** System harus fetch buku by ID
- **FR-CAT-015:** System harus tampilkan semua informasi buku
- **FR-CAT-016:** System harus fetch dan tampilkan reviews
- **FR-CAT-017:** System harus calculate average rating
- **FR-CAT-018:** System harus tampilkan rating distribution
- **FR-CAT-019:** System harus increment view_count
- **FR-CAT-020:** System harus tampilkan tombol sesuai jenis buku
- **FR-CAT-021:** System harus return 404 jika buku tidak ditemukan

**Input:**
- id (integer, required, exists in bukus table)

**Output:**
- Complete book information
- Reviews list
- Average rating
- Rating counts per star

---

### 3.3 Peminjaman Buku

#### 3.3.1 Submit Peminjaman
**Priority:** High  
**Description:** Mahasiswa mengajukan peminjaman buku fisik

**Functional Requirements:**
- **FR-PINJAM-001:** System harus validate semua input fields
- **FR-PINJAM-002:** System harus check NIM exists di database
- **FR-PINJAM-003:** System harus validate referral token
- **FR-PINJAM-004:** System harus check stok buku tersedia
- **FR-PINJAM-005:** System harus generate unique booking_id (format: BK-YYYYMMDD-XXXX)
- **FR-PINJAM-006:** System harus set status "Pending"
- **FR-PINJAM-007:** System harus set tanggal_pinjam = today
- **FR-PINJAM-008:** System harus set tanggal_kembali = today + 7 days
- **FR-PINJAM-009:** System harus create peminjaman record
- **FR-PINJAM-010:** System harus send email notification ke mahasiswa
- **FR-PINJAM-011:** System harus return booking_id ke user

**Input:**
```json
{
  "nama": "string, required",
  "nim": "string, required, numeric",
  "jurusan": "string, required",
  "no_telepon": "string, required",
  "referral_token": "string, required, 6 chars",
  "buku_ids": "array, required, min 1 item"
}
```

**Output:**
```json
{
  "success": true,
  "message": "Peminjaman berhasil diajukan",
  "booking_ids": ["BK-20260528-0001"]
}
```

**Business Rules:**
- Satu mahasiswa bisa pinjam multiple buku sekaligus
- Setiap buku mendapat booking_id terpisah
- Batas peminjaman 7 hari
- Denda Rp10.000/hari untuk keterlambatan
- E-book tidak bisa dipinjam (hanya dibaca)

#### 3.3.2 Approve Peminjaman (Admin)
**Priority:** High  
**Description:** Admin approve peminjaman dan generate QR Code

**Functional Requirements:**
- **FR-PINJAM-012:** System harus validate peminjaman exists
- **FR-PINJAM-013:** System harus check status = "Pending"
- **FR-PINJAM-014:** System harus generate QR Code berisi booking_id
- **FR-PINJAM-015:** System harus save QR Code image
- **FR-PINJAM-016:** System harus update status ke "Approved"
- **FR-PINJAM-017:** System harus send email dengan QR Code attachment
- **FR-PINJAM-018:** System harus log action ke history

**Input:**
- peminjaman_id (integer, required)

**Output:**
- Success message
- QR Code generated
- Email sent confirmation

#### 3.3.3 Scan QR Code (Admin)
**Priority:** High  
**Description:** Admin scan QR Code saat mahasiswa ambil buku

**Functional Requirements:**
- **FR-PINJAM-019:** System harus decode QR Code untuk get booking_id
- **FR-PINJAM-020:** System harus fetch peminjaman by booking_id
- **FR-PINJAM-021:** System harus validate status = "Approved"
- **FR-PINJAM-022:** System harus update status ke "Dipinjam"
- **FR-PINJAM-023:** System harus decrement stok_tersedia buku
- **FR-PINJAM-024:** System harus increment borrow_count buku
- **FR-PINJAM-025:** System harus log action ke history

**Input:**
- booking_id (string, from QR Code)

**Output:**
- Peminjaman details
- Updated status
- Success confirmation

---

### 3.4 E-book Reader

#### 3.4.1 Access E-book
**Priority:** High  
**Description:** Mahasiswa membaca e-book dalam browser

**Functional Requirements:**
- **FR-EBOOK-001:** System harus validate buku exists dan jenis = Ebook/Hybrid
- **FR-EBOOK-002:** System harus check file_ebook exists
- **FR-EBOOK-003:** System harus render PDF viewer page
- **FR-EBOOK-004:** System harus embed PDF.js library
- **FR-EBOOK-005:** System harus provide navigation controls
- **FR-EBOOK-006:** System harus support zoom in/out
- **FR-EBOOK-007:** System harus support fullscreen mode
- **FR-EBOOK-008:** System harus increment view_count

**Input:**
- id (integer, buku ID)

**Output:**
- PDF viewer page
- PDF content streamed

#### 3.4.2 Stream PDF
**Priority:** High  
**Description:** Stream PDF file ke browser

**Functional Requirements:**
- **FR-EBOOK-009:** System harus validate file exists
- **FR-EBOOK-010:** System harus set proper headers (Content-Type: application/pdf)
- **FR-EBOOK-011:** System harus support range requests untuk streaming
- **FR-EBOOK-012:** System harus prevent direct file download
- **FR-EBOOK-013:** System harus handle large files (up to 50MB)

**Input:**
- id (integer, buku ID)

**Output:**
- PDF file stream
- Proper HTTP headers

---

### 3.5 Review dan Rating

#### 3.5.1 Submit Review
**Priority:** Medium  
**Description:** Mahasiswa memberikan review dan rating

**Functional Requirements:**
- **FR-REVIEW-001:** System harus validate NIM dan referral token
- **FR-REVIEW-002:** System harus check mahasiswa exists dan approved
- **FR-REVIEW-003:** System harus validate rating (1-5)
- **FR-REVIEW-004:** System harus check duplicate review (1 per mahasiswa per buku)
- **FR-REVIEW-005:** System harus save review ke database
- **FR-REVIEW-006:** System harus update average rating buku
- **FR-REVIEW-007:** System harus return success response

**Input:**
```json
{
  "buku_id": "integer, required",
  "nim": "string, required",
  "referral_token": "string, required",
  "rating": "integer, required, 1-5",
  "comment": "string, optional, max 500 chars"
}
```

**Output:**
```json
{
  "success": true,
  "message": "Review berhasil ditambahkan"
}
```

**Business Rules:**
- Satu mahasiswa hanya bisa review satu kali per buku
- Rating wajib, comment optional
- Comment max 500 characters

#### 3.5.2 Get Reviews
**Priority:** Medium  
**Description:** Fetch semua review untuk buku tertentu

**Functional Requirements:**
- **FR-REVIEW-008:** System harus fetch reviews by buku_id
- **FR-REVIEW-009:** System harus join dengan mahasiswa table
- **FR-REVIEW-010:** System harus calculate average rating
- **FR-REVIEW-011:** System harus calculate rating distribution
- **FR-REVIEW-012:** System harus order by created_at DESC
- **FR-REVIEW-013:** System harus return empty array jika no reviews

**Input:**
- buku_id (integer, required)

**Output:**
```json
{
  "success": true,
  "reviews": [...],
  "average_rating": 4.5,
  "rating_count": 10,
  "rating_counts": {
    "5": 6,
    "4": 2,
    "3": 1,
    "2": 1,
    "1": 0
  }
}
```

---

### 3.6 Pengembalian Buku

#### 3.6.1 Scan QR Pengembalian
**Priority:** High  
**Description:** Admin scan QR Code saat mahasiswa kembalikan buku

**Functional Requirements:**
- **FR-KEMBALI-001:** System harus decode QR Code untuk get booking_id
- **FR-KEMBALI-002:** System harus fetch peminjaman by booking_id
- **FR-KEMBALI-003:** System harus validate status = "Dipinjam"
- **FR-KEMBALI-004:** System harus calculate hari keterlambatan
- **FR-KEMBALI-005:** System harus calculate denda jika terlambat (Rp10.000/hari)
- **FR-KEMBALI-006:** System harus update status ke "Dikembalikan"
- **FR-KEMBALI-007:** System harus set tanggal_dikembalikan = today
- **FR-KEMBALI-008:** System harus increment stok_tersedia buku
- **FR-KEMBALI-009:** System harus create denda record jika ada
- **FR-KEMBALI-010:** System harus log action ke history

**Input:**
- booking_id (string, from QR Code)

**Output:**
- Peminjaman details
- Denda amount (if any)
- Updated status
- Success confirmation

**Business Rules:**
- Denda = (hari_terlambat) × Rp10.000
- Hari terlambat = tanggal_dikembalikan - tanggal_kembali (jika > 0)
- Denda status default = "Belum Lunas"

---

### 3.7 Manajemen Mahasiswa

#### 3.7.1 Registrasi Mahasiswa
**Priority:** High  
**Description:** Mahasiswa mendaftar akun baru

**Functional Requirements:**
- **FR-MHS-001:** System harus validate semua input fields
- **FR-MHS-002:** System harus check NIM unique
- **FR-MHS-003:** System harus check email unique
- **FR-MHS-004:** System harus set status = "Pending"
- **FR-MHS-005:** System harus save mahasiswa ke database
- **FR-MHS-006:** System harus send email notification ke mahasiswa
- **FR-MHS-007:** System harus send notification ke admin

**Input:**
```json
{
  "nim": "string, required, unique, numeric",
  "nama": "string, required",
  "jurusan": "string, required",
  "email": "string, required, unique, valid email",
  "no_telepon": "string, optional"
}
```

**Output:**
```json
{
  "success": true,
  "message": "Registrasi berhasil. Tunggu approval dari admin."
}
```

#### 3.7.2 Approve Mahasiswa (Admin)
**Priority:** High  
**Description:** Admin approve registrasi dan generate token

**Functional Requirements:**
- **FR-MHS-008:** System harus validate mahasiswa exists
- **FR-MHS-009:** System harus check status = "Pending"
- **FR-MHS-010:** System harus generate random 6-digit alphanumeric token
- **FR-MHS-011:** System harus check token unique
- **FR-MHS-012:** System harus update status = "Approved"
- **FR-MHS-013:** System harus save referral_token
- **FR-MHS-014:** System harus send email dengan token
- **FR-MHS-015:** System harus log action

**Input:**
- mahasiswa_id (integer, required)

**Output:**
- Success message
- Generated token
- Email sent confirmation

**Business Rules:**
- Token format: 6 characters, uppercase alphanumeric
- Token harus unique
- Token dikirim via email

---

### 3.8 Chat Support

#### 3.8.1 Verify NIM untuk Chat
**Priority:** Medium  
**Description:** Mahasiswa verify NIM sebelum chat

**Functional Requirements:**
- **FR-CHAT-001:** System harus validate NIM exists
- **FR-CHAT-002:** System harus check status = "Approved"
- **FR-CHAT-003:** System harus create atau get existing chat session
- **FR-CHAT-004:** System harus return session_id

**Input:**
- nim (string, required)

**Output:**
```json
{
  "success": true,
  "session_id": "uuid",
  "mahasiswa": {...}
}
```

#### 3.8.2 Send Message
**Priority:** Medium  
**Description:** Send chat message (mahasiswa atau admin)

**Functional Requirements:**
- **FR-CHAT-005:** System harus validate session exists
- **FR-CHAT-006:** System harus validate message not empty
- **FR-CHAT-007:** System harus save message ke database
- **FR-CHAT-008:** System harus set sender_type (mahasiswa/admin)
- **FR-CHAT-009:** System harus broadcast message (real-time)
- **FR-CHAT-010:** System harus return message object

**Input:**
```json
{
  "session_id": "uuid, required",
  "message": "string, required, max 1000 chars",
  "sender_type": "mahasiswa|admin"
}
```

**Output:**
```json
{
  "success": true,
  "message": {...}
}
```

---

### 3.9 Reporting dan Export

#### 3.9.1 Export Buku ke PDF
**Priority:** Medium  
**Description:** Export daftar buku ke PDF

**Functional Requirements:**
- **FR-EXPORT-001:** System harus fetch semua buku
- **FR-EXPORT-002:** System harus generate PDF dengan proper formatting
- **FR-EXPORT-003:** System harus include header dan footer
- **FR-EXPORT-004:** System harus set filename dengan timestamp
- **FR-EXPORT-005:** System harus trigger download

**Output:**
- PDF file: buku_YYYYMMDD_HHMMSS.pdf

#### 3.9.2 Export Buku ke Excel
**Priority:** Medium  
**Description:** Export daftar buku ke Excel

**Functional Requirements:**
- **FR-EXPORT-006:** System harus fetch semua buku
- **FR-EXPORT-007:** System harus generate Excel dengan proper columns
- **FR-EXPORT-008:** System harus include header row
- **FR-EXPORT-009:** System harus set filename dengan timestamp
- **FR-EXPORT-010:** System harus trigger download

**Output:**
- Excel file: buku_YYYYMMDD_HHMMSS.xlsx

---

## 4. External Interface Requirements

### 4.1 User Interfaces

#### 4.1.1 General UI Requirements
- **UI-001:** All pages harus responsive (mobile, tablet, desktop)
- **UI-002:** Consistent color scheme (Navy, Gold, Cream)
- **UI-003:** Font: Plus Jakarta Sans
- **UI-004:** Icons: Font Awesome 6.4.0
- **UI-005:** Loading indicators untuk async operations
- **UI-006:** Toast notifications untuk feedback
- **UI-007:** Modal dialogs untuk confirmations
- **UI-008:** Breadcrumb navigation
- **UI-009:** Accessible (WCAG 2.1 Level AA)

#### 4.1.2 Public Pages
- **Homepage/Katalog:** Grid layout, search bar, filters
- **Detail Buku:** Cover, info, reviews, action buttons
- **Registrasi:** Form dengan validation
- **Cek Status:** Input booking ID, display status

#### 4.1.3 Mahasiswa Pages
- **Dashboard:** History peminjaman, denda, profile
- **E-book Reader:** PDF viewer dengan controls

#### 4.1.4 Admin Pages
- **Dashboard:** Statistics cards, charts, quick actions
- **Manajemen Buku:** DataTable dengan CRUD operations
- **Manajemen Mahasiswa:** DataTable dengan approval actions
- **Peminjaman:** DataTable dengan QR scan button
- **Pengembalian:** QR scan interface
- **Denda:** DataTable dengan payment tracking
- **Chat:** Chat interface dengan session list

### 4.2 Hardware Interfaces
- **QR Code Scanner:** Camera atau dedicated scanner device
- **Printer:** Untuk print QR Code (optional)

### 4.3 Software Interfaces

#### 4.3.1 Database Interface
- **Type:** MySQL 8.0+
- **Connection:** PDO via Laravel Eloquent ORM
- **Charset:** utf8mb4
- **Collation:** utf8mb4_unicode_ci

#### 4.3.2 Email Interface
- **Protocol:** SMTP
- **Provider:** Gmail, SendGrid, atau custom SMTP
- **Port:** 587 (TLS) atau 465 (SSL)
- **Authentication:** Username/Password

#### 4.3.3 File Storage Interface
- **Type:** Local filesystem atau Cloud storage
- **Path:** storage/app/public/
- **Symlink:** public/storage/
- **Allowed Types:** 
  - Images: jpg, jpeg, png (max 2MB)
  - PDF: pdf (max 50MB)

### 4.4 Communication Interfaces

#### 4.4.1 HTTP/HTTPS
- **Protocol:** HTTPS (TLS 1.2+)
- **Methods:** GET, POST, PUT, DELETE
- **Format:** JSON for API responses
- **Headers:** 
  - Content-Type: application/json
  - X-CSRF-TOKEN: for POST requests

#### 4.4.2 WebSocket (Optional for Real-time Chat)
- **Protocol:** WebSocket
- **Library:** Laravel Echo + Pusher/Socket.io
- **Events:** message.sent, session.closed

---

## 5. System Requirements

### 5.1 Functional Requirements Summary

| ID | Requirement | Priority |
|----|-------------|----------|
| FR-001 | User authentication dan authorization | High |
| FR-002 | Katalog dan pencarian buku | High |
| FR-003 | Peminjaman buku dengan QR Code | High |
| FR-004 | E-book reader dengan PDF streaming | High |
| FR-005 | Review dan rating system | Medium |
| FR-006 | Pengembalian dan denda otomatis | High |
| FR-007 | Manajemen mahasiswa dengan approval | High |
| FR-008 | Admin dashboard dan statistics | High |
| FR-009 | Chat support system | Medium |
| FR-010 | Email notifications | High |
| FR-011 | Export data (PDF/Excel) | Medium |
| FR-012 | History dan audit log | Medium |

### 5.2 Non-Functional Requirements

#### 5.2.1 Performance Requirements
- **NFR-PERF-001:** Page load time < 3 seconds
- **NFR-PERF-002:** Database query time < 500ms
- **NFR-PERF-003:** PDF streaming start < 2 seconds
- **NFR-PERF-004:** Support 100 concurrent users
- **NFR-PERF-005:** API response time < 1 second

#### 5.2.2 Security Requirements
- **NFR-SEC-001:** Password hashing dengan bcrypt (cost 12)
- **NFR-SEC-002:** CSRF protection untuk semua POST requests
- **NFR-SEC-003:** XSS prevention via input sanitization
- **NFR-SEC-004:** SQL injection prevention via prepared statements
- **NFR-SEC-005:** File upload validation (type, size, content)
- **NFR-SEC-006:** Session timeout after 2 hours inactivity
- **NFR-SEC-007:** HTTPS only in production
- **NFR-SEC-008:** Rate limiting untuk API endpoints
- **NFR-SEC-009:** Role-based access control (RBAC)
- **NFR-SEC-010:** Audit logging untuk sensitive operations

#### 5.2.3 Reliability Requirements
- **NFR-REL-001:** System uptime 99% (excluding maintenance)
- **NFR-REL-002:** Automated daily backup at 2 AM
- **NFR-REL-003:** Database transaction rollback on error
- **NFR-REL-004:** Graceful error handling dengan user-friendly messages
- **NFR-REL-005:** Error logging ke file dan database

#### 5.2.4 Usability Requirements
- **NFR-USE-001:** Intuitive navigation (max 3 clicks to any feature)
- **NFR-USE-002:** Consistent UI/UX across all pages
- **NFR-USE-003:** Clear error messages dengan actionable steps
- **NFR-USE-004:** Loading indicators untuk operations > 1 second
- **NFR-USE-005:** Responsive design untuk semua screen sizes
- **NFR-USE-006:** Accessibility compliant (WCAG 2.1 Level AA)

#### 5.2.5 Maintainability Requirements
- **NFR-MAIN-001:** Modular code structure (MVC pattern)
- **NFR-MAIN-002:** Code documentation (PHPDoc)
- **NFR-MAIN-003:** Database migrations untuk schema changes
- **NFR-MAIN-004:** Environment-based configuration
- **NFR-MAIN-005:** Automated testing (unit, feature tests)

#### 5.2.6 Scalability Requirements
- **NFR-SCAL-001:** Database indexing untuk frequently queried columns
- **NFR-SCAL-002:** Query optimization dengan eager loading
- **NFR-SCAL-003:** Caching untuk static data (config, settings)
- **NFR-SCAL-004:** CDN untuk static assets (images, CSS, JS)
- **NFR-SCAL-005:** Horizontal scaling capability

---

## 6. Database Requirements

### 6.1 Entity Relationship Diagram

```
┌─────────────┐       ┌──────────────┐       ┌─────────────┐
│   Users     │       │  Mahasiswas  │       │    Bukus    │
├─────────────┤       ├──────────────┤       ├─────────────┤
│ id (PK)     │       │ id (PK)      │       │ id (PK)     │
│ name        │       │ nim (UK)     │       │ nama_buku   │
│ email (UK)  │       │ nama         │       │ penerbit    │
│ password    │       │ jurusan      │       │ jenis_buku  │
│ role        │       │ email (UK)   │       │ genre_buku  │
│ created_at  │       │ no_telepon   │       │ stok_total  │
│ updated_at  │       │ status       │       │ stok_tersedia│
└─────────────┘       │ referral_token│      │ sampul_buku │
                      │ created_at   │       │ file_
# Product Requirements Document (PRD)
## SiPusaka - Sistem Informasi Perpustakaan Digital

**Version:** 1.0  
**Date:** May 28, 2026  
**Status:** Active Development  
**Document Owner:** Development Team

---

## 1. Executive Summary

### 1.1 Product Overview
SiPusaka (Sistem Informasi Perpustakaan Digital) adalah platform manajemen perpustakaan modern yang mengintegrasikan koleksi buku fisik dan digital (e-book) dalam satu sistem terpadu. Platform ini dirancang untuk memudahkan mahasiswa dalam mengakses, meminjam, dan membaca buku, serta memberikan admin tools yang powerful untuk mengelola seluruh operasional perpustakaan.

### 1.2 Product Vision
Menjadi solusi perpustakaan digital terdepan yang menggabungkan kemudahan akses e-book dengan sistem peminjaman buku fisik yang efisien, didukung oleh teknologi QR Code dan sistem referral yang inovatif.

### 1.3 Business Goals
- Meningkatkan aksesibilitas koleksi perpustakaan untuk mahasiswa
- Mengotomasi proses peminjaman dan pengembalian buku
- Mengurangi beban administratif pengelolaan perpustakaan
- Menyediakan analytics dan insights untuk pengambilan keputusan
- Meningkatkan engagement mahasiswa dengan fitur review dan rating

---

## 2. Target Users

### 2.1 Primary Users

#### **Mahasiswa (Students)**
- **Karakteristik:** Pengguna aktif perpustakaan, membutuhkan akses cepat ke buku
- **Kebutuhan:** 
  - Browse dan search koleksi buku
  - Pinjam buku fisik dengan mudah
  - Akses e-book kapan saja
  - Track status peminjaman
  - Memberikan review dan rating

#### **Admin Perpustakaan**
- **Karakteristik:** Staff perpustakaan yang mengelola operasional harian
- **Kebutuhan:**
  - Manajemen koleksi buku
  - Approval peminjaman
  - Scan QR Code untuk peminjaman/pengembalian
  - Monitor denda dan keterlambatan
  - Generate reports

#### **Super Admin**
- **Karakteristik:** IT staff atau kepala perpustakaan
- **Kebutuhan:**
  - User management
  - System configuration
  - Full access ke semua fitur
  - Analytics dashboard

### 2.2 Secondary Users

#### **Public Visitors**
- **Karakteristik:** Pengunjung website tanpa akun
- **Kebutuhan:**
  - Browse katalog buku
  - Lihat informasi buku
  - Registrasi akun mahasiswa

---

## 3. Key Features & Functionalities

### 3.1 Public Features (Tanpa Login)

#### **F1: Katalog Buku**
- **Priority:** P0 (Critical)
- **Description:** Menampilkan semua koleksi buku perpustakaan
- **User Story:** 
  > "Sebagai pengunjung, saya ingin melihat katalog buku yang tersedia agar saya tahu koleksi apa saja yang dimiliki perpustakaan"
- **Acceptance Criteria:**
  - Tampilan grid/list buku dengan cover, judul, penerbit
  - Filter berdasarkan jenis (Fisik/E-book/Hybrid)
  - Search by judul atau penerbit
  - Pagination untuk performa optimal
  - Badge status ketersediaan

#### **F2: Detail Buku**
- **Priority:** P0 (Critical)
- **Description:** Informasi lengkap tentang buku
- **User Story:**
  > "Sebagai pengunjung, saya ingin melihat detail lengkap buku termasuk review agar saya bisa memutuskan apakah buku tersebut sesuai kebutuhan saya"
- **Acceptance Criteria:**
  - Cover buku, judul, penerbit, genre
  - Stok tersedia vs total stok
  - View count dan borrow count
  - Rating rata-rata dan jumlah review
  - Daftar review dari mahasiswa
  - Tombol "Pinjam Buku" atau "Baca E-book"

#### **F3: Registrasi Mahasiswa**
- **Priority:** P0 (Critical)
- **Description:** Pendaftaran akun mahasiswa baru
- **User Story:**
  > "Sebagai calon pengguna, saya ingin mendaftar dengan NIM saya agar bisa mengakses layanan perpustakaan"
- **Acceptance Criteria:**
  - Form: NIM, Nama, Jurusan, Email, No. Telepon
  - Validasi NIM unik
  - Status pending approval
  - Email notification setelah registrasi
  - Admin approval required

#### **F4: Cek Status Peminjaman**
- **Priority:** P1 (High)
- **Description:** Cek status peminjaman tanpa login
- **User Story:**
  > "Sebagai mahasiswa, saya ingin cek status peminjaman saya dengan booking ID agar saya tahu kapan buku siap diambil"
- **Acceptance Criteria:**
  - Input booking ID
  - Tampilkan status: Pending/Approved/Ditolak/Dipinjam/Dikembalikan
  - Tampilkan QR Code jika approved
  - Informasi tanggal pinjam dan deadline
  - Informasi denda jika ada

### 3.2 Mahasiswa Features (Setelah Login)

#### **F5: Peminjaman Buku**
- **Priority:** P0 (Critical)
- **Description:** Mengajukan peminjaman buku fisik
- **User Story:**
  > "Sebagai mahasiswa, saya ingin meminjam buku fisik dengan mudah agar saya bisa membaca buku tersebut"
- **Acceptance Criteria:**
  - Form peminjaman dengan auto-fill data mahasiswa
  - Input referral token (6 digit)
  - Validasi token real-time
  - Validasi stok buku
  - Generate booking ID unik
  - Generate QR Code untuk pickup
  - Email notification dengan QR Code
  - Batas peminjaman 7 hari
  - Denda Rp10.000/hari untuk keterlambatan

#### **F6: E-book Reader**
- **Priority:** P0 (Critical)
- **Description:** Membaca e-book dalam browser
- **User Story:**
  > "Sebagai mahasiswa, saya ingin membaca e-book langsung di browser tanpa download agar lebih praktis"
- **Acceptance Criteria:**
  - PDF viewer terintegrasi
  - Streaming PDF (tidak perlu download penuh)
  - Validasi referral token untuk akses
  - Zoom in/out, navigasi halaman
  - Fullscreen mode
  - Responsive design

#### **F7: Review & Rating Buku**
- **Priority:** P1 (High)
- **Description:** Memberikan review dan rating untuk buku
- **User Story:**
  > "Sebagai mahasiswa, saya ingin memberikan review buku yang sudah saya baca agar membantu mahasiswa lain"
- **Acceptance Criteria:**
  - Verifikasi NIM dan referral token
  - Rating 1-5 bintang
  - Komentar text (optional)
  - Tampilan card-based review
  - Avatar dengan initial nama
  - Timestamp review
  - Satu review per mahasiswa per buku

#### **F8: Mahasiswa Dashboard**
- **Priority:** P1 (High)
- **Description:** Dashboard personal mahasiswa
- **User Story:**
  > "Sebagai mahasiswa, saya ingin melihat history peminjaman dan update profile saya"
- **Acceptance Criteria:**
  - Login dengan NIM dan referral token
  - Lihat history peminjaman
  - Lihat denda aktif
  - Request update profile (pending approval)
  - Logout

#### **F9: Chat Support**
- **Priority:** P2 (Medium)
- **Description:** Chat real-time dengan admin
- **User Story:**
  > "Sebagai mahasiswa, saya ingin bertanya ke admin jika ada masalah dengan peminjaman"
- **Acceptance Criteria:**
  - Verifikasi NIM sebelum chat
  - Real-time messaging
  - Chat history
  - Notifikasi pesan baru
  - Admin dapat close session

### 3.3 Admin Features

#### **F10: Dashboard Admin**
- **Priority:** P0 (Critical)
- **Description:** Overview statistik perpustakaan
- **User Story:**
  > "Sebagai admin, saya ingin melihat statistik perpustakaan agar saya bisa monitor performa"
- **Acceptance Criteria:**
  - Total buku, mahasiswa, peminjaman aktif
  - Grafik peminjaman per bulan
  - Top 5 buku terpopuler
  - Denda yang belum dibayar
  - Peminjaman pending approval
  - Quick actions

#### **F11: Manajemen Buku**
- **Priority:** P0 (Critical)
- **Description:** CRUD buku dan koleksi
- **User Story:**
  > "Sebagai admin, saya ingin mengelola koleksi buku agar katalog selalu update"
- **Acceptance Criteria:**
  - Tambah buku baru (judul, penerbit, jenis, genre, stok)
  - Upload cover buku
  - Upload file PDF untuk e-book
  - Edit informasi buku
  - Hapus buku
  - Filter dan search
  - Export ke PDF/Excel
  - Bulk operations

#### **F12: Manajemen Mahasiswa**
- **Priority:** P0 (Critical)
- **Description:** Kelola data mahasiswa
- **User Story:**
  > "Sebagai admin, saya ingin approve registrasi mahasiswa dan generate referral token"
- **Acceptance Criteria:**
  - List semua mahasiswa
  - Filter by status (Pending/Approved/Rejected)
  - Approve/Reject registrasi
  - Auto-generate referral token (6 digit) saat approve
  - Kirim email dengan token
  - Resend email token
  - Edit data mahasiswa
  - Hapus mahasiswa
  - Process update request dari mahasiswa

#### **F13: Manajemen Peminjaman**
- **Priority:** P0 (Critical)
- **Description:** Kelola peminjaman buku
- **User Story:**
  > "Sebagai admin, saya ingin approve peminjaman dan scan QR Code saat mahasiswa ambil buku"
- **Acceptance Criteria:**
  - List peminjaman (Pending/Approved/Dipinjam/Dikembalikan)
  - Approve/Tolak peminjaman
  - Generate QR Code saat approve
  - Scan QR Code untuk konfirmasi pickup
  - Update status ke "Dipinjam"
  - Kirim reminder email H-1 deadline
  - Export ke PDF/Excel
  - Filter by status, tanggal, mahasiswa

#### **F14: Manajemen Pengembalian**
- **Priority:** P0 (Critical)
- **Description:** Proses pengembalian buku
- **User Story:**
  > "Sebagai admin, saya ingin scan QR Code saat mahasiswa kembalikan buku dan hitung denda jika terlambat"
- **Acceptance Criteria:**
  - Scan QR Code pengembalian
  - Cari by booking ID
  - Tampilkan info peminjaman
  - Hitung denda otomatis jika terlambat
  - Proses pengembalian
  - Update stok buku
  - Generate denda record jika ada
  - Update status ke "Dikembalikan"

#### **F15: Manajemen Denda**
- **Priority:** P1 (High)
- **Description:** Kelola denda keterlambatan
- **User Story:**
  > "Sebagai admin, saya ingin track denda yang belum dibayar dan tandai lunas"
- **Acceptance Criteria:**
  - List semua denda
  - Filter by status (Belum Lunas/Lunas)
  - Detail denda (mahasiswa, buku, jumlah hari, total)
  - Tandai lunas
  - Export ke PDF/Excel
  - Statistik total denda

#### **F16: History Transaksi**
- **Priority:** P1 (High)
- **Description:** Log semua transaksi perpustakaan
- **User Story:**
  > "Sebagai admin, saya ingin melihat history semua transaksi untuk audit"
- **Acceptance Criteria:**
  - List semua history
  - Filter by tanggal, mahasiswa, buku
  - Detail transaksi lengkap
  - Export ke PDF/Excel
  - Search functionality

#### **F17: Chat Management**
- **Priority:** P2 (Medium)
- **Description:** Kelola chat dengan mahasiswa
- **User Story:**
  > "Sebagai admin, saya ingin membalas pertanyaan mahasiswa via chat"
- **Acceptance Criteria:**
  - List semua chat sessions
  - Filter by status (Active/Closed)
  - Real-time messaging
  - Notifikasi pesan baru
  - Close session
  - Chat history

#### **F18: User Management**
- **Priority:** P1 (High)
- **Description:** Kelola user admin (Super Admin only)
- **User Story:**
  > "Sebagai super admin, saya ingin mengelola user admin dan assign role"
- **Acceptance Criteria:**
  - CRUD user admin
  - Assign role (Admin/Super Admin)
  - Permissions management
  - Password reset
  - Activity log

---

## 4. Non-Functional Requirements

### 4.1 Performance
- Page load time < 3 seconds
- PDF streaming smooth untuk file up to 50MB
- Support 100 concurrent users
- Database query optimization

### 4.2 Security
- Password hashing (bcrypt)
- CSRF protection
- XSS prevention
- SQL injection prevention
- Secure file upload validation
- Role-based access control (RBAC)
- Session management

### 4.3 Usability
- Responsive design (mobile, tablet, desktop)
- Intuitive UI/UX
- Consistent design language
- Accessibility compliant
- Clear error messages
- Loading indicators

### 4.4 Reliability
- 99% uptime
- Automated backup daily
- Error logging and monitoring
- Graceful error handling

### 4.5 Scalability
- Modular architecture
- Database indexing
- Caching strategy
- CDN for static assets

---

## 5. Technical Constraints

### 5.1 Technology Stack
- **Backend:** Laravel 11.x (PHP 8.2+)
- **Frontend:** Blade Templates, Bootstrap 5, Vanilla JS
- **Database:** MySQL 8.0+
- **Server:** Apache/Nginx
- **Email:** SMTP (Gmail/SendGrid)
- **PDF Processing:** Laravel PDF libraries

### 5.2 Browser Support
- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+

### 5.3 Device Support
- Desktop (1920x1080 and above)
- Tablet (768x1024)
- Mobile (375x667 and above)

---

## 6. Success Metrics

### 6.1 User Adoption
- 80% mahasiswa terdaftar dalam 3 bulan
- 60% active users monthly
- 50% e-book adoption rate

### 6.2 Operational Efficiency
- 70% reduction in manual processing time
- 90% peminjaman approved dalam 24 jam
- 95% accuracy in denda calculation

### 6.3 User Satisfaction
- 4.5/5 average rating
- < 5% complaint rate
- 80% positive reviews

### 6.4 Business Impact
- 40% increase in book circulation
- 30% increase in e-book usage
- 50% reduction in overdue books

---

## 7. Roadmap & Milestones

### Phase 1: MVP (Completed)
- ✅ Basic katalog dan detail buku
- ✅ Peminjaman buku fisik
- ✅ E-book reader
- ✅ Admin dashboard
- ✅ QR Code system

### Phase 2: Enhancement (Current)
- ✅ Review & rating system
- ✅ Mahasiswa dashboard
- ✅ Chat support
- ✅ User management
- 🔄 Advanced analytics

### Phase 3: Future (Planned)
- 📋 Mobile app (iOS/Android)
- 📋 Push notifications
- 📋 AI-powered book recommendations
- 📋 Integration with academic system
- 📋 Digital library card
- 📋 Book reservation system
- 📋 Multi-language support

---

## 8. Assumptions & Dependencies

### 8.1 Assumptions
- Mahasiswa memiliki email aktif
- Admin memiliki device untuk scan QR Code
- Internet connection tersedia
- Mahasiswa familiar dengan digital platform

### 8.2 Dependencies
- Email service (SMTP) availability
- PDF files availability for e-books
- QR Code scanner device
- Server infrastructure

---

## 9. Risks & Mitigation

### 9.1 Technical Risks
| Risk | Impact | Probability | Mitigation |
|------|--------|-------------|------------|
| Server downtime | High | Low | Backup server, monitoring |
| Data loss | High | Low | Daily backup, redundancy |
| Security breach | High | Medium | Security audit, penetration testing |
| PDF streaming issues | Medium | Medium | Optimize file size, CDN |

### 9.2 Business Risks
| Risk | Impact | Probability | Mitigation |
|------|--------|-------------|------------|
| Low user adoption | High | Medium | Training, user onboarding |
| Resistance to change | Medium | High | Change management, support |
| Budget constraints | Medium | Low | Phased implementation |

---

## 10. Glossary

- **Booking ID:** Unique identifier untuk setiap peminjaman
- **Referral Token:** 6-digit code untuk akses e-book dan verifikasi
- **QR Code:** Quick Response code untuk peminjaman/pengembalian
- **E-book:** Electronic book dalam format PDF
- **Hybrid:** Buku yang tersedia dalam format fisik dan digital
- **Denda:** Penalty untuk keterlambatan pengembalian (Rp10.000/hari)

---

## 11. Approval

| Role | Name | Signature | Date |
|------|------|-----------|------|
| Product Owner | - | - | - |
| Tech Lead | - | - | - |
| Stakeholder | - | - | - |

---

**Document History:**
- v1.0 (2026-05-28): Initial PRD creation
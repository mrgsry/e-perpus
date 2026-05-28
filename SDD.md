# Software Design Document (SDD)
## SiPusaka - Sistem Informasi Perpustakaan Digital

**Version:** 1.0  
**Date:** May 28, 2026  
**Status:** Active Development  
**Prepared by:** Development Team

---

## Table of Contents
1. [Introduction](#1-introduction)
2. [System Architecture](#2-system-architecture)
3. [Database Design](#3-database-design)
4. [Component Design](#4-component-design)
5. [API Design](#5-api-design)
6. [Security Design](#6-security-design)
7. [Deployment Architecture](#7-deployment-architecture)

---

## 1. Introduction

### 1.1 Purpose
Dokumen ini menjelaskan design dan arsitektur teknis dari SiPusaka (Sistem Informasi Perpustakaan Digital). Dokumen ini ditujukan untuk development team sebagai panduan implementasi.

### 1.2 Scope
Dokumen ini mencakup:
- System architecture dan design patterns
- Database schema dan relationships
- Component design dan interactions
- API endpoints dan specifications
- Security mechanisms
- Deployment strategy

### 1.3 References
- PRD.md - Product Requirements Document
- SRS.md - Software Requirements Specification
- Laravel 11.x Documentation
- MySQL 8.0 Documentation

---

## 2. System Architecture

### 2.1 High-Level Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                        CLIENT LAYER                          │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐      │
│  │   Browser    │  │   Mobile     │  │   Tablet     │      │
│  │  (Desktop)   │  │   Browser    │  │   Browser    │      │
│  └──────────────┘  └──────────────┘  └──────────────┘      │
└─────────────────────────────────────────────────────────────┘
                            │
                            │ HTTPS
                            ▼
┌─────────────────────────────────────────────────────────────┐
│                    PRESENTATION LAYER                        │
│  ┌──────────────────────────────────────────────────────┐   │
│  │              Blade Templates (Views)                  │   │
│  │  • Public Views  • Admin Views  • Mahasiswa Views    │   │
│  └──────────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────────┘
                            │
                            ▼
┌─────────────────────────────────────────────────────────────┐
│                    APPLICATION LAYER                         │
│  ┌──────────────────────────────────────────────────────┐   │
│  │                   Controllers                         │   │
│  │  • AuthController      • BukuController              │   │
│  │  • PinjamController    • ReviewController            │   │
│  │  • ChatController      • DashboardController         │   │
│  └──────────────────────────────────────────────────────┘   │
│                            │                                 │
│  ┌──────────────────────────────────────────────────────┐   │
│  │                   Middleware                          │   │
│  │  • Authentication  • Authorization  • CSRF           │   │
│  └──────────────────────────────────────────────────────┘   │
│                            │                                 │
│  ┌──────────────────────────────────────────────────────┐   │
│  │                   Services                            │   │
│  │  • EmailService    • QRCodeService                   │   │
│  │  • PDFService      • NotificationService             │   │
│  └──────────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────────┘
                            │
                            ▼
┌─────────────────────────────────────────────────────────────┐
│                      BUSINESS LAYER                          │
│  ┌──────────────────────────────────────────────────────┐   │
│  │                   Models (Eloquent ORM)               │   │
│  │  • User        • Mahasiswa    • Buku                 │   │
│  │  • Peminjaman  • Denda        • BookReview           │   │
│  │  • ChatSession • ChatMessage  • History              │   │
│  └──────────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────────┘
                            │
                            ▼
┌─────────────────────────────────────────────────────────────┐
│                       DATA LAYER                             │
│  ┌──────────────────────────────────────────────────────┐   │
│  │                   MySQL Database                      │   │
│  │  • Tables  • Indexes  • Relationships                │   │
│  └──────────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────────┘
                            │
                            ▼
┌─────────────────────────────────────────────────────────────┐
│                    EXTERNAL SERVICES                         │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐      │
│  │ SMTP Server  │  │ File Storage │  │  QR Scanner  │      │
│  │   (Email)    │  │   (Local)    │  │   (Device)   │      │
│  └──────────────┘  └──────────────┘  └──────────────┘      │
└─────────────────────────────────────────────────────────────┘
```

### 2.2 MVC Architecture Pattern

SiPusaka menggunakan **Model-View-Controller (MVC)** pattern yang disediakan oleh Laravel:

#### **Model**
- Represents data dan business logic
- Eloquent ORM untuk database interactions
- Relationships antar entities
- Validation rules

#### **View**
- Blade templates untuk rendering HTML
- Reusable components dan layouts
- Client-side JavaScript untuk interactivity
- Responsive CSS dengan Bootstrap 5

#### **Controller**
- Handle HTTP requests
- Coordinate antara Model dan View
- Business logic orchestration
- Return responses (views atau JSON)

### 2.3 Design Patterns Used

#### **Repository Pattern**
```php
// Untuk complex queries dan data access abstraction
interface BukuRepositoryInterface {
    public function getAllWithFilters($filters);
    public function getPopularBooks($limit);
    public function searchBooks($query);
}
```

#### **Service Pattern**
```php
// Untuk business logic yang complex
class PeminjamanService {
    public function createPeminjaman($data);
    public function approvePeminjaman($id);
    public function calculateDenda($peminjaman);
}
```

#### **Observer Pattern**
```php
// Untuk event handling
class PeminjamanObserver {
    public function created(Peminjaman $peminjaman) {
        // Send email notification
    }
    
    public function updated(Peminjaman $peminjaman) {
        // Log to history
    }
}
```

---

## 3. Database Design

### 3.1 Complete Entity Relationship Diagram

```
┌─────────────────┐
│     users       │
├─────────────────┤
│ id (PK)         │
│ name            │
│ email (UK)      │
│ password        │
│ created_at      │
│ updated_at      │
└─────────────────┘
        │
        │ has many
        ▼
┌─────────────────┐
│  login_logs     │
├─────────────────┤
│ id (PK)         │
│ user_id (FK)    │
│ ip_address      │
│ user_agent      │
│ created_at      │
└─────────────────┘


┌──────────────────┐
│   mahasiswas     │
├──────────────────┤
│ id (PK)          │
│ nim (UK)         │
│ nama             │
│ jurusan          │
│ email (UK)       │
│ no_telepon       │
│ status           │◄──────┐
│ referral_token   │       │
│ pending_updates  │       │
│ created_at       │       │
│ updated_at       │       │
└──────────────────┘       │
        │                  │
        │ has many         │ belongs to
        ▼                  │
┌──────────────────┐       │
│   pinjamans      │       │
├──────────────────┤       │
│ id (PK)          │       │
│ booking_id (UK)  │       │
│ mahasiswa_id (FK)├───────┘
│ buku_id (FK)     ├───────┐
│ tanggal_pinjam   │       │
│ tanggal_kembali  │       │
│ tanggal_dikembalikan│    │
│ status           │       │
│ qr_code          │       │
│ created_at       │       │
│ updated_at       │       │
└──────────────────┘       │
        │                  │
        │ has one          │ belongs to
        ▼                  │
┌──────────────────┐       │
│     dendas       │       │
├──────────────────┤       │
│ id (PK)          │       │
│ peminjaman_id(FK)│       │
│ jumlah_hari      │       │
│ total_denda      │       │
│ status           │       │
│ created_at       │       │
│ updated_at       │       │
└──────────────────┘       │
                           │
┌──────────────────┐       │
│      bukus       │◄──────┘
├──────────────────┤
│ id (PK)          │
│ nama_buku        │
│ penerbit         │
│ jenis_buku       │
│ genre_buku       │
│ stok_total       │
│ stok_tersedia    │
│ sampul_buku      │
│ file_ebook       │
│ view_count       │
│ borrow_count     │
│ created_at       │
│ updated_at       │
└──────────────────┘
        │
        │ has many
        ▼
┌──────────────────┐
│  book_reviews    │
├──────────────────┤
│ id (PK)          │
│ buku_id (FK)     │
│ mahasiswa_id (FK)│
│ rating           │
│ comment          │
│ created_at       │
│ updated_at       │
└──────────────────┘


┌──────────────────┐
│  chat_sessions   │
├──────────────────┤
│ id (PK)          │
│ mahasiswa_id (FK)│
│ status           │
│ created_at       │
│ updated_at       │
└──────────────────┘
        │
        │ has many
        ▼
┌──────────────────┐
│  chat_messages   │
├──────────────────┤
│ id (PK)          │
│ session_id (FK)  │
│ sender_type      │
│ message          │
│ created_at       │
└──────────────────┘


┌──────────────────┐
│    histories     │
├──────────────────┤
│ id (PK)          │
│ mahasiswa_id (FK)│
│ buku_id (FK)     │
│ action           │
│ details          │
│ created_at       │
└──────────────────┘
```

### 3.2 Table Specifications

#### **users**
```sql
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### **mahasiswas**
```sql
CREATE TABLE mahasiswas (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nim VARCHAR(20) UNIQUE NOT NULL,
    nama VARCHAR(255) NOT NULL,
    jurusan VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    no_telepon VARCHAR(20) NULL,
    status ENUM('Pending', 'Approved', 'Rejected') DEFAULT 'Pending',
    referral_token VARCHAR(6) UNIQUE NULL,
    pending_updates JSON NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    INDEX idx_nim (nim),
    INDEX idx_email (email),
    INDEX idx_status (status),
    INDEX idx_referral_token (referral_token)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### **bukus**
```sql
CREATE TABLE bukus (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nama_buku VARCHAR(255) NOT NULL,
    penerbit VARCHAR(255) NOT NULL,
    jenis_buku VARCHAR(50) NOT NULL,
    genre_buku VARCHAR(100) NULL,
    stok_total INT NOT NULL DEFAULT 0,
    stok_tersedia INT NOT NULL DEFAULT 0,
    sampul_buku VARCHAR(255) NULL,
    file_ebook VARCHAR(255) NULL,
    view_count INT UNSIGNED DEFAULT 0,
    borrow_count INT UNSIGNED DEFAULT 0,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    INDEX idx_nama_buku (nama_buku),
    INDEX idx_jenis_buku (jenis_buku),
    INDEX idx_stok_tersedia (stok_tersedia),
    FULLTEXT idx_search (nama_buku, penerbit)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### **pinjamans**
```sql
CREATE TABLE pinjamans (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    booking_id VARCHAR(50) UNIQUE NOT NULL,
    mahasiswa_id BIGINT UNSIGNED NOT NULL,
    buku_id BIGINT UNSIGNED NOT NULL,
    tanggal_pinjam DATE NOT NULL,
    tanggal_kembali DATE NOT NULL,
    tanggal_dikembalikan DATE NULL,
    status ENUM('Pending', 'Approved', 'Ditolak', 'Dipinjam', 'Dikembalikan') DEFAULT 'Pending',
    qr_code VARCHAR(255) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (mahasiswa_id) REFERENCES mahasiswas(id) ON DELETE CASCADE,
    FOREIGN KEY (buku_id) REFERENCES bukus(id) ON DELETE CASCADE,
    INDEX idx_booking_id (booking_id),
    INDEX idx_mahasiswa_id (mahasiswa_id),
    INDEX idx_buku_id (buku_id),
    INDEX idx_status (status),
    INDEX idx_tanggal_kembali (tanggal_kembali)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### **dendas**
```sql
CREATE TABLE dendas (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    peminjaman_id BIGINT UNSIGNED NOT NULL,
    jumlah_hari INT NOT NULL,
    total_denda DECIMAL(10,2) NOT NULL,
    status ENUM('Belum Lunas', 'Lunas') DEFAULT 'Belum Lunas',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (peminjaman_id) REFERENCES pinjamans(id) ON DELETE CASCADE,
    INDEX idx_peminjaman_id (peminjaman_id),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### **book_reviews**
```sql
CREATE TABLE book_reviews (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    buku_id BIGINT UNSIGNED NOT NULL,
    mahasiswa_id BIGINT UNSIGNED NOT NULL,
    rating TINYINT UNSIGNED NOT NULL CHECK (rating BETWEEN 1 AND 5),
    comment TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (buku_id) REFERENCES bukus(id) ON DELETE CASCADE,
    FOREIGN KEY (mahasiswa_id) REFERENCES mahasiswas(id) ON DELETE CASCADE,
    UNIQUE KEY unique_review (buku_id, mahasiswa_id),
    INDEX idx_buku_id (buku_id),
    INDEX idx_rating (rating)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### **chat_sessions**
```sql
CREATE TABLE chat_sessions (
    id CHAR(36) PRIMARY KEY,
    mahasiswa_id BIGINT UNSIGNED NOT NULL,
    status ENUM('Active', 'Closed') DEFAULT 'Active',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (mahasiswa_id) REFERENCES mahasiswas(id) ON DELETE CASCADE,
    INDEX idx_mahasiswa_id (mahasiswa_id),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### **chat_messages**
```sql
CREATE TABLE chat_messages (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    session_id CHAR(36) NOT NULL,
    sender_type ENUM('mahasiswa', 'admin') NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP NULL,
    FOREIGN KEY (session_id) REFERENCES chat_sessions(id) ON DELETE CASCADE,
    INDEX idx_session_id (session_id),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### **histories**
```sql
CREATE TABLE histories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    mahasiswa_id BIGINT UNSIGNED NULL,
    buku_id BIGINT UNSIGNED NULL,
    action VARCHAR(100) NOT NULL,
    details TEXT NULL,
    created_at TIMESTAMP NULL,
    FOREIGN KEY (mahasiswa_id) REFERENCES mahasiswas(id) ON DELETE SET NULL,
    FOREIGN KEY (buku_id) REFERENCES bukus(id) ON DELETE SET NULL,
    INDEX idx_mahasiswa_id (mahasiswa_id),
    INDEX idx_buku_id (buku_id),
    INDEX idx_action (action),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 3.3 Database Indexes Strategy

**Primary Indexes:**
- All tables have AUTO_INCREMENT PRIMARY KEY
- Unique indexes pada nim, email, booking_id, referral_token

**Secondary Indexes:**
- Foreign keys untuk join optimization
- Status fields untuk filtering
- Date fields untuk range queries
- FULLTEXT index untuk search functionality

**Composite Indexes:**
- (buku_id, mahasiswa_id) untuk unique review constraint
- (status, tanggal_kembali) untuk overdue queries

---

## 4. Component Design

### 4.1 Controller Layer

#### **AuthController**
```php
namespace App\Http\Controllers\Admin;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLogin()
    {
        return view('admin.auth.login');
    }
    
    /**
     * Process login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }
        
        return back()->withErrors([
            'email' => 'Email atau password salah'
        ]);
    }
    
    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }
}
```

#### **BukuController**
```php
namespace App\Http\Controllers\Admin;

class BukuController extends Controller
{
    /**
     * Display list of books
     */
    public function index(Request $request)
    {
        $query = Buku::query();
        
        if ($request->has('search')) {
            $query->where('nama_buku', 'like', '%' . $request->search . '%')
                  ->orWhere('penerbit', 'like', '%' . $request->search . '%');
        }
        
        if ($request->has('jenis')) {
            $query->where('jenis_buku', $request->jenis);
        }
        
        $bukus = $query->paginate(15);
        
        return view('admin.buku.index', compact('bukus'));
    }
    
    /**
     * Store new book
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_buku' => 'required|string|max:255',
            'penerbit' => 'required|string|max:255',
            'jenis_buku' => 'required|in:Fisik,Ebook,Hybrid',
            'genre_buku' => 'nullable|string|max:100',
            'stok_total' => 'required|integer|min:0',
            'sampul_buku' => 'nullable|image|max:2048',
            'file_ebook' => 'nullable|mimes:pdf|max:51200'
        ]);
        
        if ($request->hasFile('sampul_buku')) {
            $validated['sampul_buku'] = $request->file('sampul_buku')
                ->store('covers', 'public');
        }
        
        if ($request->hasFile('file_ebook')) {
            $validated['file_ebook'] = $request->file('file_ebook')
                ->store('ebooks', 'public');
        }
        
        $validated['stok_tersedia'] = $validated['stok_total'];
        
        Buku::create($validated);
        
        return redirect()->route('admin.buku.index')
            ->with('success', 'Buku berhasil ditambahkan');
    }
    
    /**
     * Update book
     */
    public function update(Request $request, $id)
    {
        $buku = Buku::findOrFail($id);
        
        $validated = $request->validate([
            'nama_buku' => 'required|string|max:255',
            'penerbit' => 'required|string|max:255',
            'jenis_buku' => 'required|in:Fisik,Ebook,Hybrid',
            'genre_buku' => 'nullable|string|max:100',
            'stok_total' => 'required|integer|min:0',
            'sampul_buku' => 'nullable|image|max:2048',
            'file_ebook' => 'nullable|mimes:pdf|max:51200'
        ]);
        
        // Handle file uploads...
        
        $buku->update($validated);
        
        return redirect()->route('admin.buku.index')
            ->with('success', 'Buku berhasil diupdate');
    }
    
    /**
     * Delete book
     */
    public function destroy($id)
    {
        $buku = Buku::findOrFail($id);
        
        // Delete files if exist
        if ($buku->sampul_buku) {
            Storage::disk('public')->delete($buku->sampul_buku);
        }
        if ($buku->file_ebook) {
            Storage::disk('public')->delete($buku->file_ebook);
        }
        
        $buku->delete();
        
        return redirect()->route('admin.buku.index')
            ->with('success', 'Buku berhasil dihapus');
    }
}
```

#### **PinjamController**
```php
namespace App\Http\Controllers\Publik;

class PinjamController extends Controller
{
    /**
     * Submit peminjaman
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string',
            'nim' => 'required|string',
            'jurusan' => 'required|string',
            'no_telepon' => 'required|string',
            'referral_token' => 'required|string|size:6',
            'buku_ids' => 'required|array|min:1'
        ]);
        
        // Validate mahasiswa
        $mahasiswa = Mahasiswa::where('nim', $validated['nim'])
            ->where('referral_token', $validated['referral_token'])
            ->where('status', 'Approved')
            ->first();
            
        if (!$mahasiswa) {
            return response()->json([
                'success' => false,
                'message' => 'NIM atau token tidak valid'
            ], 400);
        }
        
        $bookingIds = [];
        
        foreach ($validated['buku_ids'] as $bukuId) {
            $buku = Buku::find($bukuId);
            
            if (!$buku || $buku->stok_tersedia < 1) {
                continue;
            }
            
            // Generate booking ID
            $bookingId = 'BK-' . date('Ymd') . '-' . str_pad(
                Peminjaman::whereDate('created_at', today())->count() + 1,
                4, '0', STR_PAD_LEFT
            );
            
            // Create peminjaman
            Peminjaman::create([
                'booking_id' => $bookingId,
                'mahasiswa_id' => $mahasiswa->id,
                'buku_id' => $bukuId,
                'tanggal_pinjam' => now(),
                'tanggal_kembali' => now()->addDays(7),
                'status' => 'Pending'
            ]);
            
            $bookingIds[] = $bookingId;
        }
        
        // Send email notification
        Mail::to($mahasiswa->email)->send(
            new PeminjamanCreated($mahasiswa, $bookingIds)
        );
        
        return response()->json([
            'success' => true,
            'message' => 'Peminjaman berhasil diajukan',
            'booking_ids' => $bookingIds
        ]);
    }
}
```

### 4.2 Model Layer

#### **Buku Model**
```php
namespace App\Models;

class Buku extends Model
{
    protected $fillable = [
        'nama_buku', 'penerbit', 'jenis_buku', 'genre_buku',
        'stok_total', 'stok_tersedia', 'sampul_buku', 'file_ebook',
        'view_count', 'borrow_count'
    ];
    
    protected $casts = [
        'view_count' => 'integer',
        'borrow_count' => 'integer',
        'stok_total' => 'integer',
        'stok_tersedia' => 'integer'
    ];
    
    /**
     * Relationships
     */
    public function pinjamans()
    {
        return $this->hasMany(Peminjaman::class);
    }
    
    public function reviews()
    {
        return $this->hasMany(BookReview::class);
    }
    
    /**
     * Accessors
     */
    public function getSampulUrlAttribute()
    {
        return $this->sampul_buku 
            ? asset('storage/' . $this->sampul_buku)
            : null;
    }
    
    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }
    
    /**
     * Scopes
     */
    public function scopeAvailable($query)
    {
        return $query->where('stok_tersedia', '>', 0);
    }
    
    public function scopeEbook($query)
    {
        return $query->whereIn('jenis_buku', ['Ebook', 'Hybrid']);
    }
    
    public function scopeSearch($query, $term)
    {
        return $query->where(function($q) use ($term) {
            $q->where('nama_buku', 'like', "%{$term}%")
              ->orWhere('penerbit', 'like', "%{$term}%");
        });
    }
}
```

#### **Peminjaman Model**
```php
namespace App\Models;

class Peminjaman extends Model
{
    protected $fillable = [
        'booking_id', 'mahasiswa_id', 'buku_id',
        'tanggal_pinjam', 'tanggal_kembali', 'tanggal_dikembalikan',
        'status', 'qr_code'
    ];
    
    protected $casts = [
        'tanggal_pinjam' => 'date',
        'tanggal_kembali' => 'date',
        'tanggal_dikembalikan' => 'date'
    ];
    
    /**
     * Relationships
     */
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }
    
    public function buku()
    {
        return $this->belongsTo(Buku::class);
    }
    
    public function denda()
    {
        return $this->hasOne(Denda::class);
    }
    
    /**
     * Accessors
     */
    public function getIsOverdueAttribute()
    {
        return $this->status === 'Dipinjam' 
            && now()->gt($this->tanggal_kembali);
    }
    
    public function getDaysOverdueAttribute()
    {
        if (!$this->is_overdue) {
            return 0;
        }
        
        return now()->diffInDays($this->tanggal_kembali);
    }
    
    /**
     * Scopes
     */
    public function scopePending($query)
    {
        return $query->where('status', 'Pending');
    }
    
    public function scopeActive($query)
    {
        return $query
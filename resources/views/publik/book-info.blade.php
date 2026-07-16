<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $buku->nama_buku }} | SiPusaka</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
    :root {
        --navy: #0f2444;
        --navy-mid: #1a3c6b;
        --gold: #c9930a;
        --gold-light: #f0b429;
        --cream: #faf8f3;
        --text: #1e293b;
        --muted: #64748b;
        --border: #e2e8f0;
    }

    * {
        box-sizing: border-box;
    }

    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background: var(--cream);
        color: var(--text);
        margin: 0;
    }

    /* NAVBAR */
    .navbar-sipusaka {
        background: var(--navy);
        padding: 14px 0;
        position: sticky;
        top: 0;
        z-index: 100;
        box-shadow: 0 4px 24px rgba(15, 36, 68, 0.25);
    }

    .book-info-footer {
        background: var(--navy);
        color: #fff;
        margin-top: 56px;
        padding: 48px 0 28px;
    }

    .book-info-footer h5 {
        color: #fff;
        font-weight: 700;
    }

    .book-info-footer p,
    .book-info-footer .footer-muted {
        color: rgba(255, 255, 255, .72);
    }

    .book-info-footer a {
        color: rgba(255, 255, 255, .85);
    }

    .book-info-footer a:hover {
        color: var(--gold-light);
    }

    .navbar-brand-custom {
        font-family: 'Fraunces', serif;
        font-size: 1.6rem;
        font-weight: 900;
        color: white;
        text-decoration: none;
    }

    .navbar-brand-custom span {
        color: var(--gold-light);
    }

    .nav-links a {
        color: rgba(255, 255, 255, 0.7);
        text-decoration: none;
        font-size: 14px;
        margin-left: 24px;
        transition: color 0.2s;
    }

    .nav-links a:hover {
        color: var(--gold-light);
    }

    /* BOOK INFO SECTION */
    .book-info-section {
        padding: 60px 0 80px;
    }

    /* BOOK COVER */
    .book-cover-lg {
        width: 100%;
        max-width: 300px;
        height: 450px;
        object-fit: cover;
        border-radius: 12px;
        box-shadow: 0 12px 48px rgba(15, 36, 68, 0.15);
    }

    .book-cover-placeholder-lg {
        width: 100%;
        max-width: 300px;
        height: 450px;
        background: linear-gradient(135deg, #e2e8f0, #cbd5e1);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 80px;
        color: #cbd5e1;
    }

    /* BOOK DETAILS */
    .book-title-lg {
        font-family: 'Fraunces', serif;
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--navy);
        line-height: 1.2;
        margin-bottom: 12px;
    }

    .book-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        margin: 16px 0;
    }

    .book-meta-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        color: var(--muted);
    }

    .book-meta-item i {
        color: var(--navy-mid);
    }

    .book-meta-item strong {
        color: var(--text);
        font-weight: 600;
    }

    /* BOOK DESCRIPTION */
    .book-description {
        background: white;
        padding: 24px;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(15, 36, 68, 0.06);
        margin-top: 24px;
    }

    .book-description h4 {
        font-weight: 700;
        color: var(--navy);
        margin-bottom: 12px;
    }

    .book-description p {
        color: var(--muted);
        line-height: 1.6;
    }

    /* ACTION BUTTONS */
    .action-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 24px;
    }

    .btn-book {
        padding: 12px 24px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 8px;
        border: none;
        cursor: pointer;
    }

    .btn-primary-book {
        background: var(--navy);
        border-color: var(--navy);
        color: white;
    }

    .btn-primary-book:hover {
        background: var(--navy-mid);
        border-color: var(--navy-mid);
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(26, 60, 107, 0.3);
    }

    .btn-secondary-book {
        background: white;
        border: 2px solid var(--border);
        color: var(--text);
    }

    .btn-secondary-book:hover {
        border-color: var(--navy-mid);
        color: var(--navy);
        background: #f8fafc;
    }

    .btn-ebook {
        background: var(--gold);
        border-color: var(--gold);
        color: white;
    }

    .btn-ebook:hover {
        background: var(--gold-light);
        border-color: var(--gold-light);
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(201, 147, 10, 0.3);
    }

    /* STATS */
    .book-stats {
        display: flex;
        gap: 24px;
        margin-top: 24px;
        padding: 20px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(15, 36, 68, 0.06);
    }

    .stat-item {
        text-align: center;
        flex: 1;
    }

    .stat-value {
        font-size: 24px;
        font-weight: 800;
        color: var(--navy);
    }

    .stat-label {
        font-size: 12px;
        color: var(--muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-top: 4px;
    }

    /* BADGES */
    .badge-book {
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
    }

    .badge-ebook {
        background: #fffbeb;
        color: #92400e;
    }

    .badge-physical {
        background: #eff6ff;
        color: #1d4ed8;
    }

    .badge-available {
        background: #d1fae5;
        color: #065f46;
    }

    .badge-unavailable {
        background: #fee2e2;
        color: #991b1b;
    }

    /* REVIEW SECTION */
    .review-section {
        background: white;
        padding: 0;
        margin-top: 32px;
    }

    .review-summary-card {
        background: linear-gradient(135deg, var(--navy) 0%, var(--navy-mid) 100%);
        border-radius: 16px;
        padding: 28px 32px;
        color: white;
        margin-bottom: 24px;
    }

    .review-summary-left {
        text-align: center;
        border-right: 1px solid rgba(255, 255, 255, 0.2);
    }

    .review-summary-number {
        font-size: 3.5rem;
        font-weight: 800;
        line-height: 1;
        color: white;
    }

    .review-summary-stars {
        color: var(--gold-light);
        font-size: 1.2rem;
        margin: 8px 0;
    }

    .review-summary-count {
        color: rgba(255, 255, 255, 0.7);
        font-size: 0.9rem;
    }

    .review-summary-right {
        padding-left: 24px;
    }

    .rating-bar-row {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 6px;
    }

    .rating-bar-label {
        color: rgba(255, 255, 255, 0.8);
        font-size: 13px;
        width: 60px;
    }

    .rating-bar-track {
        flex: 1;
        height: 8px;
        background: rgba(255, 255, 255, 0.15);
        border-radius: 4px;
        overflow: hidden;
    }

    .rating-bar-fill {
        height: 100%;
        background: var(--gold-light);
        border-radius: 4px;
        transition: width 0.5s ease;
    }

    .rating-bar-value {
        color: rgba(255, 255, 255, 0.6);
        font-size: 12px;
        width: 24px;
        text-align: right;
    }

    .review-card {
        background: white;
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 2px 12px rgba(15, 36, 68, 0.06);
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .review-card:hover {
        box-shadow: 0 12px 32px rgba(15, 36, 68, 0.12);
        transform: translateY(-4px);
        border-color: var(--gold-light);
    }

    .review-card-header {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
    }

    .reviewer-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--navy), var(--navy-mid));
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 18px;
        flex-shrink: 0;
    }

    .reviewer-info {
        flex: 1;
        margin-left: 14px;
    }

    .reviewer-name {
        font-weight: 700;
        color: var(--navy);
        font-size: 15px;
        margin-bottom: 4px;
    }

    .reviewer-bio {
        font-size: 12px;
        color: var(--muted);
    }

    .review-stars {
        color: #fbbf24;
        font-size: 20px;
        letter-spacing: 2px;
        margin: 16px 0;
        text-align: center;
    }

    .review-card-body {
        color: var(--text);
        font-size: 14px;
        line-height: 1.6;
        margin-bottom: 20px;
        flex-grow: 1;
        font-style: italic;
        text-align: center;
    }

    .review-card-footer {
        font-size: 11px;
        color: var(--muted);
        border-top: 1px solid var(--border);
        padding-top: 14px;
        text-align: center;
    }

    .review-form-card {
        background: #f8fafc;
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 28px;
        margin-bottom: 24px;
    }

    .review-form-card .form-label {
        font-weight: 600;
        font-size: 13px;
        color: var(--navy);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .rating-inputs {
        display: flex;
        gap: 4px;
        margin: 12px 0 20px;
    }

    .rating-star {
        font-size: 36px;
        cursor: pointer;
        transition: all 0.15s;
        color: #e2e8f0;
    }

    .rating-star:hover,
    .rating-star.active {
        color: var(--gold);
        transform: scale(1.1);
    }

    .review-auth-card {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border: 2px dashed var(--border);
        border-radius: 16px;
        padding: 40px 32px;
        text-align: center;
        margin-bottom: 24px;
    }

    .review-auth-icon {
        width: 64px;
        height: 64px;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
        font-size: 24px;
        color: var(--navy);
        box-shadow: 0 4px 12px rgba(15, 36, 68, 0.08);
    }

    .review-auth-card h4 {
        font-family: 'Fraunces', serif;
        color: var(--navy);
        margin-bottom: 8px;
    }

    .review-auth-card p {
        color: var(--muted);
        font-size: 14px;
        margin-bottom: 24px;
    }

    .review-auth-form {
        max-width: 380px;
        margin: 0 auto;
    }

    .review-auth-form .form-control {
        text-align: center;
        font-size: 15px;
    }

    .empty-reviews {
        text-align: center;
        padding: 48px 20px;
        color: var(--muted);
    }

    .empty-reviews i {
        font-size: 48px;
        color: #cbd5e1;
        margin-bottom: 16px;
    }

    .empty-reviews p {
        font-size: 14px;
    }

    /* BREADCRUMB */
    .book-breadcrumb {
        background: white;
        padding: 16px 0;
        border-bottom: 1px solid var(--border);
    }

    .breadcrumb-item a {
        color: var(--muted);
        text-decoration: none;
    }

    .breadcrumb-item a:hover {
        color: var(--navy);
    }

    .breadcrumb-item.active {
        color: var(--navy-mid);
    }

    /* MODAL */
    .modal-content {
        border-radius: 20px;
        border: none;
        overflow: hidden;
    }

    .modal-header-custom {
        background: linear-gradient(135deg, var(--navy), var(--navy-mid));
        padding: 24px 28px;
        color: white;
    }

    .modal-title-custom {
        font-family: 'Fraunces', serif;
        font-size: 1.3rem;
        font-weight: 700;
    }

    .selected-books-list {
        display: flex;
        gap: 12px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }

    .selected-book-chip {
        display: flex;
        align-items: center;
        gap: 8px;
        background: #f1f5f9;
        border-radius: 10px;
        padding: 8px 12px;
        font-size: 13px;
        font-weight: 500;
    }

    .selected-book-chip img {
        width: 32px;
        height: 40px;
        border-radius: 4px;
        object-fit: cover;
    }

    .form-label {
        font-weight: 600;
        font-size: 13.5px;
        color: #374151;
    }

    .form-control {
        border-radius: 10px;
        border: 1.5px solid var(--border);
        padding: 11px 14px;
        font-size: 14px;
    }

    .form-control:focus {
        border-color: var(--navy-mid);
        box-shadow: 0 0 0 3px rgba(26, 60, 107, 0.08);
    }

    .btn-submit {
        background: linear-gradient(135deg, var(--navy), var(--navy-mid));
        color: white;
        border: none;
        border-radius: 12px;
        padding: 13px 28px;
        font-size: 15px;
        font-weight: 700;
        width: 100%;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-submit:hover {
        opacity: 0.9;
        transform: translateY(-1px);
    }

    .btn-submit:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }

    /* SUCCESS STATE */
    .success-state {
        text-align: center;
        padding: 20px 0;
    }

    .success-icon {
        width: 72px;
        height: 72px;
        background: #d1fae5;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
        font-size: 32px;
    }

    .booking-chips {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        justify-content: center;
        margin-top: 12px;
    }

    .booking-chip {
        background: #f1f5f9;
        border: 1px solid var(--border);
        padding: 6px 14px;
        border-radius: 8px;
        font-size: 13px;
        font-family: monospace;
        font-weight: 600;
        color: var(--navy);
    }

    /* RESPONSIVE */
    @media (max-width: 768px) {
        .book-title-lg {
            font-size: 1.8rem;
        }

        .book-cover-lg,
        .book-cover-placeholder-lg {
            max-width: 100%;
            height: 350px;
        }

        .book-stats {
            flex-direction: column;
            gap: 16px;
        }

        .action-buttons {
            flex-direction: column;
        }

        .btn-book {
            width: 100%;
            justify-content: center;
        }
    }
    </style>
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar-sipusaka">
        <div class="container d-flex justify-content-between align-items-center">
            <a href="/" class="navbar-brand-custom">📚 Si<span>Pusaka</span></a>
            <div class="nav-links d-none d-md-flex align-items-center">
                <a href="/"><i class="fas fa-home me-1"></i> Beranda</a>
                <a href="/cek-status"><i class="fas fa-search me-1"></i> Cek Status</a>
                <a href="/mahasiswa/login"><i class="fas fa-user me-1"></i>Mahasiswa</a>
            </div>
        </div>
    </nav>

    <!-- BREADCRUMB -->
    <div class="book-breadcrumb">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="/">Beranda</a></li>
                    <li class="breadcrumb-item"><a href="/">Katalog</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $buku->nama_buku }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- BOOK INFO -->
    <section class="book-info-section">
        <div class="container">
            <div class="row">
                <!-- Book Cover -->
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <div class="text-center">
                        @if($buku->sampul_buku)
                        <img src="{{ asset('storage/'.$buku->sampul_buku) }}" alt="{{ $buku->nama_buku }}"
                            class="book-cover-lg">
                        @else
                        <div class="book-cover-placeholder-lg">📖</div>
                        @endif
                    </div>
                </div>

                <!-- Book Details -->
                <div class="col-lg-8">
                    <div class="mb-3">
                        <span class="badge-book badge-ebook">{{ $buku->jenis_buku }}</span>
                        @if($buku->genre_buku == 'Ebook' || $buku->genre_buku == 'Hybrid')
                        <span class="badge-book badge-ebook">E-Book</span>
                        @else
                        <span class="badge-book badge-physical">Fisik</span>
                        @endif
                        @if($buku->stok_tersedia > 0)
                        <span class="badge-book badge-available">Tersedia</span>
                        @else
                        <span class="badge-book badge-unavailable">Stok Habis</span>
                        @endif
                    </div>

                    <h1 class="book-title-lg">{{ $buku->nama_buku }}</h1>

                    <div class="book-meta">
                        <div class="book-meta-item">
                            <i class="fas fa-building"></i>
                            <span>{{ $buku->penerbit }}</span>
                        </div>
                        @if($buku->genre_buku)
                        <div class="book-meta-item">
                            <i class="fas fa-tag"></i>
                            <span>{{ $buku->genre_buku }}</span>
                        </div>
                        @endif
                        <div class="book-meta-item">
                            <i class="fas fa-eye"></i>
                            <strong>{{ $buku->view_count ?? 0 }}</strong>
                            <span>dilihat</span>
                        </div>
                        <div class="book-meta-item">
                            <i class="fas fa-book-open"></i>
                            <strong>{{ $buku->borrow_count ?? 0 }}</strong>
                            <span>dipinjam</span>
                        </div>
                    </div>

                    <div class="book-stats">
                        <div class="stat-item">
                            <div class="stat-value">{{ $buku->stok_total }}</div>
                            <div class="stat-label">Total Stok</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">{{ $buku->stok_tersedia }}</div>
                            <div class="stat-label">Tersedia</div>
                        </div>
                    </div>

                    <div class="action-buttons">
                        @if($buku->genre_buku == 'Ebook' || $buku->genre_buku == 'Hybrid')
                        <a href="{{ route('publik.ebook.baca', ['id' => $buku->id]) }}" class="btn-book btn-ebook">
                            <i class="fas fa-book-reader"></i>
                            Baca E-Book
                        </a>
                        @endif

                        @if($buku->genre_buku == 'Ebook')
                        {{-- Ebook tidak bisa dipinjam, hanya dibaca --}}
                        <button class="btn-book btn-secondary-book" disabled>
                            <i class="fas fa-info-circle"></i>
                            E-Book Tidak Dapat Dipinjam
                        </button>
                        @elseif($buku->stok_tersedia > 0)
                        <button onclick="openBorrowModal()" class="btn-book btn-primary-book">
                            <i class="fas fa-paper-plane"></i>
                            Pinjam Buku
                        </button>
                        @else
                        <button class="btn-book btn-secondary-book" disabled>
                            <i class="fas fa-ban"></i>
                            Stok Habis
                        </button>
                        @endif

                        <a href="/" class="btn-book btn-secondary-book">
                            <i class="fas fa-arrow-left"></i>
                            Kembali ke Katalog
                        </a>
                    </div>
                </div>
            </div>

            <!-- Book Description / Additional Info -->
            <div class="book-description">
                <h4><i class="fas fa-info-circle me-2"></i>Informasi Buku</h4>
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Judul:</strong> {{ $buku->nama_buku }}</p>
                        <p><strong>Penerbit:</strong> {{ $buku->penerbit }}</p>
                        <p><strong>Jenis Buku:</strong> {{ $buku->jenis_buku }}</p>
                        @if($buku->genre_buku)
                        <p><strong>Genre:</strong> {{ $buku->genre_buku }}</p>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <p><strong>Total Stok:</strong> {{ $buku->stok_total }}</p>
                        <p><strong>Stok Tersedia:</strong> {{ $buku->stok_tersedia }}</p>
                        <p><strong>View Count:</strong> {{ $buku->view_count ?? 0 }}</p>
                        <p><strong>Borrow Count:</strong> {{ $buku->borrow_count ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- MODAL FORM PEMINJAMAN -->
    <div class="modal fade" id="modalPinjam" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header-custom">
                    <div class="d-flex justify-content-between align-items-start w-100">
                        <div>
                            <div class="modal-title-custom">📋 Form Peminjaman Buku</div>
                            <div style="color:rgba(255,255,255,0.65);font-size:13px;margin-top:4px">
                                Isi data diri untuk mengajukan peminjaman
                            </div>
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                </div>
                <div class="modal-body p-4">

                    <!-- Form State -->
                    <div id="formState">
                        <!-- Buku Dipilih -->
                        <div class="mb-4">
                            <label class="form-label mb-2">
                                <i class="fas fa-book me-2 text-navy"></i>Buku yang Dipilih
                            </label>
                            <div class="selected-books-list" id="selectedBooksList">
                                <div class="selected-book-chip">
                                    @if($buku->sampul_buku)
                                    <img src="{{ asset('storage/'.$buku->sampul_buku) }}" alt="{{ $buku->nama_buku }}">
                                    @else
                                    <div
                                        style="width:32px;height:40px;background:#e2e8f0;border-radius:4px;display:flex;align-items:center;justify-content:center;font-size:14px">
                                        📖</div>
                                    @endif
                                    <div>
                                        <div style="font-weight:600;font-size:13px">{{ $buku->nama_buku }}</div>
                                        <div style="font-size:11px;color:var(--muted)">{{ $buku->penerbit }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="alertPinjam"></div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" id="f_nama" class="form-control" placeholder="Masukkan nama lengkap">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">NIM <span class="text-danger">*</span></label>
                                <input type="text" id="f_nim" class="form-control" placeholder="Nomor Induk Mahasiswa"
                                    oninput="fetchMahasiswaForModal(this.value)">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Jurusan <span class="text-danger">*</span></label>
                                <input type="text" id="f_jurusan" class="form-control"
                                    placeholder="Program studi / jurusan">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">No. Telepon <span class="text-danger">*</span></label>
                                <input type="text" id="f_telepon" class="form-control" placeholder="08xx-xxxx-xxxx">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Kode Referral <span class="text-danger">*</span></label>
                                <input type="text" id="f_referral_token" class="form-control"
                                    placeholder="Masukkan kode referral (6 digit)" maxlength="6"
                                    oninput="validateReferralTokenModal(this)" style="text-transform: uppercase;">
                                <small class="text-muted">Wajib diisi untuk mengakses ebook setelah persetujuan.</small>
                                <div id="referral_token_error" class="text-danger small mt-1" style="display:none;">
                                    Token referral tidak valid.</div>
                            </div>
                        </div>

                        <!-- Alert for referral token validation -->
                        <div class="alert alert-danger d-flex align-items-center mt-3" role="alert"
                            id="referral-alert-modal" style="display: none; border-radius: 10px; font-size: 13px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                                class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2" viewBox="0 0 16 16"
                                role="img" aria-label="Warning:">
                                <path
                                    d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                            </svg>
                            <div id="referral-alert-text-modal">Token referral tidak valid atau tidak ditemukan.</div>
                        </div>

                        <div class="alert alert-info mt-3 d-flex gap-2 align-items-start"
                            style="border-radius:12px;font-size:13px">
                            <i class="fas fa-info-circle mt-1"></i>
                            <div>
                                <strong>Informasi:</strong> NIM harus sudah terdaftar di sistem.
                                Batas peminjaman <strong>7 hari</strong>. Keterlambatan dikenakan denda
                                <strong>Rp10.000/hari</strong>.
                            </div>
                        </div>

                        <button class="btn-submit mt-3" id="btnSubmit" onclick="submitPinjam()">
                            <i class="fas fa-paper-plane me-2"></i>Ajukan Peminjaman
                        </button>
                    </div>

                    <!-- Success State -->
                    <div id="successState" style="display:none">
                        <div class="card border-0 shadow-sm"
                            style="border-radius: 20px; overflow: hidden; background: #fff;">
                            <div class="p-4 text-center">
                                <div class="mx-auto d-flex align-items-center justify-content-center mb-3"
                                    style="width: 72px; height: 72px; background: #d1fae5; color: #065f46; border-radius: 50%; font-size: 32px;">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <h5 class="fw-bold mb-2"
                                    style="font-family: 'Fraunces', serif; color: var(--navy); font-size: 1.25rem;">
                                    Peminjaman Berhasil!
                                </h5>
                                <p class="text-muted small mb-4">
                                    Peminjaman berhasil di-approve! QR Code telah dibuat dan email notifikasi telah
                                    dikirim.
                                </p>

                                <div class="bg-light p-3 rounded-3 text-start mb-4 border border-dashed"
                                    style="background-color: #f8fafc !important;">
                                    <div class="text-uppercase fw-bold text-muted mb-2"
                                        style="font-size: 10px; letter-spacing: 1.2px;">BOOKING ID ANDA:</div>
                                    <div class="booking-chips d-flex flex-wrap gap-2" id="bookingChips"></div>
                                </div>

                                <div class="d-flex align-items-center justify-content-center p-3 mb-4 rounded-3"
                                    style="background: #eff6ff; color: #1d4ed8; font-size: 13px;">
                                    <i class="fas fa-qrcode me-2"></i>
                                    <span>QR Code tersedia di email & history</span>
                                </div>

                                <button class="btn btn-primary w-100 py-3 fw-bold shadow-sm" onclick="resetForm()"
                                    style="border-radius: 12px; background: var(--navy); border: none; transition: all 0.2s;">
                                    Tutup & Kembali
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- REVIEW SECTION -->
    <section class="review-section">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="review-title mb-2"
                    style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:800; color:var(--navy); text-transform: uppercase; letter-spacing: 1px;">
                    ULASAN PEMBACA</h2>
                <p class="text-muted">Apa kata mereka tentang buku ini</p>
                <div style="width: 60px; height: 3px; background: var(--gold); margin: 0 auto;"></div>
            </div>

            <!-- Review Summary Card -->
            <div class="review-summary-card">
                <div class="row align-items-center">
                    <div class="col-md-4 review-summary-left">
                        <div class="review-summary-number" id="averageRating">0.0</div>
                        <div class="review-summary-stars" id="averageRatingStars">☆☆☆☆☆</div>
                        <div class="review-summary-count" id="ratingCount">Berdasarkan 0 ulasan</div>
                    </div>
                    <div class="col-md-8 review-summary-right d-none d-md-block">
                        <div class="rating-bar-row">
                            <div class="rating-bar-label">5 Bintang</div>
                            <div class="rating-bar-track">
                                <div class="rating-bar-fill" id="bar-5" style="width: 0%"></div>
                            </div>
                            <div class="rating-bar-value" id="val-5">0</div>
                        </div>
                        <div class="rating-bar-row">
                            <div class="rating-bar-label">4 Bintang</div>
                            <div class="rating-bar-track">
                                <div class="rating-bar-fill" id="bar-4" style="width: 0%"></div>
                            </div>
                            <div class="rating-bar-value" id="val-4">0</div>
                        </div>
                        <div class="rating-bar-row">
                            <div class="rating-bar-label">3 Bintang</div>
                            <div class="rating-bar-track">
                                <div class="rating-bar-fill" id="bar-3" style="width: 0%"></div>
                            </div>
                            <div class="rating-bar-value" id="val-3">0</div>
                        </div>
                        <div class="rating-bar-row">
                            <div class="rating-bar-label">2 Bintang</div>
                            <div class="rating-bar-track">
                                <div class="rating-bar-fill" id="bar-2" style="width: 0%"></div>
                            </div>
                            <div class="rating-bar-value" id="val-2">0</div>
                        </div>
                        <div class="rating-bar-row">
                            <div class="rating-bar-label">1 Bintang</div>
                            <div class="rating-bar-track">
                                <div class="rating-bar-fill" id="bar-1" style="width: 0%"></div>
                            </div>
                            <div class="rating-bar-value" id="val-1">0</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Authentication Card (shown if not authenticated) -->
            <div id="reviewAuthenticate" class="review-auth-card">
                <div class="review-auth-icon">
                    <i class="fas fa-pen-fancy"></i>
                </div>
                <h4>Bagikan Pendapat Anda</h4>
                <p>Bantu pembaca lain dengan memberikan rating dan ulasan untuk buku ini.</p>
                <div class="review-auth-form">
                    <div class="row g-2">
                        <div class="col-sm-6">
                            <input type="text" id="review_nim" class="form-control" placeholder="Masukkan NIM Anda">
                        </div>
                        <div class="col-sm-6">
                            <input type="text" id="review_token" class="form-control" placeholder="Token Referral"
                                maxlength="6" style="text-transform: uppercase;">
                        </div>
                        <div class="col-12 mt-3">
                            <button type="button" class="btn btn-primary w-100 py-2 fw-bold"
                                onclick="authenticateReview()" style="border-radius:10px; background:var(--navy);">
                                <i class="fas fa-check-circle me-2"></i>Verifikasi & Beri Ulasan
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Review Form Card (shown after authentication) -->
            <div id="reviewFormContainer" style="display:none;">
                <div class="review-form-card">
                    <div class="d-flex align-items-center mb-4">
                        <div class="reviewer-avatar me-3" id="userInitial">U</div>
                        <div>
                            <h5 class="mb-0 fw-bold" id="userNameDisplay">Halo, Mahasiswa!</h5>
                            <p class="text-muted small mb-0">Berikan penilaian terbaikmu untuk buku ini</p>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Rating Anda</label>
                        <div class="rating-inputs" id="ratingInputs">
                            <i class="fas fa-star rating-star" data-value="1" onclick="selectRating(1)"></i>
                            <i class="fas fa-star rating-star" data-value="2" onclick="selectRating(2)"></i>
                            <i class="fas fa-star rating-star" data-value="3" onclick="selectRating(3)"></i>
                            <i class="fas fa-star rating-star" data-value="4" onclick="selectRating(4)"></i>
                            <i class="fas fa-star rating-star" data-value="5" onclick="selectRating(5)"></i>
                        </div>
                        <input type="hidden" id="review_rating" value="0">
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Ulasan Anda</label>
                        <textarea id="review_comment" class="form-control" rows="4"
                            placeholder="Apa yang Anda sukai dari buku ini? Apakah isinya bermanfaat?"></textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-primary px-4 py-2 fw-bold review-submit-btn"
                            onclick="submitReview()" style="border-radius:10px; background:var(--navy);">
                            <i class="fas fa-paper-plane me-2"></i>Kirim Ulasan
                        </button>
                        <button type="button" class="btn btn-light px-4 py-2 fw-bold" onclick="location.reload()"
                            style="border-radius:10px; border:1px solid var(--border);">
                            Batal
                        </button>
                    </div>
                </div>
            </div>

            <!-- Reviews List -->
            <div id="reviewsList">
                <div class="empty-reviews">
                    <i class="fas fa-comment-dots"></i>
                    <p>Sedang memuat ulasan...</p>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="book-info-footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5 class="mb-3" style="font-family:'Fraunces',serif">SiPusaka</h5>
                    <p class="footer-muted small">
                        Sistem Informasi Perpustakaan Digital<br>
                        Menyediakan koleksi buku fisik dan digital
                    </p>
                </div>
                <div class="col-md-4 mb-4">
                    <h5 class="mb-3">Navigasi</h5>
                    <ul class="list-unstyled footer-muted small">
                        <li class="mb-2"><a href="/" class="text-decoration-none">Beranda</a></li>
                        <li class="mb-2"><a href="/cek-status" class="text-decoration-none">Cek Status</a>
                        </li>
                        <li class="mb-2"><a href="/admin/login" class="text-decoration-none">Admin Login</a>
                        </li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h5 class="mb-3">Kontak</h5>
                    <p class="footer-muted small">
                        hnet.diigital.biz.id<br>
                        <i class="fas fa-envelope me-2"></i>info@siplushaka.id<br>
                        <i class="fas fa-phone me-2"></i>+62 812-3456-7890
                    </p>
                </div>
            </div>
            <div class="border-top border-secondary pt-4 mt-4 text-center footer-muted small">
                © {{ date('Y') }} SiPusaka - Sistem Informasi Perpustakaan Digital
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Book data from server
    const bookData = {
        id: parseInt('{{ $buku->id }}'),
        nama: '{{ $buku->nama_buku }}',
        penerbit: '{{ $buku->penerbit }}',
        sampul: '{{ $buku->sampul_buku ? asset("storage/".$buku->sampul_buku) : "" }}'
    };

    document.addEventListener('DOMContentLoaded', function() {
        renderSelectedBooksList();
        loadReviews();
    });

    function openBorrowModal() {
        const modal = new bootstrap.Modal(document.getElementById('modalPinjam'));
        modal.show();
    }

    function renderSelectedBooksList() {
        const container = document.getElementById('selectedBooksList');
        container.innerHTML = `
                <div class="selected-book-chip" id="chip-${bookData.id}">
                    ${bookData.sampul
                        ? `<img src="${bookData.sampul}" alt="${bookData.nama}">`
                        : `<div style="width:32px;height:40px;background:#e2e8f0;border-radius:4px;display:flex;align-items:center;justify-content:center;font-size:14px">📖</div>`
                    }
                    <div>
                        <div style="font-weight:600;font-size:13px">${bookData.nama}</div>
                        <div style="font-size:11px;color:var(--muted)">${bookData.penerbit}</div>
                    </div>
                </div>
            `;
    }

    function validateReferralTokenModal(input) {
        const token = input.value.trim().toUpperCase();
        input.value = token;

        const errorDiv = document.getElementById('referral_token_error');
        const alertDiv = document.getElementById('referral-alert-modal');

        if (token.length === 6) {
            fetch(`{{ route('publik.validate-token') }}?token=${token}`)
                .then(res => res.json())
                .then(data => {
                    if (data.valid) {
                        errorDiv.style.display = 'none';
                        alertDiv.style.display = 'none';
                        input.style.borderColor = '#059669';
                    } else {
                        errorDiv.style.display = 'block';
                        alertDiv.style.display = 'flex';
                        document.getElementById('referral-alert-text-modal').textContent =
                            'Token referral tidak valid atau tidak ditemukan.';
                        input.style.borderColor = '#dc2626';
                    }
                })
                .catch(() => {
                    errorDiv.style.display = 'block';
                    alertDiv.style.display = 'flex';
                    input.style.borderColor = '#dc2626';
                });
        } else {
            errorDiv.style.display = 'none';
            alertDiv.style.display = 'none';
            input.style.borderColor = '';
        }
    }

    function submitPinjam() {
        const nama = document.getElementById('f_nama').value.trim();
        const nim = document.getElementById('f_nim').value.trim();
        const jurusan = document.getElementById('f_jurusan').value.trim();
        const telepon = document.getElementById('f_telepon').value.trim();
        const referralToken = document.getElementById('f_referral_token').value.trim().toUpperCase();

        if (!nama || !nim || !jurusan || !telepon || !referralToken) {
            document.getElementById('alertPinjam').innerHTML =
                '<div class="alert alert-danger" style="border-radius:10px"><i class="fas fa-exclamation-circle me-2"></i>Semua field wajib diisi!</div>';
            return;
        }

        if (referralToken.length !== 6) {
            document.getElementById('alertPinjam').innerHTML =
                '<div class="alert alert-danger" style="border-radius:10px"><i class="fas fa-exclamation-circle me-2"></i>Token referral harus 6 digit!</div>';
            return;
        }

        const btn = document.getElementById('btnSubmit');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses...';

        const data = {
            nama,
            nim,
            jurusan,
            no_telepon: telepon,
            referral_token: referralToken,
            buku_ids: [bookData.id], // Only the current book is selected
        };

        fetch('{{ route("publik.pinjam") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify(data)
            })
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    document.getElementById('formState').style.display = 'none';
                    document.getElementById('successState').style.display = 'block';

                    const chips = res.booking_ids.map(id =>
                        `<div class="booking-chip">${id}</div>`
                    ).join('');
                    document.getElementById('bookingChips').innerHTML = chips;
                } else {
                    document.getElementById('alertPinjam').innerHTML =
                        `<div class="alert alert-danger" style="border-radius:10px"><i class="fas fa-exclamation-circle me-2"></i>${res.message}</div>`;
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-paper-plane me-2"></i>Ajukan Peminjaman';
                }
            })
            .catch(() => {
                document.getElementById('alertPinjam').innerHTML =
                    '<div class="alert alert-danger" style="border-radius:10px">Terjadi kesalahan jaringan. Coba lagi.</div>';
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-paper-plane me-2"></i>Ajukan Peminjaman';
            });
    }

    function resetForm() {
        const modalElement = document.getElementById('modalPinjam');
        const modalInstance = bootstrap.Modal.getInstance(modalElement);
        if (modalInstance) {
            modalInstance.hide();
        }
        setTimeout(() => {
            window.location.href = '/';
        }, 300);
    }

    let nimModalTimer;

    function fetchMahasiswaForModal(nim) {
        clearTimeout(nimModalTimer);
        const cleanedNim = nim.trim().replace(/\D/g, '');

        if (cleanedNim.length < 5) {
            resetModalMahasiswaFields();
            return;
        }

        nimModalTimer = setTimeout(() => {
            fetch(`/pinjam/get-mahasiswa/${cleanedNim}`)
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('f_nama').value = data.data.nama;
                        document.getElementById('f_jurusan').value = data.data.jurusan;
                        document.getElementById('f_telepon').value = data.data.no_telepon || '';

                        document.getElementById('f_nama').readOnly = true;
                        document.getElementById('f_jurusan').readOnly = true;
                        document.getElementById('f_telepon').readOnly = true;
                    } else {
                        resetModalMahasiswaFields();
                    }
                })
                .catch(err => {
                    console.error('Error fetching mahasiswa data:', err);
                    resetModalMahasiswaFields();
                });
        }, 400);
    }

    function resetModalMahasiswaFields() {
        document.getElementById('f_nama').value = '';
        document.getElementById('f_jurusan').value = '';
        document.getElementById('f_telepon').value = '';
        document.getElementById('f_nama').readOnly = false;
        document.getElementById('f_jurusan').readOnly = false;
        document.getElementById('f_telepon').readOnly = false;
    }

    // Review functionality
    let currentReviewRating = 0;
    let authenticatedReviewUser = null;

    function selectRating(rating) {
        currentReviewRating = rating;
        const stars = document.querySelectorAll('.rating-star');
        stars.forEach((star, index) => {
            if (index < rating) {
                star.classList.add('active');
            } else {
                star.classList.remove('active');
            }
        });
        document.getElementById('review_rating').value = rating;
    }

    function authenticateReview() {
        const nim = document.getElementById('review_nim').value.trim();
        const token = document.getElementById('review_token').value.trim().toUpperCase();

        if (!nim || !token) {
            alert('NIM dan token referral wajib diisi!');
            return;
        }

        if (token.length !== 6) {
            alert('Token referral harus 6 digit!');
            return;
        }

        fetch(`/pinjam/cek-nim?nim=${encodeURIComponent(nim)}&referral_token=${encodeURIComponent(token)}`)
            .then(res => {
                if (!res.ok) {
                    return res.json().then(err => {
                        throw err;
                    });
                }
                return res.json();
            })
            .then(data => {
                if (data.success) {
                    authenticatedReviewUser = {
                        nim: nim,
                        token: token,
                        id: data.data.id,
                        nama: data.data.nama
                    };

                    document.getElementById('userNameDisplay').textContent =
                        `Halo, ${authenticatedReviewUser.nama}!`;
                    document.getElementById('userInitial').textContent = authenticatedReviewUser.nama.charAt(0)
                        .toUpperCase();

                    document.getElementById('reviewAuthenticate').style.display = 'none';
                    document.getElementById('reviewFormContainer').style.display = 'block';

                    document.getElementById('reviewFormContainer').scrollIntoView({
                        behavior: 'smooth'
                    });
                } else {
                    alert(data.message || 'Gagal memverifikasi data.');
                }
            })
            .catch(err => {
                console.error('Error:', err);
                alert(err.message || 'Terjadi kesalahan saat memverifikasi data. Coba lagi.');
            });
    }

    function submitReview() {
        if (!authenticatedReviewUser) {
            alert('Silakan verifikasi terlebih dahulu!');
            return;
        }

        if (currentReviewRating === 0) {
            alert('Silakan berikan rating!');
            return;
        }

        const comment = document.getElementById('review_comment').value.trim();
        const btn = document.querySelector('#reviewFormContainer .review-submit-btn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Mengirim...';

        const data = {
            buku_id: bookData.id,
            nim: authenticatedReviewUser.nim,
            referral_token: authenticatedReviewUser.token,
            rating: currentReviewRating,
            comment: comment
        };

        fetch('{{ route("publik.review.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify(data)
            })
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    alert('Ulasan berhasil ditambahkan!');

                    document.getElementById('reviewFormContainer').style.display = 'none';
                    document.getElementById('reviewAuthenticate').style.display = 'block';
                    document.getElementById('review_nim').value = '';
                    document.getElementById('review_token').value = '';
                    document.getElementById('review_rating').value = '0';
                    document.getElementById('review_comment').value = '';
                    currentReviewRating = 0;
                    document.querySelectorAll('.rating-star').forEach(s => s.classList.remove('active'));

                    loadReviews();
                } else {
                    alert(res.message || 'Gagal menambahkan ulasan.');
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-paper-plane me-2"></i>Kirim Ulasan';
                }
            })
            .catch(() => {
                alert('Terjadi kesalahan saat mengirim ulasan. Coba lagi.');
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-paper-plane me-2"></i>Kirim Ulasan';
            });
    }

    function loadReviews() {
        fetch(`{{ route('publik.review.get', $buku->id) }}`)
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const reviews = data.reviews;
                    const reviewsList = document.getElementById('reviewsList');

                    const avgRating = data.average_rating;
                    document.getElementById('averageRating').textContent = avgRating.toFixed(1);

                    const ratingStars = document.getElementById('averageRatingStars');
                    ratingStars.textContent = '★'.repeat(Math.round(avgRating)) + '☆'.repeat(5 - Math.round(
                        avgRating));

                    document.getElementById('ratingCount').textContent = `Berdasarkan ${data.rating_count} ulasan`;

                    const ratingCounts = data.rating_counts;
                    const totalReviews = data.rating_count;

                    for (let i = 1; i <= 5; i++) {
                        const count = ratingCounts[i] || 0;
                        const percentage = totalReviews > 0 ? (count / totalReviews) * 100 : 0;
                        document.getElementById(`bar-${i}`).style.width = `${percentage}%`;
                        document.getElementById(`val-${i}`).textContent = count;
                    }

                    if (reviews.length === 0) {
                        reviewsList.innerHTML = `
                                <div class="empty-reviews">
                                    <i class="fas fa-comment-dots"></i>
                                    <p>Belum ada ulasan untuk buku ini.</p>
                                </div>
                            `;
                    } else {
                        let html = '<div class="row g-4">';
                        html += reviews.map(review => {
                            const initial = review.mahasiswa ? review.mahasiswa.nama.charAt(0)
                                .toUpperCase() : 'M';
                            const name = review.mahasiswa ? review.mahasiswa.nama : 'Mahasiswa';
                            const jurusan = review.mahasiswa ? review.mahasiswa.jurusan : 'Pembaca';
                            const stars = '★'.repeat(review.rating) + '☆'.repeat(5 - review.rating);
                            const comment = review.comment ? `"${review.comment}"` : '"Tidak ada komentar"';

                            return `
                                <div class="col-md-4">
                                    <div class="review-card">
                                        <div class="review-card-header">
                                            <div class="reviewer-avatar">${initial}</div>
                                            <div class="reviewer-info">
                                                <div class="reviewer-name">${name}</div>
                                                <div class="reviewer-bio">${jurusan}</div>
                                            </div>
                                        </div>
                                        <div class="review-stars">
                                            ${stars}
                                        </div>
                                        <div class="review-card-body">
                                            ${comment}
                                        </div>
                                        <div class="review-card-footer">
                                            Ulasan: ${bookData.nama}
                                        </div>
                                    </div>
                                </div>
                            `;
                        }).join('');
                        html += '</div>';
                        reviewsList.innerHTML = html;
                    }
                }
            })
            .catch(err => {
                console.error('Error loading reviews:', err);
            });
    }
    </script>
</body>

</html>
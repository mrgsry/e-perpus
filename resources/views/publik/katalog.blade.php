<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Buku | SiPusaka</title>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,wght@0,300;0,700;0,900;1,300&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
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
            --green: #059669;
            --red: #dc2626;
        }
        * { box-sizing: border-box; }
        body {
            font-family: 'DM Sans', sans-serif;
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
            box-shadow: 0 4px 24px rgba(15,36,68,0.25);
        }
        .navbar-brand-custom {
            font-family: 'Fraunces', serif;
            font-size: 1.6rem;
            font-weight: 900;
            color: white;
            text-decoration: none;
        }
        .navbar-brand-custom span { color: var(--gold-light); }
        .nav-links a {
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            font-size: 14px;
            margin-left: 24px;
            transition: color 0.2s;
        }
        .nav-links a:hover { color: var(--gold-light); }
        .cart-btn {
            background: var(--gold);
            color: white;
            border: none;
            padding: 8px 18px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
            position: relative;
        }
        .cart-btn:hover { background: var(--gold-light); transform: translateY(-1px); }
        .cart-count {
            background: var(--red);
            color: white;
            width: 20px; height: 20px;
            border-radius: 50%;
            font-size: 11px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* HERO */
        .hero-section {
            background: linear-gradient(135deg, var(--navy) 0%, #1a3c6b 50%, #0d3460 100%);
            padding: 64px 0 80px;
            position: relative;
            overflow: hidden;
        }
        .hero-section::before {
            content: '';
            position: absolute;
            top: -100px; right: -100px;
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(201,147,10,0.12) 0%, transparent 70%);
            border-radius: 50%;
        }
        .hero-title {
            font-family: 'Fraunces', serif;
            font-size: clamp(2rem, 5vw, 3.5rem);
            font-weight: 900;
            color: white;
            line-height: 1.15;
            margin-bottom: 16px;
        }
        .hero-title span { color: var(--gold-light); font-style: italic; }
        .hero-sub { color: rgba(255,255,255,0.65); font-size: 16px; max-width: 480px; }
        .hero-stats {
            display: flex;
            gap: 32px;
            margin-top: 32px;
        }
        .hero-stat strong {
            display: block;
            font-family: 'Fraunces', serif;
            font-size: 2rem;
            font-weight: 900;
            color: var(--gold-light);
        }
        .hero-stat span { color: rgba(255,255,255,0.6); font-size: 13px; }

        /* SEARCH & FILTER */
        .filter-bar {
            background: white;
            padding: 20px 0;
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: 63px;
            z-index: 90;
            box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        }
        .search-input {
            border: 1.5px solid var(--border);
            border-radius: 10px;
            padding: 10px 16px 10px 42px;
            font-size: 14px;
            width: 100%;
            outline: none;
            transition: border-color 0.2s;
        }
        .search-input:focus { border-color: var(--navy-mid); }
        .search-wrap { position: relative; }
        .search-icon {
            position: absolute;
            left: 14px; top: 50%;
            transform: translateY(-50%);
            color: var(--muted);
            font-size: 14px;
        }
        .filter-chip {
            padding: 7px 16px;
            border-radius: 20px;
            border: 1.5px solid var(--border);
            background: white;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            white-space: nowrap;
        }
        .filter-chip:hover, .filter-chip.active {
            background: var(--navy);
            color: white;
            border-color: var(--navy);
        }
        .filter-chips { display: flex; gap: 8px; flex-wrap: nowrap; overflow-x: auto; padding-bottom: 2px; }
        .filter-chips::-webkit-scrollbar { display: none; }

        /* BOOK GRID */
        .catalog-section { padding: 40px 0 80px; }
        .section-header {
            display: flex;
            align-items: baseline;
            gap: 12px;
            margin-bottom: 28px;
        }
        .section-title {
            font-family: 'Fraunces', serif;
            font-size: 1.6rem;
            font-weight: 700;
        }
        .section-count { color: var(--muted); font-size: 14px; }
        .books-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
        }

        /* BOOK CARD */
        .book-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(15,36,68,0.06);
            border: 2px solid transparent;
            transition: all 0.25s;
            cursor: pointer;
            position: relative;
        }
        .book-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 32px rgba(15,36,68,0.14);
            border-color: var(--navy-mid);
        }
        .book-card.selected {
            border-color: var(--gold);
            box-shadow: 0 8px 28px rgba(201,147,10,0.2);
        }
        .book-card.out-of-stock {
            opacity: 0.65;
            cursor: not-allowed;
        }
        .book-card.out-of-stock:hover { transform: none; box-shadow: none; border-color: transparent; }

        .book-cover {
            width: 100%;
            height: 200px;
            object-fit: cover;
            display: block;
        }
        .book-cover-placeholder {
            width: 100%;
            height: 200px;
            background: linear-gradient(135deg, #e2e8f0, #cbd5e1);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
        }
        .book-info { padding: 14px; }
        .book-jenis {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--navy-mid);
            background: rgba(26,60,107,0.08);
            padding: 2px 8px;
            border-radius: 4px;
            display: inline-block;
            margin-bottom: 8px;
        }
        .book-title {
            font-family: 'Fraunces', serif;
            font-size: 14px;
            font-weight: 700;
            color: var(--text);
            line-height: 1.4;
            margin-bottom: 4px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .book-penerbit {
            font-size: 12px;
            color: var(--muted);
            margin-bottom: 10px;
        }
        .book-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .stok-badge {
            font-size: 11px;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 6px;
        }
        .stok-ada { background: #d1fae5; color: #065f46; }
        .stok-sedikit { background: #fef3c7; color: #92400e; }
        .stok-habis { background: #fee2e2; color: #991b1b; }
        .pick-btn {
            width: 28px; height: 28px;
            border-radius: 50%;
            border: 2px solid var(--navy);
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            color: var(--navy);
            transition: all 0.2s;
        }
        .book-card.selected .pick-btn {
            background: var(--gold);
            border-color: var(--gold);
            color: white;
        }
        .selected-badge {
            position: absolute;
            top: 10px; right: 10px;
            background: var(--gold);
            color: white;
            width: 28px; height: 28px;
            border-radius: 50%;
            display: none;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 700;
            box-shadow: 0 2px 8px rgba(201,147,10,0.4);
        }
        .book-card.selected .selected-badge { display: flex; }

        /* FLOATING CART */
        .floating-cart {
            position: fixed;
            bottom: 24px;
            left: 50%;
            transform: translateX(-50%) translateY(100px);
        background: var(--navy);
        color: white;
        border-radius: 20px;
        padding: 16px 28px;
        display: flex;
        align-items: center;
        gap: 20px;
        box-shadow: 0 20px 60px rgba(15,36,68,0.4);
        z-index: 200;
        transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        min-width: 380px;
    }
    .floating-cart.show { transform: translateX(-50%) translateY(0); }
    .cart-preview { display: flex; gap: 8px; flex: 1; overflow-x: auto; }
    .cart-mini-cover {
            width: 36px; height: 48px;
            border-radius: 6px;
            object-fit: cover;
            border: 2px solid rgba(255,255,255,0.2);
        }
        .cart-mini-placeholder {
            width: 36px; height: 48px;
            border-radius: 6px;
            background: rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
        }
        .cart-info strong { display: block; font-size: 14px; font-weight: 700; }
        .cart-info span { font-size: 12px; color: rgba(255,255,255,0.6); }
        .pinjam-btn {
            background: var(--gold);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            white-space: nowrap;
        }
        .pinjam-btn:hover { background: var(--gold-light); transform: scale(1.03); }

        /* READ BUTTON */
        .btn-primary {
            background: var(--navy) !important;
            border-color: var(--navy) !important;
            color: white !important;
            padding: 6px 12px !important;
            font-size: 12px !important;
            font-weight: 600 !important;
            border-radius: 8px !important;
            transition: all 0.2s !important;
        }
        .btn-primary:hover {
            background: var(--navy-mid) !important;
            transform: translateY(-1px) !important;
        }

        /* MODAL */
        .modal-content { border-radius: 20px; border: none; overflow: hidden; }
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
        .selected-books-list { display: flex; gap: 12px; margin-bottom: 24px; flex-wrap: wrap; }
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
            width: 32px; height: 40px;
            border-radius: 4px;
            object-fit: cover;
        }
        .remove-chip {
            width: 20px; height: 20px;
            background: #fee2e2;
            color: var(--red);
            border: none;
            border-radius: 50%;
            font-size: 10px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .form-label { font-weight: 600; font-size: 13.5px; color: #374151; }
        .form-control {
            border-radius: 10px;
            border: 1.5px solid var(--border);
            padding: 11px 14px;
            font-size: 14px;
        }
        .form-control:focus {
            border-color: var(--navy-mid);
            box-shadow: 0 0 0 3px rgba(26,60,107,0.08);
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
        .btn-submit:hover { opacity: 0.9; transform: translateY(-1px); }
        .btn-submit:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }

        /* SUCCESS STATE */
        .success-state { text-align: center; padding: 20px 0; }
        .success-icon {
            width: 72px; height: 72px;
            background: #d1fae5;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            font-size: 32px;
        }
        .booking-chips { display: flex; gap: 8px; flex-wrap: wrap; justify-content: center; margin-top: 12px; }
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

        /* EMPTY STATE */
        .empty-state { text-align: center; padding: 80px 0; color: var(--muted); }
        .empty-state i { font-size: 64px; margin-bottom: 16px; color: #cbd5e1; }

        /* PDF READER */
        #pdf-viewer {
            width: 100%;
            height: 70vh;
            overflow-y: auto;
            background: #525659;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }
        .pdf-page {
            margin-bottom: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
            max-width: 100%;
        }
        .reader-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background: #f8fafc;
            border-bottom: 1px solid var(--border);
        }

        /* RESPONSIVE */
        @media (max-width: 576px) {
            .books-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
            .floating-cart { min-width: calc(100vw - 32px); }
            .hero-stats { gap: 20px; }
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
            <a href="/admin/login" style="color:rgba(255,255,255,0.5);font-size:12px">Admin</a>
        </div>
        <button class="cart-btn" onclick="bukaModalPinjam()" id="cartBtn" style="display:none">
            <i class="fas fa-book-open"></i>
            <span id="cartLabel">Pinjam Buku</span>
            <div class="cart-count" id="cartCount">0</div>
        </button>
    </div>
</nav>

<!-- HERO -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <div class="hero-title">
                    Temukan Buku<br><span>Favoritmu</span> di Sini
                </div>
                <p class="hero-sub">
                    Jelajahi koleksi buku perpustakaan digital kami. Pilih hingga 3 buku sekaligus dan ajukan peminjaman dalam satu langkah.
                </p>
                <div class="hero-stats">
                    <div class="hero-stat">
                        <strong>{{ $bukus->count() }}</strong>
                        <span>Total Buku</span>
                    </div>
                    <div class="hero-stat">
                        <strong>{{ $bukus->where('stok_tersedia', '>', 0)->count() }}</strong>
                        <span>Tersedia</span>
                    </div>
                    <div class="hero-stat">
                        <strong>{{ $jenisBuku->count() }}</strong>
                        <span>Kategori</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 d-none d-lg-block text-end">
                <div style="font-size:120px;opacity:0.15;line-height:1">📚</div>
            </div>
        </div>
    </div>
</section>

<!-- FILTER BAR -->
<div class="filter-bar">
    <div class="container">
        <div class="row g-3 align-items-center">
            <div class="col-md-4">
                <div class="search-wrap">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-input" id="searchInput"
                           placeholder="Cari judul buku atau penerbit...">
                </div>
            </div>
            <div class="col-md-8">
                <div class="filter-chips">
                    <button class="filter-chip active" onclick="filterJenis('semua', this)">
                        Semua
                    </button>
                    @foreach($jenisBuku as $jenis)
                    <button class="filter-chip" onclick="filterJenis('{{ $jenis }}', this)">
                        {{ $jenis }}
                    </button>
                    @endforeach
                    <button class="filter-chip" onclick="filterJenis('tersedia', this)" style="border-color:#059669;color:#059669">
                        ✅ Tersedia
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CATALOG -->
<section class="catalog-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Koleksi Buku</h2>
            <span class="section-count" id="bookCount">{{ $bukus->count() }} buku</span>
        </div>

        <div class="books-grid" id="booksGrid">
            @forelse($bukus as $buku)
             <div class="book-card {{ $buku->stok_tersedia < 1 ? 'out-of-stock' : '' }}"
                  id="card-{{ $buku->id }}"
                  data-id="{{ $buku->id }}"
                  data-nama="{{ $buku->nama_buku }}"
                  data-penerbit="{{ $buku->penerbit }}"
                  data-jenis="{{ $buku->jenis_buku }}"
                  data-genre="{{ $buku->genre_buku ?? 'Fisik' }}"
                  data-stok="{{ $buku->stok_tersedia }}"
                  data-sampul="{{ $buku->sampul_buku ? asset('storage/'.$buku->sampul_buku) : '' }}"
                    data-ebook="{{ $buku->file_ebook ? route('publik.ebook.baca', ['id' => $buku->id]) : '' }}">

                <div class="selected-badge" id="badge-{{ $buku->id }}">✓</div>

                @if($buku->sampul_buku)
                    <img src="{{ asset('storage/'.$buku->sampul_buku) }}"
                         alt="{{ $buku->nama_buku }}" class="book-cover"
                         data-book-id="{{ $buku->id }}"
                         onclick="handleCoverClick(this)">
                @else
                    <div class="book-cover-placeholder" 
                         data-book-id="{{ $buku->id }}"
                         onclick="handleCoverClick(this)">📖</div>
                @endif

                <div class="book-info">
                    <span class="book-jenis">{{ $buku->jenis_buku }}</span>
                    <div class="book-title">{{ $buku->nama_buku }}</div>
                    <div class="book-penerbit">{{ $buku->penerbit }}</div>
                    
                    <!-- Stats: Views and Borrows -->
                    <div style="display: flex; gap: 12px; margin: 8px 0; font-size: 11px; color: var(--muted);">
                        <span title="Total dilihat">
                            <i class="fas fa-eye" style="color: #3b82f6;"></i> {{ $buku->view_count ?? 0 }}
                        </span>
                        <span title="Total dipinjam">
                            <i class="fas fa-book-open" style="color: #10b981;"></i> {{ $buku->borrow_count ?? 0 }}
                        </span>
                    </div>
                    
                    <div class="book-footer">
                        @if($buku->genre_buku == 'Ebook' || $buku->genre_buku == 'Hybrid')
                            <span class="stok-badge stok-ada">E-Book</span>
                        @else
                            @if($buku->stok_tersedia < 1)
                                <span class="stok-badge stok-habis">Stok Habis</span>
                            @elseif($buku->stok_tersedia <= 3)
                                <span class="stok-badge stok-sedikit">Sisa {{ $buku->stok_tersedia }}</span>
                            @else
                                <span class="stok-badge stok-ada">Tersedia {{ $buku->stok_tersedia }}</span>
                            @endif
                        @endif
                        
                        @if($buku->genre_buku == 'Ebook' || $buku->genre_buku == 'Hybrid')
                            <a href="{{ route('publik.ebook.baca', ['id' => $buku->id]) }}" class="btn btn-sm btn-primary" id="readbtn-{{ $buku->id }}" target="_blank">
                                <i class="fas fa-book-reader me-1"></i>Baca
                            </a>
                        @elseif($buku->stok_tersedia > 0)
                            <div class="pick-btn" id="pickbtn-{{ $buku->id }}">
                                <i class="fas fa-plus"></i>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="empty-state" style="grid-column:1/-1">
                <i class="fas fa-book-open"></i>
                <p>Belum ada buku tersedia</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- FLOATING CART -->
<div class="floating-cart" id="floatingCart">
    <div>
        <div class="cart-info">
            <strong id="floatTitle">0 Buku Dipilih</strong>
            <span>Maks. 3 buku per peminjaman</span>
        </div>
        <div class="cart-preview mt-2" id="cartPreview"></div>
    </div>
    <button class="pinjam-btn" onclick="bukaModalPinjam()">
        <i class="fas fa-paper-plane me-2"></i>Ajukan Pinjam
    </button>
</div>

<!-- MODAL EBOOK READER -->
<div class="modal fade" id="modalEbook" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header bg-navy text-white py-2">
                <h5 class="modal-title" id="ebookTitle">Baca E-Book</h5>
                <div class="d-flex align-items-center gap-3">
                    <span id="pageCount" class="badge bg-light text-navy">Hal: 0/0</span>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" onclick="closeEbook()"></button>
                </div>
            </div>
            <div class="reader-controls">
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-outline-secondary" onclick="prevPage()"><i class="fas fa-chevron-left"></i></button>
                    <button class="btn btn-sm btn-outline-secondary" onclick="nextPage()"><i class="fas fa-chevron-right"></i></button>
                </div>
                <div class="text-muted small" id="accessStatus">Akses: Preview (25%)</div>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-gold" id="btnVerifyReader" onclick="showVerifyModal()">
                        <i class="fas fa-unlock me-1"></i>Buka Akses Full
                    </button>
                </div>
            </div>
            <div class="modal-body p-0">
                <div id="pdf-viewer">
                    <div id="pdf-loader" class="text-white mt-5">
                        <i class="fas fa-circle-notch fa-spin fa-2x"></i>
                        <p class="mt-2">Memuat dokumen...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL VERIFIKASI NIM READER -->
<div class="modal fade" id="modalVerifyReader" tabindex="-1" style="z-index: 1060;">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content shadow-lg">
            <div class="modal-body p-4 text-center">
                <div class="mb-3 text-gold">
                    <i class="fas fa-lock fa-3x"></i>
                </div>
                <h5 class="fw-bold">Akses Terbatas</h5>
                <p class="text-muted small">Silakan verifikasi NIM Anda untuk membaca buku ini secara lengkap.</p>
                <div class="mb-3">
                    <input type="text" id="reader_nim" class="form-control text-center" placeholder="Masukkan NIM Anda">
                </div>
                <div id="verifyError" class="text-danger small mb-2" style="display:none">NIM tidak terdaftar!</div>
                <button class="btn btn-navy w-100 py-2" onclick="verifyReaderNim()">Verifikasi NIM</button>
                <button class="btn btn-link btn-sm mt-2 text-muted text-decoration-none" data-bs-dismiss="modal">Nanti Saja</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL FORM PEMINJAMAN -->
<div class="modal fade" id="modalPinjam" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header-custom">
                <div class="d-flex justify-content-between align-items-start">
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
                        <div class="selected-books-list" id="selectedBooksList"></div>
                    </div>

                    <div id="alertPinjam"></div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" id="f_nama" class="form-control" placeholder="Masukkan nama lengkap">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">NIM <span class="text-danger">*</span></label>
                            <input type="text" id="f_nim" class="form-control" placeholder="Nomor Induk Mahasiswa" oninput="fetchMahasiswaForModal(this.value)">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Jurusan <span class="text-danger">*</span></label>
                            <input type="text" id="f_jurusan" class="form-control" placeholder="Program studi / jurusan">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">No. Telepon <span class="text-danger">*</span></label>
                            <input type="text" id="f_telepon" class="form-control" placeholder="08xx-xxxx-xxxx">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Kode Referral <span class="text-danger">*</span></label>
                            <input type="text" id="f_referral_token" class="form-control" placeholder="Masukkan kode referral (6 digit)" maxlength="6" oninput="validateReferralTokenModal(this)" style="text-transform: uppercase;">
                            <small class="text-muted">Wajib diisi untuk mengakses ebook setelah persetujuan.</small>
                            <div id="referral_token_error" class="text-danger small mt-1" style="display:none;">Token referral tidak valid.</div>
                        </div>
                    </div>

                    <!-- Alert for referral token validation -->
                    <div class="alert alert-danger d-flex align-items-center mt-3" role="alert" id="referral-alert-modal" style="display: none; border-radius: 10px; font-size: 13px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2" viewBox="0 0 16 16" role="img" aria-label="Warning:">
                            <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                        </svg>
                        <div id="referral-alert-text-modal">Token referral tidak valid atau tidak ditemukan.</div>
                    </div>

                    <div class="alert alert-info mt-3 d-flex gap-2 align-items-start" style="border-radius:12px;font-size:13px">
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
                    <div class="success-state">
                        <div class="success-icon">✅</div>
                        <h5 style="font-family:'Fraunces',serif;font-weight:700;margin-bottom:8px">
                            Peminjaman Berhasil Diajukan!
                        </h5>
                        <p style="color:var(--muted);font-size:14px">
                            Permintaan peminjaman kamu sudah diterima. Tunggu konfirmasi dan QR Code dari admin.
                        </p>
                        <div style="background:#f8fafc;border-radius:12px;padding:16px;margin:16px 0;text-align:left">
                            <div style="font-size:12px;color:var(--muted);margin-bottom:8px;text-transform:uppercase;letter-spacing:1px;font-weight:600">
                                Booking ID kamu:
                            </div>
                            <div class="booking-chips" id="bookingChips"></div>
                        </div>
                        <p style="font-size:12px;color:var(--muted)">
                            Simpan Booking ID di atas untuk cek status peminjaman
                        </p>
                        <button class="btn btn-primary mt-2" onclick="resetForm()" style="border-radius:10px">
                            <i class="fas fa-redo me-2"></i>Pinjam Buku Lagi
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script>
    // Set PDF.js worker source immediately after library loads
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

    // State management
    let selectedBooks = [];
    const MAX_BOOKS = 3;
    
    // PDF Reader State
    let pdfDoc = null;
    let pageNum = 1;
    let pageRendering = false;
    let pageNumPending = null;
    let scale = 1.5;
    let canvas = document.createElement('canvas');
    let ctx = canvas.getContext('2d');
    let isFullAccess = false;
    let currentEbookUrl = '';
    let maxPages = 0;

    function handleCoverClick(element) {
        event.stopPropagation();
        
        const id = element.dataset.bookId;
        const card = document.getElementById('card-' + id);
        const genre = card.getAttribute('data-genre');
        const stok = parseInt(card.dataset.stok);
        
        if (genre === 'Ebook' || genre === 'Hybrid') {
            window.open('/ebook/baca/' + id, '_blank');
        } else if (stok > 0) {
            togglePilih(card);
        }
    }

    function togglePilih(card) {
        const id = parseInt(card.dataset.id);
        const idx = selectedBooks.findIndex(b => b.id === id);

        if (idx > -1) {
            // Unselect
            selectedBooks.splice(idx, 1);
            card.classList.remove('selected');
        } else {
            if (selectedBooks.length >= MAX_BOOKS) {
                showToast('Maksimal 3 buku yang bisa dipilih sekaligus!', 'warning');
                card.classList.add('shake');
                setTimeout(() => card.classList.remove('shake'), 400);
                return;
            }
            // Select
            selectedBooks.push({
                id: id,
                nama: card.dataset.nama,
                penerbit: card.dataset.penerbit,
                sampul: card.dataset.sampul,
            });
            card.classList.add('selected');
        }

        updateCartUI();
    }

    function updateCartUI() {
        const count = selectedBooks.length;

        // Update navbar btn
        const cartBtn = document.getElementById('cartBtn');
        const cartCount = document.getElementById('cartCount');
        const cartLabel = document.getElementById('cartLabel');
        if (count > 0) {
            cartBtn.style.display = 'flex';
            cartCount.textContent = count;
            cartLabel.textContent = count === 1 ? '1 Dipilih' : count + ' Dipilih';
        } else {
            cartBtn.style.display = 'none';
        }

        // Update floating cart
        const floatingCart = document.getElementById('floatingCart');
        const floatTitle = document.getElementById('floatTitle');
        const cartPreview = document.getElementById('cartPreview');

        if (count > 0) {
            floatingCart.classList.add('show');
            floatTitle.textContent = count + ' Buku Dipilih';

            cartPreview.innerHTML = selectedBooks.map(b => `
                <div title="${b.nama}">
                    ${b.sampul
                        ? `<img src="${b.sampul}" class="cart-mini-cover" alt="${b.nama}">`
                        : `<div class="cart-mini-placeholder">📖</div>`
                    }
                </div>
            `).join('');
        } else {
            floatingCart.classList.remove('show');
        }
    }

    function bukaModalPinjam() {
        if (selectedBooks.length === 0) return;
        renderSelectedBooksList();
        const modal = new bootstrap.Modal(document.getElementById('modalPinjam'));
        modal.show();
    }

    function renderSelectedBooksList() {
        const container = document.getElementById('selectedBooksList');
        container.innerHTML = selectedBooks.map((b, i) => `
            <div class="selected-book-chip" id="chip-${b.id}">
                ${b.sampul
                    ? `<img src="${b.sampul}" alt="${b.nama}">`
                    : `<div style="width:32px;height:40px;background:#e2e8f0;border-radius:4px;display:flex;align-items:center;justify-content:center;font-size:14px">📖</div>`
                }
                <div>
                    <div style="font-weight:600;font-size:13px">${b.nama}</div>
                    <div style="font-size:11px;color:var(--muted)">${b.penerbit}</div>
                </div>
                <button class="remove-chip" onclick="removeFromCart(${b.id})" title="Hapus">✕</button>
            </div>
        `).join('');
    }

    function removeFromCart(id) {
        const card = document.getElementById('card-' + id);
        if (card) card.classList.remove('selected');
        selectedBooks = selectedBooks.filter(b => b.id !== id);
        updateCartUI();
        renderSelectedBooksList();
        if (selectedBooks.length === 0) {
            bootstrap.Modal.getInstance(document.getElementById('modalPinjam')).hide();
        }
    }

    function filterJenis(jenis, btn) {
        document.querySelectorAll('.filter-chip').forEach(c => c.classList.remove('active'));
        btn.classList.add('active');

        const cards = document.querySelectorAll('.book-card');
        const search = document.getElementById('searchInput').value.toLowerCase();
        let visible = 0;

        cards.forEach(card => {
            const matchJenis = jenis === 'semua'
                ? true
                : jenis === 'tersedia'
                ? parseInt(card.dataset.stok) > 0
                : card.dataset.jenis === jenis;
            const matchSearch = card.dataset.nama.toLowerCase().includes(search)
                || card.dataset.penerbit.toLowerCase().includes(search);

            if (matchJenis && matchSearch) {
                card.style.display = '';
                visible++;
            } else {
                card.style.display = 'none';
            }
        });

        document.getElementById('bookCount').textContent = visible + ' buku';
    }

    document.getElementById('searchInput').addEventListener('input', function() {
        const search = this.value.toLowerCase();
        const activeChip = document.querySelector('.filter-chip.active');
        const activeJenis = activeChip ? activeChip.textContent.trim() : 'Semua';

        const cards = document.querySelectorAll('.book-card');
        let visible = 0;

        cards.forEach(card => {
            const matchSearch = card.dataset.nama.toLowerCase().includes(search)
                || card.dataset.penerbit.toLowerCase().includes(search);

            if (matchSearch) {
                card.style.display = '';
                visible++;
            } else {
                card.style.display = 'none';
            }
        });

        document.getElementById('bookCount').textContent = visible + ' buku';
    });

    // Validation function for referral token in modal
    function validateReferralTokenModal(input) {
        const token = input.value.trim().toUpperCase();
        input.value = token;
        
        const errorDiv = document.getElementById('referral_token_error');
        const alertDiv = document.getElementById('referral-alert-modal');
        
        if (token.length === 6) {
            // Validate token via AJAX
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
                        document.getElementById('referral-alert-text-modal').textContent = 'Token referral tidak valid atau tidak ditemukan.';
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
        const nama     = document.getElementById('f_nama').value.trim();
        const nim      = document.getElementById('f_nim').value.trim();
        const jurusan  = document.getElementById('f_jurusan').value.trim();
        const telepon  = document.getElementById('f_telepon').value.trim();
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
            nama, nim, jurusan,
            no_telepon: telepon,
            referral_token: referralToken,
            buku_ids: selectedBooks.map(b => b.id),
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
                // Tampilkan success state
                document.getElementById('formState').style.display = 'none';
                document.getElementById('successState').style.display = 'block';

                // Tampilkan booking IDs
                const chips = res.booking_ids.map(id =>
                    `<div class="booking-chip">${id}</div>`
                ).join('');
                document.getElementById('bookingChips').innerHTML = chips;

                // Reset selected books
                selectedBooks.forEach(b => {
                    const card = document.getElementById('card-' + b.id);
                    if (card) card.classList.remove('selected');
                });
                selectedBooks = [];
                updateCartUI();
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
        document.getElementById('formState').style.display = 'block';
        document.getElementById('successState').style.display = 'none';
        document.getElementById('f_nama').value = '';
        document.getElementById('f_nim').value = '';
        document.getElementById('f_jurusan').value = '';
        document.getElementById('f_telepon').value = '';
        document.getElementById('alertPinjam').innerHTML = '';
        document.getElementById('btnSubmit').disabled = false;
        document.getElementById('btnSubmit').innerHTML = '<i class="fas fa-paper-plane me-2"></i>Ajukan Peminjaman';
        bootstrap.Modal.getInstance(document.getElementById('modalPinjam')).hide();
    }

    function showToast(msg, type) {
        const toast = document.createElement('div');
        toast.style.cssText = `
            position:fixed;top:80px;right:20px;z-index:9999;
            background:${type === 'warning' ? '#fef3c7' : '#d1fae5'};
            color:${type === 'warning' ? '#92400e' : '#065f46'};
            border:1px solid ${type === 'warning' ? '#fde68a' : '#a7f3d0'};
            padding:12px 20px;border-radius:12px;font-size:14px;
            font-weight:600;box-shadow:0 4px 20px rgba(0,0,0,0.1);
            animation:slideIn 0.3s ease;
        `;
        toast.textContent = msg;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    }

    // Auto-fill NIM for modal
    let nimModalTimer;
    function fetchMahasiswaForModal(nim) {
        clearTimeout(nimModalTimer);
        const cleanedNim = nim.trim().replace(/\D/g, ''); // Remove non-digits

        if (cleanedNim.length < 5) { // Start fetching after 5 digits
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
        }, 400); // Debounce to prevent too many requests
    }

    function resetModalMahasiswaFields() {
        document.getElementById('f_nama').value = '';
        document.getElementById('f_jurusan').value = '';
        document.getElementById('f_telepon').value = '';
        document.getElementById('f_nama').readOnly = false;
        document.getElementById('f_jurusan').readOnly = false;
        document.getElementById('f_telepon').readOnly = false;
    }

    // EBOOK READER FUNCTIONS
    function openEbookReader(id) {
        // Redirect to dedicated ebook reader page
        window.open('/ebook/baca/' + id, '_blank');
    }

    function loadPdf(bookId) {
        // Wait for PDF.js to be ready
        if (typeof pdfjsLib === 'undefined') {
            setTimeout(() => loadPdf(bookId), 1);
            return;
        }

        const streamUrl = `/ebook/${bookId}`;

        pdfjsLib.getDocument({
            url: streamUrl,
            withCredentials:true,
            disableRange:true
        }).promise.then(pdfDoc_ => {
            pdfDoc = pdfDoc_;
            maxPages = pdfDoc.numPages;
            pageNum = 1;
            
            updateAccessStatus();
            renderPage(pageNum);
        }).catch(err => {
            console.error(err);
            document.getElementById('pdf-viewer').innerHTML = '<div class="text-white mt-5 text-center"><i class="fas fa-exclamation-triangle fa-2x text-warning"></i><p class="mt-2">Gagal memuat PDF. Pastikan file tersedia.</p></div>';
        });
    }

    function renderPage(num) {
        pageRendering = true;
        
        // Check restrictions
        if (!isFullAccess) {
            const limit = Math.ceil(maxPages / 4);
            if (num > limit) {
                showVerifyModal();
                pageRendering = false;
                return;
            }
        }

        pdfDoc.getPage(num).then(page => {
            const viewport = page.getViewport({ scale: scale });
            const canvas = document.createElement('canvas');
            canvas.className = 'pdf-page';
            const context = canvas.getContext('2d');
            canvas.height = viewport.height;
            canvas.width = viewport.width;

            const renderContext = {
                canvasContext: context,
                viewport: viewport
            };

            const viewer = document.getElementById('pdf-viewer');
            viewer.innerHTML = '';
            viewer.appendChild(canvas);

            page.render(renderContext).promise.then(() => {
                document.getElementById('pageCount').textContent = `Hal: ${num}/${maxPages}`;
                
                pageRendering = false;
                if (pageNumPending !== null) {
                    renderPage(pageNumPending);
                    pageNumPending = null;
                }
            });
        });
    }

    function queueRenderPage(num) {
        if (pageRendering) {
            pageNumPending = num;
        } else {
            renderPage(num);
        }
    }

    function prevPage() {
        if (pageNum <= 1) return;
        pageNum--;
        queueRenderPage(pageNum);
    }

    function nextPage() {
        if (pageNum >= maxPages) return;
        
        if (!isFullAccess) {
            const limit = Math.ceil(maxPages / 4);
            if (pageNum >= limit) {
                showVerifyModal();
                return;
            }
        }
        
        pageNum++;
        queueRenderPage(pageNum);
    }

    function showVerifyModal() {
        const verifyModal = new bootstrap.Modal(document.getElementById('modalVerifyReader'));
        verifyModal.show();
    }

    function verifyReaderNim() {
        const nim = document.getElementById('reader_nim').value.trim();
        if (!nim) return;

        fetch(`{{ route('publik.cek-nim') }}?nim=${nim}`)
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    isFullAccess = true;
                    bootstrap.Modal.getInstance(document.getElementById('modalVerifyReader')).hide();
                    updateAccessStatus();
                    showToast('Verifikasi berhasil! Akses full dibuka.', 'success');
                    renderPage(pageNum);
                } else {
                    document.getElementById('verifyError').style.display = 'block';
                }
            })
            .catch(() => {
                showToast('Terjadi kesalahan koneksi.', 'warning');
            });
    }

    function updateAccessStatus() {
        const status = document.getElementById('accessStatus');
        const btn = document.getElementById('btnVerifyReader');
        if (isFullAccess) {
            status.textContent = 'Akses: Full Version';
            status.className = 'text-success small fw-bold';
            btn.style.display = 'none';
        } else {
            const limit = Math.ceil(maxPages / 4);
            status.textContent = `Akses: Preview (${limit} Halaman)`;
            status.className = 'text-muted small';
            btn.style.display = 'block';
        }
    }

    function closeEbook() {
        pdfDoc = null;
        document.getElementById('pdf-viewer').innerHTML = '';
    }
</script>

<style>
@keyframes slideIn {
    from { opacity: 0; transform: translateX(20px); }
    to { opacity: 1; transform: translateX(0); }
}
.shake {
    animation: shake 0.4s ease;
}
@keyframes shake {
    0%, 100% { transform: translateX(0); }
    20%, 60% { transform: translateX(-6px); }
    40%, 80% { transform: translateX(6px); }
}

/* CHAT WIDGET STYLES */
.chat-widget-container { position: fixed; bottom: 20px; right: 20px; z-index: 1000; }
.chat-toggle {
    width: 60px; height: 60px; border-radius: 50%; background: var(--navy);
    border: none; color: white; cursor: pointer; display: flex; align-items: center;
    justify-content: center; box-shadow: 0 4px 12px rgba(15,36,68,0.4); transition: all 0.3s ease;
}
.chat-toggle:hover { transform: scale(1.05); box-shadow: 0 6px 20px rgba(15,36,68,0.5); }
.chat-toggle svg { width: 28px; height: 28px; }
.chat-badge {
    position: absolute; top: 0; right: 0; width: 20px; height: 20px; background: #ef4444;
    border-radius: 50%; font-size: 11px; display: flex; align-items: center;
    justify-content: center; color: white; font-weight: 600;
}
.chat-box {
    position: absolute; bottom: 75px; right: 0; width: 350px; height: 450px;
    background: white; border-radius: 16px; box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
    display: none; flex-direction: column; overflow: hidden; border: 1px solid var(--border);
}
.chat-box.open { display: flex; }
.chat-header {
    padding: 1rem; background: linear-gradient(135deg, var(--navy), var(--navy-mid));
    color: white; display: flex; align-items: center; justify-content: space-between;
}
.chat-title { font-weight: 600; font-size: 0.95rem; }
.chat-close {
    background: none; border: none; color: white; cursor: pointer; width: 28px; height: 28px;
    display: flex; align-items: center; justify-content: center; border-radius: 50%; transition: background 0.2s;
}
.chat-close:hover { background: rgba(255, 255, 255, 0.2); }
.chat-close svg { width: 18px; height: 18px; }
.chat-messages { flex: 1; padding: 1rem; overflow-y: auto; display: flex; flex-direction: column; gap: 0.75rem; background: #f8fafc; }
.chat-message { max-width: 80%; display: flex; flex-direction: column; gap: 0.25rem; }
.chat-message.user { align-self: flex-end; }
.chat-message.bot, .chat-message.admin { align-self: flex-start; }
.chat-message-text { padding: 0.75rem 1rem; border-radius: 12px; font-size: 0.875rem; line-height: 1.5; word-wrap: break-word; }
.chat-message.user .chat-message-text { background: var(--navy); color: white; border-bottom-right-radius: 4px; }
.chat-message.bot .chat-message-text, .chat-message.admin .chat-message-text { background: white; color: var(--text); border: 1px solid var(--border); border-bottom-left-radius: 4px; }
.chat-message-time { font-size: 0.7rem; color: var(--muted); margin-top: 0.25rem; }
.chat-message.user .chat-message-time { text-align: right; }
.chat-status { padding: 0.5rem 1rem; background: #ecfdf5; border-top: 1px solid var(--border); display: none; }
.status-badge { font-size: 0.75rem; color: #059669; font-weight: 500; }
.chat-form { padding: 1rem; border-top: 1px solid var(--border); display: flex; gap: 0.5rem; background: white; }
.chat-form input[type="text"] {
    flex: 1; padding: 0.625rem 1rem; border: 1px solid var(--border); border-radius: 12px;
    font-size: 0.875rem; font-family: 'DM Sans', sans-serif; outline: none; transition: border-color 0.2s;
}
.chat-form input[type="text"]:focus { border-color: var(--navy); }
.chat-form button {
    width: 40px; height: 40px; border: none; background: var(--navy); color: white;
    border-radius: 12px; cursor: pointer; display: flex; align-items: center;
    justify-content: center; transition: background 0.2s;
}
.chat-form button:hover { background: var(--navy-mid); }
.chat-form button svg { width: 18px; height: 18px; }
.chat-typing { display: flex; gap: 0.25rem; padding: 0.75rem 1rem; }
.chat-typing span { width: 8px; height: 8px; background: var(--muted); border-radius: 50%; animation: typing 1.4s infinite ease-in-out both; }
.chat-typing span:nth-child(1) { animation-delay: -0.32s; }
.chat-typing span:nth-child(2) { animation-delay: -0.16s; }
@keyframes typing { 0%, 80%, 100% { transform: scale(0); } 40% { transform: scale(1); } }
@media (max-width: 480px) { .chat-box { width: calc(100vw - 40px); height: 60vh; } }
</style>

{{-- CHAT WIDGET --}}
<div class="chat-widget-container">
    <button class="chat-toggle" id="chatToggle" aria-label="Buka chat">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
        </svg>
        <span class="chat-badge" id="chatBadge" style="display: none;"></span>
    </button>
    <div class="chat-box" id="chatBox">
        <div class="chat-header">
            <span class="chat-title">&#x1F4AC; SIPUSAKA Assistant</span>
            <button class="chat-close" id="chatClose" aria-label="Tutup chat">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
        <div class="chat-messages" id="chatMessages">
            <!-- Messages will be added here -->
        </div>
        <div class="chat-status" id="chatStatus">
            <span class="status-badge">&#x1F7E2; Terhubung dengan Admin</span>
        </div>
        <form class="chat-form" id="chatForm">
            @csrf
            <input type="hidden" id="chatSessionId" name="session_id" value="">
            <input type="hidden" id="lastMessageId" value="0">
            <input type="text" id="chatInput" name="message" placeholder="Ketik pesan Anda..." autocomplete="off" required>
            <button type="submit" aria-label="Kirim">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="22" y1="2" x2="11" y2="13"></line>
                    <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                </svg>
            </button>
        </form>
    </div>
</div>

<script>
(function() {
    'use strict';

    // Elements
    var chatToggle = document.getElementById('chatToggle');
    var chatClose = document.getElementById('chatClose');
    var chatBox = document.getElementById('chatBox');
    var chatForm = document.getElementById('chatForm');
    var chatInput = document.getElementById('chatInput');
    var chatMessages = document.getElementById('chatMessages');
    var chatSessionId = document.getElementById('chatSessionId');
    var lastMessageId = document.getElementById('lastMessageId');
    var chatStatus = document.getElementById('chatStatus');
    var chatBadge = document.getElementById('chatBadge');

    // State
    var isChatOpen = false;
    var isConnectedToAdmin = false;
    var pollInterval = null;
    var isVerified = false;
    var awaitingNimInput = false;

    // CSRF Token
    var csrfToken = document.querySelector('input[name="_token"]') ? document.querySelector('input[name="_token"]').value : '';
    
    // Routes
    var verifyNimUrl = '{{ route("chat.verify-nim") }}';
    var sendMessageUrl = '{{ route("chat.send") }}';
    var getMessagesUrl = '{{ route("chat.messages") }}';

    // Toggle chat open/close
    function toggleChat() {
        openChat();
    }

    function openChat() {
        isChatOpen = true;
        chatBox.classList.add('open');
        
        // Show welcome message if first time opening
        if (chatMessages.children.length === 0) {
            addMessage('bot', 'Halo! Selamat datang di SIPUSAKA Assistant. 👋');
            setTimeout(function() {
                addMessage('bot', 'Untuk memulai, silakan masukkan NIM Anda.');
                awaitingNimInput = true;
            }, 500);
        }
        
        chatInput.focus();
    }

    function closeChat() {
        isChatOpen = false;
        chatBox.classList.remove('open');
        stopPolling();
    }

    // Verify NIM
    function verifyNim(nim) {
        showTyping();
        
        fetch('{{ route("chat.verify-nim") }}', {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ nim: nim })
        })
        .then(function(response) { return response.json(); })
        .then(function(data) {
            hideTyping();
            
            if (data.success) {
                isVerified = true;
                awaitingNimInput = false;
                addMessage('bot', 'Terima kasih! Anda terdaftar sebagai ' + data.mahasiswa.nama + ' ✓');
                setTimeout(function() {
                    addMessage('bot', 'Ada yang bisa saya bantu hari ini?');
                    startPolling();
                }, 800);
            } else {
                addMessage('bot', '❌ ' + (data.message || 'NIM tidak terdaftar. Silakan coba lagi atau hubungi admin.'));
                awaitingNimInput = true;
            }
        })
        .catch(function(err) {
            hideTyping();
            addMessage('bot', '❌ Terjadi kesalahan. Silakan coba lagi.');
            awaitingNimInput = true;
        });
    }

    // Add message to chat
    function addMessage(sender, text) {
        var messageDiv = document.createElement('div');
        messageDiv.className = 'chat-message ' + sender;
        var time = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
        var escapedText = escapeHtml(text);
        messageDiv.innerHTML = '<div class="chat-message-text">' + escapedText + '</div><div class="chat-message-time">' + time + '</div>';
        chatMessages.appendChild(messageDiv);
        scrollToBottom();
    }

    function scrollToBottom() {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    function escapeHtml(text) {
        var div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Show/hide typing indicator
    function showTyping() {
        var typingDiv = document.createElement('div');
        typingDiv.className = 'chat-message bot chat-typing';
        typingDiv.id = 'typingIndicator';
        typingDiv.innerHTML = '<span></span><span></span><span></span>';
        chatMessages.appendChild(typingDiv);
        scrollToBottom();
    }

    function hideTyping() {
        var typing = document.getElementById('typingIndicator');
        if (typing) typing.remove();
    }

    // Send message
    function sendMessage(message) {
        addMessage('user', message);
        chatInput.value = '';
        showTyping();

        fetch('{{ route("chat.send") }}', {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                session_id: chatSessionId.value || null,
                message: message
            })
        })
        .then(function(response) { return response.json(); })
        .then(function(data) {
            hideTyping();

            if (data.session_id) {
                chatSessionId.value = data.session_id;
            }

            if (data.is_connected_to_admin && !isConnectedToAdmin) {
                isConnectedToAdmin = true;
                chatStatus.style.display = 'block';
                addMessage('bot', '\u{1F7E2} Anda sekarang terhubung dengan Admin.');
            }

            if (data.message) {
                addMessage('bot', data.message);
            }
        })
        .catch(function(err) {
            hideTyping();
            console.error('Error:', err);
        });
    }

    // Polling for new messages
    function pollMessages() {
        if (!chatSessionId.value) return;

        fetch('{{ route("chat.messages") }}?session_id=' + encodeURIComponent(chatSessionId.value) + '&last_message_id=' + encodeURIComponent(lastMessageId.value), {
            credentials: 'same-origin'
        })
        .then(function(response) { return response.json(); })
        .then(function(data) {
            if (data.messages && data.messages.length > 0) {
                data.messages.forEach(function(msg) {
                    if (msg.sender_type === 'admin') {
                        addMessage('admin', msg.message);
                    } else if (msg.sender_type === 'bot') {
                        addMessage('bot', msg.message);
                    }
                    if (msg.id > lastMessageId.value) {
                        lastMessageId.value = msg.id;
                    }
                });
            }

            if (data.is_connected_to_admin && !isConnectedToAdmin) {
                isConnectedToAdmin = true;
                chatStatus.style.display = 'block';
                addMessage('bot', '\u{1F7E2} Anda sekarang terhubung dengan Admin.');
            }
        })
        .catch(function(err) {
            console.error("Error polling:", err);
        });
    }

    function startPolling() {
        pollMessages();
        pollInterval = setInterval(pollMessages, 3000);
    }

    function stopPolling() {
        if (pollInterval) {
            clearInterval(pollInterval);
            pollInterval = null;
        }
    }

    // Event Listeners
    chatToggle.addEventListener('click', toggleChat);
    chatClose.addEventListener('click', closeChat);

    chatForm.addEventListener('submit', function(e) {
        e.preventDefault();
        var message = chatInput.value.trim();
        if (!message) return;
        
        // If awaiting NIM input, verify NIM instead of sending message
        if (awaitingNimInput && !isVerified) {
            addMessage('user', message);
            chatInput.value = '';
            verifyNim(message);
        } else if (isVerified) {
            sendMessage(message);
        } else {
            addMessage('user', message);
            chatInput.value = '';
            addMessage('bot', 'Silakan masukkan NIM Anda terlebih dahulu.');
        }
    });
})();
</script>

</body>
</html>

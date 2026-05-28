<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Mahasiswa | Sipusaka</title>

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,600,700&display=fallback">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <style>
    body {
        background: #f8fafc;
        color: #0f172a;
    }

    .main-sidebar {
        background: linear-gradient(180deg, #0f172a 0%, #172554 100%);
    }

    .brand-link {
        border-bottom: 1px solid rgba(255, 255, 255, .12) !important;
        background: rgba(255, 255, 255, .02);
    }

    .content-wrapper {
        background: linear-gradient(180deg, #f8fbff 0%, #f1f5f9 100%);
    }

    .main-header.navbar {
        position: fixed;
        top: 0;
        right: 0;
        left: 0;
        z-index: 1030;
        background: rgba(255, 255, 255, .96);
        backdrop-filter: blur(10px);
    }

    .content-wrapper {
        margin-top: 57px;
    }

    .hero-card {
        border: 1px solid #dbeafe;
        border-radius: 24px;
        color: #fff;
        background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 58%, #2563eb 100%);
        box-shadow: 0 20px 45px rgba(15, 23, 42, .14);
        overflow: hidden;
        position: relative;
    }

    .hero-card::after {
        content: "";
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at top right, rgba(255, 255, 255, .14), transparent 35%);
        pointer-events: none;
    }

    .hero-avatar {
        width: 82px;
        height: 82px;
        border-radius: 24px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, .12);
        border: 1px solid rgba(255, 255, 255, .24);
        backdrop-filter: blur(8px);
        font-size: 36px;
        box-shadow: 0 10px 28px rgba(15, 23, 42, .2);
    }

    .stat-card {
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 14px 34px rgba(15, 23, 42, .06);
        transition: .25s ease-in-out;
        background: #fff;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 18px 38px rgba(15, 23, 42, .1);
    }

    .profile-card,
    .history-card {
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        box-shadow: 0 14px 34px rgba(15, 23, 42, .06);
        overflow: hidden;
        background: #fff;
    }

    .profile-header {
        background: linear-gradient(135deg, #eff6ff, #dbeafe);
        color: #0f172a;
        padding: 28px 24px;
        text-align: center;
        border-bottom: 1px solid #dbeafe;
    }

    .table thead th {
        border-top: 0;
        background: #f8fafc;
        color: #334155;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: .06em;
        white-space: nowrap;
    }

    .table td {
        vertical-align: middle;
    }

    .badge-status {
        padding: 8px 12px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 600;
    }

    .modal-success-icon {
        width: 88px;
        height: 88px;
        border-radius: 24px;
        background: linear-gradient(135deg, #1d4ed8, #1e3a8a);
        color: #fff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 42px;
        box-shadow: 0 16px 34px rgba(30, 64, 175, .28);
    }

    .quick-link {
        border-radius: 14px;
        padding: 14px 16px;
        border: 1px solid #e2e8f0;
        color: #334155;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: .2s;
        background: #fff;
    }

    .quick-link:hover {
        color: #1d4ed8;
        background: #eff6ff;
        text-decoration: none;
        border-color: #bfdbfe;
        transform: translateX(2px);
    }

    .loan-item {
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        padding: 18px;
        background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
        box-shadow: 0 10px 24px rgba(15, 23, 42, .04);
    }

    .loan-item+.loan-item {
        margin-top: 16px;
    }

    .loan-meta {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
    }

    .loan-meta-card {
        border-radius: 14px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        padding: 12px 14px;
    }

    .loan-meta-label {
        display: block;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: .08em;
        color: #64748b;
        margin-bottom: 4px;
        font-weight: 700;
    }

    .loan-meta-value {
        color: #0f172a;
        font-weight: 600;
        line-height: 1.4;
    }

    .loan-status-badge {
        padding: 8px 14px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .loan-fine-box {
        border-radius: 16px;
        border: 1px solid #fecaca;
        background: #fff1f2;
        padding: 14px 16px;
        color: #991b1b;
    }

    @media (max-width: 767.98px) {
        .loan-meta {
            grid-template-columns: 1fr;
        }
    }

    .text-white-50 {
        color: rgba(255, 255, 255, .72) !important;
    }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <nav class="main-header navbar navbar-expand navbar-white navbar-light border-bottom-0 shadow-sm">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                        <i class="fas fa-bars"></i>
                    </a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="{{ route('mahasiswa.dashboard') }}" class="nav-link font-weight-semibold">Dashboard</a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="{{ route('publik.katalog') }}" class="nav-link">Katalog Buku</a>
                </li>
            </ul>

            <ul class="navbar-nav ml-auto align-items-center">
                <li class="nav-item mr-2 d-none d-md-block">
                    <span class="badge px-3 py-2" style="background:#dbeafe;color:#1e3a8a;border:1px solid #bfdbfe;">
                        <i class="fas fa-check-circle mr-1"></i> {{ ucfirst($mahasiswa->status) }}
                    </span>
                </li>
                <li class="nav-item">
                    <form action="{{ route('mahasiswa.logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm text-white"
                            style="background:linear-gradient(135deg,#1d4ed8,#1e3a8a);border:none;border-radius:10px;">
                            <i class="fas fa-sign-out-alt mr-1"></i> Logout
                        </button>
                    </form>
                </li>
            </ul>
        </nav>

        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <a href="{{ route('mahasiswa.dashboard') }}" class="brand-link text-center">
                <span class="brand-text font-weight-bold">
                    <i class="fas fa-book-reader mr-2"></i>Sipusaka
                </span>
            </a>

            <div class="sidebar">
                <div class="user-panel mt-3 pb-3 mb-3 d-flex align-items-center">
                    <div class="image">
                        <span class="img-circle elevation-2 d-inline-flex align-items-center justify-content-center"
                            style="width:34px;height:34px;background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.22);color:#fff;">
                            <i class="fas fa-user-graduate"></i>
                        </span>
                    </div>
                    <div class="info">
                        <a href="#" class="d-block font-weight-semibold">{{ $mahasiswa->nama }}</a>
                        <small class="text-white-50">{{ $mahasiswa->nim }}</small>
                    </div>
                </div>

                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                        <li class="nav-header text-white-50">MENU MAHASISWA</li>
                        <li class="nav-item">
                            <a href="{{ route('mahasiswa.dashboard') }}" class="nav-link active">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('publik.katalog') }}" class="nav-link">
                                <i class="nav-icon fas fa-book"></i>
                                <p>Katalog Buku</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('publik.history.form') }}" class="nav-link">
                                <i class="nav-icon fas fa-history"></i>
                                <p>Cek History Publik</p>
                            </a>
                        </li>
                        <li class="nav-header text-white-50">AKUN</li>
                        <li class="nav-item">
                            <form action="{{ route('mahasiswa.logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="nav-link btn btn-link text-left text-white w-100">
                                    <i class="nav-icon fas fa-sign-out-alt"></i>
                                    <p>Logout</p>
                                </button>
                            </form>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>

        <div class="content-wrapper">
            <div class="content-header pb-2">
                <div class="container-fluid">
                    <div class="row mb-2 align-items-center">
                        <div class="col-sm-6">
                            <h1 class="m-0 font-weight-bold">Dashboard Mahasiswa</h1>
                            <small class="text-muted">Ringkasan data diri dan aktivitas peminjaman Anda.</small>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('publik.katalog') }}">Home</a></li>
                                <li class="breadcrumb-item active">Dashboard</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    @if ($punyaBlokirPeminjaman)
                    <div class="alert alert-danger alert-dismissible fade show shadow-sm"
                        style="border-radius:18px;border:1px solid #fecaca;background:#fff1f2;color:#7f1d1d;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <div class="d-flex align-items-start">
                            <div class="mr-3">
                                <span class="d-inline-flex align-items-center justify-content-center"
                                    style="width:46px;height:46px;border-radius:14px;background:#dc2626;color:#fff;">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </span>
                            </div>
                            <div>
                                <h5 class="font-weight-bold mb-1">Peminjaman Dikunci Sementara</h5>
                                <p class="mb-2">
                                    Anda memiliki
                                    @if ($pinjamanTerlambatAktif->isNotEmpty())
                                    <strong>{{ $pinjamanTerlambatAktif->count() }} proses pengembalian
                                        terlambat</strong>
                                    @endif
                                    @if ($pinjamanTerlambatAktif->isNotEmpty() && $dendaBelumBayar->isNotEmpty())
                                    dan
                                    @endif
                                    @if ($dendaBelumBayar->isNotEmpty())
                                    <strong>denda belum dibayar sebesar Rp
                                        {{ number_format($totalDendaBelumBayar, 0, ',', '.') }}</strong>
                                    @endif
                                    .
                                </p>
                                <small>
                                    Selesaikan pengembalian buku yang terlambat dan lunasi denda di perpustakaan agar
                                    dapat meminjam buku kembali.
                                    @if ($hariTerlambatAktif > 0)
                                    Total keterlambatan aktif: <strong>{{ $hariTerlambatAktif }} hari</strong>.
                                    @endif
                                </small>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="card hero-card mb-4">
                        <div class="card-body p-4">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <div class="d-flex align-items-center">
                                        <div class="hero-avatar mr-3">
                                            <i class="fas fa-user-graduate"></i>
                                        </div>
                                        <div>
                                            <h3 class="font-weight-bold mb-1">Selamat Datang, {{ $mahasiswa->nama }}
                                            </h3>
                                            <p class="mb-0">
                                                NIM {{ $mahasiswa->nim }} &bull;
                                                {{ $mahasiswa->jurusan ?? 'Jurusan belum tersedia' }}
                                            </p>
                                            <small>Gunakan panel ini untuk memantau status dan riwayat peminjaman
                                                buku.</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 text-md-right mt-3 mt-md-0">
                                    <span class="badge px-3 py-2 mb-2"
                                        style="background:rgba(255,255,255,.14);color:#fff;border:1px solid rgba(255,255,255,.22);">
                                        <i class="fas fa-star mr-1"></i> Kredit Skor {{ $kreditSkor }}/100
                                    </span>
                                    <br>
                                    <a href="{{ route('publik.katalog') }}" class="btn btn-light btn-sm"
                                        style="border-radius:12px;color:#1e3a8a;font-weight:600;">
                                        <i class="fas fa-search mr-1"></i> Cari Buku
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Charts Row -->
                    <div class="row mb-4">
                        <!-- Donut Chart: Peminjaman per Judul -->
                        <div class="col-md-4">
                            <div class="card profile-card">
                                <div class="card-header bg-white py-2">
                                    <h5 class="card-title font-weight-bold mb-0">
                                        <i class="fas fa-chart-pie mr-1 text-primary"></i> Total Peminjaman by Judul
                                        Buku
                                    </h5>
                                </div>
                                <div class="card-body py-3">
                                    <div style="position: relative; height: 240px;">
                                        <canvas id="peminjamanChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Bar Chart: History Peminjaman & Pengembalian -->
                        <div class="col-md-4">
                            <div class="card profile-card">
                                <div class="card-header bg-white py-2">
                                    <h5 class="card-title font-weight-bold mb-0">
                                        <i class="fas fa-chart-bar mr-1 text-primary"></i> History Peminjaman &
                                        Pengembalian
                                    </h5>
                                </div>
                                <div class="card-body py-3">
                                    <div style="position: relative; height: 240px;">
                                        <canvas id="historyChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Line Chart: Login 7 Hari -->
                        <div class="col-md-4">
                            <div class="card profile-card">
                                <div class="card-header bg-white py-2">
                                    <h5 class="card-title font-weight-bold mb-0">
                                        <i class="fas fa-chart-line mr-1 text-primary"></i> Session Login (7 Hari
                                        Terakhir)
                                    </h5>
                                </div>
                                <div class="card-body py-3">
                                    <div style="position: relative; height: 240px;">
                                        <canvas id="loginChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-6">
                            <div class="card stat-card">
                                <div class="card-body">
                                    <span class="text-muted">Total Peminjaman</span>
                                    <h3 class="font-weight-bold mb-0">{{ $totalPeminjaman }}</h3>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="card stat-card">
                                <div class="card-body">
                                    <span class="text-muted">Sedang Dipinjam</span>
                                    <h3 class="font-weight-bold mb-0">{{ $sedangDipinjam }}</h3>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="card stat-card">
                                <div class="card-body">
                                    <span class="text-muted">Dikembalikan</span>
                                    <h3 class="font-weight-bold mb-0">{{ $sudahDikembalikan }}</h3>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="card stat-card">
                                <div class="card-body">
                                    <span class="text-muted">Terlambat</span>
                                    <h3 class="font-weight-bold mb-0">{{ $terlambatCount }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-4">
                            <div class="card profile-card">
                                <div class="profile-header">
                                    <h4 class="font-weight-bold mb-0">{{ $mahasiswa->nama }}</h4>
                                    <small>{{ $mahasiswa->nim }}</small>
                                </div>

                                <div class="card-body">
                                    @if($mahasiswa->pending_updates)
                                    <div class="alert alert-warning py-2 px-3 mb-3 shadow-sm"
                                        style="border-radius:12px; font-size: 0.85rem;">
                                        <i class="fas fa-clock mr-1"></i> Menunggu persetujuan admin untuk perubahan
                                        data.
                                    </div>
                                    @endif

                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <strong><i class="fas fa-user mr-1 text-primary"></i> Nama</strong>
                                        <button class="btn btn-link btn-xs p-0 text-primary btn-edit-field"
                                            data-field="nama" data-value="{{ $mahasiswa->nama }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </div>
                                    <p class="text-muted mb-2 field-value" data-field="nama">{{ $mahasiswa->nama }}</p>
                                    <div class="field-edit d-none" data-field="nama">
                                        <div class="input-group input-group-sm mb-2">
                                            <input type="text" class="form-control" value="{{ $mahasiswa->nama }}">
                                            <div class="input-group-append">
                                                <button class="btn btn-success btn-save-field" type="button"><i
                                                        class="fas fa-check"></i></button>
                                                <button class="btn btn-danger btn-cancel-field" type="button"><i
                                                        class="fas fa-times"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>

                                    <strong><i class="fas fa-id-card mr-1 text-primary"></i> NIM</strong>
                                    <p class="text-muted mb-2">{{ $mahasiswa->nim }}</p>
                                    <hr>

                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <strong><i class="fas fa-envelope mr-1 text-primary"></i> Email</strong>
                                        <button class="btn btn-link btn-xs p-0 text-primary btn-edit-field"
                                            data-field="email" data-value="{{ $mahasiswa->email }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </div>
                                    <p class="text-muted mb-2 field-value" data-field="email">
                                        {{ $mahasiswa->email ?? '-' }}</p>
                                    <div class="field-edit d-none" data-field="email">
                                        <div class="input-group input-group-sm mb-2">
                                            <input type="email" class="form-control" value="{{ $mahasiswa->email }}">
                                            <div class="input-group-append">
                                                <button class="btn btn-success btn-save-field" type="button"><i
                                                        class="fas fa-check"></i></button>
                                                <button class="btn btn-danger btn-cancel-field" type="button"><i
                                                        class="fas fa-times"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>

                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <strong><i class="fas fa-graduation-cap mr-1 text-primary"></i> Jurusan</strong>
                                        <button class="btn btn-link btn-xs p-0 text-primary btn-edit-field"
                                            data-field="jurusan" data-value="{{ $mahasiswa->jurusan }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </div>
                                    <p class="text-muted mb-2 field-value" data-field="jurusan">
                                        {{ $mahasiswa->jurusan ?? '-' }}</p>
                                    <div class="field-edit d-none" data-field="jurusan">
                                        <div class="input-group input-group-sm mb-2">
                                            <input type="text" class="form-control" value="{{ $mahasiswa->jurusan }}">
                                            <div class="input-group-append">
                                                <button class="btn btn-success btn-save-field" type="button"><i
                                                        class="fas fa-check"></i></button>
                                                <button class="btn btn-danger btn-cancel-field" type="button"><i
                                                        class="fas fa-times"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>

                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <strong><i class="fas fa-phone mr-1 text-primary"></i> No. Telepon</strong>
                                        <button class="btn btn-link btn-xs p-0 text-primary btn-edit-field"
                                            data-field="no_telepon" data-value="{{ $mahasiswa->no_telepon }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </div>
                                    <p class="text-muted mb-2 field-value" data-field="no_telepon">
                                        {{ $mahasiswa->no_telepon ?? '-' }}</p>
                                    <div class="field-edit d-none" data-field="no_telepon">
                                        <div class="input-group input-group-sm mb-2">
                                            <input type="text" class="form-control"
                                                value="{{ $mahasiswa->no_telepon }}">
                                            <div class="input-group-append">
                                                <button class="btn btn-success btn-save-field" type="button"><i
                                                        class="fas fa-check"></i></button>
                                                <button class="btn btn-danger btn-cancel-field" type="button"><i
                                                        class="fas fa-times"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>

                                    <strong><i class="fas fa-shield-alt mr-1 text-primary"></i> Status Akun</strong>
                                    <p class="mb-0">
                                        <span class="badge badge-status"
                                            style="background:#dbeafe;color:#1e3a8a;border:1px solid #bfdbfe;">
                                            <i class="fas fa-check-circle mr-1"></i>{{ ucfirst($mahasiswa->status) }}
                                        </span>
                                    </p>
                                </div>
                            </div>

                            <div class="card profile-card">
                                <div class="card-header bg-white">
                                    <h3 class="card-title font-weight-bold">
                                        <i class="fas fa-link mr-1 text-primary"></i> Akses Cepat
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <a href="{{ route('publik.katalog') }}" class="quick-link mb-2">
                                        <span><i class="fas fa-book mr-2"></i>Katalog Buku</span>
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                    @if ($punyaBlokirPeminjaman)
                                    <div class="quick-link mb-2"
                                        style="background:#fef2f2;color:#991b1b;border-color:#fecaca;cursor:not-allowed;">
                                        <span><i class="fas fa-lock mr-2"></i>Form Peminjaman Dikunci</span>
                                        <i class="fas fa-ban"></i>
                                    </div>
                                    @else
                                    <a href="{{ route('publik.pinjam.form') }}" class="quick-link mb-2">
                                        <span><i class="fas fa-hand-holding mr-2"></i>Form Peminjaman</span>
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                    @endif
                                    <a href="{{ route('publik.history.form') }}" class="quick-link">
                                        <span><i class="fas fa-history mr-2"></i>History Publik</span>
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-8">
                            <div class="card history-card">
                                <div class="card-header bg-white border-0 pt-4 px-4">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h3 class="card-title font-weight-bold mb-0">
                                                <i class="fas fa-history mr-1 text-primary"></i>
                                                Aktivitas Peminjaman
                                            </h3>
                                            <br>
                                            <small class="text-muted">Daftar transaksi peminjaman buku terbaru.</small>
                                        </div>
                                        <span class="badge px-3 py-2"
                                            style="background:#dbeafe;color:#1e3a8a;border:1px solid #bfdbfe;">{{ $totalPeminjaman }}
                                            Data</span>
                                    </div>
                                </div>

                                <div class="card-body p-4">
                                    @forelse ($peminjaman as $item)
                                    @php
                                    $tanggalPinjam = $item->tanggal_pinjam ?
                                    \Carbon\Carbon::parse($item->tanggal_pinjam) : null;
                                    $tanggalRencana = $item->tanggal_kembali_rencana ?
                                    \Carbon\Carbon::parse($item->tanggal_kembali_rencana) : null;
                                    $tanggalAktual = $item->tanggal_kembali_aktual ?
                                    \Carbon\Carbon::parse($item->tanggal_kembali_aktual) : null;

                                    $hariTerlambat = 0;
                                    if ($tanggalRencana) {
                                    $tanggalPerbandingan = $tanggalAktual ?? now();
                                    $hariTerlambat = max(0,
                                    $tanggalRencana->copy()->startOfDay()->diffInDays(\Carbon\Carbon::parse($tanggalPerbandingan)->startOfDay(),
                                    false));
                                    }

                                    $isTerlambat = $item->status === 'terlambat' || (in_array($item->status,
                                    ['dipinjam', 'dikembalikan']) && $hariTerlambat > 0);
                                    $nominalDenda = $item->denda->total_denda ?? ($hariTerlambat > 0 ? $hariTerlambat *
                                    10000 : 0);

                                    if ($isTerlambat) {
                                    $statusLabel = 'Pengembalian Terlambat';
                                    $statusStyle = 'background:#fff1f2;color:#991b1b;border:1px solid #fecaca;';
                                    $statusIcon = 'fas fa-exclamation-circle';
                                    } elseif ($item->status === 'dikembalikan') {
                                    $statusLabel = 'Sudah Dikembalikan';
                                    $statusStyle = 'background:#ecfdf5;color:#166534;border:1px solid #bbf7d0;';
                                    $statusIcon = 'fas fa-check-circle';
                                    } elseif ($item->status === 'dipinjam') {
                                    $statusLabel = 'Sedang Dipinjam';
                                    $statusStyle = 'background:#eff6ff;color:#1d4ed8;border:1px solid #bfdbfe;';
                                    $statusIcon = 'fas fa-book-reader';
                                    } elseif ($item->status === 'pending') {
                                    $statusLabel = 'Menunggu Persetujuan';
                                    $statusStyle = 'background:#fffbeb;color:#92400e;border:1px solid #fde68a;';
                                    $statusIcon = 'fas fa-clock';
                                    } elseif ($item->status === 'ditolak') {
                                    $statusLabel = 'Pengajuan Ditolak';
                                    $statusStyle = 'background:#f8fafc;color:#475569;border:1px solid #cbd5e1;';
                                    $statusIcon = 'fas fa-times-circle';
                                    } else {
                                    $statusLabel = ucfirst($item->status ?? '-');
                                    $statusStyle = 'background:#eff6ff;color:#1d4ed8;border:1px solid #bfdbfe;';
                                    $statusIcon = 'fas fa-info-circle';
                                    }
                                    @endphp

                                    <div class="loan-item">
                                        <div
                                            class="d-flex flex-column flex-md-row align-items-md-start justify-content-between mb-3">
                                            <div class="pr-md-3">
                                                <div class="d-flex align-items-center mb-2">
                                                    <span class="badge px-3 py-2 mr-2"
                                                        style="background:#dbeafe;color:#1e3a8a;border:1px solid #bfdbfe;">
                                                        Booking ID: {{ $item->booking_id ?? '-' }}
                                                    </span>
                                                    <span class="loan-status-badge" style="{{ $statusStyle }}">
                                                        <i class="{{ $statusIcon }}"></i> {{ $statusLabel }}
                                                    </span>
                                                </div>
                                                <h5 class="font-weight-bold mb-1">
                                                    {{ $item->buku->judul ?? $item->buku->nama_buku ?? '-' }}</h5>
                                                <p class="text-muted mb-0">
                                                    {{ $item->buku->penulis ?? $item->buku->pengarang ?? 'Penulis tidak tersedia' }}
                                                    @if (!empty($item->buku->penerbit))
                                                    &bull; {{ $item->buku->penerbit }}
                                                    @endif
                                                </p>
                                            </div>
                                        </div>

                                        <div class="loan-meta mb-3">
                                            <div class="loan-meta-card">
                                                <span class="loan-meta-label">Tanggal Pinjam</span>
                                                <div class="loan-meta-value">
                                                    {{ $tanggalPinjam ? $tanggalPinjam->format('d/m/Y') : '-' }}</div>
                                            </div>
                                            <div class="loan-meta-card">
                                                <span class="loan-meta-label">Rencana Kembali</span>
                                                <div class="loan-meta-value">
                                                    {{ $tanggalRencana ? $tanggalRencana->format('d/m/Y') : '-' }}</div>
                                            </div>
                                            <div class="loan-meta-card">
                                                <span class="loan-meta-label">Tanggal Dikembalikan</span>
                                                <div class="loan-meta-value">
                                                    {{ $tanggalAktual ? $tanggalAktual->format('d/m/Y') : 'Belum dikembalikan' }}
                                                </div>
                                            </div>
                                            <div class="loan-meta-card">
                                                <span class="loan-meta-label">Status Denda</span>
                                                <div class="loan-meta-value">
                                                    @if (($item->denda->status_bayar ?? null) === 'lunas')
                                                    Lunas
                                                    @elseif ($nominalDenda > 0)
                                                    Belum dibayar
                                                    @else
                                                    Tidak ada denda
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        @if ($isTerlambat)
                                        <div class="loan-fine-box">
                                            <div
                                                class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                                                <div class="mb-2 mb-md-0">
                                                    <div class="font-weight-bold">
                                                        <i class="fas fa-money-bill-wave mr-1"></i> Denda Keterlambatan
                                                    </div>
                                                    <small>
                                                        Terlambat {{ $hariTerlambat }} hari
                                                        @if (($item->denda->status_bayar ?? null) === 'lunas')
                                                        dan sudah dilunasi
                                                        @endif
                                                        .
                                                    </small>
                                                </div>
                                                <div class="text-md-right">
                                                    <div class="font-weight-bold" style="font-size:1.1rem;">
                                                        Rp {{ number_format($nominalDenda, 0, ',', '.') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    @empty
                                    <div class="text-center text-muted py-5">
                                        <i class="fas fa-folder-open fa-3x mb-3 text-secondary"></i>
                                        <div class="font-weight-bold">Belum ada aktivitas peminjaman</div>
                                        <small>Silakan cari buku dan lakukan peminjaman melalui katalog.</small>
                                    </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <footer class="main-footer border-0 shadow-sm">
            <strong>&copy; {{ date('Y') }} Sipusaka.</strong>
            <span class="text-muted">Dashboard Mahasiswa.</span>
            <div class="float-right d-none d-sm-inline-block">
                <b>AdminLTE</b> Bootstrap Panel
            </div>
        </footer>
    </div>

    @if (session('login_success'))
    <div class="modal fade" id="loginSuccessModal" tabindex="-1" role="dialog" aria-labelledby="loginSuccessModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0" style="border-radius: 18px; overflow: hidden;">
                <div class="modal-body text-center p-5">
                    <div class="modal-success-icon mb-3">
                        <i class="fas fa-check"></i>
                    </div>
                    <h4 class="font-weight-bold mb-2" id="loginSuccessModalLabel">Login Berhasil</h4>
                    <p class="text-muted mb-4">{{ session('login_success') }}</p>
                    <button type="button" class="btn px-4 text-white" data-dismiss="modal"
                        style="background:linear-gradient(135deg,#1d4ed8,#1e3a8a);border:none;border-radius:12px;">
                        <i class="fas fa-thumbs-up mr-1"></i> Lanjutkan
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script>

    @php
    $peminjamanLabelsJson = json_encode($peminjamanPerJudul->pluck('buku_judul'));
    $peminjamanDataJson = json_encode($peminjamanPerJudul->pluck('total'));
    $historyLabelsJson = json_encode($historyPeminjamanPengembalian->pluck('date'));
    $historyPeminjamanDataJson = json_encode($historyPeminjamanPengembalian->pluck('peminjaman'));
    $historyPengembalianDataJson = json_encode($historyPeminjamanPengembalian->pluck('pengembalian'));
    $loginLabelsJson = json_encode($login7Hari->pluck('date'));
    $loginDataJson = json_encode($login7Hari->pluck('total'));
    @endphp
    <script>
    // Pass PHP data to JavaScript safely
    window.chartData = {
        peminjamanLabels: JSON.parse('{!! $peminjamanLabelsJson !!}'),
        peminjamanData: JSON.parse('{!! $peminjamanDataJson !!}'),
        historyLabels: JSON.parse('{!! $historyLabelsJson !!}'),
        historyPeminjamanData: JSON.parse('{!! $historyPeminjamanDataJson !!}'),
        historyPengembalianData: JSON.parse('{!! $historyPengembalianDataJson !!}'),
        loginLabels: JSON.parse('{!! $loginLabelsJson !!}'),
        loginData: JSON.parse('{!! $loginDataJson !!}')
    };

    // Initialize Charts
    document.addEventListener('DOMContentLoaded', function() {
        // Donut Chart: Peminjaman per Judul
        const peminjamanCtx = document.getElementById('peminjamanChart').getContext('2d');

        new Chart(peminjamanCtx, {
            type: 'doughnut',
            data: {
                labels: window.chartData.peminjamanLabels,
                datasets: [{
                    data: window.chartData.peminjamanData,
                    backgroundColor: [
                        '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF',
                        '#FF9F40', '#C9CBCF', '#7CB342', '#5C6BC0', '#26A69A'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            boxWidth: 12,
                            padding: 15
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return label + ': ' + value + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });

        // Bar Chart: History Peminjaman & Pengembalian
        const historyCtx = document.getElementById('historyChart').getContext('2d');

        new Chart(historyCtx, {
            type: 'bar',
            data: {
                labels: window.chartData.historyLabels,
                datasets: [{
                        label: 'Peminjaman',
                        data: window.chartData.historyPeminjamanData,
                        backgroundColor: 'rgba(29, 78, 216, 0.75)',
                        borderColor: 'rgba(29, 78, 216, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Pengembalian',
                        data: window.chartData.historyPengembalianData,
                        backgroundColor: 'rgba(22, 163, 74, 0.75)',
                        borderColor: 'rgba(22, 163, 74, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        },
                        title: {
                            display: true,
                            text: 'Jumlah'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Tanggal'
                        }
                    }
                }
            }
        });

        // Line Chart: Login 7 Hari
        const loginCtx = document.getElementById('loginChart').getContext('2d');

        new Chart(loginCtx, {
            type: 'line',
            data: {
                labels: window.chartData.loginLabels,
                datasets: [{
                    label: 'Jumlah Login',
                    data: window.chartData.loginData,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        },
                        title: {
                            display: true,
                            text: 'Jumlah Session'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Tanggal'
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Login: ' + context.raw;
                            }
                        }
                    }
                }
            }
        });
    });
    </script>

    @if (session('login_success'))
    <script>
    $(function() {
        $('#loginSuccessModal').modal('show');
    });
    </script>
    @endif

    <script>
    $(function() {
        // Toggle edit field
        $('.btn-edit-field').on('click', function() {
            const field = $(this).data('field');
            $(`.field-value[data-field="${field}"]`).addClass('d-none');
            $(this).addClass('d-none');
            $(`.field-edit[data-field="${field}"]`).removeClass('d-none');
        });

        // Cancel edit
        $('.btn-cancel-field').on('click', function() {
            const field = $(this).closest('.field-edit').data('field');
            $(`.field-edit[data-field="${field}"]`).addClass('d-none');
            $(`.field-value[data-field="${field}"]`).removeClass('d-none');
            $(`.btn-edit-field[data-field="${field}"]`).removeClass('d-none');
        });

        // Save field
        $('.btn-save-field').on('click', function() {
            const fieldEdit = $(this).closest('.field-edit');
            const field = fieldEdit.data('field');
            const newValue = fieldEdit.find('input').val();

            const data = {};
            // Prepare all data from current view
            $('.field-value').each(function() {
                const f = $(this).data('field');
                data[f] = $(this).text().trim();
                if (data[f] === '-') data[f] = '';
            });
            // Update the one being changed
            data[field] = newValue;

            $.ajax({
                url: "{{ route('mahasiswa.update-request') }}",
                method: "POST",
                data: {
                    ...data,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert(response.message || "Gagal mengirim permintaan.");
                    }
                },
                error: function(xhr) {
                    alert("Terjadi kesalahan. Pastikan data valid.");
                }
            });
        });
    });
    </script>
</body>

</html>
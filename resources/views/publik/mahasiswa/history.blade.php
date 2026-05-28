<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>History Mahasiswa | Sipusaka</title>

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,600,700&display=fallback">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">

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
        background: rgba(255, 255, 255, .96);
        backdrop-filter: blur(10px);
    }

    .history-hero {
        border: 1px solid #dbeafe;
        border-radius: 24px;
        color: #fff;
        background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 58%, #2563eb 100%);
        box-shadow: 0 20px 45px rgba(15, 23, 42, .14);
        overflow: hidden;
        position: relative;
    }

    .history-hero::after {
        content: "";
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at top right, rgba(255, 255, 255, .14), transparent 35%);
        pointer-events: none;
    }

    .hero-icon {
        width: 78px;
        height: 78px;
        border-radius: 24px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, .12);
        border: 1px solid rgba(255, 255, 255, .24);
        backdrop-filter: blur(8px);
        font-size: 34px;
        box-shadow: 0 10px 28px rgba(15, 23, 42, .2);
    }

    .panel-card {
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        box-shadow: 0 14px 34px rgba(15, 23, 42, .06);
        overflow: hidden;
        background: #fff;
    }

    .lookup-card {
        max-width: 860px;
        margin: 0 auto;
    }

    .form-control {
        height: 46px;
        border-radius: 10px;
        border-color: #cbd5e1;
    }

    .form-control:focus {
        border-color: #1d4ed8;
        box-shadow: 0 0 0 .2rem rgba(37, 99, 235, .18);
    }

    .input-group-text {
        min-width: 46px;
        justify-content: center;
        border-radius: 10px;
    }

    .btn-gradient {
        min-height: 46px;
        font-weight: 600;
        border-radius: 10px;
        color: #fff;
        background: linear-gradient(135deg, #1d4ed8, #1e3a8a);
        border: none;
        box-shadow: 0 10px 20px rgba(30, 64, 175, .22);
    }

    .btn-gradient:hover {
        color: #fff;
        background: linear-gradient(135deg, #1e40af, #172554);
    }

    .profile-tile {
        border-radius: 18px;
        background: linear-gradient(135deg, #eff6ff, #dbeafe);
        border: 1px solid #bfdbfe;
        padding: 18px;
        height: 100%;
    }

    .profile-avatar {
        width: 68px;
        height: 68px;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #1d4ed8, #1e3a8a);
        color: #fff;
        font-size: 30px;
        box-shadow: 0 12px 24px rgba(30, 64, 175, .2);
    }

    .score-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #fff;
        color: #1e3a8a;
        border: 1px solid #bfdbfe;
        padding: 10px 14px;
        border-radius: 999px;
        font-weight: 800;
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

    .empty-state {
        padding: 52px 20px;
        text-align: center;
        color: #64748b;
    }

    .empty-state i {
        color: #94a3b8;
    }

    .text-white-50 {
        color: rgba(255, 255, 255, .72) !important;
    }

    @media (max-width: 767.98px) {
        .content-header h1 {
            font-size: 1.55rem;
        }

        .history-hero .d-flex {
            align-items: flex-start !important;
        }

        .hero-icon {
            width: 62px;
            height: 62px;
            border-radius: 18px;
            font-size: 28px;
        }
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
                    <a href="{{ route('mahasiswa.dashboard') }}" class="nav-link">Dashboard</a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="{{ route('publik.katalog') }}" class="nav-link">Katalog Buku</a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="{{ route('publik.history.form') }}" class="nav-link font-weight-semibold">History</a>
                </li>
            </ul>

            <ul class="navbar-nav ml-auto align-items-center">
                @isset($mahasiswa)
                <li class="nav-item mr-2 d-none d-md-block">
                    <span class="badge px-3 py-2" style="background:#dbeafe;color:#1e3a8a;border:1px solid #bfdbfe;">
                        <i class="fas fa-user-check mr-1"></i> {{ ucfirst($mahasiswa->status) }}
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
                @else
                <li class="nav-item">
                    <a href="{{ route('mahasiswa.login') }}" class="btn btn-sm text-white"
                        style="background:linear-gradient(135deg,#1d4ed8,#1e3a8a);border:none;border-radius:10px;">
                        <i class="fas fa-sign-in-alt mr-1"></i> Login Mahasiswa
                    </a>
                </li>
                @endisset
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
                        <a href="#" class="d-block font-weight-semibold">{{ $mahasiswa->nama ?? 'Mahasiswa' }}</a>
                        <small class="text-white-50">{{ $mahasiswa->nim ?? '-' }}</small>
                    </div>
                </div>

                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                        <li class="nav-header text-white-50">MENU MAHASISWA</li>
                        <li class="nav-item">
                            <a href="{{ route('mahasiswa.dashboard') }}" class="nav-link">
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
                            <a href="{{ route('publik.history.form') }}" class="nav-link active">
                                <i class="nav-icon fas fa-history"></i>
                                <p>Cek History Publik</p>
                            </a>
                        </li>

                        <li class="nav-header text-white-50">AKUN</li>
                        @isset($mahasiswa)
                        <li class="nav-item">
                            <form action="{{ route('mahasiswa.logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="nav-link btn btn-link text-left text-white w-100">
                                    <i class="nav-icon fas fa-sign-out-alt"></i>
                                    <p>Logout</p>
                                </button>
                            </form>
                        </li>
                        @else
                        <li class="nav-item">
                            <a href="{{ route('mahasiswa.login') }}" class="nav-link">
                                <i class="nav-icon fas fa-sign-in-alt"></i>
                                <p>Login Mahasiswa</p>
                            </a>
                        </li>
                        @endisset
                    </ul>
                </nav>
            </div>
        </aside>

        <div class="content-wrapper">
            <div class="content-header pb-2">
                <div class="container-fluid">
                    <div class="row mb-2 align-items-center">
                        <div class="col-sm-6">
                            <h1 class="m-0 font-weight-bold">History Mahasiswa</h1>

                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('publik.katalog') }}">Home</a></li>
                                <li class="breadcrumb-item active">History</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div class="card history-hero mb-4">
                        <div class="card-body p-4">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <div class="d-flex align-items-center">
                                        <div class="hero-icon mr-3">
                                            <i class="fas fa-history"></i>
                                        </div>
                                        <div>
                                            <h3 class="font-weight-bold mb-1">Riwayat Peminjaman Buku</h3>
                                            <p class="mb-0">
                                                Pantau status transaksi, tanggal pinjam, dan pengembalian buku secara
                                                responsif.
                                            </p>
                                            <small class="text-white-50">Gunakan NIM dan token referral jika belum login
                                                ke dashboard mahasiswa.</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 text-md-right mt-3 mt-md-0">
                                    <a href="{{ route('publik.katalog') }}" class="btn btn-light btn-sm"
                                        style="border-radius:12px;color:#1e3a8a;font-weight:600;">
                                        <i class="fas fa-search mr-1"></i> Cari Buku
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <i class="icon fas fa-ban mr-1"></i>
                        {{ $errors->first('msg') ?: $errors->first() }}
                    </div>
                    @endif

                    @empty($mahasiswa)
                    <div class="card panel-card lookup-card">
                        <div class="card-header bg-white border-0 pt-4 px-4">
                            <h3 class="card-title font-weight-bold mb-0">
                                <i class="fas fa-id-card mr-1 text-primary"></i>
                                Cek History Peminjaman
                            </h3>
                            <br>
                            <small class="text-muted">Masukkan NIM dan token referral aktif untuk melihat data
                                peminjaman.</small>
                        </div>
                        <form method="POST" action="{{ route('publik.history.show') }}">
                            @csrf
                            <div class="card-body p-4">
                                <div class="row">
                                    <div class="col-md-5 mb-3">
                                        <label for="nim" class="font-weight-semibold">NIM</label>
                                        <div class="input-group">
                                            <input id="nim" type="text" name="nim" class="form-control"
                                                placeholder="Contoh: 20260001" required value="{{ old('nim') }}"
                                                autofocus>
                                            <div class="input-group-append">
                                                <div class="input-group-text bg-white">
                                                    <span class="fas fa-id-card text-primary"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5 mb-3">
                                        <label for="token" class="font-weight-semibold">Token Referral</label>
                                        <div class="input-group">
                                            <input id="token" type="text" name="token"
                                                class="form-control text-uppercase" placeholder="6 digit token" required
                                                maxlength="6" value="{{ old('token') }}">
                                            <div class="input-group-append">
                                                <div class="input-group-text bg-white">
                                                    <span class="fas fa-key text-primary"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 mb-3 d-flex align-items-end">
                                        <button type="submit" class="btn btn-gradient btn-block">
                                            <i class="fas fa-search mr-1"></i> Cek
                                        </button>
                                    </div>
                                </div>

                                <div class="alert mb-0"
                                    style="background:#f8fbff;border-left:4px solid #1d4ed8;color:#334155;border-radius:10px;">
                                    <i class="fas fa-info-circle text-primary mr-1"></i>
                                    Token hanya dapat digunakan oleh mahasiswa yang sudah disetujui admin.
                                </div>
                            </div>
                        </form>
                    </div>
                    @endempty

                    @isset($mahasiswa)
                    <div class="row mb-4">
                        <div class="col-lg-8 mb-3 mb-lg-0">
                            <div class="profile-tile">
                                <div class="d-flex align-items-center">
                                    <div class="profile-avatar mr-3">
                                        <i class="fas fa-user-graduate"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-weight-bold mb-1">{{ $mahasiswa->nama }}</h4>
                                        <div class="text-muted">
                                            <i class="fas fa-id-card mr-1"></i> {{ $mahasiswa->nim }}
                                            @if(!empty($mahasiswa->jurusan))
                                            <span class="mx-2">&bull;</span>
                                            <i class="fas fa-graduation-cap mr-1"></i> {{ $mahasiswa->jurusan }}
                                            @endif
                                        </div>
                                        <span class="badge mt-2"
                                            style="background:#dbeafe;color:#1e3a8a;border:1px solid #bfdbfe;">
                                            <i class="fas fa-check-circle mr-1"></i> {{ ucfirst($mahasiswa->status) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="profile-tile d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="text-muted font-weight-semibold">Kredit Skor</div>
                                    <small>Skor berdasarkan ketepatan pengembalian.</small>
                                </div>
                                <span class="score-badge">
                                    <i class="fas fa-star"></i> {{ $kreditSkor }}/100
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="card panel-card">
                        <div class="card-header bg-white border-0 pt-4 px-4">
                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                                <div>
                                    <h3 class="card-title font-weight-bold mb-0">
                                        <i class="fas fa-clipboard-list mr-1 text-primary"></i>
                                        Daftar Riwayat Peminjaman
                                    </h3>
                                    <br>
                                    <small class="text-muted">Data transaksi terbaru ditampilkan di bagian atas.</small>
                                </div>
                                <div class="mt-3 mt-md-0">
                                    <a href="{{ route('publik.history.form') }}" class="btn btn-outline-secondary"
                                        style="border-radius:10px;">
                                        <i class="fas fa-sync-alt mr-1"></i> Cek Riwayat Lain
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Booking ID</th>
                                        <th>Buku</th>
                                        <th>Status</th>
                                        <th>Tanggal Pinjam</th>
                                        <th>Rencana Kembali</th>
                                        <th>Tanggal Kembali</th>
                                        <th>QR Code</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($peminjaman as $p)
                                    <tr>
                                        <td class="font-weight-semibold">{{ $p->booking_id ?? '-' }}</td>
                                        <td>
                                            <div class="font-weight-semibold">
                                                {{ $p->buku->judul ?? $p->buku->nama_buku ?? '-' }}</div>
                                            <small class="text-muted">{{ $p->buku->penulis ?? '' }}</small>
                                        </td>
                                        <td>
                                            @php
                                            $statusStyle = [
                                            'dikembalikan' => 'background:#dcfce7;color:#166534;border:1px solid
                                            #bbf7d0;',
                                            'dipinjam' => 'background:#dbeafe;color:#1e3a8a;border:1px solid #bfdbfe;',
                                            'terlambat' => 'background:#fee2e2;color:#991b1b;border:1px solid #fecaca;',
                                            'pending' => 'background:#fef3c7;color:#92400e;border:1px solid #fde68a;',
                                            'booking' => 'background:#ede9fe;color:#5b21b6;border:1px solid #ddd6fe;',
                                            'ditolak' => 'background:#f1f5f9;color:#475569;border:1px solid #e2e8f0;',
                                            ][$p->status] ?? 'background:#f1f5f9;color:#475569;border:1px solid
                                            #e2e8f0;';
                                            @endphp
                                            <span class="badge badge-status"
                                                style="{{ $statusStyle }}">{{ ucfirst($p->status ?? '-') }}</span>
                                        </td>
                                        <td>{{ $p->tanggal_pinjam ? \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d/m/Y') : '-' }}
                                        </td>
                                        <td>{{ $p->tanggal_kembali_rencana ? \Carbon\Carbon::parse($p->tanggal_kembali_rencana)->format('d/m/Y') : '-' }}
                                        </td>
                                        <td>{{ ($p->tanggal_kembali_aktual ?? $p->tanggal_kembali) ? \Carbon\Carbon::parse($p->tanggal_kembali_aktual ?? $p->tanggal_kembali)->format('d/m/Y') : '-' }}
                                        </td>
                                        <td>
                                            @if($p->qr_code_path)
                                            <button type="button" class="btn btn-sm btn-outline-primary"
                                                data-toggle="modal" data-target="#qrCodeModal{{ $p->id }}"
                                                style="border-radius: 10px;">
                                                <i class="fas fa-qrcode mr-1"></i> QR Code
                                            </button>
                                            @else
                                            -
                                            @endif
                                        </td>
                                    </tr>

                                    @if($p->qr_code_path)
                                    <div class="modal fade" id="qrCodeModal{{ $p->id }}" tabindex="-1" role="dialog"
                                        aria-labelledby="qrCodeModalLabel{{ $p->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content" style="border-radius: 18px; overflow: hidden;">
                                                <div class="modal-header"
                                                    style="background: linear-gradient(135deg, #1d4ed8, #1e3a8a); color: #fff;">
                                                    <h5 class="modal-title font-weight-bold"
                                                        id="qrCodeModalLabel{{ $p->id }}">
                                                        <i class="fas fa-qrcode mr-1"></i> QR Code Peminjaman
                                                    </h5>
                                                    <button type="button" class="close text-white" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="card mb-0"
                                                        style="border-radius: 16px; border: 1px solid #e2e8f0;">
                                                        <div class="card-body text-center">
                                                            <div class="mb-3">
                                                                <div class="font-weight-bold">
                                                                    {{ $p->booking_id ?? '-' }}
                                                                </div>
                                                                <small class="text-muted">
                                                                    {{ $p->buku->judul ?? $p->buku->nama_buku ?? '-' }}
                                                                </small>
                                                            </div>
                                                            <img src="{{ asset('storage/' . $p->qr_code_path) }}"
                                                                alt="QR Code {{ $p->booking_id ?? '' }}"
                                                                class="img-fluid"
                                                                style="max-width: 240px; border: 1px solid #e2e8f0; border-radius: 12px; padding: 10px; background: #fff;">
                                                            <div class="mt-3">
                                                                <a href="{{ asset('storage/' . $p->qr_code_path) }}"
                                                                    target="_blank" class="btn btn-sm btn-primary"
                                                                    style="border-radius: 10px;">
                                                                    <i class="fas fa-external-link-alt mr-1"></i>
                                                                    Buka QR Code
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    @empty
                                    <tr>
                                        <td colspan="7" class="empty-state">
                                            <i class="fas fa-folder-open fa-3x mb-3"></i>
                                            <div class="font-weight-bold">Belum ada riwayat peminjaman</div>
                                            <small>Riwayat akan muncul setelah mahasiswa melakukan peminjaman
                                                buku.</small>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endisset
                </div>
            </section>
        </div>

        <footer class="main-footer border-0 shadow-sm">
            <strong>&copy; {{ date('Y') }} Sipusaka.</strong>
            <span class="text-muted">History Mahasiswa.</span>
            <div class="float-right d-none d-sm-inline-block">
                <b>AdminLTE</b> Bootstrap Panel
            </div>
        </footer>
    </div>

    <script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script>
    <script>
    $(function() {
        $('#token').on('input', function() {
            this.value = this.value.toUpperCase();
        });
    });
    </script>
</body>

</html>
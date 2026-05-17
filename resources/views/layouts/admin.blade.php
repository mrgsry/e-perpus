<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') | SiPusaka Admin</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/css/adminlte.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link rel="icon" href="{{ asset('img/sipusaka.png') }}" type="image/png">

    <style>
    

        :root {
            --sidebar-w:      240px;
            --nav-h:          56px;
            --sidebar-bg:     #0f172a;
            --sidebar-hover:  #1e293b;
            --sidebar-active: #1d4ed8;
            --sidebar-accent: #3b82f6;
            --brand-blue:     #2563eb;
            --brand-dark:     #1d4ed8;
            --bg-page:        #f1f5f9;
            --bg-card:        #ffffff;
            --text-primary:   #0f172a;
            --text-muted:     #64748b;
            --border:         #e2e8f0;
            --font:           'Plus Jakarta Sans', sans-serif;
            --radius:         12px;
            --radius-sm:      8px;
        }

        * { box-sizing: border-box; }

        body,
        body.hold-transition {
            font-family: var(--font) !important;
            background: var(--bg-page) !important;
            color: var(--text-primary) !important;
            -webkit-font-smoothing: antialiased;
        }

        /* ── SIDEBAR ── */
        .main-sidebar,
        .main-sidebar.sidebar-dark-primary {
            background: var(--sidebar-bg) !important;
            width: var(--sidebar-w) !important;
            border-right: none !important;
            box-shadow: 4px 0 24px rgba(0,0,0,0.15) !important;
        }

        /* Brand */
        .brand-link {
            background: var(--sidebar-bg) !important;
            border-bottom: 1px solid rgba(255,255,255,0.07) !important;
            padding: 0 1.25rem !important;
            height: var(--nav-h) !important;
            display: flex !important;
            align-items: center !important;
            gap: 10px !important;
            text-decoration: none !important;
        }

        .brand-link:hover { background: var(--sidebar-hover) !important; }

        /* Override AdminLTE default img constraints in brand-link */
        .brand-link img,
        .brand-link .brand-icon-box {
            width: 44px !important;
            height: 44px !important;
            max-height: none !important;
            background: transparent !important;
            border-radius: 0 !important;
            object-fit: contain !important;
            padding: 0 !important;
            margin: 0 !important;
            float: none !important;
            line-height: 1 !important;
        }

        .brand-text {
            font-size: 1rem !important;
            font-weight: 800 !important;
            color: #ffffff !important;
            letter-spacing: -0.02em !important;
        }

        .brand-text span { color: #93c5fd !important; }

        /* Sidebar nav */
        .sidebar { padding-top: 0.5rem !important; overflow-y: auto !important; }

        .nav-sidebar .nav-item { margin: 2px 0.75rem !important; }

        .nav-sidebar .nav-link {
            display: flex !important;
            align-items: center !important;
            gap: 10px !important;
            padding: 0.6rem 0.875rem !important;
            border-radius: var(--radius-sm) !important;
            color: #94a3b8 !important;
            font-size: 0.875rem !important;
            font-weight: 500 !important;
            transition: all 0.15s !important;
            white-space: nowrap !important;
        }

        .nav-sidebar .nav-link:hover {
            background: rgba(255,255,255,0.06) !important;
            color: #e2e8f0 !important;
        }

        .nav-sidebar .nav-link.active {
            background: var(--sidebar-active) !important;
            color: #ffffff !important;
            box-shadow: 0 4px 12px rgba(37,99,235,0.35) !important;
        }

        .nav-sidebar .nav-link .nav-icon {
            width: 18px !important;
            font-size: 0.85rem !important;
            text-align: center !important;
            flex-shrink: 0 !important;
            color: inherit !important;
            margin-right: 0 !important;
        }

        .nav-sidebar .nav-link.active .nav-icon { color: #bfdbfe !important; }

        /* Sidebar section label */
        .nav-sidebar .nav-header {
            font-size: 0.65rem !important;
            font-weight: 700 !important;
            letter-spacing: 0.1em !important;
            text-transform: uppercase !important;
            color: #475569 !important;
            padding: 1rem 1.625rem 0.375rem !important;
        }

        /* ── NAVBAR ── */
        .main-header.navbar {
            background: #ffffff !important;
            border-bottom: 1px solid var(--border) !important;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04) !important;
            height: var(--nav-h) !important;
            min-height: var(--nav-h) !important;
            padding: 0 1.25rem !important;
            margin-left: var(--sidebar-w) !important;
        }

        .main-header .nav-link {
            color: var(--text-muted) !important;
            padding: 0.4rem 0.6rem !important;
            border-radius: var(--radius-sm) !important;
            transition: all 0.15s !important;
        }

        .main-header .nav-link:hover {
            background: #f1f5f9 !important;
            color: var(--text-primary) !important;
        }

        /* Page title in navbar */
        .navbar-page-title {
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--text-primary);
            letter-spacing: -0.01em;
        }

        /* Logout btn */
        .btn-logout {
            display: flex !important;
            align-items: center !important;
            gap: 6px !important;
            padding: 6px 14px !important;
            font-family: var(--font) !important;
            font-size: 0.8125rem !important;
            font-weight: 600 !important;
            color: #dc2626 !important;
            background: #fff1f2 !important;
            border: 1px solid #fecdd3 !important;
            border-radius: var(--radius-sm) !important;
            cursor: pointer !important;
            transition: all 0.15s !important;
            text-decoration: none !important;
        }

        .btn-logout:hover {
            background: #fee2e2 !important;
            color: #b91c1c !important;
            border-color: #fca5a5 !important;
        }

        /* Admin badge */
        .admin-badge {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 5px 10px;
            background: #f8fafc;
            border: 1px solid var(--border);
            border-radius: 999px;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--text-muted);
        }

        .admin-badge-dot {
            width: 7px; height: 7px;
            background: #22c55e;
            border-radius: 50%;
        }

        /* ── CONTENT WRAPPER ── */
        .content-wrapper {
            background: var(--bg-page) !important;
            margin-left: var(--sidebar-w) !important;
            min-height: calc(100vh - var(--nav-h)) !important;
            padding-top: var(--nav-h) !important;
        }

        /* ── CONTENT HEADER ── */
        .content-header {
            padding: 1.5rem 1.5rem 0 !important;
        }

        .content-header h1,
        .content-header .page-title {
            font-size: 1.25rem !important;
            font-weight: 800 !important;
            color: var(--text-primary) !important;
            letter-spacing: -0.025em !important;
            margin: 0 !important;
        }

        .content-header .breadcrumb {
            background: none !important;
            padding: 0 !important;
            margin: 0.25rem 0 0 !important;
            font-size: 0.8rem !important;
        }

        .content-header .breadcrumb-item { color: var(--text-muted) !important; }
        .content-header .breadcrumb-item.active { color: var(--brand-blue) !important; font-weight: 600 !important; }
        .content-header .breadcrumb-item + .breadcrumb-item::before { color: #cbd5e1 !important; }

        /* ── CONTENT ── */
        .content {
            padding: 1.25rem 1.5rem 2rem !important;
        }

        /* ── CARDS ── */
        .card {
            border: 1px solid var(--border) !important;
            border-radius: var(--radius) !important;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04) !important;
            background: var(--bg-card) !important;
        }

        .card-header {
            background: var(--bg-card) !important;
            border-bottom: 1px solid var(--border) !important;
            padding: 1rem 1.25rem !important;
            border-radius: var(--radius) var(--radius) 0 0 !important;
            display: flex !important;
            align-items: center !important;
            gap: 10px !important;
        }

        .card-header .card-title {
            font-size: 0.9375rem !important;
            font-weight: 700 !important;
            color: var(--text-primary) !important;
            margin: 0 !important;
        }

        .card-body { padding: 1.25rem !important; }

        /* Stat cards */
        .stat-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 1.25rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: box-shadow 0.2s, transform 0.2s;
        }

        .stat-card:hover {
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
            transform: translateY(-1px);
        }

        .stat-icon {
            width: 48px; height: 48px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .stat-icon-blue   { background: #eff6ff; color: #2563eb; }
        .stat-icon-green  { background: #f0fdf4; color: #16a34a; }
        .stat-icon-amber  { background: #fffbeb; color: #d97706; }
        .stat-icon-red    { background: #fff1f2; color: #dc2626; }
        .stat-icon-purple { background: #faf5ff; color: #9333ea; }

        .stat-label {
            font-size: 0.775rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 4px;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--text-primary);
            letter-spacing: -0.03em;
            line-height: 1;
        }

        /* ── TABLES ── */
        .table {
            font-size: 0.875rem !important;
            color: var(--text-primary) !important;
        }

        .table thead th {
            font-size: 0.75rem !important;
            font-weight: 700 !important;
            color: var(--text-muted) !important;
            text-transform: uppercase !important;
            letter-spacing: 0.06em !important;
            background: #f8fafc !important;
            border-bottom: 1px solid var(--border) !important;
            padding: 0.75rem 1rem !important;
            white-space: nowrap !important;
        }

        .table tbody td {
            padding: 0.8rem 1rem !important;
            border-bottom: 1px solid #f8fafc !important;
            vertical-align: middle !important;
        }

        .table tbody tr:hover td {
            background: #fafbff !important;
        }

        .table tbody tr:last-child td { border-bottom: none !important; }

        /* ── BADGES ── */
        .badge {
            font-family: var(--font) !important;
            font-size: 0.72rem !important;
            font-weight: 700 !important;
            padding: 4px 10px !important;
            border-radius: 999px !important;
            letter-spacing: 0.02em !important;
        }

        .badge.bg-warning  { background: #fffbeb !important; color: #92400e !important; }
        .badge.bg-success  { background: #f0fdf4 !important; color: #166534 !important; }
        .badge.bg-danger   { background: #fff1f2 !important; color: #9f1239 !important; }
        .badge.bg-primary  { background: #eff6ff !important; color: #1d4ed8 !important; }
        .badge.bg-info     { background: #f0f9ff !important; color: #0369a1 !important; }
        .badge.bg-secondary{ background: #f8fafc !important; color: #475569 !important; }

        /* ── BUTTONS ── */
        .btn {
            font-family: var(--font) !important;
            font-weight: 600 !important;
            font-size: 0.8125rem !important;
            border-radius: var(--radius-sm) !important;
            padding: 0.45rem 1rem !important;
            transition: all 0.15s !important;
        }

        .btn-primary {
            background: var(--brand-blue) !important;
            border-color: var(--brand-blue) !important;
            box-shadow: 0 2px 8px rgba(37,99,235,0.25) !important;
        }

        .btn-primary:hover {
            background: var(--brand-dark) !important;
            border-color: var(--brand-dark) !important;
            box-shadow: 0 4px 12px rgba(37,99,235,0.35) !important;
            transform: translateY(-1px) !important;
        }

        .btn-sm {
            font-size: 0.775rem !important;
            padding: 0.35rem 0.75rem !important;
        }

        /* ── FORMS ── */
        .form-control, .form-select {
            font-family: var(--font) !important;
            font-size: 0.875rem !important;
            border: 1.5px solid var(--border) !important;
            border-radius: var(--radius-sm) !important;
            padding: 0.5rem 0.875rem !important;
            color: var(--text-primary) !important;
            background: #f8fafc !important;
            transition: all 0.2s !important;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--brand-blue) !important;
            background: #fff !important;
            box-shadow: 0 0 0 3px rgba(37,99,235,0.1) !important;
        }

        .form-label {
            font-size: 0.8rem !important;
            font-weight: 700 !important;
            color: #475569 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.05em !important;
            margin-bottom: 0.4rem !important;
        }

        /* ── FOOTER ── */
        .main-footer {
            background: var(--bg-card) !important;
            border-top: 1px solid var(--border) !important;
            margin-left: var(--sidebar-w) !important;
            padding: 0.875rem 1.5rem !important;
            font-size: 0.8rem !important;
            color: var(--text-muted) !important;
            font-weight: 500 !important;
        }

        /* ── DATATABLE ── */
        .dataTables_wrapper .dataTables_filter input {
            font-family: var(--font) !important;
            font-size: 0.8125rem !important;
            border: 1.5px solid var(--border) !important;
            border-radius: var(--radius-sm) !important;
            padding: 0.4rem 0.75rem !important;
            outline: none !important;
        }

        .dataTables_wrapper .dataTables_filter input:focus {
            border-color: var(--brand-blue) !important;
            box-shadow: 0 0 0 3px rgba(37,99,235,0.1) !important;
        }

        .dataTables_wrapper .dataTables_length select {
            font-family: var(--font) !important;
            font-size: 0.8125rem !important;
            border: 1.5px solid var(--border) !important;
            border-radius: var(--radius-sm) !important;
            padding: 0.3rem 0.6rem !important;
        }

        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter {
            font-size: 0.8125rem !important;
            color: var(--text-muted) !important;
        }

        .page-item .page-link {
            font-family: var(--font) !important;
            font-size: 0.8rem !important;
            font-weight: 600 !important;
            border-radius: var(--radius-sm) !important;
            border-color: var(--border) !important;
            color: var(--text-muted) !important;
            margin: 0 2px !important;
        }

        .page-item.active .page-link {
            background: var(--brand-blue) !important;
            border-color: var(--brand-blue) !important;
            color: #fff !important;
        }

        /* ── ALERTS ── */
        .alert {
            font-size: 0.875rem !important;
            border-radius: var(--radius-sm) !important;
            border: none !important;
            padding: 0.875rem 1rem !important;
        }

        .alert-success { background: #f0fdf4 !important; color: #166534 !important; border-left: 3px solid #16a34a !important; }
        .alert-danger  { background: #fff1f2 !important; color: #9f1239 !important; border-left: 3px solid #e11d48 !important; }
        .alert-warning { background: #fffbeb !important; color: #92400e !important; border-left: 3px solid #f59e0b !important; }
        .alert-info    { background: #f0f9ff !important; color: #0369a1 !important; border-left: 3px solid #0ea5e9 !important; }

        /* ── MODALS ── */
        .modal-content {
            border: none !important;
            border-radius: var(--radius) !important;
            box-shadow: 0 20px 60px rgba(0,0,0,0.15) !important;
        }

        .modal-header {
            background: #f8fafc !important;
            border-bottom: 1px solid var(--border) !important;
            padding: 1rem 1.25rem !important;
            border-radius: var(--radius) var(--radius) 0 0 !important;
        }

        .modal-title {
            font-size: 0.9375rem !important;
            font-weight: 700 !important;
            color: var(--text-primary) !important;
        }

        .modal-footer {
            border-top: 1px solid var(--border) !important;
            padding: 0.875rem 1.25rem !important;
            background: #f8fafc !important;
            border-radius: 0 0 var(--radius) var(--radius) !important;
        }

        /* ── SCROLLBAR ── */
        .sidebar::-webkit-scrollbar { width: 4px; }
        .sidebar::-webkit-scrollbar-track { background: transparent; }
        .sidebar::-webkit-scrollbar-thumb { background: #334155; border-radius: 4px; }

        /* ── BODY COLLAPSED SIDEBAR ── */
        body.sidebar-collapse .content-wrapper,
        body.sidebar-collapse .main-header.navbar,
        body.sidebar-collapse .main-footer {
            margin-left: 0 !important;
        }
        body.sidebar-collapse .badge-sidebar {
            display: none !important;
        }

        /* ── RESPONSIVE ── */
        @media (max-width: 768px) {
            .content-wrapper,
            .main-header.navbar,
            .main-footer { margin-left: 0 !important; }
        }
    </style>

    @stack('css')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    {{-- ── NAVBAR ── --}}
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        {{-- Toggle + Page Title --}}
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                    <i class="fas fa-bars" style="font-size:1rem;"></i>
                </a>
            </li>
            <li class="nav-item d-none d-md-flex align-items-center ms-1">
                <span class="navbar-page-title">@yield('title', 'Dashboard')</span>
            </li>
        </ul>

        {{-- Right --}}
        <ul class="navbar-nav ms-auto align-items-center gap-2">
            <li class="nav-item d-none d-md-block">
                <div class="admin-badge">
                    <span class="admin-badge-dot"></span>
                    Administrator
                </div>
            </li>
            <li class="nav-item">
                <form action="{{ route('admin.logout') }}" method="POST" class="d-inline m-0">
                    @csrf
                    <button type="submit" class="btn-logout">
                        <i class="fas fa-sign-out-alt" style="font-size:0.75rem;"></i>
                        Logout
                    </button>
                </form>
            </li>
        </ul>
    </nav>

    {{-- ── SIDEBAR ── --}}
    <aside class="main-sidebar sidebar-dark-primary elevation-0">
        {{-- Brand --}}
        <a href="{{ route('admin.dashboard') }}" class="brand-link">
            <img src="{{ asset('img/sipusaka.png') }}" alt="SiPusaka"
                 style="width:56px;height:56px;max-height:none;object-fit:contain;background:transparent;flex-shrink:0;">
            <span class="brand-text">SI<span>PUSAKA</span></span>
        </a>

        <div class="sidebar">
            <nav class="mt-1 pb-3">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">

                    <li class="nav-header">Menu Utama</li>

                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}"
                           class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-chart-pie"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>

                    <li class="nav-header">Manajemen</li>

                    <li class="nav-item">
                        <a href="{{ route('admin.buku.index') }}"
                           class="nav-link {{ request()->routeIs('admin.buku*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-book-open"></i>
                            <p>Manajemen Buku</p>
                        </a>
                    </li>

                    @php
                        $pendingCount = \App\Models\Mahasiswa::where('status', 'pending')->count();
                    @endphp
                    <li class="nav-item">
                        <a href="{{ route('admin.mahasiswa.index') }}"
                           class="nav-link {{ request()->routeIs('admin.mahasiswa*') ? 'active' : '' }}" style="position:relative;">
                            <i class="nav-icon fas fa-user-graduate"></i>
                            <p>Mahasiswa</p>
                            @if($pendingCount > 0)
                            <span class="badge-sidebar" style="position:absolute; top:50%; right:15px; transform:translateY(-50%); font-size:9px; padding:2px 6px; border-radius:10px; background-color:#e74c3c; color:#fff; font-weight:bold; line-height:1;">
                                {{ $pendingCount > 99 ? '99+' : $pendingCount }}
                            </span>
                            @endif
                        </a>
                    </li>

                    <li class="nav-header">Transaksi</li>

                    <li class="nav-item">
                        <a href="{{ route('admin.peminjaman.index') }}"
                           class="nav-link {{ request()->routeIs('admin.peminjaman*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-clipboard-list"></i>
                            <p>Peminjaman</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.pengembalian.index') }}"
                           class="nav-link {{ request()->routeIs('admin.pengembalian.index') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-undo-alt"></i>
                            <p>Pengembalian</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.pengembalian.scan-qr') }}"
                           class="nav-link {{ request()->routeIs('admin.pengembalian.scan-qr') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-qrcode"></i>
                            <p>Scan QR</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.denda.index') }}"
                           class="nav-link {{ request()->routeIs('admin.denda*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-file-invoice-dollar"></i>
                            <p>Denda</p>
                        </a>
                    </li>

                    <li class="nav-header">Laporan</li>

                    <li class="nav-item">
                        <a href="{{ route('admin.history.index') }}"
                           class="nav-link {{ request()->routeIs('admin.history*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-history"></i>
                            <p>History</p>
                        </a>
                    </li>

                    <li class="nav-header">Layanan</li>

                    @php
                        $chatCount = \App\Models\ChatSession::where('status', '!=', 'closed')
                            ->where('is_connected_to_admin', true)
                            ->count();
                    @endphp
                    <li class="nav-item">
                        <a href="{{ route('admin.chat.index') }}"
                           class="nav-link {{ request()->routeIs('admin.chat*') ? 'active' : '' }}" style="position:relative;">
                            <i class="nav-icon fas fa-comments"></i>
                            <p>Chat Mahasiswa</p>
                            @if($chatCount > 0)
                            <span class="badge-sidebar" style="position:absolute; top:50%; right:15px; transform:translateY(-50%); font-size:9px; padding:2px 6px; border-radius:10px; background-color:#e74c3c; color:#fff; font-weight:bold; line-height:1;">
                                {{ $chatCount > 99 ? '99+' : $chatCount }}
                            </span>
                            @endif
                        </a>
                    </li>

                </ul>
            </nav>
        </div>
    </aside>

    {{-- ── CONTENT ── --}}
    <div class="content-wrapper">
        @yield('content')
    </div>

    {{-- ── FOOTER ── --}}
    <footer class="main-footer text-center">
        © {{ date('Y') }} <strong>SiPusaka</strong> — Sistem Informasi Perpustakaan Digital - hnet.diigital.biz.id
    </footer>

</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/js/adminlte.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
@stack('js')
@stack('scripts')
</body>
</html>

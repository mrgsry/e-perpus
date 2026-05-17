@extends('layouts.publik')

@section('title', 'Cek Status Peminjaman')

@push('head_styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    .cek-page {
        min-height: calc(100vh - 64px - 73px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 3rem 1rem;
        background: #f8fafc;
        background-image:
            radial-gradient(ellipse 70% 40% at 60% 0%, rgba(37,99,235,0.07), transparent),
            radial-gradient(ellipse 50% 30% at 10% 90%, rgba(37,99,235,0.04), transparent);
    }

    .cek-box { width: 100%; max-width: 460px; }
    .cek-header { text-align: center; margin-bottom: 1.75rem; }

    .cek-icon-wrap {
        display: inline-flex; align-items: center; justify-content: center;
        width: 54px; height: 54px; background: #2563eb; border-radius: 14px;
        margin-bottom: 1rem; box-shadow: 0 6px 20px rgba(37,99,235,0.28);
    }
    .cek-icon-wrap svg { width: 26px; height: 26px; fill: none; stroke: #fff; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
    .cek-title { font-size: 1.4rem; font-weight: 800; color: #0f172a; letter-spacing: -0.025em; margin: 0 0 0.35rem; }
    .cek-desc { font-size: 0.875rem; color: #64748b; margin: 0; }

    .cek-card {
        background: #fff; border-radius: 18px; padding: 1.875rem;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.04), 0 10px 30px -5px rgba(37,99,235,0.07);
    }

    .field-label { display: block; font-size: 0.775rem; font-weight: 700; color: #475569; letter-spacing: 0.06em; text-transform: uppercase; margin-bottom: 0.5rem; }
    .field-wrap { position: relative; margin-bottom: 0.625rem; }
    .field-ico {
        position: absolute; left: 13px; top: 50%; transform: translateY(-50%);
        width: 17px; height: 17px; fill: none; stroke: #94a3b8;
        stroke-width: 2; stroke-linecap: round; stroke-linejoin: round;
        pointer-events: none; transition: stroke 0.2s;
    }
    .field-input {
        width: 100%; height: 48px; padding: 0 14px 0 42px;
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 0.9375rem; font-weight: 500; color: #0f172a;
        background: #f8fafc; border: 1.5px solid #e2e8f0;
        border-radius: 11px; outline: none; transition: all 0.2s;
        box-sizing: border-box; letter-spacing: 0.03em;
    }
    .field-input::placeholder { color: #cbd5e1; font-weight: 400; font-size: 0.875rem; letter-spacing: 0; }
    .field-input:hover { border-color: #bfdbfe; background: #f0f6ff; }
    .field-input:focus { border-color: #2563eb; background: #fff; box-shadow: 0 0 0 3px rgba(37,99,235,0.12); }
    .field-hint { font-size: 0.8rem; color: #94a3b8; margin-top: 0.5rem; }
    .field-hint code { font-family: 'Courier New', monospace; background: #f1f5f9; color: #2563eb; font-size: 0.75rem; padding: 1px 6px; border-radius: 4px; }

    .btn-cek {
        width: 100%; height: 50px; margin-top: 1.25rem;
        display: flex; align-items: center; justify-content: center; gap: 8px;
        background: #2563eb; color: #fff;
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 0.9375rem; font-weight: 700; border: none;
        border-radius: 11px; cursor: pointer; transition: all 0.2s;
        box-shadow: 0 4px 12px rgba(37,99,235,0.3);
    }
    .btn-cek svg { width: 17px; height: 17px; fill: none; stroke: currentColor; stroke-width: 2.5; stroke-linecap: round; stroke-linejoin: round; }
    .btn-cek:hover { background: #1d4ed8; box-shadow: 0 6px 18px rgba(37,99,235,0.38); transform: translateY(-1px); }
    .btn-cek:active { transform: translateY(0); }
    .btn-cek:disabled { opacity: 0.65; cursor: not-allowed; transform: none; }

    .btn-spinner { display: none; width: 17px; height: 17px; border: 2px solid rgba(255,255,255,0.4); border-top-color: #fff; border-radius: 50%; animation: spin 0.7s linear infinite; }
    @keyframes spin { to { transform: rotate(360deg); } }

    .cek-sep { border: none; border-top: 1px solid #f1f5f9; margin: 1.5rem 0; }
    .cek-info { display: flex; gap: 10px; align-items: flex-start; background: #eff6ff; border-radius: 10px; padding: 0.875rem 1rem; }
    .cek-info svg { flex-shrink: 0; width: 17px; height: 17px; fill: none; stroke: #2563eb; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; margin-top: 1px; }
    .cek-info p { font-size: 0.8125rem; color: #1e40af; margin: 0; line-height: 1.6; }

    .cek-footer { text-align: center; margin-top: 1.375rem; font-size: 0.8125rem; color: #94a3b8; }
    .cek-footer a { color: #2563eb; font-weight: 600; text-decoration: none; }
    .cek-footer a:hover { text-decoration: underline; }

    /* Inline error box */
    .inline-error-box {
        display: none;
        align-items: flex-start;
        gap: 10px;
        margin-bottom: 1rem;
        padding: 0.875rem 1rem;
        background: #fff1f2;
        border: 1px solid #fecdd3;
        border-radius: 10px;
    }
    .inline-error-box svg { flex-shrink: 0; width: 17px; height: 17px; fill: none; stroke: #e11d48; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; margin-top: 1px; }
    .inline-error-box p { font-size: 0.875rem; color: #9f1239; margin: 0; line-height: 1.5; }

    /* Modal */
    .modal-content { border: none; border-radius: 18px; overflow: hidden; box-shadow: 0 20px 60px rgba(0,0,0,0.15); }
    .modal-header-custom { padding: 1.25rem 1.5rem 1rem; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between; }
    .modal-header-left { display: flex; align-items: center; gap: 10px; }
    .modal-header-icon { width: 36px; height: 36px; background: #eff6ff; border-radius: 9px; display: flex; align-items: center; justify-content: center; }
    .modal-header-icon svg { width: 18px; height: 18px; fill: none; stroke: #2563eb; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
    .modal-header-title { font-size: 1rem; font-weight: 700; color: #0f172a; margin: 0; letter-spacing: -0.01em; }
    .modal-header-sub { font-size: 0.775rem; color: #94a3b8; margin: 0; }
    .modal-close { width: 32px; height: 32px; border: none; background: #f1f5f9; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: background 0.15s; flex-shrink: 0; }
    .modal-close:hover { background: #e2e8f0; }
    .modal-close svg { width: 16px; height: 16px; fill: none; stroke: #64748b; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }

    /* Status badge */
    .status-badge { display: inline-flex; align-items: center; gap: 6px; padding: 5px 12px; border-radius: 999px; font-size: 0.8rem; font-weight: 700; letter-spacing: 0.01em; }
    .status-badge .dot { width: 7px; height: 7px; border-radius: 50%; }
    .badge-primary   { background: #eff6ff; color: #1d4ed8; }
    .badge-primary .dot   { background: #2563eb; }
    .badge-warning   { background: #fffbeb; color: #92400e; }
    .badge-warning .dot   { background: #f59e0b; animation: pulse 1.4s ease-in-out infinite; }
    .badge-success   { background: #f0fdf4; color: #166534; }
    .badge-success .dot   { background: #16a34a; }
    .badge-danger    { background: #fff1f2; color: #9f1239; }
    .badge-danger .dot    { background: #e11d48; }
    .badge-secondary { background: #f8fafc; color: #475569; }
    .badge-secondary .dot { background: #94a3b8; }
    @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.4; } }

    /* Detail rows */
    .detail-body { padding: 1.25rem 1.5rem; }
    .detail-section-title { font-size: 0.725rem; font-weight: 700; color: #94a3b8; letter-spacing: 0.07em; text-transform: uppercase; margin-bottom: 0.75rem; }
    .detail-row { display: flex; align-items: flex-start; padding: 0.6rem 0; border-bottom: 1px solid #f8fafc; }
    .detail-row:last-child { border-bottom: none; }
    .detail-icon { width: 30px; height: 30px; flex-shrink: 0; background: #f8fafc; border-radius: 7px; display: flex; align-items: center; justify-content: center; margin-right: 10px; margin-top: 1px; }
    .detail-icon svg { width: 14px; height: 14px; fill: none; stroke: #64748b; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
    .detail-key { font-size: 0.775rem; color: #94a3b8; font-weight: 500; width: 130px; flex-shrink: 0; padding-top: 6px; }
    .detail-val { font-size: 0.875rem; color: #0f172a; font-weight: 600; flex: 1; padding-top: 5px; line-height: 1.4; }

    /* Denda alert */
    .denda-alert { margin: 0 1.5rem 1.25rem; background: #fff7ed; border: 1px solid #fed7aa; border-radius: 10px; padding: 0.875rem 1rem; display: flex; align-items: flex-start; gap: 10px; }
    .denda-alert svg { flex-shrink: 0; width: 17px; height: 17px; fill: none; stroke: #ea580c; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; margin-top: 1px; }
    .denda-alert-text p { margin: 0; }
    .denda-alert-text .denda-label { font-size: 0.775rem; color: #9a3412; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; }
    .denda-alert-text .denda-amount { font-size: 1.1rem; color: #c2410c; font-weight: 800; margin-top: 2px; }

    /* QR section */
    .qr-section { margin: 0 1.5rem 1.25rem; background: #f8fafc; border-radius: 12px; padding: 1.25rem; text-align: center; }
    .qr-section img { width: 140px; height: 140px; border-radius: 8px; border: 4px solid #fff; box-shadow: 0 2px 12px rgba(0,0,0,0.1); }
    .qr-label { font-size: 0.775rem; color: #94a3b8; margin-top: 0.625rem; }
    .qr-download { display: inline-flex; align-items: center; gap: 5px; margin-top: 0.625rem; font-size: 0.8rem; font-weight: 600; color: #2563eb; text-decoration: none; padding: 5px 12px; background: #eff6ff; border-radius: 7px; transition: background 0.15s; }
    .qr-download:hover { background: #dbeafe; color: #1d4ed8; }
    .qr-download svg { width: 13px; height: 13px; fill: none; stroke: currentColor; stroke-width: 2.5; stroke-linecap: round; stroke-linejoin: round; }

    /* Modal footer */
    .modal-footer-custom { padding: 1rem 1.5rem; border-top: 1px solid #f1f5f9; display: flex; gap: 10px; }
    .btn-cek-lain { flex: 1; height: 42px; display: flex; align-items: center; justify-content: center; gap: 6px; background: #f8fafc; color: #475569; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.875rem; font-weight: 600; border: 1.5px solid #e2e8f0; border-radius: 10px; cursor: pointer; transition: all 0.15s; }
    .btn-cek-lain:hover { background: #f1f5f9; border-color: #cbd5e1; }
    .btn-cek-lain svg { width: 15px; height: 15px; fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
    .btn-tutup { flex: 1; height: 42px; display: flex; align-items: center; justify-content: center; background: #2563eb; color: #fff; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.875rem; font-weight: 700; border: none; border-radius: 10px; cursor: pointer; transition: all 0.15s; }
    .btn-tutup:hover { background: #1d4ed8; }
</style>
@endpush

@section('content')
<div class="cek-page">
    <div class="cek-box">

        {{-- Header --}}
        <div class="cek-header">
            <div class="cek-icon-wrap">
                <svg viewBox="0 0 24 24">
                    <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/>
                    <rect x="9" y="3" width="6" height="4" rx="1"/>
                    <path d="M9 12h6M9 16h4"/>
                </svg>
            </div>
            <h1 class="cek-title">Cek Status Peminjaman</h1>
            <p class="cek-desc">Masukkan Booking ID untuk melacak status peminjaman Anda</p>
        </div>

        {{-- Form Card --}}
        <div class="cek-card">

            {{-- Inline error --}}
            <div id="inline-error" class="inline-error-box">
                <svg viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8" x2="12" y2="12"/>
                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                <p id="inline-error-msg"></p>
            </div>

            <form id="cekStatusForm" novalidate>
                @csrf
                <label class="field-label" for="booking_id">Booking ID</label>
                <div class="field-wrap">
                    <input
                        class="field-input"
                        type="text"
                        id="booking_id"
                        name="booking_id"
                        placeholder="Contoh: PSK-20260422-0001"
                        required autocomplete="off" spellcheck="false"
                    >
                    <svg class="field-ico" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"/>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                </div>
                <p class="field-hint">Format: <code>PSK-YYYYMMDD-XXXX</code></p>

                <button type="submit" class="btn-cek" id="btnSubmit">
                    <span class="btn-spinner" id="btnSpinner"></span>
                    <svg id="btnIcon" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"/>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                    <span id="btnText">Cek Status Sekarang</span>
                </button>
            </form>

            <hr class="cek-sep">

            <div class="cek-info">
                <svg viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="16" x2="12" y2="12"/>
                    <line x1="12" y1="8" x2="12.01" y2="8"/>
                </svg>
                <p>Booking ID tercantum pada email konfirmasi atau struk peminjaman yang Anda terima dari petugas perpustakaan.</p>
            </div>
        </div>

        <p class="cek-footer">Butuh bantuan? <a href="#">Hubungi Admin</a></p>
    </div>
</div>

{{-- ── MODAL HASIL ── --}}
<div class="modal fade" id="modalStatus" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width: 480px;">
        <div class="modal-content">

            {{-- Header --}}
            <div class="modal-header-custom">
                <div class="modal-header-left">
                    <div class="modal-header-icon">
                        <svg viewBox="0 0 24 24">
                            <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/>
                            <rect x="9" y="3" width="6" height="4" rx="1"/>
                            <path d="M9 12h6M9 16h4"/>
                        </svg>
                    </div>
                    <div>
                        <p class="modal-header-title">Detail Peminjaman</p>
                        <p class="modal-header-sub" id="modalBookingId">—</p>
                    </div>
                </div>
                <button class="modal-close" data-bs-dismiss="modal">
                    <svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>

            {{-- Status badge --}}
            <div style="padding: 0.875rem 1.5rem 0;">
                <span class="status-badge" id="modalStatusBadge">
                    <span class="dot"></span>
                    <span id="modalStatusLabel">—</span>
                </span>
            </div>

            {{-- Denda alert (hidden by default) --}}
            <div class="denda-alert" id="dendaAlert" style="display:none; margin-top: 1rem;">
                <svg viewBox="0 0 24 24">
                    <path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                    <line x1="12" y1="9" x2="12" y2="13"/>
                    <line x1="12" y1="17" x2="12.01" y2="17"/>
                </svg>
                <div class="denda-alert-text">
                    <p class="denda-label">Denda Berjalan</p>
                    <p class="denda-amount" id="dendaAmount">—</p>
                </div>
            </div>

            {{-- Detail rows --}}
            <div class="detail-body">
                <p class="detail-section-title">Informasi Peminjaman</p>

                <div class="detail-row">
                    <div class="detail-icon">
                        <svg viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                    </div>
                    <span class="detail-key">Booking ID</span>
                    <span class="detail-val" id="dBookingId">—</span>
                </div>

                <div class="detail-row">
                    <div class="detail-icon">
                        <svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </div>
                    <span class="detail-key">Mahasiswa</span>
                    <span class="detail-val" id="dMahasiswa">—</span>
                </div>

                <div class="detail-row">
                    <div class="detail-icon">
                        <svg viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 016.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/></svg>
                    </div>
                    <span class="detail-key">Buku</span>
                    <span class="detail-val" id="dBuku">—</span>
                </div>

                <div class="detail-row">
                    <div class="detail-icon">
                        <svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    </div>
                    <span class="detail-key">Tgl Pinjam</span>
                    <span class="detail-val" id="dTanggalPinjam">—</span>
                </div>

                <div class="detail-row">
                    <div class="detail-icon">
                        <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    </div>
                    <span class="detail-key">Tenggat</span>
                    <span class="detail-val" id="dTenggat">—</span>
                </div>

                <div class="detail-row">
                    <div class="detail-icon">
                        <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                    </div>
                    <span class="detail-key">Tgl Kembali</span>
                    <span class="detail-val" id="dTanggalKembali">—</span>
                </div>
            </div>

            {{-- QR Code --}}
            <div class="qr-section" id="qrSection" style="display:none;">
                <img id="qrImage" src="" alt="QR Code Peminjaman">
                <p class="qr-label">QR Code peminjaman Anda</p>
                <a id="qrDownload" href="#" download class="qr-download">
                    <svg viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    Download QR Code
                </a>
            </div>

            {{-- Footer --}}
            <div class="modal-footer-custom">
                <button class="btn-cek-lain" id="btnCekLain">
                    <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    Cek ID Lain
                </button>
                <button class="btn-tutup" data-bs-dismiss="modal">Tutup</button>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form       = document.getElementById('cekStatusForm');
    const btnSubmit  = document.getElementById('btnSubmit');
    const btnSpinner = document.getElementById('btnSpinner');
    const btnIcon    = document.getElementById('btnIcon');
    const btnText    = document.getElementById('btnText');
    const inlineErr  = document.getElementById('inline-error');
    const inlineMsg  = document.getElementById('inline-error-msg');
    const modal      = new bootstrap.Modal(document.getElementById('modalStatus'));

    function setLoading(on) {
        btnSubmit.disabled       = on;
        btnSpinner.style.display = on ? 'block' : 'none';
        btnIcon.style.display    = on ? 'none'  : 'block';
        btnText.textContent      = on ? 'Mencari...' : 'Cek Status Sekarang';
    }

    function showError(msg) {
        inlineErr.style.display = 'flex';
        inlineMsg.textContent   = msg;
        inlineErr.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    function hideError() {
        inlineErr.style.display = 'none';
    }

    function setText(id, val) {
        document.getElementById(id).textContent = val || '—';
    }

    const statusBadgeMap = {
        primary:   'badge-primary',
        warning:   'badge-warning',
        success:   'badge-success',
        danger:    'badge-danger',
        secondary: 'badge-secondary',
    };

    function fillModal(data) {
        setText('modalBookingId', data.booking_id);

        const badge     = document.getElementById('modalStatusBadge');
        badge.className = 'status-badge ' + (statusBadgeMap[data.status_color] || 'badge-secondary');
        setText('modalStatusLabel', data.status);

        setText('dBookingId',      data.booking_id);
        setText('dMahasiswa',      data.mahasiswa);
        setText('dBuku',           data.buku);
        setText('dTanggalPinjam',  data.tanggal_pinjam);
        setText('dTenggat',        data.tanggal_kembali_rencana);
        setText('dTanggalKembali', data.tanggal_kembali_aktual);

        const dendaAlert = document.getElementById('dendaAlert');
        if (data.denda) {
            document.getElementById('dendaAmount').textContent = data.denda;
            dendaAlert.style.display = 'flex';
        } else {
            dendaAlert.style.display = 'none';
        }

        const qrSection  = document.getElementById('qrSection');
        const qrImage    = document.getElementById('qrImage');
        const qrDownload = document.getElementById('qrDownload');
        if (data.qr_code_url) {
            qrImage.src             = data.qr_code_url;
            qrDownload.href         = data.qr_code_url;
            qrSection.style.display = 'block';
        } else {
            qrSection.style.display = 'none';
        }
    }

    form.addEventListener('submit', async function (e) {
        e.preventDefault();
        hideError();

        const bookingId = document.getElementById('booking_id').value.trim();
        if (!bookingId) {
            showError('Masukkan Booking ID terlebih dahulu.');
            return;
        }

        setLoading(true);

        try {
            const res  = await fetch('{{ route("publik.cek-status.post") }}', {
                method:  'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept':       'application/json',
                },
                body: JSON.stringify({ booking_id: bookingId }),
            });

            const json = await res.json();

            if (!res.ok || !json.success) {
                showError(json.message || 'Booking ID tidak ditemukan.');
                return;
            }

            fillModal(json.data);
            modal.show();

        } catch (err) {
            showError('Terjadi kesalahan koneksi. Silakan coba lagi.');
        } finally {
            setLoading(false);
        }
    });

    document.getElementById('btnCekLain').addEventListener('click', function () {
        modal.hide();
        setTimeout(() => {
            document.getElementById('booking_id').value = '';
            document.getElementById('booking_id').focus();
        }, 350);
    });
});
</script>
@endpush

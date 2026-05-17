@extends('layouts.publik')

@section('title', 'Registrasi Mahasiswa')

@push('head_styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    .register-page {
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

    .register-box { width: 100%; max-width: 500px; }
    .register-header { text-align: center; margin-bottom: 1.75rem; }

    .register-icon-wrap {
        display: inline-flex; align-items: center; justify-content: center;
        width: 54px; height: 54px; background: #2563eb; border-radius: 14px;
        margin-bottom: 1rem; box-shadow: 0 6px 20px rgba(37,99,235,0.28);
    }
    .register-icon-wrap svg { width: 26px; height: 26px; fill: none; stroke: #fff; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
    .register-title { font-size: 1.4rem; font-weight: 800; color: #0f172a; letter-spacing: -0.025em; margin: 0 0 0.35rem; }
    .register-desc { font-size: 0.875rem; color: #64748b; margin: 0; }

    .register-card {
        background: #fff; border-radius: 18px; padding: 1.875rem;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.04), 0 10px 30px -5px rgba(37,99,235,0.07);
    }

    .field-label { display: block; font-size: 0.775rem; font-weight: 700; color: #475569; letter-spacing: 0.06em; text-transform: uppercase; margin-bottom: 0.5rem; }
    .field-wrap { position: relative; margin-bottom: 1rem; }
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
    .field-input:focus + .field-ico { stroke: #2563eb; }

    .btn-register {
        width: 100%; height: 50px; margin-top: 1.25rem;
        display: flex; align-items: center; justify-content: center; gap: 8px;
        background: #2563eb; color: #fff;
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 0.9375rem; font-weight: 700; border: none;
        border-radius: 11px; cursor: pointer; transition: all 0.2s;
        box-shadow: 0 4px 12px rgba(37,99,235,0.3);
    }
    .btn-register svg { width: 17px; height: 17px; fill: none; stroke: currentColor; stroke-width: 2.5; stroke-linecap: round; stroke-linejoin: round; }
    .btn-register:hover { background: #1d4ed8; box-shadow: 0 6px 18px rgba(37,99,235,0.38); transform: translateY(-1px); }
    .btn-register:active { transform: translateY(0); }
    .btn-register:disabled { opacity: 0.65; cursor: not-allowed; transform: none; }

    .btn-spinner { display: none; width: 17px; height: 17px; border: 2px solid rgba(255,255,255,0.4); border-top-color: #fff; border-radius: 50%; animation: spin 0.7s linear infinite; }
    @keyframes spin { to { transform: rotate(360deg); } }

    .register-sep { border: none; border-top: 1px solid #f1f5f9; margin: 1.5rem 0; }
    .register-info { display: flex; gap: 10px; align-items: flex-start; background: #eff6ff; border-radius: 10px; padding: 0.875rem 1rem; }
    .register-info svg { flex-shrink: 0; width: 17px; height: 17px; fill: none; stroke: #2563eb; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; margin-top: 1px; }
    .register-info p { font-size: 0.8125rem; color: #1e40af; margin: 0; line-height: 1.6; }

    .register-footer { text-align: center; margin-top: 1.375rem; font-size: 0.8125rem; color: #94a3b8; }
    .register-footer a { color: #2563eb; font-weight: 600; text-decoration: none; }
    .register-footer a:hover { text-decoration: underline; }

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
    .modal-header-icon { width: 36px; height: 36px; background: #d1fae5; border-radius: 9px; display: flex; align-items: center; justify-content: center; }
    .modal-header-icon svg { width: 18px; height: 18px; fill: none; stroke: #10b981; stroke-width: 2.5; stroke-linecap: round; stroke-linejoin: round; }
    .modal-header-title { font-size: 1rem; font-weight: 700; color: #0f172a; margin: 0; letter-spacing: -0.01em; }
    .modal-header-sub { font-size: 0.775rem; color: #94a3b8; margin: 0; }
    .modal-close { width: 32px; height: 32px; border: none; background: #f1f5f9; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: background 0.15s; flex-shrink: 0; }
    .modal-close:hover { background: #e2e8f0; }
    .modal-close svg { width: 16px; height: 16px; fill: none; stroke: #64748b; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }

    .modal-body-custom { padding: 1.25rem 1.5rem; }
    .success-message { text-align: center; padding: 1rem 0; }
    .success-icon-large { width: 80px; height: 80px; background: #d1fae5; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 1rem; animation: scaleIn 0.5s ease-out; }
    @keyframes scaleIn { from { transform: scale(0); } to { transform: scale(1); } }
    .success-icon-large svg { width: 40px; height: 40px; fill: none; stroke: #10b981; stroke-width: 3; stroke-linecap: round; stroke-linejoin: round; }
    .success-title { font-size: 1.25rem; font-weight: 700; color: #0f172a; margin: 0 0 0.5rem; }
    .success-text { font-size: 0.9375rem; color: #64748b; margin: 0 0 1.25rem; line-height: 1.6; }

    .info-alert { background: #fffbeb; border: 1px solid #fde68a; border-radius: 10px; padding: 0.875rem 1rem; display: flex; align-items: flex-start; gap: 10px; margin-bottom: 1.25rem; }
    .info-alert svg { flex-shrink: 0; width: 17px; height: 17px; fill: none; stroke: #f59e0b; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; margin-top: 1px; }
    .info-alert-text p { margin: 0; font-size: 0.8125rem; color: #92400e; line-height: 1.5; font-weight: 500; }

    .modal-footer-custom { padding: 1rem 1.5rem; border-top: 1px solid #f1f5f9; }
    .btn-close-modal { width: 100%; height: 42px; display: flex; align-items: center; justify-content: center; background: #2563eb; color: #fff; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.875rem; font-weight: 700; border: none; border-radius: 10px; cursor: pointer; transition: all 0.15s; }
    .btn-close-modal:hover { background: #1d4ed8; }
</style>
@endpush

@section('content')
<div class="register-page">
    <div class="register-box">

        {{-- Header --}}
        <div class="register-header">
            <div class="register-icon-wrap">
                <svg viewBox="0 0 24 24">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <line x1="19" y1="8" x2="19" y2="14"/>
                    <line x1="22" y1="11" x2="16" y2="11"/>
                </svg>
            </div>
            <h1 class="register-title">Registrasi Mahasiswa</h1>
            <p class="register-desc">Daftar untuk mengakses layanan perpustakaan</p>
        </div>

        {{-- Form Card --}}
        <div class="register-card">

            {{-- Inline error --}}
            <div id="inline-error" class="inline-error-box">
                <svg viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8" x2="12" y2="12"/>
                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                <p id="inline-error-msg"></p>
            </div>

            <form id="registerForm" method="POST" action="{{ route('publik.register.store') }}" novalidate>
                @csrf
                
                <label class="field-label" for="nama">Nama Lengkap</label>
                <div class="field-wrap">
                    <input
                        class="field-input"
                        type="text"
                        id="nama"
                        name="nama"
                        placeholder="Masukkan nama lengkap"
                        required autocomplete="name" spellcheck="false"
                    >
                    <svg class="field-ico" viewBox="0 0 24 24">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                </div>

                <label class="field-label" for="nim">NIM</label>
                <div class="field-wrap">
                    <input
                        class="field-input"
                        type="text"
                        id="nim"
                        name="nim"
                        placeholder="Masukkan NIM"
                        required autocomplete="off" spellcheck="false"
                    >
                    <svg class="field-ico" viewBox="0 0 24 24">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                </div>

                <label class="field-label" for="jurusan">Jurusan</label>
                <div class="field-wrap">
                    <input
                        class="field-input"
                        type="text"
                        id="jurusan"
                        name="jurusan"
                        placeholder="Masukkan jurusan"
                        required autocomplete="off" spellcheck="false"
                    >
                    <svg class="field-ico" viewBox="0 0 24 24">
                        <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                        <path d="M6 12v5c3 3 9 3 12 0v-5"/>
                    </svg>
                </div>

 <label class="field-label" for="no_telepon">No. Telepon (Opsional)</label>
 <div class="field-wrap">
     <input
         class="field-input"
         type="tel"
         id="no_telepon"
         name="no_telepon"
         placeholder="Contoh: 08123456789"
         autocomplete="tel" spellcheck="false"
     >
     <svg class="field-ico" viewBox="0 0 24 24">
        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
        <polyline points="22,6 12,13 2,6"/>
     </svg>
 </div>

<label class="field-label" for="email">Email</label>
<div class="field-wrap">
    <input
        class="field-input"
        type="email"
        id="email"
        name="email"
        placeholder="contoh: nama@example.com"
        required autocomplete="email" spellcheck="false"
    >
    <svg class="field-ico" viewBox="0 0 24 24">
        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
    </svg>
</div>
                <button type="submit" class="btn-register" id="btnSubmit">
                    <span class="btn-spinner" id="btnSpinner"></span>
                    <svg id="btnIcon" viewBox="0 0 24 24">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <line x1="19" y1="8" x2="19" y2="14"/>
                        <line x1="22" y1="11" x2="16" y2="11"/>
                    </svg>
                    <span id="btnText">Daftar Sekarang</span>
                </button>
            </form>

            <hr class="register-sep">

            <div class="register-info">
                <svg viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="16" x2="12" y2="12"/>
                    <line x1="12" y1="8" x2="12.01" y2="8"/>
                </svg>
                <p>Setelah mendaftar, akun Anda akan diverifikasi oleh admin. Anda akan dapat menggunakan layanan perpustakaan setelah akun disetujui.</p>
            </div>
        </div>

        <p class="register-footer">Sudah terdaftar? <a href="{{ route('publik.cek-status') }}">Cek Status Peminjaman</a></p>
    </div>
</div>

{{-- Success Modal --}}
<div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 480px;">
        <div class="modal-content">

            {{-- Header --}}
            <div class="modal-header-custom">
                <div class="modal-header-left">
                    <div class="modal-header-icon">
                        <svg viewBox="0 0 24 24">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                    </div>
                    <div>
                        <p class="modal-header-title">Pendaftaran Berhasil</p>
                        <p class="modal-header-sub">Menunggu Persetujuan</p>
                    </div>
                </div>
                <button class="modal-close" onclick="window.location.href='{{ url('/') }}'">
                    <svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>

            {{-- Body --}}
            <div class="modal-body-custom">
                <div class="success-message">
                    <div class="success-icon-large">
                        <svg viewBox="0 0 24 24">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                    </div>
                    <h5 class="success-title">Terima Kasih!</h5>
                    <p class="success-text">Data pendaftaran Anda telah kami terima dan sedang dalam proses verifikasi.</p>
                </div>

                <div class="info-alert">
                    <svg viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="16" x2="12" y2="12"/>
                        <line x1="12" y1="8" x2="12.01" y2="8"/>
                    </svg>
                    <div class="info-alert-text">
                        <p>Pendaftaran Anda sedang menunggu persetujuan admin. Kami akan menghubungi Anda melalui nomor telepon yang terdaftar jika ada informasi lebih lanjut.</p>
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <div class="modal-footer-custom">
                <button class="btn-close-modal" onclick="window.location.href='{{ url('/') }}'">
                    Kembali ke Beranda
                </button>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form       = document.getElementById('registerForm');
    const btnSubmit  = document.getElementById('btnSubmit');
    const btnSpinner = document.getElementById('btnSpinner');
    const btnIcon    = document.getElementById('btnIcon');
    const btnText    = document.getElementById('btnText');
    const inlineErr  = document.getElementById('inline-error');
    const inlineMsg  = document.getElementById('inline-error-msg');
    const modal      = new bootstrap.Modal(document.getElementById('successModal'));

    function setLoading(on) {
        btnSubmit.disabled       = on;
        btnSpinner.style.display = on ? 'block' : 'none';
        btnIcon.style.display    = on ? 'none'  : 'block';
        btnText.textContent      = on ? 'Mendaftar...' : 'Daftar Sekarang';
    }

    function showError(msg) {
        inlineErr.style.display = 'flex';
        inlineMsg.textContent   = msg;
        inlineErr.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    function hideError() {
        inlineErr.style.display = 'none';
    }

    form.addEventListener('submit', async function (e) {
        e.preventDefault();
        hideError();

        const formData = new FormData(form);
        
        // Basic validation
if (!formData.get('nama') || !formData.get('nim') || !formData.get('jurusan') || !formData.get('email')) {
            showError('Nama, NIM, Jurusan, dan Email harus diisi.');
            return;
        }

        setLoading(true);

        try {
            const res  = await fetch(form.action, {
                method:  'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
                body: formData,
            });

            const json = await res.json();

            if (!res.ok || !json.success) {
                showError(json.message || 'Terjadi kesalahan. Silakan coba lagi.');
                return;
            }

            // Success - show modal and reset form
            form.reset();
            modal.show();

        } catch (err) {
            showError('Terjadi kesalahan koneksi. Silakan coba lagi.');
        } finally {
            setLoading(false);
        }
    });
});
</script>
@endpush
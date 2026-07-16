<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Mahasiswa | Sipusaka</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Source+Serif+4:opsz,wght@8..60,500;8..60,600;8..60,700&family=Inter:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    :root {
        --navy-900: #0F2A4D;
        --navy-700: #16385E;
        --navy-600: #1D4373;
        --blue-100: #0A1F3D;
        --gold-500: #B98A2E;
        --gold-300: #D9B565;
        --cream-50: #FAF7F0;
        --cream-100: #F2EDE1;
        --ink-900: #1E2430;
        --ink-600: #545B68;
        --slate-400: #8B93A3;
        --line: #E4DFD2;
        --maroon-600: #7C2430;
    }

    body {
        font-family: 'Inter', sans-serif;
        min-height: 100vh;
        background: var(--blue-100);
        background-image:
            radial-gradient(circle at 1px 1px, rgba(217, 181, 101, 0.08) 1px, transparent 0);
        background-size: 22px 22px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 20px;
        margin: 0;
        color: var(--ink-900);
    }

    .auth-wrapper {
        position: relative;
        width: 100%;
        max-width: 980px;
    }

    .login-container {
        position: relative;
        z-index: 1;
        width: 100%;
        min-height: 580px;
        background: var(--cream-50);
        border: 1px solid var(--line);
        border-radius: 6px;
        box-shadow: 0 30px 60px rgba(15, 42, 77, 0.16);
        overflow: hidden;
        display: flex;
    }

    /* ================= LEFT: FORM PANEL ================= */
    .login-left {
        flex: 1;
        padding: 52px 60px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .brand-row {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 34px;
    }

    .brand-mark {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: var(--navy-900);
        border: 1.5px solid var(--gold-500);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .brand-mark i {
        color: var(--gold-300);
        font-size: 15px;
    }

    .brand-text {
        line-height: 1.25;
    }

    .brand-text strong {
        display: block;
        font-family: 'Source Serif 4', serif;
        font-size: 15.5px;
        font-weight: 700;
        letter-spacing: 0.04em;
        color: var(--navy-900);
    }

    .brand-text span {
        display: block;
        font-size: 10.5px;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: var(--ink-600);
    }

    .form-eyebrow {
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: var(--gold-500);
        margin-bottom: 10px;
    }

    .form-title {
        font-family: 'Source Serif 4', serif;
        font-size: 27px;
        font-weight: 600;
        color: var(--navy-900);
        margin-bottom: 6px;
        letter-spacing: -0.01em;
    }

    .form-subtitle {
        font-size: 13.5px;
        color: var(--ink-600);
        margin-bottom: 26px;
        line-height: 1.55;
    }

    .form-title+.form-subtitle {
        margin-top: 4px;
    }

    #loginPanel .form-title {
        margin-bottom: 28px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label-row {
        display: flex;
        align-items: baseline;
        justify-content: space-between;
        margin-bottom: 7px;
    }

    .form-label {
        display: block;
        font-size: 11.5px;
        font-weight: 600;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        color: var(--ink-600);
    }

    .input-wrap {
        position: relative;
    }

    .form-input {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid var(--line);
        border-radius: 4px;
        font-size: 14.5px;
        transition: all 0.2s ease;
        font-family: 'Inter', sans-serif;
        min-height: 46px;
        background: #ffffff;
        color: var(--ink-900);
    }

    .form-input::placeholder {
        color: var(--slate-400);
    }

    .form-input:focus {
        outline: none;
        border-color: var(--navy-600);
        box-shadow: 0 0 0 3px rgba(15, 42, 77, 0.10);
    }

    .input-wrap .form-input.has-toggle {
        padding-right: 44px;
    }

    .toggle-visibility {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: var(--slate-400);
        cursor: pointer;
        font-size: 15px;
        padding: 4px;
        line-height: 1;
    }

    .toggle-visibility:hover {
        color: var(--navy-700);
    }

    .remember-row {
        margin: 6px 0 26px;
    }

    .remember-me {
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--ink-600);
        font-size: 13.5px;
        cursor: pointer;
        user-select: none;
    }

    .remember-me input {
        width: 15px;
        height: 15px;
        accent-color: var(--navy-700);
        cursor: pointer;
    }

    .forgot-link {
        color: var(--navy-700);
        text-decoration: none;
        font-weight: 600;
        font-size: 12.5px;
        border-bottom: 1px solid transparent;
    }

    .forgot-link:hover {
        border-bottom-color: var(--navy-700);
    }

    .login-button {
        width: 100%;
        padding: 13px 20px;
        background: var(--navy-900);
        color: #ffffff;
        border: none;
        border-radius: 4px;
        font-size: 13.5px;
        font-weight: 600;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        cursor: pointer;
        transition: all 0.2s ease;
        min-height: 48px;
    }

    .login-button:hover {
        background: var(--navy-700);
    }

    .switch-form-text {
        text-align: left;
        margin-top: 20px;
        font-size: 13px;
        color: var(--ink-600);
    }

    .switch-form-text a {
        color: var(--navy-700);
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        border-bottom: 1px solid transparent;
    }

    .switch-form-text a:hover {
        border-bottom-color: var(--navy-700);
    }

    .error-message {
        background: #F7E7E4;
        border: 1px solid rgba(124, 36, 48, 0.25);
        color: var(--maroon-600);
        padding: 11px 14px;
        border-radius: 4px;
        margin-bottom: 18px;
        font-size: 13px;
        display: none;
    }

    .error-message.show {
        display: block;
    }

    .form-panel {
        display: none;
    }

    .form-panel.active {
        display: block;
    }

    .register-info {
        display: flex;
        gap: 9px;
        align-items: flex-start;
        background: var(--cream-100);
        border-left: 3px solid var(--gold-500);
        border-radius: 3px;
        padding: 13px 14px;
        margin-top: 18px;
    }

    .register-info i {
        flex-shrink: 0;
        color: var(--navy-700);
        margin-top: 2px;
        font-size: 13px;
    }

    .register-info p {
        font-size: 12px;
        color: var(--ink-600);
        margin: 0;
        line-height: 1.55;
    }

    .form-row {
        display: flex;
        gap: 12px;
    }

    .form-row .form-group {
        flex: 1;
        min-width: 0;
    }

    /* ================= RIGHT: INSTITUTIONAL PANEL ================= */
    .login-right {
        flex: 0.92;
        position: relative;
        background: var(--navy-900);
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .login-right svg {
        width: 84%;
        height: auto;
        position: relative;
        z-index: 1;
    }

    .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 33, 0.55);
        backdrop-filter: blur(4px);
        align-items: center;
        justify-content: center;
        z-index: 1000;
        padding: 20px;
    }

    .modal-overlay.show {
        display: flex;
    }

    .modal-card {
        background: var(--cream-50);
        border: 1px solid var(--line);
        border-radius: 6px;
        padding: 36px;
        max-width: 380px;
        width: 100%;
        text-align: center;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.25);
        animation: modalPop 0.25s ease;
    }

    @keyframes modalPop {
        from {
            opacity: 0;
            transform: scale(0.94) translateY(8px);
        }

        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }

    .modal-icon {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: var(--navy-900);
        border: 1.5px solid var(--gold-500);
        color: var(--gold-300);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        margin: 0 auto 18px;
    }

    .modal-icon.error {
        background: var(--maroon-600);
        border-color: var(--maroon-600);
        color: #ffffff;
    }

    .modal-title {
        font-family: 'Source Serif 4', serif;
        font-size: 19px;
        font-weight: 600;
        color: var(--navy-900);
        margin-bottom: 8px;
    }

    .modal-text {
        font-size: 13.5px;
        color: var(--ink-600);
        line-height: 1.6;
        margin-bottom: 22px;
    }

    .modal-button {
        width: 100%;
        padding: 11px 20px;
        background: var(--navy-900);
        color: white;
        border: none;
        border-radius: 4px;
        font-size: 13px;
        font-weight: 600;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        cursor: pointer;
        font-family: 'Inter', sans-serif;
    }

    .modal-button:hover {
        background: var(--navy-700);
    }

    /* ============ MOBILE ============ */
    @media (max-width: 768px) {
        body {
            padding: 0;
            display: block;
            background: var(--blue-100);
        }

        .auth-wrapper {
            max-width: 100%;
            min-height: 100vh;
        }

        .login-container {
            flex-direction: column;
            border-radius: 0;
            border: none;
            min-height: 100vh;
            box-shadow: none;
        }

        .login-right {
            min-height: 190px;
            flex: none;
            order: -1;
        }

        .login-right svg {
            width: 62%;
        }

        .login-left {
            padding: 30px 24px 40px;
        }

        .form-title {
            font-size: 22px;
        }

        .form-row {
            flex-direction: column;
            gap: 0;
        }

        .form-row .form-group {
            margin-bottom: 20px;
        }

        .modal-card {
            margin: 16px;
            padding: 24px;
        }
    }

    @media (max-width: 380px) {
        .login-left {
            padding: 26px 18px 34px;
        }

        .form-title {
            font-size: 20px;
        }
    }
    </style>
</head>

<body>
    <div class="auth-wrapper">
        <div class="login-container">
            <div class="login-left">
                <div class="brand-row">
                    <div class="brand-mark"><i class="fas fa-book"></i></div>
                    <div class="brand-text">
                        <strong>SIPUSAKA</strong>
                        <span>Sistem Informasi Perpustakaan</span>
                    </div>
                </div>

                <!-- ===== LOGIN PANEL ===== -->
                <div id="loginPanel" class="form-panel active">
                    <p class="form-eyebrow">Portal Mahasiswa</p>
                    <h1 class="form-title">Masuk ke Akun Anda</h1>

                    <div class="error-message" id="loginErrorMessage">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <span>Kredensial login tidak valid. Silakan coba lagi.</span>
                    </div>

                    <form action="{{ route('mahasiswa.login.submit') }}" method="post">
                        @csrf

                        <div class="form-group">
                            <div class="form-label-row">
                                <label for="nim" class="form-label">Nomor Induk Mahasiswa</label>
                            </div>
                            <div class="input-wrap">
                                <input type="text" id="nim" name="nim" class="form-input" placeholder="Masukkan NIM"
                                    value="{{ old('nim') }}" required autofocus>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-label-row">
                                <label for="token" class="form-label">Token Referral</label>
                                <a href="#" class="forgot-link">Lupa password?</a>
                            </div>
                            <div class="input-wrap">
                                <input type="password" id="token" name="token" class="form-input has-toggle"
                                    placeholder="6 digit token" maxlength="6" required>
                                <button type="button" class="toggle-visibility" id="toggleToken" tabindex="-1"
                                    aria-label="Tampilkan token">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="remember-row">
                            <label class="remember-me">
                                <input type="checkbox" name="remember">
                                Ingat saya
                            </label>
                        </div>

                        <button type="submit" class="login-button">
                            Masuk
                        </button>
                    </form>

                    <p class="switch-form-text">
                        Belum terdaftar? <a onclick="showRegister()">Daftar sekarang</a>
                    </p>
                </div>

                <!-- ===== REGISTER PANEL ===== -->
                <div id="registerPanel" class="form-panel">
                    <p class="form-eyebrow">Pendaftaran Anggota</p>
                    <h1 class="form-title">Buat Akun Baru</h1>
                    <p class="form-subtitle">Daftar untuk mengakses layanan perpustakaan kampus</p>

                    <div class="error-message" id="registerErrorMessage">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <span id="registerErrorText"></span>
                    </div>

                    <form action="{{ route('publik.register.store') }}" method="POST" id="registerFormSubmit">
                        @csrf

                        <div class="form-group">
                            <label for="reg_nama" class="form-label">Nama Lengkap</label>
                            <input type="text" id="reg_nama" name="nama" class="form-input" placeholder="Nama lengkap"
                                required autocomplete="name">
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="reg_nim" class="form-label">NIM</label>
                                <input type="text" id="reg_nim" name="nim" class="form-input" placeholder="NIM" required
                                    autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label for="reg_no_telepon" class="form-label">No. Telepon</label>
                                <input type="tel" id="reg_no_telepon" name="no_telepon" class="form-input"
                                    placeholder="08123456789" autocomplete="tel">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="reg_jurusan" class="form-label">Jurusan</label>
                            <select id="reg_jurusan" name="jurusan" class="form-input" required>
                                <option value="" disabled selected>Pilih Jurusan</option>
                                <option value="Teknik Informatika (TI)">Teknik Informatika (TI)</option>
                                <option value="Sistem Informatika (SI)">Sistem Informatika (SI)</option>
                                <option value="Desain Komunikasi Visual (DKV)">Desain Komunikasi Visual (DKV)</option>
                                <option value="Teknik Sipil (TS)">Teknik Sipil (TS)</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="reg_email" class="form-label">Email</label>
                            <input type="email" id="reg_email" name="email" class="form-input"
                                placeholder="contoh: nama@example.com" required autocomplete="email">
                        </div>

                        <button type="submit" class="login-button">
                            <i class="fas fa-user-plus mr-2"></i> Register Mahasiswa
                        </button>
                    </form>

                    <div class="register-info">
                        <i class="fas fa-info-circle"></i>
                        <p>Setelah mendaftar, akun Anda akan diverifikasi oleh admin. Anda akan dapat menggunakan
                            layanan perpustakaan setelah akun disetujui.</p>
                    </div>

                    <p class="switch-form-text">
                        Sudah punya akun? <a onclick="showLogin()">Masuk di sini</a>
                    </p>
                </div>
            </div>

            <div class="login-right">
                <svg viewBox="0 0 380 460" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <radialGradient id="glow" cx="50%" cy="38%" r="55%">
                            <stop offset="0%" stop-color="#2A4E80" stop-opacity="0.9" />
                            <stop offset="100%" stop-color="#2A4E80" stop-opacity="0" />
                        </radialGradient>
                        <pattern id="dots" width="18" height="18" patternUnits="userSpaceOnUse">
                            <circle cx="1" cy="1" r="1" fill="#3E5C87" opacity="0.4" />
                        </pattern>
                    </defs>

                    <rect x="0" y="0" width="380" height="460" fill="url(#dots)" />
                    <circle cx="190" cy="175" r="200" fill="url(#glow)" />

                    <!-- concentric seal rings -->
                    <circle cx="190" cy="175" r="108" fill="none" stroke="#C9A85B" stroke-width="1" opacity="0.55" />
                    <circle cx="190" cy="175" r="96" fill="none" stroke="#C9A85B" stroke-width="1" opacity="0.35" />

                    <!-- laurel left -->
                    <g stroke="#D9B565" stroke-width="2.2" fill="none" stroke-linecap="round" opacity="0.9">
                        <path d="M148 220 C130 200 128 165 140 130" />
                        <path d="M140 210 L122 202" />
                        <path d="M136 190 L116 184" />
                        <path d="M134 168 L114 165" />
                        <path d="M137 146 L119 138" />
                    </g>
                    <!-- laurel right -->
                    <g stroke="#D9B565" stroke-width="2.2" fill="none" stroke-linecap="round" opacity="0.9">
                        <path d="M232 220 C250 200 252 165 240 130" />
                        <path d="M240 210 L258 202" />
                        <path d="M244 190 L264 184" />
                        <path d="M246 168 L266 165" />
                        <path d="M243 146 L261 138" />
                    </g>

                    <!-- open book emblem -->
                    <g transform="translate(190 178)">
                        <path
                            d="M-46 -8 C-30 -18 -12 -18 0 -8 C12 -18 30 -18 46 -8 L46 30 C30 20 12 20 0 30 C-12 20 -30 20 -46 30 Z"
                            fill="#F2EDE1" stroke="#0F2A4D" stroke-width="1.5" />
                        <line x1="0" y1="-8" x2="0" y2="30" stroke="#0F2A4D" stroke-width="1.2" opacity="0.5" />
                        <line x1="-34" y1="-3" x2="-8" y2="-6" stroke="#0F2A4D" stroke-width="1" opacity="0.35" />
                        <line x1="-34" y1="6" x2="-8" y2="3" stroke="#0F2A4D" stroke-width="1" opacity="0.35" />
                        <line x1="-34" y1="15" x2="-8" y2="12" stroke="#0F2A4D" stroke-width="1" opacity="0.35" />
                        <line x1="34" y1="-3" x2="8" y2="-6" stroke="#0F2A4D" stroke-width="1" opacity="0.35" />
                        <line x1="34" y1="6" x2="8" y2="3" stroke="#0F2A4D" stroke-width="1" opacity="0.35" />
                        <line x1="34" y1="15" x2="8" y2="12" stroke="#0F2A4D" stroke-width="1" opacity="0.35" />
                    </g>

                    <!-- ribbon -->
                    <g transform="translate(190 300)">
                        <path d="M-70 0 L70 0 L58 14 L70 28 L-70 28 L-58 14 Z" fill="#16385E" stroke="#D9B565"
                            stroke-width="1" />
                        <text x="0" y="19" font-family="Source Serif 4, serif" font-size="14" font-weight="700"
                            fill="#F2EDE1" text-anchor="middle" letter-spacing="2">SIPUSAKA</text>
                    </g>

                    <!-- campus silhouette baseline -->
                    <g fill="#16385E" opacity="0.9">
                        <rect x="20" y="410" width="340" height="4" />
                        <rect x="40" y="378" width="16" height="32" />
                        <rect x="66" y="392" width="16" height="18" />
                        <rect x="150" y="360" width="30" height="50" />
                        <polygon points="150,360 165,344 180,360" />
                        <rect x="220" y="386" width="14" height="24" />
                        <rect x="242" y="372" width="14" height="38" />
                        <rect x="300" y="388" width="16" height="22" />
                    </g>
                </svg>
            </div>
        </div>
    </div>

    <div class="modal-overlay" id="successModal">
        <div class="modal-card">
            <div class="modal-icon">
                <i class="fas fa-check"></i>
            </div>
            <h3 class="modal-title">Pendaftaran Berhasil!</h3>
            <p class="modal-text">Akun Anda sedang menunggu persetujuan admin. Anda akan dapat menggunakan layanan
                perpustakaan setelah akun disetujui.</p>
            <button class="modal-button" onclick="closeSuccessModal()">Mengerti</button>
        </div>
    </div>

    <script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
    <script>
    function showLogin() {
        document.getElementById('loginPanel').classList.add('active');
        document.getElementById('registerPanel').classList.remove('active');
    }

    function showRegister() {
        document.getElementById('loginPanel').classList.remove('active');
        document.getElementById('registerPanel').classList.add('active');
    }

    function showSuccessModal() {
        document.getElementById('successModal').classList.add('show');
    }

    function closeSuccessModal() {
        document.getElementById('successModal').classList.remove('show');
        showLogin();
    }

    $(function() {
        $('#token').on('input', function() {
            this.value = this.value.toUpperCase();
        });

        $('#toggleToken').on('click', function() {
            const input = document.getElementById('token');
            const icon = $(this).find('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.removeClass('fa-eye').addClass('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.removeClass('fa-eye-slash').addClass('fa-eye');
            }
        });

        $('#registerFormSubmit').on('submit', function(e) {
            e.preventDefault();

            const form = this;
            const formData = new FormData(form);
            const errorDiv = $('#registerErrorMessage');
            const errorText = $('#registerErrorText');

            if (!formData.get('nama') || !formData.get('nim') || !formData.get('jurusan') || !formData
                .get('email')) {
                errorText.text('Nama, NIM, Jurusan, dan Email harus diisi.');
                errorDiv.addClass('show');
                setTimeout(() => {
                    errorDiv.removeClass('show');
                }, 5000);
                return;
            }

            $.ajax({
                url: form.action,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        form.reset();
                        showSuccessModal();
                    } else {
                        errorText.text(response.message ||
                            'Terjadi kesalahan. Silakan coba lagi.');
                        errorDiv.addClass('show');
                        setTimeout(() => {
                            errorDiv.removeClass('show');
                        }, 5000);
                    }
                },
                error: function(xhr) {
                    const response = xhr.responseJSON;
                    errorText.text(response?.message ||
                        'Terjadi kesalahan koneksi. Silakan coba lagi.');
                    errorDiv.addClass('show');
                    setTimeout(() => {
                        errorDiv.removeClass('show');
                    }, 5000);
                }
            });
        });
    });

    document.getElementById('successModal').addEventListener('click', function(e) {
        if (e.target === this) closeSuccessModal();
    });
    </script>

</body>

</html>
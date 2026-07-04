<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Mahasiswa | Sipusaka</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Inter', sans-serif;
        min-height: 100vh;
        background: linear-gradient(135deg, #EDE9FE 0%, #FCE7F3 50%, #DBEAFE 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 20px;
        margin: 0;
    }

    /* Outer wrapper creates the soft colorful glow around the card */
    .auth-wrapper {
        position: relative;
        width: 100%;
        max-width: 1000px;
    }

    .auth-wrapper::before {
        content: "";
        position: absolute;
        inset: -18px;
        border-radius: 40px;
        background: linear-gradient(120deg, #C7B6FF 0%, #FBC7E0 45%, #BFDBFE 100%);
        filter: blur(18px);
        opacity: 0.75;
        z-index: 0;
    }

    .login-container {
        position: relative;
        z-index: 1;
        width: 100%;
        min-height: 560px;
        background: #ffffff;
        border-radius: 28px;
        box-shadow: 0 25px 60px rgba(79, 70, 229, 0.18);
        overflow: hidden;
        display: flex;
    }

    .login-left {
        flex: 1;
        padding: 56px 64px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .login-right {
        flex: 1;
        position: relative;
        background: radial-gradient(circle at 30% 50%, #3B57D9 0%, #2E3F9E 60%, #1E2A73 100%);
        border-radius: 0 28px 28px 0;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .login-right svg {
        width: 92%;
        height: auto;
        position: relative;
        z-index: 1;
    }

    .form-title {
        font-size: 22px;
        font-weight: 700;
        color: #1F2937;
        margin-bottom: 28px;
    }

    .form-subtitle {
        font-size: 13.5px;
        color: #6B7280;
        margin-bottom: 22px;
        line-height: 1.5;
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
        font-size: 13px;
        font-weight: 600;
        color: #374151;
    }

    .input-wrap {
        position: relative;
    }

    .form-input {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid #E5E7EB;
        border-radius: 10px;
        font-size: 14.5px;
        transition: all 0.2s ease;
        font-family: 'Inter', sans-serif;
        min-height: 46px;
        background: #F9FAFB;
    }

    .form-input:focus {
        outline: none;
        border-color: #4F46E5;
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.12);
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
        color: #9CA3AF;
        cursor: pointer;
        font-size: 15px;
        padding: 4px;
        line-height: 1;
    }

    .toggle-visibility:hover {
        color: #4F46E5;
    }

    .remember-row {
        margin: 6px 0 24px;
    }

    .remember-me {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #4B5563;
        font-size: 13.5px;
        cursor: pointer;
        user-select: none;
    }

    .remember-me input {
        width: 16px;
        height: 16px;
        accent-color: #4F46E5;
        cursor: pointer;
    }

    .forgot-link {
        color: #4F46E5;
        text-decoration: none;
        font-weight: 500;
        font-size: 12.5px;
    }

    .forgot-link:hover {
        text-decoration: underline;
    }

    .login-button {
        width: 100%;
        padding: 13px 20px;
        background: linear-gradient(135deg, #4F46E5, #6366F1);
        color: white;
        border: none;
        border-radius: 10px;
        font-size: 14.5px;
        font-weight: 600;
        letter-spacing: 0.02em;
        cursor: pointer;
        transition: all 0.2s ease;
        min-height: 48px;
    }

    .login-button:hover {
        transform: translateY(-1px);
        box-shadow: 0 10px 20px rgba(79, 70, 229, 0.3);
    }

    .switch-form-text {
        text-align: left;
        margin-top: 18px;
        font-size: 13px;
        color: #6B7280;
    }

    .switch-form-text a {
        color: #4F46E5;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
    }

    .switch-form-text a:hover {
        text-decoration: underline;
    }

    .error-message {
        background: #FEE2E2;
        color: #DC2626;
        padding: 11px 14px;
        border-radius: 10px;
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
        background: #EFF6FF;
        border-radius: 10px;
        padding: 13px 14px;
        margin-top: 16px;
    }

    .register-info i {
        flex-shrink: 0;
        color: #2563EB;
        margin-top: 2px;
        font-size: 13px;
    }

    .register-info p {
        font-size: 12px;
        color: #1E40AF;
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

    /* Success / error modal */
    .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(31, 41, 55, 0.5);
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
        background: white;
        border-radius: 20px;
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
            transform: scale(0.9) translateY(10px);
        }

        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }

    .modal-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: #D1FAE5;
        color: #059669;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 26px;
        margin: 0 auto 18px;
    }

    .modal-icon.error {
        background: #FEE2E2;
        color: #DC2626;
    }

    .modal-title {
        font-size: 19px;
        font-weight: 700;
        color: #1F2937;
        margin-bottom: 8px;
    }

    .modal-text {
        font-size: 13.5px;
        color: #6B7280;
        line-height: 1.6;
        margin-bottom: 22px;
    }

    .modal-button {
        width: 100%;
        padding: 11px 20px;
        background: linear-gradient(135deg, #4F46E5, #7C3AED);
        color: white;
        border: none;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        font-family: 'Inter', sans-serif;
    }

    /* ============ MOBILE ============ */
    @media (max-width: 768px) {
        body {
            padding: 0;
            display: block;
            background: linear-gradient(135deg, #EDE9FE 0%, #FCE7F3 50%, #DBEAFE 100%);
        }

        .auth-wrapper {
            max-width: 100%;
            min-height: 100vh;
        }

        .auth-wrapper::before {
            display: none;
        }

        .login-container {
            flex-direction: column;
            border-radius: 0;
            min-height: 100vh;
            box-shadow: none;
        }

        .login-right {
            border-radius: 0;
            min-height: 240px;
            flex: none;
            order: -1;
        }

        .login-right svg {
            width: 70%;
        }

        .login-left {
            padding: 30px 24px 40px;
            border-radius: 24px 24px 0 0;
            margin-top: -24px;
            background: #ffffff;
            position: relative;
            z-index: 2;
        }

        .form-title {
            font-size: 20px;
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
            font-size: 19px;
        }
    }
    </style>
</head>

<body>
    <div class="auth-wrapper">
        <div class="login-container">
            <div class="login-left">
                <!-- ===== LOGIN PANEL ===== -->
                <div id="loginPanel" class="form-panel active">
                    <h1 class="form-title">Masuk ke Akun Anda</h1>

                    <div class="error-message" id="loginErrorMessage">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <span>Kredensial login tidak valid. Silakan coba lagi.</span>
                    </div>

                    <form action="{{ route('mahasiswa.login.submit') }}" method="post">
                        @csrf

                        <div class="form-group">
                            <div class="form-label-row">
                                <label for="nim" class="form-label">NIM</label>
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
                            SIGN IN
                        </button>
                    </form>

                    <p class="switch-form-text">
                        Belum terdaftar? <a onclick="showRegister()">Daftar sekarang</a>
                    </p>
                </div>

                <!-- ===== REGISTER PANEL ===== -->
                <div id="registerPanel" class="form-panel">
                    <h1 class="form-title">Buat Akun Baru</h1>
                    <p class="form-subtitle">Daftar untuk mengakses layanan perpustakaan</p>

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
                <svg viewBox="0 0 400 440" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <radialGradient id="glow" cx="50%" cy="45%" r="60%">
                            <stop offset="0%" stop-color="#5B79FF" stop-opacity="0.9" />
                            <stop offset="100%" stop-color="#5B79FF" stop-opacity="0" />
                        </radialGradient>
                    </defs>

                    <circle cx="200" cy="200" r="180" fill="url(#glow)" opacity="0.5" />

                    <path d="M200 200 L120 130 M200 200 L290 110 M200 200 L100 260 M200 200 L300 280" stroke="#8EA3FF"
                        stroke-width="1.5" opacity="0.5" />

                    <circle cx="120" cy="130" r="30" fill="#3B82F6" opacity="0.95" />
                    <text x="120" y="138" font-family="Inter" font-size="22" fill="white" text-anchor="middle">
                        <tspan>&#128421;</tspan>
                    </text>

                    <circle cx="290" cy="110" r="32" fill="#F59E0B" opacity="0.95" />
                    <text x="290" y="119" font-family="Inter" font-size="24" fill="white" text-anchor="middle">
                        <tspan>&#9993;</tspan>
                    </text>

                    <circle cx="100" cy="260" r="30" fill="#EC4899" opacity="0.95" />
                    <text x="100" y="269" font-family="Inter" font-size="22" fill="white" text-anchor="middle">
                        <tspan>&#128227;</tspan>
                    </text>

                    <circle cx="300" cy="280" r="32" fill="#EAB308" opacity="0.95" />
                    <text x="300" y="290" font-family="Inter" font-size="24" fill="white" text-anchor="middle">
                        <tspan>&#128200;</tspan>
                    </text>

                    <circle cx="220" cy="330" r="26" fill="#EF4444" opacity="0.95" />
                    <text x="220" y="338" font-family="Inter" font-size="20" fill="white" text-anchor="middle">
                        <tspan>&#128222;</tspan>
                    </text>

                    <ellipse cx="200" cy="215" rx="46" ry="86" fill="#1E293B" />
                    <circle cx="200" cy="118" r="26" fill="#FBCFE8" />

                    <path d="M330 40 Q360 20 380 60 Q350 70 330 40 Z" fill="#2C3E8C" opacity="0.5" />
                    <path d="M20 380 Q50 360 60 400 Q30 410 20 380 Z" fill="#2C3E8C" opacity="0.5" />
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
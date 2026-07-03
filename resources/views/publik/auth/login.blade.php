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
        background: linear-gradient(135deg, #373461 0%, #7C3AED 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .login-container {
        width: 100%;
        max-width: 1200px;
        min-height: 600px;
        max-height: 90vh;
        background: white;
        border-radius: 24px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        overflow: hidden;
        display: flex;
    }

    .login-left {
        flex: 1;
        padding: 60px;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        overflow-y: auto;
        max-height: 90vh;
    }

    .login-right {
        flex: 1;
        background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .form-title {
        font-size: 32px;
        font-weight: 700;
        color: #1F2937;
        margin-bottom: 12px;
    }

    .form-subtitle {
        font-size: 16px;
        color: #6B7280;
        margin-bottom: 30px;
    }

    /* Toggle Tabs */
    .auth-tabs {
        display: flex;
        gap: 12px;
        margin-bottom: 30px;
        background: #F3F4F6;
        padding: 6px;
        border-radius: 12px;
    }

    .tab-btn {
        flex: 1;
        padding: 12px 20px;
        border: none;
        background: transparent;
        color: #6B7280;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .tab-btn.active {
        background: white;
        color: #4F46E5;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .tab-btn:hover:not(.active) {
        color: #374151;
    }

    /* Form Containers */
    .form-container {
        display: none;
    }

    .form-container.active {
        display: block;
    }

    /* Login Form Styles */
    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        font-size: 14px;
        font-weight: 500;
        color: #374151;
        margin-bottom: 8px;
    }

    .form-input {
        width: 100%;
        padding: 14px 20px;
        border: 1px solid #E5E7EB;
        border-radius: 12px;
        font-size: 16px;
        transition: all 0.3s ease;
        font-family: 'Inter', sans-serif;
    }

    .form-input:focus {
        outline: none;
        border-color: #4F46E5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }

    .login-button {
        width: 100%;
        padding: 14px 20px;
        background: linear-gradient(135deg, #4F46E5, #6366F1);
        color: white;
        border: none;
        border-radius: 12px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-top: 12px;
    }

    .login-button:hover {
        background: linear-gradient(135deg, #4338CA, #5B4FE0);
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(79, 70, 229, 0.3);
    }

    /* Register Form Styles */
    .register-form-label {
        display: block;
        font-size: 12px;
        font-weight: 700;
        color: #475569;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        margin-bottom: 8px;
    }

    .register-input {
        width: 100%;
        padding: 14px 20px;
        border: 1px solid #E5E7EB;
        border-radius: 12px;
        font-size: 15px;
        font-weight: 500;
        font-family: 'Inter', sans-serif;
        transition: all 0.3s ease;
    }

    .register-input:focus {
        outline: none;
        border-color: #4F46E5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }

    .register-button {
        width: 100%;
        padding: 14px 20px;
        background: linear-gradient(135deg, #4F46E5, #6366F1);
        color: white;
        border: none;
        border-radius: 12px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-top: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .register-button:hover {
        background: linear-gradient(135deg, #4338CA, #5B4FE0);
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(79, 70, 229, 0.3);
    }

    .illustration {
        width: 80%;
        height: 80%;
        opacity: 0.9;
    }

    .error-message {
        background: #FEE2E2;
        color: #DC2626;
        padding: 12px 16px;
        border-radius: 12px;
        margin-bottom: 20px;
        font-size: 14px;
        display: none;
    }

    .error-message.show {
        display: block;
    }

    .register-info {
        display: flex;
        gap: 10px;
        align-items: flex-start;
        background: #EFF6FF;
        border-radius: 12px;
        padding: 16px;
        margin-top: 20px;
    }

    .register-info i {
        flex-shrink: 0;
        width: 18px;
        height: 18px;
        color: #2563EB;
        margin-top: 2px;
    }

    .register-info p {
        font-size: 13px;
        color: #1E40AF;
        margin: 0;
        line-height: 1.6;
    }

    @media (max-width: 768px) {
        .login-container {
            flex-direction: column;
            height: auto;
            max-height: 100vh;
        }

        .login-right {
            display: none;
        }

        .login-left {
            padding: 40px 20px;
        }

        .form-title {
            font-size: 28px;
        }

        .form-subtitle {
            font-size: 14px;
        }

        .auth-tabs {
            margin-bottom: 20px;
        }

        .tab-btn {
            padding: 10px 16px;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-input,
        .register-input {
            padding: 12px 16px;
            font-size: 15px;
        }

        .form-label,
        .register-form-label {
            font-size: 13px;
            margin-bottom: 6px;
        }

        .login-button,
        .register-button {
            padding: 12px 16px;
            font-size: 15px;
        }

        .register-info {
            padding: 12px;
            margin-top: 16px;
        }

        .register-info p {
            font-size: 12px;
        }
    }

    @media (max-width: 480px) {
        body {
            padding: 0;
        }

        .login-container {
            border-radius: 0;
            min-height: 100vh;
            max-height: none;
        }

        .login-left {
            padding: 24px 16px;
            max-height: none;
        }

        .form-title {
            font-size: 24px;
        }

        .form-subtitle {
            font-size: 13px;
        }

        .auth-tabs {
            margin-bottom: 16px;
        }

        .tab-btn {
            padding: 8px 12px;
            font-size: 13px;
        }

        .form-group {
            margin-bottom: 12px;
        }

        .form-input,
        .register-input {
            padding: 10px 14px;
            font-size: 14px;
        }

        .form-label,
        .register-form-label {
            font-size: 12px;
            margin-bottom: 5px;
        }

        .login-button,
        .register-button {
            padding: 10px 14px;
            font-size: 14px;
        }

        .register-info {
            padding: 10px;
            margin-top: 12px;
        }

        .register-info p {
            font-size: 11px;
        }
    }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-left">
            <!-- Toggle Tabs -->
            <div class="auth-tabs">
                <button class="tab-btn active" onclick="showLogin()">
                    <i class="fas fa-sign-in-alt"></i>
                    Login
                </button>
                <button class="tab-btn" onclick="showRegister()">
                    <i class="fas fa-user-plus"></i>
                    Register
                </button>
            </div>

            <!-- Login Form -->
            <div id="loginForm" class="form-container active">
                <h1 class="form-title">Login Mahasiswa</h1>
                <p class="form-subtitle">Masukkan NIM dan token mahasiswa Anda</p>

                <div class="error-message" id="loginErrorMessage">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <span>Kredensial login tidak valid. Silakan coba lagi.</span>
                </div>

                <form action="{{ route('mahasiswa.login.submit') }}" method="post">
                    @csrf

                    <div class="form-group">
                        <label for="nim" class="form-label">NIM</label>
                        <input type="text" id="nim" name="nim" class="form-input" placeholder="Contoh: 20260001"
                            value="{{ old('nim') }}" required autofocus>
                    </div>

                    <div class="form-group">
                        <label for="token" class="form-label">Token Referral</label>
                        <input type="password" id="token" name="token" class="form-input" placeholder="6 digit token"
                            maxlength="6" required>
                    </div>

                    <button type="submit" class="login-button">
                        <i class="fas fa-sign-in-alt mr-2"></i> Login Mahasiswa
                    </button>
                </form>

                <a href="#" class="forgot-link">Lupa password?</a>
            </div>

            <!-- Register Form -->
            <div id="registerForm" class="form-container">
                <h1 class="form-title">Register Mahasiswa</h1>
                <p class="form-subtitle">Daftar untuk mengakses layanan perpustakaan</p>

                <div class="error-message" id="registerErrorMessage">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <span id="registerErrorText"></span>
                </div>

                <form action="{{ route('publik.register.store') }}" method="POST" id="registerFormSubmit">
                    @csrf

                    <div class="form-group">
                        <label for="reg_nama" class="register-form-label">Nama Lengkap</label>
                        <input type="text" id="reg_nama" name="nama" class="register-input"
                            placeholder="Masukkan nama lengkap" required autocomplete="name">
                    </div>

                    <div class="form-group">
                        <label for="reg_nim" class="register-form-label">NIM</label>
                        <input type="text" id="reg_nim" name="nim" class="register-input" placeholder="Masukkan NIM"
                            required autocomplete="off">
                    </div>

                    <div class="form-group">
                        <label for="reg_jurusan" class="register-form-label">Jurusan</label>
                        <select id="reg_jurusan" name="jurusan" class="register-input" required>
                            <option value="" disabled selected>Pilih Jurusan</option>
                            <option value="Teknik Informatika (TI)">Teknik Informatika (TI)</option>
                            <option value="Sistem Informatika (SI)">Sistem Informatika (SI)</option>
                            <option value="Desain Komunikasi Visual (DKV)">Desain Komunikasi Visual (DKV)</option>
                            <option value="Teknik Sipil (TS)">Teknik Sipil (TS)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="reg_no_telepon" class="register-form-label">No. Telepon (Opsional)</label>
                        <input type="tel" id="reg_no_telepon" name="no_telepon" class="register-input"
                            placeholder="Contoh: 08123456789" autocomplete="tel">
                    </div>

                    <div class="form-group">
                        <label for="reg_email" class="register-form-label">Email</label>
                        <input type="email" id="reg_email" name="email" class="register-input"
                            placeholder="contoh: nama@example.com" required autocomplete="email">
                    </div>

                    <button type="submit" class="register-button">
                        <i class="fas fa-user-plus mr-2"></i> Register Mahasiswa
                    </button>
                </form>

                <div class="register-info">
                    <i class="fas fa-info-circle"></i>
                    <p>Setelah mendaftar, akun Anda akan diverifikasi oleh admin. Anda akan dapat menggunakan layanan
                        perpustakaan setelah akun disetujui.</p>
                </div>
            </div>
        </div>

        <div class="login-right">
            <svg class="illustration" viewBox="0 0 400 400" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <linearGradient id="gradient1" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" style="stop-color:#ffffff;stop-opacity:0.2" />
                        <stop offset="100%" style="stop-color:#ffffff;stop-opacity:0.05" />
                    </linearGradient>
                </defs>

                <circle cx="200" cy="200" r="150" fill="url(#gradient1)" />

                <rect x="120" y="140" width="160" height="120" rx="8" fill="white" opacity="0.9" />

                <circle cx="150" cy="180" r="15" fill="#4F46E5" opacity="0.8" />
                <circle cx="200" cy="180" r="15" fill="#7C3AED" opacity="0.8" />
                <circle cx="250" cy="180" r="15" fill="#EC4899" opacity="0.8" />

                <rect x="140" y="210" width="120" height="8" rx="4" fill="#E5E7EB" />
                <rect x="140" y="225" width="80" height="8" rx="4" fill="#E5E7EB" />
                <rect x="140" y="240" width="100" height="8" rx="4" fill="#E5E7EB" />

                <circle cx="320" cy="120" r="30" fill="white" opacity="0.3" />
                <circle cx="80" cy="280" r="20" fill="white" opacity="0.2" />

                <path d="M280 100 Q320 80 340 120" stroke="white" stroke-width="3" fill="none" opacity="0.5" />
                <path d="M100 300 Q60 320 40 280" stroke="white" stroke-width="3" fill="none" opacity="0.5" />

                <text x="200" y="320" font-family="Inter" font-size="18" font-weight="600" fill="white"
                    text-anchor="middle">
                    Check Your Project Progress
                </text>
                <text x="200" y="345" font-family="Inter" font-size="14" fill="white" opacity="0.8"
                    text-anchor="middle">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                </text>
            </svg>
        </div>
    </div>

    <script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
    <script>
    function showLogin() {
        document.getElementById('loginForm').classList.add('active');
        document.getElementById('registerForm').classList.remove('active');
        document.querySelectorAll('.tab-btn')[0].classList.add('active');
        document.querySelectorAll('.tab-btn')[1].classList.remove('active');
    }

    function showRegister() {
        document.getElementById('loginForm').classList.remove('active');
        document.getElementById('registerForm').classList.add('active');
        document.querySelectorAll('.tab-btn')[0].classList.remove('active');
        document.querySelectorAll('.tab-btn')[1].classList.add('active');
    }

    $(function() {
        $('#token').on('input', function() {
            this.value = this.value.toUpperCase();
        });

        $('#registerFormSubmit').on('submit', function(e) {
            e.preventDefault();

            const form = this;
            const formData = new FormData(form);
            const errorDiv = $('#registerErrorMessage');
            const errorText = $('#registerErrorText');

            // Basic validation
            if (!formData.get('nama') || !formData.get('nim') || !formData.get('jurusan') || !formData
                .get('email')) {
                errorText.text('Nama, NIM, Jurusan, dan Email harus diisi.');
                errorDiv.addClass('show');
                setTimeout(() => {
                    errorDiv.removeClass('show');
                }, 5000);
                return;
            }

            // Submit form via AJAX
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
                        // Reset form and show success message
                        form.reset();
                        alert(
                            'Pendaftaran berhasil! Akun Anda sedang menunggu persetujuan admin.'
                        );
                        showLogin();
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
    </script>
</body>

</html>
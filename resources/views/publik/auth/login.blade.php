<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Mahasiswa | Sipusaka</title>

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,600,700&display=fallback">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">

    <style>
    body.login-page {
        min-height: 100vh;
        background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 55%, #2563eb 100%);
    }

    .login-box {
        width: 430px;
    }

    .login-card-body {
        border-radius: 20px;
        box-shadow: 0 20px 45px rgba(15, 23, 42, 0.28);
        overflow: hidden;
        border: 1px solid #dbeafe;
        backdrop-filter: blur(6px);
    }

    .brand-panel {
        background: linear-gradient(135deg, #0f172a, #1e3a8a);
        color: #fff;
        padding: 30px 25px;
        text-align: center;
    }

    .brand-panel .brand-icon {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        background: rgba(255, 255, 255, .15);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 34px;
        margin-bottom: 12px;
        border: 2px solid rgba(255, 255, 255, .4);
        box-shadow: 0 8px 24px rgba(15, 23, 42, .25);
    }

    .card-body {
        background: #ffffff;
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

    .btn-login {
        height: 46px;
        font-weight: 600;
        border-radius: 10px;
        background: linear-gradient(135deg, #1d4ed8, #1e40af);
        border: none;
        box-shadow: 0 10px 20px rgba(30, 64, 175, 0.3);
    }

    .btn-login:hover {
        background: linear-gradient(135deg, #1e40af, #1e3a8a);
    }

    .help-box {
        background: #f8fbff;
        border-left: 4px solid #1d4ed8;
        border-radius: 10px;
        padding: 12px 14px;
        font-size: 14px;
        color: #334155;
    }

    @media (max-width: 576px) {
        .login-box {
            width: 92%;
        }
    }
    </style>
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="card login-card-body p-0">
            <div class="brand-panel">
                <div class="brand-icon">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <h3 class="mb-1 font-weight-bold">Sipusaka Mahasiswa v.2.1</h3>
                <p class="mb-0">Panel riwayat peminjaman perpustakaan</p>
            </div>

            <div class="card-body p-4">
                <div class="text-center mb-4">
                    <h5 class="font-weight-bold mb-1">Login Dashboard</h5>
                    <span class="text-muted">Masukkan NIM dan token mahasiswa Anda</span>
                </div>

                @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <i class="icon fas fa-ban mr-1"></i>
                    {{ $errors->first('msg') ?: $errors->first() }}
                </div>
                @endif

                <form action="{{ route('mahasiswa.login.submit') }}" method="post">
                    @csrf

                    <label for="nim" class="font-weight-semibold">NIM</label>
                    <div class="input-group mb-3">
                        <input id="nim" type="text" class="form-control" placeholder="Contoh: 20260001" name="nim"
                            value="{{ old('nim') }}" required autofocus>
                        <div class="input-group-append">
                            <div class="input-group-text bg-white">
                                <span class="fas fa-id-card text-primary"></span>
                            </div>
                        </div>
                    </div>

                    <label for="token" class="font-weight-semibold">Token Referral</label>
                    <div class="input-group mb-3">
                        <input id="token" type="password" class="form-control text-uppercase"
                            placeholder="6 digit token" name="token" maxlength="6" required>
                        <div class="input-group-append">
                            <div class="input-group-text bg-white">
                                <span class="fas fa-key text-primary"></span>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block btn-login">
                        <i class="fas fa-sign-in-alt mr-1"></i> Login Mahasiswa
                    </button>
                </form>

                <div class="help-box mt-4">
                    <div class="font-weight-bold mb-1">
                        <i class="fas fa-info-circle text-primary mr-1"></i> Informasi
                    </div>
                    Akun hanya dapat digunakan jika data mahasiswa sudah disetujui admin dan memiliki token referral
                    aktif.
                </div>

                <div class="row mt-4">
                    <div class="col-6">
                        <a href="{{ route('publik.katalog') }}" class="btn btn-outline-primary btn-block">
                            <i class="fas fa-book mr-1"></i> Katalog
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('publik.register.form') }}" class="btn btn-outline-primary btn-block">
                            <i class="fas fa-user-plus mr-1"></i> Daftar
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <p class="text-center text-white mt-3 mb-0">
            <small>&copy; {{ date('Y') }} Sipusaka. Sistem Informasi Perpustakaan.</small>
        </p>
    </div>

    <script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script>
    $(function() {
        $('#token').on('input', function() {
            this.value = this.value.toUpperCase();
        });
    });
    </script>
</body>

</html>
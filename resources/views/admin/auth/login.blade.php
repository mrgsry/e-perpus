<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin | SiPusaka</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        body {
            background: linear-gradient(135deg, #0f2444 0%, #1a3c6b 60%, #0d3460 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-wrapper { width: 100%; max-width: 440px; padding: 20px; }
        .brand-title { font-size: 2.2rem; font-weight: 800; color: white; }
        .brand-title span { color: #f0b429; }
        .card { border: none; border-radius: 20px; box-shadow: 0 25px 60px rgba(0,0,0,0.35); }
        .card-body { padding: 2.5rem; }
        .form-control {
            border-radius: 10px;
            padding: 12px 15px;
            border: 1.5px solid #e2e8f0;
            font-size: 14px;
        }
        .form-control:focus {
            border-color: #1a3c6b;
            box-shadow: 0 0 0 3px rgba(26,60,107,0.1);
        }
        .input-group-text {
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-right: none;
            color: #64748b;
            border-radius: 10px 0 0 10px;
        }
        .input-group .form-control { border-radius: 0 10px 10px 0; }
        .btn-masuk {
            background: linear-gradient(135deg, #1a3c6b, #234d82);
            border: none;
            border-radius: 10px;
            padding: 13px;
            font-size: 15px;
            font-weight: 700;
            transition: all 0.2s;
        }
        .btn-masuk:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 25px rgba(26,60,107,0.4);
        }
        .form-label { font-weight: 600; font-size: 13.5px; color: #374151; }
    </style>
</head>
<body>
<div class="login-wrapper">
    <div class="text-center mb-4">
        <div class="brand-title">📚 Si<span>Pusaka</span></div>
        <p class="text-white-50 mt-1" style="font-size:14px">Sistem Informasi Perpustakaan Digital v.2</p>
    </div>

    <div class="card">
        <div class="card-body">
            <h5 class="text-center mb-4 fw-bold" style="color:#0f2444">Login Dulu</h5>

            @if(session('error'))
                <div class="alert alert-danger d-flex align-items-center gap-2">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('admin.login.post') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input type="email" name="email"
                               class="form-control @error('email') is-invalid @enderror"
                               placeholder="admin@sipusaka.ac.id"
                               value="{{ old('email') }}" required autofocus>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" name="password"
                               class="form-control @error('password') is-invalid @enderror"
                               placeholder="••••••••" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <button type="submit" class="btn btn-masuk w-100 text-white">
                    <i class="fas fa-sign-in-alt me-2"></i>Masuk ke Dashboard
                </button>
            </form>
        </div>
    </div>

    <p class="text-center mt-3" style="color:rgba(255,255,255,0.4);font-size:12px">
        © {{ date('Y') }} SiPusaka — Perpustakaan Digital
    </p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
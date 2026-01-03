@extends('layouts.guest')

@section('content')
<div class="login-page-premium">
    <div class="login-card shadow-lg">
        <div class="login-header text-center">
            <div class="brand-wrapper mb-4">
                @if(isset($appSettings['app_logo']))
                    <img src="{{ asset('storage/' . $appSettings['app_logo']) }}" 
                         alt="Logo" 
                         class="app-logo-main"
                         width="80" 
                         height="80"
                         loading="eager">
                @else
                    <div class="logo-placeholder">
                        <i class="bi bi-shield-lock-fill"></i>
                    </div>
                @endif
            </div>
            <h4 class="app-name">{{ $appSettings['app_name'] ?? 'Nurul Ilmi Management' }}</h4>
            <p class="app-tagline">Portal Akademik Terpadu</p>
        </div>

        <div class="login-body">
            @if(session('error'))
                <div class="alert alert-danger border-0 shadow-sm mb-4">
                    <i class="bi bi-exclamation-circle-fill me-2"></i> {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="form-group mb-3">
                    <label class="form-label-premium">Username / Email</label>
                    <div class="input-group-premium">
                        <i class="bi bi-person icon-field"></i>
                        <input type="text" name="login" class="form-control-premium" placeholder="Masukkan ID Anda" required autofocus>
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label class="form-label-premium">Kata Sandi</label>
                    <div class="input-group-premium">
                        <i class="bi bi-key icon-field"></i>
                        <input type="password" name="password" id="password" class="form-control-premium" placeholder="********" required>
                        <button type="button" class="btn-toggle-pass" onclick="togglePass()">
                            <i class="bi bi-eye" id="eye-icon"></i>
                        </button>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input type="checkbox" name="remember" class="form-check-input" id="remember">
                        <label class="form-check-label small text-muted" for="remember">Ingat Saya</label>
                    </div>
                    <a href="#" class="forgot-link">Lupa Sandi?</a>
                </div>

                <button type="submit" class="btn-login-premium">
                    Masuk Sekarang <i class="bi bi-arrow-right-short ms-2"></i>
                </button>
            </form>
        </div>

        <div class="login-footer text-center">
            <p class="mb-0">&copy; {{ date('Y') }} {{ $appSettings['app_name'] ?? 'Nurul Ilmi' }}. All rights reserved.</p>
        </div>
    </div>
</div>

<style>
    :root {
        --primary-color: #4361ee;
        --secondary-color: #3f37c9;
        --text-dark: #1e293b;
        --text-muted: #64748b;
        --bg-soft: #f8fafc;
    }

    body.login-page {
        background: radial-gradient(circle at top left, #4361ee, #3a0ca3);
        height: 100vh;
        margin: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Inter', sans-serif;
    }

    .login-page-premium {
        width: 100%;
        max-width: 450px;
        padding: 20px;
    }

    .login-card {
        background: white;
        border-radius: 24px;
        padding: 40px;
        animation: fadeInScale 0.5s ease-out;
    }

    .app-logo-main {
        max-height: 80px;
        max-width: 100%;
        object-fit: contain;
    }

    .logo-placeholder {
        width: 80px;
        height: 80px;
        background: var(--bg-soft);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        color: var(--primary-color);
        font-size: 2.5rem;
    }

    .app-name {
        color: var(--text-dark);
        font-weight: 800;
        margin-bottom: 5px;
        font-size: 1.5rem;
    }

    .app-tagline {
        color: var(--text-muted);
        font-size: 0.9rem;
        margin-bottom: 30px;
    }

    .form-label-premium {
        font-weight: 700;
        font-size: 0.85rem;
        color: var(--text-dark);
        margin-bottom: 8px;
        display: block;
    }

    .input-group-premium {
        position: relative;
        display: flex;
        align-items: center;
    }

    .icon-field {
        position: absolute;
        left: 15px;
        color: #94a3b8;
        font-size: 1.2rem;
    }

    .form-control-premium {
        width: 100%;
        padding: 12px 12px 12px 45px;
        background: var(--bg-soft);
        border: 2px solid transparent;
        border-radius: 12px;
        transition: all 0.3s;
        font-weight: 500;
        color: #000 !important;
    }

    .form-control-premium::placeholder {
        color: #94a3b8;
        opacity: 1;
    }

    .form-control-premium:focus {
        border-color: var(--primary-color);
        background: white;
        color: #000 !important;
        outline: none;
        box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
    }

    .btn-toggle-pass {
        position: absolute;
        right: 15px;
        background: none;
        border: none;
        color: #94a3b8;
    }

    .btn-login-premium {
        width: 100%;
        padding: 14px;
        background: var(--primary-color);
        color: white;
        border: none;
        border-radius: 12px;
        font-weight: 700;
        font-size: 1rem;
        transition: all 0.3s;
        box-shadow: 0 4px 12px rgba(67, 97, 238, 0.3);
    }

    .btn-login-premium:hover {
        background: var(--secondary-color);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(67, 97, 238, 0.4);
    }

    .forgot-link {
        color: var(--primary-color);
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
    }

    .login-footer {
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid #f1f5f9;
        color: var(--text-muted);
        font-size: 0.8rem;
    }

    @keyframes fadeInScale {
        from { opacity: 0; transform: scale(0.9); }
        to { opacity: 1; transform: scale(1); }
    }
</style>

<script>
    function togglePass() {
        const pass = document.getElementById('password');
        const icon = document.getElementById('eye-icon');
        if (pass.type === 'password') {
            pass.type = 'text';
            icon.classList.replace('bi-eye', 'bi-eye-slash');
        } else {
            pass.type = 'password';
            icon.classList.replace('bi-eye-slash', 'bi-eye');
        }
    }
</script>
@endsection

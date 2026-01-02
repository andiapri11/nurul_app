@extends('layouts.guest')

@section('content')
<div class="login-container">
    <div class="login-wrapper">
        <!-- Left Side: Branding/Image -->
        <div class="login-brand-section">
            <div class="brand-content">
                <div class="brand-logo mb-4">
                    @if(isset($appSettings['app_logo']))
                        <img src="{{ asset('storage/' . $appSettings['app_logo']) }}" alt="Logo" class="img-fluid logo-img">
                    @else
                        <img src="{{ asset('template/dist/assets/img/AdminLTELogo.png') }}" alt="Logo" class="img-fluid logo-img">
                    @endif
                </div>
                <h1 class="brand-title">{{ $appSettings['app_name'] ?? 'Selamat Datang' }}</h1>
                <p class="brand-subtitle">Sistem Informasi Manajemen Sekolah Terpadu</p>
                <div class="brand-decoration">
                    <div class="circle circle-1"></div>
                    <div class="circle circle-2"></div>
                </div>
            </div>
        </div>

        <!-- Right Side: Login Form -->
        <div class="login-form-section">
            <div class="form-wrapper">
                <div class="text-center mb-4 d-md-none">
                    <img src="{{ asset('template/dist/assets/img/AdminLTELogo.png') }}" alt="Logo" class="logo-img-mobile mb-3">
                    <h2 class="fs-4 fw-bold text-primary">AdminLTE</h2>
                </div>

                <div class="form-header mb-4">
                    <h2 class="fw-bold text-dark">Login Akun</h2>
                    <p class="text-muted">Masuk untuk mengelola aktivitas akademik</p>
                </div>

                <form action="{{ route('login') }}" method="post" class="login-form">
                    @csrf
                    
                    <div class="form-floating mb-3">
                        <input id="loginUser" type="text" name="login" class="form-control @error('login') is-invalid @enderror" value="{{ old('login') }}" placeholder="Username / Email" required autofocus>
                        <label for="loginUser">Username atau Email</label>
                        @error('login')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-floating mb-4">
                        <input id="loginPassword" type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Kata Sandi" required>
                        <label for="loginPassword">Kata Sandi</label>
                        <span class="password-toggle" onclick="togglePassword()">
                            <i class="bi bi-eye" id="toggleIcon"></i>
                        </span>
                        @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="rememberMe">
                            <label class="form-check-label" for="rememberMe">Ingat Saya</label>
                        </div>
                        <a href="#" class="text-decoration-none text-primary fw-semibold fs-7">Lupa Password?</a>
                    </div>

                    <div class="d-grid gap-2 mb-4">
                        <button type="submit" class="btn btn-primary btn-lg gradient-btn">
                            Sign In <i class="bi bi-arrow-right ms-2"></i>
                        </button>
                    </div>

                    <p class="text-center text-muted fs-7 mb-0">
                        Belum punya akun? <a href="#" class="text-primary fw-semibold text-decoration-none">Hubungi Administrator</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');

    :root {
        --primary-gradient: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
        --glass-bg: rgba(255, 255, 255, 0.95);
        --brand-bg: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
    }

    body.login-page {
        background: #f1f5f9;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Plus Jakarta Sans', sans-serif; /* Fallback to system fonts */
        overflow-x: hidden;
    }

    .login-container {
        width: 100%;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem;
        background-color: #f8f9fa;
        background-image: 
            radial-gradient(at 0% 0%, hsla(253,16%,7%,1) 0, transparent 50%), 
            radial-gradient(at 50% 0%, hsla(225,39%,30%,1) 0, transparent 50%), 
            radial-gradient(at 100% 0%, hsla(339,49%,30%,1) 0, transparent 50%);
    }

    .login-wrapper {
        display: flex;
        width: 100%;
        max-width: 1000px;
        background: white;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
        min-height: 600px;
        animation: slideUp 0.6s ease-out;
    }

    /* Left Side */
    .login-brand-section {
        flex: 1;
        background: var(--brand-bg);
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 3rem;
        color: white;
        overflow: hidden;
    }

    .brand-content {
        position: relative;
        z-index: 2;
        text-align: center;
    }

    .logo-img {
        width: 120px;
        filter: drop-shadow(0 4px 6px rgba(0,0,0,0.1));
        animation: float 6s ease-in-out infinite;
    }

    .logo-img-mobile {
        width: 80px;
    }

    .brand-title {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 1rem;
        background: linear-gradient(to right, #fff, #94a3b8);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .brand-subtitle {
        font-size: 1.1rem;
        opacity: 0.8;
        font-weight: 300;
        line-height: 1.6;
    }

    /* Abstract shapes */
    .brand-decoration .circle {
        position: absolute;
        border-radius: 50%;
        opacity: 0.1;
    }

    .circle-1 {
        width: 300px;
        height: 300px;
        background: #4cc9f0;
        top: -50px;
        left: -50px;
        filter: blur(60px);
    }

    .circle-2 {
        width: 200px;
        height: 200px;
        background: #f72585;
        bottom: -50px;
        right: -50px;
        filter: blur(40px);
    }

    /* Right Side */
    .login-form-section {
        flex: 1;
        padding: 3rem;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .form-wrapper {
        width: 100%;
        max-width: 400px;
    }

    .form-control {
        border-radius: 12px;
        border: 2px solid #e2e8f0;
        padding: 1rem 0.75rem;
        height: 58px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #4361ee;
        box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
    }

    .form-floating > label {
        padding: 1rem 0.75rem;
    }

    .form-check-input {
        width: 1.25em;
        height: 1.25em;
        margin-top: 0.15em;
        border-color: #cbd5e1;
        cursor: pointer;
    }

    .form-check-input:checked {
        background-color: #4361ee;
        border-color: #4361ee;
    }

    .gradient-btn {
        background: var(--primary-gradient);
        border: none;
        color: white;
        font-weight: 600;
        border-radius: 12px;
        padding: 0.8rem;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .gradient-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(67, 97, 238, 0.3);
        color: white;
    }

    .password-toggle {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: #94a3b8;
        z-index: 10;
        padding: 8px;
    }

    /* Animations */
    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes float {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
        100% { transform: translateY(0px); }
    }

    /* Responsive */
    @media (max-width: 768px) {
        .login-wrapper {
            flex-direction: column;
            min-height: auto;
            max-width: 450px;
        }

        .login-brand-section {
            display: none;
        }
        
        .login-container {
            padding: 1rem;
            align-items: center; /* Center vertically on mobile too */
        }
        
        .login-form-section {
            padding: 2rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    function togglePassword() {
        const passwordInput = document.getElementById('loginPassword');
        const icon = document.getElementById('toggleIcon');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    }
</script>
@endpush
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    @if($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Login Gagal',
            text: '{{ $errors->first() }}',
            confirmButtonText: 'OK',
            confirmButtonColor: '#0d6efd'
        });
    @endif
</script>
@endpush

@extends('layouts.auth')

@section('title', 'Login - PlayStation Rental')

@section('content')
    <div class="split-layout">
        <!-- Visual Side (Left) -->
        <div class="visual-side">
            <div class="ps-room-art">
                <div class="floating-shapes">
                    <i class="fas fa-square shape shape-1"></i>
                    <i class="fas fa-circle shape shape-2"></i>
                    <i class="fas fa-times shape shape-3"></i>
                    <i class="fas fa-play shape shape-4"></i>
                </div>
                <div class="art-content">
                    <h1 class="art-title">Rental<br>PlayStation</h1>
                    <p class="art-subtitle">Nikmati permainan playStation dengan harga yang terjangkau</p>
                </div>
                <div class="glass-overlay"></div>
            </div>
        </div>

        <!-- Form Side (Right) -->
        <div class="form-side">
            <div class="auth-card-modern">
                <div class="auth-header">
                    <div class="auth-icon">
                        <i class="fab fa-playstation"></i>
                    </div>
                    <h2 class="auth-title">Selamat Datang Kembali!</h2>
                    <p class="auth-subtitle">Silahkan login untuk memulai sesi rental PlayStation Anda</p>
                </div>

                @if(session('status'))
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        <div>
                            @foreach($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('login.post') }}" class="login-form">
                    @csrf
                    
                    <div class="form-group">
                        <label for="email">Username / Email</label>
                        <div class="input-wrapper">
                            <input 
                                type="text" 
                                class="form-control @error('email') is-invalid @enderror" 
                                id="email" 
                                name="email" 
                                value="{{ old('email') }}"
                                placeholder="Masukkan username atau email Anda"
                                required 
                                autofocus
                            >
                            <i class="fas fa-user input-icon"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="input-wrapper">
                            <input 
                                type="password" 
                                class="form-control @error('password') is-invalid @enderror" 
                                id="password" 
                                name="password" 
                                placeholder="Masukkan kata sandi Anda"
                                required
                            >
                            <i class="fas fa-lock input-icon"></i>
                            <button type="button" class="password-toggle">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="form-check">
                        <div class="form-check-left">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">Simpan Password Saya</label>
                        </div>
                        <a href="{{ route('password.request') }}" class="auth-link">Lupa Password?</a>
                    </div>

                    <button type="submit" class="btn-auth btn-primary-auth btn-login">
                        <span>Masuk</span>
                        <i class="fas fa-arrow-right"></i>
                    </button>

                    <div class="auth-divider">
                        <span>Atau</span>
                    </div>

                    <a href="{{ route('auth.google.redirect') }}" class="btn-auth btn-google">
                        <img src="https://www.google.com/favicon.ico" alt="Google">
                        Login dengan Google
                    </a>

                    <div class="auth-bottom-links">
                        Belum Punya Akun? <a href="{{ route('register.show') }}" class="auth-link">Daftar Sekarang</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

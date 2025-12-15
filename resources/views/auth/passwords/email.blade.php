@extends('layouts.auth')

@section('title', 'Lupa Password - PlayStation Rental')

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
                    <h1 class="art-title">Ganti<br>Kembali Password Anda</h1>
                    <p class="art-subtitle">Kembali bermain game kesukaan anda</p>
                </div>
                <div class="glass-overlay"></div>
            </div>
        </div>

        <!-- Form Side (Right) -->
        <div class="form-side">
            <div class="auth-card-modern">
                <div class="auth-header">
                    <div class="auth-icon">
                        <i class="fas fa-key"></i>
                    </div>
                    <h2 class="auth-title">Lupa Kata Sandi?</h2>
                    <p class="auth-subtitle">Masukkan email Anda untuk menerima link reset</p>
                </div>

                @if (session('status'))
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

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf
                    
                    <div class="form-group">
                        <label for="email">Email</label>
                        <div class="input-wrapper">
                            <input 
                                type="email" 
                                class="form-control @error('email') is-invalid @enderror" 
                                id="email" 
                                name="email" 
                                value="{{ old('email') }}"
                                placeholder="Masukkan email terdaftar Anda"
                                required 
                                autofocus
                            >
                            <i class="fas fa-envelope input-icon"></i>
                        </div>
                    </div>

                    <button type="submit" class="btn-auth btn-primary-auth">
                        <span>Kirim Link Reset</span>
                        <i class="fas fa-paper-plane"></i>
                    </button>

                    <div class="auth-bottom-links">
                        Kembali ke <a href="{{ route('login.show') }}" class="auth-link">Halaman Login</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

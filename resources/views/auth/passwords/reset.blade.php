@extends('layouts.auth')

@section('title', 'Reset Password - PlayStation Rental')

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
                    <h1 class="art-title">Akses<br>Keamanan Anda</h1>
                    <p class="art-subtitle">Buatkan password yang kuat!</p>
                </div>
                <div class="glass-overlay"></div>
            </div>
        </div>

        <!-- Form Side (Right) -->
        <div class="form-side">
            <div class="auth-card-modern">
                <div class="auth-header">
                    <div class="auth-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h2 class="auth-title">Reset Password</h2>
                    <p class="auth-subtitle">Buat kata sandi baru untuk akun Anda</p>
                </div>

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

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    
                    <div class="form-group">
                        <label for="email">Email</label>
                        <div class="input-wrapper">
                            <input 
                                type="email" 
                                class="form-control @error('email') is-invalid @enderror" 
                                id="email" 
                                name="email" 
                                value="{{ $email ?? old('email') }}"
                                placeholder="Email"
                                required 
                                autofocus
                                readonly
                            >
                            <i class="fas fa-envelope input-icon"></i>
                        </div>
                        @error('email')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password">Kata Sandi Baru</label>
                        <div class="input-wrapper">
                            <input 
                                type="password" 
                                class="form-control @error('password') is-invalid @enderror" 
                                id="password" 
                                name="password" 
                                placeholder="Kata Sandi Baru"
                                required
                            >
                            <i class="fas fa-lock input-icon"></i>
                            <button type="button" class="password-toggle">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Konfirmasi Kata Sandi</label>
                        <div class="input-wrapper">
                            <input 
                                type="password" 
                                class="form-control" 
                                id="password_confirmation" 
                                name="password_confirmation" 
                                placeholder="Ulangi Kata Sandi Baru"
                                required
                            >
                            <i class="fas fa-lock input-icon"></i>
                            <button type="button" class="password-toggle">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn-auth btn-primary-auth">
                        <span>Reset Password</span>
                        <i class="fas fa-key"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

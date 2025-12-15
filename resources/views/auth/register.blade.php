@extends('layouts.auth')

@section('title', 'Registrasi - PlayStation Rental')

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
                    <h1 class="art-title">Gabung<br>Bersama Kami</h1>
                    <p class="art-subtitle">Mulai pengalaman baru anda dengan satu klik</p>
                </div>
                <div class="glass-overlay"></div>
            </div>
        </div>

        <!-- Form Side (Right) -->
        <div class="form-side">
            <div class="auth-card-modern">
                <div class="auth-header">
                    {{-- Icon removed as requested --}}
                    <h2 class="auth-title">Buat Akun</h2>
                    <p class="auth-subtitle">Isi detail anda untuk mendaftar</p>
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

                <form method="POST" action="{{ route('register.post') }}" class="register-form">
                    @csrf
                    
                    <div class="form-group">
                        <label for="name">Nama Lengkap</label>
                        <div class="input-wrapper">
                            <input 
                                type="text" 
                                class="form-control @error('name') is-invalid @enderror" 
                                id="name" 
                                name="name" 
                                value="{{ old('name') }}"
                                placeholder="Masukkan nama lengkap Anda"
                                required 
                                autofocus
                                autocomplete="name"
                            >
                            <i class="fas fa-user input-icon"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="address">Alamat</label>
                        <div class="input-wrapper">
                            <input 
                                type="text" 
                                class="form-control @error('address') is-invalid @enderror" 
                                id="address" 
                                name="address" 
                                value="{{ old('address') }}"
                                placeholder="Masukkan alamat lengkap Anda"
                                required
                                autocomplete="street-address"
                            >
                            <i class="fas fa-home input-icon"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="phone">Nomor Telepon</label>
                        <div class="input-wrapper">
                            <input 
                                type="tel" 
                                class="form-control @error('phone') is-invalid @enderror" 
                                id="phone" 
                                name="phone" 
                                value="{{ old('phone') }}"
                                placeholder="Nomor WhatsApp"
                                required
                                inputmode="tel"
                                autocomplete="tel-national"
                            >
                            <i class="fas fa-phone input-icon"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <div class="input-wrapper">
                            <input 
                                type="email" 
                                class="form-control @error('email') is-invalid @enderror" 
                                id="email" 
                                name="email" 
                                value="{{ old('email') }}"
                                placeholder="Alamat Email Aktif"
                                required
                                autocomplete="email"
                            >
                            <i class="fas fa-envelope input-icon"></i>
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
                                placeholder="Buat kata sandi"
                                required
                                autocomplete="new-password"
                            >
                            <i class="fas fa-lock input-icon"></i>
                            <button type="button" class="password-toggle">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Konfirmasi Password</label>
                        <div class="input-wrapper">
                            <input 
                                type="password" 
                                class="form-control" 
                                id="password_confirmation" 
                                name="password_confirmation" 
                                placeholder="Ulangi kata sandi"
                                required
                                autocomplete="new-password"
                            >
                            <i class="fas fa-lock input-icon"></i>
                            <button type="button" class="password-toggle">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="form-check">
                        <div class="form-check-left">
                            <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                            <label class="form-check-label" for="terms">Saya setuju dengan <a class="auth-link" href="#" id="terms-link">Syarat & Ketentuan</a></label>
                        </div>
                    </div>

                    <button type="submit" class="btn-auth btn-primary-auth btn-register">
                        <span>Daftar</span>
                        <i class="fas fa-user-plus"></i>
                    </button>

                    <div class="auth-divider">
                        <span>Atau</span>
                    </div>

                    <a href="{{ route('auth.google.redirect') }}" class="btn-auth btn-google">
                        <img src="https://www.google.com/favicon.ico" alt="Google">
                       Login dengan Google
                    </a>

                    <div class="auth-bottom-links">
                        Sudah Punya Akun? <a href="{{ route('login.show') }}" class="auth-link">Login di sini</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Terms Modal -->
    <div id="terms-modal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Peraturan</h3>
                <button type="button" class="close-modal">&times;</button>
            </div>
            <div class="modal-body">
                <p>Harap baca peraturan dan ketentuan berikut dengan seksama sebelum mendaftar:</p>
                <ol>
                    <li>Harap menyediakan identitas yang valid (KTP/SIM/Surat Keterangan Mahasiswa) saat menyewa.</li>
                    <li>Penyewa sepenuhnya bertanggung jawab atas kerusakan atau kehilangan unit yang disewa.</li>
                    <li>Pengembalian yang terlambat akan dikenakan denda sesuai dengan peraturan yang berlaku.</li>
                    <li>Prohibited to transfer the rented unit to a third party without permission.</li>
                    <li>Pembayaran sewa harus dilakukan sebelumnya atau sesuai dengan yang dijanjikan.</li>
                    <li>Kami berhak menolak sewa jika persyaratan tidak terpenuhi.</li>
                </ol>
                <p>Setelah mendaftar, Anda setuju dengan semua peraturan di atas.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-modal btn-decline" id="btn-decline">Tolak</button>
                <button type="button" class="btn-modal btn-accept" id="btn-accept">Terima</button>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('terms-modal');
            const link = document.getElementById('terms-link');
            const closeBtn = document.querySelector('.close-modal');
            const acceptBtn = document.getElementById('btn-accept');
            const declineBtn = document.getElementById('btn-decline');
            const checkbox = document.getElementById('terms');

            // Open modal
            link.addEventListener('click', function(e) {
                e.preventDefault();
                modal.classList.add('active');
            });

            // Close modal functions
            function closeModal() {
                modal.classList.remove('active');
            }

            closeBtn.addEventListener('click', closeModal);
            
            // Close on outside click
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closeModal();
                }
            });

            // Accept button
            acceptBtn.addEventListener('click', function() {
                checkbox.checked = true;
                closeModal();
            });

            // Decline button
            declineBtn.addEventListener('click', function() {
                checkbox.checked = false;
                closeModal();
            });
        });
    </script>
    @endpush
@endsection

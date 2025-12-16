@extends('pelanggan.layout')

@push('styles')
<style>
    /* Specific override for this page to prevent overflow */
    body .main-content {
        overflow-x: clip !important;
    }

    /* Limit container width specifically for profile edit page */
    .profile-edit-wrapper {
        width: 100% !important;
        max-width: calc(100% - 80px) !important; /* Safe margin */
        margin-left: auto !important;
        margin-right: auto !important;
    }

    /* More specific selector to override any conflicting styles */
    .main-content .profile-edit-wrapper {
        width: calc(100% - 40px) !important; /* Extra safe margin */
        max-width: none !important;
    }

    /* For larger screens, consider sidebar width */
    @media (min-width: 1200px) {
        .main-content .profile-edit-wrapper {
            max-width: calc(100vw - 300px) !important; /* Account for 260px sidebar + 40px padding */
        }

        body.sidebar-collapsed .main-content .profile-edit-wrapper {
            max-width: calc(100vw - 120px) !important; /* Account for 80px sidebar + 40px padding */
        }
    }

    /* Additional safeguard */
    .main-content .container-fluid,
    .main-content .row {
        overflow-x: hidden !important;
    }

    /* Specific override to force constrain content */
    .main-content .card {
        max-width: 100vw !important;
        overflow-x: clip !important;
    }

    /* Limit card body width more strictly */
    .main-content .card-body {
        max-width: 100% !important;
        overflow-x: hidden !important;
    }
</style>
@endpush

@section('pelanggan_content')
<div class="container-fluid" style="max-width: 100%; padding-left: 10px; padding-right: 10px;">
    <div class="row justify-content-center">
        <div class="col-12 col-xl-10 mx-auto">
            <!-- Header -->
            <div class="card mb-4">
                <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                    <div class="text-center text-md-start">
                        <h4 class="mb-1 fw-bold text-main"><i class="bi bi-pencil-square me-2 text-primary"></i>Edit Profil</h4>
                        <p class="mb-0 small text-muted">Perbarui informasi akun dan data diri Anda</p>
                    </div>
                    <div>
                        <a href="{{ route('pelanggan.profile.show') }}" class="btn btn-sm rounded-pill px-3 btn-outline-primary">
                            <i class="bi bi-arrow-left me-2"></i>Kembali
                        </a>
                    </div>
                </div>
            </div>

            @if(session('status'))
                <div class="alert mb-4 d-flex align-items-center rounded-3" style="background-color: #d1fae5; color: #065f46; border: 1px solid #10b981;">
                    <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                    <div>{{ session('status') }}</div>
                </div>
            @endif

            @if(session('error'))
                <div class="alert mb-4 d-flex align-items-center rounded-3" style="background-color: #fee2e2; color: #991b1b; border: 1px solid #ef4444;">
                    <i class="bi bi-exclamation-circle-fill me-2 fs-5"></i>
                    <div>{{ session('error') }}</div>
                </div>
            @endif

            @if(session('warning'))
                <div class="alert mb-4 d-flex align-items-start rounded-3" style="background-color: #fef3c7; color: #92400e; border: 1px solid #f59e0b;">
                    <i class="bi bi-exclamation-triangle-fill me-3 fs-4 mt-1"></i>
                    <div>
                        <h6 class="fw-bold mb-1" style="color: #92400e;">Profil Belum Lengkap!</h6>
                        <p class="mb-0 small" style="color: #78350f;">{{ session('warning') }}</p>
                    </div>
                </div>
            @endif

            @if(empty($user->phone) || empty($user->address))
                <div class="alert mb-4 d-flex align-items-start rounded-3" style="background-color: #cffafe; color: #0e7490; border: 1px solid #06b6d4;">
                    <i class="bi bi-info-circle-fill me-3 fs-4 mt-1"></i>
                    <div>
                        <h6 class="fw-bold mb-1" style="color: #0e7490;">Informasi Penting</h6>
                        <p class="mb-0 small" style="color: #155e75;">Nomor HP dan Alamat <strong>WAJIB</strong> diisi untuk melakukan pemesanan rental. Lengkapi data Anda sekarang!</p>
                    </div>
                </div>
            @endif

            <div class="card" style="max-width: calc(100vw - 40px); margin-left: auto; margin-right: auto; overflow-x: hidden;">
                <div class="card-body p-4" style="overflow-x: hidden;">
                    <form method="POST" action="{{ route('pelanggan.profile.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="row g-4 justify-content-center">
                            <div class="col-12 col-md-10">
                                <h5 class="fw-bold text-center text-main border-bottom pb-2 mb-3">Informasi Dasar</h5>
                            </div>

                            <div class="col-12 col-md-10 mb-3">
                                <label class="form-label fw-bold small text-uppercase d-block text-center text-muted">Nama Lengkap</label>
                                <div class="d-flex align-items-center justify-content-center gap-2 form-control p-0 ps-3">
                                    <i class="bi bi-person text-muted"></i>
                                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                           class="form-control border-0 shadow-none bg-transparent text-main" placeholder="Nama Lengkap">
                                </div>
                                @error('name')
                                    <small class="text-danger mt-1 d-block text-center">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-12 col-md-10 mb-3">
                                <label class="form-label fw-bold small text-uppercase d-block text-center text-muted">Email</label>
                                <div class="d-flex align-items-center justify-content-center gap-2 form-control p-0 ps-3">
                                    <i class="bi bi-envelope text-muted"></i>
                                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                           class="form-control border-0 shadow-none bg-transparent text-main" placeholder="Alamat Email">
                                </div>
                                @error('email')
                                    <small class="text-danger mt-1 d-block text-center">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-12 mt-4">
                                <h5 class="fw-bold text-center text-main border-bottom pb-2 mb-3">Kontak & Alamat</h5>
                            </div>

                            <div class="col-12 col-md-10 mb-3">
                                <label class="form-label fw-bold small text-uppercase d-block text-center text-muted">Nomor Telepon <span class="text-danger">*</span></label>
                                <div class="d-flex align-items-center justify-content-center gap-2 form-control p-0 ps-3">
                                    <i class="bi bi-telephone text-muted"></i>
                                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" required
                                           class="form-control border-0 shadow-none bg-transparent text-main"
                                           placeholder="Contoh: +6281234567890">
                                </div>
                                @error('phone')
                                    <small class="text-danger mt-1 d-block text-center">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-12 col-md-10 mb-3">
                                <label class="form-label fw-bold small text-uppercase d-block text-center text-muted">Alamat Lengkap <span class="text-danger">*</span></label>
                                <div class="d-flex align-items-center justify-content-center gap-2 form-control p-0 ps-3">
                                    <i class="bi bi-geo-alt text-muted"></i>
                                    <input type="text" name="address" value="{{ old('address', $user->address) }}" required
                                           class="form-control border-0 shadow-none bg-transparent text-main"
                                           placeholder="Jalan, Nomor Rumah, Kota">
                                </div>
                                @error('address')
                                    <small class="text-danger mt-1 d-block text-center">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-12 mt-4">
                                <h5 class="fw-bold text-center text-main border-bottom pb-2 mb-3">Keamanan</h5>
                            </div>

                            <div class="col-12 col-md-10 mb-3">
                                <label class="form-label fw-bold small text-uppercase d-block text-center text-muted">Password Baru</label>
                                <div class="d-flex align-items-center justify-content-center gap-2 form-control p-0 ps-3">
                                    <i class="bi bi-lock text-muted"></i>
                                    <input type="password" name="password"
                                           class="form-control border-0 shadow-none bg-transparent text-main"
                                           placeholder="Kosongkan jika tidak ingin mengubah">
                                </div>
                                @error('password')
                                    <small class="text-danger mt-1 d-block text-center">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-12 col-md-10 mb-3">
                                <label class="form-label fw-bold small text-uppercase d-block text-center text-muted">Konfirmasi Password</label>
                                <div class="d-flex align-items-center justify-content-center gap-2 form-control p-0 ps-3">
                                    <i class="bi bi-lock-fill text-muted"></i>
                                    <input type="password" name="password_confirmation"
                                           class="form-control border-0 shadow-none bg-transparent text-main"
                                           placeholder="Ulangi password baru">
                                </div>
                            </div>

                            <div class="col-12 mt-5 d-flex flex-column flex-md-row gap-3 justify-content-center">
                                <button type="submit" class="btn btn-primary btn-lg px-5 fw-bold shadow-lg">
                                    <i class="bi bi-save me-2"></i>Simpan Perubahan
                                </button>
                                <a href="{{ route('pelanggan.profile.show') }}" class="btn btn-outline-secondary btn-lg px-4">
                                    Batal
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Function to adjust container width based on sidebar state
    function adjustContainerWidth() {
        // Calculate available width based on sidebar state
        const isSidebarCollapsed = document.body.classList.contains('sidebar-collapsed') ||
                                 document.querySelector('.sidebar')?.classList.contains('collapsed');

        if (window.innerWidth >= 992) {
            const sidebarWidth = isSidebarCollapsed ? 80 : 260;
            const availableWidth = window.innerWidth - sidebarWidth - 60; // -60 for padding

            // Set max-width for content container
            const container = document.querySelector('.profile-edit-wrapper');
            if (container) {
                container.style.maxWidth = `${availableWidth}px`;
            }
        } else {
            // On mobile, use full width
            const container = document.querySelector('.profile-edit-wrapper');
            if (container) {
                container.style.maxWidth = '100%';
            }
        }
    }

    // Run when page loads
    document.addEventListener('DOMContentLoaded', adjustContainerWidth);

    // Run when window is resized
    window.addEventListener('resize', adjustContainerWidth);

    // Run when sidebar is toggled
    if (typeof MutationObserver !== 'undefined') {
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                    adjustContainerWidth();
                }
            });
        });

        observer.observe(document.body, {
            attributes: true,
            attributeFilter: ['class']
        });
    }
</script>
@endpush
@endsection
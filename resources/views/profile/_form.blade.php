<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-8">
            <div class="card border-0 shadow-lg glass-card overflow-hidden">
                <div class="card-body p-0">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm m-4 mb-0" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm m-4 mb-0" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        <!-- Avatar Section -->
                        <div class="text-center py-5 border-bottom" style="background: linear-gradient(135deg, rgba(6, 82, 221, 0.05) 0%, transparent 100%);">
                            <div class="position-relative d-inline-block mb-3">
                                <div id="avatar-preview-container" class="rounded-circle overflow-hidden d-flex align-items-center justify-content-center text-white fw-bold shadow-lg" style="width: 120px; height: 120px; font-size: 3rem; background: linear-gradient(135deg, #0652DD 0%, #0043b8 100%); border: 4px solid var(--card-bg);">
                                    @if(Auth::user()->avatar)
                                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}?v={{ time() }}" alt="Avatar" class="w-100 h-100 object-fit-cover">
                                    @else
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    @endif
                                </div>
                                <label for="avatar" class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="cursor: pointer; width: 36px; height: 36px; border: 3px solid var(--card-bg); transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'" data-bs-toggle="tooltip" title="{{ __('profile.change_photo') }}">
                                    <i class="bi bi-camera-fill"></i>
                                    <input type="file" name="avatar" id="avatar" class="d-none" accept="image/*" onchange="previewImage(this)">
                                </label>
                            </div>
                            
                            <h4 class="fw-bold mb-1" style="color: var(--text-main);">{{ Auth::user()->name }}</h4>
                            <p class="text-muted mb-3">{{ Auth::user()->email }}</p>
                            
                            @if(Auth::user()->avatar)
                                <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="confirmDeleteAvatar()">
                                    <i class="bi bi-trash me-1"></i> {{ __('profile.delete_photo') }}
                                </button>
                            @endif
                            
                            <p class="small mt-3 mb-0 text-muted">
                                <i class="bi bi-info-circle me-1"></i>{{ __('profile.photo_help') }}
                            </p>
                        </div>

                        <!-- Form Section -->
                        <div class="p-4 p-md-5">
                            <!-- Section Header -->
                            <div class="mb-4">
                                <h5 class="fw-bold mb-1" style="color: var(--text-main);">
                                    <i class="bi bi-person-badge me-2 text-primary"></i>Informasi Pribadi
                                </h5>
                                <p class="text-muted small mb-0">Perbarui informasi profil Anda</p>
                            </div>

                            <div class="row g-4">
                                <!-- Name -->
                                <div class="col-md-6">
                                    <label for="name" class="form-label fw-semibold small text-uppercase mb-2" style="color: var(--text-muted); letter-spacing: 0.5px;">
                                        {{ __('profile.name') }}
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-transparent border-end-0">
                                            <i class="bi bi-person text-primary"></i>
                                        </span>
                                        <input type="text" class="form-control border-start-0 ps-0 @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', Auth::user()->name) }}" required>
                                    </div>
                                    @error('name')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div class="col-md-6">
                                    <label for="email" class="form-label fw-semibold small text-uppercase mb-2" style="color: var(--text-muted); letter-spacing: 0.5px;">
                                        {{ __('profile.email') }}
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-transparent border-end-0">
                                            <i class="bi bi-envelope text-primary"></i>
                                        </span>
                                        <input type="email" class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', Auth::user()->email) }}" required>
                                    </div>
                                    @error('email')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Phone -->
                                <div class="col-md-6">
                                    <label for="phone" class="form-label fw-semibold small text-uppercase mb-2" style="color: var(--text-muted); letter-spacing: 0.5px;">
                                        {{ __('profile.phone') }}
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-transparent border-end-0">
                                            <i class="bi bi-telephone text-primary"></i>
                                        </span>
                                        <input type="text" class="form-control border-start-0 ps-0 @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', Auth::user()->phone) }}">
                                    </div>
                                    @error('phone')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Address -->
                                <div class="col-12">
                                    <label for="address" class="form-label fw-semibold small text-uppercase mb-2" style="color: var(--text-muted); letter-spacing: 0.5px;">
                                        <i class="bi bi-geo-alt text-primary me-2"></i>{{ __('profile.address') }}
                                    </label>
                                    <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3" placeholder="Masukkan alamat lengkap Anda">{{ old('address', Auth::user()->address) }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Save Button -->
                            <div class="d-flex justify-content-end mt-5 pt-4 border-top">
                                <button type="submit" class="btn btn-primary px-5 py-2 rounded-pill shadow-sm" style="transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                                    <i class="bi bi-save me-2"></i>{{ __('profile.save') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Form untuk hapus avatar -->
<form id="delete-avatar-form" action="{{ route('profile.avatar.delete') }}" method="POST" class="d-none">
    @csrf
    @method('DELETE')
</form>

<script>
    // Preview image function
    function previewImage(input) {
        if (input.files && input.files[0]) {
            // Validate file size (max 2MB)
            if (input.files[0].size > 2 * 1024 * 1024) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: '{{ __('profile.file_too_big_title') }}',
                        text: '{{ __('profile.file_too_big_text') }}',
                        confirmButtonColor: '#0652DD'
                    });
                } else {
                    alert('{{ __('profile.file_too_big_text') }}');
                }
                input.value = '';
                return;
            }

            var reader = new FileReader();
            reader.onload = function(e) {
                // Update main profile avatar
                var container = document.getElementById('avatar-preview-container');
                if (container) {
                    var img = container.querySelector('img');
                    if (img) {
                        img.src = e.target.result;
                    } else {
                        container.innerHTML = '<img src="' + e.target.result + '" alt="Avatar" class="w-100 h-100 object-fit-cover">';
                    }
                }
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Confirm delete avatar
    function confirmDeleteAvatar() {
        const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
        
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Hapus Foto Profil?',
                html: '<p class="mb-0">Foto profil Anda akan dihapus secara permanen.<br><small style="opacity: 0.7;">Tindakan ini tidak dapat dibatalkan.</small></p>',
                icon: 'warning',
                iconColor: '#ef4444',
                showCancelButton: true,
                confirmButtonText: '<i class="bi bi-trash me-1"></i> Ya, Hapus',
                cancelButtonText: '<i class="bi bi-check-lg me-1"></i> Batal',
                background: isDark ? '#1e293b' : '#ffffff',
                color: isDark ? '#f1f5f9' : '#1e293b',
                customClass: {
                    popup: 'rounded-4 shadow-lg',
                    title: 'fs-5 fw-bold',
                    htmlContainer: 'text-center'
                },
                buttonsStyling: false,
                reverseButtons: true,
                focusCancel: true,
                didOpen: () => {
                    // Style confirm button (red/danger)
                    const confirmBtn = Swal.getConfirmButton();
                    confirmBtn.style.cssText = 'background-color: #ef4444; color: white; border: none; padding: 10px 24px; border-radius: 50px; font-weight: 600; margin-left: 10px; cursor: pointer;';
                    confirmBtn.onmouseover = () => confirmBtn.style.backgroundColor = '#dc2626';
                    confirmBtn.onmouseout = () => confirmBtn.style.backgroundColor = '#ef4444';
                    
                    // Style cancel button (green/success)
                    const cancelBtn = Swal.getCancelButton();
                    cancelBtn.style.cssText = 'background-color: #10b981; color: white; border: none; padding: 10px 24px; border-radius: 50px; font-weight: 600; cursor: pointer;';
                    cancelBtn.onmouseover = () => cancelBtn.style.backgroundColor = '#059669';
                    cancelBtn.onmouseout = () => cancelBtn.style.backgroundColor = '#10b981';
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading state
                    Swal.fire({
                        title: 'Menghapus...',
                        html: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        background: isDark ? '#1e293b' : '#ffffff',
                        color: isDark ? '#f1f5f9' : '#1e293b',
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    document.getElementById('delete-avatar-form').submit();
                }
            });
        } else if (confirm('Hapus Foto Profil? Tindakan ini tidak dapat dibatalkan.')) {
            document.getElementById('delete-avatar-form').submit();
        }
    }
</script>

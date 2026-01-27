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

                <form method="POST" action="{{ route('register.post') }}" class="register-form" id="registerForm">
                    @csrf
                    
                    <!-- NAMA LENGKAP -->
                    <div class="form-group">
                        <label for="name">
                            Masukkan Nama Anda 
                            <span class="text-danger">*</span>
                            <small class="text-muted"></small>
                        </label>
                        <div class="input-wrapper">
                            <input 
                                type="text" 
                                class="form-control @error('name') is-invalid @enderror" 
                                id="name" 
                                name="name" 
                                value="{{ old('name') }}"
                                placeholder="Contoh: Ahmad Rizki Pratama"
                                required 
                                autofocus
                                autocomplete="name"
                                minlength="3"
                                maxlength="100"
                                pattern="^[a-zA-Z\s]+$"
                                title="Nama hanya boleh berisi huruf dan spasi"
                            >
                            <i class="fas fa-user input-icon"></i>
                            <span class="validation-icon valid-icon"><i class="fas fa-check-circle text-success"></i></span>
                            <span class="validation-icon invalid-icon"><i class="fas fa-times-circle text-danger"></i></span>
                        </div>
                        <small id="name-feedback" class="form-text"></small>
                    </div>

                    <!-- ALAMAT -->
                    <div class="form-group">
                        <label for="address">
                            Alamat Lengkap 
                            <span class="text-danger">*</span>
                            <small class="text-muted">(min. 10 kata)</small>
                        </label>
                        <div class="input-wrapper">
                            <textarea 
                                class="form-control @error('address') is-invalid @enderror" 
                                id="address" 
                                name="address" 
                                rows="3"
                                placeholder="Jl. Merdeka No. 123, RT 01 RW 02, Kelurahan Sukamaju, Kecamatan Bahagia, Kota Sejahtera, Provinsi Jawa Barat 12345"
                                required
                                autocomplete="street-address"
                                minlength="30"
                                maxlength="500"
                            >{{ old('address') }}</textarea>
                            <i class="fas fa-home input-icon" style="top: 1rem;"></i>
                        </div>
                        <div class="d-flex justify-content-between">
                            <small id="address-counter" class="form-text text-danger">0 kata dari minimal 10 kata</small>
                            <small id="address-chars" class="form-text text-muted">0/500 karakter</small>
                        </div>
                    </div>

                    <!-- NOMOR HP -->
                    <div class="form-group">
                        <label for="phone">
                            Nomor HP (WhatsApp) 
                            <span class="text-danger">*</span>
                            <small class="text-muted">(format +62)</small>
                        </label>
                        <div class="input-wrapper">
                            <input 
                                type="tel" 
                                class="form-control @error('phone') is-invalid @enderror" 
                                id="phone" 
                                name="phone" 
                                value="{{ old('phone', '+62') }}"
                                placeholder="+6281234567890"
                                required
                                inputmode="tel"
                                autocomplete="tel"
                                pattern="\+62[0-9]{8,15}"
                                title="Format: +62 diikuti 8-15 digit angka"
                            >
                            <i class="fas fa-phone input-icon"></i>
                            <span class="validation-icon valid-icon"><i class="fas fa-check-circle text-success"></i></span>
                            <span class="validation-icon invalid-icon"><i class="fas fa-times-circle text-danger"></i></span>
                        </div>
                        <small id="phone-feedback" class="form-text">Format: +62 diikuti 8-15 digit. Contoh: +6281234567890</small>
                    </div>

                    <!-- EMAIL -->
                    <div class="form-group">
                        <label for="email">
                            Masukkan Gmail Anda
                            <span class="text-danger">*</span>
                            <small class="text-muted"></small>
                        </label>
                        <div class="input-wrapper">
                            <input 
                                type="email" 
                                class="form-control @error('email') is-invalid @enderror" 
                                id="email" 
                                name="email" 
                                value="{{ old('email') }}"
                                placeholder="contoh@gmail.com"
                                required
                                autocomplete="email"
                                maxlength="100"
                                pattern="^[a-zA-Z0-9._%+-]+@gmail\.com$"
                                title="Email harus menggunakan @gmail.com"
                            >
                            <i class="fas fa-envelope input-icon"></i>
                            <span class="validation-icon valid-icon"><i class="fas fa-check-circle text-success"></i></span>
                            <span class="validation-icon invalid-icon"><i class="fas fa-times-circle text-danger"></i></span>
                        </div>
                        <small id="email-feedback" class="form-text text-muted"></small>
                    </div>

                    <!-- PASSWORD -->
                    <div class="form-group">
                        <label for="password">
                            Masukkan Password Anda
                            <span class="text-danger">*</span>
                        </label>
                        <div class="input-wrapper">
                            <input 
                                type="password" 
                                class="form-control @error('password') is-invalid @enderror" 
                                id="password" 
                                name="password" 
                                placeholder="Buat kata sandi yang kuat"
                                required
                                autocomplete="new-password"
                                minlength="8"
                                maxlength="50"
                            >
                            <i class="fas fa-lock input-icon"></i>
                            <button type="button" class="password-toggle" aria-label="Toggle password visibility">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div id="password-requirements" class="password-requirements mt-2">
                            <small class="d-block"><i class="fas fa-circle req-icon" id="req-length"></i> </small>
                            <small class="d-block"><i class="fas fa-circle req-icon" id="req-upper"></i> </small>
                            <small class="d-block"><i class="fas fa-circle req-icon" id="req-lower"></i> </small>
                            <small class="d-block"><i class="fas fa-circle req-icon" id="req-number"></i> </small>
                            <small class="d-block"><i class="fas fa-circle req-icon" id="req-special"></i> </small>
                        </div>
                        <div id="password-strength" class="password-strength-bar mt-2">
                            <div class="strength-meter"><div class="strength-fill" id="strength-fill"></div></div>
                            <small id="strength-text" class="text-muted">Kekuatan: -</small>
                        </div>
                    </div>

                    <!-- KONFIRMASI PASSWORD -->
                    <div class="form-group">
                        <label for="password_confirmation">
                            Konfirmasi Password 
                            <span class="text-danger">*</span>
                        </label>
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
                            <button type="button" class="password-toggle" aria-label="Toggle password visibility">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <small id="confirm-feedback" class="form-text"></small>
                    </div>

                    <!-- TERMS -->
                    <div class="form-check">
                        <div class="form-check-left">
                            <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                            <label class="form-check-label" for="terms">
                                Saya setuju dengan <a class="auth-link" href="#" id="terms-link">Syarat & Ketentuan</a>
                                <span class="text-danger">*</span>
                            </label>
                        </div>
                    </div>

                    <!-- SUBMIT BUTTON -->
                    <button type="submit" class="btn-auth btn-primary-auth btn-register" id="submitBtn">
                        <span>Daftar Sekarang</span>
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
                <div style="display: flex; align-items: center; gap: 15px;">
                    <h3>Peraturan</h3>
                    <div class="lang-toggle" style="background: rgba(255,255,255,0.1); padding: 3px; border-radius: 20px; display: flex;">
                        <button type="button" id="lang-id" class="active" style="background: none; border: none; color: #fff; padding: 2px 10px; border-radius: 15px; cursor: pointer; font-size: 0.8rem; font-weight: bold;">ID</button>
                        <button type="button" id="lang-en" style="background: none; border: none; color: #888; padding: 2px 10px; border-radius: 15px; cursor: pointer; font-size: 0.8rem; font-weight: bold;">EN</button>
                    </div>
                </div>
                <button type="button" class="close-modal">&times;</button>
            </div>
            <div class="modal-body" style="text-align: left; max-height: 400px; overflow-y: auto;">
                
                <!-- INDONESIAN CONTENT -->
                <div id="content-id">
                    <p style="margin-bottom: 15px;">Terima kasih telah memilih PlayStation Rental. Harap baca dan pahami Syarat & Ketentuan berikut sebelum menggunakan layanan kami.</p>
                    
                    <h5 style="color: #0070d1; margin-top: 20px; font-weight: 600;">1. Kelayakan Penyewa</h5>
                    <ul style="list-style-type: disc; margin-left: 20px; color: #ccc;">
                        <li>Penyewa wajib berusia minimal 17 tahun dan memiliki kartu identitas yang sah (KTP/SIM/Kartu Pelajar).</li>
                        <li>Kami berhak menolak penyewaan jika dokumen identitas dirasa kurang valid atau mencurigakan.</li>
                    </ul>

                    <h5 style="color: #0070d1; margin-top: 20px; font-weight: 600;">2. Tanggung Jawab & Keamanan Unit</h5>
                    <ul style="list-style-type: disc; margin-left: 20px; color: #ccc;">
                        <li>Penyewa bertanggung jawab penuh atas unit PlayStation, controller, game, dan aksesoris lainnya selama masa sewa.</li>
                        <li>Dilarang keras memindahtangankan, menyewakan kembali, atau menggadaikan unit kepada pihak ketiga.</li>
                        <li>Segala bentuk kerusakan (terjatuh, terkena air, konslet, dll) atau kehilangan unit menjadi tanggungan penyewa sepenuhnya.</li>
                    </ul>

                    <h5 style="color: #0070d1; margin-top: 20px; font-weight: 600;">3. Durasi Sewa & Pengembalian</h5>
                    <ul style="list-style-type: disc; margin-left: 20px; color: #ccc;">
                        <li>Waktu sewa dihitung sejak unit diterima oleh penyewa.</li>
                        <li>Keterlambatan pengembalian akan dikenakan denda sebesar <strong>Rp 10.000 per jam</strong>.</li>
                        <li>Perpanjangan masa sewa harus dikonfirmasi minimal 3 jam sebelum masa sewa berakhir.</li>
                    </ul>

                    <h5 style="color: #0070d1; margin-top: 20px; font-weight: 600;">4. Pembayaran & Denda</h5>
                    <ul style="list-style-type: disc; margin-left: 20px; color: #ccc;">
                        <li>Pembayaran wajib lunas di awal (Prepaid) saat pengambilan atau pengantaran unit.</li>
                        <li>Denda kerusakan akan disesuaikan dengan biaya perbaikan resmi (Service Center) atau harga unit pengganti jika rusak total.</li>
                    </ul>

                    <h5 style="color: #0070d1; margin-top: 20px; font-weight: 600;">5. Privasi & Lainnya</h5>
                    <ul style="list-style-type: disc; margin-left: 20px; color: #ccc;">
                        <li>Data pribadi Anda disimpan dengan aman dan hanya digunakan untuk keperluan transaksi sewa.</li>
                        <li>Dengan mendaftar, Anda dianggap sah menyetujui seluruh poin dalam perjanjian ini tanpa paksaan.</li>
                    </ul>
                </div>

                <!-- ENGLISH CONTENT -->
                <div id="content-en" style="display: none;">
                    <p style="margin-bottom: 15px;">Thank you for choosing PlayStation Rental. Please read and understand the following Terms & Conditions before using our services.</p>
                    
                    <h5 style="color: #0070d1; margin-top: 20px; font-weight: 600;">1. Renter Eligibility</h5>
                    <ul style="list-style-type: disc; margin-left: 20px; color: #ccc;">
                        <li>Renters must be at least 17 years old and possess a valid identity card (ID/License/Student Card).</li>
                        <li>We reserve the right to refuse rental if the identity document is deemed invalid or suspicious.</li>
                    </ul>

                    <h5 style="color: #0070d1; margin-top: 20px; font-weight: 600;">2. Responsibility & Unit Safety</h5>
                    <ul style="list-style-type: disc; margin-left: 20px; color: #ccc;">
                        <li>The renter is fully responsible for the PlayStation unit, controllers, games, and other accessories during the rental period.</li>
                        <li>Strictly prohibited to transfer, sub-rent, or pawn the unit to third parties.</li>
                        <li>Any form of damage (dropped, water damage, short circuit, etc.) or loss of the unit is the full responsibility of the renter.</li>
                    </ul>

                    <h5 style="color: #0070d1; margin-top: 20px; font-weight: 600;">3. Rental Duration & Return</h5>
                    <ul style="list-style-type: disc; margin-left: 20px; color: #ccc;">
                        <li>Restriction time starts when the unit is received by the renter.</li>
                        <li>Late returns will be charged a fine of <strong>Rp 10,000 per hour</strong>.</li>
                        <li>Extension of rental period must be confirmed at least 3 hours before the rental period ends.</li>
                    </ul>

                    <h5 style="color: #0070d1; margin-top: 20px; font-weight: 600;">4. Payment & Fines</h5>
                    <ul style="list-style-type: disc; margin-left: 20px; color: #ccc;">
                        <li>Payment must be fully paid in advance (Prepaid) upon pickup or delivery of the unit.</li>
                        <li>Damage fines will be adjusted to the official repair cost (Service Center) or the price of a replacement unit if totally damaged.</li>
                    </ul>

                    <h5 style="color: #0070d1; margin-top: 20px; font-weight: 600;">5. Privacy & Others</h5>
                    <ul style="list-style-type: disc; margin-left: 20px; color: #ccc;">
                        <li>Your personal data is stored securely and only used for rental transaction purposes.</li>
                        <li>By registering, you are considered to have legally agreed to all points in this agreement without coercion.</li>
                    </ul>
                </div>

                <p style="margin-top: 20px; font-size: 0.9em; color: #888; text-align: center;"><em>&copy; 2024 PlayStation Rental. All Rights Reserved.</em></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-modal btn-decline" id="btn-decline">Tolak</button>
                <button type="button" class="btn-modal btn-accept" id="btn-accept">Terima</button>
            </div>
        </div>
    </div>

    @push('scripts')
    <style>
        /* Validation Icons */
        .validation-icon { display: none; position: absolute; right: 45px; top: 50%; transform: translateY(-50%); }
        .input-wrapper .form-control.is-valid ~ .valid-icon { display: block; }
        .input-wrapper .form-control.is-invalid ~ .invalid-icon { display: block; }
        .input-wrapper .form-control.is-valid ~ .valid-icon ~ .invalid-icon { display: none; }
        
        /* Password Requirements */
        .password-requirements { font-size: 0.8rem; }
        .req-icon { font-size: 6px; margin-right: 6px; color: #dc3545; vertical-align: middle; }
        .req-icon.valid { color: #198754 !important; }
        
        /* Password Strength Bar */
        .strength-meter { height: 6px; background: #e9ecef; border-radius: 3px; overflow: hidden; }
        .strength-fill { height: 100%; width: 0%; transition: width 0.3s, background 0.3s; }
        .strength-weak { background: #dc3545; width: 25%; }
        .strength-fair { background: #fd7e14; width: 50%; }
        .strength-good { background: #ffc107; width: 75%; }
        .strength-strong { background: #198754; width: 100%; }
        
        /* Disabled Button */
        .btn-register:disabled { opacity: 0.6; cursor: not-allowed; }
        
        /* Validation Summary */
        .validation-summary { padding: 10px; background: rgba(220, 53, 69, 0.1); border-radius: 8px; text-align: center; }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ==========================================
            // ELEMENTS
            // ==========================================
            const form = document.getElementById('registerForm');
            const submitBtn = document.getElementById('submitBtn');
            // const validationSummary = document.getElementById('validation-summary');
            
            const nameField = document.getElementById('name');
            const nameFeedback = document.getElementById('name-feedback');
            
            const addressField = document.getElementById('address');
            const addressCounter = document.getElementById('address-counter');
            const addressChars = document.getElementById('address-chars');
            
            const phoneField = document.getElementById('phone');
            const phoneFeedback = document.getElementById('phone-feedback');
            
            const emailField = document.getElementById('email');
            const emailFeedback = document.getElementById('email-feedback');
            
            const passwordField = document.getElementById('password');
            const passwordConfirmField = document.getElementById('password_confirmation');
            const confirmFeedback = document.getElementById('confirm-feedback');
            
            const termsCheckbox = document.getElementById('terms');
            
            // Password requirement elements
            const reqLength = document.getElementById('req-length');
            const reqUpper = document.getElementById('req-upper');
            const reqLower = document.getElementById('req-lower');
            const reqNumber = document.getElementById('req-number');
            const reqSpecial = document.getElementById('req-special');
            const strengthFill = document.getElementById('strength-fill');
            const strengthText = document.getElementById('strength-text');
            
            // Modal elements
            const modal = document.getElementById('terms-modal');
            const termsLink = document.getElementById('terms-link');
            const closeBtn = document.querySelector('.close-modal');
            const acceptBtn = document.getElementById('btn-accept');
            const declineBtn = document.getElementById('btn-decline');
            
            // ==========================================
            // VALIDATION STATE
            // ==========================================
            let validState = {
                name: false,
                address: false,
                phone: false,
                email: false,
                password: false,
                passwordConfirm: false,
                terms: false
            };
            
            // ==========================================
            // HELPER FUNCTIONS
            // ==========================================
            function countWords(str) {
                return str.trim().split(/\s+/).filter(word => word.length > 0).length;
            }
            
            function setFieldValid(field, feedback, message = '') {
                field.classList.remove('is-invalid');
                field.classList.add('is-valid');
                if (feedback) {
                    feedback.textContent = message;
                    feedback.classList.remove('text-danger');
                    feedback.classList.add('text-success');
                }
            }
            
            function setFieldInvalid(field, feedback, message = '') {
                field.classList.remove('is-valid');
                field.classList.add('is-invalid');
                if (feedback) {
                    feedback.textContent = message;
                    feedback.classList.remove('text-success');
                    feedback.classList.add('text-danger');
                }
            }
            
            function updateSubmitButton() {
                // Button is always enabled to allow SweetAlert validation
                // const allValid = Object.values(validState).every(v => v === true);
                // submitBtn.disabled = !allValid;
                // validationSummary.style.display = allValid ? 'none' : 'block';
            }
            
            // ==========================================
            // NAME VALIDATION (letters and spaces only)
            // ==========================================
            nameField.addEventListener('input', function(e) {
                // Remove non-letter characters except spaces
                let value = e.target.value.replace(/[^a-zA-Z\s]/g, '');
                e.target.value = value;
                
                if (value.length >= 3 && /^[a-zA-Z\s]+$/.test(value)) {
                    setFieldValid(nameField, nameFeedback, '✓ Nama valid');
                    validState.name = true;
                } else if (value.length < 3) {
                    setFieldInvalid(nameField, nameFeedback, 'Nama minimal 3 karakter');
                    validState.name = false;
                } else {
                    setFieldInvalid(nameField, nameFeedback, 'Nama hanya boleh huruf dan spasi');
                    validState.name = false;
                }
                updateSubmitButton();
            });
            
            // ==========================================
            // ADDRESS VALIDATION (min 10 words)
            // ==========================================
            addressField.addEventListener('input', function() {
                const wordCount = countWords(this.value);
                const charCount = this.value.length;
                const minWords = 10;
                
                addressCounter.textContent = wordCount + ' kata dari minimal ' + minWords + ' kata';
                addressChars.textContent = charCount + '/500 karakter';
                
                if (wordCount >= minWords && charCount >= 30) {
                    addressCounter.classList.remove('text-danger');
                    addressCounter.classList.add('text-success');
                    addressField.classList.remove('is-invalid');
                    addressField.classList.add('is-valid');
                    validState.address = true;
                } else {
                    addressCounter.classList.remove('text-success');
                    addressCounter.classList.add('text-danger');
                    addressField.classList.remove('is-valid');
                    addressField.classList.add('is-invalid');
                    validState.address = false;
                }
                updateSubmitButton();
            });
            
            // ==========================================
            // PHONE VALIDATION (+62 format)
            // ==========================================
            phoneField.addEventListener('input', function(e) {
                let value = e.target.value;
                
                // Always start with +62
                if (!value.startsWith('+62')) {
                    value = value.replace(/^0+/, '').replace(/[^0-9]/g, '');
                    value = '+62' + value;
                }
                
                // Keep only +62 and digits
                value = '+62' + value.substring(3).replace(/[^0-9]/g, '');
                
                // Limit length (+62 + 15 digits max = 18 chars)
                if (value.length > 18) {
                    value = value.substring(0, 18);
                }
                
                e.target.value = value;
                
                // Validate (8-15 digits after +62)
                const phoneRegex = /^\+62[0-9]{8,15}$/;
                const digitCount = value.length - 3;
                
                if (phoneRegex.test(value)) {
                    setFieldValid(phoneField, phoneFeedback, '✓ Nomor HP valid (' + digitCount + ' digit setelah +62)');
                    validState.phone = true;
                } else {
                    const needed = 8 - digitCount;
                    if (needed > 0) {
                        setFieldInvalid(phoneField, phoneFeedback, 'Butuh ' + needed + ' digit lagi (format: +62 + 8-15 digit)');
                    } else {
                        setFieldInvalid(phoneField, phoneFeedback, 'Format tidak valid (format: +62 + 8-15 digit)');
                    }
                    validState.phone = false;
                }
                updateSubmitButton();
            });
            
            // ==========================================
            // EMAIL VALIDATION
            // ==========================================
            emailField.addEventListener('input', function() {
                const value = this.value.trim().toLowerCase();
                const gmailRegex = /^[a-zA-Z0-9._%+-]+@gmail\.com$/i;
                
                if (gmailRegex.test(value)) {
                    setFieldValid(emailField, emailFeedback, '✓ Email Gmail valid');
                    validState.email = true;
                } else if (value.length === 0) {
                    setFieldInvalid(emailField, emailFeedback, 'Email wajib diisi');
                    validState.email = false;
                } else if (!value.includes('@')) {
                    setFieldInvalid(emailField, emailFeedback, 'Email harus mengandung @');
                    validState.email = false;
                } else if (!value.endsWith('@gmail.com')) {
                    setFieldInvalid(emailField, emailFeedback, 'Email harus menggunakan @gmail.com');
                    validState.email = false;
                } else {
                    setFieldInvalid(emailField, emailFeedback, 'Format email tidak valid');
                    validState.email = false;
                }
                updateSubmitButton();
            });
            
            // ==========================================
            // PASSWORD VALIDATION WITH STRENGTH METER
            // ==========================================
            passwordField.addEventListener('input', function() {
                const value = this.value;
                let strength = 0;
                
                // Check requirements
                const hasLength = value.length >= 8;
                const hasUpper = /[A-Z]/.test(value);
                const hasLower = /[a-z]/.test(value);
                const hasNumber = /[0-9]/.test(value);
                const hasSpecial = /[!@#$%^&*(),.?":{}|<>]/.test(value);
                
                // Update requirement icons
                reqLength.classList.toggle('valid', hasLength);
                reqUpper.classList.toggle('valid', hasUpper);
                reqLower.classList.toggle('valid', hasLower);
                reqNumber.classList.toggle('valid', hasNumber);
                reqSpecial.classList.toggle('valid', hasSpecial);
                
                // Calculate strength
                if (hasLength) strength++;
                if (hasUpper) strength++;
                if (hasLower) strength++;
                if (hasNumber) strength++;
                if (hasSpecial) strength++;
                
                // Update strength meter
                strengthFill.className = 'strength-fill';
                if (strength === 0) {
                    strengthText.textContent = 'Kekuatan: -';
                } else if (strength <= 2) {
                    strengthFill.classList.add('strength-weak');
                    strengthText.textContent = 'Kekuatan: Lemah';
                } else if (strength === 3) {
                    strengthFill.classList.add('strength-fair');
                    strengthText.textContent = 'Kekuatan: Cukup';
                } else if (strength === 4) {
                    strengthFill.classList.add('strength-good');
                    strengthText.textContent = 'Kekuatan: Baik';
                } else {
                    strengthFill.classList.add('strength-strong');
                    strengthText.textContent = 'Kekuatan: Sangat Kuat';
                }
                
                // Validate (all requirements must be met)
                if (hasLength && hasUpper && hasLower && hasNumber && hasSpecial) {
                    passwordField.classList.remove('is-invalid');
                    passwordField.classList.add('is-valid');
                    validState.password = true;
                } else {
                    passwordField.classList.remove('is-valid');
                    passwordField.classList.add('is-invalid');
                    validState.password = false;
                }
                
                // Re-validate confirm password
                validateConfirmPassword();
                updateSubmitButton();
            });
            
            // ==========================================
            // CONFIRM PASSWORD VALIDATION
            // ==========================================
            function validateConfirmPassword() {
                const value = passwordConfirmField.value;
                const passwordValue = passwordField.value;
                
                if (value.length === 0) {
                    setFieldInvalid(passwordConfirmField, confirmFeedback, 'Konfirmasi password wajib diisi');
                    validState.passwordConfirm = false;
                } else if (value !== passwordValue) {
                    setFieldInvalid(passwordConfirmField, confirmFeedback, 'Password tidak cocok');
                    validState.passwordConfirm = false;
                } else if (validState.password) {
                    setFieldValid(passwordConfirmField, confirmFeedback, '✓ Password cocok');
                    validState.passwordConfirm = true;
                } else {
                    setFieldInvalid(passwordConfirmField, confirmFeedback, 'Password utama belum valid');
                    validState.passwordConfirm = false;
                }
                updateSubmitButton();
            }
            
            passwordConfirmField.addEventListener('input', validateConfirmPassword);
            
            // ==========================================
            // TERMS CHECKBOX
            // ==========================================
            termsCheckbox.addEventListener('change', function() {
                validState.terms = this.checked;
                updateSubmitButton();
            });
            
            // ==========================================
            // PASSWORD TOGGLE VISIBILITY
            // ==========================================
            // Handled by layout script
            
            // ==========================================
            // TERMS MODAL
            // ==========================================
            termsLink.addEventListener('click', function(e) {
                e.preventDefault();
                modal.classList.add('active');
            });

            function closeModal() {
                modal.classList.remove('active');
            }

            closeBtn.addEventListener('click', closeModal);
            
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closeModal();
                }
            });

            acceptBtn.addEventListener('click', function() {
                termsCheckbox.checked = true;
                validState.terms = true;
                updateSubmitButton();
                closeModal();
            });

            declineBtn.addEventListener('click', function() {
                termsCheckbox.checked = false;
                validState.terms = false;
                updateSubmitButton();
                closeModal();
            });
            
            // ==========================================
            // FORM SUBMIT PREVENTION & SWEETALERT
            // ==========================================
            form.addEventListener('submit', function(e) {
                // FORCE RE-CHECK ALL FIELDS ON SUBMIT
                validState.name = nameField.value.length >= 3 && /^[a-zA-Z\s]+$/.test(nameField.value);
                
                const addressWordCount = countWords(addressField.value);
                validState.address = addressWordCount >= 10 && addressField.value.length >= 30;
                
                const phoneVal = phoneField.value;
                validState.phone = /^\+62[0-9]{8,15}$/.test(phoneVal);
                
                const emailVal = emailField.value.trim().toLowerCase();
                validState.email = /^[a-zA-Z0-9._%+-]+@gmail\.com$/i.test(emailVal);
                
                const passVal = passwordField.value;
                validState.password = passVal.length >= 8 && /[A-Z]/.test(passVal) && /[a-z]/.test(passVal) && /[0-9]/.test(passVal) && /[!@#$%^&*(),.?":{}|<>]/.test(passVal);
                
                validState.passwordConfirm = passwordConfirmField.value === passVal && validState.password && passwordConfirmField.value.length > 0;
                
                validState.terms = termsCheckbox.checked;

                const allValid = Object.values(validState).every(v => v === true);
                
                if (!allValid) {
                    e.preventDefault();
                    
                    // Generate detailed error message
                    let errorMsg = '<ul style="text-align: left; margin-left: 20px; color: #555;">';
                    if (!validState.name) errorMsg += '<li>Nama tidak valid (min 3 huruf)</li>';
                    if (!validState.address) errorMsg += '<li>Alamat kurang lengkap (min 10 kata)</li>';
                    if (!validState.phone) errorMsg += '<li>Nomor HP format salah (harus +62...)</li>';
                    if (!validState.email) errorMsg += '<li>Email harus menggunakan @gmail.com</li>';
                    if (!validState.password) errorMsg += '<li>Password belum cukup kuat (Cek syarat)</li>';
                    if (!validState.passwordConfirm) errorMsg += '<li>Konfirmasi password tidak cocok</li>';
                    if (!validState.terms) errorMsg += '<li>Harap setujui Syarat & Ketentuan</li>';
                    errorMsg += '</ul>';

                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops! Data Belum Lengkap',
                        html: errorMsg,
                        confirmButtonText: 'Perbaiki Sekarang',
                        confirmButtonColor: '#00439c',
                        background: '#1f1f1f',
                        color: '#ffffff',
                        iconColor: '#ef4444',
                        customClass: {
                            popup: 'swal2-dark-popup',
                            title: 'swal2-dark-title',
                            htmlContainer: 'swal2-dark-html'
                        }
                    });
                }
            });
            
            // ==========================================
            // TERMS LANGUAGE TOGGLE
            // ==========================================
            const langIdBtn = document.getElementById('lang-id');
            const langEnBtn = document.getElementById('lang-en');
            const contentId = document.getElementById('content-id');
            const contentEn = document.getElementById('content-en');
            
            function setLanguage(lang) {
                if (lang === 'id') {
                    contentId.style.display = 'block';
                    contentEn.style.display = 'none';
                    langIdBtn.classList.add('active');
                    langIdBtn.style.color = '#fff';
                    langEnBtn.classList.remove('active');
                    langEnBtn.style.color = '#888';
                } else {
                    contentId.style.display = 'none';
                    contentEn.style.display = 'block';
                    langEnBtn.classList.add('active');
                    langEnBtn.style.color = '#fff';
                    langIdBtn.classList.remove('active');
                    langIdBtn.style.color = '#888';
                }
            }
            
            langIdBtn.addEventListener('click', () => setLanguage('id'));
            langEnBtn.addEventListener('click', () => setLanguage('en'));
            
            // Initialize validation summary
            updateSubmitButton();
        });
    </script>
    @endpush
@endsection

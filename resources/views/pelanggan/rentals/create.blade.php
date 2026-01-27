@extends('pelanggan.layout')

@section('pelanggan_content')
<style>
    /* Quantity Control Styles */
    .qty-btn {
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .qty-btn:hover {
        opacity: 0.85;
        transform: scale(1.05);
    }
    .qty-btn:active {
        transform: scale(0.95);
    }
    .qty-minus:hover {
        background: #e5e7eb !important;
    }
    .qty-plus:hover {
        background: #0440a8 !important;
    }
    .qty-input {
        -moz-appearance: textfield;
    }
    .qty-input::-webkit-outer-spin-button,
    .qty-input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    .quantity-control {
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .item-subtotal {
        font-size: 0.9rem;
    }
    #total-price {
        transition: all 0.3s ease;
    }
    
    /* Light Mode - Total Summary Box */
    .total-summary-box {
        background-color: #f0fdf4;
        border: 1px solid #10b981;
    }
    .total-label {
        color: #222222;
    }
    .total-info {
        color: #6B7280;
    }
    .qty-label {
        color: #222222;
    }
    .stock-info {
        color: #6B7280;
    }
    .quantity-control {
        border: 1px solid #E5E7EB;
        border-radius: 8px;
        overflow: hidden;
    }
    .qty-minus {
        border: none;
        background: #f3f4f6;
        color: #374151;
        width: 36px;
        height: 36px;
        font-size: 1.2rem;
        font-weight: bold;
    }
    .qty-plus {
        border: none;
        background: #0652DD;
        color: white;
        width: 36px;
        height: 36px;
        font-size: 1.2rem;
        font-weight: bold;
    }
    .qty-input {
        width: 50px;
        border: none;
        background: white;
        color: #222222;
        font-weight: 600;
        font-size: 1rem;
    }
    
    /* Dark Mode Styles - using [data-theme="dark"] */
    [data-theme="dark"] .text-dark,
    [data-theme="dark"] [style*="color: #222222"],
    [data-theme="dark"] [style*="color:#222222"] {
        color: #f3f4f6 !important;
    }
    
    [data-theme="dark"] .qty-minus {
        background: #374151 !important;
        color: #e5e7eb !important;
    }
    
    [data-theme="dark"] .qty-minus:hover {
        background: #4b5563 !important;
    }
    
    [data-theme="dark"] .qty-input {
        background: #1f2937 !important;
        color: #f3f4f6 !important;
        border-color: #374151 !important;
    }
    
    [data-theme="dark"] .quantity-control {
        border-color: #374151 !important;
        box-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }
    
    [data-theme="dark"] .total-summary-box {
        background-color: rgba(16, 185, 129, 0.2) !important;
        border-color: #059669 !important;
    }
    
    [data-theme="dark"] .total-label {
        color: #f3f4f6 !important;
    }
    
    [data-theme="dark"] .total-info {
        color: #9ca3af !important;
    }
    
    [data-theme="dark"] [style*="color: #6B7280"] {
        color: #9ca3af !important;
    }
    
    [data-theme="dark"] .badge[style*="background-color: #e0e7ff"] {
        background-color: rgba(99, 102, 241, 0.3) !important;
        color: #a5b4fc !important;
        border-color: #6366f1 !important;
    }
    
    [data-theme="dark"] .badge[style*="background-color: #cffafe"] {
        background-color: rgba(6, 182, 212, 0.2) !important;
        color: #67e8f9 !important;
        border-color: #06b6d4 !important;
    }
    
    /* Item details dark mode */
    [data-theme="dark"] .item-details-text {
        color: #9ca3af !important;
    }
    
    [data-theme="dark"] .item-name {
        color: #f3f4f6 !important;
    }
    
    [data-theme="dark"] .qty-label {
        color: #f3f4f6 !important;
    }
    
    [data-theme="dark"] .stock-info {
        color: #9ca3af !important;
    }
    
    /* Subtotal dark mode */
    [data-theme="dark"] .item-subtotal {
        color: #34d399 !important;
    }
    
    /* Profile Modal Styles - Professional & Clean */
    .profile-modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(17, 24, 39, 0.7);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        opacity: 0;
        animation: fadeIn 0.3s ease forwards;
    }
    
    @keyframes fadeIn {
        to { opacity: 1; }
    }
    
    .profile-modal-card {
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        max-width: 500px;
        width: 100%;
        max-height: 90vh; /* Ensure it fits in viewport */
        display: flex;
        flex-direction: column;
        overflow: hidden;
        transform: translateY(20px);
        opacity: 0;
        animation: slideUp 0.4s ease forwards 0.1s;
        border: 1px solid rgba(229, 231, 235, 0.5);
    }
    
    @keyframes slideUp {
        to { 
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .profile-modal-header {
        background: #ffffff;
        padding: 32px 32px 0;
        text-align: center;
        border-bottom: none;
        flex-shrink: 0; /* Header stays fixed */
    }
    
    .profile-modal-icon-container {
        width: 64px; /* Slightly smaller for better fit */
        height: 64px;
        background: #eff6ff;
        color: #2563eb;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        border: 4px solid #dbeafe;
    }
    
    .profile-modal-icon-container i {
        font-size: 28px;
    }
    
    .profile-modal-header h4 {
        color: #111827;
        font-weight: 700;
        font-size: 1.35rem; /* Slightly smaller */
        margin-bottom: 8px;
        letter-spacing: -0.025em;
    }
    
    .profile-modal-body {
        padding: 24px 32px 32px;
        overflow-y: auto; /* Enable scrolling for content */
    }
    
    .profile-info-box {
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 24px;
        display: flex;
        gap: 12px;
        align-items: flex-start;
    }
    
    .profile-info-box i {
        color: #3b82f6;
        font-size: 1.25rem;
        margin-top: 2px;
    }
    
    .profile-info-box p {
        font-size: 0.875rem;
        color: #4b5563;
        margin: 0;
        line-height: 1.5;
    }
    
    .form-group-clean {
        margin-bottom: 20px;
    }
    
    .form-label-clean {
        display: block;
        font-weight: 600;
        font-size: 0.875rem;
        color: #374151;
        margin-bottom: 8px;
    }
    
    .form-control-clean {
        width: 100%;
        padding: 12px 16px;
        font-size: 0.95rem;
        line-height: 1.5;
        color: #111827;
        background-color: #ffffff;
        border: 1px solid #d1d5db;
        border-radius: 10px;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }
    
    .form-control-clean:focus {
        border-color: #3b82f6;
        outline: 0;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
    }
    
    .form-control-clean::placeholder {
        color: #9ca3af;
    }
    
    .btn-submit-clean {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        padding: 14px 20px;
        font-size: 1rem;
        font-weight: 600;
        color: #ffffff;
        background-color: #2563eb;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        transition: background-color 0.2s;
        gap: 8px;
    }
    
    .btn-submit-clean:hover {
        background-color: #1d4ed8;
    }
    
    /* Dark Mode Overrides */
    [data-theme="dark"] .profile-modal-card {
        background: #1f2937;
        border-color: #374151;
    }
    
    [data-theme="dark"] .profile-modal-header {
        background: #1f2937;
    }
    
    [data-theme="dark"] .profile-modal-icon-container {
        background: #1e3a8a;
        color: #60a5fa;
        border-color: #1e40af;
    }
    
    [data-theme="dark"] .profile-modal-header h4 {
        color: #f9fafb;
    }
    
    [data-theme="dark"] .profile-modal-header p {
        color: #9ca3af;
    }
    
    [data-theme="dark"] .profile-info-box {
        background: #111827;
        border-color: #374151;
    }
    
    [data-theme="dark"] .profile-info-box p {
        color: #d1d5db;
    }
    
    [data-theme="dark"] .form-label-clean {
        color: #e5e7eb;
    }
    
    [data-theme="dark"] .form-control-clean {
        background-color: #111827;
        border-color: #4b5563;
        color: #f9fafb;
    }
    
    [data-theme="dark"] .form-control-clean:focus {
        border-color: #60a5fa;
        box-shadow: 0 0 0 3px rgba(96, 165, 250, 0.15);
    }
    
    [data-theme="dark"] .form-control-clean::placeholder {
        color: #6b7280;
    }
    /* Close Button Styles */
    .profile-modal-close {
        position: absolute;
        top: 20px;
        right: 20px;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: transparent;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
        color: #9ca3af;
        z-index: 10;
    }
    
    .profile-modal-close:hover {
        background: #f3f4f6;
        color: #374151;
        transform: rotate(90deg);
    }
    
    [data-theme="dark"] .profile-modal-close {
        color: #6b7280;
    }
    
    [data-theme="dark"] .profile-modal-close:hover {
        background: #374151;
        color: #f3f4f6;
    }

    /* Custom Alert Styles */
    .custom-alert-error {
        background-color: #fee2e2;
        color: #991b1b;
        border: 1px solid #ef4444;
        display: flex;
        align-items: flex-start;
        margin-bottom: 1rem;
    }
    
    .custom-alert-error h6 {
        color: #991b1b;
    }
    
    .custom-alert-error ul {
        color: #7f1d1d;
    }

    /* Dark Mode Alert Overrides */
    [data-theme="dark"] .custom-alert-error {
        background-color: rgba(127, 29, 29, 0.4) !important;
        border-color: #ef4444 !important;
        color: #fca5a5 !important;
    }
    
    [data-theme="dark"] .custom-alert-error h6 {
        color: #fca5a5 !important;
    }
    
    [data-theme="dark"] .custom-alert-error ul {
        color: #fecaca !important;
    }
    
    [data-theme="dark"] .custom-alert-error i {
        color: #ef4444 !important;
    }
</style>

<!-- Profile Completion Modal -->
@if($profileIncomplete ?? false)
<div class="profile-modal-overlay" id="profileModal">
    <div class="profile-modal-card">
        <button type="button" class="profile-modal-close" onclick="closeProfileModal()">
            <i class="bi bi-x-lg"></i>
        </button>
        <div class="profile-modal-header">
            <div class="profile-modal-icon-container">
                <i class="bi bi-person-lines-fill"></i>
            </div>
            <h4>Lengkapi Data Diri</h4>
            <p>Mohon lengkapi informasi kontak Anda untuk kemudahan proses penyewaan dan verifikasi.</p>
        </div>
        <div class="profile-modal-body">
            @if(empty($user->phone ?? auth()->user()->phone) || empty($user->address ?? auth()->user()->address))
            <div class="profile-info-box">
                <i class="bi bi-shield-check"></i>
                <p>Data Anda aman dan hanya digunakan untuk keperluan konfirmasi pesanan.</p>
            </div>
            @endif
            
            <form action="{{ route('pelanggan.profile.update') }}" method="POST" id="profileForm">
                @csrf
                @method('PUT')
                <input type="hidden" name="redirect_back" value="{{ url()->current() }}">
                
                @if(empty($user->phone ?? auth()->user()->phone))
                <div class="form-group-clean">
                    <label class="form-label-clean">Nomor Telepon / WhatsApp</label>
                    <input type="tel" name="phone" class="form-control-clean" 
                           placeholder="+628xxxxxxxxxx" 
                           value="{{ old('phone', '+62') }}" 
                           required pattern="\+62[0-9]{9,13}"
                           title="Nomor harus dimulai dengan +62. Contoh: +6281234567890">
                </div>
                @endif
                
                @if(empty($user->address ?? auth()->user()->address))
                <div class="form-group-clean">
                    <label class="form-label-clean">Alamat Lengkap (minimal 10 kata)</label>
                    <textarea name="address" class="form-control-clean" rows="3" 
                              placeholder="Contoh: Jl. Sudirman No. 123, RT 01 RW 02, Kelurahan Menteng, Kecamatan Menteng, Jakarta Pusat 10310" 
                              required>{{ old('address', $user->address ?? '') }}</textarea>
                    <small class="text-muted">Tuliskan alamat lengkap termasuk nama jalan, nomor, RT/RW, kelurahan, kecamatan, kota, dan kode pos.</small>
                </div>
                @endif
                
                <!-- Hidden fields to preserve existing data -->
                <input type="hidden" name="name" value="{{ $user->name ?? auth()->user()->name }}">
                <input type="hidden" name="email" value="{{ $user->email ?? auth()->user()->email }}">
                @if(!empty($user->phone ?? auth()->user()->phone))
                <input type="hidden" name="phone" value="{{ $user->phone ?? auth()->user()->phone }}">
                @endif
                @if(!empty($user->address ?? auth()->user()->address))
                <input type="hidden" name="address" value="{{ $user->address ?? auth()->user()->address }}">
                @endif
                
                <button type="submit" class="btn-submit-clean">
                    Simpan dan Lanjutkan
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function closeProfileModal() {
        const modal = document.getElementById('profileModal');
        modal.style.opacity = '0';
        setTimeout(() => {
            modal.style.display = 'none';
        }, 300);
    }
</script>
@endif

<div class="container-fluid">
    <!-- Header -->
    <div class="card card-hover-lift mb-4 animate-fade-in">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1 fw-bold" style="color: #222222;"><i class="bi bi-plus-circle me-2" style="color: #1E40FF;"></i><span class="gradient-text">Buat Penyewaan</span></h4>
                    <p class="mb-0 small" style="color: #6B7280;">Lengkapi detail penyewaan Anda</p>
                </div>
            </div>
        </div>
    </div>

    @if($errors->any())
        <div class="alert rounded-3 custom-alert-error">
            <i class="bi bi-exclamation-triangle-fill me-3 fs-4 mt-1"></i>
            <div>
                <h6 class="fw-bold mb-1">Terjadi Kesalahan!</h6>
                <ul class="mb-0 ps-3 small">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="row g-4">
        <!-- Left Column: Items to Rent -->
        <div class="col-lg-6">
            <div class="card card-hover-lift h-100">
                <div class="card-body">
                    <h5 class="mb-4 fw-bold" style="color: #222222;"><i class="bi bi-cart-check me-2" style="color: #10b981;"></i>Item yang Akan Disewa</h5>

                    @forelse($cartItems as $item)
                        @php
                            $itemModel = null;
                            $imageField = '';
                            $modelClass = null;

                            // Determine model class and image field
                            // Determine model class and image field
                            $itemType = $item->type ?? null;
                            $itemId = $item->item_id ?? $item->id ?? null;

                            switch($itemType) {
                                case 'unitps':
                                    $modelClass = 'App\\Models\\UnitPS';
                                    $imageField = 'foto';
                                    break;
                                case 'game':
                                    $modelClass = 'App\\Models\\Game';
                                    $imageField = 'gambar';
                                    break;
                                case 'accessory':
                                    $modelClass = 'App\\Models\\Accessory';
                                    $imageField = 'gambar';
                                    break;
                            }

                            if($modelClass && $itemId) {
                                $itemModel = $modelClass::find($itemId);
                            }

                            // Get item details
                            // Get item details
                            $itemName = $item->name ?? 'Unknown';
                            $itemPrice = $item->price ?? 0;
                            $itemPriceType = $item->price_type ?? 'per_hari';
                            $itemQuantity = $item->quantity ?? 1;
                        @endphp

                        <div class="d-flex align-items-start mb-3 pb-3" style="border-bottom: 1px solid #E5E7EB;">
                            <!-- Image -->
                            <div class="flex-shrink-0 me-3">
                                @if($itemModel && $itemModel->$imageField)
                                    <img src="{{ str_starts_with($itemModel->$imageField, 'http') ? $itemModel->$imageField : asset('storage/' . $itemModel->$imageField) }}"
                                         alt="{{ $itemName }}"
                                         class="rounded shadow-sm"
                                         style="width: 100px; height: 100px; object-fit: cover;">
                                @else
                                    <div class="rounded d-flex align-items-center justify-content-center" style="background-color: #F5F6FA; border: 1px solid #E5E7EB; color: #6B7280;" style="width: 100px; height: 100px;">
                                        <i class="bi bi-box-seam fs-3"></i>
                                    </div>
                                @endif
                            </div>

                            <!-- Details -->
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-2" style="color: #222222;">{{ $itemName }}</h6>

                                @if($itemModel)
                                    @if($itemType == 'unitps')
                                        <div class="small mb-1" style="color: #6B7280;"><i class="bi bi-cpu me-1"></i> Model: {{ $itemModel->model ?? 'N/A' }}</div>
                                        <div class="small mb-1" style="color: #6B7280;"><i class="bi bi-tag me-1"></i> Merek: {{ $itemModel->brand ?? 'N/A' }}</div>
                                        <div class="small mb-1" style="color: #6B7280;"><i class="bi bi-box-seam me-1"></i> Stok: {{ $itemModel->stock ?? 0 }}</div>
                                    @elseif($itemType == 'game')
                                        <div class="small mb-1" style="color: #6B7280;"><i class="bi bi-controller me-1"></i> Platform: {{ $itemModel->platform ?? 'N/A' }}</div>
                                        <div class="small mb-1" style="color: #6B7280;"><i class="bi bi-joystick me-1"></i> Genre: {{ $itemModel->genre ?? 'N/A' }}</div>
                                        <div class="small mb-1" style="color: #6B7280;"><i class="bi bi-box-seam me-1"></i> Stok: {{ $itemModel->stok ?? 0 }}</div>
                                    @elseif($itemType == 'accessory')
                                        <div class="small mb-1" style="color: #6B7280;"><i class="bi bi-headset me-1"></i> Jenis: {{ $itemModel->jenis ?? 'N/A' }}</div>
                                        <div class="small mb-1" style="color: #6B7280;"><i class="bi bi-box-seam me-1"></i> Stok: {{ $itemModel->stok ?? 0 }}</div>
                                    @endif
                                @endif

                                <div class="mt-2">
                                    <span class="badge" style="background-color: #e0e7ff; color: #3730a3; border: 1px solid #6366f1; font-weight: 600;">Rp {{ number_format($itemPrice, 0, ',', '.') }} {{ $itemPriceType == 'per_jam' ? 'per jam' : 'per hari' }}</span>
                                </div>
                                
                                <!-- Quantity Control -->
                                @if($itemType == 'game' || $itemType == 'accessory')
                                    @php
                                        $maxStock = $itemType == 'game' ? ($itemModel->stok ?? 1) : ($itemModel->stok ?? 1);
                                    @endphp
                                    <div class="mt-3 d-flex align-items-center">
                                        <span class="me-2 small fw-bold qty-label">Jumlah:</span>
                                        <div class="quantity-control d-flex align-items-center">
                                            <button type="button" class="btn btn-sm qty-btn qty-minus" 
                                                    data-item-type="{{ $itemType }}" 
                                                    data-item-id="{{ $itemId }}" 
                                                    data-price="{{ $itemPrice }}"
                                                    data-max-stock="{{ $maxStock }}">
                                                <i class="bi bi-dash"></i>
                                            </button>
                                            <input type="number" 
                                                class="form-control quantity-input text-center p-1" 
                                                name="quantity" 
                                                value="1" 
                                                min="1" 
                                                max="{{ $itemType === 'unitps' ? $item->stock : $item->stok }}"
                                                oninput="if(this.value < 1) this.value = 1;" 
                                                onkeydown="return event.keyCode !== 69 && event.keyCode !== 189"
                                                   data-item-type="{{ $itemType }}" 
                                                   data-item-id="{{ $itemId }}" 
                                                   data-price="{{ $itemPrice }}"
                                                   data-max-stock="{{ $maxStock }}"
                                                   readonly>
                                            <button type="button" class="btn btn-sm qty-btn qty-plus" 
                                                    data-item-type="{{ $itemType }}" 
                                                    data-item-id="{{ $itemId }}" 
                                                    data-price="{{ $itemPrice }}"
                                                    data-max-stock="{{ $maxStock }}">
                                                <i class="bi bi-plus"></i>
                                            </button>
                                        </div>
                                        <span class="ms-2 small stock-info">(Stok: {{ $maxStock }})</span>
                                    </div>
                                    <div class="mt-2">
                                        <span class="item-subtotal fw-bold" data-item-type="{{ $itemType }}" data-item-id="{{ $itemId }}" style="color: #10b981;">
                                            Subtotal: Rp {{ number_format($itemPrice * $itemQuantity, 0, ',', '.') }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="bi bi-cart-x" style="color: #6B7280; font-size: 3rem;"></i>
                            <p class="mt-3" style="color: #6B7280;">Keranjang kosong</p>
                        </div>
                    @endforelse

                    <!-- Action Buttons -->
                    <!-- Total Price Summary (Langkah 11-12 UC006: Hitung total + ongkir) -->
                    <div class="mt-4 p-3 rounded total-summary-box">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="small total-label">Subtotal:</span>
                            <span id="subtotal-price" class="fw-bold" style="color: #6B7280;">Rp 0</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2" id="shipping-row" style="display: none !important;">
                            <span class="small total-label">Ongkos Kirim:</span>
                            <span id="shipping-cost" class="fw-bold" style="color: #6B7280;">Rp 0</span>
                        </div>
                        <hr class="my-2" style="border-color: #10b981;">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold total-label"><i class="bi bi-calculator me-2"></i>Total Harga:</span>
                            <span id="total-price" class="fs-5 fw-bold" style="color: #10b981;">Rp 0</span>
                        </div>
                        <div class="small mt-1 total-info">
                            <i class="bi bi-info-circle me-1"></i> <span id="total-days">Pilih tanggal untuk melihat total</span>
                        </div>
                    </div>

                    @if(isset($directItem) && $directItem)
                        <div class="d-flex gap-2 mt-4">
                            <a href="javascript:history.back()" class="btn btn-sm rounded-pill px-3 flex-fill" style="color: #0652DD; border: 1px solid #0652DD; background-color: transparent;" onmouseover="this.style.backgroundColor='#0652DD'; this.style.color='white';" onmouseout="this.style.backgroundColor='transparent'; this.style.color='#0652DD';">
                                <i class="bi bi-arrow-left me-1"></i> Kembali
                            </a>
                            @if(!$cartItems->isEmpty())
                                @php $firstItem = $cartItems->first(); @endphp
                                <form id="add-to-cart-form" method="POST" action="{{ route('pelanggan.cart.add') }}" class="flex-fill">
                                    @csrf
                                    <input type="hidden" name="type" value="{{ $firstItem->type }}">
                                    <input type="hidden" name="id" value="{{ $firstItem->item_id ?? $firstItem->id ?? null }}">
                                    <input type="hidden" name="price_type" value="{{ $firstItem->price_type }}">
                                    <input type="hidden" name="quantity" id="cart-quantity" value="{{ $firstItem->quantity }}">
                                    <button type="button" class="btn btn-primary w-100" onclick="addToCart()">
                                        <span id="cart-button-text"><i class="bi bi-cart-plus me-1"></i> Tambah ke Keranjang</span>
                                        <span id="cart-button-spinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                    </button>
                                </form>
                            @endif
                        </div>
                    @else
                        <div class="mt-4">
                            <a href="{{ route('pelanggan.cart.index') }}" class="btn btn-sm rounded-pill px-3 w-100" style="color: #0652DD; border: 1px solid #0652DD; background-color: transparent;" onmouseover="this.style.backgroundColor='#0652DD'; this.style.color='white';" onmouseout="this.style.backgroundColor='transparent'; this.style.color='#0652DD';">
                                <i class="bi bi-arrow-left me-1"></i> Kembali ke Keranjang
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column: Rental Details Form -->
        <div class="col-lg-6">
            <div class="card card-hover-lift h-100">
                <div class="card-body">
                    <h5 class="mb-4 fw-bold" style="color: #222222;"><i class="bi bi-calendar-check me-2" style="color: #1E40FF;"></i>Detail Penyewaan</h5>

                    <form method="POST" action="{{ route('pelanggan.rentals.store') }}{{ isset($directItem) && $directItem && request()->has('type') && request()->has('id') ? '?type=' . request('type') . '&id=' . request('id') : '' }}">
                        @csrf

                        @if(isset($directItem) && $directItem && request()->has('type') && request()->has('id'))
                            <input type="hidden" name="type" value="{{ request('type') }}">
                            <input type="hidden" name="id" value="{{ request('id') }}">
                            <input type="hidden" name="quantity" id="form-quantity" value="1">
                        @endif

                        <!-- Rental Date -->
                        <div class="mb-4">
                            <label for="rental_date" class="form-label fw-bold" style="color: #222222;">
                                <i class="bi bi-calendar-event me-1" style="color: #10b981;"></i> Tanggal Mulai Sewa
                            </label>
                            <input type="date"
                                   id="rental_date"
                                   name="rental_date"
                                   value="{{ old('rental_date', date('Y-m-d')) }}"
                                   min="{{ date('Y-m-d') }}"
                                   required
                                   class="form-control" style="background-color: #FFFFFF; border-color: #A3A3A3; color: #222222;" @error('rental_date') style="border-color: #ef4444;" @enderror>
                            @error('rental_date')
                                <div class="invalid-feedback" style="color: #ef4444;">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Duration (1-7 Days) -->
                        <div class="mb-4">
                            <label for="duration" class="form-label fw-bold" style="color: #222222;">
                                <i class="bi bi-clock-history me-1" style="color: #f59e0b;"></i> Durasi Sewa
                            </label>
                            <select class="form-select" id="duration" name="duration" required style="background-color: #FFFFFF; border-color: #A3A3A3; color: #222222;">
                                @for($i = 1; $i <= 30; $i++)
                                    <option value="{{ $i }}" {{ old('duration') == $i ? 'selected' : '' }}>{{ $i }} Hari</option>
                                @endfor
                            </select>
                            <div class="form-text small" style="color: #6B7280;">
                                <i class="bi bi-info-circle me-1"></i> Pilih durasi sewa antara 1 sampai 30 hari.
                            </div>
                        </div>

                        <!-- Hidden Return Date (Calculated via JS) -->
                        <input type="hidden" id="return_date" name="return_date" value="{{ old('return_date') }}">
                        
                        <!-- Display Calculated Return Date -->
                        <div class="mb-4">
                            <label class="form-label fw-bold" style="color: #222222;">
                                <i class="bi bi-calendar-check me-1" style="color: #10b981;"></i> Tanggal Pengembalian
                            </label>
                            <input type="text" id="return_date_display" class="form-control" readonly style="background-color: #F3F4F6; border-color: #E5E7EB; color: #4B5563;">
                        </div>

                        <!-- Delivery Method (Langkah 10 UC006) -->
                        <div class="mb-4">
                            <label class="form-label fw-bold" style="color: #222222;">
                                <i class="bi bi-truck me-1" style="color: #8b5cf6;"></i> Metode Pengiriman
                            </label>
                            <div class="row g-3">
                                <div class="col-6">
                                    <input type="radio" class="btn-check" name="delivery_method" id="pickup" value="pickup" {{ old('delivery_method', 'pickup') == 'pickup' ? 'checked' : '' }} required>
                                    <label class="btn btn-outline-primary w-100 py-3 delivery-option" for="pickup">
                                        <i class="bi bi-shop fs-4 d-block mb-2"></i>
                                        <span class="fw-bold">Ambil di Toko</span>
                                        <small class="d-block text-muted mt-1">Gratis</small>
                                    </label>
                                </div>
                                <div class="col-6">
                                    <input type="radio" class="btn-check" name="delivery_method" id="delivery" value="delivery" {{ old('delivery_method') == 'delivery' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-primary w-100 py-3 delivery-option" for="delivery">
                                        <i class="bi bi-truck fs-4 d-block mb-2"></i>
                                        <span class="fw-bold">Diantar</span>
                                        <small class="d-block text-muted mt-1">+ Rp 15.000</small>
                                    </label>
                                </div>
                            </div>
                            @error('delivery_method')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                            
                            <!-- Delivery Address Preview (shown when delivery selected) -->
                            <div id="delivery-address-preview" class="mt-3 p-3 rounded" style="background-color: #f0f9ff; border: 1px solid #0ea5e9; display: none;">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-geo-alt-fill me-2" style="color: #0ea5e9;"></i>
                                    <div>
                                        <small class="fw-bold" style="color: #0369a1;">Alamat Pengiriman:</small>
                                        <p class="mb-0 small" style="color: #0c4a6e;">{{ auth()->user()->address ?? 'Alamat belum diisi' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="mb-4">
                            <label for="notes" class="form-label fw-bold" style="color: #222222;">
                                <i class="bi bi-sticky me-1" style="color: #06b6d4;"></i> Catatan (Opsional)
                            </label>
                            <textarea id="notes"
                                      name="notes"
                                      rows="4"
                                      class="form-control" style="background-color: #FFFFFF; border-color: #A3A3A3; color: #222222;" @error('notes') style="border-color: #ef4444;" @enderror
                                      placeholder="Tambahkan catatan khusus untuk penyewaan ini...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback" style="color: #ef4444;">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-success w-100 btn-lg fw-bold">
                            <i class="bi bi-check-circle me-2"></i> Buat Penyewaan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Date validation and Quantity Control
document.addEventListener('DOMContentLoaded', function() {
    const rentalDateInput = document.getElementById('rental_date');
    const durationInput = document.getElementById('duration');
    const returnDateInput = document.getElementById('return_date');
    const returnDateDisplay = document.getElementById('return_date_display');
    const pickupRadio = document.getElementById('pickup');
    const deliveryRadio = document.getElementById('delivery');
    const deliveryAddressPreview = document.getElementById('delivery-address-preview');
    const shippingRow = document.getElementById('shipping-row');
    
    // Shipping cost constant
    const SHIPPING_COST = 15000;
    
    // Calculate Return Date based on Duration
    function updateReturnDate() {
        const rentalDate = rentalDateInput.value ? new Date(rentalDateInput.value) : new Date();
        const duration = parseInt(durationInput.value) || 1;
        
        // Calculate return date (rental date + duration)
        const returnDate = new Date(rentalDate);
        returnDate.setDate(rentalDate.getDate() + duration);
        
        // Format for input value (YYYY-MM-DD)
        const year = returnDate.getFullYear();
        const month = String(returnDate.getMonth() + 1).padStart(2, '0');
        const day = String(returnDate.getDate()).padStart(2, '0');
        const formattedDate = `${year}-${month}-${day}`;
        
        // Update hidden input
        returnDateInput.value = formattedDate;
        
        // Update display input (dd/mm/yyyy for better readability)
        const displayDay = String(returnDate.getDate()).padStart(2, '0');
        const displayMonth = String(returnDate.getMonth() + 1).padStart(2, '0');
        returnDateDisplay.value = `${displayDay}/${displayMonth}/${year}`;
        
        calculateTotal();
    }
    
    // Listen for changes
    if(rentalDateInput) rentalDateInput.addEventListener('change', updateReturnDate);
    if(durationInput) durationInput.addEventListener('change', updateReturnDate);
    
    // Initialize initial date
    updateReturnDate();

    // Item prices data
    const itemPrices = {};
    document.querySelectorAll('.qty-input').forEach(input => {
        const key = `${input.dataset.itemType}_${input.dataset.itemId}`;
        itemPrices[key] = {
            price: parseFloat(input.dataset.price),
            quantity: parseInt(input.value),
            maxStock: parseInt(input.dataset.maxStock)
        };
    });
    
    // Format number to Indonesian Rupiah
    function formatRupiah(number) {
        return 'Rp ' + number.toLocaleString('id-ID');
    }
    
    // Get current shipping cost based on delivery method
    function getShippingCost() {
        return deliveryRadio && deliveryRadio.checked ? SHIPPING_COST : 0;
    }
    
    // Update shipping display
    function updateShippingDisplay() {
        const isDelivery = deliveryRadio && deliveryRadio.checked;
        
        if (deliveryAddressPreview) {
            deliveryAddressPreview.style.display = isDelivery ? 'block' : 'none';
        }
        
        if (shippingRow) {
            shippingRow.style.display = isDelivery ? 'flex' : 'none';
            shippingRow.style.setProperty('display', isDelivery ? 'flex' : 'none', 'important');
        }
        
        const shippingCostEl = document.getElementById('shipping-cost');
        if (shippingCostEl) {
            shippingCostEl.textContent = isDelivery ? formatRupiah(SHIPPING_COST) : 'Rp 0';
        }
        
        calculateTotal();
    }
    
    // Calculate and update total price
    function calculateTotal() {
        const duration = parseInt(durationInput.value) || 0;
        let subtotalPrice = 0;
        
        // Calculate subtotal
        Object.keys(itemPrices).forEach(key => {
            const item = itemPrices[key];
            subtotalPrice += item.price * item.quantity * duration;
        });
        
        // If no items with quantity control, use base price
        if (Object.keys(itemPrices).length === 0) {
            const priceText = document.querySelector('.badge[style*="background-color: #e0e7ff"]');
            if (priceText) {
                const priceMatch = priceText.textContent.match(/Rp\s*([\d.]+)/);
                if (priceMatch) {
                    const basePrice = parseFloat(priceMatch[1].replace(/\./g, ''));
                    subtotalPrice = basePrice * duration;
                }
            }
        }
        
        document.getElementById('total-days').textContent = `Durasi: ${duration} hari`;
        
        // Calculate total with shipping
        const shippingCost = getShippingCost();
        const totalPrice = subtotalPrice + shippingCost;
        
        // Update display
        document.getElementById('subtotal-price').textContent = formatRupiah(subtotalPrice);
        document.getElementById('total-price').textContent = formatRupiah(totalPrice);
    }
    
    // Delivery method change handlers
    if (pickupRadio) {
        pickupRadio.addEventListener('change', updateShippingDisplay);
    }
    if (deliveryRadio) {
        deliveryRadio.addEventListener('change', updateShippingDisplay);
    }
    
    // Update subtotal for individual item
    function updateSubtotal(itemType, itemId, quantity, price) {
        const subtotalEl = document.querySelector(`.item-subtotal[data-item-type="${itemType}"][data-item-id="${itemId}"]`);
        if (subtotalEl) {
            const rentalDate = rentalDateInput.value ? new Date(rentalDateInput.value) : null;
            const returnDate = returnDateInput.value ? new Date(returnDateInput.value) : null;
            
            let days = 1;
            if (rentalDate && returnDate && returnDate > rentalDate) {
                const timeDiff = returnDate.getTime() - rentalDate.getTime();
                days = Math.ceil(timeDiff / (1000 * 3600 * 24));
            }
            
            const subtotal = price * quantity * days;
            subtotalEl.textContent = `Subtotal: ${formatRupiah(subtotal)} ${days > 1 ? `(${days} hari)` : ''}`;
        }
    }
    
    // Quantity minus button handler
    document.querySelectorAll('.qty-minus').forEach(btn => {
        btn.addEventListener('click', function() {
            const itemType = this.dataset.itemType;
            const itemId = this.dataset.itemId;
            const price = parseFloat(this.dataset.price);
            const input = document.querySelector(`.qty-input[data-item-type="${itemType}"][data-item-id="${itemId}"]`);
            
            let currentQty = parseInt(input.value);
            if (currentQty > 1) {
                currentQty--;
                input.value = currentQty;
                
                // Update stored quantity
                const key = `${itemType}_${itemId}`;
                if (itemPrices[key]) {
                    itemPrices[key].quantity = currentQty;
                }
                
                // Update hidden form fields
                updateHiddenQuantity(currentQty);
                
                // Update subtotal and total
                updateSubtotal(itemType, itemId, currentQty, price);
                calculateTotal();
                
                console.log(`Quantity decreased: ${itemType}_${itemId} = ${currentQty}`);
            }
        });
    });
    
    // Quantity plus button handler
    document.querySelectorAll('.qty-plus').forEach(btn => {
        btn.addEventListener('click', function() {
            const itemType = this.dataset.itemType;
            const itemId = this.dataset.itemId;
            const price = parseFloat(this.dataset.price);
            const maxStock = parseInt(this.dataset.maxStock);
            const input = document.querySelector(`.qty-input[data-item-type="${itemType}"][data-item-id="${itemId}"]`);
            
            let currentQty = parseInt(input.value);
            if (currentQty < maxStock) {
                currentQty++;
                input.value = currentQty;
                
                // Update stored quantity
                const key = `${itemType}_${itemId}`;
                if (itemPrices[key]) {
                    itemPrices[key].quantity = currentQty;
                }
                
                // Update hidden form fields
                updateHiddenQuantity(currentQty);
                
                // Update subtotal and total
                updateSubtotal(itemType, itemId, currentQty, price);
                calculateTotal();
                
                console.log(`Quantity increased: ${itemType}_${itemId} = ${currentQty}`);
            } else {
                // Show max stock warning
                alert(`Stok maksimal tersedia: ${maxStock}`);
            }
        });
    });
    
    // Update hidden quantity fields for form submission
    function updateHiddenQuantity(qty) {
        const cartQtyInput = document.getElementById('cart-quantity');
        const formQtyInput = document.getElementById('form-quantity');
        
        if (cartQtyInput) {
            cartQtyInput.value = qty;
        }
        if (formQtyInput) {
            formQtyInput.value = qty;
        }
    }
    
    // Set minimum return date based on rental date
    rentalDateInput.addEventListener('change', function() {
        const rentalDate = new Date(this.value);
        rentalDate.setDate(rentalDate.getDate() + 1);
        const minReturnDate = rentalDate.toISOString().split('T')[0];
        returnDateInput.min = minReturnDate;
        
        // Clear return date if it's before the new minimum
        if (!returnDateInput.value || new Date(returnDateInput.value) < rentalDate) {
            returnDateInput.value = '';
        }
        
        // Recalculate total
        calculateTotal();
        
        // Update all subtotals
        document.querySelectorAll('.qty-input').forEach(input => {
            updateSubtotal(
                input.dataset.itemType, 
                input.dataset.itemId, 
                parseInt(input.value), 
                parseFloat(input.dataset.price)
            );
        });
    });
    
    // Validate return date is after rental date
    returnDateInput.addEventListener('change', function() {
        const returnDate = new Date(this.value);
        const rentalDate = new Date(rentalDateInput.value);
        
        if (returnDate <= rentalDate) {
            this.value = '';
            alert('Tanggal kembali harus setelah tanggal mulai sewa.');
        } else {
            // Recalculate total
            calculateTotal();
            
            // Update all subtotals
            document.querySelectorAll('.qty-input').forEach(input => {
                updateSubtotal(
                    input.dataset.itemType, 
                    input.dataset.itemId, 
                    parseInt(input.value), 
                    parseFloat(input.dataset.price)
                );
            });
        }
    });
    
    // Initial calculation and shipping display
    updateShippingDisplay();
    calculateTotal();
});
</script>

<script>
function addToCart() {
    const form = document.getElementById('add-to-cart-form');
    const formData = new FormData(form);
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Show loading state
    document.getElementById('cart-button-text').classList.add('d-none');
    document.getElementById('cart-button-spinner').classList.remove('d-none');
    const submitButton = form.querySelector('button[type="button"]');
    submitButton.disabled = true;

    fetch('{{ route("pelanggan.cart.add") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            if(window.showFlashMessage) {
                window.showFlashMessage(data.message, 'success');
            } else {
                alert(data.message);
            }

            // Optional: Update cart count or page content if needed
            // You might want to reload specific parts of the page here
        } else {
            if(window.showFlashMessage) {
                window.showFlashMessage(data.message || 'Gagal menambahkan ke keranjang', 'error');
            } else {
                alert(data.message || 'Gagal menambahkan ke keranjang');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        if(window.showFlashMessage) {
            window.showFlashMessage('Terjadi kesalahan saat menambahkan ke keranjang', 'error');
        } else {
            alert('Terjadi kesalahan saat menambahkan ke keranjang');
        }
    })
    .finally(() => {
        // Reset button state
        document.getElementById('cart-button-text').classList.remove('d-none');
        document.getElementById('cart-button-spinner').classList.add('d-none');
        submitButton.disabled = false;
    });
}
</script>

@endsection
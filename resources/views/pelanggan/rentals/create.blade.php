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
    
    /* Profile Modal Styles */
    .profile-modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        animation: fadeIn 0.3s ease;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    @keyframes slideUp {
        from { 
            opacity: 0;
            transform: translateY(30px) scale(0.95);
        }
        to { 
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }
    
    .profile-modal-card {
        background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
        border-radius: 24px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        max-width: 480px;
        width: 100%;
        overflow: hidden;
        animation: slideUp 0.4s ease;
    }
    
    .profile-modal-header {
        background: linear-gradient(135deg, #0652DD 0%, #1E40FF 50%, #3b82f6 100%);
        padding: 32px 28px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    
    .profile-modal-header::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 60%);
        animation: pulse 3s ease-in-out infinite;
    }
    
    @keyframes pulse {
        0%, 100% { transform: scale(1); opacity: 0.5; }
        50% { transform: scale(1.1); opacity: 0.3; }
    }
    
    .profile-modal-icon {
        width: 80px;
        height: 80px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
        position: relative;
        z-index: 1;
    }
    
    .profile-modal-icon i {
        font-size: 2.5rem;
        color: white;
    }
    
    .profile-modal-header h4 {
        color: white;
        font-weight: 700;
        margin-bottom: 8px;
        position: relative;
        z-index: 1;
    }
    
    .profile-modal-header p {
        color: rgba(255, 255, 255, 0.85);
        font-size: 0.9rem;
        margin: 0;
        position: relative;
        z-index: 1;
    }
    
    .profile-modal-body {
        padding: 28px;
    }
    
    .profile-form-group {
        margin-bottom: 20px;
    }
    
    .profile-form-label {
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
        font-size: 0.9rem;
    }
    
    .profile-form-label i {
        color: #0652DD;
        font-size: 1.1rem;
    }
    
    .profile-form-label .required {
        color: #ef4444;
        font-weight: 700;
    }
    
    .profile-form-input {
        width: 100%;
        padding: 14px 16px;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        font-size: 1rem;
        color: #1f2937;
        background: #f9fafb;
        transition: all 0.3s ease;
    }
    
    .profile-form-input:focus {
        outline: none;
        border-color: #0652DD;
        background: white;
        box-shadow: 0 0 0 4px rgba(6, 82, 221, 0.1);
    }
    
    .profile-form-input::placeholder {
        color: #9ca3af;
    }
    
    .profile-form-textarea {
        min-height: 100px;
        resize: vertical;
    }
    
    .profile-submit-btn {
        width: 100%;
        padding: 16px;
        background: linear-gradient(135deg, #0652DD 0%, #1E40FF 100%);
        color: white;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
    
    .profile-submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(6, 82, 221, 0.35);
    }
    
    .profile-submit-btn:active {
        transform: translateY(0);
    }
    
    .profile-submit-btn i {
        font-size: 1.2rem;
    }
    
    .profile-info-badge {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 14px 16px;
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        border-radius: 12px;
        margin-bottom: 24px;
        border: 1px solid #f59e0b;
    }
    
    .profile-info-badge i {
        color: #d97706;
        font-size: 1.3rem;
        margin-top: 2px;
    }
    
    .profile-info-badge p {
        color: #92400e;
        font-size: 0.85rem;
        margin: 0;
        line-height: 1.5;
    }
    
    /* Dark Mode for Profile Modal */
    [data-theme="dark"] .profile-modal-card {
        background: linear-gradient(145deg, #1f2937 0%, #111827 100%);
    }
    
    [data-theme="dark"] .profile-modal-header {
        background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 50%, #2563eb 100%);
    }
    
    [data-theme="dark"] .profile-form-label {
        color: #e5e7eb;
    }
    
    [data-theme="dark"] .profile-form-label i {
        color: #60a5fa;
    }
    
    [data-theme="dark"] .profile-form-input {
        background: #374151;
        border-color: #4b5563;
        color: #f3f4f6;
    }
    
    [data-theme="dark"] .profile-form-input:focus {
        border-color: #3b82f6;
        background: #1f2937;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.2);
    }
    
    [data-theme="dark"] .profile-form-input::placeholder {
        color: #6b7280;
    }
    
    [data-theme="dark"] .profile-info-badge {
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.2) 0%, rgba(217, 119, 6, 0.2) 100%);
        border-color: rgba(245, 158, 11, 0.5);
    }
    
    [data-theme="dark"] .profile-info-badge i {
        color: #fbbf24;
    }
    
    [data-theme="dark"] .profile-info-badge p {
        color: #fcd34d;
    }
    
    [data-theme="dark"] .profile-submit-btn {
        background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
    }
    
    [data-theme="dark"] .profile-submit-btn:hover {
        box-shadow: 0 8px 20px rgba(59, 130, 246, 0.4);
    }
</style>

<!-- Profile Completion Modal -->
@if($profileIncomplete ?? false)
<div class="profile-modal-overlay" id="profileModal">
    <div class="profile-modal-card">
        <div class="profile-modal-header">
            <div class="profile-modal-icon">
                <i class="bi bi-person-badge"></i>
            </div>
            <h4>Lengkapi Profil Anda</h4>
            <p>Informasi ini diperlukan untuk melanjutkan penyewaan</p>
        </div>
        <div class="profile-modal-body">
            <div class="profile-info-badge">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <p><strong>Penting!</strong> Nomor telepon dan alamat wajib diisi agar kami dapat menghubungi Anda terkait penyewaan.</p>
            </div>
            
            <form action="{{ route('pelanggan.profile.update') }}" method="POST" id="profileForm">
                @csrf
                @method('PUT')
                <input type="hidden" name="redirect_back" value="{{ url()->current() }}">
                
                @if(empty($user->phone ?? auth()->user()->phone))
                <div class="profile-form-group">
                    <label class="profile-form-label">
                        <i class="bi bi-telephone-fill"></i>
                        Nomor Telepon <span class="required">*</span>
                    </label>
                    <input type="tel" name="phone" class="profile-form-input" 
                           placeholder="Contoh: 081234567890" 
                           value="{{ old('phone', $user->phone ?? '') }}" 
                           required pattern="[0-9]{10,15}"
                           title="Masukkan nomor telepon yang valid (10-15 digit)">
                </div>
                @endif
                
                @if(empty($user->address ?? auth()->user()->address))
                <div class="profile-form-group">
                    <label class="profile-form-label">
                        <i class="bi bi-geo-alt-fill"></i>
                        Alamat Lengkap <span class="required">*</span>
                    </label>
                    <textarea name="address" class="profile-form-input profile-form-textarea" 
                              placeholder="Masukkan alamat lengkap Anda..." 
                              required>{{ old('address', $user->address ?? '') }}</textarea>
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
                
                <button type="submit" class="profile-submit-btn">
                    <i class="bi bi-check-circle-fill"></i>
                    Simpan & Lanjutkan
                </button>
            </form>
        </div>
    </div>
</div>
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
        <div class="alert rounded-3" style="background-color: #fee2e2; color: #991b1b; border: 1px solid #ef4444; display: flex; align-items: flex-start; margin-bottom: 1rem;">
            <i class="bi bi-exclamation-triangle-fill me-3 fs-4 mt-1"></i>
            <div>
                <h6 class="fw-bold mb-1" style="color: #991b1b;">Terjadi Kesalahan!</h6>
                <ul class="mb-0 ps-3 small" style="color: #7f1d1d;">
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
                            if(isset($directItem) && $directItem) {
                                $itemType = $item['type'] ?? null;
                                $itemId = $item['item_id'] ?? $item['id'] ?? null;
                            } else {
                                $itemType = $item->type ?? null;
                                $itemId = $item->item_id ?? null;
                            }

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
                            if(isset($directItem) && $directItem) {
                                $itemName = $item['name'] ?? 'Unknown';
                                $itemPrice = $item['price'] ?? 0;
                                $itemPriceType = $item['price_type'] ?? 'per_hari';
                                $itemQuantity = $item['quantity'] ?? 1;
                            } else {
                                $itemName = $item->name ?? 'Unknown';
                                $itemPrice = $item->price ?? 0;
                                $itemPriceType = $item->price_type ?? 'per_hari';
                                $itemQuantity = $item->quantity ?? 1;
                            }
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
                                                   class="qty-input text-center" 
                                                   name="quantities[{{ $itemType }}_{{ $itemId }}]" 
                                                   value="{{ $itemQuantity }}" 
                                                   min="1" 
                                                   max="{{ $maxStock }}"
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
                    <!-- Total Price Summary -->
                    <div class="mt-4 p-3 rounded total-summary-box">
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
                                    @if(is_array($firstItem))
                                        <input type="hidden" name="type" value="{{ $firstItem['type'] }}">
                                        <input type="hidden" name="id" value="{{ $firstItem['item_id'] ?? $firstItem['id'] ?? null }}">
                                        <input type="hidden" name="price_type" value="{{ $firstItem['price_type'] }}">
                                        <input type="hidden" name="quantity" id="cart-quantity" value="{{ $firstItem['quantity'] ?? 1 }}">
                                    @else
                                        <input type="hidden" name="type" value="{{ $firstItem->type }}">
                                        <input type="hidden" name="id" value="{{ $firstItem->item_id }}">
                                        <input type="hidden" name="price_type" value="{{ $firstItem->price_type }}">
                                        <input type="hidden" name="quantity" id="cart-quantity" value="{{ $firstItem->quantity }}">
                                    @endif
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

                        <!-- Return Date -->
                        <div class="mb-4">
                            <label for="return_date" class="form-label fw-bold" style="color: #222222;">
                                <i class="bi bi-calendar-x me-1" style="color: #f59e0b;"></i> Tanggal Kembali
                            </label>
                            <input type="date"
                                   id="return_date"
                                   name="return_date"
                                   value="{{ old('return_date') }}"
                                   min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                   required
                                   class="form-control" style="background-color: #FFFFFF; border-color: #A3A3A3; color: #222222;" @error('return_date') style="border-color: #ef4444;" @enderror>
                            @error('return_date')
                                <div class="invalid-feedback" style="color: #ef4444;">{{ $message }}</div>
                            @enderror
                            <div class="form-text small" style="color: #6B7280;">
                                <i class="bi bi-info-circle me-1"></i> Maksimal durasi sewa: 30 hari
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
    const returnDateInput = document.getElementById('return_date');
    
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
    
    // Calculate and update total price
    function calculateTotal() {
        const rentalDate = rentalDateInput.value ? new Date(rentalDateInput.value) : null;
        const returnDate = returnDateInput.value ? new Date(returnDateInput.value) : null;
        
        let totalDays = 0;
        let totalPrice = 0;
        
        if (rentalDate && returnDate && returnDate > rentalDate) {
            // Calculate days difference
            const timeDiff = returnDate.getTime() - rentalDate.getTime();
            totalDays = Math.ceil(timeDiff / (1000 * 3600 * 24));
            
            // Calculate total price for all items
            Object.keys(itemPrices).forEach(key => {
                const item = itemPrices[key];
                totalPrice += item.price * item.quantity * totalDays;
            });
            
            // If no items with quantity control, use base price
            if (Object.keys(itemPrices).length === 0) {
                // Get price from badge
                const priceText = document.querySelector('.badge[style*="background-color: #e0e7ff"]');
                if (priceText) {
                    const priceMatch = priceText.textContent.match(/Rp\s*([\d.]+)/);
                    if (priceMatch) {
                        const basePrice = parseFloat(priceMatch[1].replace(/\./g, ''));
                        totalPrice = basePrice * totalDays;
                    }
                }
            }
            
            document.getElementById('total-days').textContent = `Durasi: ${totalDays} hari`;
        } else {
            document.getElementById('total-days').textContent = 'Pilih tanggal untuk melihat total';
        }
        
        document.getElementById('total-price').textContent = formatRupiah(totalPrice);
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
    
    // Initial calculation
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
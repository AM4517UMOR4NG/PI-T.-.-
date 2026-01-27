@extends('pelanggan.layout')

@section('pelanggan_content')
<style>
    /* Readable badge styles - Light Mode */
    .badge-pending {
        background-color: #fef3c7 !important;
        color: #92400e !important;
        border: 1px solid #f59e0b !important;
        font-weight: 600;
    }
    .badge-active {
        background-color: #d1fae5 !important;
        color: #065f46 !important;
        border: 1px solid #10b981 !important;
        font-weight: 600;
    }
    .badge-waiting {
        background-color: #cffafe !important;
        color: #0e7490 !important;
        border: 1px solid #06b6d4 !important;
        font-weight: 600;
    }
    .badge-done {
        background-color: #e0e7ff !important;
        color: #3730a3 !important;
        border: 1px solid #6366f1 !important;
        font-weight: 600;
    }
    .badge-cancelled {
        background-color: #fee2e2 !important;
        color: #991b1b !important;
        border: 1px solid #ef4444 !important;
        font-weight: 600;
    }
    .badge-neutral {
        background-color: #f1f5f9 !important;
        color: #475569 !important;
        border: 1px solid #94a3b8 !important;
        font-weight: 600;
    }
    .badge-paid {
        background-color: #d1fae5 !important;
        color: #065f46 !important;
        border: 1px solid #10b981 !important;
        font-weight: 600;
    }
    .badge-unpaid {
        background-color: #fee2e2 !important;
        color: #991b1b !important;
        border: 1px solid #ef4444 !important;
        font-weight: 600;
    }
    .badge-partial {
        background-color: #fef3c7 !important;
        color: #92400e !important;
        border: 1px solid #f59e0b !important;
        font-weight: 600;
    }
    .badge-delivery {
        background-color: #ede9fe !important;
        color: #5b21b6 !important;
        border: 1px solid #8b5cf6 !important;
        font-weight: 600;
    }
    
    /* Card styling */
    .rental-card {
        background: var(--adaptive-bg-card, #ffffff);
        border: 1px solid var(--adaptive-border, #e2e8f0);
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    
    /* Info row styling */
    .info-row {
        padding: 12px 0;
        border-bottom: 1px solid var(--adaptive-border, #f1f5f9);
    }
    .info-row:last-child {
        border-bottom: none;
    }
    .info-label {
        color: var(--adaptive-text-muted, #64748b);
        font-size: 0.9rem;
    }
    .info-value {
        color: var(--adaptive-text-primary, #1e293b);
        font-weight: 500;
    }
    
    /* Alert styling */
    .alert-success-custom {
        background-color: #d1fae5;
        color: #065f46;
        border: 1px solid #10b981;
    }
    .alert-danger-custom {
        background-color: #fee2e2;
        color: #991b1b;
        border: 1px solid #ef4444;
    }
    .alert-warning-custom {
        background-color: #fef3c7;
        color: #92400e;
        border: 1px solid #f59e0b;
    }
    .alert-info-custom {
        background-color: #cffafe;
        color: #0e7490;
        border: 1px solid #06b6d4;
    }
    
    /* Table styling */
    .table-custom th {
        background-color: var(--adaptive-bg-light, #f8fafc);
        color: var(--adaptive-text-secondary, #475569);
        font-weight: 600;
        border-bottom: 2px solid var(--adaptive-border, #e2e8f0);
    }
    .table-custom td {
        color: var(--adaptive-text-primary, #1e293b);
        border-bottom: 1px solid var(--adaptive-border, #f1f5f9);
    }

    /* Dark Mode Overrides */
    [data-theme="dark"] .badge-pending {
        background-color: rgba(245, 158, 11, 0.3) !important;
        color: #fbbf24 !important;
        border-color: rgba(245, 158, 11, 0.5) !important;
    }
    [data-theme="dark"] .badge-active {
        background-color: rgba(16, 185, 129, 0.3) !important;
        color: #34d399 !important;
        border-color: rgba(16, 185, 129, 0.5) !important;
    }
    [data-theme="dark"] .badge-waiting {
        background-color: rgba(6, 182, 212, 0.3) !important;
        color: #22d3ee !important;
        border-color: rgba(6, 182, 212, 0.5) !important;
    }
    [data-theme="dark"] .badge-done {
        background-color: rgba(99, 102, 241, 0.3) !important;
        color: #a5b4fc !important;
        border-color: rgba(99, 102, 241, 0.5) !important;
    }
    [data-theme="dark"] .badge-cancelled {
        background-color: rgba(239, 68, 68, 0.3) !important;
        color: #f87171 !important;
        border-color: rgba(239, 68, 68, 0.5) !important;
    }
    [data-theme="dark"] .badge-neutral {
        background-color: rgba(71, 85, 105, 0.4) !important;
        color: #e2e8f0 !important;
        border-color: rgba(148, 163, 184, 0.5) !important;
    }
    [data-theme="dark"] .badge-paid {
        background-color: rgba(16, 185, 129, 0.3) !important;
        color: #34d399 !important;
        border-color: rgba(16, 185, 129, 0.5) !important;
    }
    [data-theme="dark"] .badge-unpaid {
        background-color: rgba(239, 68, 68, 0.3) !important;
        color: #f87171 !important;
        border-color: rgba(239, 68, 68, 0.5) !important;
    }
    [data-theme="dark"] .badge-partial {
        background-color: rgba(245, 158, 11, 0.3) !important;
        color: #fbbf24 !important;
        border-color: rgba(245, 158, 11, 0.5) !important;
    }
    [data-theme="dark"] .badge-delivery {
        background-color: rgba(139, 92, 246, 0.3) !important;
        color: #c4b5fd !important;
        border-color: rgba(139, 92, 246, 0.5) !important;
    }
    [data-theme="dark"] .alert-success-custom {
        background-color: rgba(16, 185, 129, 0.2) !important;
        color: #34d399 !important;
        border-color: rgba(16, 185, 129, 0.4) !important;
    }
    [data-theme="dark"] .alert-danger-custom {
        background-color: rgba(239, 68, 68, 0.2) !important;
        color: #f87171 !important;
        border-color: rgba(239, 68, 68, 0.4) !important;
    }
    [data-theme="dark"] .alert-warning-custom {
        background-color: rgba(245, 158, 11, 0.2) !important;
        color: #fbbf24 !important;
        border-color: rgba(245, 158, 11, 0.4) !important;
    }
    [data-theme="dark"] .alert-info-custom {
        background-color: rgba(6, 182, 212, 0.2) !important;
        color: #22d3ee !important;
        border-color: rgba(6, 182, 212, 0.4) !important;
    }
    [data-theme="dark"] .alert-info-custom {
        background-color: rgba(6, 182, 212, 0.2) !important;
        color: #22d3ee !important;
        border-color: rgba(6, 182, 212, 0.4) !important;
    }

    /* Delivery Card Styles */
    .delivery-card {
        border: 2px solid #0ea5e9;
        background: linear-gradient(135deg, #e0f2fe 0%, #f0f9ff 100%);
        color: #1e293b;
    }
    .delivery-card-title {
        color: #0369a1;
    }
    .delivery-card-text {
        color: #1e293b;
    }
    .delivery-card-spinner {
        color: #0ea5e9;
    }
    
    /* Dark Mode for Delivery Card */
    [data-theme="dark"] .delivery-card {
        border-color: #0ea5e9;
        background: linear-gradient(135deg, #0c4a6e 0%, #075985 100%);
        color: #e2e8f0;
    }
    [data-theme="dark"] .delivery-card-title {
        color: #38bdf8;
    }
    [data-theme="dark"] .delivery-card-text {
        color: #e2e8f0;
    }
    [data-theme="dark"] .delivery-card-spinner {
        color: #7dd3fc;
    }
    [data-theme="dark"] .delivery-card strong {
        color: #f8fafc;
    }
    [data-theme="dark"] .delivery-card small.text-muted {
        color: #cbd5e1 !important;
    }
    
    /* Delivery Address Box */
    .delivery-address-box {
        background-color: rgba(255, 255, 255, 0.9);
        border: 1px solid rgba(0, 0, 0, 0.1);
    }
    .delivery-address-label {
        color: #64748b;
    }
    .delivery-address-text {
        color: #1e293b;
    }
    
    [data-theme="dark"] .delivery-address-box {
        background-color: rgba(15, 23, 42, 0.6);
        border: 1px solid rgba(148, 163, 184, 0.2);
    }
    [data-theme="dark"] .delivery-address-label {
        color: #94a3b8;
    }
    [data-theme="dark"] .delivery-address-text {
        color: #f1f5f9;
    }
</style>

<div class="container-fluid">
    <!-- Header -->
    <div class="rental-card mb-4">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1 fw-bold" style="color: #1e293b;"><i class="bi bi-receipt me-2" style="color: #3b82f6;"></i>Detail Penyewaan #{{ $rental->id }}</h4>
                    <p class="mb-0 small" style="color: #64748b;">Informasi lengkap transaksi penyewaan Anda</p>
                </div>
                <div>
                    <a href="{{ route('pelanggan.rentals.index') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('status'))
        <div class="alert alert-success-custom mb-4 d-flex align-items-center rounded-3">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
            <div>{{ session('status') }}</div>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger-custom mb-4 d-flex align-items-center rounded-3">
            <i class="bi bi-exclamation-circle-fill me-2 fs-5"></i>
            <div>{{ session('error') }}</div>
        </div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning-custom mb-4 d-flex align-items-center rounded-3">
            <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
            <div>{{ session('warning') }}</div>
        </div>
    @endif

    <div class="row g-4">
        <!-- Left Column: Rental Information -->
        <div class="col-lg-6">
            <div class="rental-card h-100">
                <div class="card-body p-4">
                    <h5 class="mb-4 fw-bold" style="color: #1e293b;"><i class="bi bi-info-circle me-2" style="color: #3b82f6;"></i>Informasi Penyewaan</h5>

                    <div class="info-row">
                        <div class="row align-items-center">
                            <div class="col-5 info-label">Kode Rental</div>
                            <div class="col-7 info-value fw-bold">{{ $rental->kode ?? '#'.$rental->id }}</div>
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="row align-items-center">
                            <div class="col-5 info-label"><i class="bi bi-calendar-event me-1"></i> Tanggal Sewa</div>
                            <div class="col-7 info-value">{{ \Carbon\Carbon::parse($rental->start_at)->format('d M Y, H:i') }}</div>
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="row align-items-center">
                            <div class="col-5 info-label"><i class="bi bi-calendar-x me-1"></i> Tanggal Kembali</div>
                            <div class="col-7 info-value">{{ \Carbon\Carbon::parse($rental->due_at)->format('d M Y, H:i') }}</div>
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="row align-items-center">
                            <div class="col-5 info-label"><i class="bi bi-hourglass me-1"></i> Durasi</div>
                            <div class="col-7 info-value">
                                @php
                                    $startDate = \Carbon\Carbon::parse($rental->start_at)->startOfDay();
                                    $dueDate = \Carbon\Carbon::parse($rental->due_at)->startOfDay();
                                    $durasi = $startDate->diffInDays($dueDate);
                                    if ($durasi < 1) $durasi = 1;
                                @endphp
                                {{ $durasi }} hari
                            </div>
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="row align-items-center">
                            <div class="col-5 info-label"><i class="bi bi-tag me-1"></i> Status</div>
                            <div class="col-7">
                                @php
                                  $statusText = match($rental->status) {
                                    'pending' => 'Menunggu Pembayaran',
                                    'menunggu_pengantaran' => 'Menunggu Pengantaran',
                                    'sedang_disewa' => 'Sedang Disewa',
                                    'menunggu_konfirmasi' => 'Menunggu Konfirmasi',
                                    'selesai' => 'Selesai',
                                    'cancelled' => 'Dibatalkan',
                                    default => ucfirst($rental->status)
                                  };
                                @endphp
                                @if($rental->status === 'pending')
                                    <span class="badge badge-pending"><i class="bi bi-clock me-1"></i>{{ $statusText }}</span>
                                @elseif($rental->status === 'menunggu_pengantaran')
                                    <span class="badge badge-delivery"><i class="bi bi-truck me-1"></i>{{ $statusText }}</span>
                                @elseif($rental->status === 'sedang_disewa')
                                    <span class="badge badge-active"><i class="bi bi-play-circle me-1"></i>{{ $statusText }}</span>
                                @elseif($rental->status === 'menunggu_konfirmasi')
                                    <span class="badge badge-waiting"><i class="bi bi-hourglass-split me-1"></i>{{ $statusText }}</span>
                                @elseif($rental->status === 'selesai')
                                    <span class="badge badge-done"><i class="bi bi-check-circle me-1"></i>{{ $statusText }}</span>
                                @elseif($rental->status === 'cancelled')
                                    <span class="badge badge-cancelled"><i class="bi bi-x-circle me-1"></i>{{ $statusText }}</span>
                                @else
                                    <span class="badge badge-neutral">{{ $statusText }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="row align-items-center">
                            <div class="col-5 info-label"><i class="bi bi-cash me-1"></i> Total</div>
                            <div class="col-7 fw-bold fs-5" style="color: #059669;">Rp {{ number_format($rental->total, 0, ',', '.') }}</div>
                        </div>
                    </div>

                    @if($rental->fine > 0)
                    <div class="info-row">
                        <div class="row align-items-center">
                            <div class="col-5 info-label"><i class="bi bi-exclamation-triangle me-1 text-danger"></i> Denda</div>
                            <div class="col-7 fw-bold" style="color: #991b1b;">Rp {{ number_format($rental->fine, 0, ',', '.') }}</div>
                        </div>
                    </div>
                    @endif

                    <div class="info-row">
                        <div class="row align-items-center">
                            <div class="col-5 info-label"><i class="bi bi-credit-card me-1"></i> Status Bayar</div>
                            <div class="col-7">
                                @if($rental->paid >= $rental->total)
                                    <span class="badge badge-paid"><i class="bi bi-check-circle-fill me-1"></i> LUNAS</span>
                                @elseif($rental->paid > 0)
                                    <span class="badge badge-partial"><i class="bi bi-exclamation-circle-fill me-1"></i> KURANG BAYAR</span>
                                @else
                                    <span class="badge badge-unpaid"><i class="bi bi-x-circle-fill me-1"></i> BELUM LUNAS</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($rental->notes)
                    <div class="info-row">
                        <div class="info-label mb-2"><i class="bi bi-sticky me-1"></i> Catatan</div>
                        <div class="p-3 rounded" style="background-color: #f8fafc; border: 1px solid #e2e8f0; color: #1e293b;">{{ $rental->notes }}</div>
                    </div>
                    @endif

                    <!-- Actions -->
                    @if($rental->status === 'menunggu_pengantaran')
                        <div class="mt-4">
                            @if($rental->delivery_method == 'pickup')
                                <!-- Metode: Ambil di Toko (pickup) -->
                                <div class="rental-card p-4 delivery-card">
                                    <h6 class="fw-bold mb-3 delivery-card-title">
                                        <i class="bi bi-shop me-2"></i>Pengambilan di Toko
                                    </h6>
                                    
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="me-3" style="color: #f59e0b;">
                                            <i class="bi bi-hourglass-split fs-3"></i>
                                        </div>
                                        <div>
                                            <strong class="delivery-card-text">Menunggu Pengambilan</strong>
                                            <br>
                                            <small class="text-muted">Silakan datang ke toko untuk mengambil barang Anda</small>
                                        </div>
                                    </div>
                                    
                                    <div class="p-3 rounded delivery-address-box">
                                        <small class="delivery-address-label"><i class="bi bi-geo-alt me-1"></i>Alamat toko:</small>
                                        <p class="mb-0 fw-bold delivery-address-text">PlayStation Rental Store</p>
                                        <small class="text-muted">Jam operasional: 09:00 - 21:00 WIB</small>
                                    </div>
                                    
                                    <div class="alert alert-warning mt-3 mb-0">
                                        <i class="bi bi-info-circle me-1"></i>
                                        <small>Tunjukkan kode pesanan <strong>{{ $rental->kode }}</strong> kepada kasir saat mengambil barang. Kasir akan mengkonfirmasi pengambilan dan waktu sewa akan mulai terhitung.</small>
                                    </div>
                                </div>
                            @else
                                <!-- Metode: Diantar ke alamat (delivery) atau rental lama (null) -->
                                <div class="rental-card p-4 delivery-card">
                                    <h6 class="fw-bold mb-3 delivery-card-title">
                                        <i class="bi bi-truck me-2"></i>Status Pengantaran
                                    </h6>
                                    
                                    @if($rental->delivered_at)
                                        <!-- Barang sudah diantar, user bisa konfirmasi -->
                                        <div class="alert alert-success mb-3">
                                            <i class="bi bi-check-circle me-2"></i>
                                            <strong>Barang sudah diantarkan!</strong>
                                            <br>
                                            <small>Diantar pada: {{ $rental->delivered_at->format('d M Y, H:i') }}</small>
                                            @if($rental->delivery_notes)
                                                <br><small>Catatan: {{ $rental->delivery_notes }}</small>
                                            @endif
                                        </div>
                                        
                                        <p class="mb-3 delivery-card-text">
                                            <i class="bi bi-info-circle me-1"></i>
                                            Silakan konfirmasi jika Anda sudah menerima barang. 
                                            <strong>Waktu sewa akan mulai terhitung setelah Anda mengkonfirmasi penerimaan.</strong>
                                        </p>
                                        
                                        <form action="{{ route('pelanggan.rentals.confirm-delivery', $rental) }}" method="POST" 
                                            onsubmit="return confirm('Apakah Anda sudah menerima barang dengan baik? Waktu sewa akan mulai terhitung setelah konfirmasi ini.')">
                                            @csrf
                                            <button type="submit" class="btn btn-success w-100 fw-bold py-2">
                                                <i class="bi bi-check-circle me-2"></i>Konfirmasi Sudah Menerima Barang
                                            </button>
                                        </form>
                                    @else
                                        <!-- Barang belum diantar -->
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="spinner-border spinner-border-sm delivery-card-spinner me-3" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                            <div>
                                                <strong class="delivery-card-text">Pesanan sedang diproses</strong>
                                                <br>
                                                <small class="text-muted">Barang akan segera diantarkan ke alamat Anda</small>
                                            </div>
                                        </div>
                                        
                                        <div class="p-3 rounded delivery-address-box">
                                            <small class="delivery-address-label"><i class="bi bi-geo-alt me-1"></i>Alamat pengiriman:</small>
                                            <p class="mb-0 fw-bold delivery-address-text">{{ $rental->delivery_address ?? auth()->user()->address ?? 'Alamat belum diisi' }}</p>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endif

                    @if($rental->status === 'sedang_disewa')
                        <div class="mt-4">
                            <a href="{{ route('pelanggan.rentals.return', $rental) }}" class="btn btn-warning w-100 fw-bold" style="color: #222222;">
                                <i class="bi bi-box-arrow-in-down me-2"></i> Kembalikan Barang
                            </a>
                        </div>
                    @endif

                    @if($rental->status === 'menunggu_konfirmasi')
                        <div class="alert alert-info-custom mt-4 mb-0 rounded-3">
                            <i class="bi bi-info-circle-fill me-2"></i> Pengembalian Anda sedang menunggu konfirmasi dari kasir.
                        </div>
                    @endif

                    @if($rental->status === 'pending')
                        <div class="mt-4">
                            <!-- Tombol Lanjutkan Pembayaran -->
                            <a href="{{ route('pelanggan.rentals.continue-payment', $rental) }}" class="btn btn-success w-100 fw-bold mb-3 py-2">
                                <i class="bi bi-credit-card me-2"></i> Lanjutkan Pembayaran
                            </a>
                            
                            @if($rental->payments->count() > 0)
                                @php
                                    $pendingPayment = $rental->payments->where('transaction_status', 'pending')->first();
                                @endphp
                                <button id="check-payment-btn" class="btn btn-outline-primary w-100 fw-bold mb-3" data-order-id="{{ $pendingPayment->order_id ?? '' }}">
                                    <i class="bi bi-arrow-clockwise me-2"></i> Cek Status Pembayaran
                                </button>
                                <div class="small mb-3 text-center" style="color: #64748b;">
                                    <i class="bi bi-info-circle me-1"></i> Klik jika Anda sudah membayar tapi status belum berubah.
                                </div>
                            @endif
                            
                            <!-- Tombol Batalkan Pesanan -->
                            <form method="POST" action="{{ route('pelanggan.rentals.cancel', $rental) }}" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini? Tindakan ini tidak dapat dibatalkan.')">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger w-100 fw-bold">
                                    <i class="bi bi-x-circle me-2"></i> Batalkan Pesanan
                                </button>
                            </form>
                        </div>
                        
                        <!-- Info Pembayaran Tertunda dengan Countdown -->
                        @php
                            $expireTime = $rental->created_at->addHour();
                            $isExpired = now()->gt($expireTime);
                            $remainingMinutes = now()->diffInMinutes($expireTime, false);
                        @endphp
                        <div class="alert {{ $isExpired ? 'alert-danger' : 'alert-warning-custom' }} mt-4 mb-0 rounded-3">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-{{ $isExpired ? 'x-circle' : 'exclamation-triangle' }}-fill me-2 mt-1"></i>
                                <div>
                                    @if($isExpired)
                                        <strong>Pesanan Akan Segera Dibatalkan!</strong>
                                        <p class="mb-0 small mt-1">Waktu pembayaran telah habis. Pesanan ini akan otomatis dibatalkan.</p>
                                    @else
                                        <strong>Pembayaran Belum Selesai</strong>
                                        <p class="mb-0 small mt-1">
                                            Silakan selesaikan pembayaran sebelum <strong>{{ $expireTime->format('H:i') }}</strong> 
                                            ({{ $remainingMinutes }} menit lagi). Pesanan akan otomatis dibatalkan jika tidak dibayar dalam 1 jam.
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column: Rental Items -->
        <div class="col-lg-6">
            <div class="rental-card h-100">
                <div class="card-body p-4">
                    <h5 class="mb-4 fw-bold" style="color: #1e293b;"><i class="bi bi-box-seam me-2" style="color: #10b981;"></i>Item yang Disewa</h5>

                    @forelse($rental->items as $item)
                        @php
                            $itemName = 'Item Tidak Ditemukan';
                            $itemImage = null;
                            $itemImageField = '';

                            if($item->rentable) {
                                $itemName = $item->rentable->nama ?? $item->rentable->judul ?? $item->rentable->name ?? 'Unknown';

                                // Get image field based on type
                                if (str_contains($item->rentable_type, 'UnitPS')) {
                                    $itemImageField = 'foto';
                                } else {
                                    $itemImageField = 'gambar';
                                }

                                if(isset($item->rentable->$itemImageField)) {
                                    $itemImage = $item->rentable->$itemImageField;
                                }
                            }

                            $itemType = class_basename($item->rentable_type);
                        @endphp

                        <div class="d-flex align-items-start mb-3 pb-3" style="{{ !$loop->last ? 'border-bottom: 1px solid #f1f5f9;' : '' }}">
                            <!-- Image -->
                            <div class="flex-shrink-0 me-3">
                                @if($itemImage)
                                    <img src="{{ str_starts_with($itemImage, 'http') ? $itemImage : asset('storage/' . $itemImage) }}"
                                         alt="{{ $itemName }}"
                                         class="rounded shadow-sm"
                                         style="width: 80px; height: 80px; object-fit: cover;">
                                @else
                                    <div class="rounded d-flex align-items-center justify-content-center" style="background-color: #f8fafc; border: 1px solid #e2e8f0; color: #64748b; width: 80px; height: 80px;">
                                        <i class="bi bi-box-seam fs-3"></i>
                                    </div>
                                @endif
                            </div>

                            <!-- Details -->
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-2" style="color: #1e293b;">{{ $itemName }}</h6>
                                <div class="small mb-1">
                                    <span class="badge badge-neutral">{{ $itemType }}</span>
                                    @if($item->condition === 'rusak')
                                        <span class="badge" style="background-color: #fee2e2; color: #991b1b; border: 1px solid #ef4444;"><i class="bi bi-exclamation-circle me-1"></i>RUSAK</span>
                                    @elseif($item->condition === 'baik')
                                        <span class="badge" style="background-color: #d1fae5; color: #065f46; border: 1px solid #10b981;"><i class="bi bi-check-circle me-1"></i>BAIK</span>
                                    @endif
                                </div>
                                <div class="small mb-1" style="color: #64748b;"><i class="bi bi-123 me-1"></i> Jumlah: <span style="color: #1e293b;">{{ $item->quantity }}</span></div>
                                <div class="small mb-1" style="color: #64748b;"><i class="bi bi-tag me-1"></i> Harga: <span style="color: #1e293b;">Rp {{ number_format($item->price, 0, ',', '.') }}</span></div>
                                <div class="fw-bold mt-2" style="color: #059669;"><i class="bi bi-cash me-1"></i> Subtotal: Rp {{ number_format($item->total, 0, ',', '.') }}</div>
                                @if($item->fine > 0)
                                    <div class="small mt-1" style="color: #991b1b;"><i class="bi bi-exclamation-triangle me-1"></i> Denda: <span class="fw-bold">Rp {{ number_format($item->fine, 0, ',', '.') }}</span></div>
                                    @if($item->fine_description)
                                        <div class="small" style="color: #64748b;">{{ $item->fine_description }}</div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="bi bi-box" style="color: #94a3b8; font-size: 3rem;"></i>
                            <p class="mt-3" style="color: #64748b;">Tidak ada item</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Damage Reports Section -->
    @php
        $damageReports = $rental->items->filter(function($item) {
            return $item->condition === 'rusak' && $item->damageReport;
        })->map(function($item) {
            return $item->damageReport;
        });
    @endphp
    
    @if($damageReports->count() > 0)
    <div class="rental-card mt-4" style="border-left: 4px solid #ef4444; border-radius: 12px;">
        <div class="card-body p-4">
            <h5 class="mb-4 fw-bold" style="color: #dc2626;"><i class="bi bi-exclamation-triangle me-2"></i>Laporan Kerusakan</h5>
            
            @foreach($damageReports as $report)
                @php
                    $rentable = $report->rentalItem->rentable ?? null;
                    $itemName = $rentable->name ?? $rentable->nama ?? $rentable->judul ?? 'Item Tidak Diketahui';
                @endphp
                <div class="p-4 rounded-3 mb-3" style="background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%); border: 1px solid #fecaca;">
                    <!-- Header -->
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-2 mb-3">
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <i class="bi bi-box-seam" style="color: #dc2626;"></i>
                                <h6 class="fw-bold mb-0" style="color: #991b1b;">{{ $itemName }}</h6>
                            </div>
                            <small style="color: #6b7280;"><i class="bi bi-calendar3 me-1"></i>Dilaporkan: {{ $report->created_at->format('d M Y H:i') }}</small>
                        </div>
                        <div>
                            @if($report->status === 'pending')
                                <span class="badge px-3 py-2" style="background-color: #fef3c7; color: #92400e; border: 1px solid #f59e0b;">
                                    <i class="bi bi-clock me-1"></i>Menunggu Review Admin
                                </span>
                            @elseif($report->status === 'reviewed')
                                <span class="badge px-3 py-2" style="background-color: #cffafe; color: #0e7490; border: 1px solid #06b6d4;">
                                    <i class="bi bi-check me-1"></i>Admin Sudah Review
                                </span>
                            @elseif($report->status === 'user_confirmed')
                                <span class="badge px-3 py-2" style="background-color: #e0e7ff; color: #3730a3; border: 1px solid #6366f1;">
                                    <i class="bi bi-credit-card me-1"></i>Menunggu Pembayaran
                                </span>
                            @elseif($report->status === 'fine_paid')
                                <span class="badge px-3 py-2" style="background-color: #d1fae5; color: #065f46; border: 1px solid #10b981;">
                                    <i class="bi bi-check-circle me-1"></i>Denda Dibayar
                                </span>
                            @elseif($report->status === 'kasir_confirmed')
                                <span class="badge px-3 py-2" style="background-color: #f1f5f9; color: #475569; border: 1px solid #94a3b8;">
                                    <i class="bi bi-hourglass me-1"></i>Menunggu Admin
                                </span>
                            @elseif($report->status === 'resolved')
                                <span class="badge px-3 py-2" style="background-color: #1e293b; color: #f8fafc;">
                                    <i class="bi bi-check-all me-1"></i>Selesai
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Description -->
                    <div class="mb-3 p-3 rounded-2" style="background-color: rgba(255,255,255,0.7);">
                        <p class="mb-1 small fw-bold" style="color: #6b7280;"><i class="bi bi-chat-text me-1"></i>Keterangan Kerusakan:</p>
                        <p class="mb-0" style="color: #1f2937;">{{ $report->description ?? '-' }}</p>
                    </div>
                    
                    <!-- Admin Feedback -->
                    @if($report->admin_feedback)
                    <div class="mb-3 p-2 rounded" style="background-color: #fff;">
                        <p class="mb-1 text-muted small"><i class="bi bi-chat-left-quote me-1"></i>Feedback Admin:</p>
                        <p class="mb-0 fw-bold text-danger">{{ $report->admin_feedback }}</p>
                        @if($report->fine_amount)
                            <p class="mb-0 mt-2">
                                <span class="text-muted">Denda yang ditetapkan:</span>
                                <span class="fw-bold text-danger fs-5">Rp {{ number_format($report->fine_amount, 0, ',', '.') }}</span>
                            </p>
                        @endif
                    </div>
                    @endif
                    
                    <!-- Action Buttons -->
                    <div class="mt-3">
                        @if($report->status === 'reviewed' && !$report->user_confirmed)
                            <!-- User needs to confirm -->
                            <div class="alert alert-warning mb-3">
                                <i class="bi bi-info-circle me-2"></i>
                                Admin telah mereview kerusakan dan menetapkan denda. Silakan konfirmasi untuk melanjutkan ke pembayaran.
                            </div>
                            <form action="{{ route('pelanggan.damage-reports.confirm', $report) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-warning" onclick="return confirm('Anda setuju dengan denda yang ditetapkan?')">
                                    <i class="bi bi-check-lg me-1"></i>Konfirmasi & Setuju Denda
                                </button>
                            </form>
                        @elseif($report->status === 'user_confirmed' && !$report->fine_paid)
                            <!-- User needs to pay -->
                            <div class="alert alert-info mb-3">
                                <i class="bi bi-info-circle me-2"></i>
                                Silakan bayar denda untuk menyelesaikan proses.
                            </div>
                            <a href="{{ route('pelanggan.damage-reports.pay-fine', $report) }}" class="btn btn-danger">
                                <i class="bi bi-credit-card me-1"></i>Bayar Denda Rp {{ number_format($report->fine_amount, 0, ',', '.') }}
                            </a>
                        @elseif($report->fine_paid && !$report->kasir_confirmed_at)
                            <!-- Waiting for kasir confirmation -->
                            <div class="alert alert-success mb-0">
                                <i class="bi bi-check-circle me-2"></i>
                                Pembayaran denda berhasil! Menunggu konfirmasi dari kasir.
                            </div>
                        @elseif($report->kasir_confirmed_at && $report->status !== 'resolved')
                            <!-- Waiting for admin to close -->
                            <div class="alert alert-secondary mb-0">
                                <i class="bi bi-hourglass-split me-2"></i>
                                Kasir telah mengkonfirmasi pembayaran. Menunggu admin menutup case.
                            </div>
                        @elseif($report->status === 'resolved')
                            <!-- Completed -->
                            <div class="alert alert-dark mb-0">
                                <i class="bi bi-check-all me-2"></i>
                                Case kerusakan telah ditutup. Terima kasih.
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Payment History -->
    @if($rental->payments->count() > 0)
    <div class="rental-card mt-4">
        <div class="card-body p-4">
            <h5 class="mb-4 fw-bold" style="color: #1e293b;"><i class="bi bi-clock-history me-2" style="color: #f59e0b;"></i>Riwayat Pembayaran</h5>

            <div class="table-responsive">
                <table class="table table-custom align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Jumlah</th>
                            <th>Metode</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rental->payments as $payment)
                        <tr>
                            <td>{{ $payment->created_at->format('d M Y, H:i') }}</td>
                            <td class="fw-bold" style="color: #059669;">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                            <td><span class="badge badge-neutral">{{ ucfirst($payment->method ?? 'N/A') }}</span></td>
                            <td>
                                @php
                                    $status = $payment->transaction_status ?? 'pending';
                                @endphp
                                @if(in_array($status, ['settlement', 'capture']))
                                    <span class="badge badge-paid"><i class="bi bi-check-circle me-1"></i> Lunas</span>
                                @elseif($status == 'pending')
                                    <span class="badge badge-pending"><i class="bi bi-hourglass-split me-1"></i> Menunggu</span>
                                @elseif(in_array($status, ['cancel', 'expire', 'deny']))
                                    <span class="badge badge-cancelled"><i class="bi bi-x-circle me-1"></i> {{ ucfirst($status) }}</span>
                                @else
                                    <span class="badge badge-neutral">{{ ucfirst($status) }}</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkBtn = document.getElementById('check-payment-btn');
    if (checkBtn) {
        checkBtn.addEventListener('click', function() {
            const orderId = this.getAttribute('data-order-id');
            const originalText = this.innerHTML;
            
            this.disabled = true;
            this.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Memeriksa...';
            
            fetch(`/midtrans/status/${orderId}`)
                .then(response => response.json())
                .then(data => {
                    console.log('Status check:', data);
                    if (data.status && (data.status.transaction_status === 'settlement' || data.status.transaction_status === 'capture')) {
                        alert('Pembayaran berhasil dikonfirmasi! Halaman akan dimuat ulang.');
                        window.location.reload();
                    } else {
                        alert('Status pembayaran saat ini: ' + (data.status ? data.status.transaction_status : 'Belum tersedia'));
                        this.disabled = false;
                        this.innerHTML = originalText;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat memeriksa status.');
                    this.disabled = false;
                    this.innerHTML = originalText;
                });
        });
    }
});
</script>

@extends('kasir.layout')

@section('kasir_content')
<style>
    .detail-card {
        background: var(--card-bg, #ffffff);
        border-radius: 16px;
        overflow: hidden;
    }
    .detail-card-header {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid var(--card-border, #e5e7eb);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .detail-card-header h6 {
        margin: 0;
        font-weight: 600;
        color: var(--text-main, #1f2937);
    }
    .detail-card-header i {
        color: #0652DD;
    }
    .detail-card-body {
        padding: 1.5rem;
    }
    .item-row {
        display: grid;
        grid-template-columns: 2fr 1fr 0.5fr 1fr 1fr;
        gap: 1rem;
        padding: 1rem 1.5rem;
        align-items: center;
        border-bottom: 1px solid var(--card-border, #f1f5f9);
    }
    .item-row:last-child {
        border-bottom: none;
    }
    .item-row.header {
        background: var(--bg-light, #f8fafc);
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--text-muted, #6b7280);
    }
    .item-row.damaged {
        background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
    }
    .item-info {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }
    .item-name {
        font-weight: 600;
        color: var(--text-main, #1f2937);
    }
    .item-meta {
        font-size: 0.75rem;
        color: var(--text-muted, #6b7280);
    }
    .condition-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
    }
    .condition-badge.rusak {
        background: #fee2e2;
        color: #dc2626;
        border: 1px solid #fecaca;
    }
    .condition-badge.baik {
        background: #d1fae5;
        color: #059669;
        border: 1px solid #a7f3d0;
    }
    .type-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.35rem 0.75rem;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 500;
    }
    .type-badge.unitps { background: #dbeafe; color: #1d4ed8; }
    .type-badge.game { background: #cffafe; color: #0891b2; }
    .type-badge.accessory { background: #f3f4f6; color: #4b5563; }
    .price-text {
        font-weight: 600;
        color: var(--text-main, #1f2937);
    }
    .fine-text {
        font-size: 0.75rem;
        color: #dc2626;
        margin-top: 0.25rem;
    }
    .payment-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }
    .payment-box {
        padding: 1.25rem;
        border-radius: 12px;
        background: var(--bg-light, #f8fafc);
        border: 1px solid var(--card-border, #e5e7eb);
    }
    .payment-box.danger {
        background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
        border-color: #fecaca;
    }
    .payment-label {
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--text-muted, #6b7280);
        margin-bottom: 0.5rem;
    }
    .payment-label.danger {
        color: #dc2626;
    }
    .payment-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-main, #1f2937);
    }
    .payment-value.success { color: #059669; }
    .payment-value.danger { color: #dc2626; }
    .status-alert {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem 1.25rem;
        border-radius: 12px;
        margin-top: 1.5rem;
    }
    .status-alert.success {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        border: 1px solid #6ee7b7;
    }
    .status-alert.warning {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        border: 1px solid #fcd34d;
    }
    .status-alert.danger {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        border: 1px solid #fca5a5;
    }
    .status-icon {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }
    .status-icon.success { background: #059669; color: white; }
    .status-icon.warning { background: #d97706; color: white; }
    .status-icon.danger { background: #dc2626; color: white; }
    .status-text h6 {
        margin: 0;
        font-weight: 700;
        font-size: 0.9rem;
    }
    .status-text p {
        margin: 0;
        font-size: 0.8rem;
        opacity: 0.8;
    }
    .damage-detail {
        margin-top: 0.5rem;
        padding: 0.75rem;
        background: rgba(255,255,255,0.5);
        border-radius: 8px;
        font-size: 0.8rem;
    }
    .damage-detail .label {
        font-weight: 600;
        color: #991b1b;
        margin-bottom: 0.25rem;
    }
    .damage-detail .value {
        color: #7f1d1d;
    }
    
    /* Dark mode */
    [data-theme="dark"] .item-row.header {
        background: rgba(255,255,255,0.05);
    }
    [data-theme="dark"] .item-row.damaged {
        background: linear-gradient(135deg, rgba(220, 38, 38, 0.15) 0%, rgba(220, 38, 38, 0.1) 100%);
    }
    [data-theme="dark"] .payment-box {
        background: rgba(255,255,255,0.05);
        border-color: rgba(255,255,255,0.1);
    }
    [data-theme="dark"] .payment-box.danger {
        background: linear-gradient(135deg, rgba(220, 38, 38, 0.15) 0%, rgba(220, 38, 38, 0.1) 100%);
        border-color: rgba(220, 38, 38, 0.3);
    }
    [data-theme="dark"] .condition-badge.rusak {
        background: rgba(220, 38, 38, 0.2);
        border-color: rgba(220, 38, 38, 0.4);
    }
    [data-theme="dark"] .condition-badge.baik {
        background: rgba(5, 150, 105, 0.2);
        border-color: rgba(5, 150, 105, 0.4);
    }
    
    @media (max-width: 768px) {
        .item-row {
            grid-template-columns: 1fr;
            gap: 0.5rem;
        }
        .item-row.header {
            display: none;
        }
        .payment-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <i class="bi bi-receipt fs-4" style="color: #0652DD;"></i>
                <h4 class="mb-0 fw-bold" style="color: var(--text-main, #1f2937);">Detail Transaksi</h4>
            </div>
            <p class="mb-0 small" style="color: var(--text-muted, #6b7280);">Kode: <span class="fw-bold">{{ $rental->kode ?? '#'.$rental->id }}</span></p>
        </div>
        <a href="{{ route('kasir.rentals.index') }}" class="btn btn-sm rounded-pill px-4" style="color: #0652DD; border: 2px solid #0652DD; background-color: transparent; font-weight: 600;">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
    </div>

    @if(session('status'))
        <div class="alert d-flex align-items-center mb-4 rounded-3" style="background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%); border: 1px solid #6ee7b7; color: #065f46;">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
            <div>{{ session('status') }}</div>
        </div>
    @endif

    @if(session('error'))
        <div class="alert d-flex align-items-center mb-4 rounded-3" style="background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); border: 1px solid #fca5a5; color: #991b1b;">
            <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
            <div>{{ session('error') }}</div>
        </div>
    @endif

    <div class="row g-4">
        <!-- Left Column: Items & Payment -->
        <div class="col-lg-8">
            <!-- Items Card -->
            <div class="detail-card shadow-sm mb-4">
                <div class="detail-card-header">
                    <i class="bi bi-box-seam"></i>
                    <h6>Item Sewa</h6>
                </div>
                
                <!-- Table Header -->
                <div class="item-row header">
                    <div>Item</div>
                    <div class="text-center">Tipe</div>
                    <div class="text-center">Qty</div>
                    <div class="text-end">Harga</div>
                    <div class="text-end">Total</div>
                </div>
                
                <!-- Items -->
                @foreach($rental->items as $item)
                    @php
                        $itemName = $item->rentable->name ?? $item->rentable->nama ?? $item->rentable->judul ?? 'Item Terhapus';
                    @endphp
                    <div class="item-row {{ $item->condition === 'rusak' ? 'damaged' : '' }}">
                        <div class="item-info">
                            <span class="item-name">{{ $itemName }}</span>
                            @if($item->condition === 'rusak')
                                <span class="condition-badge rusak">
                                    <i class="bi bi-exclamation-circle"></i> RUSAK
                                </span>
                                @if($item->fine_description)
                                    <div class="damage-detail">
                                        <div class="label">Keterangan:</div>
                                        <div class="value">{{ $item->fine_description }}</div>
                                    </div>
                                @endif
                            @elseif($item->condition === 'baik')
                                <span class="condition-badge baik">
                                    <i class="bi bi-check-circle"></i> BAIK
                                </span>
                            @endif
                        </div>
                        <div class="text-center">
                            @if($item->rentable_type == 'App\Models\UnitPS')
                                <span class="type-badge unitps">Unit PS</span>
                            @elseif($item->rentable_type == 'App\Models\Game')
                                <span class="type-badge game">Game</span>
                            @elseif($item->rentable_type == 'App\Models\Accessory')
                                <span class="type-badge accessory">Aksesoris</span>
                            @else
                                <span class="type-badge accessory">Lainnya</span>
                            @endif
                        </div>
                        <div class="text-center fw-bold">{{ $item->quantity }}</div>
                        <div class="text-end price-text">Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                        <div class="text-end">
                            <div class="price-text">Rp {{ number_format($item->total, 0, ',', '.') }}</div>
                            @if($item->fine > 0)
                                <div class="fine-text">+ Denda: Rp {{ number_format($item->fine, 0, ',', '.') }}</div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Payment Card -->
            <div class="detail-card shadow-sm">
                <div class="detail-card-header">
                    <i class="bi bi-wallet2"></i>
                    <h6>Rincian Pembayaran</h6>
                </div>
                <div class="detail-card-body">
                    <div class="payment-grid">
                        <div class="payment-box">
                            <div class="payment-label">Total Tagihan</div>
                            <div class="payment-value">Rp {{ number_format($rental->total, 0, ',', '.') }}</div>
                        </div>
                        <div class="payment-box">
                            <div class="payment-label">Sudah Dibayar</div>
                            <div class="payment-value success">Rp {{ number_format($rental->paid, 0, ',', '.') }}</div>
                        </div>
                        @if($rental->fine > 0)
                        <div class="payment-box danger">
                            <div class="payment-label danger">Denda Kerusakan</div>
                            <div class="payment-value danger">Rp {{ number_format($rental->fine, 0, ',', '.') }}</div>
                        </div>
                        @endif
                    </div>

                    <!-- Status -->
                    @if($rental->paid >= $rental->total)
                        <div class="status-alert success">
                            <div class="status-icon success">
                                <i class="bi bi-check-lg"></i>
                            </div>
                            <div class="status-text" style="color: #065f46;">
                                <h6>LUNAS</h6>
                                <p>Seluruh tagihan telah dibayarkan</p>
                            </div>
                        </div>
                    @elseif($rental->paid > 0)
                        <div class="status-alert warning">
                            <div class="status-icon warning">
                                <i class="bi bi-exclamation-lg"></i>
                            </div>
                            <div class="status-text" style="color: #92400e;">
                                <h6>KURANG BAYAR</h6>
                                <p>Sisa: Rp {{ number_format($rental->total - $rental->paid, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    @else
                        <div class="status-alert danger">
                            <div class="status-icon danger">
                                <i class="bi bi-x-lg"></i>
                            </div>
                            <div class="status-text" style="color: #991b1b;">
                                <h6>BELUM LUNAS</h6>
                                <p>Belum ada pembayaran</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column: Status & Info -->
        <div class="col-lg-4">
            <!-- Status Action Card -->
            <div class="detail-card shadow-sm mb-4">
                <div class="detail-card-header">
                    <i class="bi bi-gear"></i>
                    <h6>Status & Aksi</h6>
                </div>
                <div class="detail-card-body text-center">
                    <div class="mb-4">
                        @switch($rental->status)
                            @case('sedang_disewa')
                                <span class="badge px-4 py-2 fs-6 rounded-pill" style="background: linear-gradient(135deg, #0652DD 0%, #1E40FF 100%); color: white;">
                                    <i class="bi bi-play-circle me-1"></i>Sedang Disewa
                                </span>
                                @break
                            @case('menunggu_konfirmasi')
                                <span class="badge px-4 py-2 fs-6 rounded-pill" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white;">
                                    <i class="bi bi-hourglass-split me-1"></i>Menunggu Konfirmasi
                                </span>
                                @break
                            @case('selesai')
                                <span class="badge px-4 py-2 fs-6 rounded-pill" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white;">
                                    <i class="bi bi-check-circle me-1"></i>Selesai
                                </span>
                                @break
                            @case('cancelled')
                                <span class="badge px-4 py-2 fs-6 rounded-pill" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white;">
                                    <i class="bi bi-x-circle me-1"></i>Dibatalkan
                                </span>
                                @break
                            @default
                                <span class="badge px-4 py-2 fs-6 rounded-pill" style="background: #64748b; color: white;">
                                    {{ ucfirst($rental->status) }}
                                </span>
                        @endswitch
                    </div>

                    @if($rental->status == 'menunggu_konfirmasi')
                        <div class="p-3 rounded-3 mb-3 text-start" style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border: 1px solid #fcd34d;">
                            <small style="color: #92400e;"><i class="bi bi-info-circle me-1"></i> Pelanggan telah mengajukan pengembalian. Cek barang sebelum konfirmasi.</small>
                        </div>
                        <a href="{{ route('kasir.rentals.return', $rental) }}" class="btn w-100 fw-bold py-2" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; border: none; border-radius: 10px;">
                            <i class="bi bi-clipboard-check me-2"></i>Cek Kondisi & Konfirmasi
                        </a>
                    @elseif($rental->status == 'sedang_disewa')
                        <a href="{{ route('kasir.rentals.return', $rental) }}" class="btn w-100 fw-bold py-2" style="background: linear-gradient(135deg, #0652DD 0%, #1E40FF 100%); color: white; border: none; border-radius: 10px;">
                            <i class="bi bi-box-arrow-in-down me-2"></i>Proses Pengembalian
                        </a>
                    @endif
                </div>
            </div>

            <!-- Customer Info -->
            <div class="detail-card shadow-sm mb-4">
                <div class="detail-card-header">
                    <i class="bi bi-person"></i>
                    <h6>Pelanggan</h6>
                </div>
                <div class="detail-card-body">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="d-flex align-items-center justify-content-center fw-bold" style="width: 56px; height: 56px; border-radius: 50%; background: linear-gradient(135deg, #0652DD 0%, #1E40FF 100%); color: white; font-size: 1.25rem;">
                            {{ strtoupper(substr($rental->customer->name ?? '?', 0, 1)) }}
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold" style="color: var(--text-main, #1f2937);">{{ $rental->customer->name ?? 'Guest' }}</h6>
                            <small style="color: var(--text-muted, #6b7280);">{{ $rental->customer->email ?? '-' }}</small>
                        </div>
                    </div>
                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex align-items-center gap-3 p-3 rounded-2" style="background: var(--bg-light, #f8fafc);">
                            <i class="bi bi-telephone" style="color: #0652DD;"></i>
                            <div>
                                <small style="color: var(--text-muted, #6b7280);">Telepon</small>
                                <div class="fw-medium" style="color: var(--text-main, #1f2937);">{{ $rental->customer->phone ?? '-' }}</div>
                            </div>
                        </div>
                        <div class="d-flex align-items-start gap-3 p-3 rounded-2" style="background: var(--bg-light, #f8fafc);">
                            <i class="bi bi-geo-alt" style="color: #0652DD;"></i>
                            <div>
                                <small style="color: var(--text-muted, #6b7280);">Alamat</small>
                                <div class="fw-medium" style="color: var(--text-main, #1f2937);">{{ $rental->customer->address ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Timeline -->
            <div class="detail-card shadow-sm">
                <div class="detail-card-header">
                    <i class="bi bi-calendar3"></i>
                    <h6>Waktu Sewa</h6>
                </div>
                <div class="detail-card-body">
                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex align-items-center gap-3 p-3 rounded-2" style="background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%); border: 1px solid #93c5fd;">
                            <div class="d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border-radius: 50%; background: #0652DD; color: white;">
                                <i class="bi bi-play-fill"></i>
                            </div>
                            <div>
                                <small style="color: #1e40af;">Mulai Sewa</small>
                                <div class="fw-bold" style="color: #1e3a8a;">{{ $rental->start_at ? \Carbon\Carbon::parse($rental->start_at)->format('d M Y, H:i') : '-' }}</div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-3 p-3 rounded-2" style="background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); border: 1px solid #fca5a5;">
                            <div class="d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border-radius: 50%; background: #dc2626; color: white;">
                                <i class="bi bi-alarm"></i>
                            </div>
                            <div>
                                <small style="color: #991b1b;">Jatuh Tempo</small>
                                <div class="fw-bold" style="color: #7f1d1d;">{{ $rental->due_at ? \Carbon\Carbon::parse($rental->due_at)->format('d M Y, H:i') : '-' }}</div>
                            </div>
                        </div>
                        @if($rental->returned_at)
                        <div class="d-flex align-items-center gap-3 p-3 rounded-2" style="background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%); border: 1px solid #6ee7b7;">
                            <div class="d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border-radius: 50%; background: #059669; color: white;">
                                <i class="bi bi-check-lg"></i>
                            </div>
                            <div>
                                <small style="color: #065f46;">Dikembalikan</small>
                                <div class="fw-bold" style="color: #064e3b;">{{ \Carbon\Carbon::parse($rental->returned_at)->format('d M Y, H:i') }}</div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

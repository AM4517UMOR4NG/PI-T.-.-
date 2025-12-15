@extends('admin.layout')

@section('admin_content')
<style>
    .photo-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
    }
    .photo-item {
        position: relative;
        aspect-ratio: 1;
        border-radius: 12px;
        overflow: hidden;
        cursor: pointer;
        transition: transform 0.2s ease;
    }
    .photo-item:hover {
        transform: scale(1.02);
    }
    .photo-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .photo-item .photo-label {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(transparent, rgba(0,0,0,0.7));
        color: white;
        padding: 8px;
        font-size: 0.75rem;
        font-weight: 600;
        text-align: center;
    }
    .info-card {
        background: var(--adaptive-bg-card, #ffffff);
        border: 1px solid var(--adaptive-border, #e2e8f0);
        border-radius: 12px;
    }
    .info-row {
        padding: 12px 16px;
        border-bottom: 1px solid var(--adaptive-border, #f1f5f9);
    }
    .info-row:last-child {
        border-bottom: none;
    }
    .info-label {
        color: var(--adaptive-text-muted, #64748b);
        font-size: 0.85rem;
    }
    .info-value {
        color: var(--adaptive-text-primary, #1e293b);
        font-weight: 500;
    }

    /* Modal for photo preview */
    .photo-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.9);
        z-index: 9999;
        align-items: center;
        justify-content: center;
    }
    .photo-modal.show {
        display: flex;
    }
    .photo-modal img {
        max-width: 90%;
        max-height: 90%;
        object-fit: contain;
    }
    .photo-modal .close-btn {
        position: absolute;
        top: 20px;
        right: 20px;
        color: white;
        font-size: 2rem;
        cursor: pointer;
    }
</style>

<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('admin.damage-reports.index') }}" class="text-muted text-decoration-none mb-2 d-inline-block">
                <i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar
            </a>
            <h2 class="fw-bold text-dark mb-1">Detail Laporan Kerusakan #{{ $damageReport->id }}</h2>
            <p class="text-muted mb-0">Review foto dan berikan feedback untuk laporan ini</p>
        </div>
        <div>
            @if($damageReport->status === 'pending')
                <span class="badge bg-warning bg-opacity-10 text-warning px-4 py-2 rounded-pill fs-6">
                    <i class="bi bi-clock me-1"></i>Menunggu Review
                </span>
            @elseif($damageReport->status === 'reviewed')
                <span class="badge bg-info bg-opacity-10 text-info px-4 py-2 rounded-pill fs-6">
                    <i class="bi bi-check me-1"></i>Sudah Direview
                </span>
            @else
                <span class="badge bg-success bg-opacity-10 text-success px-4 py-2 rounded-pill fs-6">
                    <i class="bi bi-check-all me-1"></i>Selesai
                </span>
            @endif
        </div>
    </div>

    <div class="row g-4">
        <!-- Left Column - Photos -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-images me-2"></i>Foto Kerusakan (6 Sudut)</h5>
                </div>
                <div class="card-body">
                    <div class="photo-grid">
                        @php
                            $photos = [
                                ['key' => 'photo_top', 'label' => 'Atas', 'icon' => 'bi-arrow-up-circle'],
                                ['key' => 'photo_bottom', 'label' => 'Bawah', 'icon' => 'bi-arrow-down-circle'],
                                ['key' => 'photo_front', 'label' => 'Depan', 'icon' => 'bi-box-arrow-in-up'],
                                ['key' => 'photo_back', 'label' => 'Belakang', 'icon' => 'bi-box-arrow-in-down'],
                                ['key' => 'photo_left', 'label' => 'Kiri', 'icon' => 'bi-arrow-left-circle'],
                                ['key' => 'photo_right', 'label' => 'Kanan', 'icon' => 'bi-arrow-right-circle'],
                            ];
                        @endphp
                        
                        @foreach($photos as $photo)
                            <div class="photo-item" onclick="openPhotoModal('{{ $damageReport->{$photo['key']} ? asset('storage/' . $damageReport->{$photo['key']}) : '' }}')">
                                @if($damageReport->{$photo['key']})
                                    <img src="{{ asset('storage/' . $damageReport->{$photo['key']}) }}" alt="{{ $photo['label'] }}">
                                @else
                                    <div class="d-flex align-items-center justify-content-center h-100 bg-light">
                                        <i class="bi bi-image text-muted fs-1"></i>
                                    </div>
                                @endif
                                <div class="photo-label">
                                    <i class="bi {{ $photo['icon'] }} me-1"></i>{{ $photo['label'] }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-chat-left-text me-2"></i>Keterangan Kerusakan</h5>
                </div>
                <div class="card-body">
                    <p class="mb-0 text-dark">{{ $damageReport->description ?? 'Tidak ada keterangan dari pelanggan.' }}</p>
                </div>
            </div>

            <!-- Admin Feedback (if reviewed) -->
            @if($damageReport->admin_feedback)
            <div class="card border-0 shadow-sm mt-4 border-start border-4 border-primary">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold text-primary"><i class="bi bi-chat-right-quote me-2"></i>Feedback Admin</h5>
                </div>
                <div class="card-body">
                    <p class="mb-2 text-dark">{{ $damageReport->admin_feedback }}</p>
                    <div class="small text-muted">
                        Direview oleh: {{ $damageReport->reviewer->name ?? 'Unknown' }} 
                        pada {{ $damageReport->reviewed_at ? $damageReport->reviewed_at->format('d M Y H:i') : '-' }}
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Right Column - Info & Actions -->
        <div class="col-lg-5">
            <!-- Item Info -->
            <div class="info-card mb-4">
                <div class="p-3 border-bottom">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-box me-2"></i>Informasi Item</h6>
                </div>
                @php
                    $itemName = 'Unknown';
                    $itemType = '';
                    $itemImage = null;
                    if($damageReport->rentalItem && $damageReport->rentalItem->rentable) {
                        $rentable = $damageReport->rentalItem->rentable;
                        $itemName = $rentable->nama ?? $rentable->judul ?? $rentable->name ?? 'Unknown';
                        $itemType = class_basename($damageReport->rentalItem->rentable_type);
                        $itemImage = $rentable->foto ?? $rentable->gambar ?? null;
                    }
                @endphp
                <div class="info-row d-flex align-items-center">
                    @if($itemImage)
                        <img src="{{ asset('storage/' . $itemImage) }}" class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;">
                    @else
                        <div class="rounded bg-light d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                            <i class="bi bi-box text-muted fs-4"></i>
                        </div>
                    @endif
                    <div>
                        <div class="info-value">{{ $itemName }}</div>
                        <span class="badge bg-secondary">{{ $itemType }}</span>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">Rental ID</div>
                    <div class="info-value">#{{ $damageReport->rentalItem->rental_id ?? '-' }}</div>
                </div>
            </div>

            <!-- Reporter Info -->
            <div class="info-card mb-4">
                <div class="p-3 border-bottom">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-person me-2"></i>Pelapor</h6>
                </div>
                <div class="info-row">
                    <div class="info-label">Nama</div>
                    <div class="info-value">{{ $damageReport->reporter->name ?? 'Unknown' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Email</div>
                    <div class="info-value">{{ $damageReport->reporter->email ?? '-' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Tanggal Lapor</div>
                    <div class="info-value">{{ $damageReport->created_at->format('d M Y H:i') }}</div>
                </div>
            </div>

            <!-- Fine Info -->
            <div class="info-card mb-4 border-start border-4 {{ $damageReport->fine_amount ? 'border-danger' : 'border-warning' }}">
                <div class="p-3 border-bottom">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-cash me-2"></i>Informasi Denda</h6>
                </div>
                <div class="info-row">
                    <div class="info-label">Status Denda</div>
                    <div class="info-value">
                        @if($damageReport->fine_amount)
                            <span class="badge bg-danger">Sudah Ditetapkan</span>
                        @else
                            <span class="badge bg-warning text-dark">Belum Ditetapkan</span>
                        @endif
                    </div>
                </div>
                @if($damageReport->fine_amount)
                <div class="info-row">
                    <div class="info-label">Jumlah Denda</div>
                    <div class="info-value text-danger fs-5 fw-bold">Rp {{ number_format($damageReport->fine_amount, 0, ',', '.') }}</div>
                </div>
                @endif
            </div>

            <!-- Review Form (if pending) -->
            @if($damageReport->status === 'pending')
            <div class="card border-0 shadow-sm border-start border-4 border-primary">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold text-primary"><i class="bi bi-pencil-square me-2"></i>Review Kerusakan</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.damage-reports.review', $damageReport) }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Feedback / Keterangan <span class="text-danger">*</span></label>
                            <textarea name="admin_feedback" class="form-control" rows="4" placeholder="Berikan feedback tentang kerusakan..." required>{{ old('admin_feedback') }}</textarea>
                            @error('admin_feedback')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Jumlah Denda (Rp) <span class="text-danger">*</span></label>
                            <input type="number" name="fine_amount" class="form-control" min="0" step="1000" placeholder="Masukkan jumlah denda" value="{{ old('fine_amount', 0) }}" required>
                            @error('fine_amount')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Masukkan 0 jika tidak ada denda</small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Status Setelah Review <span class="text-danger">*</span></label>
                            <select name="status" class="form-select" required>
                                <option value="reviewed">Direview (Menunggu Pembayaran Denda)</option>
                                <option value="resolved">Selesai (Tidak Ada Denda / Sudah Dibayar)</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-check-lg me-2"></i>Submit Review
                        </button>
                    </form>
                </div>
            </div>
            @endif

            <!-- Status Progress -->
            @if($damageReport->status !== 'pending')
            <div class="info-card mb-4">
                <div class="p-3 border-bottom">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-diagram-3 me-2"></i>Progress Status</h6>
                </div>
                <div class="p-3">
                    <div class="d-flex flex-column gap-2">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            <span class="text-muted">Admin Review</span>
                            <span class="ms-auto small text-muted">{{ $damageReport->reviewed_at ? $damageReport->reviewed_at->format('d/m/Y H:i') : '-' }}</span>
                        </div>
                        <div class="d-flex align-items-center">
                            @if($damageReport->user_confirmed)
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                            @else
                                <i class="bi bi-circle text-muted me-2"></i>
                            @endif
                            <span class="{{ $damageReport->user_confirmed ? 'text-muted' : 'fw-bold' }}">User Konfirmasi Denda</span>
                            <span class="ms-auto small text-muted">{{ $damageReport->user_confirmed_at ? $damageReport->user_confirmed_at->format('d/m/Y H:i') : '-' }}</span>
                        </div>
                        <div class="d-flex align-items-center">
                            @if($damageReport->fine_paid)
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                            @else
                                <i class="bi bi-circle text-muted me-2"></i>
                            @endif
                            <span class="{{ $damageReport->fine_paid ? 'text-muted' : ($damageReport->user_confirmed ? 'fw-bold' : '') }}">User Bayar Denda</span>
                            <span class="ms-auto small text-muted">{{ $damageReport->fine_paid_at ? $damageReport->fine_paid_at->format('d/m/Y H:i') : '-' }}</span>
                        </div>
                        <div class="d-flex align-items-center">
                            @if($damageReport->kasir_confirmed_at)
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                            @else
                                <i class="bi bi-circle text-muted me-2"></i>
                            @endif
                            <span class="{{ $damageReport->kasir_confirmed_at ? 'text-muted' : ($damageReport->fine_paid ? 'fw-bold' : '') }}">Kasir Konfirmasi</span>
                            <span class="ms-auto small text-muted">{{ $damageReport->kasir_confirmed_at ? $damageReport->kasir_confirmed_at->format('d/m/Y H:i') : '-' }}</span>
                        </div>
                        <div class="d-flex align-items-center">
                            @if($damageReport->status === 'resolved')
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                            @else
                                <i class="bi bi-circle text-muted me-2"></i>
                            @endif
                            <span class="{{ $damageReport->status === 'resolved' ? 'text-muted' : ($damageReport->kasir_confirmed_at ? 'fw-bold' : '') }}">Admin Tutup Case</span>
                            <span class="ms-auto small text-muted">{{ $damageReport->closed_at ? $damageReport->closed_at->format('d/m/Y H:i') : '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Mark as Resolved (only after kasir confirmed) -->
            @if($damageReport->kasir_confirmed_at && $damageReport->status !== 'resolved')
            <div class="card border-0 shadow-sm border-start border-4 border-success">
                <div class="card-body">
                    <div class="alert alert-success mb-3">
                        <i class="bi bi-check-circle me-2"></i>
                        Kasir telah mengkonfirmasi pembayaran denda. Anda dapat menutup case ini.
                    </div>
                    <form action="{{ route('admin.damage-reports.resolve', $damageReport) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success w-100" onclick="return confirm('Tandai laporan ini sebagai selesai dan tutup case?')">
                            <i class="bi bi-check-all me-2"></i>Tutup Case
                        </button>
                    </form>
                </div>
            </div>
            @elseif($damageReport->status === 'reviewed' && !$damageReport->user_confirmed)
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="alert alert-info mb-0">
                        <i class="bi bi-hourglass-split me-2"></i>
                        Menunggu pelanggan mengkonfirmasi dan membayar denda.
                    </div>
                </div>
            </div>
            @elseif($damageReport->user_confirmed && !$damageReport->fine_paid)
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="alert alert-warning mb-0">
                        <i class="bi bi-credit-card me-2"></i>
                        Pelanggan sudah konfirmasi. Menunggu pembayaran denda.
                    </div>
                </div>
            </div>
            @elseif($damageReport->fine_paid && !$damageReport->kasir_confirmed_at)
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="alert alert-primary mb-0">
                        <i class="bi bi-person-badge me-2"></i>
                        Denda sudah dibayar. Menunggu kasir mengkonfirmasi pembayaran.
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Photo Modal -->
<div class="photo-modal" id="photoModal" onclick="closePhotoModal()">
    <span class="close-btn">&times;</span>
    <img src="" id="modalImage" alt="Preview">
</div>

<script>
function openPhotoModal(src) {
    if (!src) return;
    document.getElementById('modalImage').src = src;
    document.getElementById('photoModal').classList.add('show');
}

function closePhotoModal() {
    document.getElementById('photoModal').classList.remove('show');
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closePhotoModal();
    }
});
</script>
@endsection

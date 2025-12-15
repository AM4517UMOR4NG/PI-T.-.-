@extends('admin.layout')

@section('admin_content')
<style>
    :root {
        --glass-bg: rgba(255, 255, 255, 0.8);
        --glass-border: rgba(255, 255, 255, 0.5);
        --glass-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.1);
    }

    [data-theme="dark"] {
        --glass-bg: rgba(30, 41, 59, 0.7);
        --glass-border: rgba(255, 255, 255, 0.05);
        --glass-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3);
    }

    .glass-card {
        background: var(--glass-bg);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid var(--glass-border);
        box-shadow: var(--glass-shadow);
        border-radius: 16px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .glass-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 40px 0 rgba(31, 38, 135, 0.2);
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        font-size: 1.5rem;
    }

    .table-custom th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        background: rgba(0,0,0,0.02);
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }
    
    [data-theme="dark"] .table-custom th {
        background: rgba(255,255,255,0.02);
        border-bottom: 1px solid rgba(255,255,255,0.05);
    }

    .avatar-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
    }

    /* Fix for text-danger being white in global layout */
    .glass-card .text-danger, 
    .table .text-danger,
    .card .text-danger {
        color: #dc3545 !important;
    }
    
    [data-theme="dark"] .glass-card .text-danger,
    [data-theme="dark"] .table .text-danger,
    [data-theme="dark"] .card .text-danger {
        color: #f87171 !important;
    }
</style>

<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h2 class="fw-bold display-6 mb-1">Laporan Kerusakan</h2>
            <p class="text-muted mb-0">Review dan kelola laporan kerusakan dari pelanggan.</p>
        </div>
        <div>
            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                <i class="bi bi-archive me-2"></i>Total: {{ $stats['total'] }} Laporan
            </span>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="glass-card p-4 h-100 position-relative overflow-hidden">
                <div class="d-flex justify-content-between align-items-start position-relative z-1">
                    <div>
                        <p class="text-muted small text-uppercase fw-bold mb-1">Total Laporan</p>
                        <h3 class="fw-bold mb-0 display-6">{{ $stats['total'] }}</h3>
                    </div>
                    <div class="stat-icon bg-secondary bg-opacity-10 text-secondary">
                        <i class="bi bi-file-earmark-text"></i>
                    </div>
                </div>
                <div class="position-absolute bottom-0 end-0 opacity-10" style="transform: translate(30%, 30%);">
                    <i class="bi bi-file-earmark-text" style="font-size: 8rem; color: gray;"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="glass-card p-4 h-100 position-relative overflow-hidden">
                <div class="d-flex justify-content-between align-items-start position-relative z-1">
                    <div>
                        <p class="text-muted small text-uppercase fw-bold mb-1">Menunggu Review</p>
                        <h3 class="fw-bold mb-0 display-6">{{ $stats['pending'] }}</h3>
                    </div>
                    <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                        <i class="bi bi-clock-history"></i>
                    </div>
                </div>
                <div class="position-absolute bottom-0 end-0 opacity-10" style="transform: translate(30%, 30%);">
                    <i class="bi bi-clock-history" style="font-size: 8rem; color: var(--warning);"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="glass-card p-4 h-100 position-relative overflow-hidden">
                <div class="d-flex justify-content-between align-items-start position-relative z-1">
                    <div>
                        <p class="text-muted small text-uppercase fw-bold mb-1">Sudah Direview</p>
                        <h3 class="fw-bold mb-0 display-6">{{ $stats['reviewed'] }}</h3>
                    </div>
                    <div class="stat-icon bg-info bg-opacity-10 text-info">
                        <i class="bi bi-check-circle"></i>
                    </div>
                </div>
                <div class="position-absolute bottom-0 end-0 opacity-10" style="transform: translate(30%, 30%);">
                    <i class="bi bi-check-circle" style="font-size: 8rem; color: var(--secondary);"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="glass-card p-4 h-100 position-relative overflow-hidden">
                <div class="d-flex justify-content-between align-items-start position-relative z-1">
                    <div>
                        <p class="text-muted small text-uppercase fw-bold mb-1">Selesai</p>
                        <h3 class="fw-bold mb-0 display-6">{{ $stats['resolved'] }}</h3>
                    </div>
                    <div class="stat-icon bg-success bg-opacity-10 text-success">
                        <i class="bi bi-check-all"></i>
                    </div>
                </div>
                <div class="position-absolute bottom-0 end-0 opacity-10" style="transform: translate(30%, 30%);">
                    <i class="bi bi-check-all" style="font-size: 8rem; color: var(--success);"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="glass-card mb-4">
        <div class="card-body p-4">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted text-uppercase">Status Laporan</label>
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0"><i class="bi bi-filter"></i></span>
                        <select name="status" class="form-select border-start-0 ps-0">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu Review</option>
                            <option value="reviewed" {{ request('status') == 'reviewed' ? 'selected' : '' }}>Sudah Direview</option>
                            <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted text-uppercase">Dari Tanggal</label>
                    <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted text-uppercase">Sampai Tanggal</label>
                    <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold">
                        <i class="bi bi-search me-2"></i>Terapkan Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Reports Table -->
    <div class="glass-card">
        <div class="card-header bg-transparent border-bottom border-light py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold"><i class="bi bi-list-ul me-2 text-primary"></i>Daftar Laporan Kerusakan</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-custom table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">ID</th>
                        <th>Barang</th>
                        <th>Pelapor</th>
                        <th>Keterangan</th>
                        <th>Bukti Foto</th>
                        <th>Estimasi Denda</th>
                        <th>Status</th>
                        <th>Waktu Lapor</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $report)
                        @php
                            $itemName = 'Tidak Diketahui';
                            $itemType = '';
                            if($report->rentalItem && $report->rentalItem->rentable) {
                                $itemName = $report->rentalItem->rentable->nama ?? $report->rentalItem->rentable->judul ?? $report->rentalItem->rentable->name ?? 'Tidak Diketahui';
                                $itemType = class_basename($report->rentalItem->rentable_type);
                            }
                        @endphp
                        <tr>
                            <td class="ps-4">
                                <span class="font-monospace text-muted">#{{ $report->id }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="icon-shape bg-light text-primary rounded-3 p-2 me-3">
                                        @if($itemType == 'UnitPS')
                                            <i class="bi bi-controller"></i>
                                        @elseif($itemType == 'Game')
                                            <i class="bi bi-disc"></i>
                                        @else
                                            <i class="bi bi-headset"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="fw-bold">{{ $itemName }}</div>
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary small">{{ $itemType }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle bg-primary bg-opacity-10 text-primary me-2" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                        {{ substr($report->reporter->name ?? 'U', 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold small">{{ $report->reporter->name ?? 'Tidak Diketahui' }}</div>
                                        <div class="small text-muted" style="font-size: 0.75rem;">{{ $report->reporter->email ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="small text-muted" style="max-width: 200px;">
                                    {{ Str::limit($report->description, 50) ?? '-' }}
                                </div>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    @if($report->photo_top)
                                        <img src="{{ asset('storage/' . $report->photo_top) }}" class="rounded border" style="width: 36px; height: 36px; object-fit: cover;" title="Atas">
                                    @endif
                                    @if($report->photo_front)
                                        <img src="{{ asset('storage/' . $report->photo_front) }}" class="rounded border" style="width: 36px; height: 36px; object-fit: cover;" title="Depan">
                                    @endif
                                    @php
                                        $photoCount = collect([$report->photo_top, $report->photo_bottom, $report->photo_front, $report->photo_back, $report->photo_left, $report->photo_right])->filter()->count();
                                    @endphp
                                    @if($photoCount > 2)
                                        <div class="rounded bg-light d-flex align-items-center justify-content-center border small fw-bold text-muted" style="width: 36px; height: 36px;">
                                            +{{ $photoCount - 2 }}
                                        </div>
                                    @endif
                                    @if($photoCount == 0)
                                        <span class="text-muted small fst-italic">Tidak ada foto</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if($report->fine_amount)
                                    <span class="fw-bold text-danger">Rp {{ number_format($report->fine_amount, 0, ',', '.') }}</span>
                                @else
                                    <span class="badge bg-light text-muted border">Belum ditentukan</span>
                                @endif
                            </td>
                            <td>
                                @if($report->status === 'pending')
                                    <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill">Menunggu Review</span>
                                @elseif($report->status === 'reviewed')
                                    <span class="badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill">Sudah Direview</span>
                                @else
                                    <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">Selesai</span>
                                @endif
                            </td>
                            <td>
                                <div class="small text-muted">{{ $report->created_at->locale('id')->isoFormat('D MMM Y') }}</div>
                                <div class="small text-muted">{{ $report->created_at->format('H:i') }}</div>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.damage-reports.show', $report) }}" class="btn btn-sm btn-primary rounded-pill px-3">
                                    <i class="bi bi-eye me-1"></i>Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <div class="text-muted opacity-50">
                                    <i class="bi bi-inbox display-4 d-block mb-3"></i>
                                    <h5>Tidak ada laporan kerusakan</h5>
                                    <p class="small">Belum ada laporan kerusakan yang masuk sesuai filter ini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($reports->hasPages())
            <div class="card-footer bg-transparent border-top border-light py-3">
                {{ $reports->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

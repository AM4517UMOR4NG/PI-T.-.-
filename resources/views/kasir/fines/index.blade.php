@extends('kasir.layout')

@section('kasir_content')
<style>
    /* Adaptive colors for fines page */
    .table .fw-bold {
        color: var(--adaptive-text-primary, #1e293b);
    }
    
    [data-theme="dark"] .table .fw-bold {
        color: var(--adaptive-text-primary) !important;
    }
</style>
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Pembayaran Denda</h2>
            <p class="text-muted mb-0">Kelola pembayaran denda kerusakan</p>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 border-start border-4 border-secondary">
                <div class="card-body">
                    <p class="text-muted mb-1 small text-uppercase fw-bold">Total Denda</p>
                    <h3 class="fw-bold mb-0">Rp {{ number_format($stats['total_fines'], 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 border-start border-4 border-success">
                <div class="card-body">
                    <p class="text-muted mb-1 small text-uppercase fw-bold">Sudah Dibayar</p>
                    <h3 class="fw-bold text-success mb-0">Rp {{ number_format($stats['paid_fines'], 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 border-start border-4 border-warning">
                <div class="card-body">
                    <p class="text-muted mb-1 small text-uppercase fw-bold">Menunggu Konfirmasi</p>
                    <h3 class="fw-bold text-warning mb-0">{{ $stats['pending_confirmation'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 border-start border-4 border-danger">
                <div class="card-body">
                    <p class="text-muted mb-1 small text-uppercase fw-bold">Belum Dibayar</p>
                    <h3 class="fw-bold text-danger mb-0">{{ $stats['unpaid_fines'] }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label small fw-bold">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua</option>
                        <option value="pending_confirmation" {{ request('status') == 'pending_confirmation' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Sudah Dikonfirmasi</option>
                        <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>Belum Dibayar</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search me-1"></i>Filter
                    </button>
                    <a href="{{ route('kasir.fines') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">ID</th>
                        <th>Item Rusak</th>
                        <th>Pelanggan</th>
                        <th>Jumlah Denda</th>
                        <th>Status Bayar</th>
                        <th>Tanggal Bayar</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($damageReports as $report)
                        @php
                            $itemName = 'Unknown';
                            $customerName = 'Unknown';
                            if($report->rentalItem && $report->rentalItem->rentable) {
                                $itemName = $report->rentalItem->rentable->nama ?? $report->rentalItem->rentable->judul ?? 'Unknown';
                            }
                            if($report->rentalItem && $report->rentalItem->rental && $report->rentalItem->rental->customer) {
                                $customerName = $report->rentalItem->rental->customer->name;
                            }
                        @endphp
                        <tr>
                            <td class="ps-4">
                                <span class="badge bg-light">#{{ $report->id }}</span>
                            </td>
                            <td>
                                <div class="fw-bold">{{ $itemName }}</div>
                                <small class="text-muted">Rental #{{ $report->rentalItem->rental_id ?? '-' }}</small>
                            </td>
                            <td>
                                <div class="fw-bold">{{ $customerName }}</div>
                                <small class="text-muted">{{ $report->reporter->email ?? '-' }}</small>
                            </td>
                            <td>
                                <span class="fw-bold text-danger">Rp {{ number_format($report->fine_amount, 0, ',', '.') }}</span>
                            </td>
                            <td>
                                @if($report->kasir_confirmed_at)
                                    <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">
                                        <i class="bi bi-check-circle me-1"></i>Dikonfirmasi
                                    </span>
                                @elseif($report->fine_paid)
                                    <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill">
                                        <i class="bi bi-clock me-1"></i>Menunggu Konfirmasi
                                    </span>
                                @elseif($report->user_confirmed)
                                    <span class="badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill">
                                        <i class="bi bi-hourglass me-1"></i>Menunggu Bayar
                                    </span>
                                @else
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 rounded-pill">
                                        <i class="bi bi-dash me-1"></i>Proses
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($report->fine_paid_at)
                                    <small class="text-muted">{{ $report->fine_paid_at->format('d M Y H:i') }}</small>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($report->fine_paid && !$report->kasir_confirmed_at)
                                    <form action="{{ route('kasir.confirm-fine', $report) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Konfirmasi pembayaran denda ini?')">
                                            <i class="bi bi-check-lg me-1"></i>Konfirmasi
                                        </button>
                                    </form>
                                @elseif($report->kasir_confirmed_at)
                                    <span class="text-success"><i class="bi bi-check-circle-fill"></i></span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Tidak ada data denda
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($damageReports->hasPages())
            <div class="card-footer bg-white">
                {{ $damageReports->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@extends('kasir.layout')

@section('kasir_content')
<style>
    /* Adaptive colors for transaction page */
    .stat-card h4 {
        color: inherit;
    }
    
    [data-theme="dark"] .stat-card h4.text-dark {
        color: var(--adaptive-text-primary) !important;
    }
    
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
            <h2 class="fw-bold mb-1">Semua Transaksi</h2>
            <p class="text-muted mb-0">Kelola semua transaksi rental</p>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row g-3 mb-4">
        <div class="col">
            <div class="card border-0 shadow-sm text-center py-3">
                <div class="card-body py-2">
                    <h4 class="fw-bold mb-0">{{ $stats['total'] }}</h4>
                    <small class="text-muted">Total</small>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card border-0 shadow-sm text-center py-3 border-start border-4 border-warning">
                <div class="card-body py-2">
                    <h4 class="fw-bold text-warning mb-0">{{ $stats['pending'] }}</h4>
                    <small class="text-muted">Pending</small>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card border-0 shadow-sm text-center py-3 border-start border-4 border-primary">
                <div class="card-body py-2">
                    <h4 class="fw-bold text-primary mb-0">{{ $stats['active'] }}</h4>
                    <small class="text-muted">Aktif</small>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card border-0 shadow-sm text-center py-3 border-start border-4 border-info">
                <div class="card-body py-2">
                    <h4 class="fw-bold text-info mb-0">{{ $stats['waiting'] }}</h4>
                    <small class="text-muted">Menunggu</small>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card border-0 shadow-sm text-center py-3 border-start border-4 border-success">
                <div class="card-body py-2">
                    <h4 class="fw-bold text-success mb-0">{{ $stats['completed'] }}</h4>
                    <small class="text-muted">Selesai</small>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card border-0 shadow-sm text-center py-3 border-start border-4 border-danger">
                <div class="card-body py-2">
                    <h4 class="fw-bold text-danger mb-0">{{ $stats['cancelled'] }}</h4>
                    <small class="text-muted">Batal</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Cari</label>
                    <input type="text" name="search" class="form-control" placeholder="Kode / Nama pelanggan" value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="sedang_disewa" {{ request('status') == 'sedang_disewa' ? 'selected' : '' }}>Sedang Disewa</option>
                        <option value="menunggu_konfirmasi" {{ request('status') == 'menunggu_konfirmasi' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                        <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold">Dari</label>
                    <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold">Sampai</label>
                    <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search me-1"></i>Filter
                    </button>
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
                        <th class="ps-4">Kode</th>
                        <th>Pelanggan</th>
                        <th>Item</th>
                        <th>Total</th>
                        <th>Dibayar</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rentals as $rental)
                        <tr>
                            <td class="ps-4">
                                <span class="font-monospace fw-bold">{{ $rental->kode ?? '#'.$rental->id }}</span>
                            </td>
                            <td>
                                <div class="fw-bold">{{ $rental->customer->name ?? '-' }}</div>
                                <small class="text-muted">{{ $rental->customer->email ?? '-' }}</small>
                            </td>
                            <td>
                                @php 
                                    $names = $rental->items->map(function($item) {
                                        return ($item->rentable->nama ?? $item->rentable->judul ?? 'Unknown');
                                    })->take(2)->implode(', ');
                                    $remaining = $rental->items->count() - 2;
                                @endphp
                                <span class="text-truncate d-inline-block" style="max-width: 150px;">
                                    {{ $names }}{{ $remaining > 0 ? " +{$remaining}" : '' }}
                                </span>
                            </td>
                            <td>
                                <span class="fw-bold">Rp {{ number_format($rental->total, 0, ',', '.') }}</span>
                                @if($rental->fine > 0)
                                    <br><small class="text-danger">+Denda: Rp {{ number_format($rental->fine, 0, ',', '.') }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="{{ $rental->paid >= $rental->total ? 'text-success' : 'text-danger' }}">
                                    Rp {{ number_format($rental->paid, 0, ',', '.') }}
                                </span>
                            </td>
                            <td>
                                @switch($rental->status)
                                    @case('pending')
                                        <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill">Pending</span>
                                        @break
                                    @case('sedang_disewa')
                                        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">Sedang Disewa</span>
                                        @break
                                    @case('menunggu_konfirmasi')
                                        <span class="badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill">Menunggu Konfirmasi</span>
                                        @break
                                    @case('selesai')
                                        <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">Selesai</span>
                                        @break
                                    @case('cancelled')
                                        <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill">Dibatalkan</span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary">{{ $rental->status }}</span>
                                @endswitch
                            </td>
                            <td>
                                <small class="text-muted">{{ $rental->created_at->format('d M Y') }}</small>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="{{ route('kasir.rentals.show', $rental) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if($rental->status === 'menunggu_konfirmasi')
                                        <a href="{{ route('kasir.rentals.return', $rental) }}" class="btn btn-sm btn-success">
                                            <i class="bi bi-check-lg"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Tidak ada transaksi
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($rentals->hasPages())
            <div class="card-footer bg-white">
                {{ $rentals->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

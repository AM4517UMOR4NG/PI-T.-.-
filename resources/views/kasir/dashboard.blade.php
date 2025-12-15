@extends('kasir.layout')

@section('kasir_content')
<style>
    /* Kasir Dashboard Adaptive Colors */
    .stat-card {
        background-color: var(--adaptive-bg-card, #ffffff);
        border-color: var(--adaptive-border, #e2e8f0);
    }
    
    .stat-card .card-body p,
    .stat-card .card-body h3 {
        color: var(--adaptive-text-primary, #1e293b);
    }
    
    .stat-card .text-muted {
        color: var(--adaptive-text-muted, #64748b) !important;
    }
    
    [data-theme="dark"] .stat-card {
        background-color: var(--adaptive-bg-card) !important;
    }
    
    [data-theme="dark"] .stat-card .card-body p,
    [data-theme="dark"] .stat-card .card-body h3 {
        color: var(--adaptive-text-primary) !important;
    }
    
    /* Fix for icon shapes */
    .icon-shape {
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    /* Ensure list-group items are readable */
    .list-group-item .fw-bold {
        color: var(--adaptive-text-primary, #1e293b);
    }
    
    [data-theme="dark"] .list-group-item .fw-bold {
        color: var(--adaptive-text-primary) !important;
    }
    
    [data-theme="dark"] .list-group-item .text-muted {
        color: var(--adaptive-text-muted) !important;
    }
    
    /* Table text colors */
    .table .fw-bold {
        color: var(--adaptive-text-primary, #1e293b);
    }
    
    [data-theme="dark"] .table .fw-bold {
        color: var(--adaptive-text-primary) !important;
    }
    
    [data-theme="dark"] .table .text-muted {
        color: var(--adaptive-text-muted) !important;
    }
</style>
<div class="container-fluid py-4">
    @if(session('impersonate_admin_id'))
        <div class="alert alert-warning d-flex align-items-center mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <div class="flex-grow-1">
                Anda sedang login sebagai Kasir (Impersonation).
            </div>
            <form action="{{ route('admin.impersonate.leave') }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="btn btn-warning btn-sm fw-bold">
                    <i class="bi bi-arrow-return-left me-1"></i> Kembali ke Admin
                </button>
            </form>
        </div>
    @endif

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Dashboard Kasir</h2>
            <p class="text-muted mb-0">Selamat datang, {{ Auth::user()->name }}!</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('kasir.daily-report') }}" class="btn btn-outline-primary">
                <i class="bi bi-file-earmark-text me-1"></i>Laporan Harian
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 border-start border-4 border-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small text-uppercase fw-bold">Menunggu Pembayaran</p>
                            <h3 class="fw-bold mb-0">{{ $stats['pending_payment'] }}</h3>
                        </div>
                        <div class="icon-shape bg-warning bg-opacity-10 text-warning rounded-3 p-3">
                            <i class="bi bi-clock-history fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 border-start border-4 border-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small text-uppercase fw-bold">Sedang Disewa</p>
                            <h3 class="fw-bold mb-0">{{ $stats['active_rentals'] }}</h3>
                        </div>
                        <div class="icon-shape bg-primary bg-opacity-10 text-primary rounded-3 p-3">
                            <i class="bi bi-controller fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 border-start border-4 border-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small text-uppercase fw-bold">Menunggu Konfirmasi</p>
                            <h3 class="fw-bold mb-0">{{ $stats['waiting_confirmation'] }}</h3>
                        </div>
                        <div class="icon-shape bg-info bg-opacity-10 text-info rounded-3 p-3">
                            <i class="bi bi-hourglass-split fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 border-start border-4 border-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small text-uppercase fw-bold">Selesai Hari Ini</p>
                            <h3 class="fw-bold mb-0">{{ $stats['completed_today'] }}</h3>
                        </div>
                        <div class="icon-shape bg-success bg-opacity-10 text-success rounded-3 p-3">
                            <i class="bi bi-check-circle fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Today's Income -->
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100 bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1 small text-uppercase fw-bold opacity-75">Pemasukan Hari Ini</p>
                            <h2 class="fw-bold mb-0">Rp {{ number_format($todayIncome, 0, ',', '.') }}</h2>
                            <small class="opacity-75">{{ now()->format('d F Y') }}</small>
                        </div>
                        <div class="opacity-50">
                            <i class="bi bi-cash-stack" style="font-size: 3rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100 bg-gradient" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1 small text-uppercase fw-bold opacity-75">Denda Menunggu Konfirmasi</p>
                            <h2 class="fw-bold mb-0">{{ $stats['pending_fine_confirmation'] }}</h2>
                            <small class="opacity-75">Pembayaran denda perlu dikonfirmasi</small>
                        </div>
                        <div class="opacity-50">
                            <i class="bi bi-exclamation-triangle" style="font-size: 3rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Pending Fine Confirmations -->
        @if(count($pendingFineConfirmations) > 0)
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100 border-start border-4 border-danger">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-danger">
                        <i class="bi bi-exclamation-circle me-2"></i>Denda Menunggu Konfirmasi
                    </h5>
                    <a href="{{ route('kasir.fines') }}" class="btn btn-sm btn-outline-danger">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @foreach($pendingFineConfirmations as $report)
                            @php
                                $itemName = 'Unknown';
                                if($report->rentalItem && $report->rentalItem->rentable) {
                                    $itemName = $report->rentalItem->rentable->nama ?? $report->rentalItem->rentable->judul ?? 'Unknown';
                                }
                            @endphp
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-bold">{{ $itemName }}</div>
                                    <small class="text-muted">
                                        {{ $report->rentalItem->rental->customer->name ?? 'Unknown' }} - 
                                        Rp {{ number_format($report->fine_amount, 0, ',', '.') }}
                                    </small>
                                </div>
                                <form action="{{ route('kasir.confirm-fine', $report) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Konfirmasi pembayaran denda?')">
                                        <i class="bi bi-check-lg"></i> Konfirmasi
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Waiting Return Confirmation -->
        @if(count($waitingReturnConfirmation) > 0)
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100 border-start border-4 border-info">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-info">
                        <i class="bi bi-box-arrow-in-down me-2"></i>Menunggu Konfirmasi Pengembalian
                    </h5>
                    <a href="{{ route('kasir.rentals.index') }}" class="btn btn-sm btn-outline-info">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @foreach($waitingReturnConfirmation as $rental)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-bold">{{ $rental->kode ?? '#'.$rental->id }}</div>
                                    <small class="text-muted">
                                        {{ $rental->customer->name ?? 'Unknown' }} - 
                                        {{ $rental->returned_at ? $rental->returned_at->format('d M Y H:i') : '-' }}
                                    </small>
                                </div>
                                <a href="{{ route('kasir.rentals.return', $rental) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-eye"></i> Review
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Recent Transactions -->
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-clock-history me-2"></i>Transaksi Terbaru</h5>
                    <a href="{{ route('kasir.transactions') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Kode</th>
                                <th>Pelanggan</th>
                                <th>Item</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentRentals as $rental)
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
                                                return ($item->rentable->nama ?? $item->rentable->judul ?? 'Unknown') . ($item->quantity > 1 ? ' x'.$item->quantity : '');
                                            })->take(2)->implode(', ');
                                            $remaining = $rental->items->count() - 2;
                                        @endphp
                                        <span class="text-truncate d-inline-block" style="max-width: 200px;">
                                            {{ $names }}{{ $remaining > 0 ? " +{$remaining} lainnya" : '' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="fw-bold">Rp {{ number_format($rental->total, 0, ',', '.') }}</span>
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
                                                <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 rounded-pill">{{ $rental->status }}</span>
                                        @endswitch
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('kasir.rentals.show', $rental) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                        Belum ada transaksi
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

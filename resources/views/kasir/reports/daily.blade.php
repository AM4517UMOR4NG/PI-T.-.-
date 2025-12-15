@extends('kasir.layout')

@section('kasir_content')
<style>
    /* Adaptive colors for daily report */
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
            <h2 class="fw-bold mb-1">Laporan Harian</h2>
            <p class="text-muted mb-0">{{ $date->format('l, d F Y') }}</p>
        </div>
        <div>
            <form method="GET" class="d-flex gap-2">
                <input type="date" name="date" class="form-control" value="{{ $date->format('Y-m-d') }}">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 border-start border-4 border-primary">
                <div class="card-body">
                    <p class="text-muted mb-1 small text-uppercase fw-bold">Rental Dibuat</p>
                    <h3 class="fw-bold mb-0">{{ $summary['total_rentals_created'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 border-start border-4 border-success">
                <div class="card-body">
                    <p class="text-muted mb-1 small text-uppercase fw-bold">Rental Selesai</p>
                    <h3 class="fw-bold mb-0">{{ $summary['total_rentals_completed'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 border-start border-4 border-info">
                <div class="card-body">
                    <p class="text-muted mb-1 small text-uppercase fw-bold">Pemasukan Rental</p>
                    <h3 class="fw-bold text-info mb-0">Rp {{ number_format($summary['total_rental_income'], 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 border-start border-4 border-warning">
                <div class="card-body">
                    <p class="text-muted mb-1 small text-uppercase fw-bold">Pemasukan Denda</p>
                    <h3 class="fw-bold text-warning mb-0">Rp {{ number_format($summary['total_fine_income'], 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Income -->
    <div class="card border-0 shadow-sm mb-4 bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <div class="card-body text-white text-center py-4">
            <p class="mb-1 text-uppercase fw-bold opacity-75">Total Pemasukan Hari Ini</p>
            <h1 class="fw-bold mb-0">Rp {{ number_format($summary['total_income'], 0, ',', '.') }}</h1>
        </div>
    </div>

    <div class="row g-4">
        <!-- Rentals Created -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-plus-circle me-2 text-primary"></i>Rental Dibuat</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Kode</th>
                                <th>Pelanggan</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rentalsCreated as $rental)
                                <tr>
                                    <td><span class="font-monospace">{{ $rental->kode ?? '#'.$rental->id }}</span></td>
                                    <td>{{ $rental->customer->name ?? '-' }}</td>
                                    <td>Rp {{ number_format($rental->total, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-3">Tidak ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Rentals Completed -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-check-circle me-2 text-success"></i>Rental Selesai</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Kode</th>
                                <th>Pelanggan</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rentalsCompleted as $rental)
                                <tr>
                                    <td><span class="font-monospace">{{ $rental->kode ?? '#'.$rental->id }}</span></td>
                                    <td>{{ $rental->customer->name ?? '-' }}</td>
                                    <td>Rp {{ number_format($rental->total, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-3">Tidak ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Payments -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-cash me-2 text-info"></i>Pembayaran Diterima</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Rental</th>
                                <th>Pelanggan</th>
                                <th>Jumlah</th>
                                <th>Metode</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payments as $payment)
                                <tr>
                                    <td><span class="font-monospace">{{ $payment->rental->kode ?? '#'.$payment->rental_id }}</span></td>
                                    <td>{{ $payment->rental->customer->name ?? '-' }}</td>
                                    <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                                    <td><span class="badge bg-secondary">{{ $payment->payment_method ?? '-' }}</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">Tidak ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Fines Confirmed -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-exclamation-triangle me-2 text-warning"></i>Denda Dikonfirmasi</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>ID</th>
                                <th>Pelanggan</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($finesConfirmed as $fine)
                                <tr>
                                    <td>#{{ $fine->id }}</td>
                                    <td>{{ $fine->rentalItem->rental->customer->name ?? '-' }}</td>
                                    <td>Rp {{ number_format($fine->fine_amount, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-3">Tidak ada data</td>
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

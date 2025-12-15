@extends('kasir.layout')

@section('title', 'Manajemen Pembayaran')

@section('kasir_content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1"><i class="bi bi-credit-card me-2"></i>Manajemen Pembayaran</h4>
            <p class="text-muted mb-0">Kelola semua pembayaran rental</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card card-glow h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-success bg-opacity-10 rounded-3 p-3">
                                <i class="bi bi-cash-stack text-success fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-muted small mb-1">Total Pendapatan</p>
                            <h5 class="fw-bold mb-0">Rp {{ number_format($stats['total_income'], 0, ',', '.') }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-glow h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 rounded-3 p-3">
                                <i class="bi bi-calendar-check text-primary fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-muted small mb-1">Pendapatan Hari Ini</p>
                            <h5 class="fw-bold mb-0">Rp {{ number_format($stats['today_income'], 0, ',', '.') }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-glow h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-warning bg-opacity-10 rounded-3 p-3">
                                <i class="bi bi-hourglass-split text-warning fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-muted small mb-1">Menunggu Pembayaran</p>
                            <h5 class="fw-bold mb-0">{{ $stats['pending_payments'] }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-glow h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-info bg-opacity-10 rounded-3 p-3">
                                <i class="bi bi-check-circle text-info fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-muted small mb-1">Pembayaran Sukses</p>
                            <h5 class="fw-bold mb-0">{{ $stats['successful_payments'] }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="card card-glow mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label small">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="settlement" {{ request('status') == 'settlement' ? 'selected' : '' }}>Sukses</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="expire" {{ request('status') == 'expire' ? 'selected' : '' }}>Expired</option>
                        <option value="cancel" {{ request('status') == 'cancel' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small">Dari Tanggal</label>
                    <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label small">Sampai Tanggal</label>
                    <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="bi bi-search me-1"></i> Filter
                    </button>
                    <a href="{{ route('kasir.payments') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Payments Table -->
    <div class="card card-glow">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">ID Pembayaran</th>
                            <th>Kode Rental</th>
                            <th>Pelanggan</th>
                            <th>Jumlah</th>
                            <th>Metode</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                        <tr>
                            <td class="ps-4">
                                <span class="fw-medium">{{ $payment->order_id ?? $payment->id }}</span>
                            </td>
                            <td>
                                @if($payment->rental)
                                <a href="{{ route('kasir.rentals.show', $payment->rental) }}" class="text-decoration-none">
                                    {{ $payment->rental->kode }}
                                </a>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($payment->rental && $payment->rental->customer)
                                <div class="d-flex align-items-center">
                                    <img src="{{ $payment->rental->customer->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($payment->rental->customer->name) }}" 
                                         class="rounded-circle me-2" width="32" height="32">
                                    <span>{{ $payment->rental->customer->name }}</span>
                                </div>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="fw-bold text-success">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ ucfirst($payment->payment_type ?? 'N/A') }}</span>
                            </td>
                            <td>
                                @php
                                    $statusClass = match($payment->transaction_status) {
                                        'settlement', 'capture' => 'success',
                                        'pending' => 'warning',
                                        'expire' => 'secondary',
                                        'cancel', 'deny' => 'danger',
                                        default => 'secondary'
                                    };
                                    $statusLabel = match($payment->transaction_status) {
                                        'settlement', 'capture' => 'Sukses',
                                        'pending' => 'Pending',
                                        'expire' => 'Expired',
                                        'cancel' => 'Dibatalkan',
                                        'deny' => 'Ditolak',
                                        default => ucfirst($payment->transaction_status ?? 'Unknown')
                                    };
                                @endphp
                                <span class="badge bg-{{ $statusClass }}">{{ $statusLabel }}</span>
                            </td>
                            <td>
                                <small class="text-muted">{{ $payment->created_at->format('d M Y H:i') }}</small>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="bi bi-inbox display-4 d-block mb-3"></i>
                                    <p>Belum ada data pembayaran</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($payments->hasPages())
        <div class="card-footer bg-transparent">
            {{ $payments->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

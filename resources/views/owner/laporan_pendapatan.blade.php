@extends('pemilik.layout')
@section('title','Laporan Pendapatan')
@section('owner_content')

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold mb-1" style="color: var(--text-main);">Laporan Pendapatan</h2>
            <p class="text-muted mb-0">Analisis detail pendapatan bisnis Anda.</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-secondary btn-sm shadow-sm" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse" aria-expanded="false" aria-controls="filterCollapse">
                <i class="bi bi-filter me-2"></i>Filter Tanggal
            </button>
            <form action="{{ route('pemilik.laporan.export') }}" method="GET" class="d-flex gap-2">
                <input type="hidden" name="format" value="xlsx">
                <input type="hidden" name="dari" value="{{ $dari ?? '' }}">
                <input type="hidden" name="sampai" value="{{ $sampai ?? '' }}">
                <button type="submit" class="btn btn-outline-success btn-sm shadow-sm"><i class="bi bi-file-earmark-excel me-2"></i>Export Excel</button>
            </form>
            <form action="{{ route('pemilik.laporan.export') }}" method="GET" class="d-flex gap-2">
                <input type="hidden" name="format" value="pdf">
                <input type="hidden" name="dari" value="{{ $dari ?? '' }}">
                <input type="hidden" name="sampai" value="{{ $sampai ?? '' }}">
                <button type="submit" class="btn btn-outline-danger btn-sm shadow-sm"><i class="bi bi-file-earmark-pdf me-2"></i>Export PDF</button>
            </form>
        </div>
    </div>

    <!-- Collapsible Filter -->
    <div class="collapse mb-4 {{ request('dari') || request('sampai') ? 'show' : '' }}" id="filterCollapse">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form method="GET" action="{{ route('pemilik.laporan_pendapatan') }}">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label text-muted small fw-bold text-uppercase ls-1">Dari Tanggal</label>
                            <input type="date" class="form-control" name="dari" value="{{ $dari ?? '' }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-muted small fw-bold text-uppercase ls-1">Sampai Tanggal</label>
                            <input type="date" class="form-control" name="sampai" value="{{ $sampai ?? '' }}">
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-dark w-100 fw-bold"><i class="bi bi-filter me-2"></i>Terapkan Filter</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Top Stats Row -->
    <div class="row g-4 mb-4">
        <!-- Grand Total -->
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4 d-flex flex-column justify-content-center">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <p class="text-muted small mb-1 fw-bold text-uppercase ls-1">Total Pemasukan</p>
                            <h2 class="fw-bold mb-0" style="color: var(--text-main);">Rp {{ number_format($revenueStats['grand_total'] ?? 0, 0, ',', '.') }}</h2>
                        </div>
                        <div class="bg-light p-2 rounded-circle text-muted">
                            <i class="bi bi-wallet2 fs-4"></i>
                        </div>
                    </div>
                    <div class="mt-2 small text-muted">
                        <i class="bi bi-calendar-check me-1"></i> {{ $periodLabel ?? 'Total' }}
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Rental Total -->
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <p class="text-muted small mb-1 fw-bold text-uppercase ls-1">Pendapatan Rental</p>
                            <h3 class="fw-bold mb-0" style="color: var(--text-main);">Rp {{ number_format($revenueStats['total'] ?? 0, 0, ',', '.') }}</h3>
                        </div>
                        <div class="bg-light p-2 rounded-circle text-muted">
                            <i class="bi bi-controller fs-4"></i>
                        </div>
                    </div>
                    <div class="d-flex gap-3 mt-3 pt-3 border-top border-light">
                        <div>
                            <span class="text-muted small d-block">Hari Ini</span>
                            <span class="fw-bold small" style="color: var(--text-main);">+{{ number_format($revenueStats['today'] ?? 0, 0, ',', '.') }}</span>
                        </div>
                        <div>
                            <span class="text-muted small d-block">Bulan Ini</span>
                            <span class="fw-bold small" style="color: var(--text-main);">{{ number_format($revenueStats['month'] ?? 0, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Fines Total -->
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <p class="text-muted small mb-1 fw-bold text-uppercase ls-1">Pendapatan Denda</p>
                            <h3 class="fw-bold mb-0" style="color: var(--text-main);">Rp {{ number_format($revenueStats['fine_total'] ?? 0, 0, ',', '.') }}</h3>
                        </div>
                        <div class="bg-light p-2 rounded-circle text-muted">
                            <i class="bi bi-exclamation-triangle fs-4"></i>
                        </div>
                    </div>
                    <div class="d-flex gap-3 mt-3 pt-3 border-top border-light">
                        <div>
                            <span class="text-muted small d-block">Hari Ini</span>
                            <span class="fw-bold small" style="color: var(--text-main);">+{{ number_format($revenueStats['fine_today'] ?? 0, 0, ',', '.') }}</span>
                        </div>
                        <div>
                            <span class="text-muted small d-block">Bulan Ini</span>
                            <span class="fw-bold small" style="color: var(--text-main);">{{ number_format($revenueStats['fine_month'] ?? 0, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="row g-4 mb-4">
        <!-- Chart Section -->
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-bold mb-1" style="color: var(--text-main);">Grafik Pendapatan</h5>
                        <p class="text-muted small mb-0">Tren pendapatan - {{ $periodLabel ?? '7 Hari Terakhir' }}</p>
                    </div>
                </div>
                <div class="card-body px-4 pb-4">
                    <div style="height: 350px;">
                        <canvas id="revChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Stats Side Panel -->
        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <h5 class="fw-bold mb-1" style="color: var(--text-main);">Rincian Periode</h5>
                    <p class="text-muted small mb-0">Detail berdasarkan filter</p>
                </div>
                <div class="card-body px-4">
                    <!-- Rental Stats -->
                    <div class="mb-4">
                        <h6 class="text-muted fw-bold text-uppercase small mb-3"><i class="bi bi-controller me-2"></i>Rental</h6>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Terpilih ({{ $periodLabel }})</span>
                            <span class="fw-bold" style="color: var(--text-main);">Rp {{ number_format($revenueStats['filtered'] ?? 0, 0, ',', '.') }}</span>
                        </div>
                        <div class="progress" style="height: 4px; background-color: var(--bg-light);">
                            <div class="progress-bar bg-dark" role="progressbar" style="width: 100%; opacity: 0.7;"></div>
                        </div>
                    </div>

                    <!-- Fine Stats -->
                    <div class="mb-4">
                        <h6 class="text-muted fw-bold text-uppercase small mb-3"><i class="bi bi-exclamation-triangle me-2"></i>Denda</h6>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Terpilih ({{ $periodLabel }})</span>
                            <span class="fw-bold" style="color: var(--text-main);">Rp {{ number_format($revenueStats['fine_filtered'] ?? 0, 0, ',', '.') }}</span>
                        </div>
                        <div class="progress" style="height: 4px; background-color: var(--bg-light);">
                            <div class="progress-bar bg-secondary" role="progressbar" style="width: 100%; opacity: 0.5;"></div>
                        </div>
                    </div>

                    <!-- Total Stats -->
                    <div class="pt-3 border-top">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-muted">Total Periode Ini</span>
                            <span class="fw-bold fs-5" style="color: var(--text-main);">Rp {{ number_format(($revenueStats['filtered'] ?? 0) + ($revenueStats['fine_filtered'] ?? 0), 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="row g-4">
        <!-- Rental Payments -->
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-bold mb-1" style="color: var(--text-main);">Riwayat Pembayaran Masuk</h5>
                        <p class="text-muted small mb-0">Transaksi rental terbaru</p>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-4 py-3 text-muted fw-semibold border-0 small text-uppercase ls-1">Tanggal</th>
                                    <th class="px-4 py-3 text-muted fw-semibold border-0 small text-uppercase ls-1">ID</th>
                                    <th class="px-4 py-3 text-muted fw-semibold border-0 small text-uppercase ls-1">Pelanggan</th>
                                    <th class="px-4 py-3 text-muted fw-semibold border-0 small text-uppercase ls-1">Metode</th>
                                    <th class="px-4 py-3 text-muted fw-semibold text-end border-0 small text-uppercase ls-1">Nominal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($revenueList as $payment)
                                    <tr>
                                        <td class="px-4 py-3 border-bottom-0">
                                            <div class="d-flex flex-column">
                                                <span class="fw-bold" style="color: var(--text-main);">{{ $payment->paid_at ? \Carbon\Carbon::parse($payment->paid_at)->format('d M Y') : '-' }}</span>
                                                <span class="text-muted small">{{ $payment->paid_at ? \Carbon\Carbon::parse($payment->paid_at)->format('H:i') : '-' }}</span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 border-bottom-0">
                                            <span class="font-monospace text-muted small bg-light px-2 py-1 rounded">{{ $payment->id }}</span>
                                        </td>
                                        <td class="px-4 py-3 border-bottom-0">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle me-2 bg-secondary bg-opacity-10 text-secondary fw-bold small d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; rounded-circle">
                                                    {{ substr($payment->rental->customer->name ?? 'U', 0, 1) }}
                                                </div>
                                                <span style="color: var(--text-main);">{{ $payment->rental->customer->name ?? '-' }}</span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-muted border-bottom-0">
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-10">
                                                {{ ucfirst($payment->payment_type ?? 'Manual') }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 fw-bold text-end border-bottom-0" style="color: var(--text-main);">
                                            + Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="bi bi-wallet2 fs-1 mb-2 opacity-50"></i>
                                                <p class="mb-0">Belum ada data pembayaran</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="px-4 py-3 border-top border-secondary border-opacity-10">
                        {{ $revenueList->links() }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Fine Payments -->
        @if(isset($fineList) && count($fineList) > 0)
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-bold mb-1" style="color: var(--text-main);">Riwayat Pembayaran Denda</h5>
                        <p class="text-muted small mb-0">Denda kerusakan yang telah diselesaikan</p>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-4 py-3 text-muted fw-semibold border-0 small text-uppercase ls-1">Tanggal</th>
                                    <th class="px-4 py-3 text-muted fw-semibold border-0 small text-uppercase ls-1">ID Rental</th>
                                    <th class="px-4 py-3 text-muted fw-semibold border-0 small text-uppercase ls-1">Pelanggan</th>
                                    <th class="px-4 py-3 text-muted fw-semibold border-0 small text-uppercase ls-1">Item Rusak</th>
                                    <th class="px-4 py-3 text-muted fw-semibold text-end border-0 small text-uppercase ls-1">Nominal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($fineList as $rental)
                                    @php
                                        $damagedItems = $rental->items->where('condition', 'rusak');
                                        $itemNames = $damagedItems->map(function($item) {
                                            return $item->rentable->nama ?? $item->rentable->judul ?? $item->rentable->name ?? 'Unknown';
                                        })->implode(', ');
                                        if(empty($itemNames)) {
                                            $itemNames = $rental->items->map(function($item) {
                                                return $item->rentable->nama ?? $item->rentable->judul ?? $item->rentable->name ?? 'Unknown';
                                            })->first() ?? 'Unknown';
                                        }
                                    @endphp
                                    <tr>
                                        <td class="px-4 py-3 border-bottom-0">
                                            <div class="d-flex flex-column">
                                                <span class="fw-bold" style="color: var(--text-main);">{{ $rental->updated_at ? $rental->updated_at->format('d M Y') : '-' }}</span>
                                                <span class="text-muted small">{{ $rental->updated_at ? $rental->updated_at->format('H:i') : '-' }}</span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 border-bottom-0">
                                            <span class="font-monospace text-muted small bg-light px-2 py-1 rounded">#{{ $rental->id }}</span>
                                        </td>
                                        <td class="px-4 py-3 border-bottom-0">
                                            <span style="color: var(--text-main);">{{ $rental->customer->name ?? 'Unknown' }}</span>
                                        </td>
                                        <td class="px-4 py-3 text-muted border-bottom-0">
                                            <span class="text-truncate d-inline-block" style="max-width: 200px;" title="{{ $itemNames }}">
                                                {{ $itemNames }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 fw-bold text-end border-bottom-0" style="color: var(--text-main);">
                                            + Rp {{ number_format($rental->fine ?? 0, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <style>
        .ls-1 {
            letter-spacing: 1px;
        }
        .form-control {
            border-color: var(--card-border);
            background-color: var(--bg-light);
            color: var(--text-main);
        }
        .form-control:focus {
            border-color: #64748b;
            box-shadow: 0 0 0 0.25rem rgba(100, 116, 139, 0.25);
            color: var(--text-main);
        }
        .avatar-circle {
            border-radius: 50%;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Initialize the chart
        document.addEventListener('DOMContentLoaded', function() {
            // Date Filter Validation
            const dariInput = document.querySelector('#filterCollapse input[name="dari"]');
            const sampaiInput = document.querySelector('#filterCollapse input[name="sampai"]');

            if (dariInput && sampaiInput) {
                // Set initial constraints
                if (dariInput.value) sampaiInput.min = dariInput.value;
                if (sampaiInput.value) dariInput.max = sampaiInput.value;

                // Update constraints on change
                dariInput.addEventListener('change', function() {
                    sampaiInput.min = this.value;
                    if (sampaiInput.value && sampaiInput.value < this.value) {
                        sampaiInput.value = this.value;
                    }
                });

                sampaiInput.addEventListener('change', function() {
                    dariInput.max = this.value;
                    if (dariInput.value && dariInput.value > this.value) {
                        dariInput.value = this.value;
                    }
                });
            }

            // Get theme colors
            const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
            const textColor = isDark ? '#94a3b8' : '#64748b';
            const gridColor = isDark ? 'rgba(148, 163, 184, 0.1)' : 'rgba(0, 0, 0, 0.05)';
            const mainColor = isDark ? '#e2e8f0' : '#1e293b';
            const secondaryColor = isDark ? '#64748b' : '#94a3b8';

            Chart.defaults.color = textColor;
            Chart.defaults.borderColor = gridColor;

            const revCtx = document.getElementById('revChart');
            if (revCtx) {
                new Chart(revCtx, {
                    type: 'line',
                    data: {
                        labels: @json($revLabels ?? []),
                        datasets: [{
                            label: 'Pendapatan Rental',
                            data: @json($revData ?? []),
                            borderColor: mainColor,
                            backgroundColor: 'rgba(148, 163, 184, 0.1)',
                            borderWidth: 2,
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: mainColor,
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6
                        }, {
                            label: 'Pendapatan Denda',
                            data: @json($fineRevData ?? []),
                            borderColor: secondaryColor,
                            backgroundColor: 'rgba(148, 163, 184, 0.05)',
                            borderWidth: 2,
                            borderDash: [5, 5],
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: secondaryColor,
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { 
                                display: true, 
                                position: 'top',
                                labels: {
                                    usePointStyle: true,
                                    boxWidth: 8
                                }
                            },
                            tooltip: {
                                backgroundColor: isDark ? '#1e293b' : '#ffffff',
                                titleColor: isDark ? '#fff' : '#000',
                                bodyColor: isDark ? '#cbd5e1' : '#64748b',
                                borderColor: isDark ? '#334155' : '#e2e8f0',
                                borderWidth: 1,
                                padding: 12,
                                cornerRadius: 8,
                                callbacks: {
                                    label: function(context) {
                                        return 'Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed.y);
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: { borderDash: [4, 4] },
                                ticks: {
                                    callback: function(value) {
                                        return 'Rp ' + new Intl.NumberFormat('id-ID', { notation: "compact" }).format(value);
                                    }
                                }
                            },
                            x: {
                                grid: { display: false }
                            }
                        }
                    }
                });
            }
        });
    </script>
@endsection

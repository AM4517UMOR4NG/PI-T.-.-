@extends('pemilik.layout')
@section('title','Status Unit & Produk')
@section('owner_content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1" style="color: var(--text-main);">Status Unit & Produk</h2>
            <p class="text-muted mb-0">Monitoring ketersediaan aset rental secara real-time.</p>
        </div>
    </div>

    <!-- Health Summary Cards - Row 1 -->
    <div class="row g-4 mb-4">
        <!-- Overall Health Card -->
        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <h5 class="fw-bold mb-0" style="color: var(--text-main);"><i class="bi bi-heart-pulse me-2 text-danger"></i>Kesehatan Keseluruhan</h5>
                    <p class="text-muted small mb-0">Ringkasan kondisi semua aset rental</p>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-5 text-center mb-3 mb-md-0">
                            <div class="position-relative d-inline-block">
                                <svg width="150" height="150" viewBox="0 0 150 150">
                                    <circle cx="75" cy="75" r="60" fill="none" stroke="var(--card-border)" stroke-width="12"/>
                                    <circle cx="75" cy="75" r="60" fill="none" 
                                            stroke="{{ $overallHealth >= 80 ? '#22c55e' : ($overallHealth >= 50 ? '#eab308' : '#ef4444') }}" 
                                            stroke-width="12" 
                                            stroke-dasharray="{{ $overallHealth * 3.77 }} 377"
                                            stroke-linecap="round"
                                            transform="rotate(-90 75 75)"/>
                                </svg>
                                <div class="position-absolute top-50 start-50 translate-middle text-center">
                                    <span class="display-5 fw-bold" style="color: {{ $overallHealth >= 80 ? '#22c55e' : ($overallHealth >= 50 ? '#eab308' : '#ef4444') }};">{{ $overallHealth }}%</span>
                                    <small class="d-block text-muted">Sehat</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-muted small">Total Aset</span>
                                    <span class="fw-bold" style="color: var(--text-main);">{{ $totalItems }} item</span>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-muted small">Kondisi Baik</span>
                                    <span class="fw-bold text-success">{{ $totalItems - $totalDamaged }} item</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted small">Rusak/Perlu Perbaikan</span>
                                    <span class="fw-bold text-danger">{{ $totalDamaged }} item</span>
                                </div>
                            </div>
                            <div class="alert {{ $overallHealth >= 80 ? 'alert-success' : ($overallHealth >= 50 ? 'alert-warning' : 'alert-danger') }} py-2 px-3 mb-0 small">
                                <i class="bi {{ $overallHealth >= 80 ? 'bi-check-circle' : ($overallHealth >= 50 ? 'bi-exclamation-triangle' : 'bi-x-circle') }} me-1"></i>
                                @if($overallHealth >= 80)
                                    Kondisi aset sangat baik!
                                @elseif($overallHealth >= 50)
                                    Beberapa aset perlu perhatian
                                @else
                                    Banyak aset perlu perbaikan segera
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Health by Category Card -->
        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <h5 class="fw-bold mb-0" style="color: var(--text-main);"><i class="bi bi-bar-chart me-2 text-primary"></i>Kesehatan per Kategori</h5>
                    <p class="text-muted small mb-0">Status kondisi berdasarkan jenis produk</p>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="text-muted fw-semibold border-0">Kategori</th>
                                    <th class="text-muted fw-semibold text-center border-0">Total</th>
                                    <th class="text-muted fw-semibold text-center border-0">Tersedia</th>
                                    <th class="text-muted fw-semibold text-center border-0">Disewa</th>
                                    <th class="text-muted fw-semibold text-center border-0">Rusak</th>
                                    <th class="text-muted fw-semibold text-center border-0">Kesehatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="border-0">
                                        <i class="bi bi-controller text-primary me-2"></i>
                                        <span style="color: var(--text-main);">Unit PS</span>
                                    </td>
                                    <td class="text-center border-0 fw-bold" style="color: var(--text-main);">{{ $stats['units']['total'] }}</td>
                                    <td class="text-center border-0"><span class="badge bg-success-subtle text-success">{{ $stats['units']['available'] }}</span></td>
                                    <td class="text-center border-0"><span class="badge bg-warning-subtle text-warning">{{ $stats['units']['rented'] }}</span></td>
                                    <td class="text-center border-0"><span class="badge bg-danger-subtle text-danger">{{ $stats['units']['damaged'] }}</span></td>
                                    <td class="text-center border-0">
                                        <div class="progress" style="height: 8px; width: 60px; margin: 0 auto;">
                                            <div class="progress-bar {{ $stats['units']['health'] >= 80 ? 'bg-success' : ($stats['units']['health'] >= 50 ? 'bg-warning' : 'bg-danger') }}" 
                                                 style="width: {{ $stats['units']['health'] }}%"></div>
                                        </div>
                                        <small class="{{ $stats['units']['health'] >= 80 ? 'text-success' : ($stats['units']['health'] >= 50 ? 'text-warning' : 'text-danger') }}">{{ $stats['units']['health'] }}%</small>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="border-0">
                                        <i class="bi bi-disc text-info me-2"></i>
                                        <span style="color: var(--text-main);">Games</span>
                                    </td>
                                    <td class="text-center border-0 fw-bold" style="color: var(--text-main);">{{ $stats['games']['total'] }}</td>
                                    <td class="text-center border-0"><span class="badge bg-success-subtle text-success">{{ $stats['games']['available'] }}</span></td>
                                    <td class="text-center border-0"><span class="badge bg-warning-subtle text-warning">{{ $stats['games']['rented'] }}</span></td>
                                    <td class="text-center border-0"><span class="badge bg-danger-subtle text-danger">{{ $stats['games']['damaged'] }}</span></td>
                                    <td class="text-center border-0">
                                        <div class="progress" style="height: 8px; width: 60px; margin: 0 auto;">
                                            <div class="progress-bar {{ $stats['games']['health'] >= 80 ? 'bg-success' : ($stats['games']['health'] >= 50 ? 'bg-warning' : 'bg-danger') }}" 
                                                 style="width: {{ $stats['games']['health'] }}%"></div>
                                        </div>
                                        <small class="{{ $stats['games']['health'] >= 80 ? 'text-success' : ($stats['games']['health'] >= 50 ? 'text-warning' : 'text-danger') }}">{{ $stats['games']['health'] }}%</small>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="border-0">
                                        <i class="bi bi-headset text-purple me-2"></i>
                                        <span style="color: var(--text-main);">Aksesoris</span>
                                    </td>
                                    <td class="text-center border-0 fw-bold" style="color: var(--text-main);">{{ $stats['accessories']['total'] }}</td>
                                    <td class="text-center border-0"><span class="badge bg-success-subtle text-success">{{ $stats['accessories']['available'] }}</span></td>
                                    <td class="text-center border-0"><span class="badge bg-warning-subtle text-warning">{{ $stats['accessories']['rented'] }}</span></td>
                                    <td class="text-center border-0"><span class="badge bg-danger-subtle text-danger">{{ $stats['accessories']['damaged'] }}</span></td>
                                    <td class="text-center border-0">
                                        <div class="progress" style="height: 8px; width: 60px; margin: 0 auto;">
                                            <div class="progress-bar {{ $stats['accessories']['health'] >= 80 ? 'bg-success' : ($stats['accessories']['health'] >= 50 ? 'bg-warning' : 'bg-danger') }}" 
                                                 style="width: {{ $stats['accessories']['health'] }}%"></div>
                                        </div>
                                        <small class="{{ $stats['accessories']['health'] >= 80 ? 'text-success' : ($stats['accessories']['health'] >= 50 ? 'text-warning' : 'text-danger') }}">{{ $stats['accessories']['health'] }}%</small>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Tables - Row 2 (Unit PS & Games) -->
    <div class="row g-4 mb-4">
        <!-- Unit PS Section -->
        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0" style="color: var(--text-main);"><i class="bi bi-controller me-2 text-primary"></i>Unit PlayStation</h5>
                    <span class="badge bg-primary bg-opacity-10 text-primary">{{ count($unitps) }} Total</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 350px;">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light bg-opacity-10 sticky-top backdrop-blur">
                                <tr>
                                    <th class="px-4 py-3 text-muted fw-semibold">Produk</th>
                                    <th class="px-4 py-3 text-muted fw-semibold">Nama Unit</th>
                                    <th class="px-4 py-3 text-muted fw-semibold">Model</th>
                                    <th class="px-4 py-3 text-muted fw-semibold text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($unitps as $unit)
                                    @php $isRented = isset($rentedUnits[$unit->id]) && $rentedUnits[$unit->id] > 0; @endphp
                                    <tr>
                                        <td class="px-4 py-3">
                                            @if($unit->foto)
                                                <img src="{{ str_starts_with($unit->foto, 'http') ? $unit->foto : asset('storage/' . $unit->foto) }}" 
                                                     class="rounded shadow-sm" style="width: 40px; height: 40px; object-fit: cover;">
                                            @else
                                                <div class="bg-secondary bg-opacity-25 rounded d-flex align-items-center justify-content-center text-muted" style="width: 40px; height: 40px;">
                                                    <i class="bi bi-controller"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 fw-medium" style="color: var(--text-main);">{{ $unit->name }}</td>
                                        <td class="px-4 py-3 text-muted">{{ $unit->model }}</td>
                                        <td class="px-4 py-3 text-center">
                                            @if($isRented)
                                                <span class="badge bg-warning-subtle text-warning rounded-pill px-3">Disewa</span>
                                            @else
                                                <span class="badge bg-success-subtle text-success rounded-pill px-3">Tersedia</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center py-3 text-muted">Tidak ada data unit</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Games Section -->
        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0" style="color: var(--text-main);"><i class="bi bi-disc me-2 text-info"></i>Games</h5>
                    <span class="badge bg-primary bg-opacity-10 text-primary">{{ count($games) }} Total</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 350px;">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light bg-opacity-10 sticky-top backdrop-blur">
                                <tr>
                                    <th class="px-4 py-3 text-muted fw-semibold">Produk</th>
                                    <th class="px-4 py-3 text-muted fw-semibold">Judul Game</th>
                                    <th class="px-4 py-3 text-muted fw-semibold text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($games as $game)
                                    @php $isRented = isset($rentedGames[$game->id]) && $rentedGames[$game->id] > 0; @endphp
                                    <tr>
                                        <td class="px-4 py-3">
                                            @if($game->gambar)
                                                <img src="{{ str_starts_with($game->gambar, 'http') ? $game->gambar : asset('storage/' . $game->gambar) }}" 
                                                     class="rounded shadow-sm" style="width: 40px; height: 40px; object-fit: cover;">
                                            @else
                                                <div class="bg-secondary bg-opacity-25 rounded d-flex align-items-center justify-content-center text-muted" style="width: 40px; height: 40px;">
                                                    <i class="bi bi-disc"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3" style="color: var(--text-main);">{{ $game->judul }}</td>
                                        <td class="px-4 py-3 text-center">
                                            @if($isRented)
                                                <span class="badge bg-warning-subtle text-warning rounded-pill">Disewa</span>
                                            @else
                                                <span class="badge bg-success-subtle text-success rounded-pill">Tersedia</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="text-center py-3 text-muted">Tidak ada data game</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Tables - Row 3 (Accessories) -->
    <div class="row g-4">
        <!-- Accessories Section -->
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0" style="color: var(--text-main);"><i class="bi bi-headset me-2 text-purple"></i>Aksesoris</h5>
                    <span class="badge bg-primary bg-opacity-10 text-primary">{{ count($accessories) }} Total</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light bg-opacity-10">
                                <tr>
                                    <th class="px-4 py-3 text-muted fw-semibold">Produk</th>
                                    <th class="px-4 py-3 text-muted fw-semibold">Nama Aksesoris</th>
                                    <th class="px-4 py-3 text-muted fw-semibold">Jenis</th>
                                    <th class="px-4 py-3 text-muted fw-semibold text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($accessories as $acc)
                                    @php $isRented = isset($rentedAccessories[$acc->id]) && $rentedAccessories[$acc->id] > 0; @endphp
                                    <tr>
                                        <td class="px-4 py-3">
                                            @if($acc->gambar)
                                                <img src="{{ str_starts_with($acc->gambar, 'http') ? $acc->gambar : asset('storage/' . $acc->gambar) }}" 
                                                     class="rounded shadow-sm" style="width: 40px; height: 40px; object-fit: cover;">
                                            @else
                                                <div class="bg-secondary bg-opacity-25 rounded d-flex align-items-center justify-content-center text-muted" style="width: 40px; height: 40px;">
                                                    <i class="bi bi-headset"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3" style="color: var(--text-main);">{{ $acc->nama }}</td>
                                        <td class="px-4 py-3 text-muted">{{ $acc->jenis ?? '-' }}</td>
                                        <td class="px-4 py-3 text-center">
                                            @if($isRented)
                                                <span class="badge bg-warning-subtle text-warning rounded-pill">Disewa</span>
                                            @else
                                                <span class="badge bg-success-subtle text-success rounded-pill">Tersedia</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center py-3 text-muted">Tidak ada data aksesoris</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .backdrop-blur {
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            background: var(--card-bg) !important;
        }
        .text-purple {
            color: #8b5cf6 !important;
        }
        .progress {
            background-color: var(--card-border);
        }
    </style>
@endsection

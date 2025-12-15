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

    .nav-pills-custom .nav-link {
        color: var(--text-muted);
        background: transparent;
        border-radius: 30px;
        padding: 0.5rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .nav-pills-custom .nav-link.active {
        background: var(--primary);
        color: white;
        box-shadow: 0 4px 15px rgba(6, 82, 221, 0.3);
    }

    .table-custom th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        background: rgba(0,0,0,0.02);
    }
    
    [data-theme="dark"] .table-custom th {
        background: rgba(255,255,255,0.02);
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
    <!-- Welcome Section -->
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h2 class="fw-bold display-6 mb-1">Dashboard</h2>
            <p class="text-muted mb-0">Selamat datang kembali, {{ Auth::user()->name }}! Berikut adalah ringkasan harian Anda.</p>
        </div>
        <div class="d-flex gap-2">
            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                <i class="bi bi-calendar-event me-2"></i>{{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y') }}
            </span>
        </div>
    </div>

    <!-- User Statistics -->
    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="glass-card p-4 h-100 position-relative overflow-hidden">
                <div class="d-flex justify-content-between align-items-start position-relative z-1">
                    <div>
                        <p class="text-muted small text-uppercase fw-bold mb-1">Pelanggan</p>
                        <h3 class="fw-bold mb-0 display-6">{{ $userStats['pelanggan'] }}</h3>
                    </div>
                    <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                        <i class="bi bi-people"></i>
                    </div>
                </div>
                <div class="position-absolute bottom-0 end-0 opacity-10" style="transform: translate(30%, 30%);">
                    <i class="bi bi-people" style="font-size: 8rem; color: var(--primary);"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="glass-card p-4 h-100 position-relative overflow-hidden">
                <div class="d-flex justify-content-between align-items-start position-relative z-1">
                    <div>
                        <p class="text-muted small text-uppercase fw-bold mb-1">Kasir</p>
                        <h3 class="fw-bold mb-0 display-6">{{ $userStats['kasir'] }}</h3>
                    </div>
                    <div class="stat-icon bg-info bg-opacity-10 text-info">
                        <i class="bi bi-shop"></i>
                    </div>
                </div>
                <div class="position-absolute bottom-0 end-0 opacity-10" style="transform: translate(30%, 30%);">
                    <i class="bi bi-shop" style="font-size: 8rem; color: var(--secondary);"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="glass-card p-4 h-100 position-relative overflow-hidden">
                <div class="d-flex justify-content-between align-items-start position-relative z-1">
                    <div>
                        <p class="text-muted small text-uppercase fw-bold mb-1">Pemilik</p>
                        <h3 class="fw-bold mb-0 display-6">{{ $userStats['pemilik'] }}</h3>
                    </div>
                    <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                        <i class="bi bi-person-badge"></i>
                    </div>
                </div>
                <div class="position-absolute bottom-0 end-0 opacity-10" style="transform: translate(30%, 30%);">
                    <i class="bi bi-person-badge" style="font-size: 8rem; color: var(--warning);"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="glass-card p-4 h-100 position-relative overflow-hidden">
                <div class="d-flex justify-content-between align-items-start position-relative z-1">
                    <div>
                        <p class="text-muted small text-uppercase fw-bold mb-1">Admin</p>
                        <h3 class="fw-bold mb-0 display-6">{{ $userStats['admin'] }}</h3>
                    </div>
                    <div class="stat-icon bg-secondary bg-opacity-10 text-secondary">
                        <i class="bi bi-shield-lock"></i>
                    </div>
                </div>
                <div class="position-absolute bottom-0 end-0 opacity-10" style="transform: translate(30%, 30%);">
                    <i class="bi bi-shield-lock" style="font-size: 8rem; color: gray;"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Inventory Overview -->
    <div class="row g-4 mb-5">
        @foreach ($stats ?? [] as $row)
            <div class="col-md-4">
                <div class="glass-card p-4 h-100">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold mb-0">{{ $row['name'] }}</h5>
                        <span class="badge bg-light text-dark border">{{ $row['total'] }} Total</span>
                    </div>
                    
                    <!-- Progress Bar -->
                    <div class="progress mb-4" style="height: 8px; border-radius: 4px; background-color: rgba(0,0,0,0.05);">
                        @php
                            $percent = $row['total'] > 0 ? ($row['available'] / $row['total']) * 100 : 0;
                        @endphp
                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $percent }}%; border-radius: 4px;"></div>
                    </div>

                    <div class="row text-center g-0">
                        <div class="col border-end border-light">
                            <div class="text-success fw-bold small text-uppercase mb-1">Tersedia</div>
                            <div class="fw-bold fs-4">{{ $row['available'] }}</div>
                        </div>
                        <div class="col border-end border-light">
                            <div class="text-primary fw-bold small text-uppercase mb-1">Disewa</div>
                            <div class="fw-bold fs-4">{{ $row['rented'] }}</div>
                        </div>
                        <div class="col">
                            <div class="text-danger fw-bold small text-uppercase mb-1">Rusak</div>
                            <div class="fw-bold fs-4">{{ $row['damaged'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Main Content Grid -->
    <div class="row g-4">
        <!-- Left Column: Transactions & Reports -->
        <div class="col-lg-8">
            <!-- Recent Transactions -->
            <div class="glass-card mb-4">
                <div class="card-header bg-transparent border-bottom border-light py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-clock-history me-2 text-primary"></i>Transaksi Terkini</h5>
                    <a href="{{ route('admin.laporan.index') }}" class="btn btn-sm btn-light rounded-pill px-3">Lihat Semua</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-custom table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">ID</th>
                                <th>Pelanggan</th>
                                <th>Item</th>
                                <th>Status</th>
                                <th>Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentRentals as $rental)
                                <tr>
                                    <td class="ps-4"><span class="font-monospace text-muted">#{{ $rental->id }}</span></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle bg-primary bg-opacity-10 text-primary me-3">
                                                {{ substr($rental->customer->name ?? 'U', 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $rental->customer->name ?? 'Tidak Diketahui' }}</div>
                                                <div class="small text-muted">{{ $rental->customer->email ?? '-' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @foreach($rental->items as $item)
                                            <div class="small mb-1">
                                                <span class="fw-bold text-primary">{{ $item->quantity }}x</span>
                                                {{ $item->rentable->nama ?? $item->rentable->judul ?? $item->rentable->name ?? 'Barang' }}
                                            </div>
                                        @endforeach
                                    </td>
                                    <td>
                                        @if($rental->status == 'menunggu_konfirmasi')
                                            <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill">Menunggu</span>
                                        @elseif($rental->status == 'sedang_disewa')
                                            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">Disewa</span>
                                        @elseif($rental->status == 'selesai')
                                            <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">Selesai</span>
                                        @elseif($rental->status == 'cancelled')
                                            <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill">Batal</span>
                                        @else
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 rounded-pill">{{ $rental->status }}</span>
                                        @endif
                                    </td>
                                    <td class="text-muted small">{{ $rental->created_at->locale('id')->diffForHumans() }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">Belum ada transaksi</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pending Damage Reports (Conditional) -->
            @if(count($pendingDamageReports ?? []) > 0)
            <div class="glass-card border-warning border-2">
                <div class="card-header bg-transparent border-bottom border-light py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-warning"><i class="bi bi-exclamation-triangle me-2"></i>Laporan Menunggu Review</h5>
                    <a href="{{ route('admin.damage-reports.index') }}" class="btn btn-sm btn-warning text-white rounded-pill px-3">Review Semua</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-custom table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">Item</th>
                                <th>Pelapor</th>
                                <th>Keterangan</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendingDamageReports as $report)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold">{{ $report->rentalItem->rentable->nama ?? 'Barang' }}</div>
                                        <span class="badge bg-secondary small">{{ class_basename($report->rentalItem->rentable_type) }}</span>
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $report->reporter->name ?? 'Tidak Diketahui' }}</div>
                                        <div class="small text-muted">{{ $report->created_at->format('d M H:i') }}</div>
                                    </td>
                                    <td class="small text-muted">{{ Str::limit($report->description, 50) }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.damage-reports.show', $report) }}" class="btn btn-sm btn-primary rounded-pill">Review</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>

        <!-- Right Column: Quick Stats & Inventory Details -->
        <div class="col-lg-4">
            <!-- Damage Summary -->
            <div class="glass-card mb-4">
                <div class="card-header bg-transparent border-bottom border-light py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-tools me-2 text-danger"></i>Status Kerusakan</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3 p-3 rounded-3 bg-danger bg-opacity-10">
                        <div class="d-flex align-items-center">
                            <div class="stat-icon bg-white text-danger me-3 rounded-circle" style="width: 40px; height: 40px; font-size: 1.2rem;">
                                <i class="bi bi-controller"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold">Unit PS Rusak</h6>
                                <small class="text-muted">Perlu perbaikan</small>
                            </div>
                        </div>
                        <h4 class="mb-0 fw-bold text-danger">{{ $damagedByType['unitps'] ?? 0 }}</h4>
                    </div>
                    
                    <div class="d-flex align-items-center justify-content-between mb-3 p-3 rounded-3 bg-warning bg-opacity-10">
                        <div class="d-flex align-items-center">
                            <div class="stat-icon bg-white text-warning me-3 rounded-circle" style="width: 40px; height: 40px; font-size: 1.2rem;">
                                <i class="bi bi-disc"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold">Game Rusak</h6>
                                <small class="text-muted">Tidak bisa dimainkan</small>
                            </div>
                        </div>
                        <h4 class="mb-0 fw-bold text-warning">{{ $damagedByType['games'] ?? 0 }}</h4>
                    </div>

                    <div class="d-flex align-items-center justify-content-between p-3 rounded-3 bg-info bg-opacity-10">
                        <div class="d-flex align-items-center">
                            <div class="stat-icon bg-white text-info me-3 rounded-circle" style="width: 40px; height: 40px; font-size: 1.2rem;">
                                <i class="bi bi-headset"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold">Aksesoris Rusak</h6>
                                <small class="text-muted">Perlu penggantian</small>
                            </div>
                        </div>
                        <h4 class="mb-0 fw-bold text-info">{{ $damagedByType['accessories'] ?? 0 }}</h4>
                    </div>
                </div>
            </div>

            <!-- Inventory Tabs -->
            <div class="glass-card">
                <div class="card-header bg-transparent border-bottom border-light py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-box-seam me-2 text-primary"></i>Detail Inventaris</h5>
                </div>
                <div class="card-body p-0">
                    <ul class="nav nav-pills nav-pills-custom justify-content-center py-3" id="inventoryTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="ps-tab" data-bs-toggle="pill" data-bs-target="#pills-ps" type="button" role="tab">PS</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="games-tab" data-bs-toggle="pill" data-bs-target="#pills-games" type="button" role="tab">Games</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="acc-tab" data-bs-toggle="pill" data-bs-target="#pills-acc" type="button" role="tab">Aksesoris</button>
                        </li>
                    </ul>
                    
                    <div class="tab-content" id="inventoryTabContent">
                        <!-- PS Tab -->
                        <div class="tab-pane fade show active" id="pills-ps" role="tabpanel">
                            <div class="table-responsive" style="max-height: 400px;">
                                <table class="table table-custom table-hover align-middle mb-0">
                                    <thead class="sticky-top bg-white">
                                        <tr>
                                            <th class="ps-4">Nama</th>
                                            <th class="text-center">Stok</th>
                                            <th class="text-center">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($unitps as $unit)
                                            <tr>
                                                <td class="ps-4">
                                                    <div class="fw-bold">{{ $unit['nama'] }}</div>
                                                    <small class="text-muted">{{ $unit['model'] }}</small>
                                                </td>
                                                <td class="text-center fw-bold">{{ $unit['stok'] }}</td>
                                                <td class="text-center">
                                                    @if($unit['disewa'] > 0)
                                                        <span class="badge bg-primary bg-opacity-10 text-primary">{{ $unit['disewa'] }} Sewa</span>
                                                    @else
                                                        <span class="badge bg-success bg-opacity-10 text-success">Tersedia</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="3" class="text-center py-3">Tidak ada data</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="p-3 text-center border-top border-light">
                                <a href="{{ route('admin.unitps.index') }}" class="btn btn-sm btn-outline-primary rounded-pill">Kelola Unit PS</a>
                            </div>
                        </div>

                        <!-- Games Tab -->
                        <div class="tab-pane fade" id="pills-games" role="tabpanel">
                            <div class="table-responsive" style="max-height: 400px;">
                                <table class="table table-custom table-hover align-middle mb-0">
                                    <thead class="sticky-top bg-white">
                                        <tr>
                                            <th class="ps-4">Judul</th>
                                            <th class="text-center">Stok</th>
                                            <th class="text-center">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($games as $game)
                                            <tr>
                                                <td class="ps-4">
                                                    <div class="fw-bold text-truncate" style="max-width: 150px;">{{ $game['judul'] }}</div>
                                                    <small class="badge bg-light border">{{ $game['platform'] }}</small>
                                                </td>
                                                <td class="text-center fw-bold">{{ $game['stok'] }}</td>
                                                <td class="text-center">
                                                    @if($game['disewa'] > 0)
                                                        <span class="badge bg-primary bg-opacity-10 text-primary">{{ $game['disewa'] }} Sewa</span>
                                                    @else
                                                        <span class="badge bg-success bg-opacity-10 text-success">Tersedia</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="3" class="text-center py-3">Tidak ada data</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="p-3 text-center border-top border-light">
                                <a href="{{ route('admin.games.index') }}" class="btn btn-sm btn-outline-primary rounded-pill">Kelola Games</a>
                            </div>
                        </div>

                        <!-- Accessories Tab -->
                        <div class="tab-pane fade" id="pills-acc" role="tabpanel">
                            <div class="table-responsive" style="max-height: 400px;">
                                <table class="table table-custom table-hover align-middle mb-0">
                                    <thead class="sticky-top bg-white">
                                        <tr>
                                            <th class="ps-4">Nama</th>
                                            <th class="text-center">Stok</th>
                                            <th class="text-center">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($accessories as $acc)
                                            <tr>
                                                <td class="ps-4">
                                                    <div class="fw-bold">{{ $acc['nama'] }}</div>
                                                    <small class="text-muted">{{ $acc['jenis'] }}</small>
                                                </td>
                                                <td class="text-center fw-bold">{{ $acc['stok'] }}</td>
                                                <td class="text-center">
                                                    @if($acc['disewa'] > 0)
                                                        <span class="badge bg-primary bg-opacity-10 text-primary">{{ $acc['disewa'] }} Sewa</span>
                                                    @else
                                                        <span class="badge bg-success bg-opacity-10 text-success">Tersedia</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="3" class="text-center py-3">Tidak ada data</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="p-3 text-center border-top border-light">
                                <a href="{{ route('admin.accessories.index') }}" class="btn btn-sm btn-outline-primary rounded-pill">Kelola Aksesoris</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

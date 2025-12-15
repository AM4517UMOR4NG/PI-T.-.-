@extends('pemilik.layout')
@section('title','Beranda Pemilik')
@section('owner_content')
    @if(session('impersonate_admin_id'))
        <form action="{{ route('admin.impersonate.leave') }}" method="POST" class="mb-3">
            @csrf
            <button type="submit" class="btn btn-warning btn-sm shadow-sm"><i class="bi bi-arrow-return-left me-1"></i> Kembali ke Admin</button>
        </form>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1" style="color: var(--text-main);">Dashboard Pemilik</h2>
            <p class="text-muted mb-0">Ringkasan aktivitas dan performa bisnis Anda.</p>
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="row g-4 mb-4">
        <!-- Unit Tersedia -->
        <div class="col-12 col-md-4">
             <div class="card h-100 border-0 shadow-sm hover-scale">
                <div class="card-body d-flex align-items-center p-4">
                    <div class="d-flex align-items-center justify-content-center rounded-4 me-3" style="width: 60px; height: 60px; background-color: rgba(59, 130, 246, 0.1); color: #3b82f6;">
                        <i class="bi bi-controller fs-3"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-0 small text-uppercase fw-bold ls-1">Unit PS</p>
                        <h3 class="fw-bold mb-0" style="color: var(--text-main);">{{ $availableUnits ?? 0 }}</h3>
                        <a href="{{ route('pemilik.status_produk') }}" class="text-decoration-none small mt-1 d-inline-block" style="color: #3b82f6;">Lihat Detail <i class="bi bi-arrow-right ms-1"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Games Tersedia -->
        <div class="col-12 col-md-4">
            <div class="card h-100 border-0 shadow-sm hover-scale">
                <div class="card-body d-flex align-items-center p-4">
                    <div class="d-flex align-items-center justify-content-center rounded-4 me-3" style="width: 60px; height: 60px; background-color: rgba(249, 115, 22, 0.1); color: #f97316;">
                        <i class="bi bi-disc fs-3"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-0 small text-uppercase fw-bold ls-1">Games</p>
                        <h3 class="fw-bold mb-0" style="color: var(--text-main);">{{ $availableGames ?? 0 }}</h3>
                        <a href="{{ route('pemilik.status_produk') }}" class="text-decoration-none small mt-1 d-inline-block" style="color: #f97316;">Lihat Detail <i class="bi bi-arrow-right ms-1"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Aksesoris Tersedia -->
        <div class="col-12 col-md-4">
             <div class="card h-100 border-0 shadow-sm hover-scale">
                <div class="card-body d-flex align-items-center p-4">
                    <div class="d-flex align-items-center justify-content-center rounded-4 me-3" style="width: 60px; height: 60px; background-color: rgba(6, 182, 212, 0.1); color: #06b6d4;">
                        <i class="bi bi-headset fs-3"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-0 small text-uppercase fw-bold ls-1">Aksesoris</p>
                        <h3 class="fw-bold mb-0" style="color: var(--text-main);">{{ $availableAccessories ?? 0 }}</h3>
                        <a href="{{ route('pemilik.status_produk') }}" class="text-decoration-none small mt-1 d-inline-block" style="color: #06b6d4;">Lihat Detail <i class="bi bi-arrow-right ms-1"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transaksi Hari Ini -->
        <div class="col-12 col-md-6">
             <div class="card h-100 border-0 shadow-sm hover-scale">
                <div class="card-body d-flex align-items-center p-4">
                    <div class="d-flex align-items-center justify-content-center rounded-4 me-3" style="width: 60px; height: 60px; background-color: rgba(139, 92, 246, 0.1); color: #8b5cf6;">
                        <i class="bi bi-receipt fs-3"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-0 small text-uppercase fw-bold ls-1">Transaksi Hari Ini</p>
                        <h3 class="fw-bold mb-0" style="color: var(--text-main);">{{ $todaysTransactions ?? 0 }}</h3>
                        <a href="{{ route('pemilik.laporan_transaksi') }}" class="text-decoration-none small mt-1 d-inline-block" style="color: #8b5cf6;">Lihat Laporan <i class="bi bi-arrow-right ms-1"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pendapatan 7 Hari -->
        <div class="col-12 col-md-6">
             <div class="card h-100 border-0 shadow-sm hover-scale">
                <div class="card-body d-flex align-items-center p-4">
                    <div class="d-flex align-items-center justify-content-center rounded-4 me-3" style="width: 60px; height: 60px; background-color: rgba(16, 185, 129, 0.1); color: #10b981;">
                        <i class="bi bi-cash-stack fs-3"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-0 small text-uppercase fw-bold ls-1">Pendapatan 7 Hari</p>
                        <h3 class="fw-bold mb-0" style="color: var(--text-main);">Rp {{ number_format($revTotal7 ?? 0, 0, ',', '.') }}</h3>
                        <a href="{{ route('pemilik.laporan_pendapatan') }}" class="text-decoration-none small mt-1 d-inline-block" style="color: #10b981;">Analisis <i class="bi bi-arrow-right ms-1"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Transaksi Terbaru (Brief List) -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
            <div>
                <h5 class="fw-bold mb-1" style="color: var(--text-main);">Aktivitas Terbaru</h5>
                <p class="text-muted small mb-0">5 transaksi terakhir yang masuk</p>
            </div>
            <a href="{{ route('pemilik.laporan_transaksi') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">Lihat Semua</a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4 py-3 text-muted fw-semibold border-0">Pelanggan</th>
                            <th class="px-4 py-3 text-muted fw-semibold border-0">Item</th>
                            <th class="px-4 py-3 text-muted fw-semibold border-0">Total</th>
                            <th class="px-4 py-3 text-muted fw-semibold border-0">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(($recentTransactions ?? []) as $t)
                            <tr>
                                <td class="px-4 py-3 border-bottom-0">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                            <span class="fw-bold">{{ substr($t->customer->name ?? $t->nama_pelanggan ?? 'U', 0, 1) }}</span>
                                        </div>
                                        <div>
                                            <span class="fw-bold d-block" style="color: var(--text-main);">{{ $t->customer->name ?? $t->nama_pelanggan ?? '-' }}</span>
                                            <small class="text-muted">{{ $t->created_at ? $t->created_at->diffForHumans() : '-' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-muted border-bottom-0">
                                    @if($t->items && $t->items->count() > 0)
                                        @foreach($t->items as $item)
                                            <div class="d-flex align-items-center mb-1">
                                                @if($item->rentable_type === 'App\\Models\\UnitPS')
                                                    <i class="bi bi-controller me-2 text-primary"></i>
                                                    <span>{{ $item->rentable->nama ?? $item->rentable->name ?? 'Unit' }}</span>
                                                @elseif($item->rentable_type === 'App\\Models\\Game')
                                                    <i class="bi bi-disc me-2 text-warning"></i>
                                                    <span>{{ $item->rentable->judul ?? $item->rentable->title ?? 'Game' }}</span>
                                                @elseif($item->rentable_type === 'App\\Models\\Accessory')
                                                    <i class="bi bi-headset me-2 text-info"></i>
                                                    <span>{{ $item->rentable->nama ?? $item->rentable->name ?? 'Aksesoris' }}</span>
                                                @endif
                                            </div>
                                        @endforeach
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-4 py-3 fw-bold border-bottom-0" style="color: var(--text-main);">
                                    Rp {{ number_format($t->total ?? $t->biaya ?? 0, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 border-bottom-0">
                                    @php $st = $t->status ?? 'selesai'; @endphp
                                    <span class="badge rounded-pill {{ $st==='selesai' || $st==='paid' ? 'bg-success-subtle text-success' : ($st==='active' || $st==='ongoing' ? 'bg-primary-subtle text-primary' : ($st==='pending' ? 'bg-warning-subtle text-warning' : 'bg-secondary-subtle text-secondary')) }} px-3 py-2">
                                        {{
                                            match($st) {
                                                'active', 'ongoing' => 'Sedang Disewa',
                                                'paid' => 'Lunas',
                                                'pending' => 'Menunggu',
                                                'selesai' => 'Selesai',
                                                'cancelled' => 'Dibatalkan',
                                                'returned' => 'Dikembalikan',
                                                default => ucfirst($st)
                                            }
                                        }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="bi bi-inbox fs-1 mb-2 opacity-50"></i>
                                        <p class="mb-0">Belum ada transaksi terbaru</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <style>
        .hover-scale {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .hover-scale:hover {
            transform: translateY(-3px);
            box-shadow: 0 .5rem 1rem rgba(0,0,0,.08) !important;
        }
        .ls-1 {
            letter-spacing: 1px;
        }
    </style>
@endsection

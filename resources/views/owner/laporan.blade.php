@extends('pemilik.layout')
@section('title','Laporan Transaksi')
@section('owner_content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1" style="color: var(--text-main);">Laporan Transaksi</h2>
            <p class="text-muted mb-0">Riwayat lengkap penyewaan dan status transaksi.</p>
        </div>
        <div class="d-flex gap-2">
            <form action="{{ route('pemilik.laporan.export') }}" method="GET" class="d-flex gap-2">
                <input type="hidden" name="format" value="xlsx">
                <input type="hidden" name="dari" value="{{ request('dari') }}">
                <input type="hidden" name="sampai" value="{{ request('sampai') }}">
                <button type="submit" class="btn btn-success btn-sm shadow-sm"><i class="bi bi-file-earmark-excel me-2"></i>Export Excel</button>
            </form>
        </div>
    </div>

    <!-- Filters Card -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <form action="{{ route('pemilik.laporan_transaksi') }}" method="GET">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label text-muted small fw-bold text-uppercase ls-1">Dari Tanggal</label>
                        <input type="date" name="dari" class="form-control" value="{{ request('dari') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-muted small fw-bold text-uppercase ls-1">Sampai Tanggal</label>
                        <input type="date" name="sampai" class="form-control" value="{{ request('sampai') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-muted small fw-bold text-uppercase ls-1">Status Transaksi</label>
                        <select name="status" class="form-select">
                            <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>Semua Status</option>
                            <option value="menunggu_konfirmasi" {{ request('status') == 'menunggu_konfirmasi' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                            <option value="sedang_disewa" {{ request('status') == 'sedang_disewa' ? 'selected' : '' }}>Sedang Disewa</option>
                            <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100 fw-bold"><i class="bi bi-filter me-2"></i>Terapkan Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4 py-3 text-muted fw-semibold border-0">ID Transaksi</th>
                            <th class="px-4 py-3 text-muted fw-semibold border-0">Waktu & Tanggal</th>
                            <th class="px-4 py-3 text-muted fw-semibold border-0">Pelanggan</th>
                            <th class="px-4 py-3 text-muted fw-semibold border-0">Detail Item</th>
                            <th class="px-4 py-3 text-muted fw-semibold border-0">Total Biaya</th>
                            <th class="px-4 py-3 text-muted fw-semibold border-0">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rentals as $rental)
                            <tr>
                                <td class="px-4 py-3 border-bottom-0">
                                    <span class="font-monospace fw-bold text-primary">#{{ $rental->kode ?? $rental->id }}</span>
                                </td>
                                <td class="px-4 py-3 border-bottom-0">
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold" style="color: var(--text-main);">{{ $rental->created_at->format('d M Y') }}</span>
                                        <small class="text-muted">{{ $rental->created_at->format('H:i') }} WIB</small>
                                    </div>
                                </td>
                                <td class="px-4 py-3 border-bottom-0">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 35px; height: 35px;">
                                            <span class="fw-bold small">{{ substr($rental->customer->name ?? 'U', 0, 1) }}</span>
                                        </div>
                                        <span class="fw-medium" style="color: var(--text-main);">{{ $rental->customer->name ?? '-' }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-muted small border-bottom-0">
                                    @foreach($rental->items as $item)
                                        <div class="mb-1 d-flex align-items-center">
                                            @if($item->rentable_type === 'App\\Models\\UnitPS')
                                                <i class="bi bi-controller me-2 text-primary"></i>
                                            @elseif($item->rentable_type === 'App\\Models\\Game')
                                                <i class="bi bi-disc me-2 text-warning"></i>
                                            @elseif($item->rentable_type === 'App\\Models\\Accessory')
                                                <i class="bi bi-headset me-2 text-info"></i>
                                            @endif
                                            <span>{{ $item->rentable->nama ?? $item->rentable->judul ?? 'Item' }}</span>
                                        </div>
                                    @endforeach
                                    <div class="mt-1 text-muted small">
                                        <i class="bi bi-clock me-1"></i> Durasi: {{ $rental->durasi ?? ($rental->start_at && $rental->due_at ? $rental->start_at->diffInHours($rental->due_at) : '-') }} Jam
                                    </div>
                                </td>
                                <td class="px-4 py-3 fw-bold border-bottom-0" style="color: var(--text-main);">
                                    Rp {{ number_format($rental->total, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 border-bottom-0">
                                    @php $st = $rental->status; @endphp
                                    <span class="badge rounded-pill {{ $st==='selesai' || $st==='paid' ? 'bg-success-subtle text-success' : ($st==='active' || $st==='sedang_disewa' ? 'bg-primary-subtle text-primary' : ($st==='menunggu_konfirmasi' ? 'bg-warning-subtle text-warning' : ($st==='cancelled' ? 'bg-danger text-white' : 'bg-secondary-subtle text-secondary'))) }} px-3 py-2">
                                        {{
                                            match($st) {
                                                'active', 'sedang_disewa' => 'Sedang Disewa',
                                                'paid' => 'Lunas',
                                                'pending' => 'Menunggu',
                                                'selesai' => 'Selesai',
                                                'cancelled' => 'Dibatalkan',
                                                'returned' => 'Dikembalikan',
                                                'menunggu_konfirmasi' => 'Menunggu Konfirmasi',
                                                default => ucfirst(str_replace('_', ' ', $st))
                                            }
                                        }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="bi bi-clipboard-x fs-1 mb-2 opacity-50"></i>
                                        <p class="mb-0">Tidak ada data transaksi yang ditemukan</p>
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
        .ls-1 {
            letter-spacing: 1px;
        }
        .form-control, .form-select {
            border-color: var(--card-border);
            background-color: var(--bg-light);
            color: var(--text-main);
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.25);
        }
    </style>
@endsection

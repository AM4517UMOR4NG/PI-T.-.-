@extends('kasir.layout')

@section('title', 'Manajemen Pengantaran')

@section('kasir_content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">
            <i class="bi bi-truck me-2"></i>Manajemen Pengantaran
        </h4>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Menunggu Diantar</h6>
                            <h2 class="mb-0">{{ $stats['pending_delivery'] }}</h2>
                        </div>
                        <i class="bi bi-box-seam fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-warning text-dark">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Menunggu Konfirmasi</h6>
                            <h2 class="mb-0">{{ $stats['awaiting_confirmation'] }}</h2>
                        </div>
                        <i class="bi bi-hourglass-split fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Diantar Hari Ini</h6>
                            <h2 class="mb-0">{{ $stats['delivered_today'] }}</h2>
                        </div>
                        <i class="bi bi-truck fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Dikonfirmasi User</h6>
                            <h2 class="mb-0">{{ $stats['confirmed_today'] }}</h2>
                        </div>
                        <i class="bi bi-check-circle fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Tab Navigation -->
    <ul class="nav nav-tabs mb-4" id="deliveryTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button" role="tab">
                <i class="bi bi-box-seam me-1"></i>Menunggu Diantar
                @if($stats['pending_delivery'] > 0)
                    <span class="badge bg-primary">{{ $stats['pending_delivery'] }}</span>
                @endif
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="awaiting-tab" data-bs-toggle="tab" data-bs-target="#awaiting" type="button" role="tab">
                <i class="bi bi-hourglass-split me-1"></i>Menunggu Konfirmasi User
                @if($stats['awaiting_confirmation'] > 0)
                    <span class="badge bg-warning text-dark">{{ $stats['awaiting_confirmation'] }}</span>
                @endif
            </button>
        </li>
    </ul>

    <div class="tab-content" id="deliveryTabsContent">
        <!-- Tab: Menunggu Diantar -->
        <div class="tab-pane fade show active" id="pending" role="tabpanel">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="bi bi-box-seam me-2"></i>Rental Menunggu Pengantaran</h5>
                    <small class="text-muted">Rental yang sudah dibayar dan menunggu untuk diantarkan ke pelanggan</small>
                </div>
                <div class="card-body p-0">
                    @if($pendingDeliveries->isEmpty())
                        <div class="text-center py-5">
                            <i class="bi bi-inbox fs-1 text-muted"></i>
                            <p class="text-muted mt-2">Tidak ada rental yang menunggu pengantaran</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Kode</th>
                                        <th>Pelanggan</th>
                                        <th>Item</th>
                                        <th>Total</th>
                                        <th>Alamat</th>
                                        <th>Tgl Pesan</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendingDeliveries as $rental)
                                        <tr>
                                            <td>
                                                <span class="badge bg-secondary">{{ $rental->kode }}</span>
                                            </td>
                                            <td>
                                                <strong>{{ $rental->customer->name ?? 'N/A' }}</strong>
                                                <br>
                                                <small class="text-muted">
                                                    <i class="bi bi-telephone me-1"></i>{{ $rental->customer->phone ?? '-' }}
                                                </small>
                                            </td>
                                            <td>
                                                @foreach($rental->items as $item)
                                                    <span class="badge bg-light text-dark me-1">
                                                        {{ $item->rentable->nama ?? $item->rentable->judul ?? $item->rentable->name ?? 'Item' }}
                                                        ({{ $item->quantity }}x)
                                                    </span>
                                                @endforeach
                                            </td>
                                            <td>
                                                <strong>Rp {{ number_format($rental->total, 0, ',', '.') }}</strong>
                                            </td>
                                            <td>
                                                <small>{{ Str::limit($rental->customer->address ?? '-', 50) }}</small>
                                            </td>
                                            <td>
                                                {{ $rental->created_at->format('d M Y') }}
                                                <br>
                                                <small class="text-muted">{{ $rental->created_at->format('H:i') }}</small>
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm btn-success" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#confirmDeliveryModal{{ $rental->id }}">
                                                    <i class="bi bi-truck me-1"></i>Konfirmasi Antar
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- Modal Konfirmasi Pengantaran -->
                                        <div class="modal fade" id="confirmDeliveryModal{{ $rental->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="{{ route('kasir.deliveries.confirm', $rental) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">
                                                                <i class="bi bi-truck me-2"></i>Konfirmasi Pengantaran
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Konfirmasi bahwa barang untuk rental <strong>{{ $rental->kode }}</strong> sudah diantarkan ke:</p>
                                                            
                                                            <div class="alert alert-info">
                                                                <strong>{{ $rental->customer->name ?? 'N/A' }}</strong><br>
                                                                <i class="bi bi-geo-alt me-1"></i>{{ $rental->customer->address ?? 'Alamat tidak tersedia' }}<br>
                                                                <i class="bi bi-telephone me-1"></i>{{ $rental->customer->phone ?? '-' }}
                                                            </div>

                                                            <div class="mb-3">
                                                                <label class="form-label">Catatan Pengantaran (Opsional)</label>
                                                                <textarea name="delivery_notes" class="form-control" rows="3" 
                                                                    placeholder="Contoh: Diterima oleh satpam, barang dititipkan di pos, dll."></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-success">
                                                                <i class="bi bi-check-circle me-1"></i>Konfirmasi Sudah Diantar
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer bg-white">
                            {{ $pendingDeliveries->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Tab: Menunggu Konfirmasi User -->
        <div class="tab-pane fade" id="awaiting" role="tabpanel">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="bi bi-hourglass-split me-2"></i>Menunggu Konfirmasi Pelanggan</h5>
                    <small class="text-muted">Barang sudah diantarkan, menunggu pelanggan mengkonfirmasi penerimaan</small>
                </div>
                <div class="card-body p-0">
                    @if($awaitingConfirmation->isEmpty())
                        <div class="text-center py-5">
                            <i class="bi bi-inbox fs-1 text-muted"></i>
                            <p class="text-muted mt-2">Tidak ada yang menunggu konfirmasi pelanggan</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Kode</th>
                                        <th>Pelanggan</th>
                                        <th>Item</th>
                                        <th>Diantar Pada</th>
                                        <th>Diantar Oleh</th>
                                        <th>Catatan</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($awaitingConfirmation as $rental)
                                        <tr>
                                            <td>
                                                <span class="badge bg-warning text-dark">{{ $rental->kode }}</span>
                                            </td>
                                            <td>
                                                <strong>{{ $rental->customer->name ?? 'N/A' }}</strong>
                                                <br>
                                                <small class="text-muted">
                                                    <i class="bi bi-telephone me-1"></i>{{ $rental->customer->phone ?? '-' }}
                                                </small>
                                            </td>
                                            <td>
                                                @foreach($rental->items as $item)
                                                    <span class="badge bg-light text-dark me-1">
                                                        {{ $item->rentable->nama ?? $item->rentable->judul ?? $item->rentable->name ?? 'Item' }}
                                                    </span>
                                                @endforeach
                                            </td>
                                            <td>
                                                {{ $rental->delivered_at->format('d M Y H:i') }}
                                                <br>
                                                <small class="text-muted">
                                                    {{ $rental->delivered_at->diffForHumans() }}
                                                </small>
                                            </td>
                                            <td>
                                                {{ $rental->deliverer->name ?? 'N/A' }}
                                            </td>
                                            <td>
                                                <small>{{ Str::limit($rental->delivery_notes ?? '-', 30) }}</small>
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm btn-outline-danger" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#cancelDeliveryModal{{ $rental->id }}">
                                                    <i class="bi bi-x-circle me-1"></i>Batalkan
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- Modal Batalkan Pengantaran -->
                                        <div class="modal fade" id="cancelDeliveryModal{{ $rental->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="{{ route('kasir.deliveries.cancel', $rental) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-header">
                                                            <h5 class="modal-title text-danger">
                                                                <i class="bi bi-x-circle me-2"></i>Batalkan Status Pengantaran
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Anda akan membatalkan status pengantaran untuk rental <strong>{{ $rental->kode }}</strong>.</p>
                                                            <p class="text-muted">Status akan kembali ke "Menunggu Diantar".</p>
                                                            
                                                            <div class="mb-3">
                                                                <label class="form-label">Alasan Pembatalan <span class="text-danger">*</span></label>
                                                                <textarea name="cancel_reason" class="form-control" rows="3" required
                                                                    placeholder="Contoh: Alamat salah, pelanggan tidak ada di tempat, dll."></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                            <button type="submit" class="btn btn-danger">
                                                                <i class="bi bi-x-circle me-1"></i>Batalkan Pengantaran
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer bg-white">
                            {{ $awaitingConfirmation->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

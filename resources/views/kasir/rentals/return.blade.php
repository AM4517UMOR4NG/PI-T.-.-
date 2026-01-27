@extends('kasir.layout')

@section('kasir_content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-clipboard-check me-2 text-primary"></i>Proses Pengembalian</h5>
                    <a href="{{ route('kasir.rentals.show', $rental) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                        <i class="bi bi-arrow-left me-1"></i>Kembali
                    </a>
                </div>
                <div class="card-body">
                    <!-- Customer Info -->
                    <div class="mb-4 p-3 bg-light rounded">
                        <div class="row">
                            <div class="col-md-4">
                                <small class="text-muted d-block">Pelanggan</small>
                                <span class="fw-bold">{{ $rental->customer->name }}</span>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted d-block">ID Rental</small>
                                <span class="font-monospace fw-bold">#{{ $rental->kode ?? $rental->id }}</span>
                            </div>
                            <div class="col-md-4 text-md-end">
                                <small class="text-muted d-block">Status</small>
                                @if($rental->status === 'menunggu_konfirmasi')
                                    <span class="badge bg-warning text-dark">Menunggu Konfirmasi</span>
                                @else
                                    <span class="badge bg-primary">{{ ucfirst(str_replace('_', ' ', $rental->status)) }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Late Fee Info -->
                    @php
                        $lateFeeData = \App\Services\LateFeeService::calculateLateFee($rental);
                        $hasUserDamageReport = $rental->items->where('condition', 'rusak')->count() > 0;
                    @endphp
                    
                    @if($lateFeeData['is_late'])
                        <div class="alert alert-danger d-flex align-items-start mb-4">
                            <i class="bi bi-clock-fill me-2 mt-1"></i>
                            <div>
                                <strong>Terlambat {{ $lateFeeData['hours_late'] }} jam!</strong><br>
                                <span>{{ $lateFeeData['description'] }}</span><br>
                                <span class="fw-bold">Denda Keterlambatan: Rp {{ number_format($lateFeeData['late_fee'], 0, ',', '.') }}</span>
                            </div>
                        </div>
                    @endif

                    <!-- Info Alert for User-submitted conditions -->
                    @if($hasUserDamageReport)
                        <div class="alert alert-warning d-flex align-items-start mb-4">
                            <i class="bi bi-exclamation-triangle-fill me-2 mt-1"></i>
                            <div>
                                <strong>Perhatian!</strong> Pelanggan melaporkan ada barang dalam kondisi RUSAK. 
                                Silakan verifikasi kondisi barang dan tentukan denda yang sesuai.
                            </div>
                        </div>
                    @endif
                    
                    <!-- Late Fee Rules Info -->
                    <div class="alert alert-info d-flex align-items-start mb-4">
                        <i class="bi bi-info-circle-fill me-2 mt-1"></i>
                        <div>
                            <strong>Aturan Denda Keterlambatan:</strong>
                            <ul class="mb-0 mt-1 small">
                                <li>1-3 jam: 20% dari harga sewa</li>
                                <li>3-8 jam: 40% dari harga sewa</li>
                                <li>8-12 jam: 60% dari harga sewa</li>
                                <li>> 12 jam: 100% (bayar dua kali lipat)</li>
                            </ul>
                        </div>
                    </div>

                    <form action="{{ route('kasir.rentals.confirm-return', $rental) }}" method="POST">
                        @csrf
                        
                        <h6 class="fw-bold mb-3"><i class="bi bi-box-seam me-2"></i>Daftar Item</h6>
                        
                        @foreach($rental->items as $index => $item)
                            @php
                                $userReportedCondition = $item->condition ?? 'baik';
                                $userFineDescription = $item->fine_description ?? '';
                            @endphp
                            <div class="card mb-3 {{ $userReportedCondition === 'rusak' ? 'border-danger' : 'border-0' }} bg-light">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div>
                                            <h6 class="mb-1 fw-bold">
                                                @if($item->rentable_type == 'App\Models\UnitPS')
                                                    <i class="bi bi-controller me-1 text-primary"></i>{{ $item->rentable->name ?? 'Unit PS' }}
                                                @elseif($item->rentable_type == 'App\Models\Game')
                                                    <i class="bi bi-disc me-1 text-info"></i>{{ $item->rentable->judul ?? 'Game' }}
                                                @elseif($item->rentable_type == 'App\Models\Accessory')
                                                    <i class="bi bi-headset me-1 text-secondary"></i>{{ $item->rentable->nama ?? 'Aksesoris' }}
                                                @endif
                                            </h6>
                                            <small class="text-muted">Qty: {{ $item->quantity }} | Tipe: {{ class_basename($item->rentable_type) }}</small>
                                        </div>
                                        @if($userReportedCondition === 'rusak')
                                            <span class="badge bg-danger"><i class="bi bi-exclamation-circle me-1"></i>Dilaporkan Rusak</span>
                                        @endif
                                    </div>

                                    <!-- User's damage report (if any) -->
                                    @if($userReportedCondition === 'rusak' && $userFineDescription)
                                        <div class="alert alert-danger py-2 mb-3">
                                            <small class="fw-bold d-block">Keterangan dari Pelanggan:</small>
                                            <small>{{ $userFineDescription }}</small>
                                        </div>
                                    @endif

                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label class="form-label small fw-bold">Kondisi (Verifikasi Kasir)</label>
                                            <select name="items[{{ $item->id }}][condition]" class="form-select condition-select" data-target="#fine-section-{{ $index }}">
                                                <option value="baik" {{ $userReportedCondition === 'baik' ? 'selected' : '' }}>✓ Baik</option>
                                                <option value="rusak" {{ $userReportedCondition === 'rusak' ? 'selected' : '' }}>✗ Rusak</option>
                                            </select>
                                        </div>
                                        
                                        <div class="col-md-8 fine-section" id="fine-section-{{ $index }}" style="{{ $userReportedCondition === 'rusak' ? 'display: block;' : 'display: none;' }}">
                                            <div class="row g-2">
                                                <div class="col-md-5">
                                                    <label class="form-label small fw-bold text-danger">Denda (Rp) *</label>
                                                    <input type="number" name="items[{{ $item->id }}][fine]" class="form-control border-danger" min="0" value="{{ $item->fine ?? 0 }}" placeholder="Masukkan nominal denda"
                                                        oninput="if(this.value < 0) this.value = 0;" 
                                                        onkeydown="return event.keyCode !== 69 && event.keyCode !== 189">
                                                    <small class="text-muted">Denda berdasarkan kerusakan yang dibuat</small>
                                                </div>
                                                <div class="col-md-7">
                                                    <label class="form-label small fw-bold">Keterangan Kerusakan</label>
                                                    <input type="text" name="items[{{ $item->id }}][fine_description]" class="form-control" value="{{ $userFineDescription ?: 'Denda berdasarkan kerusakan yang dibuat' }}" placeholder="Contoh: Stik patah, layar retak">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <!-- Summary -->
                        <div class="card bg-primary-subtle border-0 mt-4">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-6">
                                        <h6 class="mb-1 fw-bold">Total Tagihan Awal</h6>
                                        <h4 class="mb-0 text-primary">Rp {{ number_format($rental->total, 0, ',', '.') }}</h4>
                                    </div>
                                    <div class="col-md-6 text-md-end">
                                        <small class="text-muted d-block">Denda akan ditambahkan ke total tagihan</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('kasir.rentals.show', $rental) }}" class="btn btn-light">Batal</a>
                            <button type="submit" class="btn btn-success fw-bold">
                                <i class="bi bi-check-circle me-2"></i>Konfirmasi Pengembalian
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selects = document.querySelectorAll('.condition-select');
    selects.forEach(select => {
        // Initial state
        const targetId = select.getAttribute('data-target');
        const target = document.querySelector(targetId);
        if (select.value === 'rusak') {
            target.style.display = 'block';
        }
        
        select.addEventListener('change', function() {
            const targetId = this.getAttribute('data-target');
            const target = document.querySelector(targetId);
            if (this.value === 'rusak') {
                target.style.display = 'block';
            } else {
                target.style.display = 'none';
                // Reset inputs
                const fineInput = target.querySelector('input[type="number"]');
                if (fineInput) fineInput.value = 0;
            }
        });
    });
});
</script>
@endsection

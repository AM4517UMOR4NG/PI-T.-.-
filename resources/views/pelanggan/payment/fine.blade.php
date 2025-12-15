@extends('pelanggan.layout')

@section('pelanggan_content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-danger text-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-exclamation-triangle me-2"></i>Pembayaran Denda Kerusakan</h5>
                </div>
                <div class="card-body p-4">
                    <!-- Item Info -->
                    <div class="mb-4 p-3 rounded" style="background-color: var(--adaptive-bg-light, #f8fafc);">
                        <h6 class="fw-bold mb-3">Detail Kerusakan</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-1 text-muted small">Item Rusak</p>
                                <p class="fw-bold">{{ $damageReport->rentalItem->rentable->nama ?? $damageReport->rentalItem->rentable->judul ?? 'Unknown' }}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1 text-muted small">Kode Rental</p>
                                <p class="fw-bold">{{ $rental->kode ?? '#'.$rental->id }}</p>
                            </div>
                        </div>
                        <div class="mt-2">
                            <p class="mb-1 text-muted small">Keterangan Kerusakan</p>
                            <p class="mb-0">{{ $damageReport->description ?? '-' }}</p>
                        </div>
                        @if($damageReport->admin_feedback)
                        <div class="mt-2">
                            <p class="mb-1 text-muted small">Feedback Admin</p>
                            <p class="mb-0 text-danger">{{ $damageReport->admin_feedback }}</p>
                        </div>
                        @endif
                    </div>

                    <!-- Fine Amount -->
                    <div class="text-center mb-4">
                        <p class="text-muted mb-1">Jumlah Denda yang Harus Dibayar</p>
                        <h2 class="fw-bold text-danger">Rp {{ number_format($damageReport->fine_amount, 0, ',', '.') }}</h2>
                    </div>

                    <!-- Payment Button -->
                    <div class="text-center">
                        <button id="pay-button" class="btn btn-danger btn-lg px-5">
                            <i class="bi bi-credit-card me-2"></i>Bayar Sekarang
                        </button>
                        <p class="text-muted small mt-3">
                            <i class="bi bi-shield-check me-1"></i>Pembayaran aman melalui Midtrans
                        </p>
                    </div>

                    <hr class="my-4">

                    <div class="text-center">
                        <a href="{{ route('pelanggan.rentals.show', $rental) }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i>Kembali ke Detail Rental
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
    document.getElementById('pay-button').addEventListener('click', function() {
        snap.pay('{{ $snapToken }}', {
            onSuccess: function(result) {
                // Redirect to callback with success
                window.location.href = '{{ route("pelanggan.damage-reports.fine-callback", $damageReport) }}?transaction_status=settlement&payment_type=' + result.payment_type;
            },
            onPending: function(result) {
                alert('Pembayaran pending. Silakan selesaikan pembayaran Anda.');
            },
            onError: function(result) {
                alert('Pembayaran gagal. Silakan coba lagi.');
            },
            onClose: function() {
                console.log('Payment popup closed');
            }
        });
    });
</script>
@endsection

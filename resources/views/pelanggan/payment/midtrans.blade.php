@extends('pelanggan.layout')

@section('pelanggan_content')
<div class="container-fluid">
    <!-- Header -->
    <div class="card card-hover-lift mb-4 animate-fade-in">
        <div class="card-body">
            <div class="text-center">
                <h4 class="mb-1 fw-bold text-main"><i class="bi bi-credit-card-2-front me-2 text-primary"></i><span class="gradient-text">Pembayaran Penyewaan</span></h4>
                <p class="mb-0 small text-muted">Selesaikan pembayaran untuk melanjutkan penyewaan Anda</p>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-7">
            <!-- Order Info -->
            <div class="card card-hover-lift mb-4">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="small mb-1 text-muted">Order ID</div>
                            <div class="fw-bold text-main">{{ $orderId }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="small mb-1 text-muted">Kode Rental</div>
                            <div class="fw-bold text-main">{{ $rental->kode }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="small mb-1 text-muted">Status</div>
                            <div>
                                <span class="badge" style="background-color: #fef3c7; color: #92400e; border: 1px solid #f59e0b; font-weight: 600;">
                                    <i class="bi bi-clock-history me-1"></i> Menunggu Pembayaran
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rental Details -->
            <div class="card card-hover-lift mb-4">
                <div class="card-body">
                    <h5 class="mb-3 fw-bold text-main"><i class="bi bi-calendar-check me-2 text-primary"></i>Detail Penyewaan</h5>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="small mb-1 text-muted"><i class="bi bi-calendar-event me-1"></i> Tanggal Mulai</div>
                            <div class="text-main">{{ \Carbon\Carbon::parse($rental->start_at)->format('d M Y') }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="small mb-1 text-muted"><i class="bi bi-calendar-x me-1"></i> Tanggal Kembali</div>
                            <div class="text-main">{{ \Carbon\Carbon::parse($rental->due_at)->format('d M Y') }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="small mb-1 text-muted"><i class="bi bi-hourglass me-1"></i> Durasi</div>
                            <div class="text-main">{{ \Carbon\Carbon::parse($rental->start_at)->diffInDays(\Carbon\Carbon::parse($rental->due_at)) }} Hari</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="card card-hover-lift mb-4">
                <div class="card-body">
                    <h5 class="mb-3 fw-bold text-main"><i class="bi bi-receipt me-2 text-success"></i>Ringkasan Pesanan</h5>

                    @foreach($rental->items as $item)
                        @php
                            $itemName = 'Item';
                            if($item->rentable) {
                                $itemName = $item->rentable->nama ?? $item->rentable->judul ?? $item->rentable->name ?? 'Item';
                            }
                        @endphp
                        <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                            <div class="text-main">
                                {{ $itemName }}
                                <span class="small text-muted">(x{{ $item->quantity }})</span>
                            </div>
                            <div class="fw-bold text-main">Rp {{ number_format($item->total, 0, ',', '.') }}</div>
                        </div>
                    @endforeach

                    <div class="d-flex justify-content-between align-items-center pt-3 mt-2 border-top">
                        <div class="fs-5 fw-bold text-main">
                            <i class="bi bi-cash-stack me-1 text-success"></i> Total Pembayaran
                        </div>
                        <div class="fs-4 fw-bold text-success">Rp {{ number_format($rental->total, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>

            <!-- Payment Instructions -->
            <div class="card card-hover-lift mb-4">
                <div class="card-body">
                    <h5 class="mb-3 fw-bold text-main"><i class="bi bi-info-circle me-2 text-primary"></i>Instruksi Pembayaran</h5>

                    <ul class="small mb-0 text-muted">
                        <li class="mb-2">Klik tombol "Lanjutkan Pembayaran" di bawah ini</li>
                        <li class="mb-2">Pilih metode pembayaran yang Anda inginkan (Transfer Bank, E-Wallet, Kartu Kredit, dll)</li>
                        <li class="mb-2">Ikuti instruksi pembayaran yang muncul</li>
                        <li class="mb-2">Selesaikan pembayaran sebelum batas waktu yang ditentukan</li>
                        <li>Status penyewaan akan otomatis diperbarui setelah pembayaran berhasil</li>
                    </ul>
                </div>
            </div>

            <!-- Payment Button -->
            <button id="pay-button" class="btn btn-success btn-lg w-100 fw-bold mb-3">
                <i class="bi bi-credit-card me-2"></i> Lanjutkan Pembayaran
            </button>

            <!-- Back Button -->
            <div class="text-center mb-3">
                <a href="{{ route('pelanggan.rentals.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Penyewaan
                </a>
            </div>

            <!-- Security Badge -->
            <div class="card card-hover-lift">
                <div class="card-body text-center">
                    <i class="bi bi-shield-check-fill text-success" class="fs-4 me-2"></i>
                    <span class="small text-muted">Pembayaran aman dan terenkripsi dengan Midtrans</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ config('midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script type="text/javascript">
  const payButton = document.getElementById('pay-button');
  
  payButton.onclick = function () {
    // Disable button to prevent double click
    payButton.disabled = true;
    payButton.innerHTML = '<i class="bi bi-hourglass-split"></i> Memproses Pembayaran...';
    
    snap.pay('{{ $snapToken }}', {
      onSuccess: function(result){
        console.log('Payment success:', result);
        
        // Show verifying message
        if (window.showFlashMessage) {
          window.showFlashMessage('Pembayaran Berhasil!', 'Memverifikasi status pembayaran...', 'info');
        }
        
        // Manually check status to ensure DB is updated (fix for localhost webhook issues)
        fetch('{{ route("midtrans.status", $orderId) }}')
            .then(response => response.json())
            .then(data => {
                console.log('Verification result:', data);
                if (window.showFlashMessage) {
                    window.showFlashMessage('Terverifikasi!', 'Pembayaran telah dikonfirmasi.', 'success');
                }
                setTimeout(function() {
                    window.location.href = '{{ route("pelanggan.rentals.show", $rental) }}';
                }, 1000);
            })
            .catch(error => {
                console.error('Verification error:', error);
                // Redirect anyway, maybe webhook worked or user can check later
                setTimeout(function() {
                    window.location.href = '{{ route("pelanggan.rentals.show", $rental) }}';
                }, 2000);
            });
      },
      onPending: function(result){
        console.log('Payment pending:', result);
        
        // Show pending message
        if (window.showFlashMessage) {
          window.showFlashMessage('Pembayaran Sedang Diproses', 'Silakan cek status pembayaran Anda.', 'warning');
        }
        
        // Also check status for pending
        fetch('{{ route("midtrans.status", $orderId) }}')
            .then(() => {
                setTimeout(function() {
                    window.location.href = '{{ route("pelanggan.rentals.show", $rental) }}';
                }, 2000);
            });
      },
      onError: function(result){
        console.error('Payment error:', result);
        
        // Show error message
        if (window.showFlashMessage) {
          window.showFlashMessage('Pembayaran Gagal', 'Terjadi kesalahan. Silakan coba lagi.', 'danger');
        }
        
        setTimeout(function() {
          payButton.disabled = false;
          payButton.innerHTML = '<i class="bi bi-credit-card"></i> Lanjutkan Pembayaran';
        }, 2000);
      },
      onClose: function(){
        console.log('Payment popup closed');
        payButton.disabled = false;
        payButton.innerHTML = '<i class="bi bi-credit-card"></i> Lanjutkan Pembayaran';
      }
    });
  };
  
  // Auto-trigger payment popup after 1.5 seconds
  setTimeout(function() {
    payButton.click();
  }, 1500);
</script>
@endsection

<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; }
        .header { background: #10b981; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; }
        .footer { margin-top: 30px; font-size: 0.8rem; color: #888; text-align: center; }
        .btn { background: #10b981; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Pesanan Sedang Diantar! 🚚</h2>
    </div>
    
    <div class="content">
        <p>Halo, {{ $this->rental->customer->name }}!</p>
        <p>Kabar gembira! Pesanan rental Anda dengan kode <strong>{{ $this->rental->kode }}</strong> sudah diproses dan sedang dalam perjalanan ke alamat Anda.</p>
        
        <p><strong>Alamat Pengantaran:</strong><br>
        {{ $this->rental->delivery_address ?? $this->rental->customer->address }}</p>
        
        @if($this->rental->delivery_notes)
        <p><strong>Catatan Pengantaran:</strong><br>
        {{ $this->rental->delivery_notes }}</p>
        @endif
        
        <p>Mohon pastikan ada penerima di lokasi dan siapkan kartu identitas untuk verifikasi saat kurir tiba.</p>

        <p style="text-align: center; margin-top: 30px;">
            <a href="{{ route('pelanggan.rentals.show', $this->rental->id) }}" class="btn">Lihat Detail Pesanan</a>
        </p>
    </div>

    <div class="footer">
        &copy; {{ date('Y') }} PlayStation Rental. All rights reserved.
    </div>
</body>
</html>

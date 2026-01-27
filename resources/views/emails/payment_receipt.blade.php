<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; }
        .header { background: #0070d1; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .total { font-weight: bold; text-align: right; }
        .footer { margin-top: 30px; font-size: 0.8rem; color: #888; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Pembayaran Berhasil!</h2>
    </div>
    
    <div class="content">
        <p>Halo, {{ $rental->customer->name }}!</p>
        <p>Terima kasih telah melakukan pembayaran. Berikut adalah detail transaksi Anda:</p>
        
        <p><strong>Kode Pesanan:</strong> {{ $rental->kode }}</p>
        <p><strong>Tanggal:</strong> {{ $rental->created_at->format('d M Y H:i') }}</p>
        
        <table class="table">
            <thead>
                <tr>
                    <th>Barang</th>
                    <th>Jumlah</th>
                    <th>Harga</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rental->items as $item)
                @php
                    // Determine item name
                    $itemName = $item->rentable->nama ?? $item->rentable->name ?? $item->rentable->judul ?? 'Item Hapus';
                    
                    // Calculate duration
                    $start = \Carbon\Carbon::parse($rental->start_at);
                    $due = \Carbon\Carbon::parse($rental->due_at);
                    $durationHours = $start->diffInHours($due);
                    $durationDays = $start->diffInDays($due);
                    
                    $durationText = $durationHours > 24 ? "$durationDays Hari" : "$durationHours Jam";
                @endphp
                <tr>
                    <td>
                        {{ $itemName }} <br>
                        <small>({{ $durationText }})</small>
                    </td>
                    <td>{{ $item->quantity }}</td>
                    <td>Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                </tr>
                @endforeach
                <tr>
                    <td colspan="2" class="total">Total Bayar</td>
                    <td class="total">Rp {{ number_format($rental->paid, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        <p style="margin-top: 20px;">
            Status pesanan Anda saat ini: <strong>Menunggu Pengantaran</strong>.<br>
            Harap siapkan kartu identitas saat kurir datang.
        </p>

        <p style="text-align: center; margin-top: 30px;">
            <a href="{{ route('pelanggan.rentals.show', $rental->id) }}" style="background: #0070d1; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Lihat Pesanan</a>
        </p>
    </div>

    <div class="footer">
        &copy; {{ date('Y') }} PlayStation Rental. All rights reserved.
    </div>
</body>
</html>

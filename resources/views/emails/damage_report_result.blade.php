<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; color: #333; }
        .header { background: #10b981; color: white; padding: 20px; text-align: center; } /* Green for success/no fine */
        .content { padding: 20px; }
        .footer { margin-top: 30px; font-size: 0.8rem; color: #888; text-align: center; }
        .highlight { font-weight: bold; color: #10b981; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Kerusakan Selesai</h2>
    </div>
    
    <div class="content">
        <p>Halo, {{ $damageReport->reporter->name }}!</p>
        <p>Laporan kerusakan untuk item <strong>{{ $damageReport->rentalItem->rentable->nama ?? $damageReport->rentalItem->rentable->name ?? 'Item' }}</strong> (Rental #{{ $damageReport->rentalItem->rental_id }}) telah direview oleh tim kami.</p>
        
        <p>Berdasarkan hasil pemeriksaan, kami memutuskan bahwa:</p>
        <h3>Anda TIDAK dikenakan denda.</h3>
        
        <p>Terima kasih atas kejujuran Anda dalam melaporkan kondisi barang. Kami sangat menghargai pelanggan yang bertanggung jawab.</p>
        
        <p>Catatan dari Admin:<br>
        <em>"{{ $damageReport->admin_feedback }}"</em>
        </p>

        <p style="margin-top: 20px;">
            Status laporan ini telah ditutup. Anda dapat melanjutkan penyewaan berikutnya dengan tenang.
        </p>

        <p style="text-align: center; margin-top: 30px;">
            <a href="{{ route('pelanggan.rentals.show', $damageReport->rentalItem->rental_id) }}" style="background: #10b981; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Lihat Detail</a>
        </p>
    </div>

    <div class="footer">
        &copy; {{ date('Y') }} PlayStation Rental. All rights reserved.
    </div>
</body>
</html>

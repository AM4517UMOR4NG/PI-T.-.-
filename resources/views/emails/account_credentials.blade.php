<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; color: #333; }
        .header { background: #0d6efd; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; }
        .credentials-box { background: #f8f9fa; border: 1px solid #dee2e6; padding: 15px; border-radius: 5px; margin: 20px 0; }
        .footer { margin-top: 30px; font-size: 0.8rem; color: #888; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Selamat Datang di Tim!</h2>
    </div>
    
    <div class="content">
        <p>Halo, {{ $user->name }}!</p>
        <p>Akun <strong>{{ ucfirst($user->role) }}</strong> Anda telah berhasil dibuat. Berikut adalah detail kredensial login Anda:</p>
        
        <div class="credentials-box">
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Password:</strong> {{ $password }}</p>
        </div>
        
        <p>Silakan login dan segera ganti kata sandi Anda demi keamanan.</p>

        <p style="text-align: center; margin-top: 20px;">
            <a href="{{ route('login.show') }}" style="background: #0d6efd; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Login Sekarang</a>
        </p>
    </div>

    <div class="footer">
        &copy; {{ date('Y') }} PlayStation Rental. All rights reserved.
    </div>
</body>
</html>

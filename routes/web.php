<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Admin\PelangganController;
use App\Http\Controllers\Admin\UnitPSController;
use App\Http\Controllers\Admin\GameController;
use App\Http\Controllers\Admin\AccessoryController;
use App\Http\Controllers\Kasir\RentalController as KasirRentalController;
use App\Http\Controllers\Kasir\PaymentController as KasirPaymentController;
use App\Http\Controllers\Pelanggan\UnitPSController as PelangganUnitPSController;
use App\Http\Controllers\Pelanggan\GameController as PelangganGameController;
use App\Http\Controllers\Pelanggan\AccessoryController as PelangganAccessoryController;
use App\Http\Controllers\Pelanggan\ProfileController as PelangganProfileController;
use App\Http\Controllers\Pelanggan\CartController as PelangganCartController;
use App\Http\Controllers\Pelanggan\RentalController as PelangganRentalController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Owner\StatusProdukController;
use App\Http\Controllers\Owner\LaporanController;
use App\Http\Controllers\Kasir\TransaksiController;
use App\Http\Controllers\Admin\ImpersonateController;
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DamageReportController;

// Midtrans webhook (must be outside auth middleware)
Route::post('midtrans/notification', [MidtransController::class, 'notification'])->name('midtrans.notification');
Route::get('midtrans/status/{orderId}', [MidtransController::class, 'checkStatus'])->name('midtrans.status');

Route::get('/', function () {
    if (Auth::check()) {
        $role = Auth::user()->role;
        return match ($role) {
            'admin' => redirect()->route('dashboard.admin'),
            'kasir' => redirect()->route('dashboard.kasir'),
            'pemilik' => redirect()->route('dashboard.pemilik'),
            'pelanggan' => redirect()->route('dashboard.pelanggan'),
            default => redirect()->route('dashboard.pelanggan'),
        };
    }
    return view('landing');
});

// Public landing page (direct link)
// Public landing page (direct link)
Route::view('/landing', 'landing')->name('landing');

// Guest Pages
Route::view('/about', 'pages.about')->name('about');
Route::view('/terms', 'pages.terms')->name('terms');
Route::view('/privacy', 'pages.privacy')->name('privacy');
Route::view('/contact', 'pages.contact')->name('contact');

// Public Showcase Pages (for non-logged in users)
Route::get('showcase', [DashboardController::class, 'galleryShowcase'])->name('showcase.gallery');
Route::get('showcase/unitps', [DashboardController::class, 'unitpsLanding'])->name('showcase.unitps');
Route::get('showcase/games', [DashboardController::class, 'gameLanding'])->name('showcase.games');
Route::get('showcase/accessories', [DashboardController::class, 'accessoryLanding'])->name('showcase.accessories');

// Serve files from the public disk without requiring the /public/storage symlink
Route::get('/media/{path}', function (string $path) {
    if (!Storage::disk('public')->exists($path)) {
        abort(404);
    }
    return Storage::disk('public')->response($path);
})->where('path', '.*')->name('media');

Route::get('/register', [RegisterController::class, 'show'])->name('register.show');
Route::post('/register', [RegisterController::class, 'register'])->name('register.post');

Route::get('/login', [LoginController::class, 'show'])->name('login.show');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');

// Google OAuth
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google.redirect');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('auth.google.callback');

// Password Reset
Route::get('password/reset', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('password.update');

// Alias for default auth middleware redirect (expects route name 'login')
Route::get('/auth', function () {
    return redirect()->route('login.show');
})->name('login');

Route::get('lang/{locale}', function ($locale) {
    \Illuminate\Support\Facades\Log::info('Language Switcher: Requested ' . $locale);
    if (in_array($locale, ['en', 'id'])) {
        \Illuminate\Support\Facades\Session::put('locale', $locale);
        \Illuminate\Support\Facades\Session::save();
        \Illuminate\Support\Facades\Log::info('Language Switcher: Session set to ' . $locale);
    }
    return back();
})->name('lang.switch');

Route::middleware(['auth'])->group(function () {
    // Unified Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/avatar', [ProfileController::class, 'deleteAvatar'])->name('profile.avatar.delete');

    // Admin - Pelanggan
    Route::resource('admin/pelanggan', PelangganController::class)->parameters([
        'pelanggan' => 'pelanggan'
    ])->names('admin.pelanggan');

    // Admin - UnitPS
    Route::resource('admin/unitps', UnitPSController::class)->parameters([
        'unitps' => 'unitp'
    ])->names('admin.unitps');

    // Admin - Games
    Route::resource('admin/games', GameController::class)->names('admin.games');

    // Admin - Accessories
    Route::resource('admin/accessories', AccessoryController::class)->names('admin.accessories');

    // Admin - Staff management (fallback and specific routes)
    Route::get('admin/staff', [StaffController::class, 'index'])->name('admin.staff.index');
    Route::get('admin/staff/create', [StaffController::class, 'create'])->name('admin.staff.create');
    
    // Admin - Kelola Admin
    Route::get('admin/admin', [StaffController::class, 'adminIndex'])->name('admin.admin.index');
    Route::get('admin/admin/create', [StaffController::class, 'adminCreate'])->name('admin.admin.create');
    Route::post('admin/admin', [StaffController::class, 'store'])->name('admin.admin.store');
    Route::get('admin/admin/{user}/edit', [StaffController::class, 'edit'])->name('admin.admin.edit');
    Route::put('admin/admin/{user}', [StaffController::class, 'update'])->name('admin.admin.update');
    Route::delete('admin/admin/{user}', [StaffController::class, 'destroy'])->name('admin.admin.destroy');
    
    // Admin - Kelola Pemilik
    Route::get('admin/pemilik', [StaffController::class, 'pemilikIndex'])->name('admin.pemilik.index');
    Route::get('admin/pemilik/create', [StaffController::class, 'pemilikCreate'])->name('admin.pemilik.create');
    Route::post('admin/pemilik', [StaffController::class, 'store'])->name('admin.pemilik.store');
    Route::get('admin/pemilik/{user}/edit', [StaffController::class, 'edit'])->name('admin.pemilik.edit');
    Route::put('admin/pemilik/{user}', [StaffController::class, 'update'])->name('admin.pemilik.update');
    Route::delete('admin/pemilik/{user}', [StaffController::class, 'destroy'])->name('admin.pemilik.destroy');
    
    // Admin - Kelola Kasir
    Route::get('admin/kasir', [StaffController::class, 'kasirIndex'])->name('admin.kasir.index');
    Route::get('admin/kasir/create', [StaffController::class, 'kasirCreate'])->name('admin.kasir.create');
    Route::post('admin/kasir', [StaffController::class, 'store'])->name('admin.kasir.store');
    Route::get('admin/kasir/{user}/edit', [StaffController::class, 'edit'])->name('admin.kasir.edit');
    Route::put('admin/kasir/{user}', [StaffController::class, 'update'])->name('admin.kasir.update');
    Route::delete('admin/kasir/{user}', [StaffController::class, 'destroy'])->name('admin.kasir.destroy');

    Route::get('/dashboard/admin', [DashboardController::class, 'admin'])->name('dashboard.admin');
    Route::get('/dashboard/kasir', [DashboardController::class, 'kasir'])->name('dashboard.kasir');
    Route::get('/dashboard/pemilik', [DashboardController::class, 'pemilik'])->name('dashboard.pemilik');
    Route::get('/dashboard/pelanggan', [DashboardController::class, 'pelanggan'])->name('dashboard.pelanggan');
    Route::get('admin/laporan', [DashboardController::class, 'adminReport'])->name('admin.laporan.index');

    // Admin - Damage Reports
    Route::get('admin/damage-reports', [DamageReportController::class, 'index'])->name('admin.damage-reports.index');
    Route::get('admin/damage-reports/{damageReport}', [DamageReportController::class, 'show'])->name('admin.damage-reports.show');
    Route::post('admin/damage-reports/{damageReport}/review', [DamageReportController::class, 'review'])->name('admin.damage-reports.review');
    Route::post('admin/damage-reports/{damageReport}/resolve', [DamageReportController::class, 'resolve'])->name('admin.damage-reports.resolve');

    // Logout (POST)
    Route::post('/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    })->name('logout');
});

Route::middleware(['web', 'auth', 'can:access-pelanggan'])->prefix('pelanggan')->name('pelanggan.')->group(function () {
    // Pelanggan - Profile
    Route::get('profile', [PelangganProfileController::class, 'show'])->name('profile.show');
    Route::get('profile/edit', [PelangganProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [PelangganProfileController::class, 'update'])->name('profile.update');

    // Pelanggan - View Catalog
    Route::get('unitps', [PelangganUnitPSController::class, 'index'])->name('unitps.index');
    Route::get('games', [PelangganGameController::class, 'index'])->name('games.index');
    Route::get('accessories', [PelangganAccessoryController::class, 'index'])->name('accessories.index');

    // Pelanggan - Cart
    Route::get('cart', [PelangganCartController::class, 'index'])->name('cart.index');
    Route::post('cart/add', [PelangganCartController::class, 'add'])->name('cart.add');
    Route::post('cart/update', [PelangganCartController::class, 'update'])->name('cart.update');
    Route::post('cart/remove', [PelangganCartController::class, 'remove'])->name('cart.remove');
    Route::post('cart/clear', [PelangganCartController::class, 'clear'])->name('cart.clear');

    // Pelanggan - Rentals
    Route::get('rentals', [PelangganRentalController::class, 'index'])->name('rentals.index');
    Route::get('rentals/create', [PelangganRentalController::class, 'create'])->name('rentals.create');
    Route::post('rentals', [PelangganRentalController::class, 'store'])
        ->middleware(['throttle:3,1'])
        ->name('rentals.store');
    Route::get('rentals/{rental}', [PelangganRentalController::class, 'show'])->name('rentals.show');
    Route::get('rentals/{rental}/return', [PelangganRentalController::class, 'returnRental'])->name('rentals.return');
    Route::post('rentals/{rental}/process-return', [PelangganRentalController::class, 'processReturn'])->name('rentals.process-return');
    Route::get('rentals/{rental}/continue-payment', [PelangganRentalController::class, 'continuePayment'])->name('rentals.continue-payment');
    Route::post('rentals/{rental}/cancel', [PelangganRentalController::class, 'cancel'])->name('rentals.cancel');
    
    // Pelanggan - Damage Reports
    Route::post('damage-reports/{damageReport}/confirm', [PelangganRentalController::class, 'confirmDamageReport'])->name('damage-reports.confirm');
    Route::get('damage-reports/{damageReport}/pay-fine', [PelangganRentalController::class, 'payFine'])->name('damage-reports.pay-fine');
    Route::match(['get', 'post'], 'damage-reports/{damageReport}/fine-callback', [PelangganRentalController::class, 'finePaymentCallback'])->name('damage-reports.fine-callback');
});

Route::middleware(['web', 'auth', 'can:access-pemilik'])->prefix('pemilik')->name('pemilik.')->group(function () {
    Route::get('status-produk', [StatusProdukController::class, 'index'])->name('status_produk');
    Route::get('laporan-transaksi', [LaporanController::class, 'index'])->name('laporan_transaksi');
    Route::get('laporan-pendapatan', [LaporanController::class, 'pendapatan'])->name('laporan_pendapatan');
    Route::get('laporan/export', [LaporanController::class, 'export'])->name('laporan.export');
    Route::get('damage-reports', [LaporanController::class, 'damageReports'])->name('damage-reports');
});

Route::middleware(['web', 'auth', 'can:access-kasir'])->prefix('kasir')->name('kasir.')->group(function () {
    // Kasir Dashboard
    Route::get('dashboard', [\App\Http\Controllers\Kasir\KasirDashboardController::class, 'index'])->name('dashboard');
    
    // All Transactions
    Route::get('transactions', [\App\Http\Controllers\Kasir\KasirDashboardController::class, 'allTransactions'])->name('transactions');
    
    // Payments Management
    Route::get('payments', [\App\Http\Controllers\Kasir\KasirDashboardController::class, 'payments'])->name('payments');
    
    // Fine Payments
    Route::get('fines', [\App\Http\Controllers\Kasir\KasirDashboardController::class, 'finePayments'])->name('fines');
    Route::post('fines/{damageReport}/confirm', [\App\Http\Controllers\Kasir\KasirDashboardController::class, 'confirmFinePayment'])->name('confirm-fine');
    
    // Daily Report
    Route::get('daily-report', [\App\Http\Controllers\Kasir\KasirDashboardController::class, 'dailyReport'])->name('daily-report');
    
    // Confirm Return
    Route::post('confirm-return/{rental}', [\App\Http\Controllers\Kasir\KasirDashboardController::class, 'confirmReturn'])->name('confirm-return');

    // Legacy routes
    Route::get('transaksi', [TransaksiController::class, 'index'])->name('transaksi.index'); // form cari
    Route::get('transaksi/{rental}', [TransaksiController::class, 'show'])->name('transaksi.show'); // detail
    Route::post('transaksi/{rental}/pengembalian', [TransaksiController::class, 'pengembalian'])->name('transaksi.pengembalian'); // konfirmasi
    Route::post('transaksi/{rental}/aktifkan', [TransaksiController::class, 'aktifkan'])->name('transaksi.aktifkan'); // aktifkan sewa setelah dibayar
    
    // Kasir - Rentals Management
    Route::get('rentals', [KasirRentalController::class, 'index'])->name('rentals.index');
    Route::get('rentals/create', [KasirRentalController::class, 'create'])->name('rentals.create');
    Route::post('rentals', [KasirRentalController::class, 'store'])->name('rentals.store');
    Route::get('rentals/{rental}', [KasirRentalController::class, 'show'])->name('rentals.show');
    Route::get('rentals/{rental}/return', [KasirRentalController::class, 'returnForm'])->name('rentals.return');
    Route::post('rentals/{rental}/return', [KasirRentalController::class, 'confirmReturn']); // Handle POST to same URL
    Route::post('rentals/{rental}/process-return', [KasirRentalController::class, 'processReturn'])->name('rentals.process-return');
    Route::post('rentals/{rental}/confirm-return', [KasirRentalController::class, 'confirmReturn'])->name('rentals.confirm-return');
});

Route::middleware(['web', 'auth'])->group(function () {
    Route::get('admin/impersonate/{user}', [ImpersonateController::class, 'impersonate'])->name('admin.impersonate');
    Route::post('admin/impersonate/leave', [ImpersonateController::class, 'leaveImpersonate'])->name('admin.impersonate.leave');
});

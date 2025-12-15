# 📋 DOKUMENTASI LENGKAP SISTEM RENTAL PLAYSTATION

## 📌 Informasi Umum Proyek

| Aspek | Detail |
|-------|--------|
| **Nama Proyek** | PlayStation Rental System |
| **Framework** | Laravel 12.x |
| **PHP Version** | ^8.2 |
| **Database** | MySQL / SQLite |
| **Frontend** | Blade Templates, Bootstrap 5, Vite |
| **Payment Gateway** | Midtrans (Snap API) |
| **OAuth** | Google OAuth 2.0 (Laravel Socialite) |
| **Export** | Maatwebsite Excel |

---

## 🔧 Teknologi & Package yang Digunakan

### Backend Dependencies
| Package | Versi | Fungsi |
|---------|-------|--------|
| `laravel/framework` | ^12.0 | Core framework PHP |
| `laravel/socialite` | ^5.23 | OAuth authentication (Google Login) |
| `midtrans/midtrans-php` | ^2.6 | Payment gateway integration |
| `maatwebsite/excel` | ^3.1 | Export laporan ke Excel/CSV |
| `laravel/tinker` | ^2.10.1 | REPL untuk debugging |

### Frontend Stack
| Teknologi | Fungsi |
|-----------|--------|
| Blade Templates | Server-side templating |
| Bootstrap 5 | CSS Framework |
| Bootstrap Icons | Icon library |
| Vite | Asset bundling |
| JavaScript (Vanilla) | Interaktivitas UI |
| Chart.js | Grafik pendapatan |

---

## 👥 Role & Hak Akses

Sistem memiliki **4 role utama**:

| Role | Deskripsi |
|------|-----------|
| **Admin** | Mengelola seluruh sistem, user, produk, dan laporan kerusakan |
| **Pemilik** | Melihat laporan pendapatan dan status produk |
| **Kasir** | Mengelola transaksi, konfirmasi pengembalian, dan pembayaran denda |
| **Pelanggan** | Menyewa produk, melakukan pembayaran, dan mengembalikan barang |

---

## 🛠️ FITUR ADMIN

### 1. Dashboard Admin
| Aspek | Detail |
|-------|--------|
| **Route** | `GET /dashboard/admin` |
| **Controller** | `DashboardController@admin` |
| **View** | `dashboards/admin.blade.php` |
| **Middleware** | `auth`, `can:access-admin` |

**Fitur:**
- Statistik inventaris (Unit PS, Games, Accessories)
- Jumlah item tersedia, disewa, dan rusak
- Statistik user per role
- Transaksi terbaru (10 terakhir)
- Laporan kerusakan pending
- Damage statistics by category

---

### 2. Manajemen Pelanggan (CRUD)
| Aspek | Detail |
|-------|--------|
| **Route** | `resource: admin/pelanggan` |
| **Controller** | `Admin\PelangganController` |
| **Model** | `User` (role: pelanggan) |
| **View** | `admin/pelanggan/*.blade.php` |

**Fitur:**
- ✅ Lihat daftar pelanggan
- ✅ Tambah pelanggan baru
- ✅ Edit data pelanggan
- ✅ Hapus pelanggan
- ✅ Pencarian & filter

---

### 3. Manajemen Unit PlayStation (CRUD)
| Aspek | Detail |
|-------|--------|
| **Route** | `resource: admin/unitps` |
| **Controller** | `Admin\UnitPSController` |
| **Model** | `UnitPS` |
| **View** | `admin/unitps/*.blade.php` |

**Fields:**
- `name` - Nama unit
- `model` - Model PS (PS4, PS5, dll)
- `brand` - Merek
- `serial_number` - Nomor seri
- `stock` - Jumlah stok
- `price_per_hour` - Harga per jam
- `foto` - Gambar unit
- `kondisi` - Kondisi barang
- `status` - Status (available/maintenance)
- `keywords` - Kata kunci pencarian

---

### 4. Manajemen Games (CRUD)
| Aspek | Detail |
|-------|--------|
| **Route** | `resource: admin/games` |
| **Controller** | `Admin\GameController` |
| **Model** | `Game` |
| **View** | `admin/games/*.blade.php` |

**Fields:**
- `judul` - Judul game
- `platform` - Platform (PS4/PS5)
- `genre` - Genre game
- `stok` - Jumlah stok
- `harga_per_hari` - Harga per hari
- `gambar` - Cover game
- `deskripsi` - Deskripsi
- `nomor_seri` - Nomor seri
- `kondisi` - Kondisi
- `keywords` - Kata kunci

---

### 5. Manajemen Accessories (CRUD)
| Aspek | Detail |
|-------|--------|
| **Route** | `resource: admin/accessories` |
| **Controller** | `Admin\AccessoryController` |
| **Model** | `Accessory` |
| **View** | `admin/accessories/*.blade.php` |

**Fields:**
- `nama` - Nama aksesoris
- `jenis` - Jenis (Controller, Headset, dll)
- `stok` - Jumlah stok
- `harga_per_hari` - Harga per hari
- `gambar` - Gambar
- `deskripsi` - Deskripsi
- `nomor_seri` - Nomor seri
- `kondisi` - Kondisi
- `keywords` - Kata kunci

---

### 6. Manajemen Staff (Admin, Kasir, Pemilik)
| Aspek | Detail |
|-------|--------|
| **Route** | `admin/staff`, `admin/kasir`, `admin/pemilik`, `admin/admin` |
| **Controller** | `Admin\StaffController` |
| **Model** | `User` |
| **View** | `admin/staff/*.blade.php` |

**Fitur:**
- ✅ CRUD untuk setiap role staff
- ✅ Assign role (kasir/pemilik/admin)
- ✅ Manajemen password
- ✅ Aktivasi/deaktivasi akun

---

### 7. Laporan Kerusakan (Damage Reports)
| Aspek | Detail |
|-------|--------|
| **Route** | `admin/damage-reports` |
| **Controller** | `Admin\DamageReportController` |
| **Model** | `DamageReport` |
| **View** | `admin/damage-reports/*.blade.php` |

**Fitur:**
- ✅ Lihat semua laporan kerusakan
- ✅ Review laporan (set denda)
- ✅ Resolve/tutup case
- ✅ Lihat foto kerusakan (6 sisi)

**Flow Kerusakan:**
1. `pending` → Menunggu review admin
2. `reviewed` → Admin sudah set denda, menunggu konfirmasi user
3. `user_confirmed` → User konfirmasi, menunggu pembayaran
4. `fine_paid` → Denda dibayar, menunggu konfirmasi kasir
5. `kasir_confirmed` → Kasir konfirmasi, menunggu admin tutup
6. `resolved` → Case selesai

---

### 8. Impersonate User
| Aspek | Detail |
|-------|--------|
| **Route** | `GET admin/impersonate/{user}` |
| **Controller** | `Admin\ImpersonateController` |

**Fitur:**
- ✅ Login sebagai user lain untuk debugging
- ✅ Kembali ke akun admin asli

---

### 9. Laporan Admin
| Aspek | Detail |
|-------|--------|
| **Route** | `GET admin/laporan` |
| **Controller** | `DashboardController@adminReport` |
| **View** | `admin/laporan/index.blade.php` |

**Fitur:**
- Ringkasan pendapatan (total, hari ini, bulan ini)
- Statistik transaksi
- Pembayaran terakhir

---

## 💰 FITUR PEMILIK (OWNER)

### 1. Dashboard Pemilik
| Aspek | Detail |
|-------|--------|
| **Route** | `GET /dashboard/pemilik` |
| **Controller** | `DashboardController@pemilik` |
| **View** | `dashboards/pemilik.blade.php` |
| **Middleware** | `auth`, `can:access-pemilik` |

**Fitur:**
- KPI Cards (Unit, Games, Accessories tersedia)
- Transaksi hari ini
- Pendapatan 7 hari terakhir
- Transaksi terbaru

---

### 2. Status Produk
| Aspek | Detail |
|-------|--------|
| **Route** | `GET pemilik/status-produk` |
| **Controller** | `Owner\StatusProdukController@index` |
| **View** | `owner/status_produk.blade.php` |

**Fitur:**
- ✅ Lihat status semua produk
- ✅ Filter berdasarkan kategori
- ✅ Status: tersedia, disewa, rusak

---

### 3. Laporan Transaksi
| Aspek | Detail |
|-------|--------|
| **Route** | `GET pemilik/laporan-transaksi` |
| **Controller** | `Owner\LaporanController@index` |
| **View** | `owner/laporan.blade.php` |

**Fitur:**
- ✅ Daftar semua transaksi
- ✅ Filter tanggal (dari - sampai)
- ✅ Filter status
- ✅ Detail transaksi

---

### 4. Laporan Pendapatan
| Aspek | Detail |
|-------|--------|
| **Route** | `GET pemilik/laporan-pendapatan` |
| **Controller** | `Owner\LaporanController@pendapatan` |
| **View** | `owner/laporan_pendapatan.blade.php` |
| **Integrasi** | Chart.js |

**Fitur:**
- ✅ Grafik pendapatan (rental + denda)
- ✅ Filter periode tanggal
- ✅ Statistik: total, hari ini, bulan ini
- ✅ Daftar pembayaran detail
- ✅ Daftar pembayaran denda

---

### 5. Export Laporan
| Aspek | Detail |
|-------|--------|
| **Route** | `GET pemilik/laporan/export` |
| **Controller** | `Owner\LaporanController@export` |
| **Integrasi** | Maatwebsite Excel |

**Fitur:**
- ✅ Export ke Excel (.xlsx)
- ✅ Export ke CSV
- ✅ Filter tanggal

---

### 6. Laporan Kerusakan (View Only)
| Aspek | Detail |
|-------|--------|
| **Route** | `GET pemilik/damage-reports` |
| **Controller** | `Owner\LaporanController@damageReports` |
| **View** | `owner/damage_reports.blade.php` |

**Fitur:**
- ✅ Lihat semua item rusak
- ✅ Statistik kerusakan per kategori
- ✅ Total denda terkumpul
- ✅ Denda pending

---

## 🧾 FITUR KASIR

### 1. Dashboard Kasir
| Aspek | Detail |
|-------|--------|
| **Route** | `GET kasir/dashboard` |
| **Controller** | `Kasir\KasirDashboardController@index` |
| **View** | `kasir/dashboard.blade.php` |
| **Middleware** | `auth`, `can:access-kasir` |

**Fitur:**
- Statistik: pending payment, active rentals, waiting confirmation
- Pendapatan hari ini
- Transaksi terbaru
- Konfirmasi denda pending
- Pengembalian menunggu konfirmasi

---

### 2. Semua Transaksi
| Aspek | Detail |
|-------|--------|
| **Route** | `GET kasir/transactions` |
| **Controller** | `Kasir\KasirDashboardController@allTransactions` |
| **View** | `kasir/transactions/index.blade.php` |

**Fitur:**
- ✅ Daftar semua transaksi
- ✅ Filter status
- ✅ Filter tanggal
- ✅ Pencarian (kode/nama pelanggan)
- ✅ Pagination

---

### 3. Manajemen Pembayaran
| Aspek | Detail |
|-------|--------|
| **Route** | `GET kasir/payments` |
| **Controller** | `Kasir\KasirDashboardController@payments` |
| **View** | `kasir/payments/index.blade.php` |

**Fitur:**
- ✅ Daftar semua pembayaran
- ✅ Filter status pembayaran
- ✅ Filter tanggal
- ✅ Statistik: total income, today income, pending, successful

---

### 4. Pembayaran Denda
| Aspek | Detail |
|-------|--------|
| **Route** | `GET kasir/fines` |
| **Controller** | `Kasir\KasirDashboardController@finePayments` |
| **View** | `kasir/fines/index.blade.php` |

**Fitur:**
- ✅ Daftar denda yang perlu dikonfirmasi
- ✅ Filter: pending confirmation, confirmed, unpaid
- ✅ Statistik denda
- ✅ Konfirmasi pembayaran denda

---

### 5. Konfirmasi Pembayaran Denda
| Aspek | Detail |
|-------|--------|
| **Route** | `POST kasir/fines/{damageReport}/confirm` |
| **Controller** | `Kasir\KasirDashboardController@confirmFinePayment` |

**Fitur:**
- ✅ Verifikasi denda sudah dibayar
- ✅ Update status damage report
- ✅ Update rental fine_paid

---

### 6. Konfirmasi Pengembalian
| Aspek | Detail |
|-------|--------|
| **Route** | `POST kasir/confirm-return/{rental}` |
| **Controller** | `Kasir\KasirDashboardController@confirmReturn` |

**Fitur:**
- ✅ Konfirmasi pengembalian barang
- ✅ Restore stok untuk barang kondisi baik
- ✅ Handle barang rusak (tidak restore stok)
- ✅ Update status rental

---

### 7. Laporan Harian
| Aspek | Detail |
|-------|--------|
| **Route** | `GET kasir/daily-report` |
| **Controller** | `Kasir\KasirDashboardController@dailyReport` |
| **View** | `kasir/reports/daily.blade.php` |

**Fitur:**
- ✅ Rental dibuat hari ini
- ✅ Rental selesai hari ini
- ✅ Pembayaran diterima
- ✅ Denda dikonfirmasi
- ✅ Summary total pendapatan

---

### 8. Manajemen Rental (Legacy)
| Aspek | Detail |
|-------|--------|
| **Route** | `kasir/rentals/*` |
| **Controller** | `Kasir\RentalController` |

**Fitur:**
- ✅ Buat rental baru (untuk pelanggan walk-in)
- ✅ Lihat detail rental
- ✅ Proses pengembalian

---

## 🛒 FITUR PELANGGAN

### 1. Dashboard Pelanggan
| Aspek | Detail |
|-------|--------|
| **Route** | `GET /dashboard/pelanggan` |
| **Controller** | `DashboardController@pelanggan` |
| **View** | `dashboards/pelanggan.blade.php` |
| **Middleware** | `auth`, `can:access-pelanggan` |

**Fitur:**
- Katalog Unit PS terbaru
- Katalog Games terbaru
- Katalog Accessories terbaru
- Quick access ke penyewaan

---

### 2. Katalog Produk
| Aspek | Detail |
|-------|--------|
| **Route** | `pelanggan/unitps`, `pelanggan/games`, `pelanggan/accessories` |
| **Controller** | `Pelanggan\UnitPSController`, `Pelanggan\GameController`, `Pelanggan\AccessoryController` |
| **View** | `pelanggan/unitps/index.blade.php`, dll |

**Fitur:**
- ✅ Lihat semua produk tersedia
- ✅ Filter & pencarian
- ✅ Lihat detail produk
- ✅ Tambah ke keranjang

---

### 3. Keranjang Belanja (Cart)
| Aspek | Detail |
|-------|--------|
| **Route** | `pelanggan/cart/*` |
| **Controller** | `Pelanggan\CartController` |
| **Model** | `Cart` |
| **View** | `pelanggan/cart/index.blade.php` |

**Fitur:**
- ✅ Lihat keranjang
- ✅ Tambah item (`POST cart/add`)
- ✅ Update quantity (`POST cart/update`)
- ✅ Hapus item (`POST cart/remove`)
- ✅ Kosongkan keranjang (`POST cart/clear`)
- ✅ Checkout ke rental

---

### 4. Penyewaan (Rental)
| Aspek | Detail |
|-------|--------|
| **Route** | `pelanggan/rentals/*` |
| **Controller** | `Pelanggan\RentalController` |
| **Model** | `Rental`, `RentalItem` |
| **View** | `pelanggan/rentals/*.blade.php` |

**Fitur:**
- ✅ Lihat riwayat penyewaan
- ✅ Filter status & tanggal
- ✅ Pencarian kode/item
- ✅ Buat penyewaan baru
- ✅ Pilih tanggal sewa & kembali
- ✅ Validasi durasi (max 30 hari)
- ✅ Lihat detail penyewaan

---

### 5. Pembayaran Midtrans
| Aspek | Detail |
|-------|--------|
| **Route** | `pelanggan/rentals` (POST) |
| **Controller** | `Pelanggan\RentalController@store` |
| **Service** | `MidtransService` |
| **View** | `pelanggan/payment/midtrans.blade.php` |
| **Integrasi** | Midtrans Snap API |

**Flow Pembayaran:**
1. User checkout → Create rental (status: pending)
2. Generate Snap Token → Tampilkan popup Midtrans
3. User bayar → Midtrans webhook notification
4. Webhook update status → `sedang_disewa`
5. Stok dikurangi setelah pembayaran sukses

**Metode Pembayaran Tersedia:**
- 💳 Credit Card
- 🏦 Bank Transfer (BCA, BNI, BRI, Mandiri, Permata)
- 💰 E-Wallet (GoPay, ShopeePay, DANA, OVO)
- 🏪 Convenience Store (Alfamart, Indomaret)

---

### 6. Lanjutkan Pembayaran
| Aspek | Detail |
|-------|--------|
| **Route** | `GET pelanggan/rentals/{rental}/continue-payment` |
| **Controller** | `Pelanggan\RentalController@continuePayment` |

**Fitur:**
- ✅ Lanjutkan pembayaran yang tertunda
- ✅ Generate snap token baru jika expired
- ✅ Redirect ke halaman pembayaran

---

### 7. Batalkan Penyewaan
| Aspek | Detail |
|-------|--------|
| **Route** | `POST pelanggan/rentals/{rental}/cancel` |
| **Controller** | `Pelanggan\RentalController@cancel` |

**Fitur:**
- ✅ Batalkan rental pending
- ✅ Alasan pembatalan
- ✅ Tidak bisa batalkan jika sudah dibayar

---

### 8. Pengembalian Barang
| Aspek | Detail |
|-------|--------|
| **Route** | `GET pelanggan/rentals/{rental}/return` |
| **Controller** | `Pelanggan\RentalController@returnRental` |
| **View** | `pelanggan/rentals/return.blade.php` |

**Fitur:**
- ✅ Form pengembalian
- ✅ Pilih kondisi per item (Baik/Rusak)
- ✅ Upload 6 foto kerusakan (jika rusak)
- ✅ Deskripsi kerusakan
- ✅ Submit untuk review

---

### 9. Proses Pengembalian
| Aspek | Detail |
|-------|--------|
| **Route** | `POST pelanggan/rentals/{rental}/process-return` |
| **Controller** | `Pelanggan\RentalController@processReturn` |

**Fitur:**
- ✅ Validasi kondisi semua item
- ✅ Upload foto kerusakan (6 sisi: atas, bawah, depan, belakang, kiri, kanan)
- ✅ Buat damage report otomatis
- ✅ Update status ke `menunggu_konfirmasi`

---

### 10. Konfirmasi Laporan Kerusakan
| Aspek | Detail |
|-------|--------|
| **Route** | `POST pelanggan/damage-reports/{damageReport}/confirm` |
| **Controller** | `Pelanggan\RentalController@confirmDamageReport` |

**Fitur:**
- ✅ Konfirmasi denda yang ditetapkan admin
- ✅ Update status damage report

---

### 11. Bayar Denda
| Aspek | Detail |
|-------|--------|
| **Route** | `GET pelanggan/damage-reports/{damageReport}/pay-fine` |
| **Controller** | `Pelanggan\RentalController@payFine` |
| **Integrasi** | Midtrans |

**Fitur:**
- ✅ Bayar denda via Midtrans
- ✅ Callback setelah pembayaran

---

### 12. Profil Pelanggan
| Aspek | Detail |
|-------|--------|
| **Route** | `pelanggan/profile/*` |
| **Controller** | `Pelanggan\ProfileController` |
| **View** | `pelanggan/profile/*.blade.php` |

**Fitur:**
- ✅ Lihat profil
- ✅ Edit profil (nama, email, telepon, alamat)
- ✅ Upload avatar
- ✅ Ganti password

---

## 🔐 FITUR AUTENTIKASI

### 1. Register
| Aspek | Detail |
|-------|--------|
| **Route** | `GET/POST /register` |
| **Controller** | `Auth\RegisterController` |
| **View** | `auth/register.blade.php` |

**Fields:**
- Nama lengkap
- Email
- Password (min 8 karakter)
- Konfirmasi password

---

### 2. Login
| Aspek | Detail |
|-------|--------|
| **Route** | `GET/POST /login` |
| **Controller** | `Auth\LoginController` |
| **View** | `auth/login.blade.php` |

**Fitur:**
- ✅ Login dengan email & password
- ✅ Remember me
- ✅ Redirect berdasarkan role

---

### 3. Google OAuth Login
| Aspek | Detail |
|-------|--------|
| **Route** | `GET auth/google`, `GET auth/google/callback` |
| **Controller** | `Auth\GoogleController` |
| **Integrasi** | Laravel Socialite + Google OAuth 2.0 |

**Flow:**
1. User klik "Login dengan Google"
2. Redirect ke Google consent screen
3. Google callback dengan user data
4. Create/update user di database
5. Auto login

---

### 4. Forgot Password
| Aspek | Detail |
|-------|--------|
| **Route** | `GET/POST password/reset`, `GET/POST password/reset/{token}` |
| **Controller** | `Auth\ForgotPasswordController`, `Auth\ResetPasswordController` |
| **View** | `auth/passwords/*.blade.php` |

**Flow:**
1. User request reset link
2. Email dikirim dengan token
3. User klik link → form reset password
4. Password diupdate

---

### 5. Logout
| Aspek | Detail |
|-------|--------|
| **Route** | `POST /logout` |
| **Middleware** | `auth` |

---

## 🌐 FITUR PUBLIK (TANPA LOGIN)

### 1. Landing Page
| Aspek | Detail |
|-------|--------|
| **Route** | `GET /`, `GET /landing` |
| **View** | `landing.blade.php` |

**Fitur:**
- Hero section
- Katalog preview
- CTA register/login

---

### 2. Gallery Showcase
| Aspek | Detail |
|-------|--------|
| **Route** | `GET /showcase` |
| **Controller** | `DashboardController@galleryShowcase` |
| **View** | `pages/showcase/gallery.blade.php` |

**Fitur:**
- ✅ Galeri semua produk
- ✅ Filter kategori (Unit PS, Games, Accessories)
- ✅ Lightbox view
- ✅ Responsive grid (3 kolom desktop, 2 tablet, 1 mobile)

---

### 3. Halaman Statis
| Route | View |
|-------|------|
| `GET /about` | `pages/about.blade.php` |
| `GET /terms` | `pages/terms.blade.php` |
| `GET /privacy` | `pages/privacy.blade.php` |
| `GET /contact` | `pages/contact.blade.php` |

---

### 4. Multi-Language Support
| Aspek | Detail |
|-------|--------|
| **Route** | `GET lang/{locale}` |
| **Supported** | `id` (Indonesia), `en` (English) |
| **Storage** | Session |

---

## 💳 INTEGRASI MIDTRANS

### Konfigurasi
```php
// config/midtrans.php
'server_key' => env('MIDTRANS_SERVER_KEY'),
'client_key' => env('MIDTRANS_CLIENT_KEY'),
'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
'is_sanitized' => true,
'is_3ds' => true,
```

### Webhook Notification
| Aspek | Detail |
|-------|--------|
| **Route** | `POST /midtrans/notification` |
| **Controller** | `MidtransController@notification` |

**Transaction Status Handling:**
| Status | Action |
|--------|--------|
| `capture` (fraud: accept) | Rental → `sedang_disewa`, kurangi stok |
| `settlement` | Rental → `sedang_disewa`, kurangi stok |
| `pending` | Rental tetap `pending` |
| `deny/expire/cancel` | Rental → `cancelled` |

### Check Status Manual
| Aspek | Detail |
|-------|--------|
| **Route** | `GET /midtrans/status/{orderId}` |
| **Controller** | `MidtransController@checkStatus` |

---

## 📊 MODEL & DATABASE

### Users
```
- id, name, email, password, role
- phone, address, google_id, avatar
- email_verified_at, remember_token
- created_at, updated_at
```

### UnitPS
```
- id, name, model, brand, serial_number
- stock, price_per_hour, foto
- kondisi, status, keywords, deskripsi
- created_at, updated_at
```

### Games
```
- id, judul, platform, genre
- stok, harga_per_hari, gambar
- deskripsi, nomor_seri, kondisi, keywords
- created_at, updated_at
```

### Accessories
```
- id, nama, jenis
- stok, harga_per_hari, gambar
- deskripsi, nomor_seri, kondisi, keywords
- created_at, updated_at
```

### Rentals
```
- id, user_id, handled_by, kode
- start_at, due_at, returned_at
- status, subtotal, discount, total, paid
- notes, fine, fine_paid, late_fee
- late_hours, late_fee_description
- cancelled_at, cancel_reason
- created_at, updated_at
```

**Status Rental:**
- `pending` - Menunggu pembayaran
- `sedang_disewa` - Aktif/sedang disewa
- `menunggu_konfirmasi` - Menunggu konfirmasi pengembalian
- `selesai` - Selesai
- `cancelled` - Dibatalkan

### RentalItems
```
- id, rental_id
- rentable_type, rentable_id (polymorphic)
- quantity, price, total
- condition, fine, fine_description
- created_at, updated_at
```

### Payments
```
- id, rental_id, method, amount
- reference, paid_at, order_id
- snap_token, payment_instructions
- transaction_id, transaction_status
- payment_type, gross_amount
- transaction_time, fraud_status, raw_response
- created_at, updated_at
```

### Carts
```
- id, user_id, type, item_id
- quantity, price, name, price_type
- created_at, updated_at
```

### DamageReports
```
- id, rental_item_id, reported_by
- description
- photo_top, photo_bottom, photo_front
- photo_back, photo_left, photo_right
- status, reviewed_by, admin_feedback
- fine_amount, reviewed_at
- user_confirmed, user_confirmed_at
- fine_paid, fine_paid_at
- fine_payment_method, fine_transaction_id
- confirmed_by_kasir, kasir_confirmed_at
- closed_by, closed_at
- created_at, updated_at
```

---

## 🔒 MIDDLEWARE & GATES

### Gates (AuthServiceProvider)
```php
Gate::define('access-admin', fn($user) => $user->role === 'admin');
Gate::define('access-kasir', fn($user) => in_array($user->role, ['admin', 'kasir']));
Gate::define('access-pemilik', fn($user) => in_array($user->role, ['admin', 'pemilik']));
Gate::define('access-pelanggan', fn($user) => $user->role === 'pelanggan');
```

### Route Middleware Groups
- `auth` - Harus login
- `can:access-admin` - Hanya admin
- `can:access-kasir` - Admin atau kasir
- `can:access-pemilik` - Admin atau pemilik
- `can:access-pelanggan` - Hanya pelanggan
- `throttle:3,1` - Rate limiting (3 request per menit)

---

## 📁 STRUKTUR FOLDER

```
app/
├── Exceptions/
│   └── InsufficientStockException.php
├── Exports/
│   └── RentalExport.php
├── Http/
│   ├── Controllers/
│   │   ├── Admin/
│   │   │   ├── AccessoryController.php
│   │   │   ├── DamageReportController.php
│   │   │   ├── GameController.php
│   │   │   ├── ImpersonateController.php
│   │   │   ├── PelangganController.php
│   │   │   ├── StaffController.php
│   │   │   └── UnitPSController.php
│   │   ├── Auth/
│   │   │   ├── ForgotPasswordController.php
│   │   │   ├── GoogleController.php
│   │   │   ├── LoginController.php
│   │   │   ├── RegisterController.php
│   │   │   └── ResetPasswordController.php
│   │   ├── Kasir/
│   │   │   ├── KasirDashboardController.php
│   │   │   ├── PaymentController.php
│   │   │   ├── RentalController.php
│   │   │   └── TransaksiController.php
│   │   ├── Owner/
│   │   │   ├── LaporanController.php
│   │   │   └── StatusProdukController.php
│   │   ├── Pelanggan/
│   │   │   ├── AccessoryController.php
│   │   │   ├── CartController.php
│   │   │   ├── GameController.php
│   │   │   ├── ProfileController.php
│   │   │   ├── RentalController.php
│   │   │   └── UnitPSController.php
│   │   ├── Controller.php
│   │   ├── DashboardController.php
│   │   ├── MidtransController.php
│   │   └── ProfileController.php
│   └── Middleware/
├── Models/
│   ├── Accessory.php
│   ├── Cart.php
│   ├── DamageReport.php
│   ├── Game.php
│   ├── Payment.php
│   ├── Rental.php
│   ├── RentalItem.php
│   ├── UnitPS.php
│   └── User.php
└── Services/
    └── MidtransService.php

resources/views/
├── admin/
│   ├── accessories/
│   ├── damage-reports/
│   ├── games/
│   ├── laporan/
│   ├── pelanggan/
│   ├── staff/
│   └── unitps/
├── auth/
├── dashboards/
├── kasir/
│   ├── fines/
│   ├── payments/
│   ├── rentals/
│   ├── reports/
│   └── transactions/
├── layouts/
├── owner/
├── pages/
│   └── showcase/
├── pelanggan/
│   ├── accessories/
│   ├── cart/
│   ├── games/
│   ├── payment/
│   ├── profile/
│   ├── rentals/
│   └── unitps/
└── profile/
```

---

## 🚀 CARA MENJALANKAN

### 1. Install Dependencies
```bash
composer install
npm install
```

### 2. Setup Environment
```bash
cp .env.example .env
php artisan key:generate
```

### 3. Konfigurasi Database
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=rental_ps
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Konfigurasi Midtrans
```env
MIDTRANS_SERVER_KEY=your_server_key
MIDTRANS_CLIENT_KEY=your_client_key
MIDTRANS_IS_PRODUCTION=false
```

### 5. Konfigurasi Google OAuth
```env
GOOGLE_CLIENT_ID=your_client_id
GOOGLE_CLIENT_SECRET=your_client_secret
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
```

### 6. Migrasi & Seeder
```bash
php artisan migrate
php artisan db:seed
```

### 7. Storage Link
```bash
php artisan storage:link
```

### 8. Jalankan Server
```bash
# Development
composer run dev

# Atau manual
php artisan serve
npm run dev
```

---

## 📝 CATATAN PENTING

1. **Stok Management**: Stok hanya dikurangi setelah pembayaran berhasil (bukan saat checkout)
2. **Kode Transaksi**: Generate otomatis 4 karakter unik (AA01, AB12, dll)
3. **Durasi Sewa**: Maksimal 30 hari
4. **Foto Kerusakan**: Wajib 6 foto dari semua sisi
5. **Denda**: Ditentukan oleh admin setelah review
6. **Auto Cancel**: Rental pending akan otomatis dibatalkan jika tidak dibayar

---

## 👨‍💻 DIBUAT OLEH

Dokumentasi ini dibuat secara otomatis berdasarkan analisis kode sumber proyek PlayStation Rental System.

**Tanggal Dokumentasi**: 3 Desember 2025

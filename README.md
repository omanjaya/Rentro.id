# 🏢 Rentro.id - Marketplace Penyewaan Peralatan Elektronik

Aplikasi marketplace multi-sisi untuk penyewaan peralatan elektronik yang dibangun dengan Laravel 12. Platform ini menghubungkan pemilik peralatan (vendor) dengan pelanggan yang membutuhkan penyewaan perangkat elektronik.

## 🌟 Fitur Utama

### 📱 **Multi-User System**
- **Pelanggan Individu** - Penyewaan pribadi untuk perorangan
- **Pelanggan Bisnis** - Akun perusahaan dengan fitur penyewaan massal  
- **Vendor/Penyedia** - Pemilik peralatan yang menyewakan inventaris mereka
- **Admin** - Manajemen platform dengan kontrol penuh sistem

### 💼 **Fitur Marketplace Lanjutan**
- ✅ Sistem verifikasi dan persetujuan vendor
- ✅ Revenue sharing berbasis komisi (12-15% komisi platform)
- ✅ Workflow persetujuan listing produk
- ✅ Manajemen stok otomatis
- ✅ Pelacakan pendapatan dan analitik
- ✅ Sistem dashboard multi-tier

### 🛒 **Sistem Pemesanan Canggih**
- ✅ Pengecekan ketersediaan real-time via AJAX
- ✅ Kalkulasi harga dinamis dengan JavaScript
- ✅ Validasi tanggal dan pencegahan konflik
- ✅ Manajemen stok dengan update otomatis
- ✅ Konfirmasi pemesanan dengan kode rental unik

## 🗂️ Kategori Produk

1. **Laptop & Komputer** - Perangkat performa tinggi
2. **Kamera & Fotografi** - Peralatan profesional
3. **Audio & Sound** - Perangkat rekam dan playback
4. **Gaming** - Konsol dan aksesori gaming
5. **Mobile & Tablet** - Perangkat portabel dan aksesori

## 🏗️ Arsitektur Sistem

### Tipe Pengguna & Peran
```
user_type ENUM: 'individual', 'business', 'vendor', 'admin'
vendor_status ENUM: 'pending', 'approved', 'rejected', 'suspended'
```

### Model Pendapatan
- **Produk Platform** (vendor_id = null): 100% pendapatan ke platform
- **Produk Vendor** (vendor_id = user_id): Vendor menerima `Total Harga - Komisi`

## 📚 Dokumentasi Lengkap

- 📖 **[TUTORIAL-PEMULA.md](TUTORIAL-PEMULA.md)** - Panduan step-by-step untuk pemula
- 🔧 **[TROUBLESHOOTING.md](TROUBLESHOOTING.md)** - Solusi masalah umum dan debugging
- 🚀 **[DEPLOYMENT.md](DEPLOYMENT.md)** - Panduan deployment ke production
- 💻 **[DEVELOPMENT.md](DEVELOPMENT.md)** - Panduan development lanjutan

## 🚀 Quick Start

### Untuk Pemula
**Jika Anda baru belajar web development**, ikuti panduan lengkap di **[TUTORIAL-PEMULA.md](TUTORIAL-PEMULA.md)** yang mencakup instalasi software hingga testing aplikasi.

### Untuk Developer Berpengalaman
Jika sudah familiar dengan Laravel, ikuti instalasi cepat di bawah ini:

### Persyaratan Sistem
- PHP 8.1 atau lebih tinggi
- Composer
- Node.js & NPM
- SQLite/MySQL
- Laravel 12

### Langkah Instalasi

1. **Clone Repository**
```bash
git clone [repository-url]
cd Rentro.id
```

2. **Install Dependencies**
```bash
# Backend dependencies
composer install

# Frontend dependencies  
npm install && npm run dev
```

3. **Environment Setup**
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure database di .env
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

4. **Database Setup**
```bash
# Run migrations dengan sample data
php artisan migrate:fresh --seed

# Setup storage symlink
php artisan storage:link
```

5. **Start Development Server**
```bash
php artisan serve
```

Aplikasi akan berjalan di `http://localhost:8000`

## 👤 Data User Sample

### Admin
- **Email:** admin@rentro.id
- **Password:** password
- **Akses:** Manajemen sistem lengkap

### Vendor
- **Email:** vendor@example.com / **Password:** password
- **Email:** techstore@example.com / **Password:** password
- **Status:** Vendor yang sudah disetujui dengan produk

### Pelanggan
- **Email:** john@example.com / **Password:** password (Individu)
- **Email:** jane@example.com / **Password:** password (Individu)  
- **Email:** corporate@example.com / **Password:** password (Bisnis)

## 🛡️ Fitur Keamanan

- **Laravel Breeze** untuk autentikasi aman
- **Middleware berbasis peran** untuk kontrol akses
- **Perlindungan CSRF** pada semua form
- **Validasi input** komprehensif dengan Form Request classes
- **Keamanan upload file** dengan validasi tipe dan ukuran
- **Pencegahan SQL injection** melalui Eloquent ORM

## 🎨 Teknologi & Design

- **Backend:** Laravel 12 dengan arsitektur MVC
- **Frontend:** Tailwind CSS untuk design responsif
- **Database:** SQLite/MySQL dengan optimisasi indexing
- **JavaScript:** Vanilla JS untuk interaksi dinamis
- **Template Engine:** Blade dengan komponen reusable

## 📊 Dashboard & Analytics

### Dashboard Admin
- Pelacakan total pendapatan lintas platform dan vendor
- Metrik registrasi pengguna berdasarkan tipe
- Analitik performa produk
- Distribusi status rental

### Dashboard Vendor  
- Pelacakan pendapatan personal setelah komisi
- Metrik performa produk
- Statistik manajemen rental

## 🔄 Workflow Status

### Workflow Listing Produk
```
Vendor Buat → Review Pending → Persetujuan Admin → Live di Platform
```

### Workflow Persetujuan Rental
**Produk Platform:**
```
Pelanggan Pesan → Admin Setujui → Admin Aktifkan → Admin Selesaikan
```

**Produk Vendor:**
```
Pelanggan Pesan → Vendor Setujui/Aktifkan → Vendor Selesaikan
```

## 🌐 Bahasa

Aplikasi ini telah di-hardcode menggunakan **Bahasa Indonesia** secara penuh untuk memenuhi kebutuhan presentasi akademik. Semua interface, form, pesan, dan navigasi ditampilkan dalam Bahasa Indonesia.

## 🛠️ Commands Berguna

```bash
# Refresh database dengan data marketplace
php artisan migrate:fresh --seed

# Clear caches
php artisan config:clear && php artisan view:clear && php artisan route:clear

# Production optimization
php artisan config:cache && php artisan route:cache && php artisan view:cache
```

## 📝 Development Notes

- Gunakan **absolute paths** untuk semua file operations
- Ikuti **coding standards** Laravel dengan PSR-12
- **Test thoroughly** sebelum deployment
- **Backup database** sebelum migration besar

## 🤝 Kontribusi

Aplikasi ini dikembangkan untuk keperluan akademik (Tugas Akhir/Skripsi). Silakan fork dan kembangkan sesuai kebutuhan Anda.

## 📄 Lisensi

Project ini menggunakan lisensi MIT. Lihat file `LICENSE` untuk detail lengkap.

## 🆘 Support & Bantuan

### 📖 Dokumentasi
1. **[TUTORIAL-PEMULA.md](TUTORIAL-PEMULA.md)** - Mulai di sini jika baru belajar
2. **[TROUBLESHOOTING.md](TROUBLESHOOTING.md)** - Jika mengalami error atau masalah
3. **[DEPLOYMENT.md](DEPLOYMENT.md)** - Saat akan deploy ke production
4. **[DEVELOPMENT.md](DEVELOPMENT.md)** - Untuk pengembangan lanjutan

### 🔧 Masalah Umum
- **Error saat instalasi?** → Baca [TROUBLESHOOTING.md](TROUBLESHOOTING.md)
- **Bingung cara start?** → Ikuti [TUTORIAL-PEMULA.md](TUTORIAL-PEMULA.md)
- **Mau deploy ke hosting?** → Ikuti [DEPLOYMENT.md](DEPLOYMENT.md)

### 💬 Komunitas
- Create issue di GitHub repository ini
- Stack Overflow dengan tag `laravel` dan `rentro-id`
- Komunitas Laravel Indonesia

---

**Rentro.id** - Transforming electronic equipment rental through innovative marketplace technology. 🚀
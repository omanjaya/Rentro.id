# 👶 Tutorial Pemula - Menjalankan Rentro.id di Local

Panduan lengkap untuk pemula yang ingin menjalankan project Rentro.id di komputer lokal. Tutorial ini dibuat untuk orang yang baru belajar web development.

## 📋 Apa yang Akan Kita Lakukan?

1. Install software yang diperlukan
2. Download project dari GitHub
3. Setup database dan konfigurasi
4. Menjalankan aplikasi
5. Login dan testing fitur

---

## 🛠️ Step 1: Install Software yang Diperlukan

### A. Install PHP (versi 8.1 atau lebih tinggi)

**Windows:**
1. Download XAMPP dari https://www.apachefriends.org/
2. Install XAMPP (pilih PHP, Apache, MySQL)
3. Buka XAMPP Control Panel
4. Start Apache dan MySQL

**Mac:**
```bash
# Install Homebrew dulu (jika belum ada)
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"

# Install PHP
brew install php@8.1
```

**Linux (Ubuntu/Debian):**
```bash
sudo apt update
sudo apt install php8.1 php8.1-cli php8.1-fpm php8.1-mysql php8.1-xml php8.1-mbstring php8.1-curl php8.1-zip
```

### B. Install Composer (Package Manager untuk PHP)

1. Kunjungi https://getcomposer.org/download/
2. Download dan install Composer
3. Verify instalasi:
```bash
composer --version
```

### C. Install Node.js dan NPM

1. Download dari https://nodejs.org/ (pilih LTS version)
2. Install Node.js
3. Verify instalasi:
```bash
node --version
npm --version
```

### D. Install Git

**Windows:**
- Download dari https://git-scm.com/download/win

**Mac:**
```bash
brew install git
```

**Linux:**
```bash
sudo apt install git
```

---

## 📥 Step 2: Download Project

### A. Clone Repository
```bash
# Masuk ke folder dimana Anda ingin menyimpan project
cd Desktop

# Clone project (ganti URL dengan URL repository Anda)
git clone https://github.com/username/rentro-id.git

# Masuk ke folder project
cd rentro-id
```

### B. Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install JavaScript dependencies  
npm install
```

**Jika ada error saat composer install:**
```bash
# Coba dengan ignore platform requirements
composer install --ignore-platform-req=ext-gd
```

---

## ⚙️ Step 3: Konfigurasi Environment

### A. Copy Environment File
```bash
# Copy file .env.example menjadi .env
cp .env.example .env

# Untuk Windows (jika cp tidak work):
copy .env.example .env
```

### B. Generate Application Key
```bash
php artisan key:generate
```

### C. Setup Database

**Opsi 1 - SQLite (Mudah untuk pemula):**
```bash
# Buat file database SQLite
touch database/database.sqlite

# Untuk Windows:
type nul > database/database.sqlite
```

**Opsi 2 - MySQL (Jika Anda menggunakan XAMPP):**
1. Buka phpMyAdmin (http://localhost/phpmyadmin)
2. Buat database baru bernama `rentro_id`
3. Edit file `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=rentro_id
DB_USERNAME=root
DB_PASSWORD=
```

---

## 🗄️ Step 4: Setup Database

### A. Jalankan Migrations dan Seeders
```bash
# Jalankan migrations untuk membuat tabel
php artisan migrate

# Isi database dengan data sample
php artisan db:seed
```

**Jika ingin reset ulang database:**
```bash
php artisan migrate:fresh --seed
```

### B. Setup Storage Link
```bash
php artisan storage:link
```

---

## 🚀 Step 5: Menjalankan Aplikasi

### A. Compile Assets (CSS/JS)
```bash
npm run dev
```

### B. Start Server
```bash
php artisan serve
```

Aplikasi akan berjalan di: **http://localhost:8000**

---

## 🎯 Step 6: Testing & Login

### A. Buka Browser
Kunjungi: http://localhost:8000

### B. Data Login yang Tersedia

**Admin (Manajemen Platform):**
- Email: `admin@rentro.id`
- Password: `password`
- Akses: Dashboard admin, kelola semua produk, user, vendor

**Vendor (Penyedia Peralatan):**
- Email: `vendor@example.com`
- Password: `password`
- Akses: Dashboard vendor, kelola produk sendiri

**Customer Individual:**
- Email: `john@example.com`
- Password: `password`
- Akses: Dashboard customer, sewa produk

**Customer Business:**
- Email: `corporate@example.com`
- Password: `password`
- Akses: Dashboard business dengan fitur khusus

### C. Testing Fitur

1. **Login sebagai Customer:**
   - Browse produk di homepage
   - Klik "Lihat Detail" pada produk
   - Coba booking produk
   - Lihat dashboard penyewaan

2. **Login sebagai Vendor:**
   - Lihat dashboard vendor
   - Tambah produk baru
   - Kelola produk existing
   - Lihat rental untuk produk Anda

3. **Login sebagai Admin:**
   - Lihat dashboard admin
   - Kelola semua produk
   - Kelola kategori
   - Approve/reject vendor
   - Kelola semua rental

---

## 🆘 Troubleshooting - Masalah Umum

### ❌ Error: "Class not found"
```bash
composer dump-autoload
```

### ❌ Error: "Permission denied" (Linux/Mac)
```bash
sudo chmod -R 775 storage bootstrap/cache
sudo chown -R $USER:www-data storage bootstrap/cache
```

### ❌ Error: "Key not found"
```bash
php artisan key:generate
```

### ❌ Error: Database connection
```bash
# Check apakah database file ada (untuk SQLite)
ls -la database/database.sqlite

# Atau cek MySQL service running (untuk MySQL)
```

### ❌ Error: "Storage link not found"
```bash
php artisan storage:link
```

### ❌ Error saat npm install
```bash
# Clear npm cache
npm cache clean --force
npm install
```

### ❌ CSS/JS tidak muncul
```bash
# Compile ulang assets
npm run dev

# Atau untuk production
npm run build
```

---

## 🎨 Step 7: Customize Development

### A. Membuat User Baru
1. Kunjungi `/register`
2. Pilih tipe akun (Individual/Business/Vendor)
3. Isi form registrasi
4. Login dengan akun baru

### B. Development Workflow
```bash
# Setiap kali mulai development
cd rentro-id
php artisan serve

# Di terminal terpisah (untuk auto-compile CSS/JS)
npm run dev
```

### C. Clear Cache saat Development
```bash
# Clear semua cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

---

## 📝 Tips untuk Pemula

### 🔥 Hot Tips:
1. **Selalu backup database** sebelum eksperimen besar
2. **Gunakan SQLite** untuk development (lebih mudah)
3. **Check logs** di `storage/logs/laravel.log` jika ada error
4. **Gunakan browser incognito** untuk testing multi-user
5. **Restart server** jika ada perubahan di config

### 📚 Belajar Lebih Lanjut:
- **Laravel Documentation:** https://laravel.com/docs
- **PHP Basics:** https://www.php.net/manual/en/tutorial.php
- **Tailwind CSS:** https://tailwindcss.com/docs
- **Git Basics:** https://git-scm.com/book/en/v2

### 🛠️ Tools Berguna:
- **VS Code:** Editor code terbaik dengan Laravel extension
- **TablePlus/phpMyAdmin:** Untuk lihat isi database
- **Postman:** Untuk testing API (jika ada)
- **Browser DevTools:** Untuk debug CSS/JS

---

## 🎉 Selamat!

Jika Anda sampai di sini dan aplikasi berhasil jalan, **SELAMAT!** 🎉

Anda sudah berhasil:
- ✅ Setup environment development
- ✅ Install dan konfigurasi Laravel application
- ✅ Menjalankan database migrations
- ✅ Testing fitur aplikasi
- ✅ Memahami struktur project

### Langkah Selanjutnya:
1. Eksplorasi semua fitur yang ada
2. Baca kode di folder `app/`, `resources/views/`
3. Coba modifikasi tampilan atau fitur kecil
4. Baca DEVELOPMENT.md untuk panduan development lanjut

**Happy Coding!** 🚀

---

## 📞 Butuh Bantuan?

Jika mengalami masalah:
1. Cek section Troubleshooting di atas
2. Baca error message dengan teliti
3. Google error message yang muncul
4. Tanya di komunitas Laravel Indonesia
5. Create issue di GitHub repository

**Remember:** Setiap developer pernah mengalami error. Yang penting adalah belajar dari error tersebut! 💪
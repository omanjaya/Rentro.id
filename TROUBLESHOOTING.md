# 🔧 Troubleshooting Guide - Rentro.id

Panduan lengkap untuk mengatasi masalah umum yang sering terjadi saat setup dan development Rentro.id.

## 🚨 Masalah Installation

### 1. Composer Install Gagal

**Error:** `Your requirements could not be resolved to an installable set of packages`

**Solusi:**
```bash
# Cek versi PHP
php --version

# Jika PHP < 8.1, upgrade dulu PHP
# Kemudian install dengan ignore platform requirements
composer install --ignore-platform-requirements

# Atau update composer
composer self-update
composer install
```

### 2. NPM Install Error

**Error:** `npm ERR! peer dep missing`

**Solusi:**
```bash
# Delete node_modules dan package-lock.json
rm -rf node_modules
rm package-lock.json

# Install ulang
npm cache clean --force
npm install

# Jika masih error, coba dengan npm legacy
npm install --legacy-peer-deps
```

### 3. Permission Denied (Linux/Mac)

**Error:** `Permission denied` saat akses storage atau cache

**Solusi:**
```bash
# Set permission yang benar
sudo chmod -R 775 storage
sudo chmod -R 775 bootstrap/cache

# Set ownership
sudo chown -R $USER:www-data storage
sudo chown -R $USER:www-data bootstrap/cache

# Untuk development lokal (tidak recommended untuk production)
sudo chmod -R 777 storage
sudo chmod -R 777 bootstrap/cache
```

---

## 🗄️ Masalah Database

### 1. Database Connection Error

**Error SQLite:** `database file does not exist`

**Solusi:**
```bash
# Pastikan file database ada
touch database/database.sqlite

# Jika di Windows
type nul > database\database.sqlite

# Jalankan migration
php artisan migrate
```

**Error MySQL:** `Connection refused`

**Solusi:**
```bash
# Pastikan MySQL service running
# Windows (XAMPP): Start MySQL di XAMPP Control Panel
# Mac: brew services start mysql
# Linux: sudo service mysql start

# Test koneksi
mysql -u root -p

# Check .env configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=rentro_id
DB_USERNAME=root
DB_PASSWORD=
```

### 2. Migration Error

**Error:** `SQLSTATE[42000]: Syntax error`

**Solusi:**
```bash
# Reset database
php artisan migrate:reset

# Fresh migration
php artisan migrate:fresh

# Dengan seeder
php artisan migrate:fresh --seed
```

### 3. Seeder Error

**Error:** `Class 'DatabaseSeeder' not found`

**Solusi:**
```bash
# Regenerate autoload
composer dump-autoload

# Jalankan seeder spesifik
php artisan db:seed --class=UserSeeder
php artisan db:seed --class=CategorySeeder
php artisan db:seed --class=ProductSeeder
```

---

## 🌐 Masalah Server & Browser

### 1. Server Tidak Bisa Start

**Error:** `Port 8000 already in use`

**Solusi:**
```bash
# Gunakan port lain
php artisan serve --port=8001

# Atau kill process yang menggunakan port 8000
# Windows:
netstat -ano | findstr :8000
taskkill /PID <PID_NUMBER> /F

# Mac/Linux:
lsof -ti:8000 | xargs kill -9
```

### 2. 404 Error di Route

**Error:** `404 Not Found` untuk route yang seharusnya ada

**Solusi:**
```bash
# Clear route cache
php artisan route:clear

# Check route list
php artisan route:list

# Clear semua cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

### 3. CSS/JS Tidak Muncul

**Error:** Styling hilang atau JavaScript tidak berfungsi

**Solusi:**
```bash
# Compile assets
npm run dev

# Untuk development dengan auto-reload
npm run dev

# Untuk production
npm run build

# Check apakah file di public/build/ ada
ls public/build/
```

---

## 🔐 Masalah Authentication & Session

### 1. Login Tidak Berfungsi

**Error:** Selalu redirect ke login page

**Solusi:**
```bash
# Clear session
php artisan session:table
php artisan migrate

# atau jika menggunakan file session
rm -rf storage/framework/sessions/*

# Check .env session config
SESSION_DRIVER=database
```

### 2. CSRF Token Mismatch

**Error:** `419 Page Expired` saat submit form

**Solusi:**
```bash
# Clear cache
php artisan config:clear

# Check apakah @csrf ada di form
# Pastikan form method POST

# Restart server
php artisan serve
```

### 3. Registration Error

**Error:** User tidak bisa register dengan tipe tertentu

**Solusi:**
- Check database seeder sudah jalan (kategori harus ada)
- Pastikan enum values di database sesuai
- Check validation rules di RegisterController

---

## 📁 Masalah File Upload

### 1. Storage Link Error

**Error:** Gambar tidak muncul atau 404 untuk uploaded files

**Solusi:**
```bash
# Create storage link
php artisan storage:link

# Check apakah link berhasil dibuat
ls -la public/storage

# Jika masih error, hapus dan buat ulang
rm public/storage
php artisan storage:link
```

### 2. File Upload Permission

**Error:** `failed to open stream: Permission denied`

**Solusi:**
```bash
# Set permission untuk storage
chmod -R 775 storage/app/public

# Check ownership
chown -R www-data:www-data storage/app/public
```

### 3. File Size Limit

**Error:** File upload gagal untuk file besar

**Solusi:**
Edit `php.ini`:
```ini
upload_max_filesize = 10M
post_max_size = 10M
max_execution_time = 300
```

Restart web server setelah edit php.ini

---

## 🎨 Masalah Frontend

### 1. Tailwind Classes Tidak Bekerja

**Error:** Styling Tailwind tidak muncul

**Solusi:**
```bash
# Check tailwind config
cat tailwind.config.js

# Rebuild assets
npm run dev

# Check apakah Tailwind dikompile dengan benar
cat public/build/assets/app-*.css | grep "tailwind"
```

### 2. JavaScript Error

**Error:** Fitur JavaScript tidak berfungsi (booking form, etc.)

**Solusi:**
```bash
# Check browser console untuk error
# Open DevTools -> Console

# Rebuild JavaScript
npm run dev

# Check apakah JavaScript file ter-load
# DevTools -> Network tab
```

---

## 🐛 Debugging Tips

### 1. Enable Debug Mode

Edit `.env`:
```env
APP_DEBUG=true
APP_ENV=local
LOG_LEVEL=debug
```

### 2. Check Logs

```bash
# Tail log file
tail -f storage/logs/laravel.log

# Clear log file
> storage/logs/laravel.log
```

### 3. Database Debugging

```bash
# Check database content
php artisan tinker

# Di tinker console:
\App\Models\User::count()
\App\Models\Product::all()
\App\Models\Category::all()
```

### 4. Route Debugging

```bash
# List semua route
php artisan route:list

# Filter route tertentu
php artisan route:list --name=admin
php artisan route:list --path=api
```

---

## 🔄 Reset Environment

### Complete Reset (Nuclear Option)

Jika semua cara di atas tidak berhasil:

```bash
# 1. Backup database (jika ada data penting)
cp database/database.sqlite database/backup.sqlite

# 2. Clean install
rm -rf vendor/
rm -rf node_modules/
rm composer.lock
rm package-lock.json

# 3. Fresh install
composer install
npm install

# 4. Reset environment
cp .env.example .env
php artisan key:generate

# 5. Fresh database
rm database/database.sqlite
touch database/database.sqlite
php artisan migrate:fresh --seed

# 6. Rebuild assets
npm run dev
php artisan storage:link

# 7. Clear all cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# 8. Start server
php artisan serve
```

---

## 📞 Masih Butuh Bantuan?

### 1. Debug Information yang Berguna

Saat meminta bantuan, berikan informasi:
```bash
# Versi software
php --version
composer --version
npm --version
node --version

# Error message lengkap
# Screenshot error di browser
# Isi file .env (tanpa password/key sensitif)
```

### 2. Komunitas & Resources

- **Laravel Documentation:** https://laravel.com/docs
- **Stack Overflow:** Tag `laravel`
- **Laravel Indonesia:** Facebook Group
- **GitHub Issues:** Create issue di repository

### 3. Check Official Sources

- Laravel tidak compatible dengan PHP versi lama
- Check Laravel compatibility matrix
- Update dependencies secara berkala

---

**Remember:** Error adalah bagian dari learning process! 💪

Setiap developer senior pernah mengalami semua error di atas. Yang penting adalah:
1. Baca error message dengan teliti
2. Search di Google dengan kata kunci spesifik
3. Check dokumentasi official
4. Jangan takut untuk eksperimen

**Happy Debugging!** 🐛🔨
# 🚀 Panduan Deployment - Rentro.id

Panduan lengkap untuk deploy aplikasi Rentro.id ke berbagai platform hosting.

## 📋 Pre-Deployment Checklist

### ✅ Persiapan Sebelum Deploy

- [ ] Testing lengkap di local environment
- [ ] Database seeder berfungsi dengan baik
- [ ] All features tested (auth, booking, admin, vendor)
- [ ] CSS/JS assets ter-compile dengan benar
- [ ] Error handling sudah proper
- [ ] Backup database development

---

## 🌐 Deployment ke Shared Hosting (Hostinger, cPanel, dll)

### Step 1: Persiapan Files

```bash
# 1. Optimize untuk production
composer install --optimize-autoloader --no-dev
npm run build

# 2. Create deployment package
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 3. Set environment production
cp .env .env.backup
```

### Step 2: Upload Files

**Via cPanel File Manager atau FTP:**

1. **Upload semua files kecuali:**
   - `.env` (buat manual di server)
   - `node_modules/`
   - `.git/`
   - `tests/`
   - `storage/logs/`

2. **Structure di hosting:**
```
public_html/
├── (semua isi folder public/)
├── app/
├── bootstrap/
├── config/
├── database/
├── resources/
├── routes/
├── storage/
├── vendor/
└── .env
```

### Step 3: Setup .env Production

Buat file `.env` di server:
```env
APP_NAME="Rentro.id"
APP_ENV=production
APP_KEY=base64:YOUR_GENERATED_KEY
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_db_username
DB_PASSWORD=your_db_password

SESSION_DRIVER=database
QUEUE_CONNECTION=database
CACHE_STORE=database

MAIL_MAILER=smtp
MAIL_HOST=smtp.yourdomain.com
MAIL_PORT=587
MAIL_USERNAME=noreply@yourdomain.com
MAIL_PASSWORD=your_email_password
MAIL_ENCRYPTION=tls
```

### Step 4: Setup Database

**Via phpMyAdmin:**
1. Create database baru
2. Import database atau jalankan migrations:

**Via SSH (jika tersedia):**
```bash
cd /path/to/your/app
php artisan migrate --force
php artisan db:seed --force
```

### Step 5: Set Permissions

**Via cPanel File Manager:**
- Set `storage/` dan `bootstrap/cache/` permissions ke 755
- Set `storage/logs/` permissions ke 755

**Via SSH:**
```bash
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
```

### Step 6: Setup Storage Link

**Via SSH:**
```bash
php artisan storage:link
```

**Manual (jika tidak ada SSH):**
- Create symbolic link dari `public/storage` ke `storage/app/public`
- Atau copy files dari storage ke public/storage

---

## ☁️ Deployment ke Cloud Platform

### A. Heroku Deployment

**Persiapan:**
```bash
# Install Heroku CLI
# Create Procfile
echo "web: vendor/bin/heroku-php-apache2 public/" > Procfile

# Add to git
git add .
git commit -m "Prepare for Heroku deployment"
```

**Deploy Process:**
```bash
# Login Heroku
heroku login

# Create app
heroku create your-app-name

# Set environment variables
heroku config:set APP_NAME="Rentro.id"
heroku config:set APP_ENV=production
heroku config:set APP_DEBUG=false
heroku config:set APP_KEY=$(php artisan --show key:generate)

# Add database
heroku addons:create heroku-postgresql:hobby-dev

# Deploy
git push heroku main

# Run migrations
heroku run php artisan migrate --force
heroku run php artisan db:seed --force
```

### B. DigitalOcean/Vultr VPS

**Server Requirements:**
- Ubuntu 20.04 LTS
- PHP 8.1+
- MySQL 8.0+
- Nginx/Apache
- Composer
- Node.js

**Setup Process:**
```bash
# 1. Update server
sudo apt update && sudo apt upgrade -y

# 2. Install LEMP stack
sudo apt install nginx mysql-server php8.1-fpm php8.1-mysql php8.1-xml php8.1-mbstring php8.1-curl php8.1-zip php8.1-bcmath -y

# 3. Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# 4. Clone project
cd /var/www/
sudo git clone your-repo-url rentro-id
sudo chown -R www-data:www-data rentro-id

# 5. Install dependencies
cd rentro-id
composer install --optimize-autoloader --no-dev

# 6. Setup environment
sudo cp .env.example .env
sudo php artisan key:generate

# 7. Setup database
sudo mysql
CREATE DATABASE rentro_id;
CREATE USER 'rentro'@'localhost' IDENTIFIED BY 'strong_password';
GRANT ALL PRIVILEGES ON rentro_id.* TO 'rentro'@'localhost';
FLUSH PRIVILEGES;

# 8. Run migrations
php artisan migrate --force
php artisan db:seed --force

# 9. Set permissions
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

# 10. Setup Nginx
sudo nano /etc/nginx/sites-available/rentro-id
```

**Nginx Configuration:**
```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /var/www/rentro-id/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

```bash
# Enable site
sudo ln -s /etc/nginx/sites-available/rentro-id /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

---

## 🔒 Security & SSL

### SSL Certificate (Let's Encrypt)

```bash
# Install certbot
sudo apt install certbot python3-certbot-nginx

# Get certificate
sudo certbot --nginx -d yourdomain.com

# Test auto-renewal
sudo certbot renew --dry-run
```

### Security Headers

Add to Nginx config:
```nginx
add_header X-Frame-Options "SAMEORIGIN";
add_header X-Content-Type-Options "nosniff";
add_header X-XSS-Protection "1; mode=block";
add_header Strict-Transport-Security "max-age=31536000; includeSubDomains";
```

---

## 📊 Production Monitoring

### A. Error Monitoring

**Setup Logs:**
```bash
# Rotate Laravel logs
sudo nano /etc/logrotate.d/laravel

# Add content:
/var/www/rentro-id/storage/logs/*.log {
    daily
    missingok
    rotate 14
    compress
    notifempty
    create 644 www-data www-data
}
```

**Monitor Logs:**
```bash
# Real-time monitoring
tail -f storage/logs/laravel.log

# Check error frequency
grep "ERROR" storage/logs/laravel.log | wc -l
```

### B. Performance Monitoring

**Enable OPcache:**
```php
// Add to php.ini
opcache.enable=1
opcache.memory_consumption=128
opcache.interned_strings_buffer=8
opcache.max_accelerated_files=4000
opcache.revalidate_freq=2
```

**Database Optimization:**
```sql
-- Add indexes for performance
ALTER TABLE products ADD INDEX idx_category_status (category_id, status);
ALTER TABLE rentals ADD INDEX idx_user_status (user_id, status);
ALTER TABLE users ADD INDEX idx_user_type (user_type);
```

---

## 🔄 Maintenance & Updates

### A. Backup Strategy

**Database Backup:**
```bash
# Daily backup script
#!/bin/bash
DATE=$(date +%Y%m%d_%H%M%S)
mysqldump -u rentro -p rentro_id > /backups/db_backup_$DATE.sql
find /backups -name "db_backup_*.sql" -mtime +7 -delete
```

**File Backup:**
```bash
# Weekly file backup
tar -czf /backups/files_backup_$(date +%Y%m%d).tar.gz /var/www/rentro-id \
    --exclude=/var/www/rentro-id/storage/logs \
    --exclude=/var/www/rentro-id/node_modules
```

### B. Update Process

```bash
# 1. Backup current version
cp -r /var/www/rentro-id /backups/rentro-id_$(date +%Y%m%d)

# 2. Pull updates
cd /var/www/rentro-id
git pull origin main

# 3. Update dependencies
composer install --optimize-autoloader --no-dev

# 4. Run migrations (if any)
php artisan migrate --force

# 5. Clear caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6. Restart services
sudo systemctl reload nginx
sudo systemctl restart php8.1-fpm
```

---

## 🆘 Troubleshooting Production

### Common Production Issues

**1. 500 Internal Server Error:**
```bash
# Check Laravel logs
tail -f storage/logs/laravel.log

# Check web server logs
sudo tail -f /var/log/nginx/error.log
```

**2. File Permission Issues:**
```bash
# Fix permissions
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

**3. Database Connection Issues:**
```bash
# Test database connection
php artisan tinker
DB::connection()->getPdo();
```

**4. Asset Loading Issues:**
```bash
# Rebuild assets
npm install
npm run build

# Check public/build directory exists
ls -la public/build/
```

---

## 📝 Post-Deployment Checklist

### ✅ Verification Steps

- [ ] Homepage loads correctly
- [ ] User registration works
- [ ] Login functionality works for all user types
- [ ] Admin dashboard accessible
- [ ] Vendor dashboard accessible  
- [ ] Product booking system works
- [ ] File uploads work (product images)
- [ ] Email notifications work (if configured)
- [ ] SSL certificate is valid
- [ ] Site loads on mobile devices
- [ ] Database backup is working
- [ ] Error monitoring is active

### 🎯 Performance Testing

```bash
# Test page load times
curl -w "@curl-format.txt" -o /dev/null -s "https://yourdomain.com"

# Test database performance
php artisan tinker
Illuminate\Support\Facades\DB::enableQueryLog();
// Run some operations
Illuminate\Support\Facades\DB::getQueryLog();
```

---

**🎉 Congratulations!** 

Aplikasi Rentro.id Anda sekarang sudah live di production! 

**Next Steps:**
1. Monitor error logs regularly
2. Setup automated backups
3. Monitor site performance
4. Plan for scaling if needed
5. Keep dependencies updated

**Remember:** Production is not "set and forget" - it needs ongoing maintenance and monitoring! 🚀
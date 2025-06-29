# 🛠️ Panduan Development - Rentro.id

Panduan lengkap untuk developer yang akan melanjutkan pengembangan aplikasi Rentro.id.

## 📁 Struktur Project

```
Rentro.id/
├── app/
│   ├── Http/Controllers/        # Controller untuk semua fitur
│   │   ├── Admin/              # Admin management
│   │   ├── Vendor/             # Vendor management  
│   │   └── User/               # User rentals
│   ├── Models/                 # Database models
│   ├── Middleware/             # Custom middleware
│   └── Providers/              # Service providers
├── database/
│   ├── migrations/             # Database schema
│   ├── seeders/                # Sample data
│   └── factories/              # Model factories
├── resources/
│   ├── views/                  # Blade templates
│   │   ├── admin/              # Admin interface
│   │   ├── vendor/             # Vendor interface
│   │   ├── auth/               # Authentication pages
│   │   └── components/         # Reusable components
│   └── css/                    # Styling assets
└── routes/                     # Route definitions
```

## 🗄️ Database Schema Utama

### Users Table
```sql
users (
    id, name, email, password, phone, address, avatar,
    user_type ENUM('individual', 'business', 'vendor', 'admin'),
    business_name, business_address, business_phone,
    vendor_status ENUM('pending', 'approved', 'rejected', 'suspended'),
    commission_rate DECIMAL(5,2) DEFAULT 15.00,
    verified_at, approved_at, rejected_at
)
```

### Products Table  
```sql
products (
    id, category_id, vendor_id, name, slug, description,
    specifications JSON, price_per_day, vendor_price,
    stock, image, gallery JSON, status,
    listing_status ENUM('draft', 'pending', 'approved', 'rejected'),
    rejection_reason, approved_at, featured BOOLEAN
)
```

### Rentals Table
```sql
rentals (
    id, user_id, product_id, rental_code,
    start_date, end_date, days, price_per_day, total_price,
    status ENUM('pending', 'approved', 'active', 'completed', 'cancelled'),
    phone, address, notes
)
```

## 🚀 Quick Start untuk Developer Baru

### 1. Clone & Setup
```bash
git clone [repository-url]
cd Rentro.id
composer install
cp .env.example .env
php artisan key:generate
```

### 2. Database Setup
```bash
# Edit .env untuk database
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite

# Jalankan migrations
php artisan migrate:fresh --seed
php artisan storage:link
```

### 3. Sample Data
Setelah seeding, Anda akan memiliki:
- 1 Admin user
- 2 Vendor yang sudah disetujui  
- 3 Customer (individual & business)
- 20+ produk sample
- 5 kategori produk

## 🔧 Fitur yang Sudah Diimplementasi

### ✅ Authentication & Authorization
- Multi-user registration (individual, business, vendor)
- Role-based access control
- Vendor verification system
- Laravel Breeze integration

### ✅ Product Management
- CRUD operations untuk admin dan vendor
- Image upload dengan gallery support
- Category management
- Product approval workflow
- Stock management

### ✅ Rental System
- Booking form dengan date validation
- Real-time availability checking
- Rental status workflow
- Commission calculation
- Rental code generation

### ✅ Dashboard System
- Admin dashboard dengan analytics
- Vendor dashboard dengan revenue tracking
- Customer dashboard dengan rental history
- Multi-user role support

### ✅ UI/UX
- Responsive design dengan Tailwind CSS
- Interactive components dengan JavaScript
- File upload dengan preview
- Form validation
- Status indicators

## 🔄 Workflow Development

### 1. Menambah Fitur Baru
```bash
# Buat controller
php artisan make:controller FeatureController

# Buat model dengan migration
php artisan make:model Feature -m

# Buat form request untuk validasi
php artisan make:request StoreFeatureRequest
```

### 2. Database Changes
```bash
# Buat migration baru
php artisan make:migration add_field_to_table

# Edit migration file, lalu jalankan
php artisan migrate

# Jika perlu rollback
php artisan migrate:rollback
```

### 3. Testing
```bash
# Test fitur baru
php artisan serve

# Clear cache jika diperlukan
php artisan config:clear
php artisan view:clear
php artisan route:clear
```

## 🎯 Area Pengembangan Prioritas

### 🔴 High Priority
1. **Payment Gateway Integration**
   - Integrasi dengan Midtrans/Xendit
   - Automatic payment processing
   - Payment status tracking

2. **Enhanced Vendor Features**
   - Vendor analytics dashboard
   - Bulk product management
   - Vendor rating system

3. **Advanced Booking System**
   - Calendar integration
   - Recurring rentals
   - Booking conflicts prevention

### 🟡 Medium Priority
1. **Notification System**
   - Email notifications
   - In-app notifications
   - SMS notifications

2. **Mobile API**
   - RESTful API development
   - Mobile app support
   - API documentation

3. **Advanced Analytics**
   - Revenue reporting
   - User behavior tracking
   - Performance metrics

### 🟢 Low Priority
1. **Social Features**
   - Product reviews & ratings
   - User profiles
   - Social sharing

2. **Advanced Search**
   - Elasticsearch integration
   - Advanced filtering
   - Search analytics

## 🔒 Security Guidelines

### Input Validation
- Gunakan Form Request classes untuk validasi
- Validasi file uploads (type, size, virus scan)
- Sanitize user input

### Access Control
- Gunakan middleware untuk authorization
- Implement RBAC (Role-Based Access Control)
- Validate user permissions di setiap action

### Data Protection
- Encrypt sensitive data
- Use HTTPS di production
- Implement CSRF protection
- Validate API requests

## 🐛 Debugging & Troubleshooting

### Common Issues

**1. Database Connection Error**
```bash
# Check .env database configuration
# Ensure database file exists (for SQLite)
touch database/database.sqlite
php artisan migrate
```

**2. Storage Link Missing**
```bash
php artisan storage:link
```

**3. Permission Issues**
```bash
chmod -R 775 storage bootstrap/cache
```

**4. Cache Issues**
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

### Debugging Tools
- Laravel Debugbar untuk development
- Laravel Telescope untuk monitoring
- Xdebug untuk step debugging
- Log files di `storage/logs/`

## 📝 Coding Standards

### Laravel Best Practices
- Ikuti PSR-12 coding standards
- Gunakan Eloquent ORM untuk database operations
- Implement Repository pattern untuk logic kompleks
- Gunakan Service classes untuk business logic

### Naming Conventions
- Controllers: `PascalCase` + `Controller` suffix
- Models: `PascalCase` singular
- Variables: `camelCase`
- Database tables: `snake_case` plural
- Routes: `kebab-case`

### File Organization
- Group related functionality dalam folders
- Gunakan namespaces yang konsisten
- Pisahkan business logic dari presentation logic
- Implement dependency injection

## 🚀 Deployment Guidelines

### Production Checklist
- [ ] Update .env untuk production
- [ ] Set APP_ENV=production
- [ ] Set APP_DEBUG=false
- [ ] Configure proper database
- [ ] Setup SSL certificate
- [ ] Configure web server (Apache/Nginx)
- [ ] Setup backup system
- [ ] Configure monitoring

### Performance Optimization
```bash
# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimize composer autoloader
composer install --optimize-autoloader --no-dev
```

## 📞 Support & Resources

### Documentation
- Laravel 12: https://laravel.com/docs
- Tailwind CSS: https://tailwindcss.com/docs
- PHP Standards: https://www.php-fig.org/psr/

### Community
- Laravel Community: https://laravel.com/community
- Stack Overflow: Tag `laravel`
- Laravel News: https://laravel-news.com

---

**Happy Coding!** 🎉 Semoga panduan ini membantu dalam pengembangan Rentro.id lebih lanjut.
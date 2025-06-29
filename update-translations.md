# Translation Implementation Guide

## Key Translation Functions

- `t('key')` - Translates from app.php language file
- `__('key')` - Laravel's default translation function
- `t('nav.dashboard')` - Nested translation keys

## Quick Implementation

### 1. Update Welcome Page
Replace hardcoded text with:
```blade
{{ t('hero.title') }}
{{ t('hero.subtitle') }}
{{ t('hero.description') }}
{{ t('hero.browse_products') }}
{{ t('hero.get_started') }}
{{ t('products.popular_categories') }}
{{ t('products.featured_products') }}
```

### 2. Update Navigation
Replace menu items:
```blade
{{ t('nav.home') }}
{{ t('nav.products') }}
{{ t('nav.dashboard') }}
{{ t('nav.my_rentals') }}
{{ t('nav.profile') }}
{{ t('nav.logout') }}
```

### 3. Update Product Pages
```blade
{{ t('products.title') }}
{{ t('products.search_placeholder') }}
{{ t('products.filter_by_category') }}
{{ t('products.per_day') }}
{{ t('products.stock') }}
{{ t('products.rent_now') }}
```

### 4. Update Forms
```blade
{{ t('form.required') }}
{{ t('user.name') }}
{{ t('user.email') }}
{{ t('user.password') }}
{{ t('save') }}
{{ t('cancel') }}
```

### 5. Update Admin Dashboard
```blade
{{ t('admin.dashboard') }}
{{ t('admin.total_products') }}
{{ t('admin.total_users') }}
{{ t('admin.revenue_today') }}
{{ t('admin.recent_rentals') }}
```

## Testing Language Switch

1. Visit site with `?lang=id` for Indonesian
2. Visit site with `?lang=en` for English
3. Language preference is stored in session

## Adding New Translations

1. Add to `/resources/lang/id/app.php`
2. Use `t('your.new.key')` in Blade files
3. Clear cache: `php artisan cache:clear`

## Common Patterns

### Status Labels
```php
{{ t('rentals.statuses.' . $rental->status) }}
```

### Dynamic Plurals
```php
{{ trans_choice('products.items', $count) }}
```

### With Parameters
```php
{{ t('messages.welcome_back', ['name' => $user->name]) }}
```
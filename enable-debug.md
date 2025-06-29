# How to Enable Debug Mode on Production (Hostinger)

⚠️ **WARNING**: Only enable debug mode temporarily for troubleshooting. Debug mode exposes sensitive information!

## Method 1: Via File Manager (Hostinger Panel)

1. Login to your Hostinger control panel
2. Go to **File Manager**
3. Navigate to your project root directory
4. Find and edit the `.env` file
5. Change these values:
   ```
   APP_ENV=local
   APP_DEBUG=true
   ```
6. Save the file
7. Clear cache (important!):
   - Via SSH: `php artisan config:clear`
   - Or delete the file: `bootstrap/cache/config.php`

## Method 2: Via SSH

```bash
# Connect to your server
ssh u976886556@your-server-ip

# Navigate to your project
cd rentro

# Edit .env file
nano .env
# or
vim .env

# Change these lines:
APP_ENV=local
APP_DEBUG=true

# Save and exit (Ctrl+X, then Y for nano)

# Clear configuration cache
php artisan config:clear
php artisan cache:clear
```

## Method 3: Create a Temporary Debug Route

Add this to `routes/web.php`:

```php
Route::get('/temp-debug-enable', function() {
    if (request()->get('key') !== 'your-secret-key') {
        abort(404);
    }
    
    Artisan::call('config:clear');
    
    // Temporarily set debug mode
    config(['app.debug' => true]);
    config(['app.env' => 'local']);
    
    return 'Debug mode temporarily enabled for this session';
});
```

## To Disable Debug Mode (IMPORTANT!)

After troubleshooting, immediately:

1. Change back in `.env`:
   ```
   APP_ENV=production
   APP_DEBUG=false
   ```

2. Clear cache:
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

## Alternative: Check Laravel Logs

Instead of enabling debug mode, check the error logs:

```bash
# Via SSH
tail -f storage/logs/laravel.log

# Or download via File Manager:
# /storage/logs/laravel.log
```

## Security Notes

- **NEVER** leave debug mode on in production
- Debug mode exposes:
  - Database credentials
  - API keys
  - File paths
  - Stack traces
  - Environment variables
- Always disable it after troubleshooting
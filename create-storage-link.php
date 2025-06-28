<?php
/**
 * Manual storage link creator for Hostinger
 * Run this script in browser or CLI to create storage link
 */

$targetFolder = __DIR__ . '/storage/app/public';
$linkFolder = __DIR__ . '/public/storage';

// Remove existing link/directory if exists
if (is_link($linkFolder)) {
    unlink($linkFolder);
} elseif (is_dir($linkFolder)) {
    rmdir($linkFolder);
}

// Try to create symlink
if (@symlink($targetFolder, $linkFolder)) {
    echo "Storage link created successfully!\n";
} else {
    // If symlink fails, try alternative methods
    echo "Symlink failed. Creating .htaccess redirect instead...\n";
    
    // Create directory
    if (!is_dir($linkFolder)) {
        mkdir($linkFolder, 0755, true);
    }
    
    // Create .htaccess for redirect
    $htaccess = <<<'HTACCESS'
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ /storage/app/public/$1 [L]
</IfModule>
HTACCESS;
    
    file_put_contents($linkFolder . '/.htaccess', $htaccess);
    echo "Created .htaccess redirect in public/storage\n";
}

echo "Done!\n";
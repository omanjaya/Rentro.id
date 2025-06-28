<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class FileUploadService
{
    /**
     * Upload single image file
     */
    public function uploadImage(UploadedFile $file, string $directory = 'products'): string
    {
        $filename = $this->generateUniqueFilename($file);
        $path = $directory . '/' . $filename;
        
        // Create directory if it doesn't exist
        Storage::disk('public')->makeDirectory($directory);
        
        // Store original file
        $file->storeAs($directory, $filename, 'public');
        
        // Create thumbnail if image
        if ($this->isImage($file)) {
            $this->createThumbnail($file, $directory, $filename);
        }
        
        return $path;
    }
    
    /**
     * Upload multiple image files
     */
    public function uploadMultipleImages(array $files, string $directory = 'products'): array
    {
        $uploadedPaths = [];
        
        foreach ($files as $file) {
            if ($file instanceof UploadedFile) {
                $uploadedPaths[] = $this->uploadImage($file, $directory);
            }
        }
        
        return $uploadedPaths;
    }
    
    /**
     * Delete image file
     */
    public function deleteImage(string $path): bool
    {
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
            
            // Delete thumbnail
            $thumbnailPath = $this->getThumbnailPath($path);
            if (Storage::disk('public')->exists($thumbnailPath)) {
                Storage::disk('public')->delete($thumbnailPath);
            }
            
            return true;
        }
        
        return false;
    }
    
    /**
     * Delete multiple image files
     */
    public function deleteMultipleImages(array $paths): void
    {
        foreach ($paths as $path) {
            $this->deleteImage($path);
        }
    }
    
    /**
     * Generate unique filename
     */
    private function generateUniqueFilename(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();
        $name = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        $timestamp = now()->format('YmdHis');
        $random = Str::random(6);
        
        return "{$name}_{$timestamp}_{$random}.{$extension}";
    }
    
    /**
     * Check if file is an image
     */
    private function isImage(UploadedFile $file): bool
    {
        return in_array($file->getMimeType(), [
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/webp'
        ]);
    }
    
    /**
     * Create thumbnail for image
     */
    private function createThumbnail(UploadedFile $file, string $directory, string $filename): void
    {
        $thumbnailDirectory = $directory . '/thumbnails';
        Storage::disk('public')->makeDirectory($thumbnailDirectory);
        
        $thumbnailPath = storage_path('app/public/' . $thumbnailDirectory . '/' . $filename);
        
        // Create thumbnail (300x300) using Intervention Image v3
        $manager = new ImageManager(new Driver());
        $image = $manager->read($file->getRealPath());
        $image->cover(300, 300);
        $image->save($thumbnailPath);
    }
    
    /**
     * Get thumbnail path
     */
    private function getThumbnailPath(string $originalPath): string
    {
        $pathInfo = pathinfo($originalPath);
        return $pathInfo['dirname'] . '/thumbnails/' . $pathInfo['basename'];
    }
    
    /**
     * Get full URL for uploaded file
     */
    public function getFileUrl(string $path): string
    {
        return Storage::disk('public')->url($path);
    }
    
    /**
     * Get thumbnail URL for uploaded file
     */
    public function getThumbnailUrl(string $path): string
    {
        $thumbnailPath = $this->getThumbnailPath($path);
        return Storage::disk('public')->url($thumbnailPath);
    }
}
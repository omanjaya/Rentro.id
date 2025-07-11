<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'vendor_id', 'name', 'slug', 'description', 'specifications',
        'price_per_day', 'vendor_price', 'stock', 'image', 'gallery', 'status',
        'listing_status', 'rejection_reason', 'approved_at', 'featured'
    ];

    protected $casts = [
        'specifications' => 'array',
        'gallery' => 'array',
        'price_per_day' => 'decimal:2',
        'vendor_price' => 'decimal:2',
        'approved_at' => 'datetime',
        'featured' => 'boolean'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($product) {
            $product->slug = Str::slug($product->name);
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }

    // Performance optimization: Scope for active products
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Marketplace scopes
    public function scopeApproved($query)
    {
        return $query->where('listing_status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('listing_status', 'pending');
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    public function scopeByVendor($query, $vendorId)
    {
        return $query->where('vendor_id', $vendorId);
    }

    // Check if product is platform-owned (no vendor)
    public function isPlatformProduct()
    {
        return is_null($this->vendor_id);
    }

    // Get commission amount for a rental price
    public function getCommissionAmount($rentalPrice)
    {
        if ($this->isPlatformProduct()) {
            return 0; // No commission for platform products
        }
        
        $vendor = $this->vendor;
        $commissionRate = $vendor->commission_rate ?? 15.00;
        return $rentalPrice * ($commissionRate / 100);
    }

    // Cache frequently accessed data
    public function getPriceFormattedAttribute()
    {
        return 'Rp ' . number_format($this->price_per_day);
    }

    public function isAvailable($startDate, $endDate)
    {
        $conflictingRentals = $this->rentals()
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                      ->orWhereBetween('end_date', [$startDate, $endDate])
                      ->orWhere(function ($q) use ($startDate, $endDate) {
                          $q->where('start_date', '<=', $startDate)
                            ->where('end_date', '>=', $endDate);
                      });
            })->count();

        return $conflictingRentals < $this->stock;
    }

    public function getImageUrlAttribute()
    {
        // If image starts with http/https, return as-is (external URL)
        if ($this->image && filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }
        
        // If local storage image exists
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        
        // Fallback to a better default image based on category
        $defaultImages = [
            1 => 'https://images.unsplash.com/photo-1541807084-5c52b6b3adef?w=500&h=300&fit=crop', // Laptop
            2 => 'https://images.unsplash.com/photo-1606983340126-99ab4feaa64a?w=500&h=300&fit=crop', // Camera
            3 => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=500&h=300&fit=crop', // Audio
            4 => 'https://images.unsplash.com/photo-1606144042614-b2417e99c4e3?w=500&h=300&fit=crop', // Gaming
            5 => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=500&h=300&fit=crop', // Mobile
        ];
        
        return $defaultImages[$this->category_id] ?? 'https://images.unsplash.com/photo-1560472354-b33ff0c44a43?w=500&h=300&fit=crop';
    }

    public function getGalleryUrlsAttribute()
    {
        if (!$this->gallery || !is_array($this->gallery)) {
            return [];
        }

        return collect($this->gallery)->map(function ($image) {
            // Check if it's already a full URL (external image like Unsplash)
            if (filter_var($image, FILTER_VALIDATE_URL)) {
                return $image;
            }

            // If it's a local file path, use Laravel's asset helper
            return asset('storage/' . $image);
        })->toArray();
    }
}
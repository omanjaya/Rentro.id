<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'name', 'email', 'password', 'phone', 'address', 'user_type', 'status', 'avatar',
        'business_name', 'business_license', 'business_description', 'verification_status',
        'verified_at', 'verification_notes', 'commission_rate', 'featured_vendor'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'verified_at' => 'datetime',
            'password' => 'hashed',
            'commission_rate' => 'decimal:2',
            'featured_vendor' => 'boolean',
        ];
    }

    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }

    public function vendorProducts()
    {
        return $this->hasMany(Product::class, 'vendor_id');
    }

    public function isAdmin()
    {
        return $this->user_type === 'admin';
    }

    public function isVendor()
    {
        return $this->user_type === 'vendor';
    }

    public function isBusiness()
    {
        return $this->user_type === 'business';
    }

    public function isIndividual()
    {
        return $this->user_type === 'individual';
    }

    public function isVerified()
    {
        return $this->verification_status === 'verified';
    }

    public function isPendingVerification()
    {
        return $this->verification_status === 'pending';
    }

    public function isActive()
    {
        return $this->status === 'active';
    }

    public function isInactive()
    {
        return $this->status === 'inactive';
    }

    public function activate()
    {
        $this->status = 'active';
        return $this->save();
    }

    public function deactivate()
    {
        $this->status = 'inactive';
        return $this->save();
    }
}

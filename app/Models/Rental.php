<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Rental extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'product_id', 'rental_code', 'start_date', 'end_date',
        'total_days', 'price_per_day', 'total_price', 'status', 'notes'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'price_per_day' => 'decimal:2',
        'total_price' => 'decimal:2'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($rental) {
            $rental->rental_code = 'RNT' . strtoupper(uniqid());
            $rental->total_days = Carbon::parse($rental->start_date)
                ->diffInDays(Carbon::parse($rental->end_date)) + 1;
            $rental->total_price = $rental->total_days * $rental->price_per_day;
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
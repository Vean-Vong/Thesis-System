<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $table = 'sales';

    protected $fillable = [
        'customer_id',
        'stock_id',
        'model',
        'price',
        'deposit',
        'discount',
        'date',
        'duration',
        'warranty',
        'seller',
        'contract_type',
        'quantity_sold'
    ];

    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    // public function products()
    // {
    //     return $this->belongsTo(Product::class);
    // }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'sale_products')
            ->withPivot('quantity', 'price')
            ->withTimestamps();
    }


    /**
     * Accessor for formatted price (if stored as a string)
     */
    public function getFormattedPriceAttribute()
    {
        return '$' . number_format($this->price, 2);
    }

    /**
     * Accessor for formatted discount (e.g., "10%")
     */
    public function getFormattedDiscountAttribute()
    {
        return $this->discount . '%';
    }
    protected $casts = [
        'date' => 'date:d-m-Y', // Automatically formats the date
    ];
}

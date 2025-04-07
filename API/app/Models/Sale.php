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
        'stock_id',
        'model',
        'price',
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

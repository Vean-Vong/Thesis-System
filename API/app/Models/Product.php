<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'model',
        'colors',
        'filtration_stage',
        'cold_water_tank_capacity',
        'hot_water_tank_capacity',
        'heating_capacity',
        'cooling_capacity',
        'cold_power_consumption',
        'hot_power_consumption',
        'quantity',
        'images',
        'price',
        'rental_price',
        'install_price',
    ];

    protected $casts = [
        'images' => 'array',  // Important for JSON cast
    ];

    public function stocks()
    {
        // Connect Product to Stock via "model" field
        return $this->hasMany(Stock::class, 'model', 'model');
    }

    // public function sales()
    // {
    //     return $this->hasMany(Sale::class);
    // }

    // Add an accessor for total stock quantity
    public function getTotalStockAttribute()
    {
        return $this->stocks()
            ->where('colors', $this->colors)
            ->sum('quantity');
    }
    // public function getStockStatusAttribute()
    // {
    //     $total = $this->total_stock;
    //     return $total > 0 ? $total : 'Out of stock';
    // }
    public function sales()
    {
        return $this->belongsToMany(Sale::class, 'sale_products')
            ->withPivot('quantity', 'price')
            ->withTimestamps();
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    use HasFactory;

    protected $table = 'rentals';


    protected $fillable = [
        'customer_id',
        'model',
        'price',
        'deposit',
        'discount',
        'date',
        'duration',
        'warranty',
        'seller',
        'contract_type',
        'invoice_number',
    ];

    public function payments()
    {
        return $this->hasMany(\App\Models\Payment::class);
    }
    public function customer()
    {
        return $this->belongsTo(\App\Models\Customer::class);
    }
    public function products()
    {
        return $this->belongsToMany(Product::class, 'rental_products')
            ->withPivot('quantity', 'rental_price')
            ->withTimestamps();
    }
    public function sale()
    {
        return $this->belongsTo(\App\Models\Sale::class);
    }
}

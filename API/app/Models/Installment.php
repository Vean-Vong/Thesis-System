<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Installment extends Model
{
    protected $fillable = [
        'sale_id',
        'customer_id',
        'seller_id',
        'deposit',
        'sale_date',
        'total_price',
        'install_price',
        'monthly_fee',
        'paid_amount',
    ];

    public function sale()
    {
        return $this->belongsTo(\App\Models\Sale::class);
    }


    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id', 'id');
    }
    public function payments()
    {
        return $this->hasMany(\App\Models\Payment::class);
    }
    public function products()
    {
        return $this->belongsToMany(Product::class, 'rental_products')
            ->withPivot('quantity', 'install_price')
            ->withTimestamps();
    }
}
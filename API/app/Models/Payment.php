<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'installment_id',
        'rental_id',
        'customer_id',
        'seller_id',
        'amount',
        'payment_date',
        'quantity_paid',
        'note',
    ];

    public function installment()
    {
        return $this->belongsTo(\App\Models\Installment::class);
    }
    public function rental()
    {
        return $this->belongsTo(\App\Models\rental::class);
    }

    public function customer()
    {
        return $this->belongsTo(\App\Models\Customer::class);
    }

    public function seller()
    {
        return $this->belongsTo(\App\Models\User::class, 'seller_id', 'id');
    }
}

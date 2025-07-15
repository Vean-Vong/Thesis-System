<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'model',
        'quantity',
        'date',
        'colors',
        'supplier'
    ];


    public function product()
    {
        // Connect Stock to Product via "model" field
        return $this->belongsTo(Product::class, 'model', 'model');
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}

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
        'supplier'
    ];

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}
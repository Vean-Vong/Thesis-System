<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    use HasFactory;

    protected $table = 'rentals';


    protected $fillable = [
        'model',
        'price',
        'discount',
        'date',
        'duration',
        'warranty',
        'seller',
        'contract_type',
    ];
}
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
        'image',
    ];
}

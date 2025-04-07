<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Filter extends Model
{
    use HasFactory;

    protected $table = 'filters';

    protected $fillable = [
        'sediment',
        'pre_carbon',
        'uf_membrane',
        'post_carbon',
        'date',
    ];

    protected $casts = [
        'sediment' => 'integer',
        'pre_carbon' => 'integer',
        'uf_membrane' => 'integer',
        'post_carbon' => 'integer',
    ];
}

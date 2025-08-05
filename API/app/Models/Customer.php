<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'customers';

    protected $fillable = ['name', 'address', 'phone', 'date', 'job'];

    protected $casts = [
        'date' => 'datetime:Y-m-d',
    ];

    public function sales()
    {
        return $this->hasMany(\App\Models\Sale::class);
    }

    public function rentals()
    {
        return $this->hasMany(\App\Models\Rental::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status'
    ];

    public function setStatusAttribute($value)
    {
        $this->attributes['status'] = $value === true ?  1 : 0;
    }

    public function getStatusLabelAttribute()
    {
        return $this->status === 1 ? 'Active' : 'Inactive';
    }
}
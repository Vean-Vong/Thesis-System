<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'status',
    ];
    public function setStatusAttribute($value)
    {
        if ($value === true) {
            $this->attributes['status']  = 1;
        } else {
            $this->attributes['status'] = 0;
        }
    }
    public function getStatusLabelAttribute()
    {
        if ($this->status === 1) {
            return 'Active';
        } else {
            return 'Inactive';
        }
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class);
    }
}

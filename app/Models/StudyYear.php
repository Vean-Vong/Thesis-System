<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudyYear extends Model
{
    use HasFactory;
    public function setStatusAttribute($value)
    {
        $this->attributes['status'] = $value === true ?  1 : 0;
    }
    public function getStatusLabelAttribute()
    {
        return $this->status === 1 ? 'Active' : 'Inactive';
    }
}

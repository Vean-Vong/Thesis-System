<?php

namespace App\Models;

use App\Traits\CreatedUpdatedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcademicYear extends Model
{
    use HasFactory, SoftDeletes, CreatedUpdatedBy;

    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'is_active'
    ];

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query
                    ->where('name', 'like', '%' . $search . '%');
            });
        });
    }

    public function setIsActiveAttribute($value)
    {
        if($value == true) {
            $academic_year = AcademicYear::where('is_active', 1)->first();
            if($academic_year->is_active ?? null) {
                $academic_year->is_active = 0;
                $academic_year->save();
            }
        };
        $this->attributes['is_active'] = $value;

    }


}

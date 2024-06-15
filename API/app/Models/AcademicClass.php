<?php

namespace App\Models;

use App\Traits\CreatedUpdatedBy;
use App\Traits\UUIDs;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcademicClass extends Model
{
    use HasFactory, CreatedUpdatedBy, UUIDs, SoftDeletes;

    protected $fillable = [
        'name',
        'type',
        'teacher_id',
        'room_id',
        'time_id',
        'level_id',
        'academic_year_id',
    ];

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query
                    ->where('name', 'like', '%' . $search . '%');
            });
        })->when($filters['academic_year_id'] ?? null, function ($query, $academic_year_id) {
            $query->whereAcademicYearId($academic_year_id);
        });
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function studies()
    {
        return $this->hasMany(Study::class);
    }

}

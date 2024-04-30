<?php

namespace App\Models;

use App\Traits\CreatedUpdatedBy;
use App\Traits\UUIDs;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Study extends Model
{
    use HasFactory, UUIDs, CreatedUpdatedBy, SoftDeletes;

    protected $fillable = [
        'academic_class_id',
        'student_id'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function academicClass()
    {
        return $this->belongsTo(AcademicClass::class);
    }

}

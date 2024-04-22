<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Study extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'study_class_id',
        'comment',
        'status'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function studyClass()
    {
        return $this->belongsTo(StudyClass::class);
    }
}

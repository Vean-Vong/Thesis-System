<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    use HasFactory;

    protected $fillable = [
        'study_class_id',
        'student_id',
        'semester',
        'type',
    ];

    public function scoreDetails()
    {
        return $this->hasMany(ScoreDetail::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

}

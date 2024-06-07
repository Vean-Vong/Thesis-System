<?php

namespace App\Models;

use App\Traits\CreatedUpdatedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exam extends Model
{
    use HasFactory, CreatedUpdatedBy, SoftDeletes;

    protected $fillable = [
        'academic_class_id',
        'student_id',
        'att',
        'quiz',
        'hw',
        're',
        'voc',
        'gr',
        'liu',
        'wr',
        'li',
        'sp',
        'mid',
        'attitude',
        'final',
        'total',
    ];
}

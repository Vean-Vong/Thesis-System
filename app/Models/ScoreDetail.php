<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScoreDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'score_id',
        'subject_id',
        'score',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}

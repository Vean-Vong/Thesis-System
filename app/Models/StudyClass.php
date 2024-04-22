<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudyClass extends Model
{
    use HasFactory;

    public function subjects()
    {
        return $this->belongsToMany(Subject::class);
    }

    public function studyYear()
    {
        return $this->belongsTo(StudyYear::class);
    }

    public function studies()
    {
        return $this->hasMany(Study::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

}

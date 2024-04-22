<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'first_name', 'last_name', 'first_name_latin', 'last_name_latin', 'gender', 'dob',
        'school_name', 'school_code', 'village', 'commune', 'district', 'province', 'phone', 'photo_path', 'status'
    ];

    protected $table = 'teachers';

    protected $appends = ['full_name'];

    public function getStatusLabelAttribute()
    {
        if ($this->status === 1) {
            return 'Active';
        } else {
            return 'Inactive';
        };
    }
    public function getGenderLabelAttribute()
    {
        if ($this->gender === 1) {
            return "ប្រុស";
        } else {
            return "ស្រី";
        }
    }
    public function getFullNameAttribute()
    {
        return $this->last_name . ' ' . $this->first_name;
    }
    public function getFullNameLatinAttribute()
    {
        return $this->last_name_latin . ' ' . $this->first_name_latin;
    }
}

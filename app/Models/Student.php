<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'first_name',
        'last_name',
        'first_name_latin',
        'last_name_latin',
        'gender',
        'dob',
        'village_birth',
        'commune_birth',
        'district_birth',
        'province_birth',
        'village',
        'commune',
        'district',
        'province',
        'd_first_name',
        'd_last_name',
        'd_job',
        'd_phone_number',
        'm_first_name',
        'm_last_name',
        'm_job',
        'm_phone_number',
        'phone',
        'student_status',
        'from',
        'photo_path',
        'other',
        'status',
        'g_first_name',
        'g_last_name',
        'g_job',
        'g_gender',
        'g_phone_number',
        'g_detail',
    ];

    public function getFullNameAttribute()
    {
        return $this->last_name . ' ' . $this->first_name;
    }
    public function getFullNameLatinAttribute()
    {
        return $this->last_name_latin . ' ' . $this->first_name_latin;
    }
    public function getMotherFullNameAttribute()
    {
        return $this->m_last_name . ' ' . $this->m_first_name;
    }
    public function getFatherFullNameAttribute()
    {
        return $this->d_last_name . ' ' . $this->d_first_name;
    }
    public function getGuardianFullNameAttribute()
    {
        return $this->g_last_name . ' ' . $this->g_first_name;
    }
    public function getStudentStatusLabelAttribute()
    {
        if ($this->student_status === 1) {
            return 'កំព្រាឳពុក';
        } else if ($this->student_status === 2) {
            return 'កំព្រាម្តាយ';
        } else if ($this->student_status === 3) {
            return 'កំព្រាឳពុក ម្តាយ';
        } else if ($this->student_status === 4) {
            return 'ពិការ';
        } else {
            return '';
        }
    }

    public function getGenderLabelAttribute()
    {
        return $this->gender === 1 ? 'ប្រុស' : 'ស្រី';
    }
    public function getGuardianGenderLabelAttribute()
    {
        return $this->g_gender === 1 ? 'ប្រុស' : 'ស្រី';
    }
    public function getStatusLabelAttribute()
    {
        return $this->status === 1 ? 'Active' : 'Inactive';
    }

    public function setPhotoPathAttribute($value)
    {
        if (isset($value)) {
            if ($this->photo_path) Storage::disk('public')->delete($this->photo_path);
            $path = $value->store('student/' . date('FY'), ['disk' => 'public']);
            $this->attributes['photo_path'] = $path;
        }
    }

    public static function boot()
    {
        parent::boot();
        static::deleting(function ($item) {
            if ($item->photo_path) Storage::disk('public')->delete($item->photo_path);
        });
    }
}

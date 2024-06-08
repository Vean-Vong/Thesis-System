<?php

namespace App\Models;

use App\Traits\UUIDs;
use App\Traits\CreatedUpdatedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory, UUIDs, CreatedUpdatedBy, SoftDeletes;

    protected $appends = [
        'sex_text',
    ];

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

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('code', 'like', '%' . $search . '%')
                    ->orWhere('first_name', 'like', '%' . $search . '%')
                    ->orWhere('last_name', 'like', '%' . $search . '%')
                    ->orWhere('first_name_latin', 'like', '%' . $search . '%')
                    ->orWhere('last_name_latin', 'like', '%' . $search . '%')
                    ->orWhere('phone', 'like', '%' . $search . '%')
                    ->orWhere('d_first_name', 'like', '%' . $search . '%')
                    ->orWhere('d_last_name', 'like', '%' . $search . '%')
                    ->orWhere('m_last_name', 'like', '%' . $search . '%')
                    ->orWhere('m_first_name', 'like', '%' . $search . '%');
            });
        });
    }

    public function getSexTextAttribute()
    {
        return $this->gender == 1 ? 'ប្រុស' : 'ស្រី';
    }
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

    public function getStatusLabelAttribute()
    {
        return $this->status === 1 ? 'Active' : 'Inactive';
    }

    public function setPhotoPathAttribute($value)
    {
        if (isset($value)) {
            // remove old avatar on update
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

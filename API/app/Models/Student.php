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
        'school_id',
        'code',
        'name',
        'name_latin',
        'sex',
        'dob',
        'photo_path',
        'pob',
        'address',
        'dad_name',
        'dad_phone',
        'mom_name',
        'mom_phone'
    ];

    public function scopeMine($query)
    {
        return $query->where('school_id', auth()->user()->school_id);
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('code', 'like', '%' . $search . '%')
                    ->orWhere('name', 'like', '%' . $search . '%')
                    ->orWhere('name_latin', 'like', '%' . $search . '%')
                    ->orWhere('dad_name', 'like', '%' . $search . '%')
                    ->orWhere('dad_phone', 'like', '%' . $search . '%')
                    ->orWhere('mom_name', 'like', '%' . $search . '%')
                    ->orWhere('mom_phone', 'like', '%' . $search . '%');
            });
        });
    }

    public function getSexTextAttribute()
    {
        return $this->sex == 1 ? 'ប្រុស' : 'ស្រី';
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

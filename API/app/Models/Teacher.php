<?php

namespace App\Models;

use App\Traits\UUIDs;
use App\Traits\CreatedUpdatedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Teacher extends Model
{
    use HasFactory, UUIDs, CreatedUpdatedBy, SoftDeletes;

    protected $appends = [
        'sex_text',
        'position_text'
    ];

    protected $fillable = [
        'code',
        'name',
        'name_latin',
        'sex',
        'dob',
        'pob',
        'photo_path',
        'position',
        'address',
        'phone',
    ];

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('code', 'like', '%' . $search . '%')
                    ->orWhere('name', 'like', '%' . $search . '%')
                    ->orWhere('name_latin', 'like', '%' . $search . '%')
                    ->orWhere('phone', 'like', '%' . $search . '%');
            });
        });
    }

    public function getSexTextAttribute()
    {
        return $this->sex == 1 ? 'ប្រុស' : 'ស្រី';
    }

    public function getPositionTextAttribute()
    {
        if($this->position == 1) {
            return 'គ្រូបង្រៀន';
        } else if($this->position == 2) {
            return 'នាយករង';
        } else {
            return 'នាយក';
        }
    }

    public function setPhotoPathAttribute($value)
    {
        if (isset($value)) {
            // remove old avatar on update
            if ($this->photo_path) Storage::disk('public')->delete($this->photo_path);
            $path = $value->store('teacher/' . date('FY'), ['disk' => 'public']);
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

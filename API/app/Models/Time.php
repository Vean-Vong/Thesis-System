<?php

namespace App\Models;

use App\Traits\CreatedUpdatedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Time extends Model
{
    use HasFactory, CreatedUpdatedBy;

    protected $fillable = [
        'time'
    ];

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('time', 'like', '%' . $search . '%');
            });
        });
    }

}

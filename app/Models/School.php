<?php

namespace App\Models;

use App\Traits\CreatedUpdatedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class School extends Model
{
    use HasFactory, CreatedUpdatedBy, SoftDeletes;

    protected $fillable = [
        'code',
        'khmer_name',
        'latin_name',
        'short_name',
        'sort',
    ];

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query
                ->where('code', 'like', '%' . $search . '%')
                ->orWhere('khmer_name', 'like', '%' . $search . '%')
                ->orWhere('short_name', 'like', '%' . $search . '%');
        });
    }
}

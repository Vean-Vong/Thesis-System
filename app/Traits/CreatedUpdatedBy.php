<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

Trait CreatedUpdatedBy
{
    public static function bootCreatedUpdatedBy()
    {

        static::creating(function ($model) {
            if (!$model->isDirty('created_by')) {
                $model->created_by = Auth::user()->id;
            }
        });
        static::updating(function ($model) {
            if (!$model->isDirty('updated_by')) {
                $model->updated_by = Auth::user()->id;
            }
        });
    }
}

<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $table = 'employees';


    protected $fillable = ['name', 'gender', 'position', 'email', 'phone', 'address', 'date_of_birth', 'hire_date',];


    // Format dates when accessing them
    public function getHireDateAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y'); // Change format as needed
    }

    public function getDateOfBirthAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y'); // Example: "25-03-2025"
    }
}
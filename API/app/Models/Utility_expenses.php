<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Utility_expenses extends Model
{
    use HasFactory;


    protected $table = 'utility_expenses';
    protected $fillable = ['type', 'bill_date', 'usage_amount', 'cost', 'unit_rate'];
}

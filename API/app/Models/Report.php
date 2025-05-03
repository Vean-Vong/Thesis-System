<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $table = 'reports';

    protected $fillable = [
        'customer_name',
        'invoice_number',
        'unit',
        'cash_on_hand',
        'cash_on_bank',
        'date',
        'remark',
    ];
}

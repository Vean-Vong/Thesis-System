<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'invoice_date',
        'subtotal',
        'tax',
        'total',
    ];

    public function utilityExpenses()
    {
        return $this->belongsToMany(Utility_expenses::class, 'invoice_utility_expense', 'invoice_id', 'utility_expense_id');
    }
}
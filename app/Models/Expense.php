<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;
    protected $fillable = [
        'section',
        'sub_service',
        'bill_no',
        'supplier',
        'amount',
        'paid_amount',
        'pending_amount',
        'expense_description',
        'expense_date'
    ];
}

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
        'expense_description',
        'expense_date'
    ];

    public function payments(){
        return $this->hasMany(ExpensesPayment::class,'expense_id','id');
    }

    public function department_main(){
        return $this->belongsTo(DepartmentExpenses::class,'section','id');
    }

    public function department_sub(){
        return $this->belongsTo(DepartmentExpenses::class,'sub_service','id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeePricesChange extends Model
{
    use HasFactory;

    protected $fillable = ['employee_id','amount','change_date'];
}

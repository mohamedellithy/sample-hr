<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeSale extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id','remained','sale_date','status'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}

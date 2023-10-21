<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeSalarie extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'employee_id','days',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}

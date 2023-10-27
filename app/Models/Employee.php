<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected $fillable = [
        'name','nationality','salary','passport_no','birthday','passport_expiry','join_date',
    ];

    public function advances()
    {
        return $this->hasMany(EmployeeAdvance::class);
    }

    public function sales()
    {
        return $this->hasMany(EmployeeSale::class);
    }

}

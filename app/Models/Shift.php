<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id','date',"clock_in","clock_out"
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}

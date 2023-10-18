<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected $fillable = [
        'name','nationality','salary','hour','passport_no','birthday','passport_expiry','card_expiry','join_date',
    ];

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MoneyResource extends Model
{
    use HasFactory;

    protected $fillable = [
        'value',
        'type',
        'description',
        'resource_date',
        'reference_id'
    ];


}

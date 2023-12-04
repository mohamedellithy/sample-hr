<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartmentExpenses extends Model
{
    use HasFactory;

    protected $fillable = ['department_name','parent_id'];

    public function department_parent(){
        return $this->belongsTo(DepartmentExpenses::class,'parent_id','id');
    }

    public function child_sections(){
        return $this->hasMany(DepartmentExpenses::class,'parent_id','id');
    }

}

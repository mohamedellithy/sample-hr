<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesReturn extends Model
{
    use HasFactory;
    protected $fillable = ['qty', 'price','product_id','order_id'];
      public function product()
    {
        return $this->belongsTo(Product::class);
    }
     public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientSale extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id','amount','paid','remained','sale_date',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}

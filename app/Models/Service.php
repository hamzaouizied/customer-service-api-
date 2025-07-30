<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = ['customer_id', 'name', 'description'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}

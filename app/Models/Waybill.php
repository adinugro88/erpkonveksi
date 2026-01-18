<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Waybill extends Model
{
    protected $guarded = ['id'];

    public function details()
    {
        return $this->hasMany(WaybillDetail::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}

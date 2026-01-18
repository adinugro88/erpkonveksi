<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WaybillDetail extends Model
{
    protected $guarded = ['id'];

    public function waybill()
    {
        return $this->belongsTo(Waybill::class);
    }
}

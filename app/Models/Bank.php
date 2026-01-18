<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Invoice;

class Bank extends Model
{
    protected $guarded = ['id'];

    public function Invoice()
    {
        return $this->hasMany(Invoice::class, 'bank_id', 'id');
    }
}

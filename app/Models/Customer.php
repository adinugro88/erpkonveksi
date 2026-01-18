<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Job_cust;
use App\Models\Invoice;

class Customer extends Model
{
    protected $guarded = ['id'];

    public function JobCust()
    {
        return $this->belongsTo(Job_cust::class);
    }

    public function Invoice()
    {
        return $this->hasMany(Invoice::class, 'customer_id', 'id');
    }
}

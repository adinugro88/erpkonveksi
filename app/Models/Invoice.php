<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\InvoiceDetail;
use App\Models\Customer;
use App\Models\Bank;

class Invoice extends Model
{
    protected $guarded = ['id'];
    
    public function details()
    {
        return $this->hasMany(InvoiceDetail::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class , 'bank_id', 'id');
    }
}

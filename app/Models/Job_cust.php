<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Job_cust extends Model
{
  
    protected $guarded = ['id'];

    public function Customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function mastersetortariks()
    {
        return $this->hasMany(Mastersetortarik::class, 'jobcust_id');
    }

    public function karjobs()
    {
        return $this->hasMany(Karjobs::class, 'jobcust_id');
    }

    public function suratJalans()
    {
        return $this->hasMany(SuratJalan::class, 'job_cust_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratJalan extends Model
{
    protected $guarded = ['id'];

    public function jobCust()
    {
        return $this->belongsTo(Job_cust::class, 'job_cust_id');
    }

    public function details()
    {
        return $this->hasMany(SuratJalanDetail::class, 'surat_jalan_id');
    }
}

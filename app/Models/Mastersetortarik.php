<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mastersetortarik extends Model
{
    protected $fillable = [
        'karyawan_id',
        'jobcust_id'
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id');
    }

    public function jobCust()
    {
        return $this->belongsTo(Job_cust::class, 'jobcust_id');
    }

    public function setortariks()
    {
        return $this->hasMany(Setortarik::class, 'mastersetortarik_id');
    }
}

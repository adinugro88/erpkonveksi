<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Karjobs extends Model
{
    protected $guarded = ['id'];

    //relasi ke job_custs
    public function jobcust()
    {
        return $this->belongsTo(Job_cust::class, 'jobcust_id', 'id');
    }

    //relasi ke setortariks via pivot
    public function setortariks()
    {
        return $this->belongsToMany(Setortarik::class, 'setortarikpvts', 'karjobs_id', 'setortarik_id');
    }
}

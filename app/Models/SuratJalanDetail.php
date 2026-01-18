<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratJalanDetail extends Model
{
    protected $guarded = ['id'];

    public function suratJalan()
    {
        return $this->belongsTo(SuratJalan::class, 'surat_jalan_id');
    }
}

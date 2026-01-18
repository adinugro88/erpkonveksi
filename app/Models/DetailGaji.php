<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailGaji extends Model
{
    protected $table = 'detail_gajis';
    protected $guarded = ['id'];

    public function gaji()
    {
        return $this->belongsTo(Gaji::class);
    }

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }
}

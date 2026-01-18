<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setortarikdtl extends Model
{
    protected $fillable = [
        'barang',
        'jumlah',
        'keterangan',
        'setortarik_id'
    ];

    public function setortarik()
    {
        return $this->belongsTo(Setortarik::class, 'setortarik_id');
    }

    public function detailGajis()
    {
        return $this->hasMany(DetailGaji::class, 'setortarikdtl_id');
    }
}

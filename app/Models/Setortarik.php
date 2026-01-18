<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setortarik extends Model
{
    protected $fillable = [
        'kode_setortarik',
        'tanggal',
        'jenis',
        'keterangan',
        'photo1',
        'photo2',
        'photo3',
        'mastersetortarik_id',
        'parent_id'
    ];

    public function mastersetortarik()
    {
        return $this->belongsTo(Mastersetortarik::class, 'mastersetortarik_id');
    }

    public function details()
    {
        return $this->hasMany(Setortarikdtl::class, 'setortarik_id');
    }

    public function karjobs()
    {
        return $this->belongsToMany(Karjobs::class, 'setortarikpvts', 'setortarik_id', 'karjobs_id');
    }

    // Parent ambil transaction (for setor)
    public function parentAmbil()
    {
        return $this->belongsTo(Setortarik::class, 'parent_id');
    }

    // Child setor transactions (for ambil)
    public function setorTransactions()
    {
        return $this->hasMany(Setortarik::class, 'parent_id');
    }

    // Scope for ambil only
    public function scopeAmbilOnly($query)
    {
        return $query->where('jenis', 'tarik')->whereNull('parent_id');
    }

    // Scope for setor only
    public function scopeSetorOnly($query)
    {
        return $query->where('jenis', 'setor')->whereNotNull('parent_id');
    }
}

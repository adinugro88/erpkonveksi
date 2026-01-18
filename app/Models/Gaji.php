<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gaji extends Model
{
    protected $guarded = ['id'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            do {
                $randomNumber = str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);

                $newCode = 'GJN-' . $randomNumber;
            } while (static::where('no_kode', $newCode)->exists());

            $model->no_kode = $newCode;
        });
    }

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class)->withDefault([
            'nama' => 'Karyawan Tidak Ditemukan',
            'nip' => '-'
        ]);
    }

    public function details()
    {
        return $this->hasMany(DetailGaji::class, 'gaji_id');
    }
}

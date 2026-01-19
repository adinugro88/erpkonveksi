<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'alamat',
        'no_hp',
        'email',
        'status',
        'nip',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            do {
                $randomChar = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 5);

                $newCode = 'NIP-' . $randomChar . "-" . now()->timestamp;
                // dd($newCode);
            } while (static::where('nip', $newCode)->exists());

            $model->nip = $newCode;
        });
    }

    public function gaji()
    {
        return $this->hasMany(Gaji::class, 'karyawan_id');
    }

    public function mastersetortariks()
    {
        return $this->hasMany(Mastersetortarik::class, 'karyawan_id');
    }
    
}

<?php

namespace App\Livewire\Karyawan;

use Livewire\Component;
use App\Models\Karyawan;

class Edit extends Component
{
    public $nama,$nip,$alamat,$email,$no_hp,$status;
    public $id;
    public function mount($id)
    {
        $this->id = $id;
        $this->karyawan = Karyawan::find($id);
        $this->nama = $this->karyawan->nama;
        $this->nip = $this->karyawan->nip;
        $this->alamat = $this->karyawan->alamat;
        $this->email = $this->karyawan->email;
        $this->no_hp = $this->karyawan->no_hp;
        $this->status = $this->karyawan->status;
    }

    public function update()
    {
        $this->karyawan->nama = $this->nama;
        $this->karyawan->nip = $this->nip;
        $this->karyawan->alamat = $this->alamat;
        $this->karyawan->email = $this->email;
        $this->karyawan->no_hp = $this->no_hp;
        $this->karyawan->status = $this->status;
        $this->karyawan->save();
        $this->redi('karyawan');

    }


    public function render()
    {
        return view('livewire.karyawan.edit');
    }
}

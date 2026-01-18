<?php

namespace App\Livewire\Karyawan;

use Livewire\Component;
use App\Models\Karyawan;

class Create extends Component
{
    public $nama, $nip, $alamat, $telp,$email,$no_hp,$status;

    public function mount()
    {
        $this->nama = null;
        $this->nip = null;
        $this->alamat = null;
        $this->telp = null;
        $this->email = null;
        $this->no_hp = null;
        $this->status = null;
    }

    public function back()
    {
        return redirect()->route('karyawan');
    }

    public function create()
    {
        $karyawan = new Karyawan;
        $karyawan->nama = $this->nama;
        $karyawan->nip = $this->nip;
        $karyawan->alamat = $this->alamat;
        $karyawan->no_hp = $this->no_hp;
        $karyawan->email = $this->email;
        $karyawan->status = $this->status;
        $karyawan->save();
        session()->flash('success', 'Data Karyawan berhasil disimpan');
        return redirect()->route('karyawan');
    }

    public function render()
    {
        return view('livewire.karyawan.create')
        ->layout('layouts.app');
    }
}

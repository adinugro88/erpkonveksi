<?php

namespace App\Livewire\Karyawan;

use Livewire\Component;
use App\Models\Karyawan ;

class Index extends Component
{

    public $karyawan;
    public $selectedKaryawan = null;
    public $showModal = false;

    public function mount()
    {
        $this->karyawan = Karyawan::all();
    }

    public function viewKaryawan($id)
    {
        $this->selectedKaryawan = Karyawan::find($id);
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedKaryawan = null;
    }
    public function render()
    {
        return view('livewire.karyawan.index');
    }
}

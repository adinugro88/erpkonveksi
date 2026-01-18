<?php

namespace App\Livewire\SuratJalan;

use Livewire\Component;
use App\Models\SuratJalan;

class Detail extends Component
{
    public $suratJalan;

    public function mount($id)
    {
        $this->suratJalan = SuratJalan::with(['details', 'jobCust.Customer'])->findOrFail($id);
    }

    public function render()
    {
        return view('livewire.surat-jalan.detail');
    }
}

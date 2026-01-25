<?php

namespace App\Livewire;

use Livewire\Component;

use App\Models\Hasildetail;
use App\Models\Penghasilan;
use App\Models\Karyawan;
use App\Models\Invoice;
use App\Models\Customer;
use App\Models\Karjobs;
use App\Models\Setortarik;
use App\Models\SuratJalan;


class Dashboard extends Component
{



    public $suratjalan, $cust, $penghasilan, $karyawan, $karjobs, $setortarik;
    public function render()
    {
        $this->suratjalan = SuratJalan::all();
        $this->cust = Customer::all();
        $this->penghasilan = Invoice::all();
        $this->karyawan = Karyawan::all();
        $this->karjobs = Karjobs::all();
        $this->setortarik = Setortarik::all();
        return view('livewire.dashboard');
    }
}

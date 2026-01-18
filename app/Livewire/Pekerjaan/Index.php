<?php

namespace App\Livewire\Pekerjaan;

use Livewire\Component;
use App\Models\Karjobs;
use App\Models\Job_cust;

class Index extends Component
{
    public function render()
    {
        //with paguination
        $jobcusts = Job_cust::paginate(10);

        return view('livewire.pekerjaan.index', compact('jobcusts'));
    }
}

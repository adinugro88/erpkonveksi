<?php

namespace App\Livewire\Pekerjaan;

use Livewire\Component;
use App\Models\Job_cust;
use App\Models\Karjobs;

use Livewire\Attributes\On;
use Flasher\Prime\FlasherInterface;
use Livewire\Attributes\Reactive;


class Detail extends Component
{
    public $id;
    public $karjobs, $Jobs;
    public $karjob_id, $nama_pekerjaan, $deskripsi_pekerjaan;
    public $jobcust_id;

    public function mount($id)
    {
        $this->id = $id;
        $this->jobcust_id = $id;
        $this->Jobs = Job_cust::findOrFail($id);
    }

    public function render()
    {
        $this->karjobs = Karjobs::where('jobcust_id', $this->id)->get();
        return view('livewire.pekerjaan.detail');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->dispatch('show-karjob-modal');
    }

    public function closeModal()
    {
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->karjob_id = null;
        $this->nama_pekerjaan = '';
        $this->deskripsi_pekerjaan = '';
    }

    public function store()
    {
        $this->validate([
            'nama_pekerjaan' => 'required',
            'deskripsi_pekerjaan' => 'nullable',
        ]);

        Karjobs::updateOrCreate(['id' => $this->karjob_id], [
            'nama_pekerjaan' => $this->nama_pekerjaan,
            'deskripsi_pekerjaan' => $this->deskripsi_pekerjaan,
            'jobcust_id' => $this->jobcust_id
        ]);

        session()->flash('message', $this->karjob_id ? 'Job Updated Successfully.' : 'Job Created Successfully.');
        
        $this->dispatch('hide-karjob-modal');
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $karjob = Karjobs::findOrFail($id);
        $this->karjob_id = $id;
        $this->nama_pekerjaan = $karjob->nama_pekerjaan;
        $this->deskripsi_pekerjaan = $karjob->deskripsi_pekerjaan;
    
        $this->dispatch('show-karjob-modal');
    }

    public function delete($id)
    {
       
        try {
         $this->karjob_id = $id;
        $nama = Karjobs::findOrFail($id);
        sweetalert()
            ->showDenyButton()
            ->info('Apakah Anda Yakin Menghapus Detail Pekerjaan ?'.$nama->nama);
        $this->dispatch('show-delete-confirmation', id: $id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Tangani jika karyawan tidak ditemukan
            sweetalert()->error('Data karyawan tidak ditemukan!');
    }
    }

    #[On('sweetalert:confirmed')]
    public function onConfirmed()
    {
        Karjobs::destroy($this->karjob_id);
        flash()->success('Job Deleted Successfully.');
    }

    #[On('sweetalert:denied')]
    public function onDenied(array $payload): void
    {
        flash()->info('Deletion was cancelled.');
    }
}

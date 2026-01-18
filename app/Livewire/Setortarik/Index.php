<?php

namespace App\Livewire\Setortarik;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use App\Models\Mastersetortarik;
use App\Models\Karyawan;
use App\Models\Job_cust;

class Index extends Component
{
    use WithPagination;

    public $mastersetortarik_id;
    public $karyawan_id;
    public $jobcust_id;

    public function render()
    {
        $query = Mastersetortarik::with(['karyawan', 'jobCust']);

        // IF Karyawan, only see their own master records
        if (auth()->user()->isKaryawan()) {
            if (auth()->user()->karyawan_id) {
                $query->where('karyawan_id', auth()->user()->karyawan_id);
            } else {
                // If karyawan user but not linked to a karyawan record, show nothing
                $query->whereRaw('1 = 0');
            }
        }

        $mastersetortariks = $query->paginate(10);
        
        // Get all karyawan and jobs for the form
        $karyawans = Karyawan::all();
        $jobcusts = Job_cust::all();

        return view('livewire.setortarik.index', compact('mastersetortariks', 'karyawans', 'jobcusts'));
    }

    public function create()
    {
        if (auth()->user()->isKaryawan()) {
            session()->flash('error', 'Akses ditolak.');
            return;
        }
        $this->resetInputFields();
        $this->dispatch('show-master-modal');
    }

    public function store()
    {
        if (auth()->user()->isKaryawan()) {
            session()->flash('error', 'Akses ditolak.');
            return;
        }
        $this->validate([
            'karyawan_id' => 'required|exists:karyawans,id',
            'jobcust_id' => 'required|exists:job_custs,id',
        ]);

        // Check if combination already exists
        $exists = Mastersetortarik::where('karyawan_id', $this->karyawan_id)
            ->where('jobcust_id', $this->jobcust_id)
            ->exists();

        if ($exists && !$this->mastersetortarik_id) {
            session()->flash('error', 'Karyawan ini sudah di-assign ke job tersebut!');
            return;
        }

        Mastersetortarik::updateOrCreate(
            ['id' => $this->mastersetortarik_id],
            [
                'karyawan_id' => $this->karyawan_id,
                'jobcust_id' => $this->jobcust_id,
            ]
        );

        session()->flash('message', $this->mastersetortarik_id ? 'Master setor ambil berhasil diupdate.' : 'Karyawan berhasil di-assign ke job.');
        
        $this->dispatch('hide-master-modal');
        $this->resetInputFields();
    }

    public function edit($id)
    {
        if (auth()->user()->isKaryawan()) {
            session()->flash('error', 'Akses ditolak.');
            return;
        }
        $master = Mastersetortarik::findOrFail($id);
        $this->mastersetortarik_id = $id;
        $this->karyawan_id = $master->karyawan_id;
        $this->jobcust_id = $master->jobcust_id;

        $this->dispatch('show-master-modal');
    }

    public function delete($id)
    {
        if (auth()->user()->isKaryawan()) {
            session()->flash('error', 'Akses ditolak.');
            return;
        }
        $this->mastersetortarik_id = $id;

        $master = Mastersetortarik::find($id);

        if ($master && $master->setortariks()->count() > 0) {
            session()->flash('error', 'Tidak dapat menghapus! Master ini sudah memiliki data setor ambil.');
            return;
        }

        $this->dispatch('show-delete-confirmation', id: $id);
    }

    #[On('sweetalert:confirmed')]
    public function onConfirmed()
    {
        $master = Mastersetortarik::find($this->mastersetortarik_id);
        
        if ($master) {
            // Check if has setortariks
            if ($master->setortariks()->count() > 0) {
                session()->flash('error', 'Tidak dapat menghapus! Master ini sudah memiliki data setor ambil.');
                return;
            }
            
            $master->delete();
            session()->flash('message', 'Master setor ambil berhasil dihapus.');
        }
    }

    #[On('sweetalert:denied')]
    public function onDenied($payload = null)
    {
        // session()->flash('info', 'Penghapusan dibatalkan.');
    }

    public function closeModal()
    {
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->mastersetortarik_id = null;
        $this->karyawan_id = null;
        $this->jobcust_id = null;
    }
}

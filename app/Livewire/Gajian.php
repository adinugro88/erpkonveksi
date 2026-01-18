<?php

namespace App\Livewire;

use App\Models\Gaji;
use App\Models\DetailGaji;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use Flasher\Prime\FlasherInterface;

class Gajian extends Component
{
    use WithPagination;



    public $search = '';
    public $perPage = 10;
    public $showViewModal = false;
    public $selectedGaji = null;

    protected $queryString = ['search', 'perPage'];

    public function viewGaji($id)
    {
        $this->selectedGaji = Gaji::with(['karyawan', 'details'])->find($id);
        $this->showViewModal = true;
    }

    public function closeViewModal()
    {
        $this->showViewModal = false;
        $this->selectedGaji = null;
    }

    public $gajianid;




    public function deleteGaji($id)
    {
        $this->gajianid = $id;
        sweetalert()
            ->showDenyButton()
            ->info('Apakah Anda Yakin Menghapus Data Gaji ?');
    }


    #[On('sweetalert:confirmed')]
    public function onConfirmed(array $payload): void
    {
        static $hasRun = false;

        if ($hasRun) {
            return;
        }

        $hasRun = true;

            //dd($this->gajianid);
            Gaji::destroy($this->gajianid);
            DetailGaji::where('gaji_id', $this->gajianid)->delete();
             flash()->Warning('Data Gaji Berhasil Dihapus');
             $this->refreshdata();
    }

    #[On('sweetalert:denied')]
    public function onDeny(array $payload): void
    {
        static $hasRun = false;

            if ($hasRun) {
                return;
            }

            $hasRun = true;

        flash()->info('Tidak Jadi Menghapus Data.');
    }

    public function refreshdata()
    {
        return redirect()->route('gajian.index');
    }

    public function updatingSearch()
    {
        $this->resetPage(); // Reset pagination when search query changes
    }

    public function render()
    {
        $gajian = Gaji::with('karyawan')
            ->where(function($query) {
                $query->where('no_kode', 'like', '%' . $this->search . '%')
                      ->orWhereHas('karyawan', function ($subQuery) {
                          $subQuery->where('nama', 'like', '%' . $this->search . '%');
                      });
            })
            ->when(auth()->user()->role === 'karyawan', function($query) {
                $query->where('karyawan_id', auth()->user()->karyawan_id);
            })
            ->orderBy('id', 'desc')
            ->paginate($this->perPage);


        return view('livewire.gajian', compact('gajian'));
    }
}

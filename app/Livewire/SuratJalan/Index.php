<?php

namespace App\Livewire\SuratJalan;

use Livewire\Component;
use App\Models\SuratJalan;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    
    public $search = '';
    public $filterJenis = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function delete($id)
    {
        $sj = SuratJalan::find($id);
        if ($sj) {
            $sj->delete();
            session()->flash('message', 'Surat Jalan berhasil dihapus.');
        }
    }

    public function render()
    {
        $query = SuratJalan::with(['jobCust.Customer'])
            ->withCount('details')
            ->orderBy('tanggal', 'desc');

        if ($this->search) {
            $query->where('kode_sj', 'like', '%' . $this->search . '%')
                  ->orWhereHas('jobCust', function($q) {
                      $q->where('nama', 'like', '%' . $this->search . '%');
                  });
        }

        if ($this->filterJenis) {
            $query->where('jenis', $this->filterJenis);
        }

        return view('livewire.surat-jalan.index', [
            'suratJalans' => $query->paginate(10)
        ]);
    }
}

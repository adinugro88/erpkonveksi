<?php

namespace App\Livewire\Setortarik;

use Livewire\Component;
use App\Models\Mastersetortarik;
use App\Models\Setortarik;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class Detail extends Component
{
    public $mastersetortarik_id;
    public $mastersetortarik;
    public $setortariks;
    
    public $setortarik_id; // For delete
    public $filterJenis = 'all';

    public function mount($mastersetortarik_id)
    {
        $this->mastersetortarik_id = $mastersetortarik_id;
        $this->mastersetortarik = Mastersetortarik::with(['karyawan', 'jobCust'])->findOrFail($mastersetortarik_id);
    }

    public function render()
    {
        $query = Setortarik::where('mastersetortarik_id', $this->mastersetortarik_id)
            ->with(['details', 'karjobs']);

        if ($this->filterJenis == 'all') {
            // Default: Ambil parents with their Setor children
            $this->setortariks = $query->where('jenis', 'tarik')
                ->with(['setorTransactions.details', 'setorTransactions.karjobs'])
                ->orderBy('tanggal', 'desc')
                ->get();
        } elseif ($this->filterJenis == 'tarik') {
            // Only Ambil
            $this->setortariks = $query->where('jenis', 'tarik')
                ->orderBy('tanggal', 'desc')
                ->get();
        } elseif ($this->filterJenis == 'setor') {
            // Only Setor (Flat list)
            $this->setortariks = $query->where('jenis', 'setor')
                ->with('parentAmbil')
                ->orderBy('tanggal', 'desc')
                ->get();
        }

        // Fetch orphaned SETOR transactions (only relevant for 'all' or 'setor')
        $orphanedSetors = collect();
        if ($this->filterJenis == 'all' || $this->filterJenis == 'setor') {
             $orphanedQuery = Setortarik::where('mastersetortarik_id', $this->mastersetortarik_id)
                ->where('jenis', 'setor')
                ->whereNull('parent_id')
                ->with(['details', 'karjobs']);
             
             // If filtering unique logic for orphans? 
             // Actually, if filterJenis is 'setor', the main query above MIGHT fetch orphans if they have type 'setor'. 
             // But existing code treated orphans separately. 
             // Let's check: Main query for 'setor' just says `where('jenis', 'setor')`. It doesn't enforce parent_id value.
             // So if I use that main query for 'setor', it fetches ALL setors (orphaned + childs).
             // That's actually BETTER for a flat "Setor" view.
             
             // So, ONLY fetching orphans explicitly for 'all' mode where we show hierarchy.
             if ($this->filterJenis == 'all') {
                 $orphanedSetors = $orphanedQuery->get();
             }
        }

        return view('livewire.setortarik.detail', [
            'orphanedSetors' => $orphanedSetors
        ]);
    }

    public function delete($id)
    {
        if (auth()->user()->isKaryawan()) {
            session()->flash('error', 'Akses ditolak.');
            return;
        }
        try {
            $this->setortarik_id = $id;
            $setortarik = Setortarik::findOrFail($id);
            
            $this->dispatch('show-delete-confirmation', id: $id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            session()->flash('error', 'Data tidak ditemukan!');
        }
    }

    #[On('sweetalert:confirmed')]
    public function onConfirmed()
    {
        if (auth()->user()->isKaryawan()) {
            session()->flash('error', 'Akses ditolak.');
            return;
        }
        $setortarik = Setortarik::find($this->setortarik_id);
        
        if ($setortarik) {
            DB::transaction(function () use ($setortarik) {
                // Check if this is an AMBIL with child SETOR
                if ($setortarik->jenis == 'tarik' && $setortarik->setorTransactions()->count() > 0) {
                    session()->flash('error', 'Tidak dapat menghapus transaksi AMBIL yang sudah memiliki SETOR!');
                    return;
                }

                // Delete photos
                if ($setortarik->photo1) {
                    Storage::disk('public')->delete($setortarik->photo1);
                }
                if ($setortarik->photo2) {
                    Storage::disk('public')->delete($setortarik->photo2);
                }
                if ($setortarik->photo3) {
                    Storage::disk('public')->delete($setortarik->photo3);
                }
                
                // Delete details
                $setortarik->details()->delete();
                
                // Detach karjobs pivot
                $setortarik->karjobs()->detach();
                
                // Delete setortarik
                $setortarik->delete();
                
                session()->flash('message', 'Data berhasil dihapus.');
            });
        }
    }

    #[On('sweetalert:denied')]
    public function onDenied(array $payload): void
    {
        session()->flash('info', 'Penghapusan dibatalkan.');
    }
}

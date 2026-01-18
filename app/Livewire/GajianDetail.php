<?php

namespace App\Livewire;

use App\Models\DetailGaji;
use App\Models\Gaji;
use App\Models\Karyawan;
use App\Models\Setortarik;
use App\Models\Setortarikdtl;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class GajianDetail extends Component
{
    use WithPagination;

    #[Url(keep: true)]
    public $search = '';
    public $isFocused = false;
    public $isLoading = false;
    public $perPage = 10;
    public $selectedItem = null;

    protected $queryString = ['search'];

    public $karyawan;
    public $date = '';
    // public $details = []; // Data details
    public $deskripsi = '';
    public $jumlah = '';
    public $potongan = '';
    public $total = '0';
    public $status = '';
    // public $mode = 'penambahan';

    public $pertambahan = [];
    public $pengurangan = [];

    // Setor Tarik Integration
    public $showSetorModal = false;
    public $availableSetorItems = [];
    public $selectedSetorIds = [];

    public $listeners = ['calculateTotal'];

    public function updatingSearch()
    {
        $this->resetPage();

        // Reset selection if search value is modified
        if ($this->selectedItem) {
            $this->selectedItem = null;
        }
    }

    /**
     * Focus on the input field to show suggestions.
     */
    public function onFocus()
    {
        $this->isFocused = true;
    }

    /**
     * Load more data when scrolling.
     */
    public function loadMore()
    {
        $this->isLoading = true;
        $this->perPage += $this->perPage;

        // Delay for a better user experience
        usleep(100000); // 500ms

        $this->isLoading = false;
    }

    public function selectItem($id)
    {
        $karyawan = Karyawan::find($id);
        $this->selectedItem = $karyawan ? $karyawan->toArray() : null;

        if ($this->selectedItem) {
            $this->search = $this->selectedItem['nama']; // Set search input to selected item's name
        }

        // Hide the dropdown
        $this->isFocused = false;

        // Fetch setor items for this employee
        $this->fetchSetorItems();
    }

    public function fetchSetorItems()
    {
        if (!$this->selectedItem) {
            $this->availableSetorItems = [];
            return;
        }

        // Fetch individual Setortarikdtl items for this employee that haven't been linked to a DetailGaji
        $alreadySelectedIds = collect($this->pertambahan)->pluck('setortarikdtl_id')->filter()->toArray();

        $this->availableSetorItems = Setortarikdtl::whereHas('setortarik.mastersetortarik', function($query) {
                $query->where('karyawan_id', $this->selectedItem['id']);
            })
            ->whereHas('setortarik', function($query) {
                $query->where('jenis', 'setor');
            })
            ->whereDoesntHave('detailGajis')
            ->whereNotIn('id', $alreadySelectedIds)
            ->with('setortarik')
            ->orderBy(Setortarik::select('tanggal')->whereColumn('setortariks.id', 'setortarikdtls.setortarik_id'), 'desc')
            ->get();
    }

    public function openSetorModal()
    {
        if (!$this->selectedItem) {
            session()->flash('error', 'Pilih karyawan terlebih dahulu.');
            return;
        }
        $this->selectedSetorIds = []; // Clear previous selections
        $this->fetchSetorItems();
        $this->showSetorModal = true;
    }

    public function addSelectedSetorToPertambahan()
    {
        foreach ($this->selectedSetorIds as $detailId) {
            $detail = Setortarikdtl::with('setortarik')->find($detailId);
            if ($detail) {
                // Check if first row is empty, if so, replace it
                if (count($this->pertambahan) === 1 && empty($this->pertambahan[0]['deskripsi']) && empty($this->pertambahan[0]['jumlah'])) {
                    $this->pertambahan[0] = [
                        'deskripsi' => $detail->barang,
                        'jumlah' => $detail->jumlah,
                        'harga' => 0,
                        'total' => 0,
                        'setortarikdtl_id' => $detail->id
                    ];
                } else {
                    $this->pertambahan[] = [
                        'deskripsi' => $detail->barang,
                        'jumlah' => $detail->jumlah,
                        'harga' => 0,
                        'total' => 0,
                        'setortarikdtl_id' => $detail->id
                    ];
                }
            }
        }

        $this->showSetorModal = false;
        $this->selectedSetorIds = [];
        $this->calculateTotal();
    }

    public function updated($propertyName)
    {
        $this->calculateTotal();
    }

    public function mount()
    {
        $this->pertambahan = [['deskripsi' => '', 'jumlah' => '', 'harga' => '', 'total' => 0]];
        $this->pengurangan = [['deskripsi' => '', 'potongan' => '', 'total' => '']];
    }

    public function addPertambahan()
    {
        $this->pertambahan[] = ['deskripsi' => '', 'jumlah' => '', 'harga' => '', 'total' => 0];
    }

    public function removePertambahan($index)
    {
        unset($this->pertambahan[$index]);
        $this->pertambahan = array_values($this->pertambahan); // Reindex array
    }

    public function addPengurangan()
    {
        $this->pengurangan[] = ['deskripsi' => '', 'potongan' => '', 'total' => ''];
    }

    public function removePengurangan($index)
    {
        unset($this->pengurangan[$index]);
        $this->pengurangan = array_values($this->pengurangan); // Reindex array
    }

    public function updatedPertambahan($value, $key)
    {
        if (str_contains($key, '.')) {
            [$index, $field] = explode('.', $key);

            if ($field === 'jumlah' || $field === 'harga') {
                $jumlah = $this->pertambahan[$index]['jumlah'];
                $harga = $this->pertambahan[$index]['harga'];

                // Clean dots just in case
                $jumlah = is_string($jumlah) ? str_replace('.', '', $jumlah) : $jumlah;
                $harga = is_string($harga) ? str_replace('.', '', $harga) : $harga;

                $this->pertambahan[$index]['total'] = (is_numeric($jumlah) ? floatval($jumlah) : 0) * (is_numeric($harga) ? floatval($harga) : 0);
            }
        }

        $this->calculateTotal();
    }

    public function updatedPengurangan($value, $key)
    {
        if (str_contains($key, '.')) {
            [$index, $field] = explode('.', $key);

            if ($field === 'potongan') {
                $potongan = $this->pengurangan[$index]['potongan'];
                $potongan = is_string($potongan) ? str_replace('.', '', $potongan) : $potongan;
                $this->pengurangan[$index]['total'] = is_numeric($potongan) ? floatval($potongan) : 0;
            }
        }
        $this->calculateTotal();
    }



    public function kalkulate()
    {
        $this->calculateTotal();
    }


    public function calculateTotal()
    {
        // Hitung total pertambahan
        $totalPertambahan = array_reduce($this->pertambahan, function ($carry, $item) {
            $val = str_replace('.', '', $item['total']);
            return $carry + (is_numeric($val) ? floatval($val) : 0);
        }, 0);

        // Hitung total pengurangan
        $totalPengurangan = array_reduce($this->pengurangan, function ($carry, $item) {
            $val = str_replace('.', '', $item['total']);
            return $carry + (is_numeric($val) ? floatval($val) : 0);
        }, 0);

        // Total keseluruhan
        $this->total = $totalPertambahan - $totalPengurangan;
    }



    public function storeGaji()
    {
        $this->validate([
            'date' => 'required|date',
            'selectedItem' => 'required',
        ], [
            'date.required' => 'Tanggal harus diisi.',
            'selectedItem.required' => 'Karyawan harus dipilih.',
        ]);

        $karyawan = Karyawan::find($this->selectedItem['id']);
        if (!$karyawan) {
            session()->flash('error', 'Karyawan tidak ditemukan.');
            return;
        }

        $gaji = Gaji::create([
            'tanggal' => $this->date,
            'karyawan_id' => $this->selectedItem['id'],
        ]);

        // Filter valid pertambahan rows
        $validPertambahan = array_filter($this->pertambahan, function($item) {
            return !empty($item['deskripsi']) && !empty($item['jumlah']) && !empty($item['harga']);
        });

        foreach ($validPertambahan as $detail) {
            DetailGaji::create([
                'deskripsi' => $detail['deskripsi'],
                'jumlah' => $detail['jumlah'] ?? 0,
                'fee' => $detail['harga'] ?? 0,
                'gaji_id' => $gaji->id,
                'setortarikdtl_id' => $detail['setortarikdtl_id'] ?? null,
            ]);
        }

        // Filter valid pengurangan rows
        $validPengurangan = array_filter($this->pengurangan, function($item) {
            return !empty($item['deskripsi']) && !empty($item['potongan']);
        });

        foreach ($validPengurangan as $detail) {
            DetailGaji::create([
                'deskripsi' => $detail['deskripsi'],
                'potongan' => $detail['potongan'] ?? 0,
                'gaji_id' => $gaji->id,
            ]);
        }


        session()->flash('success', 'Data gaji berhasil disimpan!');
        return $this->redirect(route('gajian.index'), true);
    }


    public function render()
    {
        $datakaryawan = Karyawan::where('nama', 'like', '%' . $this->search . '%')
            ->orWhere('nip', 'like', '%' . $this->search . '%')
            ->paginate($this->perPage);

        return view('livewire.gajian-detail', compact('datakaryawan'));
    }
}

<?php

namespace App\Livewire;

use Livewire\Component;

use App\Models\DetailGaji;
use App\Models\Gaji;
use App\Models\Karyawan;
use App\Models\Setortarik;
use App\Models\Setortarikdtl;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

class GajianDetailEdit extends Component
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

    public $id;

    public function mount($id)
    {
        $this->id = $id;
        $gaji_master = Gaji::with('karyawan')->findOrFail($id);
        $this->date = $gaji_master->tanggal;
        $this->selectedItem = $gaji_master->karyawan ? $gaji_master->karyawan->toArray() : null;

        $gaji_details = DetailGaji::where('gaji_id', $id)->get();

        // Inisialisasi array kosong untuk pertambahan dan pengurangan
        $this->pertambahan = [];
        $this->pengurangan = [];

        foreach ($gaji_details as $item) {
            if ($item->potongan !== null) {
                $this->pengurangan[] = [
                    'deskripsi' => $item->deskripsi ?: '',
                    'potongan' => $item->potongan ?: '',
                    'total' => $item->potongan ?: ''
                ];
            } else {
                $this->pertambahan[] = [
                    'deskripsi' => $item->deskripsi ?: '',
                    'jumlah' => $item->jumlah ?: '',
                    'harga' => $item->fee ?: '',
                    'total' => ($item->jumlah ? $item->jumlah : 0) * ($item->fee ? $item->fee : 0),
                    'setortarikdtl_id' => $item->setortarikdtl_id
                ];
            }
        }

        // Jika tidak ada data pengurangan, inisialisasi array pengurangan dengan nilai default
        if (empty($this->pengurangan)) {
            $this->pengurangan = [['deskripsi' => '', 'potongan' => '', 'total' => '']];
        }

        // Jika tidak ada data pertambahan, inisialisasi array pertambahan dengan nilai default
        if (empty($this->pertambahan)) {
            $this->pertambahan = [['deskripsi' => '', 'jumlah' => '', 'harga' => '', 'total' => 0]];
        }

        $this->calculateTotal();

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

    public function addPertambahan()
    {
        $this->pertambahan[] = ['deskripsi' => '', 'jumlah' => '', 'harga' => '', 'total' => 0];
    }

    public function removePertambahan($index)
    {
        unset($this->pertambahan[$index]);
        $this->pertambahan = array_values($this->pertambahan); // Reindex array
        $this->calculateTotal();
    }

    public function addPengurangan()
    {
        $this->pengurangan[] = ['deskripsi' => '', 'potongan' => '', 'total' => ''];
    }

    public function removePengurangan($index)
    {
        unset($this->pengurangan[$index]);
        $this->pengurangan = array_values($this->pengurangan); // Reindex array
        $this->calculateTotal();
    }

    public function updatedPertambahan($value, $key)
    {
        if (str_contains($key, '.')) {
            [$index, $field] = explode('.', $key);

            if ($field === 'jumlah' || $field === 'harga') {
                $jumlah = $this->pertambahan[$index]['jumlah'];
                $harga = $this->pertambahan[$index]['harga'];

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
        ], [
            'date.required' => 'Tanggal harus diisi.',
        ]);

        $gaji = Gaji::findOrFail($this->id);
        $gaji->update([
            'tanggal' => $this->date
        ]);

        // Hapus semua data yang berkaitan dengan gaji_id terlebih dahulu
        DetailGaji::where('gaji_id', $this->id)->delete();

        // Filter valid pertambahan rows
        $validPertambahan = array_filter($this->pertambahan, function($item) {
            return !empty($item['deskripsi']) && !empty($item['jumlah']) && !empty($item['harga']);
        });

        foreach ($validPertambahan as $detail) {
            DetailGaji::create([
                'deskripsi' => $detail['deskripsi'],
                'jumlah' => $detail['jumlah'] ?? 0,
                'fee' => $detail['harga'] ?? 0,
                'gaji_id' => $this->id,
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
                'gaji_id' => $this->id,
            ]);
        }

        // Hitung total setelah update
        $this->calculateTotal();

        // Reset input dan berikan notifikasi
        session()->flash('success', 'Data gaji berhasil diperbarui!');
        return $this->redirect(route('gajian.index'), true);

    }



    public function render()
    {
        return view('livewire.gajian-detail-edit');
    }
}

<?php

namespace App\Livewire\Setortarik;

use Livewire\Component;
use App\Models\Mastersetortarik;
use App\Models\Setortarik;
use App\Models\Setortarikdtl;
use App\Models\Karjobs;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Laravel\Facades\Image;

class Setor extends Component
{
    use WithFileUploads;

    public $mastersetortarik_id;
    public $mastersetortarik;
    public $karjobsList;
    public $availableAmbilTransactions = [];
    
    // Form properties
    public $setortarik_id;
    public $parent_id;
    public $tanggal;
    public $keterangan;
    public $photo1, $photo2, $photo3;
    public $existing_photo1, $existing_photo2, $existing_photo3;
    
    // Items
    public $items = [];
    
    // Parent reference
    public $parentAmbilItems = [];
    public $selected_karjobs = [];

    public function mount($mastersetortarik_id, $id = null)
    {
        if (auth()->user()->isKaryawan()) {
            abort(403, 'Akses ditolak.');
        }
        $this->mastersetortarik_id = $mastersetortarik_id;
        $this->mastersetortarik = Mastersetortarik::with(['karyawan', 'jobCust'])->findOrFail($mastersetortarik_id);
        $this->karjobsList = Karjobs::where('jobcust_id', $this->mastersetortarik->jobcust_id)->get();
        
        // Load available ambil transactions
        $this->availableAmbilTransactions = Setortarik::where('mastersetortarik_id', $this->mastersetortarik_id)
            ->ambilOnly()
            ->with(['karjobs', 'details'])
            ->orderBy('tanggal', 'desc')
            ->get();
        
        if ($id) {
            // Edit mode
            $this->loadExisting($id);
        } else {
            // Create mode
            $this->items = [['barang' => '', 'jumlah' => '', 'keterangan' => '']];
        }
    }

    private function loadExisting($id)
    {
        $setortarik = Setortarik::with(['details', 'karjobs', 'parentAmbil'])->findOrFail($id);
        
        // Only allow editing setor transactions
        if ($setortarik->jenis != 'setor') {
            session()->flash('error', 'Hanya transaksi SETOR yang bisa diedit di halaman ini!');
            return redirect()->route('setortarik.detail', $this->mastersetortarik_id);
        }
        
        $this->setortarik_id = $id;
        $this->parent_id = $setortarik->parent_id;
        $this->tanggal = $setortarik->tanggal;
        $this->keterangan = $setortarik->keterangan;
        $this->existing_photo1 = $setortarik->photo1;
        $this->existing_photo2 = $setortarik->photo2;
        $this->existing_photo3 = $setortarik->photo3;
        
        $this->items = $setortarik->details->map(function($item) {
            return [
                'barang' => $item->barang,
                'jumlah' => $item->jumlah,
                'keterangan' => $item->keterangan,
            ];
        })->toArray();
        
        $this->selected_karjobs = $setortarik->karjobs->pluck('id')->toArray();
        
        // Load parent items
        if ($setortarik->parentAmbil) {
            $this->loadParentItems();
        }
    }

    public function updatedParentId()
    {
        $this->loadParentItems();
    }

    private function loadParentItems()
    {
        if ($this->parent_id) {
            $parent = Setortarik::with(['karjobs', 'details'])->find($this->parent_id);
            
            if ($parent) {
                $this->selected_karjobs = $parent->karjobs->pluck('id')->toArray();
                
                // Store parent items for reference display
                $this->parentAmbilItems = $parent->details->map(function($item) {
                    return [
                        'barang' => $item->barang,
                        'jumlah' => $item->jumlah,
                        'keterangan' => $item->keterangan
                    ];
                })->toArray();
                
                // Auto-populate $items with parent karjobs (related work)
                // Only do this if not in edit mode
                if (!$this->setortarik_id) {
                    $this->items = $parent->karjobs->map(function($job) {
                        return [
                            'barang' => $job->nama_pekerjaan,
                            'jumlah' => '', // Empty so user must input result/quantity
                            'keterangan' => $job->deskripsi_pekerjaan ?? ''
                        ];
                    })->toArray();
                }
            }
        }
    }

    public function addItem()
    {
        $this->items[] = ['barang' => '', 'jumlah' => '', 'keterangan' => ''];
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function store()
    {
        if (auth()->user()->isKaryawan()) {
            abort(403, 'Akses ditolak.');
        }
        $this->validate([
            'parent_id' => 'required|exists:setortariks,id',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
            'items.*.barang' => 'required|string',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.keterangan' => 'nullable|string',
        ]);

        DB::transaction(function () {
            $data = [
                'tanggal' => $this->tanggal,
                'jenis' => 'setor',
                'keterangan' => $this->keterangan,
                'mastersetortarik_id' => $this->mastersetortarik_id,
                'parent_id' => $this->parent_id,
            ];

            if (!$this->setortarik_id) {
                $data['kode_setortarik'] = 'STR-' . strtoupper(substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 5)) . '-' . now()->timestamp;
            }

            // Handle photos
            foreach (['photo1', 'photo2', 'photo3'] as $photoField) {
                $existingField = 'existing_' . $photoField;

                if ($this->$photoField) {
                    if ($this->setortarik_id && $this->$existingField) {
                        Storage::disk('public')->delete($this->$existingField);
                    }
                    
                    $file = $this->$photoField;
                    $filename = 'setortarik/' . time() . '_' . $photoField . '.jpg';
                    
                    // Compress image using Intervention Image
                    $img = Image::read($file);
                    $img->scale(width: 1200);
                    $encoded = $img->toJpeg(80);
                    
                    Storage::disk('public')->put($filename, (string) $encoded);
                    $data[$photoField] = $filename;
                } elseif ($this->setortarik_id) {
                    $data[$photoField] = $this->$existingField;
                }
            }

            $setortarik = Setortarik::updateOrCreate(['id' => $this->setortarik_id], $data);

            if ($this->setortarik_id) {
                Setortarikdtl::where('setortarik_id', $setortarik->id)->delete();
            }

            foreach ($this->items as $item) {
                if (!empty($item['barang'])) {
                    Setortarikdtl::create([
                        'barang' => $item['barang'],
                        'jumlah' => $item['jumlah'],
                        'keterangan' => $item['keterangan'] ?? null,
                        'setortarik_id' => $setortarik->id,
                    ]);
                }
            }

            // Inherit karjobs from parent
            $parent = Setortarik::find($this->parent_id);
            if ($parent) {
                $setortarik->karjobs()->sync($parent->karjobs->pluck('id')->toArray());
            }
        });

        session()->flash('message', $this->setortarik_id ? 'Setor barang berhasil diupdate.' : 'Setor barang berhasil dicatat.');
        return redirect()->route('setortarik.detail', $this->mastersetortarik_id);
    }

    public function cancel()
    {
        return redirect()->route('setortarik.detail', $this->mastersetortarik_id);
    }

    public function render()
    {
        return view('livewire.setortarik.setor');
    }
}

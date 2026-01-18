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

class Ambil extends Component
{
    use WithFileUploads;

    public $mastersetortarik_id;
    public $mastersetortarik;
    public $karjobsList;
    
    // Form properties
    public $setortarik_id;
    public $tanggal;
    public $keterangan;
    public $photo1, $photo2, $photo3;
    public $existing_photo1, $existing_photo2, $existing_photo3;
    
    // Items
    public $items = [];
    
    // Selected karjobs
    public $selected_karjobs = [];

    public function mount($mastersetortarik_id, $id = null)
    {
        if (auth()->user()->isKaryawan()) {
            abort(403, 'Akses ditolak.');
        }
        $this->mastersetortarik_id = $mastersetortarik_id;
        $this->mastersetortarik = Mastersetortarik::with(['karyawan', 'jobCust'])->findOrFail($mastersetortarik_id);
        $this->karjobsList = Karjobs::where('jobcust_id', $this->mastersetortarik->jobcust_id)->get();
        
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
        $setortarik = Setortarik::with(['details', 'karjobs'])->findOrFail($id);
        
        // Only allow editing ambil transactions
        if ($setortarik->jenis != 'tarik') {
            session()->flash('error', 'Hanya transaksi AMBIL yang bisa diedit di halaman ini!');
            return redirect()->route('setortarik.detail', $this->mastersetortarik_id);
        }
        
        $this->setortarik_id = $id;
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
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
            'photo1' => 'nullable|image|max:2048',
            'photo2' => 'nullable|image|max:2048',
            'photo3' => 'nullable|image|max:2048',
            'items.*.barang' => 'required|string',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.keterangan' => 'nullable|string',
            'selected_karjobs' => 'required|array|min:1',
        ]);

        DB::transaction(function () {
            $data = [
                'tanggal' => $this->tanggal,
                'jenis' => 'tarik',
                'keterangan' => $this->keterangan,
                'mastersetortarik_id' => $this->mastersetortarik_id,
                'parent_id' => null,
            ];

            if (!$this->setortarik_id) {
                $data['kode_setortarik'] = 'AMB-' . strtoupper(substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 5)) . '-' . now()->timestamp;
            }

            // Handle photos
            foreach (['photo1', 'photo2', 'photo3'] as $photoField) {
                if ($this->$photoField) {
                    $existingField = 'existing_' . $photoField;
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

            $setortarik->karjobs()->sync($this->selected_karjobs);
        });

        session()->flash('message', $this->setortarik_id ? 'Ambil barang berhasil diupdate.' : 'Ambil barang berhasil dicatat.');
        return redirect()->route('setortarik.detail', $this->mastersetortarik_id);
    }

    public function cancel()
    {
        return redirect()->route('setortarik.detail', $this->mastersetortarik_id);
    }

    public function render()
    {
        return view('livewire.setortarik.ambil');
    }
}

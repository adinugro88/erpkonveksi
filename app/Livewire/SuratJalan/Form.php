<?php

namespace App\Livewire\SuratJalan;

use Livewire\Component;
use App\Models\SuratJalan;
use App\Models\SuratJalanDetail;
use App\Models\Job_cust;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;
use Intervention\Image\Laravel\Facades\Image;

class Form extends Component
{
    use WithFileUploads;

    public $sj_id;
    public $kode_sj;
    public $tanggal;
    public $job_cust_id;
    public $customer; // Property baru
    public $jenis = 'keluar';
    public $penerima;
    public $pengirim;
    public $sopir;
    public $no_kendaraan;
    public $keterangan;
    public $bukti_suratjalan;
    public $buktiSuratjalanUrl;
    public $buktiDeleted = false;
    public $oldBuktiPath;
    
    public $items = [];
    public $jobsList = [];

    public function mount($id = null)
    {
        $this->jobsList = Job_cust::orderBy('nama', 'asc')->get();
        $this->tanggal = date('Y-m-d');

        if ($id) {
            $this->sj_id = $id;
            $sj = SuratJalan::with('details')->findOrFail($id);
            $this->kode_sj = $sj->kode_sj;
            $this->tanggal = $sj->tanggal;
            $this->job_cust_id = $sj->job_cust_id;
            $this->customer = $sj->customer; // Load customer
            $this->jenis = $sj->jenis;
            $this->penerima = $sj->penerima;
            $this->pengirim = $sj->pengirim;
            $this->sopir = $sj->sopir;
            $this->no_kendaraan = $sj->no_kendaraan;
            $this->keterangan = $sj->keterangan;
            $this->oldBuktiPath = $sj->bukti_suratjalan;
            $this->buktiSuratjalanUrl = $sj->bukti_suratjalan ? Storage::url($sj->bukti_suratjalan) : null;

            foreach ($sj->details as $detail) {
                $this->items[] = [
                    'barang' => $detail->barang,
                    'jumlah' => $detail->jumlah,
                    'satuan' => $detail->satuan,
                    'keterangan' => $detail->keterangan
                ];
            }
        } else {
            $this->generateKodeSj();
            $this->addItem();
        }
    }

    public function updatedJobCustId($value)
    {
        if($value) {
            $job = Job_cust::with('Customer')->find($value);
            if($job && $job->Customer) {
                $this->customer = $job->Customer->nama;
            } else {
                $this->customer = '';
            }
        } else {
            $this->customer = '';
        }
    }

    public function updatedJenis()
    {
        if (!$this->sj_id) {
            $this->generateKodeSj();
        }
    }

    public function generateKodeSj()
    {
        $prefix = $this->jenis == 'keluar' ? 'SJ-OUT' : 'SJ-IN';
        $timestamp = now()->timestamp;
        $random = strtoupper(substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 3));
        $this->kode_sj = "$prefix-$random-$timestamp";
    }

    public function removeBukti()
    {
        if ($this->bukti_suratjalan) {
            $this->bukti_suratjalan = null;
        }

        if ($this->buktiSuratjalanUrl) {
            $this->buktiSuratjalanUrl = null;
            $this->buktiDeleted = true;
        }
    }

    public function addItem()
    {
        $this->items[] = [
            'barang' => '',
            'jumlah' => 1,
            'satuan' => 'Pcs',
            'keterangan' => ''
        ];
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function save()
    {
        $this->validate([
            'kode_sj' => 'required|unique:surat_jalans,kode_sj,' . $this->sj_id,
            'tanggal' => 'required|date',
            'job_cust_id' => 'required|exists:job_custs,id',
            'jenis' => 'required|in:keluar,masuk',
            'bukti_suratjalan' => 'nullable|image|max:5120',
            'items' => 'required|array|min:1',
            'items.*.barang' => 'required|string',
            'items.*.jumlah' => 'required|numeric|min:0.01',
        ]);

        $buktiPath = null;
        if ($this->bukti_suratjalan) {
            $file = $this->bukti_suratjalan;
            $filename = time() . '_bukti_suratjalan.jpg';
            $relativePath = 'uploads/' . $filename;

            Storage::disk('public')->makeDirectory('uploads');

            $img = Image::read($file);
            $img->scale(width: 1200);
            $encoded = $img->toJpeg(80);

            Storage::disk('public')->put($relativePath, (string) $encoded);
            $buktiPath = $relativePath;
        }

        $data = [
            'kode_sj' => $this->kode_sj,
            'tanggal' => $this->tanggal,
            'job_cust_id' => $this->job_cust_id,
            'customer' => $this->customer, // Save Customer
            'jenis' => $this->jenis,
            'penerima' => $this->penerima,
            'pengirim' => $this->pengirim,
            'sopir' => $this->sopir,
            'no_kendaraan' => $this->no_kendaraan,
            'keterangan' => $this->keterangan,
        ];

        if ($buktiPath) {
            $data['bukti_suratjalan'] = $buktiPath;
        } elseif ($this->buktiDeleted) {
            $data['bukti_suratjalan'] = null;
        }

        DB::transaction(function () use ($data) {
            $sj = SuratJalan::updateOrCreate(
                ['id' => $this->sj_id],
                $data
            );

            if ($this->sj_id) {
                $sj->details()->delete();
            }

            foreach ($this->items as $item) {
                $sj->details()->create($item);
            }
        });

        if ($this->oldBuktiPath && ($buktiPath || $this->buktiDeleted)) {
            Storage::disk('public')->delete($this->oldBuktiPath);
        }

        session()->flash('message', 'Surat Jalan berhasil disimpan.');
        return redirect()->route('surat-jalan.index');
    }

    public function render()
    {
        return view('livewire.surat-jalan.form');
    }
}

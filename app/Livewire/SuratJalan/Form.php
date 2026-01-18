<?php

namespace App\Livewire\SuratJalan;

use Livewire\Component;
use App\Models\SuratJalan;
use App\Models\SuratJalanDetail;
use App\Models\Job_cust;
use Illuminate\Support\Facades\DB;

class Form extends Component
{
    public $sj_id;
    public $kode_sj;
    public $tanggal;
    public $job_cust_id;
    public $jenis = 'keluar';
    public $penerima;
    public $pengirim;
    public $sopir;
    public $no_kendaraan;
    public $keterangan;
    
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
            $this->jenis = $sj->jenis;
            $this->penerima = $sj->penerima;
            $this->pengirim = $sj->pengirim;
            $this->sopir = $sj->sopir;
            $this->no_kendaraan = $sj->no_kendaraan;
            $this->keterangan = $sj->keterangan;

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
            'items' => 'required|array|min:1',
            'items.*.barang' => 'required|string',
            'items.*.jumlah' => 'required|numeric|min:0.01',
        ]);

        DB::transaction(function () {
            $sj = SuratJalan::updateOrCreate(
                ['id' => $this->sj_id],
                [
                    'kode_sj' => $this->kode_sj,
                    'tanggal' => $this->tanggal,
                    'job_cust_id' => $this->job_cust_id,
                    'jenis' => $this->jenis,
                    'penerima' => $this->penerima,
                    'pengirim' => $this->pengirim,
                    'sopir' => $this->sopir,
                    'no_kendaraan' => $this->no_kendaraan,
                    'keterangan' => $this->keterangan,
                ]
            );

            if ($this->sj_id) {
                $sj->details()->delete();
            }

            foreach ($this->items as $item) {
                $sj->details()->create($item);
            }
        });

        session()->flash('message', 'Surat Jalan berhasil disimpan.');
        return redirect()->route('surat-jalan.index');
    }

    public function render()
    {
        return view('livewire.surat-jalan.form');
    }
}

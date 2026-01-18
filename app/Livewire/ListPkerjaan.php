<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Job_cust;
use App\Models\Customer;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Intervention\Image\Laravel\Facades\Image;

class ListPkerjaan extends Component
{
    use WithFileUploads;
    protected $listeners = ['hapusConfirmed' => 'hapus'];
    public $cust;
    public $nama;
    public $tanggal;
    public $gambar1;
    public $gambar2;
    public $gambar3;
    // URLs for existing images to preview when editing
    public $preview1Url;
    public $preview2Url;
    public $preview3Url;
    public $keterangan;
    public $modal = false;
    public $editingId = null;

    public function mount(Customer $customer)
    {
        $this->cust = $customer->id;
    }


    public function render()
    {
        $jobsdetail = Job_cust::where('customers_id', $this->cust)->get();
        // dd($jobsdetail);
        return view('livewire.list-pkerjaan', compact('jobsdetail'));
    }

    public function showup(){
        $this->modal = true;
    }

    public function closed(){
        $this->modal = false;
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

    public function simpandata(){
        $this->validate([
            'cust' => 'required|integer',
            'nama' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'gambar1' => 'nullable|image|max:5120',
            'gambar2' => 'nullable|image|max:5120',
            'gambar3' => 'nullable|image|max:5120',
            'keterangan' => 'nullable|string',
        ]);
    
        $job = new Job_cust();
        $job->customers_id = $this->cust;
        $job->nama = $this->nama;
        $job->tanggal = $this->tanggal;
        $job->keterangan = $this->keterangan;
        // images will be saved into photo1/photo2/photo3 columns
    
        // Handle image uploads and compression
        // map component fields to DB columns
        $map = [
            'gambar1' => 'photo1',
            'gambar2' => 'photo2',
            'gambar3' => 'photo3',
        ];

        foreach ($map as $imgField => $dbColumn) {
            if ($this->$imgField) {
                // skip if DB column does not exist to avoid SQL errors
                if (! Schema::hasColumn('job_custs', $dbColumn)) {
                    continue;
                }

                $file = $this->$imgField;
                $filename = time() . '_' . $imgField . '.jpg'; // Save as jpg for consistent compression
                $relativePath = 'uploads/' . $filename;

                // ensure directory exists
                Storage::disk('public')->makeDirectory('uploads');

                // Compress image using Intervention Image
                $img = Image::read($file);
                $img->scale(width: 1200); // Scale to 1200px width, maintaining aspect ratio
                $encoded = $img->toJpeg(80); // Encode as JPEG with 80% quality

                Storage::disk('public')->put($relativePath, (string) $encoded);
                $job->$dbColumn = $relativePath;
            }
        }
    
        $job->save();
        session()->flash('message', 'Data berhasil disimpan!');
        $this->dispatch('closed');
        $this->resetdata();
    }

    public function edit($id)
    {
        $job = Job_cust::find($id);
        if (! $job) {
            session()->flash('message', 'Data tidak ditemukan');
            return;
        }

        $this->editingId = $job->id;
        $this->nama = $job->nama;
        $this->tanggal = $job->tanggal;
        $this->keterangan = $job->keterangan;
        // don't set gambar1/2/3 to files; keep null unless user uploads replacement
        $this->gambar1 = null;
        $this->gambar2 = null;
        $this->gambar3 = null;

        // Populate preview URLs for existing images so modal shows them
        $this->preview1Url = $job->photo1 ? Storage::url($job->photo1) : null;
        $this->preview2Url = $job->photo2 ? Storage::url($job->photo2) : null;
        $this->preview3Url = $job->photo3 ? Storage::url($job->photo3) : null;

        // open modal on client side: dispatch event to front-end
        $this->dispatch('open-job-modal');
    }

    public function updateRecord()
    {
        $this->validate([
            'nama' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'gambar1' => 'nullable|image|max:5120',
            'gambar2' => 'nullable|image|max:5120',
            'gambar3' => 'nullable|image|max:5120',
            'keterangan' => 'nullable|string',
        ]);

        $job = Job_cust::find($this->editingId);
        if (! $job) {
            session()->flash('message', 'Data tidak ditemukan');
            return;
        }

        $job->nama = $this->nama;
        $job->tanggal = $this->tanggal;
        $job->keterangan = $this->keterangan;

        $map = [
            'gambar1' => 'photo1',
            'gambar2' => 'photo2',
            'gambar3' => 'photo3',
        ];

        foreach ($map as $imgField => $dbColumn) {
            if ($this->$imgField) {
                // skip if DB column does not exist to avoid SQL errors
                if (! Schema::hasColumn('job_custs', $dbColumn)) {
                    continue;
                }

                // delete old file if exists
                if ($job->$dbColumn) {
                    Storage::disk('public')->delete($job->$dbColumn);
                }

                $file = $this->$imgField;
                $filename = time() . '_' . $imgField . '.jpg';
                $relativePath = 'uploads/' . $filename;

                Storage::disk('public')->makeDirectory('uploads');

                // Compress image using Intervention Image
                $img = Image::read($file);
                $img->scale(width: 1200);
                $encoded = $img->toJpeg(80);

                Storage::disk('public')->put($relativePath, (string) $encoded);
                $job->$dbColumn = $relativePath;
            }
        }

        $job->save();
        session()->flash('message', 'Data berhasil diperbarui!');
        $this->dispatch('closed');
        $this->editingId = null;
        $this->resetdata();
    }

    public function resetdata(){
        $this->nama = "";
        $this->tanggal = "";
        $this->gambar1 = null;
        $this->gambar2 = null;
        $this->gambar3 = null;
        $this->preview1Url = null;
        $this->preview2Url = null;
        $this->preview3Url = null;
        $this->keterangan = "";
    }

    public function hapus($id)
    {
        $job = Job_cust::find($id);
        if (! $job) {
            session()->flash('message', 'Data tidak ditemukan');
            return;
        }

        // delete files if exist (only for columns that exist)
        foreach (['photo1', 'photo2', 'photo3'] as $col) {
            if (! Schema::hasColumn('job_custs', $col)) {
                continue;
            }

            if ($job->$col) {
                Storage::disk('public')->delete($job->$col);
            }
        }

        $job->delete();
        session()->flash('message', 'Data berhasil dihapus');
        $this->dispatch('refreshComponent');
    }

    public function resetForm()
    {
        // Reset form state when opening a new job form
        $this->editingId = null;
        $this->resetdata();
    }
}

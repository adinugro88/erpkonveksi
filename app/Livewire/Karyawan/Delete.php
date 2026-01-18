<?php

namespace App\Livewire\Karyawan;

use Livewire\Component;
use App\Models\Karyawan;
use App\Models\Gaji;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Flasher\Prime\FlasherInterface;


class Delete extends Component
{
    public $karyawanId;

    

    public function deleteUser()
{
    try {
        $nama = Karyawan::where('id', $this->karyawanId)->firstOrFail();
        //dd($nama->nama);
        sweetalert()
            ->showDenyButton()
            ->info('Apakah Anda Yakin Menghapus Data Karyawan ?'.$nama->nama);
            
        $this->dispatch('show-delete-confirmation', id: $this->karyawanId);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        // Tangani jika karyawan tidak ditemukan
        sweetalert()->error('Data karyawan tidak ditemukan!');
    }
}





   #[On('sweetalert:confirmed')]
    public function onConfirmed(): void 
    {
       
        // Ekstrak ID dari payload
    $id = $this->karyawanId;


        // Check if this karyawan has any gaji records linked to them
        $gajiBulanCount = Gaji::where('karyawan_id', $this->karyawanId)->count();
        
        // dd($gajiBulanCount);
        if ($gajiBulanCount == 1) {
            flash()->error("Data Karyawan tidak bisa dihapus karena sudah memiliki data gaji.");
            $this->refreshdata();
        } 
        else {
            // No gaji records, safe to delete
            Karyawan::destroy($id);
            flash()->warning('Data Karyawan Berhasil Dihapus');
            $this->refreshdata();
        }
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
         $this->dispatch('refresh');
    }

    public function render()
    {
        return view('livewire.karyawan.delete');
    }
}

<?php

namespace App\Livewire\Karyawan;

use Livewire\Component;
use App\Models\Karyawan;
use App\Models\Gaji;
use App\Models\Mastersetortarik;
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
    public function onConfirmed()
    {
        static $hasRun = false;

        if ($hasRun) {
            return;
        }

        $hasRun = true;

        $id = $this->karyawanId;
        
        // Check all related records
        $gajiCount = Gaji::where('karyawan_id', $id)->count();
        $setortarikCount = Mastersetortarik::where('karyawan_id', $id)->count();
        
        if ($gajiCount > 0 || $setortarikCount > 0) {
            $message = "Data Karyawan tidak bisa dihapus karena masih memiliki:";
            if ($gajiCount > 0) $message .= " {$gajiCount} data gaji,";
            if ($setortarikCount > 0) $message .= " {$setortarikCount} data setor/tarik,";
            $message = rtrim($message, ',') . ".";
            
            flash()->error($message);
            $this->refreshdata();
        } else {
            // No related records, safe to delete
            Karyawan::destroy($id);
            session()->flash('success', 'Data Karyawan Berhasil Dihapus');
            return redirect()->route('karyawan.index');
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

<?php

namespace App\Livewire\Karyawan;

use Livewire\Component;
use App\Models\Karyawan;

class Form extends Component
{
    public $id, $nama, $alamat, $no_hp, $email, $status, $nip;

    protected $rules = [
        'nama' => 'required|string|max:255',
        'alamat' => 'nullable|string',
        'no_hp' => 'nullable|string|max:255',
        'email' => 'nullable|email|max:255',
        'status' => 'required|in:0,1',
    ];

    public function mount($id = null)
    {
        if ($id) {
            $data = Karyawan::find($id);
            $this->id = $data->id;
            $this->nama = $data->nama;
            $this->alamat = $data->alamat === '-' ? '' : $data->alamat;
            $this->no_hp = $data->no_hp === '-' ? '' : $data->no_hp;
            $this->email = $data->email === '-' ? '' : $data->email;
            $this->status = $data->status;
            $this->nip = $data->nip;
        }
    }

    public function save()
    {
        \Log::info('Livewire Karyawan Form: save() called', [
            'nama' => $this->nama,
            'email' => $this->email,
            'no_hp' => $this->no_hp,
            'status' => $this->status,
        ]);

        try {
            $this->validate();

            if ($this->id) {
                $data = Karyawan::find($this->id);
                $data->update([
                    'nama' => $this->nama,
                    'alamat' => $this->alamat ?: '-',
                    'no_hp' => $this->no_hp ?: '-',
                    'email' => $this->email ?: '-',
                    'status' => $this->status,
                    'nip' => $this->nip,
                ]);
            } else {
                Karyawan::create([
                    'nama' => $this->nama,
                    'alamat' => $this->alamat ?: '-',
                    'no_hp' => $this->no_hp ?: '-',
                    'email' => $this->email ?: '-',
                    'status' => $this->status,
                    'nip' => $this->nip,
                ]);
            }

            session()->flash('success', 'Data berhasil disimpan');
            return redirect()->route('karyawan.index');
        } catch (\Throwable $e) {
            if ($e instanceof \Illuminate\Validation\ValidationException) {
                // Rethrow validation exception so Livewire can return field errors
                throw $e;
            }

            \Log::error('Livewire Karyawan Form save error: ' . $e->getMessage(), [
                'exception' => $e,
            ]);
            session()->flash('error', 'Terjadi kesalahan saat menyimpan data. Silakan cek log.');
        }
    }

    public function render()
    {
        return view('livewire.karyawan.form');
    }
}

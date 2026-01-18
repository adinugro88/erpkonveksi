<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserManagement extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $name, $email, $password, $role = 'karyawan', $user_id, $karyawan_id;
    public $isEdit = false;
    public $search = '';
    public $karyawans = [];

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($this->user_id)],
            'role' => 'required|in:admin,operasional,karyawan',
            'password' => $this->isEdit ? 'nullable|min:8' : 'required|min:8',
            'karyawan_id' => 'nullable|exists:karyawans,id',
        ];
    }

    public function mount()
    {
        $this->karyawans = \App\Models\Karyawan::all();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function resetFields()
    {
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->role = 'karyawan';
        $this->user_id = null;
        $this->karyawan_id = null;
        $this->isEdit = false;
    }

    public function store()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'karyawan_id' => ($this->role === 'karyawan') ? $this->karyawan_id : null,
        ];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        User::updateOrCreate(['id' => $this->user_id], $data);

        session()->flash('message', $this->isEdit ? 'User berhasil diupdate.' : 'User berhasil dibuat.');
        $this->resetFields();
        $this->dispatch('hide-modal');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->user_id = $id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->karyawan_id = $user->karyawan_id;
        $this->isEdit = true;
        
        $this->dispatch('show-modal');
    }

    public function delete($id)
    {
        if (auth()->id() == $id) {
            session()->flash('error', 'Anda tidak bisa menghapus diri sendiri.');
            return;
        }

        User::find($id)->delete();
        session()->flash('message', 'User berhasil dihapus.');
    }

    public function render()
    {
        return view('livewire.user-management', [
            'users' => User::where('name', 'like', '%' . $this->search . '%')
                ->orWhere('email', 'like', '%' . $this->search . '%')
                ->paginate(10)
        ]);
    }
}

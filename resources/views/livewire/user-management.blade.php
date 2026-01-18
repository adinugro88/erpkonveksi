@section('Judul','User Management')
@section('action','Daftar Pengguna & Role')

<div class="container">
    <div class="card p-3">
        <div class="row mb-3">
            <div class="col-6">
                <input type="text" class="form-control" placeholder="Cari user..." wire:model.live="search">
            </div>
            <div class="col-6 text-right">
                <button wire:click="resetFields" data-toggle="modal" data-target="#userModal" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah User
                </button>
            </div>
        </div>

        @if (session()->has('message'))
            <div class="alert alert-success mt-3">
                {{ session('message') }}
            </div>
        @endif
        
        @if (session()->has('error'))
            <div class="alert alert-danger mt-3">
                {{ session('error') }}
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th width="50">No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php $i = ($users->currentPage() - 1) * $users->perPage(); @endphp
                    @forelse ($users as $user)
                        <tr>
                            <td>{{ ++$i }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->role == 'admin')
                                    <span class="badge badge-danger">ADMIN</span>
                                @elseif($user->role == 'operasional')
                                    <span class="badge badge-warning">OPERASIONAL</span>
                                @else
                                    <span class="badge badge-info">KARYAWAN</span>
                                @endif
                            </td>
                            <td>
                                <button wire:click="edit({{ $user->id }})" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="confirm('Yakin ingin menghapus user ini?') || event.stopImmediatePropagation()" 
                                        wire:click="delete({{ $user->id }})" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Data tidak ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $users->links() }}
        </div>
    </div>

    <!-- Modal User -->
    <div wire:ignore.self class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="userModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userModalLabel">{{ $isEdit ? 'Edit User' : 'Tambah User Baru' }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label>Nama Pengguna <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" wire:model="name">
                            @error('name') <span class="text-danger small">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label>Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" wire:model="email">
                            @error('email') <span class="text-danger small">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label>Role <span class="text-danger">*</span></label>
                            <select class="form-control" wire:model.live="role">
                                <option value="admin">Admin</option>
                                <option value="operasional">Operasional</option>
                                <option value="karyawan">Karyawan</option>
                            </select>
                            @error('role') <span class="text-danger small">{{ $message }}</span>@enderror
                        </div>

                        @if($role === 'karyawan')
                        <div class="form-group">
                            <label>Pilih Data Karyawan <span class="text-danger">*</span></label>
                            <select class="form-control" wire:model="karyawan_id">
                                <option value="">-- Pilih Karyawan --</option>
                                @foreach($karyawans as $k)
                                    <option value="{{ $k->id }}">{{ $k->nama }} ({{ $k->nip }})</option>
                                @endforeach
                            </select>
                            @error('karyawan_id') <span class="text-danger small">{{ $message }}</span>@enderror
                            <small class="text-muted">User dengan role Karyawan harus dihubungkan ke data Karyawan agar bisa melihat datanya sendiri.</small>
                        </div>
                        @endif

                        <div class="form-group">
                            <label>Password {{ $isEdit ? '(kosongkan jika tidak ubah)' : '*' }}</label>
                            <input type="password" class="form-control" wire:model="password">
                            @error('password') <span class="text-danger small">{{ $message }}</span>@enderror
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" wire:click="store" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</div>

@section('style')
<style>
    /* Ensure modal-backdrop doesn't trap the modal */
    .modal {
        z-index: 1050 !important;
    }
    /* Hide template-specific overlay when modal is shown to prevent double darkness */
    body.modal-open .overlay {
        display: none !important;
    }

    .modal-dialog {
        max-width: 500px;
        margin: 5.75rem auto!important;
    }

    .modal-backdrop.show {
        display: none!important;
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('show-modal', () => {
            $('#userModal').modal('show');
        });
        Livewire.on('hide-modal', () => {
            $('#userModal').modal('hide');
        });
    });
</script>
@endsection

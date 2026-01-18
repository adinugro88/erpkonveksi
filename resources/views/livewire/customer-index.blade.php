@section('Judul', 'Customer')
@section('action', 'Daftar Customer')

<div class="container container-fluid">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <div class="row align-items-center">
                <div class="col">
                    <h4 class="card-title m-0"><i class="material-icons mr-2 text-info align-middle">people</i> Daftar Customer</h4>
                </div>
                <div class="col-auto d-flex align-items-center">
                    <div class="search-form search-form--light mr-3">
                        <input type="text" class="form-control" placeholder="Cari nama customer..." wire:model.live="search">
                        <button class="btn" type="button"><i class="material-icons">search</i></button>
                    </div>
                    <a href="{{ route('customers.create') }}" class="btn btn-info shadow-sm" wire:navigate>
                        <i class="material-icons mr-1">add</i> Tambah Customer
                    </a>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th width="50" class="text-center">#</th>
                            <th>Nama Customer</th>
                            <th>No. Telepon</th>
                            <th>Email</th>
                            <th class="text-center">Jobs</th>
                            <th width="200" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $customer)
                            <tr>
                                <td class="text-center">{{ ($customers->currentPage()-1) * $customers->perPage() + $loop->iteration }}</td>
                                <td class="font-weight-bold">{{ $customer->nama }}</td>
                                <td>{{ $customer->phone }}</td>
                                <td>{{ $customer->email }}</td>
                                <td class="text-center">
                                    <a href="{{ route('customers.pekerjaan', $customer->id) }}" class="btn btn-lg ml-3 btn-outline-success">
                                        <i class="material-icons icon-14pt mr-1">work</i> Lihat Pekerjaan
                                    </a>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <button wire:click="viewCustomer({{ $customer->id }})" class="btn btn-lg ml-3 btn-outline-info" title="Detail">
                                            <i class="material-icons icon-16pt">visibility</i>
                                        </button>
                                        <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-lg ml-3 btn-outline-warning" wire:navigate title="Edit">
                                            <i class="material-icons icon-16pt">edit</i>
                                        </a>
                                        <button onclick="confirm('Yakin ingin menghapus customer ini?') || event.stopImmediatePropagation()" wire:click="deleteCustomer({{ $customer->id }})" class="btn btn-lg ml-3 btn-outline-danger" title="Hapus">
                                            <i class="material-icons icon-16pt">delete</i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">Belum ada data customer</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($customers->hasPages())
            <div class="card-footer bg-white border-0 py-3">
                {{ $customers->links() }}
            </div>
        @endif
    </div>

    <!-- Detail Customer Modal -->
    <div class="modal fade @if($showModal) show @endif" id="customerModal" tabindex="-1" role="dialog" 
         @if($showModal) style="display: block; background: rgba(0,0,0,0.5);" @endif>
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-info text-white border-0">
                    <h5 class="modal-title font-weight-bold text-white">
                        <i class="material-icons mr-2 align-middle">person_outline</i> 
                        Informasi Customer
                    </h5>
                    <button type="button" class="close text-white" wire:click="closeModal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4">
                    @if($selectedCustomer)
                        <div class="row align-items-center mb-4">
                            <div class="col-auto">
                                <div class="avatar avatar-xl bg-light text-info rounded-circle d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                    <i class="material-icons" style="font-size: 48px;">business</i>
                                </div>
                            </div>
                            <div class="col">
                                <h3 class="mb-0 font-weight-bold">{{ $selectedCustomer->nama }}</h3>
                                <p class="text-muted mb-0"><i class="material-icons icon-14pt align-middle mr-1">email</i> {{ $selectedCustomer->email }}</p>
                            </div>
                        </div>

                        <div class="row bg-light rounded m-0 p-3 pt-4">
                            <div class="col-md-6 mb-4">
                                <label class="text-muted small font-weight-bold text-uppercase mb-1 d-block">No. Telepon / WhatsApp</label>
                                <div class="font-weight-bold h5 mb-0 text-dark"><i class="material-icons icon-16pt align-middle mr-1 text-success">phone</i> {{ $selectedCustomer->phone ?? '-' }}</div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="text-muted small font-weight-bold text-uppercase mb-1 d-block">Terdaftar Pada</label>
                                <div class="font-weight-bold h5 mb-0 text-dark">{{ $selectedCustomer->created_at->format('d F Y') }}</div>
                            </div>
                            <div class="col-12">
                                <label class="text-muted small font-weight-bold text-uppercase mb-1 d-block">Alamat Lengkap</label>
                                <div class="font-weight-bold text-dark h6 line-height-1-5">{{ $selectedCustomer->alamat ?? '-' }}</div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="spinner-border text-info" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light px-4" wire:click="closeModal">Tutup</button>
                    @if($selectedCustomer)
                        <a href="{{ route('customers.edit', $selectedCustomer->id) }}" class="btn btn-info px-5 shadow-sm" wire:navigate>
                            <i class="material-icons mr-1">edit</i> Edit Customer
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@section('style')
    <style>
        .modal-dialog.modal-lg {
            margin-top: 100px;
        }
    </style>
@endsection


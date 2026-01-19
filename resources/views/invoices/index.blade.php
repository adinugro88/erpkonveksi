<x-app-layout>
    @section('Judul','Invoice')
    @section('action','All Invoice')

    <div class="container page__container">

        <div class="card p-4">

            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">


                <!-- Filter Form -->
                <form action="{{ route('invoices.index') }}" method="GET"
                    class="d-flex gap-2 align-items-center flex-wrap">

                    <div class="row">
                        <div class="col-12">
                              <h3>Filter Invoice</h3>
                                <hr>
                        </div>
                      
                        <div class="col-12 col-md-6 my-3">
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light">Dari</span>
                                <input type="date" name="start_date" class="form-control "
                                    value="{{ request('start_date') }}">
                            </div>
                        </div>
                        <div class="col-12 col-md-6 my-3">
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light">Sampai</span>
                                <input type="date" name="end_date" class="form-control"
                                    value="{{ request('end_date') }}">
                            </div>
                        </div>
                        <div class="col-6 col-md-4 my-3">
                            <button type="submit" class="btn btn-lg btn-secondary">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                            
                        </div>
                        <div class="col-6 col-md-2 my-3">
                            @if(request('start_date') || request('end_date'))
                            <a href="{{ route('invoices.index') }}" class="btn btn-lg btn-outline-secondary"
                                title="Reset Filter">
                                <i class="fas fa-undo"></i>
                            </a>
                            @endif
                        </div>
                       
                        <div class="col-12 my-3">
                             <hr>
                            <a href="{{ route('invoices.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Buat Invoice Baru
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <!-- Desktop View (Table) -->
            <div class="d-none d-md-block table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th width="5%">#</th>
                            <th>No Invoice</th>
                            <th>Customer</th>
                            <th>Bank</th>
                            <th>Tanggal</th>
                            <th width="20%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($invoices as $invoice)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><span class="badge bg-light text-dark border">{{ $invoice->no_invoice }}</span></td>
                            <td>{{ $invoice->customer->nama }}</td>
                            <td>
                                <small class="text-muted d-block">{{ $invoice->bank->bank_name }}</small>
                                {{ $invoice->bank->account_number }}
                            </td>
                            <td>{{ \Carbon\Carbon::parse($invoice->date)->translatedFormat('d F Y') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('invoices.show', $invoice->id) }}" class="btn btn-info text-white"
                                        title="Lihat"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('invoices.edit', $invoice->id) }}"
                                        class="btn btn-warning text-white" title="Edit"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('invoices.destroy', $invoice->id) }}" method="POST"
                                        class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" title="Hapus"><i
                                                class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="fas fa-inbox fa-2x mb-2"></i><br>
                                Belum ada data invoice
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile View (Cards) -->
            <div class="d-md-none">
                @forelse ($invoices as $invoice)
                <div class="card mb-3 border shadow-sm">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h6 class="fw-bold text-primary mb-1">{{ $invoice->no_invoice }}</h6>
                                <span
                                    class="badge bg-light text-dark border">{{ \Carbon\Carbon::parse($invoice->date)->translatedFormat('d M Y') }}</span>
                            </div>

                        </div>

                        <div class="small mb-2">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted">Customer:</span>
                                <span class="fw-medium">{{ $invoice->customer->nama }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Total:</span>
                                <span class="fw-bold">Rp
                                    {{ number_format($invoice->total ?? $invoice->details->sum('total'), 0, ',', '.') }}</span>
                            </div>
                        </div>
                        <div class="small mb-2 col-12">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted">Action:</span>
                            </div>
                            <div class=" d-flex justify-content-between">
                                <form action="{{ route('invoices.destroy', $invoice->id) }}" method="POST"
                                    class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn ml-3 btn-lg btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>

                                <a href="{{ route('invoices.edit', $invoice->id) }}"
                                    class="ml-3 btn btn-lg btn-warning text-white">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('invoices.show', $invoice->id) }}"
                                    class="ml-3 btn btn-lg btn-info text-white">
                                    <i class="fas fa-eye"></i>
                                </a>

                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-5 text-muted bg-light rounded">
                    <i class="fas fa-search fa-2x mb-2"></i><br>
                    Data tidak ditemukan
                </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>

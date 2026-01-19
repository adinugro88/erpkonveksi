<x-app-layout>

    @section('Judul','Customer')
    @section('action','Tambah Customer')

    <div class="container-fluid page__container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3">
                        <h4 class="card-title m-0"><i class="material-icons mr-2 text-warning align-middle">edit</i> Edit Informasi Customer</h4>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('customers.update', $customer->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label font-weight-bold" for="nama">Nama Lengkap / Instansi</label>
                                    <div class="input-group input-group-merge">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="material-icons">person</i></div>
                                        </div>
                                        <input type="text" name="nama" id="nama" class="form-control" value="{{ $customer->nama }}" placeholder="Masukkan nama..." required>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label font-weight-bold" for="phone">No. Telepon / WA</label>
                                    <div class="input-group input-group-merge">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="material-icons">phone</i></div>
                                        </div>
                                        <input type="text" name="phone" id="phone" class="form-control" value="{{ $customer->phone }}" placeholder="08...">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label font-weight-bold" for="email">Alamat Email</label>
                                    <div class="input-group input-group-merge">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="material-icons">email</i></div>
                                        </div>
                                        <input type="email" name="email" id="email" class="form-control" value="{{ $customer->email }}" placeholder="email@example.com">
                                    </div>
                                </div>
                                <div class="col-12 mb-4">
                                    <label class="form-label font-weight-bold" for="alamat">Alamat Lengkap</label>
                                    <textarea name="alamat" id="alamat" class="form-control" rows="3" placeholder="Masukkan alamat lengkap...">{{ $customer->alamat }}</textarea>
                                </div>
                            </div>
                            <hr class="my-4">
                            <div class="d-flex justify-content-end">
                                <a href="{{ route('customers.index') }}" class="btn btn-light px-4 mr-2">Batal</a>
                                <button type="submit" class="btn btn-warning px-5 shadow-sm text-white">
                                    <i class="material-icons mr-1 text-white">update</i> Update Customer
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

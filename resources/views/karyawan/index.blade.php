<x-app-layout>
    @section('Judul','Karyawan')
    @section('action','All Karyawan')

    <div class="container page__container">
        
        <hr>

        <div class="card">
            <div class="card-header d-flex align-items-center">
            <div class="col-6">
                <h4>Data Karyawan</h4>
            </div>
            <div class="col-6 text-end">
                <a href="{{ route('karyawan.create') }}" class="btn btn-primary">Tambah
                    Karyawan</a>
            </div>
        </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">NIP</th>
                                <th scope="col">Nama</th>
                                <th scope="col">Email</th>
                                <th scope="col">Nomer HP</th>
                                <th scope="col">Alamat</th>
                                <th scope="col">Status</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{Str::limit($item->nip, 5, '...')  }}</td>
                                <td>{{ $item->nama }}</td>
                                <td>{{ $item->email }}</td>
                                <td>{{ $item->no_hp }}</td>
                                <td>{{ $item->alamat }}</td>
                                <td>
                                    <span class="badge badge-{{ $item->status == 1 ? 'success' : 'danger' }}">
                                        {{ $item->status == 1 ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <a href="{{ route('karyawan.edit', $item->id) }}"
                                            class="btn btn-sm btn-warning me-2">
                                            <i class="material-icons text-white icon-18pt">edit</i> 
                                        </a>
                                        <livewire:karyawan.delete :karyawan-id="$item->id" :key="$item->id"/>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    <style>
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            /* Mendukung smooth scrolling di perangkat mobile */
        }

        .table {
            width: 100%;
            margin-bottom: 1rem;
            background-color: transparent;
        }

    </style>
</x-app-layout>

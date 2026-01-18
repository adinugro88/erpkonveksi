<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Gajian') }}
        </h2>
    </x-slot>

    <div class="row mb-3">
        <div class="col">
            <h4>Detail Gaji</h4>
        </div>
        {{-- <div class="col text-end">
            <a href="{{ route('gajian.index') }}" class="btn btn-sm btn-secondary">Kembali</a>
            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addDetailModal">Tambah
                Detail</button>
        </div> --}}
    </div>

    <div class="card mb-4">
        <div class="card-header">Informasi Gaji</div>
        <div class="card-body">
            <p><strong>Kode:</strong> {{ $gajian->no_kode }}</p>
            <p><strong>Tanggal:</strong> {{ $gajian->tanggal }}</p>
            <p><strong>Nama Karyawan:</strong> {{ $gajian->karyawan->nama }}</p>
        </div>
    </div>

    {{-- <div class="card">
        <div class="card-header">Detail Gaji</div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Deskripsi</th>
                        <th scope="col">Jumlah</th>
                        <th scope="col">Fee</th>
                        <th scope="col">Potongan</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($detailGaji as $item)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>{{ $item->deskripsi }}</td>
                            <td>{{ $item->jumlah ?? '-' }}</td>
                            <td>{{ $item->fee ?? '-' }}</td>
                            <td>{{ $item->potongan ?? '-' }}</td>
                            <td>
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                    data-bs-target="#editDetailModal{{ $item->id }}">Edit</button>
                                <form action="{{ route('detail-gaji.destroy', $item->id) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                </form>

                                <!-- Edit Modal -->
                                <div class="modal fade" id="editDetailModal{{ $item->id }}" tabindex="-1"
                                    aria-labelledby="editDetailModalLabel{{ $item->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editDetailModalLabel{{ $item->id }}">
                                                    Edit Detail Gaji</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('detail-gaji.update', $item->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="deskripsi" class="form-label">Deskripsi</label>
                                                        <input type="text" class="form-control" id="deskripsi"
                                                            name="deskripsi" value="{{ $item->deskripsi }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="jumlah" class="form-label">Jumlah</label>
                                                        <input type="number" class="form-control" id="jumlah"
                                                            name="jumlah" value="{{ $item->jumlah }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="fee" class="form-label">Fee</label>
                                                        <input type="number" class="form-control" id="fee"
                                                            name="fee" value="{{ $item->fee }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="potongan" class="form-label">Potongan</label>
                                                        <input type="number" class="form-control" id="potongan"
                                                            name="potongan" value="{{ $item->potongan }}">
                                                    </div>
                                                    <input type="hidden" name="gaji_id" value="{{ $gajian->id }}">
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada detail gaji</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div> --}}

    {{-- <!-- Add Modal -->
    <div class="modal fade" id="addDetailModal" tabindex="-1" aria-labelledby="addDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addDetailModalLabel">Tambah Detail Gaji</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('detail-gaji.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <input type="text" class="form-control" id="deskripsi" name="deskripsi" required>
                        </div>
                        <div class="mb-3">
                            <label for="jumlah" class="form-label">Jumlah</label>
                            <input type="number" class="form-control" id="jumlah" name="jumlah">
                        </div>
                        <div class="mb-3">
                            <label for="fee" class="form-label">Fee</label>
                            <input type="number" class="form-control" id="fee" name="fee">
                        </div>
                        <div class="mb-3">
                            <label for="potongan" class="form-label">Potongan</label>
                            <input type="number" class="form-control" id="potongan" name="potongan">
                        </div>
                        <input type="hidden" name="gaji_id" value="{{ $gajian->id }}">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div> --}}

    <livewire:gajian-detail-edit :id="$gajian->id" />

    </x-app-layout>

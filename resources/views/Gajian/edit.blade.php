<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gajian') }}
        </h2>
    </x-slot>

    <div class="row">
        <div class="col">
            <h4>Edit Data Gajian</h4>
        </div>
    </div>
    <hr>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('gajian.update', $gajian->id) }}" method="post">
                @csrf
                @method('put')
                <div class="mb-3">
                    <label for="no_kode" class="form-label">Kode</label>
                    <input type="text" class="form-control" id="no_kode" name="no_kode"
                        value="{{ $gajian->no_kode }}" required>
                </div>
                <div class="mb-3">
                    <label for="tanggal" class="form-label">Tanggal</label>
                    <input type="date" class="form-control" id="tanggal" name="tanggal"
                        value="{{ $gajian->tanggal }}" required>
                </div>
                <div class="mb-3">
                    <label for="karyawan_id" class="form-label">Nama Karyawan</label>
                    <select class="form-select" id="karyawan_id" name="karyawan_id" required>
                        <option selected>Pilih Karyawan</option>
                        @foreach ($karyawan as $item)
                            <option value="{{ $item->id }}"
                                {{ $gajian->karyawan_id == $item->id ? 'selected' : '' }}>{{ $item->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>
</x-app-layout>

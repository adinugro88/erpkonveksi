<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gajian') }}
        </h2>
    </x-slot>

    <div class="row">
        <div class="col">
            <h4>Tambah Data Gajian</h4>
        </div>
    </div>
    <hr>

    {{-- <div class="card">
        <div class="card-body">
            <form action="{{ route('gajian.store') }}" method="post">
                @csrf
                <div class="mb-3">
                    <label for="tanggal" class="form-label">Tanggal</label>
                    <input type="date" class="form-control" id="tanggal" name="tanggal" required>
                </div>
                <div class="mb-3">
                    <label for="karyawan_id" class="form-label">Nama Karyawan</label>
                    <select class="form-select" id="karyawan_id" name="karyawan_id" required>
                        <option selected>Pilih Karyawan</option>
                        @foreach ($karyawan as $item)
                            <option value="{{ $item->id }}">{{ $item->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
    </div> --}}

    <livewire:gajian-detail />

</x-app-layout>

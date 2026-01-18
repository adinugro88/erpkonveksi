<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gajian') }}
        </h2>
    </x-slot>

    <div class="row">
        <div class="col">
            <h4>Data Gajian</h4>
        </div>
        <div class="col text-end">
            <a href="{{ route('gajian.create') }}" class="btn btn-sm btn-primary" wire:navigate>Tambah Gajian</a>
        </div>
    </div>
    <hr>

    {{-- <div class="card">
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Kode</th>
                        <th scope="col">Tanggal</th>
                        <th scope="col">Nama</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($gajian as $item)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>{{ $item->no_kode }}</td>
                            <td>{{ $item->tanggal }}</td>
                            <td>{{ $item->karyawan->nama }}</td>
                            <td>
                                <div class="d-flex">
                                    <a href="{{ route('gajian.print', $item->id) }}" class="btn btn-sm btn-secondary me-2"><i
                                            class="bi bi-printer"></i></a>
                                    <a href="{{ route('gajian.show', $item->id) }}" class="btn btn-sm btn-info me-2"
                                        wire:navigate><i class="bi bi-eye"></i></a>
                                    <a href="{{ route('gajian.edit', $item->id) }}" class="btn btn-sm btn-warning me-2"
                                        wire:navigate><i class="bi bi-pencil-square"></i></a>
                                    <form action="{{ route('gajian.destroy', $item->id) }}" method="post"
                                        class="d-inline">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                    @endforeach
                </tbody>
            </table>

        </div> --}}
    {{-- </div> --}}
    <livewire:gajian />
</x-app-layout>

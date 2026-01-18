<x-app-layout>
    @section('Judul','Master Bank')
    @section('action','List Bank')

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Banks') }}
        </h2>
    </x-slot>

    <div class="card container">
        <div class="card-body">
            @if ($banks->count() < 1)
                <a href="{{ route('banks.create') }}" class="btn btn-primary mb-3">Add New Bank</a>
            @endif
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Informasi</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($banks as $bank)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $bank->informasi }}</td>
                            <td>
                                <a href="{{ route('banks.edit', $bank->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('banks.destroy', $bank->id) }}" method="POST"
                                    style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">No Banks Found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>

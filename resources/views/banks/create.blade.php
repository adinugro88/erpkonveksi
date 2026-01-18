<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add New Bank') }}
        </h2>
    </x-slot>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('banks.store') }}" method="POST">
                @csrf
                <div class="form-group mb-3">
                    <label for="informasi">Informasi</label>
                    <input type="text" name="informasi" id="informasi" class="form-control" value="{{ old('informasi') }}" required>
                </div>
                <button type="submit" class="btn btn-success">Save</button>
                <a href="{{ route('banks.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</x-app-layout>

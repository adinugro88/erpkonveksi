<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Waybills') }}
        </h2>
    </x-slot>

    <div class="container mt-4">
        <a href="{{ route('waybills.create') }}" class="btn btn-primary mb-3">Create Waybill</a>

        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Customer</th>
                    <th>Code</th>
                    <th>Date</th>
                    <th>Notes</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($waybills as $waybill)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $waybill->customer->name }}</td>
                        <td>{{ $waybill->code }}</td>
                        <td>{{ $waybill->date }}</td>
                        <td>{{ $waybill->notes }}</td>
                        <td>
                            <a href="{{ route('waybills.edit', $waybill->id) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('waybills.destroy', $waybill->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>

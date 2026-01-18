<x-app-layout>
    @section('Judul','Invoice')
    @section('action','All Invoice')

    <div class="container page__container">

    <div class="card p-4">
        <a href="{{ route('invoices.create') }}" class="btn btn-primary mb-3">Buat Invoice Baru</a>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered table-responsive">
            <thead>
                <tr>
                    <th>#</th>
                    <th>No Invoice</th>
                    <th>Customer</th>
                    <th>Bank</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($invoices as $invoice)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $invoice->no_invoice }}</td>
                        <td>{{ $invoice->customer->nama }}</td>
                        <td>{{ $invoice->bank->informasi }}</td>
                        <td>{{ $invoice->date }}</td>
                        <td>
                            <a href="{{ route('invoices.show', $invoice->id) }}" class="btn btn-info btn-sm">Lihat</a>
                            <a href="{{ route('invoices.edit', $invoice->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('invoices.destroy', $invoice->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    </div>
</x-app-layout>

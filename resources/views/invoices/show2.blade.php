<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Invoice') }}
        </h2>
    </x-slot>

    <div class="container">
        <h1>Detail Invoice </h1>

        <p><strong>No Invoice:</strong> {{ $invoice->no_invoice }}</p>
        <p><strong>Customer:</strong> {{ $invoice->customer->nama }}</p>
        <p><strong>Bank:</strong> {{ $invoice->bank->informasi }}</p>
        <p><strong>Tanggal:</strong> {{ $invoice->date }}</p>

        <h3>Detail</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Deskripsi</th>
                    <th>Qty</th>
                    <th>Harga</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($invoice->details as $detail)
                    <tr>
                        <td>{{ $detail->tanggal }}</td>
                        <td>{{ $detail->deskripsi }}</td>
                        <td>{{ $detail->qty }}</td>
                        <td>{{ number_format($detail->price, 2) }}</td>
                        <td>{{ number_format($detail->qty * $detail->price, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>

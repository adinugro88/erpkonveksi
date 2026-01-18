<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Slip Gaji</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            margin: 0 auto;
            padding: 0px;
            border: 1px solid #000;
            margin-top: 30px;
        }
        .header {
            background-color: aquamarine;
            text-align: center;
            margin-bottom: 30px;
            padding: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .info-section {
            margin-bottom: 20px;
        }
        .info-section p {
            margin-bottom: 5px;
            margin-left: 10px;
            font-size: 16px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .table th, .table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
        }
        .table th {
            background-color: #282828;
            color: white;
        }
        .total {
            font-weight: bold;
            text-align: right;
        }
        .red-text {
            color: red;
        }
        @media print {
            .container {
                border: none;
                margin: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1> SLIP-GAJI CV JANGKUNG RAHARJA  </h1>
        </div>

        <div class="info-section">
            <p><strong>Nama:</strong> {{ $gajian->karyawan->nama }}</p>
            <p><strong>Tanggal:</strong> {{ $gajian->tanggal }}</p>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Deskripsi</th>
                    <th>Jumlah</th>
                    <th>Harga</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total = 0;
                @endphp
                @forelse ($detailGaji as $item)
                    <tr class="{{ $item->potongan ? 'red-text' : '' }}">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->deskripsi }}</td>
                        <td>{{ number_format($item->jumlah, 0, ',', '.') }}</td>
                        <td>{{ number_format($item->fee, 0, ',', '.') }}</td>
                        <td>{{ number_format(($item->jumlah * $item->fee) - ($item->potongan ?? 0), 0, ',', '.') }}</td>
                    </tr>
                    @php
                        $total += ($item->jumlah * $item->fee) - ($item->potongan ?? 0);
                    @endphp
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada detail gaji</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="total">Total</td>
                    <td><strong> Rp {{ number_format($total, 0, ',', '.') }} </strong></td>
                </tr>
            </tfoot>
        </table>
    </div>

    {{-- <script>
        window.onload = function() {
            window.print();
        };
    </script> --}}
</body>
</html>

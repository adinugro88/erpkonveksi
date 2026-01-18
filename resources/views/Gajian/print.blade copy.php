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
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #000;
            margin-top: 30px;
            page-break-after: always;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .info-section {
            margin-bottom: 20px;
        }
        .info-section p {
            margin: 0;
            font-size: 14px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .table th, .table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        .table th {
            background-color: #f2f2f2;
        }
        .total {
            font-weight: bold;
            text-align: right;
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
            <h1>Slip Gaji</h1>
            <p><strong>Perusahaan:</strong> Nama Perusahaan Anda</p>
        </div>

        <div class="info-section">
            <p><strong>Tanggal:</strong> {{ $gajian->tanggal }}</p>
            <p><strong>Nama Karyawan:</strong> {{ $gajian->karyawan->nama }}</p>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Deskripsi</th>
                    <th>Jumlah</th>
                    <th>Fee</th>
                    <th>Potongan</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total = 0;
                @endphp
                @forelse ($detailGaji as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->deskripsi }}</td>
                        <td>{{ number_format($item->jumlah, 0, ',', '.') ?? '-' }}</td>
                        <td>{{ number_format($item->fee, 0, ',', '.') ?? '-' }}</td>
                        <td>{{ number_format($item->potongan, 0, ',', '.') ?? '-' }}</td>
                    </tr>
                    @php
                        $total += $item->jumlah - ($item->potongan ?? 0) + ($item->fee ?? 0);
                    @endphp
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada detail gaji</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="total">Total Gaji</td>
                    <td>{{ number_format($total, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>

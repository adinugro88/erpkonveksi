<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Slip Gaji - {{ $gajian->karyawan->nama }}</title>
    <style>
        @page {
            margin: 0.5cm;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11pt;
            color: #333;
            line-height: 1.4;
            margin: 0;
            padding: 10px;
            page-break-inside: avoid;
        }
        .header-table {
            width: 100%;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .logo {
            width: 140px;
        }
        .title-area {
            text-align: right;
        }
        .title-area h1 {
            margin: 0;
            color: #1a73e8;
            font-size: 20pt;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .title-area p {
            margin: 2px 0 0;
            color: #666;
            font-weight: bold;
        }
        
        .info-table {
            width: 100%;
            margin-bottom: 25px;
        }
        .info-table td {
            vertical-align: top;
            width: 50%;
        }
        .info-label {
            color: #666;
            font-size: 9pt;
            text-transform: uppercase;
            font-weight: bold;
            margin-bottom: 2px;
        }
        .info-value {
            font-size: 12pt;
            font-weight: bold;
        }

        .main-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .main-table th {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            padding: 10px;
            text-align: left;
            font-size: 9pt;
            color: #495057;
            text-transform: uppercase;
        }
        .main-table td {
            padding: 10px;
            border-bottom: 1px solid #eee;
            vertical-align: middle;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        
        .row-potongan {
            color: #dc3545;
        }

        .footer-table {
            width: 100%;
        }
        .summary-box {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 15px;
            text-align: right;
            border-radius: 4px;
        }
        .total-label {
            font-size: 10pt;
            color: #666;
            margin-bottom: 5px;
        }
        .total-amount {
            font-size: 18pt;
            font-weight: bold;
            color: #1a73e8;
        }

        .signature-area {
            margin-top: 50px;
            text-align: center;
        }
        .signature-line {
            margin-top: 60px;
            border-top: 1px solid #000;
            width: 200px;
            display: inline-block;
        }
        
        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 8pt;
            font-weight: bold;
            display: inline-block;
        }
        .bg-success { background-color: #d4edda; color: #155724; }
        .bg-danger { background-color: #f8d7da; color: #721c24; }

    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td width="50%">
                <img src="{{ public_path('assetsinv/img/logocvjr.png') }}" class="logo" alt="Logo">
            </td>
            <td class="title-area">
                <h1>SLIP GAJI</h1>
                <p>CV JANGKUNG RAHARJA</p>
                <small style="color: #888;">Periode: {{ \Carbon\Carbon::parse($gajian->tanggal)->format('F Y') }}</small>
            </td>
        </tr>
    </table>

    <table class="info-table">
        <tr>
            <td>
                <div class="info-label">KARYAWAN</div>
                <div class="info-value">{{ $gajian->karyawan->nama }}</div>
                <div style="color: #666; font-size: 10pt;">NIP: {{ $gajian->karyawan->nip ?? '-' }}</div>
            </td>
            <td class="text-right">
                <div class="info-label">TANGGAL CETAK</div>
                <div class="info-value">{{ \Carbon\Carbon::parse($gajian->tanggal)->format('j F Y') }}</div>
                <div style="color: #666; font-size: 10pt;">No. Referensi: {{ $gajian->no_kode }}</div>
            </td>
        </tr>
    </table>

    <table class="main-table">
        <thead>
            <tr>
                <th width="30">NO</th>
                <th>DESKRIPSI</th>
                <th class="text-center">QTY</th>
                <th class="text-right">NOMINAL/FEE</th>
                <th class="text-right">SUBTOTAL</th>
            </tr>
        </thead>
        <tbody>
            @php $netto = 0; @endphp
            @foreach ($detailGaji as $item)
                @php 
                    $isPotongan = !is_null($item->potongan);
                    $subtotal = $isPotongan ? $item->potongan : ($item->jumlah * $item->fee);
                    $netto += $isPotongan ? -$subtotal : $subtotal;
                @endphp
                <tr class="{{ $isPotongan ? 'row-potongan' : '' }}">
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>
                        <div style="font-weight: bold;">{{ $item->deskripsi }}</div>
                        <small class="badge {{ $isPotongan ? 'bg-danger' : 'bg-success' }}">
                            {{ $isPotongan ? 'PENGURANGAN' : 'PENAMBAHAN' }}
                        </small>
                    </td>
                    <td class="text-center">{{ $isPotongan ? '-' : number_format($item->jumlah, 0, ',', '.') }}</td>
                    <td class="text-right">
                        {{ $isPotongan ? '-' : 'Rp ' . number_format($item->fee, 0, ',', '.') }}
                    </td>
                    <td class="text-right" style="font-weight: bold;">
                        {{ $isPotongan ? '-' : '' }}Rp {{ number_format($subtotal, 0, ',', '.') }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="footer-table">
        <tr>
            <td width="60%">
                <div style="font-size: 9pt; color: #666; font-style: italic;">
                    * Slip gaji ini dihasilkan secara otomatis oleh sistem.<br>
                    * Harap simpan slip ini sebagai bukti pembayaran yang sah.
                </div>
            </td>
            <td width="40%" vertical-align="top">
                <div class="summary-box">
                    <div class="total-label">TOTAL GAJI NETTO</div>
                    <div class="total-amount">Rp {{ number_format($netto, 0, ',', '.') }}</div>
                    <div style="font-size: 8pt; color: #888; margin-top: 10px;">
                        Status Pembayaran: <span style="color: green; font-weight: bold;">LUNAS</span>
                    </div>
                </div>
            </td>
        </tr>
    </table>

</body>
</html>

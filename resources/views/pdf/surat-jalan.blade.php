<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Surat Jalan - {{ $suratJalan->kode_sj }}</title>
    @if(isset($previewMode) && $previewMode)
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
            body { font-size: 0.75em !important; } /* Smaller base font for mobile */
            .invoice-container { padding: 10px !important; } /* Minimal padding */
            
            /* Compact Table for Mobile Preview */
            .invoice-table th, .invoice-table td {
                padding: 4px 6px !important; /* drastically reduce padding */
                font-size: 0.9em !important;
            }
            .invoice-table th { font-size: 0.8em !important; }
            h1.big-title { font-size: 24px !important; } /* Scale down title */
            .header-logo img { width: 100px !important; } /* Scale down logo */
            
            /* Ensure table fits */
            .invoice-table { width: 100% !important; table-layout: fixed; }
            .invoice-table td { word-wrap: break-word; overflow-wrap: break-word; }
        </style>
    @endif
    <style>
        @page { margin: 0; }
        body {
            font-family: 'Nunito Sans', sans-serif;
            color: #6E6E6E;
            font-size: 14px;
            line-height: 22px;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
        }
        .invoice-container {
            padding: 50px 40px;
            max-width: 100%;
            margin: 0 auto;
        }
        /* Theme Colors from .hall-template */
        :root {
            --theme-color: #be7623;
            --title-color: #111111;
            --body-color: #6E6E6E;
            --white-color: #ffffff;
            --border-color: #C4C4C4;
            --smoke-color: #f3f3f3;
        }
        /* Inline CSS for DomPDF compatibility */
        .color-theme { color: #be7623; }
        .bg-theme { background-color: #be7623; }
        
        h1, h2, h3, h4, h5, h6 {
            color: #111111;
            font-weight: 700;
            margin: 0 0 15px 0;
            line-height: 1.4;
        }
        h1 { font-size: 40px; }
        p { margin: 0 0 10px 0; }
        
        /* Header */
        .header-layout5 {
            margin-bottom: 40px;
        }
        .header-logo img {
            max-width: 150px;
        }
        .big-title {
            font-size: 40px;
            color: #111111;
            margin-bottom: 10px;
            text-transform: uppercase;
        }
        .invoice-info p {
            margin-bottom: 5px;
            font-size: 14px;
        }
        .invoice-info b {
            color: #111111;
            display: inline-block;
            width: 100px;
        }

        /* Layout Grid Helper for Header */
        .row { display: table; width: 100%; }
        .col-left { display: table-cell; width: 50%; vertical-align: middle; }
        .col-right { display: table-cell; width: 50%; vertical-align: top; text-align: right; }

        /* Table */
        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            margin-bottom: 30px;
        }
        .invoice-table th, .invoice-table td {
            padding: 13px 20px;
            border: none;
            text-align: left;
        }
        .invoice-table th {
            background-color: #be7623; /* Theme Color */
            color: #ffffff;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 14px;
        }
        .invoice-table tr:nth-child(even) td {
            background-color: #f3f3f3; /* Smoke Color */
        }
        .invoice-table tr:nth-child(odd) td {
            background-color: #ffffff;
        }
        .invoice-table th:first-child, .invoice-table td:first-child {
            padding-left: 20px;
            border-top-left-radius: 5px;
            border-bottom-left-radius: 5px;
        }
        .invoice-table th:last-child, .invoice-table td:last-child {
            padding-right: 20px;
            border-top-right-radius: 5px;
            border-bottom-right-radius: 5px;
            text-align: right;
        }

        /* Footer / Notes */
        .invoice-note {
            margin-top: 40px;
            background-color: #f3f3f3;
            padding: 20px;
            border-radius: 5px;
            font-size: 13px;
        }
        .invoice-note b {
            color: #be7623;
            font-size: 16px;
            display: block;
            margin-bottom: 5px;
        }

        /* Signature Section */
        .signature-section {
            margin-top: 50px;
            width: 100%;
        }
        .signature-box {
            display: table-cell;
            width: 33%;
            text-align: center;
            vertical-align: bottom;
            height: 120px;
        }
        .signature-line {
            margin-top: 60px;
            border-top: 1px solid #C4C4C4;
            display: inline-block;
            width: 80%;
            padding-top: 5px;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="invoice-container">
        <!-- Header -->
        <div class="row header-layout5">
            <div class="col-left">
                <div class="header-logo">
                    <!-- Assuming existing asset path works, otherwise simple text fallback can be used if dompdf fails to load image -->
                     @if(isset($previewMode) && $previewMode)
                        <img src="{{ asset('assetsinv/img/logocvjr.png') }}" alt="LOGO" style="width: 150px;">
                     @else
                        <img src="{{ public_path('assetsinv/img/logocvjr.png') }}" alt="LOGO" style="width: 150px;">
                     @endif
                </div>
            </div>
            <div class="col-right invoice-info">
                <h1 class="big-title">SURAT JALAN</h1>
                <p><b>No. SJ</b>: {{ $suratJalan->kode_sj }}</p>
                <p><b>Tanggal</b>: {{ \Carbon\Carbon::parse($suratJalan->tanggal)->format('d-M-Y') }}</p>
                <p><b>Jenis</b>: <span style="text-transform: uppercase;">{{ $suratJalan->jenis }}</span></p>
            </div>
        </div>

        <div style="margin-bottom: 30px;">
            <div class="row">
                <div class="col-left" style="vertical-align: top;">
                    <p style="margin-bottom: 5px; color: #be7623; font-weight: bold;">PENGIRIM:</p>
                    <h4 style="margin:0; font-size: 18px;">
                        @if($suratJalan->jenis == 'masuk')
                            {{ $suratJalan->jobCust->Customer->nama ?? $suratJalan->pengirim }}
                        @else
                            CV JANGKUNG RAHARJA
                        @endif
                    </h4>
                    @if($suratJalan->jenis == 'masuk')
                        <p>Sopir: {{ $suratJalan->sopir ?: '-' }} ({{ $suratJalan->no_kendaraan ?: '-' }})</p>
                    @endif
                </div>
                
                <div class="col-right" style="text-align: left; padding-left: 20px;">
                     <p style="margin-bottom: 5px; color: #be7623; font-weight: bold;">PENERIMA / TUJUAN:</p>
                     <h4 style="margin:0; font-size: 18px;">
                        @if($suratJalan->jenis == 'masuk')
                            CV JANGKUNG RAHARJA
                        @else
                            {{ $suratJalan->jobCust->Customer->nama ?? $suratJalan->penerima }}
                        @endif
                     </h4>
                     <p>Job: {{ $suratJalan->jobCust->nama ?? '-' }}</p>
                     @if($suratJalan->jenis != 'masuk')
                        <p>Sopir: {{ $suratJalan->sopir ?: '-' }} ({{ $suratJalan->no_kendaraan ?: '-' }})</p>
                     @endif
                </div>
            </div>
        </div>

        <!-- Table -->
        <table class="invoice-table">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="35%">Nama Barang</th>
                    <th width="15%" style="text-align: right">Jumlah</th>
                    <th width="15%" style="text-align: center">Satuan</th>
                    <th width="30%">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($suratJalan->details as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><b>{{ $item->barang }}</b></td>
                    <td style="text-align: right">{{ $item->jumlah + 0 }}</td>
                    <td style="text-align: center">{{ $item->satuan }}</td>
                    <td>{{ $item->keterangan }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @if($suratJalan->keterangan)
        <div class="invoice-note">
            <b>Catatan:</b>
            {{ $suratJalan->keterangan }}
        </div>
        @endif

        <!-- Footer / Signature -->
        <div class="signature-section" style="display: table; width: 100%; margin-top: 50px;">
            <div class="signature-box" style="display: table-cell; width: 50%; vertical-align: top;">
                <div style="min-height: 60px;"> <!-- Fixed height container for header labels if needed to align bottom of labels, but user asked for top alignment of text 'Penerima' and 'Hormat Kami' -->
                    <span style="font-weight: bold;">Penerima,</span>
                </div>
                <div style="height: 1.5cm;"></div>
                <div class="signature-line">( ........................... )</div>
            </div>
            <div class="signature-box" style="display: table-cell; width: 50%; vertical-align: top;">
                <div style="min-height: 60px;">
                    <span style="font-weight: bold;">Hormat Kami,</span><br>
                    <strong>CV JANGKUNG RAHARJA</strong>
                </div>
                <div style="height: 1.5cm;"></div>
                <div class="signature-line">( ........................... )</div>
            </div>
        </div>
        
        <div style="margin-top: 40px; text-align: center; color: #999; font-size: 12px; border-top: 1px dashed #ccc; padding-top: 10px;">
            <p>Perum Puri 3 Blok B2 No 4 Tigaraksa Tangerang | 087878933090</p>
        </div>

        @if(isset($previewMode) && $previewMode)
            <div style="margin-top: 50px; text-align: center;" class="no-print">
                 <a href="{{ route('surat-jalan.print', $suratJalan->id) }}" style="background-color: #be7623; color: white; padding: 15px 30px; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 16px;">
                    DOWNLOAD PDF
                 </a>
            </div>
            <style>
                @media print {
                    .no-print { display: none; }
                }
            </style>
        @endif

    </div>

</body>
</html>

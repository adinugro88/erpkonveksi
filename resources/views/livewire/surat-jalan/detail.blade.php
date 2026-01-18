@section('Judul', 'Detail Surat Jalan')
@section('action', $suratJalan->kode_sj)

<div class="container pb-5">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center bg-white border-bottom-0">
            <h5 class="mb-0">Informasi Surat Jalan</h5>
            <div>
                <a href="{{ route('surat-jalan.preview', $suratJalan->id) }}" target="_blank" class="btn btn-info mb-3">
                    <i class="fas fa-file-pdf"></i> Download / Print PDF
                </a>
                <a href="{{ route('surat-jalan.index') }}" class="btn btn-secondary mb-3 ml-3">Kembali</a>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <td width="150" class="font-weight-bold">Kode SJ</td>
                            <td>: {{ $suratJalan->kode_sj }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Tanggal</td>
                            <td>: {{ \Carbon\Carbon::parse($suratJalan->tanggal)->format('d F Y') }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Jenis</td>
                            <td>
                                : <span class="badge badge-{{ $suratJalan->jenis == 'keluar' ? 'warning' : 'success' }}">
                                    {{ strtoupper($suratJalan->jenis) }}
                                  </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Job / Project</td>
                            <td>: {{ $suratJalan->jobCust->nama ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <td width="150" class="font-weight-bold">Pengirim</td>
                            <td>: {{ $suratJalan->pengirim ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Penerima</td>
                            <td>: {{ $suratJalan->penerima ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Sopir</td>
                            <td>: {{ $suratJalan->sopir ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">No. Kendaraan</td>
                            <td>: {{ $suratJalan->no_kendaraan ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <h5 class="mb-3">Daftar Barang</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="thead-light">
                        <tr>
                            <th width="50">No</th>
                            <th>Nama Barang</th>
                            <th width="150" class="text-right">Jumlah</th>
                            <th width="100">Satuan</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($suratJalan->details as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->barang }}</td>
                            <td class="text-right">{{ $item->jumlah + 0 }}</td> {{-- +0 to remove trailing zeros if float --}}
                            <td>{{ $item->satuan }}</td>
                            <td>{{ $item->keterangan }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($suratJalan->keterangan)
            <div class="mt-4">
                <h6>Keterangan Tambahan:</h6>
                <div class="p-3 bg-light rounded border">
                    {{ $suratJalan->keterangan }}
                </div>
            </div>
            @endif

        </div>
    </div>
</div>

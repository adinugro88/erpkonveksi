@section('Judul','Surat Jalan')
@section('action','Daftar Surat Jalan')

<div class="container">
    <div class="card p-3">
        <div class="row mb-3">
            <div class="col-md-4">
                <input type="text" class="form-control" placeholder="Cari Kode SJ atau Job..." wire:model.live="search">
            </div>
            <div class="col-md-3 my-3">
                <select class="form-control" wire:model.live="filterJenis">
                    <option value="">-- Semua Jenis --</option>
                    <option value="keluar">SJ KELUAR (OUT)</option>
                    <option value="masuk">SJ MASUK (IN)</option>
                </select>
            </div>
            <div class="col-md-5 text-right">
                <a href="{{ route('surat-jalan.form') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Buat Surat Jalan Baru
                </a>
            </div>
        </div>

        @if (session()->has('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th width="50">No</th>
                        <th>Kode SJ</th>
                        <th>Tanggal</th>
                        <th>Job / Customer</th>
                        <th>Jenis</th>
                        <th>Item(s)</th>
                        <th>Driver</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php $i = ($suratJalans->currentPage() - 1) * $suratJalans->perPage(); @endphp
                    @forelse ($suratJalans as $sj)
                        <tr>
                            <td>{{ ++$i }}</td>
                            <td><code>{{ $sj->kode_sj }}</code></td>
                            <td>{{ \Carbon\Carbon::parse($sj->tanggal)->format('d/m/Y') }}</td>
                            <td>
                                <strong>{{ $sj->jobCust->nama }}</strong><br>
                                <small class="text-muted">{{ $sj->jobCust->Customer->nama ?? '-' }}</small>
                            </td>
                            <td>
                                <span class="badge badge-{{ $sj->jenis == 'keluar' ? 'warning' : 'success' }}">
                                    {{ strtoupper($sj->jenis) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-info">{{ $sj->details_count }} Item</span>
                            </td>
                            <td>{{ $sj->sopir ?: '-' }}</td>
                            <td>
                                <div class="d-flex flex-wrap" style="gap: 5px;">
                                    <a href="{{ route('surat-jalan.detail', $sj->id) }}" class="btn btn-lg btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('surat-jalan.form', $sj->id) }}" class="btn btn-lg btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button onclick="confirm('Yakin ingin menghapus?') || event.stopImmediatePropagation()" 
                                            wire:click="delete({{ $sj->id }})" class="btn btn-lg btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">Data tidak ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $suratJalans->links() }}
        </div>
    </div>
</div>

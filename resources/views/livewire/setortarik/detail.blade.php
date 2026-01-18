@section('Judul','Detail Setor Ambil')
@section('action','Setor Ambil Karyawan')

<div class="container">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-4">
                    <h5><strong>Karyawan:</strong> {{ $mastersetortarik->karyawan->nama }}</h5>
                    <p><strong>NIP:</strong> {{ $mastersetortarik->karyawan->nip }}</p>
                </div>
                <div class="col-12 col-md-8">
                    <h5><strong>Tugas:</strong> {{ $mastersetortarik->jobCust->nama }}</h5>
                    <p><strong>Keterangan:</strong> {{ $mastersetortarik->jobCust->keterangan }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-body">
            <div class="row">
                <div class="col-6">
                    <h5 class="card-title mb-2">Data Setor Ambil</h5>
                    <div class="dropdown">
                        <button class="btn btn-white btn-lg dropdown-toggle border" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-filter text-muted mr-1"></i> 
                            @if($filterJenis == 'all') Semua Data
                            @elseif($filterJenis == 'tarik') Transaksi Ambil
                            @else Transaksi Setor
                            @endif
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item {{ $filterJenis == 'all' ? 'active' : '' }}" href="#" wire:click.prevent="$set('filterJenis', 'all')">Semua Data</a>
                            <a class="dropdown-item {{ $filterJenis == 'tarik' ? 'active' : '' }}" href="#" wire:click.prevent="$set('filterJenis', 'tarik')">Transaksi Ambil</a>
                            <a class="dropdown-item {{ $filterJenis == 'setor' ? 'active' : '' }}" href="#" wire:click.prevent="$set('filterJenis', 'setor')">Transaksi Setor</a>
                        </div>
                    </div>
                </div>
                @if(!auth()->user()->isKaryawan())
                <div class="col-6 text-right">
                    @if(!auth()->user()->isKaryawan())
                    <a href="/setortarik-ambil/{{ $mastersetortarik_id }}" class="btn btn-warning btn-lg mb-3">
                        <i class="fas fa-arrow-down"></i> Ambil Barang
                    </a>
                    <a href="/setortarik-setor/{{ $mastersetortarik_id }}" class="btn btn-success btn-lg mb-3">
                        <i class="fas fa-arrow-up"></i> Setor Barang
                    </a>
                    @endif
                </div>
                @endif
            </div>

            @if (session()->has('message'))
                <div class="alert alert-success mt-3">
                    {{ session('message') }}
                </div>
            @endif
            
            @if (session()->has('error'))
                <div class="alert alert-danger mt-3">
                    {{ session('error') }}
                </div>
            @endif
            
            @if (session()->has('info'))
                <div class="alert alert-info mt-3">
                    {{ session('info') }}
                </div>
            @endif

            <div class="table-responsive mt-5">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th width="50">No</th>
                            <th>Kode</th>
                            <th>Tanggal</th>
                            <th>Jenis</th>
                            <th>Keterangan</th>
                            <th>Jumlah Item</th>
                            <th>Pekerjaan Terkait</th>
                            <th>Ref Ambil</th>
                            <th>Foto</th>
                            @if(!auth()->user()->isKaryawan())
                            <th width="150">Action</th>
                            @else
                            <th width="50">Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @php $j = 0; @endphp
                        @forelse ($setortariks as $item)
                        @php $j++; @endphp
                        <!-- DYNAMIC MAIN ROW -->
                        <tr class="{{ $item->jenis == 'tarik' ? 'table-warning-light' : 'table-success-light' }}" 
                            style="background-color: {{ $item->jenis == 'tarik' ? 'rgba(255, 193, 7, 0.08)' : 'rgba(40, 167, 69, 0.04)' }};">
                            
                            <td><strong>{{ $j }}</strong></td>
                            
                            <td><code>{{ $item->kode_setortarik }}</code></td>
                            
                            <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                            
                            <td>
                                @if($item->jenis == 'tarik')
                                    <span class="badge badge-warning">AMBIL</span>
                                @else
                                    <span class="badge badge-success">SETOR</span>
                                @endif
                            </td>
                            
                            <td>{{ $item->keterangan ?? '-' }}</td>
                            
                            <td>
                                <span class="badge badge-info">{{ $item->details->count() }} item(s)</span>
                                @if($item->details->count() > 0)
                                    <button class="btn btn-xs btn-secondary" data-toggle="modal" data-target="#itemsModal{{ $item->id }}">
                                        Lihat
                                    </button>
                                @endif
                            </td>
                            
                            <td>
                                @if($item->jenis == 'tarik')
                                    @if($item->karjobs->count() > 0)
                                        <small>{{ $item->karjobs->pluck('nama_pekerjaan')->join(', ') }}</small>
                                    @else
                                        <em class="text-muted">-</em>
                                    @endif
                                @else
                                    <small class="text-muted"><em>Mengikuti Ambil</em></small>
                                @endif
                            </td>
                            
                            <td>
                                @if($item->jenis == 'setor')
                                    @if($item->parentAmbil)
                                        <small><code>{{ $item->parentAmbil->kode_setortarik }}</code></small>
                                    @elseif($item->parent_id)
                                        <small>Ref: {{ $item->parent_id }}</small>
                                    @else
                                        -
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                            
                            <td>
                                @if($item->photo1 || $item->photo2 || $item->photo3)
                                    <button class="btn btn-lg btn-info" data-toggle="modal" data-target="#photoModal{{ $item->id }}">
                                        <i class="fas fa-images"></i>
                                    </button>
                                @else
                                    -
                                @endif
                            </td>
                            
                             <td>
                                <div class="d-flex flex-wrap" style="gap: 5px;">
                                    @if(!auth()->user()->isKaryawan())
                                        @if($item->jenis == 'tarik')
                                        <a href="/setortarik-ambil/{{ $mastersetortarik_id }}/{{ $item->id }}" class="btn btn-warning btn-lg mb-3">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @else
                                        <a href="/setortarik-setor/{{ $mastersetortarik_id }}/{{ $item->id }}" class="btn btn-success btn-lg mb-3">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @endif
                                        <button wire:click="delete({{ $item->id }})" class="btn btn-danger btn-lg mb-3">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    @else
                                        <span class="text-muted"><i class="fas fa-lock"></i></span>
                                    @endif
                                </div>
                            </td>
                        </tr>

                        <!-- NESTED SETOR (CHILDREN) - ONLY FOR 'ALL' & 'TARIK' types -->
                        @if($filterJenis == 'all' && $item->jenis == 'tarik')
                            @foreach($item->setorTransactions as $setor)
                            <tr class="table-success-light" style="background-color: rgba(40, 167, 69, 0.04);">
                                <td class="text-right">
                                    <i class="fas fa-level-up-alt fa-rotate-90 text-muted"></i>
                                </td>
                                <td><code>{{ $setor->kode_setortarik }}</code></td>
                                <td>{{ \Carbon\Carbon::parse($setor->tanggal)->format('d/m/Y') }}</td>
                                <td>
                                    <span class="badge badge-success">SETOR</span>
                                </td>
                                <td>{{ $setor->keterangan ?? '-' }}</td>
                                <td>
                                    <span class="badge badge-info">{{ $setor->details->count() }} item(s)</span>
                                    @if($setor->details->count() > 0)
                                        <button class="btn btn-xs btn-secondary" data-toggle="modal" data-target="#itemsModal{{ $setor->id }}">
                                            Lihat
                                        </button>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-muted"><em>Mengikuti Ambil</em></small>
                                </td>
                                <td>
                                    <small><code>{{ $item->kode_setortarik }}</code></small>
                                </td>
                                <td>
                                    @if($setor->photo1 || $setor->photo2 || $setor->photo3)
                                        <button class="btn btn-lg btn-info" data-toggle="modal" data-target="#photoModal{{ $setor->id }}">
                                            <i class="fas fa-images"></i>
                                        </button>
                                    @else
                                        -
                                    @endif
                                </td>
                                 <td>
                                    <div class="d-flex flex-wrap" style="gap: 5px;">
                                        @if(!auth()->user()->isKaryawan())
                                        <a href="/setortarik-setor/{{ $mastersetortarik_id }}/{{ $setor->id }}" class="btn btn-success btn-lg mb-3">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button wire:click="delete({{ $setor->id }})" class="btn btn-danger btn-lg mb-3">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        @else
                                        <span class="text-muted"><i class="fas fa-lock"></i></span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        @endif

                        @empty
                        <tr>
                            <td colspan="10" class="text-center">Belum ada data setor ambil</td>
                        </tr>
                        @endforelse

                        @if($orphanedSetors->count() > 0)
                        <tr class="bg-light">
                            <td colspan="10"><strong>Transaksi Setor Tanpa Induk (Legacy/Manual)</strong></td>
                        </tr>
                        @foreach($orphanedSetors as $setor)
                        @php $j++; @endphp
                        <tr class="table-danger-light" style="background-color: rgba(220, 53, 69, 0.05);">
                            <td>{{ $j }}</td>
                            <td><code>{{ $setor->kode_setortarik }}</code></td>
                            <td>{{ \Carbon\Carbon::parse($setor->tanggal)->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge badge-danger">SETOR ORPHAN</span>
                            </td>
                            <td>{{ $setor->keterangan ?? '-' }}</td>
                            <td>
                                <span class="badge badge-info">{{ $setor->details->count() }} item(s)</span>
                                <button class="btn btn-xs btn-secondary" data-toggle="modal" data-target="#itemsModal{{ $setor->id }}">Lihat</button>
                            </td>
                            <td>-</td>
                            <td>-</td>
                            <td>
                                @if($setor->photo1 || $setor->photo2 || $setor->photo3)
                                    <button class="btn btn-lg btn-info" data-toggle="modal" data-target="#photoModal{{ $setor->id }}"><i class="fas fa-images"></i></button>
                                @endif
                            </td>
                             <td>
                                <div class="d-flex flex-wrap" style="gap: 5px;">
                                    @if(!auth()->user()->isKaryawan())
                                    <a href="/setortarik-setor/{{ $mastersetortarik_id }}/{{ $setor->id }}" class="btn btn-success btn-lg"><i class="fas fa-edit"></i></a>
                                    <button wire:click="delete({{ $setor->id }})" class="btn btn-danger btn-lg"><i class="fas fa-trash"></i></button>
                                    @else
                                    <span class="text-muted"><i class="fas fa-lock"></i></span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
    </div>

    <!-- MODALS SECTION -->
    @push('modals')
        @foreach ($setortariks as $ambil)
            @include('livewire.setortarik.modals', ['detail' => $ambil])
            @foreach($ambil->setorTransactions as $childSetor)
                @include('livewire.setortarik.modals', ['detail' => $childSetor])
            @endforeach
        @endforeach

        @foreach ($orphanedSetors as $setor)
            @include('livewire.setortarik.modals', ['detail' => $setor])
        @endforeach
    @endpush
</div>


@section('style')
<style>
    /* Ensure modal-backdrop doesn't trap the modal */
    .modal {
        z-index: 1050 !important;
    }
    /* Hide template-specific overlay when modal is shown to prevent double darkness */
    body.modal-open .overlay {
        display: none !important;
    }

    .modal-dialog {
        max-width: 500px;
        margin: 5.75rem auto!important;
    }

    .modal-backdrop.show {
        display: none!important;
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('show-delete-confirmation', (event) => {
            if (confirm('Apakah Anda yakin ingin menghapus data ini?\n\nCatatan: Transaksi AMBIL yang sudah memiliki SETOR tidak dapat dihapus.')) {
                Livewire.dispatch('sweetalert:confirmed');
            } else {
                Livewire.dispatch('sweetalert:denied', {});
            }
        });
    });
</script>
@endsection


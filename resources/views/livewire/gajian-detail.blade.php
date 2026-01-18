@section('Judul', 'Tambah Gaji Karyawan')
@section('action', 'Gaji')

<div class="container pb-5">
    <form wire:submit='storeGaji'>
        @csrf
        
        <div class="row">
            <div class="col-lg-12">
                <!-- Header Card: Tanggal & Karyawan -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="tanggal" class="form-label font-weight-bold">Tanggal</label>
                                <input type="date" wire:model="date" class="form-control" id="tanggal" required>
                                @error('date') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="search_karyawan" class="form-label font-weight-bold">Pilih Karyawan</label>
                                <div class="position-relative">
                                    <input type="hidden" name="selected_id" value="{{ $selectedItem['id'] ?? '' }}">
                                    <input class="form-control" wire:model.live="search" wire:focus="onFocus"
                                        id="search_karyawan" placeholder="Ketik nama karyawan..." 
                                        @if ($selectedItem) readonly value="{{ $selectedItem['nama'] }}" @endif>
                                    
                                    @if ($isFocused && !$selectedItem)
                                        <div class="card position-absolute w-100 shadow-lg" style="z-index: 1000; max-height: 250px; overflow-y: auto;">
                                            <div class="list-group list-group-flush">
                                                @forelse ($datakaryawan as $item)
                                                    <a href="#" class="list-group-item list-group-item-action py-2" wire:click.prevent="selectItem({{ $item->id }})">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <strong class="mb-0">{{ $item->nama }}</strong>
                                                            <span class="badge badge-light">{{ $item->nip }}</span>
                                                        </div>
                                                    </a>
                                                @empty
                                                    <div class="list-group-item text-muted">Karyawan tidak ditemukan</div>
                                                @endforelse
                                                @if ($isLoading)
                                                    <div class="list-group-item text-center"><span class="spinner-border spinner-border-sm"></span></div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                    @if($selectedItem)
                                        <button type="button" class="btn btn-sm btn-link text-danger position-absolute" style="right: 10px; top: 5px;" wire:click="$set('selectedItem', null)">
                                            <i class="material-icons icon-16pt">close</i>
                                        </button>
                                    @endif
                                </div>
                                @error('selectedItem') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4 border-left-info border-left-3">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center flex-wrap">
                        <h4 class="card-title m-0"><i class="material-icons text-info mr-2">add_circle_outline</i> Pertambahan</h4>
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-outline-info" wire:click="openSetorModal">
                                <i class="material-icons icon-16pt">cloud_download</i> Ambil dari Setor
                            </button>
                            <button type="button" class="btn btn-sm btn-info" wire:click="addPertambahan">
                                <i class="material-icons">add</i> Tambah Baris
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        @foreach ($pertambahan as $index => $item)
                            <div class="row align-items-end mb-3">
                                <div class="col-md-4">
                                    <label class="small font-weight-bold">Deskripsi</label>
                                    <input type="text" class="form-control" placeholder="Deskripsi" wire:model="pertambahan.{{ $index }}.deskripsi">
                                </div>
                                <div class="col-md-2">
                                    <label class="small font-weight-bold">Qty</label>
                                    <input type="number" class="form-control" placeholder="Qty" wire:model.live="pertambahan.{{ $index }}.jumlah">
                                </div>
                                <div class="col-md-3">
                                    <label class="small font-weight-bold">Harga</label>
                                    <input type="number" class="form-control" placeholder="Harga" wire:model.live="pertambahan.{{ $index }}.harga">
                                </div>
                                <div class="col-md-2">
                                    <label class="small font-weight-bold text-muted">Total</label>
                                    <input type="text" class="form-control bg-light" value="{{ number_format((float)($pertambahan[$index]['total'] ?? 0), 0, ',', '.') }}" disabled>
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-sm btn-outline-danger" wire:click="removePertambahan({{ $index }})">
                                        <i class="material-icons icon-16pt">delete</i>
                                    </button>
                                </div>
                            </div>
                            @if(!$loop->last) <hr class="my-3"> @endif
                        @endforeach
                    </div>
                </div>

                <!-- Pengurangan Card -->
                <div class="card mb-4 border-left-danger border-left-3">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h4 class="card-title m-0"><i class="material-icons text-danger mr-2">remove_circle_outline</i> Pengurangan</h4>
                        <button type="button" class="btn btn-sm btn-danger" wire:click="addPengurangan">
                            <i class="material-icons">add</i> Tambah Baris
                        </button>
                    </div>
                    <div class="card-body">
                        @foreach ($pengurangan as $index => $item)
                            <div class="row align-items-end mb-3">
                                <div class="col-md-7">
                                    <label class="small font-weight-bold">Deskripsi</label>
                                    <input type="text" class="form-control" placeholder="Deskripsi" wire:model="pengurangan.{{ $index }}.deskripsi">
                                </div>
                                <div class="col-md-4">
                                    <label class="small font-weight-bold text-danger">Potongan</label>
                                    <input type="number" class="form-control" placeholder="Potongan" wire:model.live="pengurangan.{{ $index }}.potongan">
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-sm btn-outline-danger" wire:click="removePengurangan({{ $index }})">
                                        <i class="material-icons icon-16pt">delete</i>
                                    </button>
                                </div>
                            </div>
                            @if(!$loop->last) <hr class="my-3"> @endif
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-lg-12">
                <!-- Summary Card -->
                <div class="card card-body bg-dark text-white sticky-top" style="top: 20px;">
                    <h5 class="text-white-50">Total Gaji Netto</h5>
                    <h2 class="text-white mb-4">Rp. {{ number_format($total, 0, ',', '.') }}</h2>
                    <hr class="border-secondary">
                    <button type="submit" class="btn btn-primary btn-lg btn-block mb-3">
                         <i class="material-icons mr-1">save</i> Simpan Data Gaji
                    </button>
                    <a href="{{ route('gajian.index') }}" class="btn btn-outline-light btn-block" wire:navigate>
                        Kembali
                    </a>
                </div>
            </div>
        </div>
    </form>

    <!-- Setor Tarik Selection Modal -->
    <div class="modal fade @if($showSetorModal) show @endif" id="setorModal" tabindex="-1" role="dialog" 
         @if($showSetorModal) style="display: block; background: rgba(0,0,0,0.5);" @endif>
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title"><i class="material-icons mr-2 text-info">cloud_download</i> Pilih Data Setor Barang</h5>
                    <button type="button" class="close" wire:click="$set('showSetorModal', false)">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body p-0" style="max-height: 400px; overflow-y: auto;">
                    @if(count($availableSetorItems) > 0)
                        <table class="table table-striped table-hover m-0">
                            <thead class="thead-light">
                                <tr>
                                    <th width="50" class="text-center">#</th>
                                    <th>Tanggal / Kode</th>
                                    <th>Pekerjaan / Barang</th>
                                    <th class="text-center">Qty</th>
                                    <th width="100" class="text-center">Pilih</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($availableSetorItems as $detail)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="font-weight-bold">{{ \Carbon\Carbon::parse($detail->setortarik->tanggal)->format('d/m/Y') }}</div>
                                            <small class="text-muted">{{ $detail->setortarik->kode_setortarik }}</small>
                                        </td>
                                        <td>
                                            <div class="font-weight-bold">{{ $detail->barang }}</div>
                                            @if($detail->keterangan)
                                                <small class="text-muted">{{ $detail->keterangan }}</small>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-light">{{ $detail->jumlah }}</span>
                                        </td>
                                        <td class="text-center">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="setor_dtl_{{ $detail->id }}" value="{{ $detail->id }}" wire:model.live="selectedSetorIds">
                                                <label class="custom-control-label" for="setor_dtl_{{ $detail->id }}"></label>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="p-4 text-center text-muted">
                            <i class="material-icons icon-48pt mb-2">inbox</i>
                            <p>Tidak ada data setor yang tersedia untuk karyawan ini.</p>
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="$set('showSetorModal', false)">Batal</button>
                    <button type="button" class="btn btn-info" wire:click="addSelectedSetorToPertambahan" @if(empty($selectedSetorIds)) disabled @endif>
                        <i class="material-icons mr-1">check_circle</i> Tambah ke Gajian
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

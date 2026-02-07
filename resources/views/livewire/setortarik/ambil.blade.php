@section('Judul','Ambil Barang')
@section('action','Form Ambil Barang')

<div class="container">
    <div class="card">
        <div class="card-header bg-warning text-white">
            <h5 class="mb-0">
                <i class="fas fa-arrow-down"></i> {{ $setortarik_id ? 'Edit' : 'Tambah' }} Ambil Barang
            </h5>
        </div>
        <div class="card-body">
            <!-- Info Section -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <h6><strong>Karyawan:</strong> {{ $mastersetortarik->karyawan->nama }}</h6>
                    <p class="text-muted"><strong>NIP:</strong> {{ $mastersetortarik->karyawan->nip }}</p>
                </div>
                <div class="col-md-6">
                    <h6><strong>Tugas:</strong> {{ $mastersetortarik->jobCust->nama }}</h6>
                    <p class="text-muted">{{ $mastersetortarik->jobCust->keterangan }}</p>
                </div>
            </div>

            <hr>

            <!-- Form -->
            <form wire:submit.prevent="store">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tanggal">Tanggal <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" wire:model="tanggal">
                            @error('tanggal') <span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="keterangan">Keterangan</label>
                    <textarea class="form-control" wire:model="keterangan" rows="3"></textarea>
                    @error('keterangan') <span class="text-danger">{{ $message }}</span>@enderror
                </div>

                <!-- Pekerjaan Terkait -->
                <h6 class="border-bottom pb-2 mt-4 mb-3">Pekerjaan Terkait <span class="text-danger">*</span></h6>
                @if($karjobsList->count() > 0)
                    <div class="row">
                        @foreach($karjobsList as $karjob)
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="{{ $karjob->id }}" 
                                       wire:model="selected_karjobs" id="karjob{{ $karjob->id }}">
                                <label class="form-check-label" for="karjob{{ $karjob->id }}">
                                    {{ $karjob->nama_pekerjaan }}
                                    @if($karjob->deskripsi_pekerjaan)
                                        <small class="text-muted d-block">{{ $karjob->deskripsi_pekerjaan }}</small>
                                    @endif
                                </label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @error('selected_karjobs') <div class="text-danger">{{ $message }}</div>@enderror
                @else
                    <p class="text-muted">Belum ada pekerjaan untuk job ini.</p>
                @endif

                <!-- Detail Barang -->
                <h6 class="border-bottom pb-2 mt-4 mb-3">Detail Barang yang Diambil <span class="text-danger">*</span></h6>
                @foreach($items as $index => $item)
                <div class="row mb-3 border-bottom pb-3"> <!-- Added separator for mobile clarity -->
                    <div class="col-12 col-md-5 mb-2 mb-md-0">
                        <label class="d-md-none">Nama Barang</label>
                        <input type="text" class="form-control" placeholder="Nama Barang" wire:model="items.{{ $index }}.barang">
                        @error('items.'.$index.'.barang') <span class="text-danger text-sm">{{ $message }}</span>@enderror
                    </div>
                    <div class="col-12 col-md-3 mb-2 mb-md-0">
                        <label class="d-md-none">Jumlah</label>
                        <input type="number" class="form-control" placeholder="Jumlah" wire:model="items.{{ $index }}.jumlah" min="1">
                        @error('items.'.$index.'.jumlah') <span class="text-danger text-sm">{{ $message }}</span>@enderror
                    </div>
                    <div class="col-12 col-md-3 mb-2 mb-md-0">
                        <label class="d-md-none">Keterangan</label>
                        <input type="text" class="form-control" placeholder="Keterangan" wire:model="items.{{ $index }}.keterangan">
                    </div>
                    <div class="col-12 col-md-1 d-flex align-items-center">
                        @if($index > 0)
                        <button type="button" class="btn btn-danger btn-lg btn-block" wire:click="removeItem({{ $index }})">
                            <i class="fas fa-minus"></i> <span class="d-md-none">Hapus Baris</span>
                        </button>
                        @endif
                    </div>
                </div>
                @endforeach
                <button type="button" class="btn btn-lg btn-secondary" wire:click="addItem">
                    <i class="fas fa-plus"></i> Tambah Item
                </button>

                <!-- Upload Foto -->
                <h6 class="border-bottom pb-2 mt-4 mb-3">Upload Foto (Opsional)</h6>
                <div class="row">
                    @foreach(['photo1', 'photo2', 'photo3'] as $photoField)
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label font-weight-bold"><i class="material-icons icon-16pt align-middle mr-1">add_photo_alternate</i>Foto {{ substr($photoField, -1) }}</label>
                            <div class="custom-file mb-2">
                                <input type="file" class="custom-file-input" wire:model="{{ $photoField }}" id="{{ $photoField }}">
                                <label class="custom-file-label" for="{{ $photoField }}">Pilih Foto</label>
                            </div>
                            @error($photoField) <span class="text-danger small">{{ $message }}</span>@enderror
                            
                            <!-- Preview Frame -->
                            <div class="mt-2 text-center bg-light rounded" style="min-height: 120px; display: flex; align-items: center; justify-content: center; border: 1px dashed #ced4da;">
                                <!-- Loading indicator -->
                                <div wire:loading wire:target="{{ $photoField }}">
                                    <div class="spinner-border spinner-border-sm text-primary" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    <div class="text-muted small mt-1">Uploading...</div>
                                </div>
                                
                                <!-- Preview -->
                                <div wire:loading.remove wire:target="{{ $photoField }}">
                                    @if ($this->$photoField && !is_string($this->$photoField))
                                        <img src="{{ $this->$photoField->temporaryUrl() }}" class="img-fluid rounded shadow-sm" style="max-height: 110px;">
                                    @elseif($this->{'existing_'.$photoField})
                                        <img src="{{ asset('storage/'.$this->{'existing_'.$photoField}) }}" class="img-fluid rounded shadow-sm" style="max-height: 110px;">
                                    @else
                                        <span class="text-muted small">Preview</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Actions -->
                <div class="mt-4">
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                    <button type="button" wire:click="cancel" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('style')
<style>
    .card-header.bg-warning {
        background-color: #ffc107 !important;
    }
</style>
@endsection

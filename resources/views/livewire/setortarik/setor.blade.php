@section('Judul','Setor Barang')
@section('action','Form Setor Barang')

<div class="container">
    <div class="card">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">
                <i class="fas fa-arrow-up"></i> {{ $setortarik_id ? 'Edit' : 'Tambah' }} Setor Barang
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

                <!-- Pilih Transaksi Ambil -->
                <h6 class="border-bottom pb-2 mt-4 mb-3">Pilih Transaksi Ambil <span class="text-danger">*</span></h6>
                @if($availableAmbilTransactions->count() > 0)
                    <div class="form-group">
                        <select class="form-control" wire:model.live="parent_id">
                            <option value="">-- Pilih Transaksi Ambil --</option>
                            @foreach($availableAmbilTransactions as $ambil)
                                <option value="{{ $ambil->id }}">
                                    {{ $ambil->kode_setortarik }} - {{ \Carbon\Carbon::parse($ambil->tanggal)->format('d/m/Y') }}
                                    ({{ $ambil->details->count() }} item)
                                </option>
                            @endforeach
                        </select>
                        @error('parent_id') <span class="text-danger">{{ $message }}</span>@enderror
                    </div>

                    @if($parent_id && count($parentAmbilItems) > 0)
                    <div class="alert alert-info">
                        <strong>Barang yang diambil:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach($parentAmbilItems as $pItem)
                                <li>{{ $pItem['barang'] }} - {{ $pItem['jumlah'] }} {{ $pItem['keterangan'] ? '('.$pItem['keterangan'].')' : '' }}</li>
                            @endforeach
                        </ul>
                        
                        @if(count($selected_karjobs) > 0)
                        <strong class="mt-3 d-block">Pekerjaan terkait:</strong>
                        <div class="mt-2">
                            @php
                                $selectedJobs = $karjobsList->whereIn('id', $selected_karjobs);
                            @endphp
                            @foreach($selectedJobs as $job)
                                <span class="badge badge-secondary">{{ $job->nama_pekerjaan }}</span>
                            @endforeach
                        </div>
                        @endif
                    </div>
                    @endif
                @else
                    <div class="alert alert-warning">
                        Belum ada transaksi AMBIL untuk karyawan ini. Silakan buat transaksi AMBIL terlebih dahulu.
                        <a href="/setortarik-ambil/{{ $mastersetortarik_id }}" class="btn btn-warning btn-sm mt-2">
                            <i class="fas fa-arrow-down"></i> Ambil Barang
                        </a>
                    </div>
                @endif

                <div class="form-group mt-3">
                    <label for="keterangan">Keterangan</label>
                    <textarea class="form-control" wire:model="keterangan" rows="3"></textarea>
                    @error('keterangan') <span class="text-danger">{{ $message }}</span>@enderror
                </div>

                <!-- Detail Barang -->
                <h6 class="border-bottom pb-2 mt-4 mb-3">Detail Barang yang Disetor <span class="text-danger">*</span></h6>
                @if($parent_id)
                <div class="alert alert-info mb-3">
                    <i class="fas fa-info-circle"></i> Item di bawah otomatis terisi dari <strong>Pekerjaan Terkait</strong> pada transaksi Ambil. Silakan isi jumlahnya.
                </div>
                @endif
                @foreach($items as $index => $item)
                <div class="row mb-3 border-bottom pb-3">
                    <div class="col-12 col-md-5 mb-2 mb-md-0">
                        <label class="d-md-none">Nama Barang</label>
                        <input type="text" class="form-control {{ ($parent_id && $index < count($selected_karjobs)) ? 'bg-light' : '' }}" 
                               placeholder="Nama Barang" 
                               wire:model="items.{{ $index }}.barang"
                               @if($parent_id && $index < count($selected_karjobs)) readonly @endif>
                        @error('items.'.$index.'.barang') <span class="text-danger text-sm">{{ $message }}</span>@enderror
                    </div>
                    <div class="col-12 col-md-3 mb-2 mb-md-0">
                        <label class="d-md-none">Jumlah</label>
                        <input type="number" class="form-control" placeholder="Jumlah *" wire:model="items.{{ $index }}.jumlah" min="1">
                        @error('items.'.$index.'.jumlah') <span class="text-danger text-sm">{{ $message }}</span>@enderror
                    </div>
                    <div class="col-12 col-md-3 mb-2 mb-md-0">
                        <label class="d-md-none">Keterangan</label>
                        <input type="text" class="form-control" placeholder="Keterangan" wire:model="items.{{ $index }}.keterangan">
                    </div>
                    <div class="col-12 col-md-1 d-flex align-items-center">
                        @if(!$parent_id || $index >= count($selected_karjobs))
                        <button type="button" class="btn btn-danger btn-sm btn-block" wire:click="removeItem({{ $index }})">
                            <i class="fas fa-minus"></i> <span class="d-md-none">Hapus Baris</span>
                        </button>
                        @endif
                    </div>
                </div>
                @endforeach
                @if(!$parent_id || count($items) >= count($selected_karjobs))
                <button type="button" class="btn btn-sm btn-secondary" wire:click="addItem">
                    <i class="fas fa-plus"></i> Tambah Item Lain
                </button>
                @endif

                <!-- Upload Foto -->
                <h6 class="border-bottom pb-2 mt-4 mb-3">Upload Foto (Opsional)</h6>
                <div class="row">
                    @foreach(['photo1', 'photo2', 'photo3'] as $photoField)
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Foto {{ substr($photoField, -1) }}</label>
                            <input type="file" class="form-control-file" wire:model="{{ $photoField }}" accept="image/*">
                            @error($photoField) <span class="text-danger">{{ $message }}</span>@enderror
                            @if ($this->$photoField && !is_string($this->$photoField))
                                <img src="{{ $this->$photoField->temporaryUrl() }}" class="img-thumbnail mt-2" width="100">
                            @elseif($this->{'existing_'.$photoField})
                                <img src="{{ asset('storage/'.$this->{'existing_'.$photoField}) }}" class="img-thumbnail mt-2" width="100">
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Actions -->
                <div class="mt-4">
                    <button type="submit" class="btn btn-success" @if(!$parent_id) disabled @endif>
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
    .card-header.bg-success {
        background-color: #28a745 !important;
    }
</style>
@endsection

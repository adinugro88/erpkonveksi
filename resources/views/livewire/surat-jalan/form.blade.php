@section('Judul','Surat Jalan')
@section('action', $sj_id ? 'Edit Surat Jalan' : 'Buat Surat Jalan Baru')

<div class="container pb-5">
    <div class="card">
        <div class="card-header bg-{{ $jenis == 'keluar' ? 'warning' : 'success' }} text-white">
            <h5 class="mb-0">Form Surat Jalan ({{ strtoupper($jenis) }})</h5>
        </div>
        <div class="card-body">
            <form wire:submit.prevent="save">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Jenis <span class="text-danger">*</span></label>
                            <select id="selectjenis" class="form-control" wire:model.live="jenis" @if($sj_id) disabled @endif>
                                <option value="keluar">KELUAR (PENGIRIMAN)</option>
                                <option value="masuk">MASUK (Barang Masuk)</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Kode Surat Jalan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" wire:model="kode_sj" readonly>
                            @error('kode_sj') <span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Tanggal <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" wire:model="tanggal">
                            @error('tanggal') <span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label>Pekerjaan / Job <span class="text-danger">*</span></label>
                            <div wire:ignore>
                                <select id="selectjob" class="form-control" wire:model.live="job_cust_id">
                                    <option value="">-- Pilih Job --</option>
                                    @foreach($jobsList as $job)
                                        <option value="{{ $job->id }}">{{ $job->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('job_cust_id') <span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label>Customer</label>
                            <input type="text" class="form-control bg-light" wire:model="customer" readonly placeholder="Nama Customer (Otomatis)">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Keterangan Umum</label>
                            <textarea class="form-control" wire:model="keterangan" rows="1"></textarea>
                        </div>
                    </div>
                </div>

                <hr>
                <h6>Logistik & Pengiriman</h6>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Pengirim</label>
                            <input type="text" class="form-control" wire:model="pengirim" placeholder="Nama Pengirim">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Penerima</label>
                            <input type="text" class="form-control" wire:model="penerima" placeholder="Nama Penerima">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Sopir / Driver</label>
                            <input type="text" class="form-control" wire:model="sopir" placeholder="Nama Sopir">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>No. Kendaraan</label>
                            <input type="text" class="form-control" wire:model="no_kendaraan" placeholder="B 1234 ABC">
                        </div>
                    </div>
                </div>

                <hr>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0">Daftar Barang</h6>
                </div>

                <div class="mb-3">
                    <!-- Desktop Header -->
                    <div class="d-none d-md-flex row font-weight-bold bg-light py-2 px-2 border rounded-top">
                        <div class="col-md-4">Nama Barang <span class="text-danger">*</span></div>
                        <div class="col-md-2">Jumlah <span class="text-danger">*</span></div>
                        <div class="col-md-2">Satuan</div>
                        <div class="col-md-3">Keterangan</div>
                        <div class="col-md-1"></div>
                    </div>

                    <!-- Items List -->
                    @foreach($items as $index => $item)
                    <div class="row border-bottom py-3 px-2 {{ $loop->even ? 'bg-light' : '' }} align-items-center">
                        <!-- Nama Barang -->
                        <div class="col-12 col-md-4 mb-2 mb-md-0">
                            <label class="d-md-none font-weight-bold">Nama Barang <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" wire:model="items.{{ $index }}.barang" placeholder="Nama Barang">
                            @error('items.'.$index.'.barang') <span class="text-danger small d-block">{{ $message }}</span>@enderror
                        </div>

                        <!-- Jumlah -->
                        <div class="col-12 col-md-2 mb-2 mb-md-0">
                            <label class="d-md-none font-weight-bold">Jumlah <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control" wire:model="items.{{ $index }}.jumlah" placeholder="0">
                            @error('items.'.$index.'.jumlah') <span class="text-danger small d-block">{{ $message }}</span>@enderror
                        </div>

                        <!-- Satuan -->
                        <div class="col-12 col-md-2 mb-2 mb-md-0">
                            <label class="d-md-none font-weight-bold">Satuan</label>
                            <input type="text" class="form-control" wire:model="items.{{ $index }}.satuan" placeholder="Pcs/Kg">
                        </div>

                        <!-- Keterangan -->
                        <div class="col-12 col-md-3 mb-2 mb-md-0">
                            <label class="d-md-none font-weight-bold">Keterangan</label>
                            <input type="text" class="form-control" wire:model="items.{{ $index }}.keterangan" placeholder="Catatan">
                        </div>

                        <!-- Action -->
                        <div class="col-12 col-md-1 text-center">
                            @if(count($items) > 1)
                            <button type="button" class="btn btn-block btn-danger btn-sm d-md-none mt-2" wire:click="removeItem({{ $index }})">
                                <i class="fas fa-trash"></i> Hapus Item
                            </button>
                            <button type="button" class="btn btn-danger btn-sm d-none d-md-block mx-auto" wire:click="removeItem({{ $index }})">
                                <i class="fas fa-times"></i>
                            </button>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <div class="text-right mb-4">
                    <button type="button" class="btn btn-sm btn-info" wire:click="addItem">
                        <i class="fas fa-plus"></i> Tambah Baris
                    </button>
                </div>

                <div class="mt-4 text-right">
                    <a href="{{ route('surat-jalan.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary px-5">
                        <i class="fas fa-save"></i> Simpan Surat Jalan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('scripts')
<script>
    document.addEventListener('livewire:initialized', () => {
        const initTomSelect = (id, modelName) => {
            if(document.getElementById(id.replace('#',''))) {
                new TomSelect(id, {
                    plugins: ['dropdown_input'],
                    onInitialize: function() {
                        this.on('change', function(value) {
                           @this.set(modelName, value);
                        });
                    }
                });
            }
        };

        initTomSelect('#selectjob', 'job_cust_id');
         initTomSelect('#selectjenis', 'jenis');
    });
</script>
@endsection

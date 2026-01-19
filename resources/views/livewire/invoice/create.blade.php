@section('Judul', 'Buat Invoice')
@section('action', 'Form Invoice')

<div class="container">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="fas fa-file-invoice"></i> Buat Invoice Baru
            </h5>
        </div>
        <div class="card-body">
            
            <!-- Alert/Info -->
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> Pilih <strong>Surat Jalan Keluar</strong> untuk mengisi otomatis item dari pengiriman sebelumnya.
            </div>

            <form wire:submit.prevent="save">
                
                <!-- Helper Selection -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="surat_jalan_id"><strong>Ambil dari Surat Jalan (Opsional)</strong></label>
                            <select class="form-control" wire:model.live="surat_jalan_id">
                                <option value="">-- Pilih Surat Jalan --</option>
                                @foreach($suratJalans as $sj)
                                    <option value="{{ $sj->id }}">
                                        {{ $sj->kode_sj }} - {{ $sj->tanggal }} 
                                        ({{ $sj->jobCust->Customer->nama ?? 'Umum' }})
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Item akan otomatis disalin dari Surat Jalan yang dipilih.</small>
                        </div>
                    </div>
                </div>

                <hr>

                <!-- Invoice Details -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>No Invoice <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" wire:model="no_invoice">
                            @error('no_invoice') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Tanggal <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" wire:model="date">
                            @error('date') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Customer <span class="text-danger">*</span></label>
                            <select class="form-control" wire:model="customer_id">
                                <option value="">-- Pilih Customer --</option>
                                @foreach($customers as $cust)
                                    <option value="{{ $cust->id }}">{{ $cust->nama }}</option>
                                @endforeach
                            </select>
                            @error('customer_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Bank <span class="text-danger">*</span></label>
                            <select class="form-control" wire:model="bank_id">
                                <option value="">-- Pilih Bank --</option>
                                @foreach($banks as $bank)
                                    <option value="{{ $bank->id }}">{{ $bank->bank_name }} - {{ $bank->account_number }}</option>
                                @endforeach
                            </select>
                            @error('bank_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- Items Table -->
                <h6 class="border-bottom pb-2 mt-4 mb-3">Item Invoice</h6>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr class="bg-light">
                                <th width="40%">Deskripsi</th>
                                <th width="15%">Qty</th>
                                <th width="20%">Harga</th>
                                <th width="20%">Total</th>
                                <th width="5%"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $index => $item)
                            <tr>
                                <td>
                                    <input type="text" class="form-control" wire:model="items.{{ $index }}.deskripsi" placeholder="Deskripsi Item">
                                    @error('items.'.$index.'.deskripsi') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                                </td>
                                <td>
                                    <input type="number" class="form-control" wire:model.live="items.{{ $index }}.qty" min="1">
                                    @error('items.'.$index.'.qty') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                                </td>
                                <td>
                                    <input type="number" class="form-control" wire:model.live="items.{{ $index }}.price" min="0">
                                    @error('items.'.$index.'.price') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                                </td>
                                <td>
                                    <input type="text" class="form-control" value="{{ number_format((int)$item['qty'] * (int)$item['price']) }}" readonly>
                                </td>
                                <td>
                                    @if(count($items) > 1)
                                    <button type="button" class="btn btn-danger btn-sm" wire:click="removeItem({{ $index }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-right"><strong>Grand Total</strong></td>
                                <td>
                                    @php
                                        $grandTotal = 0;
                                        foreach($items as $item) {
                                            $grandTotal += (int)$item['qty'] * (int)$item['price'];
                                        }
                                    @endphp
                                    <strong>Rp {{ number_format($grandTotal) }}</strong>
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <button type="button" class="btn btn-secondary btn-sm mb-4" wire:click="addItem">
                    <i class="fas fa-plus"></i> Tambah Baris
                </button>

                <div class="text-right">
                    <a href="{{ route('invoice') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Invoice
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

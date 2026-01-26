@section('Judul','Setor Ambil Karyawan')
@section('action','Data Master Setor Ambil')

<div class="container">
    <div class="card p-3">
        <div class="row mb-3">
            <div class="col-6">
                <div class="search-form search-form--light">
                    <input type="text" class="form-control search" placeholder="Search">
                    <button class="btn" type="button" role="button"><i class="material-icons">search</i></button>
                </div>
            </div>
            @if(!auth()->user()->isKaryawan())
            <div class="col-6 text-right">
                @if(!auth()->user()->isKaryawan())
                <button wire:click="create" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Assign Karyawan ke Job
                </button>
                @endif
            </div>
            @endif
        </div>

        @if (session()->has('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif
        
        @if (session()->has('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        
        @if (session()->has('info'))
            <div class="alert alert-info">
                {{ session('info') }}
            </div>
        @endif

        <div class="table-responsive border-bottom" data-toggle="lists"
            data-lists-values='["js-lists-values-employee-name"]'>
            <table class="table mb-0 thead-border-top-0">
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th>Karyawan</th>
                        <th>Tugas / Job</th>
                        @if(!auth()->user()->isKaryawan())
                        <th width="200">Action</th>
                        @else
                        <th width="100">Action</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="list" id="staff02">
                    @php $i = 0; @endphp
                    @forelse ($mastersetortariks as $item)
                        <tr>
                            <td>{{ ++$i }}</td>
                            <td class="js-lists-values-employee-name">
                                {{ $item->karyawan->nama ?? 'N/A' }}
                            </td>
                            <td>
                                <strong>{{ $item->jobCust->nama ?? 'N/A' }}</strong><br>
                                <small class="text-muted">{{ Str::limit($item->jobCust->keterangan ?? '', 50) }}</small>
                            </td>
                            <td>
                                <div class="d-flex flex-wrap" style="gap: 5px;">
                                    <a href="/setortarik-detail/{{ $item->id }}" class="btn btn-lg btn-primary mb-3">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    @if(!auth()->user()->isKaryawan())
                                    <button wire:click="edit({{ $item->id }})" class="btn btn-lg btn-warning mb-3">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button wire:click="delete({{ $item->id }})" class="btn btn-lg btn-danger mb-3">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Belum ada karyawan yang di-assign ke job</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $mastersetortariks->links() }}
        </div>
    </div>

    <!-- Modal for Create/Edit Master -->
    <div wire:ignore.self class="modal fade" id="masterModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $mastersetortarik_id ? 'Edit Assignment' : 'Assign Karyawan ke Job' }}</h5>
                    <button type="button" class="close" data-dismiss="modal" wire:click.prevent="closeModal()">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="karyawan_id">Karyawan <span class="text-danger">*</span></label>
                            <div wire:ignore>
                                <select id="selectopr" wire:model="karyawan_id" autocomplete="off" placeholder="Pilih Karyawan">
                                    <option value="">-- Pilih Karyawan --</option>
                                    @foreach($karyawans as $karyawan)
                                        <option value="{{ $karyawan->id }}">
                                            {{ $karyawan->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('karyawan_id') <span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label for="jobcust_id">Job / Tugas <span class="text-danger">*</span></label>
                            <div wire:ignore>
                                <select autocomplete="off" id="jobcust" wire:model="jobcust_id">
                                    <option value="">-- Pilih Job --</option>
                                    @foreach($jobcusts as $job)
                                        <option value="{{ $job->id }}">{{ $job->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('jobcust_id') <span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" wire:click.prevent="closeModal()">Batal</button>
                    <button type="button" wire:click.prevent="store()" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@section('style')
<style>
    .modal-backdrop {
        display: none !important;
    }
    .modal {
        z-index: 4050 !important;
        margin-top: 55px !important;
    }
</style>
@endsection

@section('scripts')

<script src="{{ asset('assetsbaru/vendor/list.min.js') }}"></script>
<script src="{{ asset('assetsbaru/js/list.js') }}"></script>

<script>
   


    document.addEventListener('livewire:initialized', () => {

        const initTomSelect = (id, modelName) => {
            new TomSelect(id, {
                plugins: ['dropdown_input'],
                onInitialize: function() {
                    this.on('change', function(value) {
                       @this.set(modelName, value);
                    });
                }
            });
        };

        initTomSelect('#selectopr', 'karyawan_id');
        initTomSelect('#jobcust', 'jobcust_id');

     
        const modal = new bootstrap.Modal(document.getElementById('masterModal'));

        Livewire.on('show-master-modal', () => {
            modal.show();
        });

        Livewire.on('hide-master-modal', () => {
            modal.hide();
        });
        
        Livewire.on('show-delete-confirmation', (event) => {
            if (confirm('Apakah Anda yakin ingin menghapus master setor ambil ini?\n\nHanya bisa dihapus jika belum ada data setor ambil.')) {
                Livewire.dispatch('sweetalert:confirmed');
            } else {
                Livewire.dispatch('sweetalert:denied', {});
            }
        });
    });
</script>
@endsection



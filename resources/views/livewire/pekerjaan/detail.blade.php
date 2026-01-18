@section('Judul','Detail Pekerjaan')
@section('action','All Pekerjaan')

<div class="container">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-6">
                    <h4><strong>Nama:</strong> {{ $Jobs->nama }}</h4>
                </div>
                <div class="col-12 col-md-6">
                    <h5><strong>Keterangan:</strong> {{ $Jobs->keterangan }}</h5>
                </div>
                <hr style="width: 100%; color: black; height: 1px; background-color:black;" />
                <div class="col-12 col-md-4">
                    <p><strong>Gambar 1:</strong></p>
                    @if ($Jobs->photo1)
                    <img src="{{ asset('storage/'.$Jobs->photo1) }}" alt="Gambar 1" width="200" height="100">
                    @else
                    <p>Tidak ada gambar 1.</p>
                    @endif
                </div>
                <div class="col-12 col-md-4">
                    <p><strong>Gambar 2:</strong></p>
                    @if ($Jobs->photo2)
                    <img src="{{ asset('storage/'.$Jobs->photo2) }}" alt="Gambar 2" width="200" height="100">
                    @else
                    <p>Tidak ada gambar 2.</p>
                    @endif
                </div>
                <div class="col-12 col-md-4">
                    <p><strong>Gambar 3:</strong></p>
                    @if ($Jobs->photo3)
                    <img src="{{ asset('storage/'.$Jobs->photo3) }}" alt="Gambar 3" width="200" height="100">
                    @else
                    <p>Tidak ada gambar 3.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-body">
            <div class="row">
                <div class="col-6">
                    <h5 class="card-title">Job Details</h5>
                </div>
                <div class="col-6 text-right">
                    <button data-toggle="modal" data-target="#karjobModal" class="btn btn-primary">Tambah Pekerjaan</button>
                </div>
            </div>

            @if (session()->has('message'))
                <div class="alert alert-success mt-3">
                    {{ session('message') }}
                </div>
            @endif

            <div class="table-responsive mt-5">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Pekerjaan</th>
                            <th>Deskripsi Pekerjaan</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $j = 0; @endphp
                        @foreach ($karjobs as $detail)
                        <tr>
                            <td>{{ ++$j }}</td>
                            <td>{{ $detail->nama_pekerjaan }}</td>
                            <td>{{ $detail->deskripsi_pekerjaan }}</td>
                            <td>
                                <button wire:click="edit({{ $detail->id }})" class="btn btn-primary btn-lg mb-3 mr-3">
                                    <i class="material-icons">edit</i>
                                </button>
                                <button wire:click="delete({{ $detail->id }})" class="btn btn-danger btn-lg mb-3 ">
                                    <i class="material-icons">delete</i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="karjobModal" tabindex="-1" role="dialog" aria-labelledby="karjobModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="karjobModalLabel">{{ $karjob_id ? 'Edit Job' : 'Create New Job' }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" wire:click.prevent="closeModal()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="nama_pekerjaan">Nama Pekerjaan</label>
                            <input type="text" class="form-control" id="nama_pekerjaan" wire:model="nama_pekerjaan">
                            @error('nama_pekerjaan') <span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label for="deskripsi_pekerjaan">Deskripsi Pekerjaan</label>
                            <textarea class="form-control" id="deskripsi_pekerjaan" wire:model="deskripsi_pekerjaan"></textarea>
                            @error('deskripsi_pekerjaan') <span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" wire:click.prevent="closeModal()">Close</button>
                    <button type="button" wire:click.prevent="store()" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>  
</div>

@section('style')
    <style>
        .modal-backdrop{
            display: none;
        }

        #karjobModal {
             margin-top: 75px;
        }
    </style>
@endsection

@section('scripts')
<script>
    document.addEventListener('livewire:initialized', () => {
        const modal = new bootstrap.Modal(document.getElementById('karjobModal'));

        Livewire.on('show-karjob-modal', () => {
            modal.show();
        });

        Livewire.on('hide-karjob-modal', () => {
            modal.hide();
        });
    });
</script>
@endsection

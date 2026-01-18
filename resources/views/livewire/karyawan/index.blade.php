<div>
    <div class="row">
        <div class="col-xl-12 mx-auto">
            <div class="d-flex align-items-center justify-content-between">
                <h6 class="mb-0 text-uppercase">Data karyawan </h6>
                <button  wire:navigate class="btn btn-info">Create</button>
            </div>
            <hr>
            <div class="card">
                <div class="card-body">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">nama</th>
                                <th scope="col">NIP</th>
                                <th scope="col">EMail</th>
                                <th scope="col">no_HP</th>
                                <th scope="col">Status</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($karyawan) > 0 )
                            @foreach($karyawan as $data)
                            <tr>
                                <th scope="row">{{$loop->iteration}}</th>
                                <td>{{$data->nama}}</td>
                                <td>{{$data->nip}}</td>
                                <td>{{$data->email}}</td>
                                <td>{{$data->no_hp}}</td>
                                <td>{{$data->status}}</td>
                                <td>
                                    <div class="d-flex flex-wrap" style="gap: 5px;">
                                        <button wire:click="viewKaryawan({{ $data->id }})" class="btn btn-lg btn-secondary">View</button>
                                        @if(auth()->user()->isAdmin())
                                        <a href="{{ route('karyawan.edit', $data->id) }}" class="btn btn-lg btn-info">Edit</a>
                                        <form action="{{ route('karyawan.destroy', $data->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?');" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-lg btn-danger">Delete</button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="6" align="center">Belum ada data</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Detail Karyawan Modal -->
     <div class="modal fade @if($showModal) show @endif" id="karyawanModal" tabindex="-1" role="dialog" 
          @if($showModal) style="display: block; background: rgba(0,0,0,0.5);" @endif>
         <div class="modal-dialog modal-lg" role="document">
             <div class="modal-content border-0 shadow-lg">
                 <div class="modal-header bg-info text-white border-0">
                     <h5 class="modal-title font-weight-bold">
                         <i class="material-icons mr-2 align-middle">person</i> 
                         Profil Karyawan
                     </h5>
                     <button type="button" class="close text-white" wire:click="closeModal">
                         <span>&times;</span>
                     </button>
                 </div>
                 <div class="modal-body p-4">
                     @if($selectedKaryawan)
                         <div class="row align-items-center mb-4">
                             <div class="col-auto">
                                 <div class="avatar avatar-xl bg-light text-info rounded-circle d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                     <i class="material-icons" style="font-size: 48px;">account_circle</i>
                                 </div>
                             </div>
                             <div class="col">
                                 <h3 class="mb-0 font-weight-bold">{{ $selectedKaryawan->nama }}</h3>
                                 <p class="text-muted mb-0">{{ $selectedKaryawan->nip }}</p>
                                 <span class="badge {{ $selectedKaryawan->status == 'aktif' ? 'badge-success' : 'badge-danger' }} mt-2 px-3 py-2">
                                     {{ strtoupper($selectedKaryawan->status) }}
                                 </span>
                             </div>
                         </div>

                         <div class="card bg-light border-0">
                             <div class="card-body">
                                 <div class="row">
                                     <div class="col-md-6 mb-3">
                                         <label class="text-muted small font-weight-bold text-uppercase mb-1 d-block">Email</label>
                                         <div class="font-weight-bold">{{ $selectedKaryawan->email ?? '-' }}</div>
                                     </div>
                                     <div class="col-md-6 mb-3">
                                         <label class="text-muted small font-weight-bold text-uppercase mb-1 d-block">No. HP</label>
                                         <div class="font-weight-bold">{{ $selectedKaryawan->no_hp ?? '-' }}</div>
                                     </div>
                                     <div class="col-12">
                                         <label class="text-muted small font-weight-bold text-uppercase mb-1 d-block">Alamat</label>
                                         <div class="font-weight-bold">{{ $selectedKaryawan->alamat ?? '-' }}</div>
                                     </div>
                                 </div>
                             </div>
                         </div>
                     @else
                        <div class="text-center py-5">
                            <div class="spinner-border text-info" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                     @endif
                 </div>
                 <div class="modal-footer border-0">
                     <button type="button" class="btn btn-secondary px-4" wire:click="closeModal">Tutup</button>
                 </div>
             </div>
         </div>
     </div>
 </div>
</div>

@section('Judul', 'Gaji Karyawan')
@section('action', 'Daftar Gaji')

<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col">
                    <h4 class="card-title">Daftar Gaji</h4>
                </div>
                <div class="col-auto d-flex align-items-center">
                    <input type="text" wire:model.live="search" class="form-control mr-3"
                        placeholder="Search by Nama..." style="width: 250px;" />
                    @if(auth()->user()->role !== 'karyawan')
                        <a href="{{ route('gajian.create') }}" class="btn btn-info" wire:navigate>
                            <i class="material-icons">add</i> Tambah Gaji
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th width="50">#</th>
                            <th>Tanggal</th>
                            <th>Nama Karyawan</th>
                            <th width="200" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($gajian as $item)
                            <tr>
                                <td>{{ $gajian->firstItem() + $loop->index }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('j F Y') }}</td>
                                <td>{{ $item->karyawan->nama ?? 'Karyawan Tidak Ditemukan' }}</td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <!-- <a href="{{ route('gajian.print', $item->id) }}" class="btn btn-lg btn-outline-secondary mr-1" title="Print">
                                            <i class="material-icons icon-16pt">print</i>
                                        </a> -->
                                        <button wire:click="viewGaji({{ $item->id }})" class="btn btn-lg btn-outline-info mr-1 mb-3" title="Detail">
                                            <i class="material-icons icon-16pt">visibility</i>
                                        </button>
                                        @if(auth()->user()->role !== 'karyawan')
                                            <a href="{{ route('gajian.edit', $item->id) }}" class="btn btn-lg btn-outline-warning mr-1 mb-3" wire:navigate title="Edit">
                                                <i class="material-icons icon-16pt">edit</i>
                                            </a>
                                            <button wire:click="deleteGaji({{ $item->id }})" class="btn btn-lg btn-outline-danger mb-3" title="Delete">
                                                <i class="material-icons icon-16pt">delete</i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4">No Data Found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer d-flex justify-content-between align-items-center">
            <div class="text-muted small">
                Showing {{ $gajian->firstItem() ?? 0 }} to {{ $gajian->lastItem() ?? 0 }} of {{ $gajian->total() }} entries
            </div>
            <div>
                {{ $gajian->links() }}
            </div>
        </div>
    </div>
    <!-- Detail Gaji Modal -->
     <div class="modal fade @if($showViewModal) show @endif" id="viewModal" tabindex="-1" role="dialog" 
          @if($showViewModal) style="display: block; background: rgba(0,0,0,0.5);" @endif>
         <div class="modal-dialog modal-lg" role="document">
             <div class="modal-content border-0 shadow-lg">
                 <div class="modal-header bg-dark text-white border-0">
                     <h5 class="modal-title font-weight-bold text-white">
                         <i class="material-icons mr-2 align-middle">receipt</i> 
                         Detail Slip Gaji
                     </h5>
                     <button type="button" class="close text-white" wire:click="closeViewModal">
                         <span>&times;</span>
                     </button>
                 </div>
                 <div class="modal-body">
                     @if($selectedGaji)
                         <div class="row mb-4">
                             <div class="col-sm-6">
                                 <h6 class="text-muted text-uppercase small font-weight-bold mb-1">Karyawan</h6>
                                 <div class="h5 mb-0">{{ $selectedGaji->karyawan->nama ?? 'N/A' }}</div>
                                 <div class="text-muted small">{{ $selectedGaji->karyawan->nip ?? '-' }}</div>
                             </div>
                             <div class="col-sm-6 text-sm-right">
                                 <h6 class="text-muted text-uppercase small font-weight-bold mb-1">Tanggal</h6>
                                 <div class="h5 mb-0">{{ \Carbon\Carbon::parse($selectedGaji->tanggal)->format('d F Y') }}</div>
                                 <div class="text-muted small">ID: #{{ $selectedGaji->id }}</div>
                             </div>
                         </div>

                         <div class="row">
                             <!-- Pertambahan Section -->
                             <div class="col-md-6">
                                 <div class="card border-0 bg-light mb-3">
                                     <div class="card-header bg-info-soft text-info border-0 py-2">
                                         <h6 class="m-0 font-weight-bold small">PENAMBAHAN (+)</h6>
                                     </div>
                                     <div class="card-body p-0">
                                         <table class="table table-sm mb-0">
                                             <tbody>
                                                 @php $totalAdd = 0; @endphp
                                                 @foreach($selectedGaji->details->whereNull('potongan') as $dtl)
                                                     <tr>
                                                         <td class="pl-3 py-2">
                                                             <div class="font-weight-bold small">{{ $dtl->deskripsi }}</div>
                                                             <div class="text-muted extra-small">{{ $dtl->jumlah }} x Rp. {{ number_format($dtl->fee, 0, ',', '.') }}</div>
                                                         </td>
                                                         <td class="text-right pr-3 align-middle font-weight-bold text-info">
                                                             Rp. {{ number_format($dtl->jumlah * $dtl->fee, 0, ',', '.') }}
                                                         </td>
                                                     </tr>
                                                     @php $totalAdd += ($dtl->jumlah * $dtl->fee); @endphp
                                                 @endforeach
                                             </tbody>
                                         </table>
                                     </div>
                                 </div>
                             </div>

                             <!-- Pengurangan Section -->
                             <div class="col-md-6">
                                 <div class="card border-0 bg-light mb-3">
                                     <div class="card-header bg-danger-soft text-danger border-0 py-2">
                                         <h6 class="m-0 font-weight-bold small">PENGURANGAN (-)</h6>
                                     </div>
                                     <div class="card-body p-0">
                                         <table class="table table-sm mb-0">
                                             <tbody>
                                                 @php $totalSub = 0; @endphp
                                                 @foreach($selectedGaji->details->whereNotNull('potongan') as $dtl)
                                                     <tr>
                                                         <td class="pl-3 py-2 font-weight-bold small">
                                                             {{ $dtl->deskripsi }}
                                                         </td>
                                                         <td class="text-right pr-3 align-middle font-weight-bold text-danger">
                                                             (Rp. {{ number_format($dtl->potongan, 0, ',', '.') }})
                                                         </td>
                                                     </tr>
                                                     @php $totalSub += $dtl->potongan; @endphp
                                                 @endforeach
                                             </tbody>
                                         </table>
                                     </div>
                                 </div>
                             </div>
                         </div>

                         <div class="card bg-dark text-white border-0 mt-3 shadow-sm">
                             <div class="card-body py-3 d-flex justify-content-between align-items-center">
                                 <div class="h6 m-0 font-weight-bold text-uppercase text-info">Gaji Netto</div>
                                 <div class="h3 m-0 font-weight-bold text-info">Rp. {{ number_format($totalAdd - $totalSub, 0, ',', '.') }}</div>
                             </div>
                         </div>
                     @else
                        <div class="text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                     @endif
                 </div>
                 <div class="modal-footer border-0">
                     <button type="button" class="btn btn-outline-dark" wire:click="closeViewModal">Tutup</button>
                     @if($selectedGaji)
                        <a href="{{ route('gajian.print', $selectedGaji->id) }}" class="btn btn-info shadow-sm">
                            <i class="material-icons icon-18pt mr-1 align-middle">print</i> Print Slip
                        </a>
                     @endif
                 </div>
             </div>
         </div>
     </div>

     <style>
         .bg-info-soft { background-color: #e3f2fd; }
         .bg-danger-soft { background-color: #fce4ec; }
         .extra-small { font-size: 0.75rem; }
     </style>
 </div>


 @section('style')
 <style>
  .modal-dialog.modal-lg {
  margin-top: 80px;
}
 </style>
 @endsection
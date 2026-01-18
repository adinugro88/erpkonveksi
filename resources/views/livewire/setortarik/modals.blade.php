<!-- Items Modal -->
@if($detail->details->count() > 0)
<div class="modal fade" id="itemsModal{{ $detail->id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ $detail->jenis == 'tarik' ? 'Detail Barang' : 'Setor Barang' }} - {{ $detail->kode_setortarik }}</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>{{ $detail->jenis == 'tarik' ? 'Barang' : 'Setor' }}</th>
                            <th class="text-center">Jumlah</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($detail->details as $item)
                        <tr>
                            <td>{{ $item->barang }}</td>
                            <td class="text-center">{{ $item->jumlah }}</td>
                            <td>{{ $item->keterangan ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Photo Modal -->
@if($detail->photo1 || $detail->photo2 || $detail->photo3)
<div class="modal fade" id="photoModal{{ $detail->id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Foto - {{ $detail->kode_setortarik }}</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    @if($detail->photo1)
                    <div class="col-md-4 mb-2">
                        <img src="{{ asset('storage/'.$detail->photo1) }}" class="img-fluid rounded border" alt="Photo 1">
                    </div>
                    @endif
                    @if($detail->photo2)
                    <div class="col-md-4 mb-2">
                        <img src="{{ asset('storage/'.$detail->photo2) }}" class="img-fluid rounded border" alt="Photo 2">
                    </div>
                    @endif
                    @if($detail->photo3)
                    <div class="col-md-4 mb-2">
                        <img src="{{ asset('storage/'.$detail->photo3) }}" class="img-fluid rounded border" alt="Photo 3">
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endif

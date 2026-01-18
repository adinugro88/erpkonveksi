@section('Judul','Pekerjaan')
@section('action','All Pekerjaan')

<div class="container">
    <div class="table-responsive border-bottom card p-3" data-toggle="lists"
        data-lists-values='["js-lists-values-employee-name"]'>

        <div class="search-form search-form--light m-3">
            <input type="text" class="form-control search" placeholder="Search">
            <button class="btn" type="button" role="button"><i class="material-icons">search</i></button>
        </div>

        <table class="table mb-0 thead-border-top-0">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Produk</th>
                    <th>Gambar</th>
                    <th>Keterangan</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody class="list" id="staff02">
                @php $i = 0; @endphp
                @foreach ($jobcusts as $item)
                    @php
                        $photos = [
                            $item->photo1 ? asset('storage/'.$item->photo1) : null,
                            $item->photo2 ? asset('storage/'.$item->photo2) : null,
                            $item->photo3 ? asset('storage/'.$item->photo3) : null,
                        ];
                    @endphp
                    <tr>
                        <td>{{ ++$i }}</td>
                        <td class="js-lists-values-employee-name">
                            {{ $item->nama }}
                        </td>
                        <td>
                            <img src="{{ asset('storage/'.$item->photo1) }}" alt="gambar" width="100" height="100"
                                 class="img-thumbnail"
                                 data-toggle="modal"
                                 data-target="#imageModal"
                                 data-photos='@json($photos)'
                                 style="cursor: pointer;">
                        </td>
                        <td>
                            {{ $item->keterangan }}
                        </td>
                        <td>
                            <div class="d-flex flex-wrap" style="gap: 5px;">
                                <a href="/pekerjaan-detail/{{ $item->id }}" class="btn btn-lg btn-info">
                                    <i class="material-icons">visibility</i>
                                </a>
                                
                            </div>
                        </td>
                    </tr>
                   
                @endforeach
            </tbody>
        </table>

    </div>

    {{-- Modal Carousel (gunakan modal-large sebagai imageModal) --}}
    <div id="imageModal" class="modal fade" tabindex="-1" role="dialog"
         aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Image Gallery</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner">
                            {{-- diisi via JS --}}
                        </div>
                        <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
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
    $(function () {
        $('#imageModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var photos = button.data('photos');
            var modal = $(this);
            var carouselInner = modal.find('.carousel-inner');

            carouselInner.empty();

            if (!Array.isArray(photos)) {
                photos = [];
            }

            photos = photos.filter(function (p) { return p; });

            if (photos.length === 0) {
                carouselInner.append(
                    '<div class="carousel-item active">' +
                        '<div class="text-center py-5">Tidak ada gambar</div>' +
                    '</div>'
                );
                return;
            }

            photos.forEach(function (photo, index) {
                var activeClass = index === 0 ? ' active' : '';
                var slide =
                    '<div class="carousel-item' + activeClass + '">' +
                        '<img style="width:100%;height:200px;" src="' + photo + '"  alt="gambar">' +
                    '</div>';
                carouselInner.append(slide);
            });

            $('#carouselExampleControls').carousel(0);
        });
    });
</script>
@endsection

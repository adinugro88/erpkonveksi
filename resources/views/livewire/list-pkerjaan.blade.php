<div>
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="card-title m-0"><i class="material-icons mr-2 text-info align-middle">assignment</i> Detail Pekerjaan</h5>
                </div>
                <div class="col-auto">
                    <button type="button" class="btn btn-info shadow-sm" onclick="@this.call('resetForm'); setTimeout(() => { $('#jobModal').modal('show'); }, 100);">
                        <i class="material-icons mr-1">add_task</i> Tambah Pekerjaan
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th width="50" class="text-center">#</th>
                            <th>Produk / Nama Pekerjaan</th>
                            <th>Tanggal</th>
                            <th class="text-center">Lampiran Foto</th>
                            <th>Keterangan</th>
                            <th width="150" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($jobsdetail as $item)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td class="font-weight-bold">{{ $item->nama }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center" style="gap: 5px;">
                                        @php
                                            $allPhotos = array_filter([$item->photo1, $item->photo2, $item->photo3]);
                                            $photoUrls = array_map(function($p) { return asset('storage/'.$p); }, $allPhotos);
                                        @endphp
                                        @foreach(['photo1', 'photo2', 'photo3'] as $idx => $photo)
                                            @if($item->$photo)
                                                <a href="javascript:void(0)" onclick="openPhotoCarousel({{ json_encode($photoUrls) }}, {{ $idx }})">
                                                    <img src="{{ asset('storage/'.$item->$photo) }}" class="rounded shadow-sm border" style="width: 40px; height: 40px; object-fit: cover;" alt="Photo">
                                                </a>
                                            @endif
                                        @endforeach
                                        @if(empty($allPhotos))
                                            <span class="text-muted small">Tanpa foto</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="text-truncate" style="max-width: 200px;" title="{{ $item->keterangan }}">
                                        {{ $item->keterangan ?? '-' }}
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <button wire:click="edit({{ $item->id }})" class="btn btn-lg ml-3 btn-outline-warning" title="Edit">
                                            <i class="material-icons icon-16pt">edit</i>
                                        </button>
                                        <button onclick="confirm('Yakin ingin menghapus pekerjaan ini?') || event.stopImmediatePropagation()" 
                                                wire:click="hapus({{ $item->id }})" 
                                                class="btn btn-lg ml-3 btn-outline-danger" title="Hapus">
                                            <i class="material-icons icon-16pt">delete</i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted italic">Belum ada riwayat pekerjaan untuk customer ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Job Modal -->
    <div class="modal fade" id="jobModal" tabindex="-1" role="dialog" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow-lg">
                <form 
                    @if($editingId) wire:submit.prevent="updateRecord" @else wire:submit.prevent="simpandata" @endif
                    enctype="multipart/form-data"
                >
                    <div class="modal-header bg-dark text-white border-0">
                        <h5 class="modal-title font-weight-bold text-white">
                            <i class="material-icons mr-2 align-middle">{{ $editingId ? 'edit_note' : 'post_add' }}</i> 
                            {{ $editingId ? 'Update' : 'Tambah' }} Pekerjaan
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal" onclick="@this.call('resetForm');">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="row">
                            <div class="col-md-6">
                                <input type="hidden" wire:model="cust" value="{{$cust}}">
                                <div class="form-group mb-4">
                                    <label class="form-label font-weight-bold">Tanggal Pekerjaan</label>
                                    <div class="input-group input-group-merge">
                                        <div class="input-group-prepend"><div class="input-group-text"><i class="material-icons">event</i></div></div>
                                        <input type="date" wire:model="tanggal" class="form-control" required>
                                    </div>
                                    @error('tanggal') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group mb-4">
                                    <label class="form-label font-weight-bold">Nama Produk / Pekerjaan</label>
                                    <div class="input-group input-group-merge">
                                        <input type="text" wire:model="nama" class="form-control" placeholder="Contoh: Tas Bagpack, Pouch..." required>
                                    </div>
                                    @error('nama') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group mb-0">
                                    <label class="form-label font-weight-bold">Keterangan Tambahan</label>
                                    <textarea wire:model="keterangan" class="form-control" rows="4" placeholder="QTY : 2000"></textarea>
                                </div>
                            </div>
                            <div class="col-md-6 border-left">
                                <label class="form-label font-weight-bold mb-3 d-block"><i class="material-icons icon-16pt align-middle mr-1">add_photo_alternate</i> Lampiran Foto (Max 3)</label>
                                
                                @foreach(['gambar1', 'gambar2', 'gambar3'] as $idx => $field)
                                    <div class="mb-3">
                                        <div class="custom-file mb-1">
                                            <input type="file" wire:model="{{ $field }}" class="custom-file-input" id="{{ $field }}">
                                            <label class="custom-file-label" for="{{ $field }}">Pilih Foto {{ $idx+1 }}</label>
                                        </div>
                                        <div class="mt-2 text-center bg-light rounded" style="min-height: 80px; display: flex; align-items: center; justify-content: center; border: 1px dashed #ced4da;">
                                            @php 
                                                $previewVar = "preview".($idx+1)."Url"; 
                                            @endphp
                                            @if ($$field)
                                                <img src="{{ $$field->temporaryUrl() }}" class="img-fluid rounded shadow-sm" style="max-height: 70px;">
                                            @elseif(isset($$previewVar) && $$previewVar)
                                                <img src="{{ $$previewVar }}" class="img-fluid rounded shadow-sm" style="max-height: 70px;">
                                            @else
                                                <span class="text-muted small">Preview</span>
                                            @endif
                                        </div>
                                        @error($field) <span class="text-danger extra-small">{{ $message }}</span> @enderror
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light px-4" data-dismiss="modal" onclick="@this.call('resetForm');">Batal</button>
                        <button type="submit" class="btn btn-info px-5 shadow-sm">
                            <i class="material-icons mr-1">save</i> {{ $editingId ? 'Update Data' : 'Simpan Data' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Photo Carousel Modal -->
    <div class="modal fade" id="photoCarouselModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content bg-transparent border-0">
                <div class="modal-header border-0 p-0 mb-2">
                    <button type="button" class="close text-white" data-dismiss="modal" style="opacity: 1; font-size: 2rem;">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body p-0">
                    <div id="jobPhotosCarousel" class="carousel slide" data-ride="carousel" data-interval="false">
                        <div class="carousel-inner" id="carouselContent">
                            <!-- JS injected items -->
                        </div>
                        <a class="carousel-control-prev" href="#jobPhotosCarousel" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#jobPhotosCarousel" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                    <div class="text-center mt-3 pb-3">
                        <button type="button" class="btn btn-light px-5 shadow-sm font-weight-bold" data-dismiss="modal">
                            <i class="material-icons mr-1">close</i> Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .extra-small { font-size: 0.75rem; }
        .badge-soft-info { background-color: #e3f2fd; color: #0d47a1; border: 1px solid #bbdefb; }
        .modal-header .close { padding: 1rem; margin: -1rem -1rem -1rem auto; }
        .custom-file-label::after { content: "Cari"; }

        .modal-dialog.modal-lg.modal-dialog-centered {
            margin-top: 70px;
        }

        .modal-backdrop.show{
            display: none!important;
        }
    </style>

    @section('style')
        <style>
            .modal-dialog.modal-lg {
                margin-top: 100px;
            }
            #photoCarouselModal .carousel-item img {
                max-height: 80vh;
                width: auto;
                margin: 0 auto;
                display: block;
                border-radius: 8px;
                box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            }
            #photoCarouselModal .modal-content {
                box-shadow: none;
            }
        </style>
    @endsection

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            window.addEventListener('closed', function () {
                $('#jobModal').modal('hide');
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open');
            });

            window.addEventListener('open-job-modal', function () {
                $('#jobModal').modal('show');
            });

            window.openPhotoCarousel = function(photoUrls, activeIndex) {
                const content = document.getElementById('carouselContent');
                content.innerHTML = '';
                
                photoUrls.forEach((url, index) => {
                    const item = document.createElement('div');
                    item.className = 'carousel-item' + (index === 0 ? ' active' : '');
                    item.innerHTML = `<img src="${url}" class="d-block w-100" alt="Job Photo">`;
                    content.appendChild(item);
                });

                // Show modal
                $('#photoCarouselModal').modal('show');
                
                // Set active item after modal is shown to avoid layout issues
                setTimeout(() => {
                    $('#jobPhotosCarousel').carousel(activeIndex);
                }, 100);
            };
        });
    </script>
</div>

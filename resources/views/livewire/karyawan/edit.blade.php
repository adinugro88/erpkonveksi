<div>
    <div class="row">
        <div class="col-12 col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div class="mb-4">
                        <h5 class="mb-3">Nama Karyawan</h5>
                        <input wire:model='nama' type="text" class="form-control" placeholder="Nama Karyawan">
                    </div>
                    <div class="mb-4">
                        <h5 class="mb-3">NIP Karyawan</h5>
                        <input wire:model='nip' type="text" class="form-control" placeholder="NIP">
                    </div>
                    <div class="mb-4">
                        <h5 class="mb-3">Alamat</h5>
                        <textarea wire:model='alamat' class="form-control" cols="4" rows="6"
                            placeholder="Alamat"></textarea>
                    </div>
                    <div class="mb-4">
                        <h5 class="mb-3" >Email</h5>
                        <input type="text" wire:model='email' class="form-control" placeholder="Email">
                    </div>
                    <div class="mb-4">
                        <h5 class="mb-3">No Handphone</h5>
                        <input type="text" wire:model='no_hp' class="form-control" placeholder="No Handphone">
                    </div>
                    <div class="mb-4">
                        <h5 class="mb-3">Status</h5>
                        <select class="form-control" wire:model='status'>
                            <option selected value="Aktif">Aktif</option>
                            <option value="Tidak Aktif">Tidak Aktif</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="mb-3">Action</h5>
                    <hr>
                    <div class="d-flex align-items-center justify-content-between">
                        <button type="button" wire:click='back()' class="btn btn-danger ">Cancel </button>
                        <button type="button" wire:click='edit()' class="btn btn-primary ">Save</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!--end row-->
</div>

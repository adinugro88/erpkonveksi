<div>
    @if (session()->has('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <form wire:submit.prevent="save">
        <div class="mb-3">
            <label for="nama" class="form-label">Nama</label>
            <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama"
                wire:model.defer="nama">
            @error('nama')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="text" class="form-control @error('email') is-invalid @enderror" id="email"
                wire:model.defer="email">
            @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="no_hp" class="form-label">Nomer HP</label>
            <input type="text" class="form-control @error('no_hp') is-invalid @enderror" id="no_hp"
                wire:model.defer="no_hp">
            @error('no_hp')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="alamat" class="form-label">Alamat</label>
            <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" rows="3"
                wire:model.defer="alamat"></textarea>
            @error('alamat')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <div class="form-group">
                <label for="select01">Status</label>
                <select id="status" data-toggle="select" class="form-control @error('status') is-invalid @enderror" wire:model.defer="status">
                   <option value="">Pilih Status</option>
                <option value="1">Active</option>
                <option value="0">Inactive</option>
                </select>
                  @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>

</div>

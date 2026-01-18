<x-app-layout>

    @section('Judul', 'Pesanan')
@section('action', $customer->nama)

<div class="container-fluid page__container">
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-auto">
                    <div class="avatar avatar-xl bg-light text-info rounded-circle d-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
                        <i class="material-icons" style="font-size: 40px;">business</i>
                    </div>
                </div>
                <div class="col">
                    <div class="d-flex align-items-center mb-1">
                        <h3 class="mb-0 font-weight-bold text-dark">{{ $customer->nama }}</h3>
                        <span class="badge badge-soft-info ml-3 px-3 py-1">Customer Gold</span>
                    </div>
                    <div class="row text-muted small mt-2">
                        <div class="col-auto d-flex align-items-center mr-4 col-12 mb-3">
                            <i class="material-icons icon-14pt mr-1 text-success ">phone</i>
                            {{ $customer->phone }}
                        </div>
                        <div class="col-auto d-flex align-items-center mr-4 col-12 mb-3">
                            <i class="material-icons icon-14pt mr-1 text-primary">email</i>
                            {{ $customer->email }}
                        </div>
                        <div class="col-auto d-flex align-items-center col-12 mb-3">
                            <i class="material-icons icon-14pt mr-1 text-warning">location_on</i>
                            {{ $customer->alamat }}
                        </div>
                    </div>
                </div>
                <div class="col-auto">
                    <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary px-4" wire:navigate>
                        <i class="material-icons mr-1">arrow_back</i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Livewire List Pekerjaan -->
    @livewire('list-pkerjaan', ['customer' => $customer], key($customer->id))
</div>
</x-app-layout>

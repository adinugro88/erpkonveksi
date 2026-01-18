<x-app-layout>
     @section('Judul','Karyawan')
    @section('action','Edit Karyawan')

    <div class="container page__container">

    <div class="card">
        <div class="card-body">
            <livewire:karyawan.form :id="$id" />
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gajian') }}
        </h2>
    </x-slot>

    <livewire:gajian-detail />



</x-app-layout>

@section('style')
<style>
    .modal-dialog.modal-lg {
  margin-top: 80px;
}
</style>
@endsection

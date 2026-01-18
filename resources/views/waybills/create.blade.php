<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ isset($waybill) ? __('Edit Waybill') : __('Create Waybill') }}
        </h2>
    </x-slot>

    <div class="container mt-4">
        <form action="{{ isset($waybill) ? route('waybills.update', $waybill->id) : route('waybills.store') }}" method="POST">
            @csrf
            @if (isset($waybill))
                @method('PUT')
            @endif

            <div class="mb-3">
                <label for="customer_id" class="form-label">Customer</label>
                <select name="customer_id" id="customer_id" class="form-control">
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->id }}" {{ isset($waybill) && $waybill->customer_id == $customer->id ? 'selected' : '' }}>
                            {{ $customer->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="code" class="form-label">Code</label>
                <input type="text" name="code" id="code" class="form-control" value="{{ $waybill->code ?? '' }}" required>
            </div>

            <div class="mb-3">
                <label for="date" class="form-label">Date</label>
                <input type="date" name="date" id="date" class="form-control" value="{{ $waybill->date ?? '' }}" required>
            </div>

            <div class="mb-3">
                <label for="notes" class="form-label">Notes</label>
                <textarea name="notes" id="notes" class="form-control">{{ $waybill->notes ?? '' }}</textarea>
            </div>

            <hr>
            <h4>Details</h4>
            <table class="table" id="details-table">
                <thead>
                    <tr>
                        <th>Description</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if (isset($waybill))
                        @foreach ($waybill->details as $index => $detail)
                            <tr>
                                <td><input type="text" name="details[{{ $index }}][description]" class="form-control" value="{{ $detail->description }}" required></td>
                                <td><input type="number" name="details[{{ $index }}][qty]" class="form-control" value="{{ $detail->qty }}" required></td>
                                <td><input type="number" name="details[{{ $index }}][price]" class="form-control" value="{{ $detail->price }}" required></td>
                                <td><input type="number" name="details[{{ $index }}][total]" class="form-control" value="{{ $detail->total }}" readonly></td>
                                <td><button type="button" class="btn btn-danger remove-row">Remove</button></td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
            <button type="button" id="add-row" class="btn btn-secondary">Add Row</button>

            <button type="submit" class="btn btn-primary mt-3">{{ isset($waybill) ? 'Update' : 'Create' }}</button>
        </form>
    </div>

    <script>
        document.getElementById('add-row').addEventListener('click', function() {
            const table = document.querySelector('#details-table tbody');
            const rowCount = table.rows.length;
            const newRow = `
                <tr>
                    <td><input type="text" name="details[${rowCount}][description]" class="form-control" required></td>
                    <td><input type="number" name="details[${rowCount}][qty]" class="form-control" required></td>
                    <td><input type="number" name="details[${rowCount}][price]" class="form-control" required></td>
                    <td><input type="number" name="details[${rowCount}][total]" class="form-control" readonly></td>
                    <td><button type="button" class="btn btn-danger remove-row">Remove</button></td>
                </tr>`;
            table.insertAdjacentHTML('beforeend', newRow);
        });

        document.querySelector('#details-table').addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-row')) {
                e.target.closest('tr').remove();
            }
        });
    </script>
</x-app-layout>

<x-app-layout>
    @section('Judul','Invoice')
    @section('action','Create Invoice')

    <div class="container page__container">

        <div class="card p-4">
            <h1>Edit Invoice</h1>

            <form action="{{ route('invoices.update', $invoice->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Informasi Invoice -->
                <div class="mb-3">
                    <label for="customer_id" class="form-label">Customer</label>
                    <select name="customer_id" id="customer_id" class="form-control">
                        @foreach ($customers as $customer)
                        <option value="{{ $customer->id }}"
                            {{ $customer->id == $invoice->customer_id ? 'selected' : '' }}>
                            {{ $customer->nama }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="bank_id" class="form-label">Bank</label>
                    <select name="bank_id" id="bank_id" class="form-control">
                        @foreach ($banks as $bank)
                        <option value="{{ $bank->id }}" {{ $bank->id == $invoice->bank_id ? 'selected' : '' }}>
                            {{ $bank->informasi }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="no_invoice" class="form-label">Nomor Invoice</label>
                    <input type="text" name="no_invoice" id="no_invoice" class="form-control"
                        value="{{ $invoice->no_invoice }}" required>
                </div>

                <div class="mb-3">
                    <label for="date" class="form-label">Tanggal Invoice</label>
                    <input type="date" name="date" id="date" class="form-control" value="{{ $invoice->date }}" required>
                </div>

                <hr>



                <div class="row">
                    <div class="col-6">
                        <!-- Detail Invoice -->
                        <h3>Detail Invoice</h3>
                    </div>
                    <div class="col-6 justify-align-right text-end">
                        <button type="button" id="add-row" class="btn btn-info text-white">Tambah Baris</button>
                    </div>
                </div>
                <div class="table-responsive mt-4">
                    <table class="table table-responsive" id="invoice-details">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Deskripsi</th>
                                <th>Qty</th>
                                <th>Harga</th>
                                <th>Total</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($invoice->details as $index => $detail)
                            <tr>
                                <td><input style="min-width:150px" type="date" name="details[{{ $index }}][tanggal]"
                                        class="form-control" value="{{ $detail->tanggal }}" required></td>
                                <td><input style="min-width:250px" type="text" name="details[{{ $index }}][deskripsi]"
                                        class="form-control" value="{{ $detail->deskripsi }}" required></td>
                                <td><input style="min-width:150px" type="number" name="details[{{ $index }}][qty]"
                                        class="form-control qty" value="{{ $detail->qty }}" required></td>
                                <td><input style="min-width:150px" type="number" name="details[{{ $index }}][price]"
                                        class="form-control price" value="{{ $detail->price }}" required></td>
                                <td><input style="min-width:150px" type="number" name="details[{{ $index }}][total]"
                                        class="form-control total" value="{{ $detail->qty * $detail->price }}" readonly>
                                </td>
                                <td>
                                    <button type="button" id="recalculate" class="btn btn-secondary mt-3">Hitung Kembali</button>

                                    <button type="button" class="btn btn-danger remove-row">Hapus</button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>



                <hr>

                <div class="row">
                    <div class="col-6">
                        <button type="button" id="preview-pdf" class="btn btn-success text-white ">Preview PDF</button>
                    </div>
                    <div class="col-6 text-end">
                        <h6>Total Harga</h6>
                        <b class="totalharga"></b>
                    </div>
                    <div class="col-6 mt-3">
                        <button type="submit" class="btn btn-primary">Simpan</button>

                    </div>
                </div>
            </form>
        </div>

        <div id="pdf-preview-container" class="mt-4 p-3 bg-white border rounded shadow-sm d-none">
            <div class="d-flex justify-content-end mb-3">
                <button id="close-preview" class="btn btn-sm btn-outline-danger">Tutup Preview</button>
            </div>

            <div id="pdf-content" class="text-sm">
                <!-- Konten preview akan di-generate di sini -->
            </div>
        </div>

    </div>

    <script>
        let rowIndex = {
            {
                count($invoice - > details)
            }
        };

        // Tambah baris detail
        document.getElementById('add-row').addEventListener('click', function () {
            const tableBody = document.querySelector('#invoice-details tbody');
            const newRow = `
                <tr>
                    <td><input style="min-width:150px" type="date" name="details[${rowIndex}][tanggal]" class="form-control" required></td>
                    <td><input style="min-width:250px" type="text" name="details[${rowIndex}][deskripsi]" class="form-control" required></td>
                    <td><input style="min-width:150px" type="number" name="details[${rowIndex}][qty]" class="form-control qty" required></td>
                    <td><input style="min-width:150px" type="number" name="details[${rowIndex}][price]" class="form-control price" required></td>
                    <td><input style="min-width:150px" type="number" name="details[${rowIndex}][total]" class="form-control total" readonly></td>
                    <td>
                        <button type="button" class="btn btn-danger remove-row">Hapus</button>
                    </td>
                </tr>
            `;
            tableBody.insertAdjacentHTML('beforeend', newRow);
            rowIndex++;
        });

        // Fungsi untuk menghitung ulang total harga
function recalculateTotal() {
    let grandTotal = 0;
    document.querySelectorAll('.total').forEach(function (totalInput) {
        grandTotal += parseFloat(totalInput.value) || 0;
    });
    document.querySelector('.totalharga').textContent = 'Rp ' + grandTotal.toLocaleString('id-ID', {
        minimumFractionDigits: 0
    });
}

// Event listener untuk tombol Hitung Kembali
document.getElementById('recalculate').addEventListener('click', function () {
    recalculateTotal();
});


        // Hapus baris detail
        document.getElementById('invoice-details').addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-row')) {
                e.target.closest('tr').remove();
                calculateGrandTotal();
            }
        });

        // Hitung total setiap baris
        document.addEventListener('input', function (e) {
            if (e.target.classList.contains('qty') || e.target.classList.contains('price')) {
                const row = e.target.closest('tr');
                const qty = row.querySelector('.qty').value || 0;
                const price = row.querySelector('.price').value || 0;
                const total = row.querySelector('.total');
                total.value = qty * price;
            }
        });

        //buat total harga semua
        function calculateGrandTotal() {
            let grandTotal = 0;
            document.querySelectorAll('.total').forEach(function (totalInput) {
                grandTotal += parseFloat(totalInput.value) || 0;
            });
            document.getElementById('grand-total').textContent = grandTotal.toFixed(2);
        }

        //output total harga semua
        document.addEventListener('input', function () {
            let grandTotal = 0;
            document.querySelectorAll('.total').forEach(function (totalInput) {
                grandTotal += parseFloat(totalInput.value) || 0;
            });
            //format rupiah tanpa 2 angka belakang koma
            document.querySelector('.totalharga').textContent = 'Rp ' + grandTotal.toLocaleString('id-ID', {
                minimumFractionDigits: 0
            });
        });

        //cek jika sudah ada data detail, hitung totalnya saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function () {
            let grandTotal = 0;
            document.querySelectorAll('.total').forEach(function (totalInput) {
                grandTotal += parseFloat(totalInput.value) || 0;
            });
            //format rupiah tanpa 2 angka belakang koma
            document.querySelector('.totalharga').textContent = 'Rp ' + grandTotal.toLocaleString('id-ID', {
                minimumFractionDigits: 0
            });
        });

    </script>
    <script>
        function formatDateToIndonesian(dateString) {
            const days = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
            const months = [
                "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                "Juli", "Agustus", "September", "Oktober", "November", "Desember"
            ];

            const date = new Date(dateString);
            if (isNaN(date.getTime())) return "Tanggal tidak valid";

            const dayName = days[date.getDay()];
            const day = date.getDate();
            const month = months[date.getMonth()];
            const year = date.getFullYear();

            return `${day} ${month} ${year}`;
        }



        // Fungsi generate preview menggunakan struktur Bootstrap



        function generatePDFPreview() {
            const date = document.getElementById('date').value;
            const noInvoice = document.getElementById('no_invoice').value;
            const bankSelect = document.getElementById('bank_id');
            const customerSelect = document.getElementById('customer_id');
            const bankName = bankSelect.options[bankSelect.selectedIndex].text;
            const customerName = customerSelect.options[customerSelect.selectedIndex].text;

            const rows = document.querySelectorAll('#invoice-details tbody tr');
            const details = [];
            rows.forEach(row => {
                const tanggal = row.querySelector('[name^="details"][name$="[tanggal]"]').value;
                const deskripsi = row.querySelector('[name^="details"][name$="[deskripsi]"]').value;
                const qty = row.querySelector('[name^="details"][name$="[qty]"]').value;
                const price = row.querySelector('[name^="details"][name$="[price]"]').value;
                const total = row.querySelector('[name^="details"][name$="[total]"]').value || 0;
                details.push({
                    tanggal,
                    deskripsi,
                    qty,
                    price,
                    total
                });
            });

            let grandTotal = 0;
            details.forEach(d => grandTotal += parseFloat(d.total) || 0);

            // Generate HTML dengan Bootstrap classes (mobile-first)
            // GANTI BAGIAN TABEL INI DI DALAM generatePDFPreview()
            return `
                <div class="text-center mb-3">
                    <img src="{{ asset('assetsinv/img/logocvjr.png') }}" alt="Logo CV Jangkung Raharja" width="120" class="mb-2">
                    <h5 class="mb-0">INVOICE</h5>
                    <small class="text-muted">Nomor: ${noInvoice}</small>
                </div>
    
                <div class="row mb-3">
                    <div class="col-12">
                        <p class="mb-1"><strong>Tanggal:</strong> ${formatDateToIndonesian(date)}</p>
                        <p class="mb-1"><strong>Kepada:</strong> ${customerName}</p>
                    </div>
                </div>
                <div class="table-responsive-lg">
                <table class="table table-bordered mb-4">
                    <thead>
                        <tr class="bg-light">
                            <th class="text-center" style="width: 8%;">No</th>
                            <th class="text-center" style="width: 20%;">Tanggal</th>
                            <th class="text-center" style="width: 30%;">Deskripsi</th>
                            <th class="text-center" style="width: 10%;">Qty</th>
                            <th class="text-center" style="width: 15%;">Harga</th>
                            <th class="text-center" style="width: 17%;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${details.map((detail, index) => `
                            <tr>
                                <td class="text-center align-middle">${index + 1}</td>
                                <td class="text-end align-middle fw-normal" style="font-size: 11px; padding: 4px 6px; white-space: nowrap; min-width: 70px;"> ${formatDateToIndonesian(detail.tanggal)}</td>
                                <td class="text-end align-middle fw-normal" style="font-size: 11px; padding: 4px 6px; white-space: nowrap; min-width: 70px;">${detail.deskripsi}</td>
                                <td class="text-end align-middle fw-normal" style="font-size: 11px; padding: 4px 6px; white-space: nowrap; min-width: 70px;">${detail.qty}</td>
                                <!-- 👇 Kolom Harga - Font kecil, padding minimal -->
                                <td class="text-end align-middle fw-normal" style="font-size: 11px; padding: 4px 6px; white-space: nowrap; min-width: 70px;">
                                    Rp ${parseFloat(detail.price).toLocaleString('id-ID')}
                                </td>
                                <!-- 👇 Kolom Total - Font kecil, padding minimal -->
                                <td class="text-end align-middle fw-normal" style="font-size: 11px; padding: 4px 6px; white-space: nowrap; min-width: 70px;">
                                    Rp ${parseFloat(detail.total).toLocaleString('id-ID')}
                                </td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
                </div>
                <div class="row mb-3">
                    <div class="col-6"></div>
                    <div class="col-6 text-end">
                        <h6><strong>Total:</strong> Rp ${grandTotal.toLocaleString('id-ID')}</h6>
                    </div>
                </div>
    
                <div class="border-top pt-3 mt-3">
                    <p class="mb-1"><strong>Catatan:</strong></p>
                    <p class="mb-0 small">Lakukan pembayaran ke:<br><strong>${bankName}</strong></p>
                </div>
    
                <div class="mt-3 pt-3 border-top text-end">
                    <p class="mb-0 small"><strong>CV JANGKUNG RAHARJA</strong><br>
                    Perum Puri 3 Blok B2 No 4 Tigaraksa Tangerang<br>
                    087878933090 | Sukisnawati</p>
                </div>
            `;
        }

        // Event Preview PDF
        document.getElementById('preview-pdf').addEventListener('click', function () {
            if (!document.getElementById('date').value ||
                !document.getElementById('no_invoice').value ||
                !document.getElementById('bank_id').value ||
                !document.getElementById('customer_id').value) {
                alert('Isi semua field wajib terlebih dahulu!');
                return;
            }

            if (document.querySelectorAll('#invoice-details tbody tr').length === 0) {
                alert('Tambahkan setidaknya satu detail invoice!');
                return;
            }

            document.getElementById('pdf-content').innerHTML = generatePDFPreview();
            document.getElementById('pdf-preview-container').classList.remove('d-none');

            // 👇 TAMBHAHAN INI: SCROLL OTOMATIS KE PREVIEW
            document.getElementById('pdf-preview-container').scrollIntoView({
                behavior: 'smooth', // Animasi halus
                block: 'start' // Scroll ke atas container preview
            });
        });

        // Tutup preview
        document.getElementById('close-preview').addEventListener('click', function () {
            document.getElementById('pdf-preview-container').classList.add('d-none');
        });

    </script>
</x-app-layout>

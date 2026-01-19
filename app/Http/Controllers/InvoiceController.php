<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Customer;
use App\Models\Bank;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    // Menampilkan daftar invoice
    public function index(Request $request)
    {
        $query = Invoice::with('customer', 'bank')->latest();

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }

        $invoices = $query->get();
        return view('invoices.index', compact('invoices'));
    }

    // Form untuk membuat invoice baru
    public function create()
    {
        $customers = Customer::all();
        $banks = Bank::all();
        $nomorinvoice = $this->invoicenumbergenerate();

        // Ambil surat jalan untuk option ambil data
        $suratJalans = \App\Models\SuratJalan::with('details', 'jobCust.Customer')
            ->where('jenis', '!=', 'masuk')
            ->orderBy('tanggal', 'desc')
            ->get();

        // dd($customers, $banks);
        return view('invoices.create', compact('customers', 'banks', 'nomorinvoice', 'suratJalans'));///////
    }

    public function invoicenumbergenerate()
    {
        $latestInvoice = Invoice::latest()->first();
        if ($latestInvoice) {
            $lastNumber = (int) substr($latestInvoice->no_invoice, -4);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }
        return 'INV-' . date('Ymd') . '-' . $newNumber;
    }


    // Menyimpan invoice baru
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required',
            'bank_id' => 'required',
            'no_invoice' => 'required|unique:invoices',
            'date' => 'required|date',
            'details.*.tanggal' => 'required|date',
            'details.*.deskripsi' => 'required|string',
            'details.*.qty' => 'required|numeric|min:1',
            'details.*.price' => 'required|numeric|min:0',
        ]);

        // Simpan data invoice
        $invoice = Invoice::create($request->only('customer_id', 'bank_id', 'no_invoice', 'date'));

        // Simpan detail invoice
        foreach ($request->details as $detail) {
            $invoice->details()->create($detail);
        }

        return redirect()->route('invoices.index')->with('success', 'Invoice berhasil dibuat!');
    }

    // Menampilkan detail invoice
    public function show($id)
    {
        $invoice = Invoice::with([
            'details' => function($query) {
                $query->orderBy('tanggal', 'asc'); // Urutkan detail berdasarkan tanggal
            },
            'customer',
            'bank'
        ])->findOrFail($id);

        return view('invoices.show', compact('invoice'));
    }

    // Form untuk edit invoice
    public function edit($id)
    {
        $invoice = Invoice::with('details')->findOrFail($id);
        $customers = Customer::all();
        $banks = Bank::all();
        // Ambil surat jalan untuk option ambil data
        $suratJalans = \App\Models\SuratJalan::with('details', 'jobCust.Customer')
            ->where('jenis', '!=', 'masuk')
            ->orderBy('tanggal', 'desc')
            ->get();
            
        return view('invoices.edit', compact('invoice', 'customers', 'banks', 'suratJalans'));
    }

    // Mengupdate data invoice
    public function update(Request $request, $id)
    {
        $request->validate([
            'customer_id' => 'required',
            'bank_id' => 'required',
            'no_invoice' => "required|unique:invoices,no_invoice,$id",
            'date' => 'required|date',
            'details.*.tanggal' => 'required|date',
            'details.*.deskripsi' => 'required|string',
            'details.*.qty' => 'required|numeric|min:1',
            'details.*.price' => 'required|numeric|min:0',
        ]);

        // Update data invoice
        $invoice = Invoice::findOrFail($id);
        $invoice->update($request->only('customer_id', 'bank_id', 'no_invoice', 'date'));

        // Hapus semua detail lama
        $invoice->details()->delete();

        // Tambahkan detail baru
        foreach ($request->details as $detail) {
            $invoice->details()->create($detail);
        }

        return redirect()->route('invoices.index')->with('success', 'Invoice berhasil diperbarui!');
    }

    // Menghapus invoice
    public function destroy($id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->details()->delete();
        $invoice->delete();

        return redirect()->route('invoices.index')->with('success', 'Invoice berhasil dihapus!');
    }
}

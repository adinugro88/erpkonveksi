<?php

namespace App\Livewire\Invoice;


use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\SuratJalan;
use App\Models\Customer;
use App\Models\Bank;
use Livewire\Component;
use Livewire\Attributes\Layout;

class Create extends Component
{
    public $no_invoice;
    public $date;
    public $customer_id;
    public $bank_id;
    public $items = [];
    public $surat_jalan_id;
    
    // Collections for dropdowns
    public $suratJalans = [];
    public $customers = [];
    public $banks = [];

    public function mount()
    {
        $this->date = date('Y-m-d');
        // Generate mock Invoice No or leave blank for logic later
        $this->no_invoice = 'INV-' . time(); 
        
        $this->items = [
            ['deskripsi' => '', 'qty' => 1, 'price' => 0, 'total' => 0]
        ];

        // Load initial data
        $this->customers = Customer::all();
        $this->banks = Bank::all();
        
        // Get Surat Jalan Keluar (not 'masuk')
        $this->suratJalans = SuratJalan::with('jobCust.Customer')
            ->where('jenis', '!=', 'masuk')
            ->orderBy('tanggal', 'desc')
            ->get();
    }

    public function loadSuratJalan()
    {
        if (!$this->surat_jalan_id) return;

        $suratJalan = SuratJalan::with(['details', 'jobCust.Customer'])->find($this->surat_jalan_id);
        if ($suratJalan) {
            // Auto select customer if associated
            if ($suratJalan->jobCust && $suratJalan->jobCust->Customer) {
                $this->customer_id = $suratJalan->jobCust->Customer->id;
            }

            // Populate items
            $this->items = [];
            foreach ($suratJalan->details as $detail) {
                // Determine description format
                $desc = $detail->barang;
                if($detail->keterangan) {
                   $desc .= ' - ' . $detail->keterangan;
                }

                $this->items[] = [
                    'deskripsi' => $desc,
                    'qty' => $detail->jumlah,
                    'price' => 0,
                    'total' => 0
                ];
            }
            // If empty (shouldn't be), add one empty row
            if (empty($this->items)) {
                $this->items[] = ['deskripsi' => '', 'qty' => 1, 'price' => 0, 'total' => 0];
            }
        }
    }

    public function addItem()
    {
        $this->items[] = ['deskripsi' => '', 'qty' => 1, 'price' => 0, 'total' => 0];
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function save()
    {
        $this->validate([
            'no_invoice' => 'required',
            'date' => 'required|date',
            'customer_id' => 'required|exists:customers,id',
            'bank_id' => 'required|exists:banks,id',
            'items.*.deskripsi' => 'required',
            'items.*.qty' => 'required|numeric|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        // Calculate totals
        $grandTotal = 0;
        foreach ($this->items as $item) {
            $grandTotal += $item['qty'] * $item['price'];
        }

        $invoice = Invoice::create([
            'no_invoice' => $this->no_invoice,
            'date' => $this->date,
            'customer_id' => $this->customer_id,
            'bank_id' => $this->bank_id,
            'total' => $grandTotal,
        ]);

        foreach ($this->items as $item) {
            $itemTotal = $item['qty'] * $item['price'];
            InvoiceDetail::create([
                'invoice_id' => $invoice->id,
                'tanggal' => $this->date, // Assuming item date matches invoice date for simplicity
                'deskripsi' => $item['deskripsi'],
                'qty' => $item['qty'],
                'price' => $item['price'],
                'total' => $itemTotal,
            ]);
        }
        
        return redirect()->route('invoice')->with('message', 'Invoice Created Successfully');
    }

    public function render()
    {
        return view('livewire.invoice.create');
    }
}

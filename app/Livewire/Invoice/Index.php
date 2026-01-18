<?php

namespace App\Livewire\Invoice;

use Livewire\Component;

class Index extends Component
{
    public $kode_invoice;
    public $date;
    public $bank;
    public $total_inv;
    public $invoicedetail;

    public function render()
    {
        return view('livewire.invoice.index');
    }
}

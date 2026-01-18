<?php

namespace App\Livewire;

use Livewire\Component;

use App\Models\Customer;
use Livewire\WithPagination;

class CustomerIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $showModal = false;
    public $selectedCustomer = null;

    protected $queryString = ['search', 'perPage'];

    public function viewCustomer($id)
    {
        $this->selectedCustomer = Customer::find($id);
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedCustomer = null;
    }

    public function deleteCustomer($id)
    {
        $customer = Customer::find($id);
        if ($customer) {
            $customer->delete();
            flash()->success('Customer deleted successfully.');
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $customers = Customer::where('nama', 'like', '%' . $this->search . '%')
            ->orderBy('id', 'desc')
            ->paginate($this->perPage);

        return view('livewire.customer-index', compact('customers'));
    }
}

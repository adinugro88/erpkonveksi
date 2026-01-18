<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::all();
        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }


    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'email' => 'required|email|unique:customers,email',
            'alamat' => 'required|string',
        ]);

        Customer::create($request->all());
        return redirect()->route('customers.index')->with('success', 'Customer created successfully.');
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'email' => 'required|email|unique:customers,email,' . $customer->id,
            'alamat' => 'required|string',
        ]);

        $customer->update($request->all());
        return redirect()->route('customers.index')->with('success', 'Customer updated successfully.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully.');
    }

    //pekerjaan method to show customer pekerjaan
    public function pekerjaan(Customer $customer)
    {
        return view("customers.pekerjaan", compact('customer'));
    }


}

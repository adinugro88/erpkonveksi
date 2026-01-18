<?php

namespace App\Http\Controllers;

use App\Models\Waybill;
use App\Models\WaybillDetail;
use App\Models\Customer;
use Illuminate\Http\Request;

class WaybillController extends Controller
{
    public function index()
    {
        $waybills = Waybill::with('customer')->get();
        return view('waybills.index', compact('waybills'));
    }

    public function create()
    {
        $customers = Customer::all();
        return view('waybills.create', compact('customers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'code' => 'required|string|max:255',
            'date' => 'required|date',
            'notes' => 'nullable|string',
            'details.*.description' => 'required|string',
            'details.*.qty' => 'required|integer',
            'details.*.price' => 'required|numeric',
        ]);

        $waybill = Waybill::create($validated);

        foreach ($validated['details'] as $detail) {
            $waybill->details()->create([
                'description' => $detail['description'],
                'qty' => $detail['qty'],
                'price' => $detail['price'],
                'total' => $detail['qty'] * $detail['price'],
            ]);
        }

        return redirect()->route('waybills.index')->with('success', 'Waybill created successfully.');
    }

    public function edit($id)
    {
        $waybill = Waybill::with('details')->findOrFail($id);
        $customers = Customer::all();

        return view('waybills.edit', compact('waybill', 'customers'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'code' => 'required|string|max:255',
            'date' => 'required|date',
            'notes' => 'nullable|string',
            'details.*.description' => 'required|string',
            'details.*.qty' => 'required|integer',
            'details.*.price' => 'required|numeric',
        ]);

        $waybill = Waybill::findOrFail($id);
        $waybill->update($validated);

        $waybill->details()->delete();
        foreach ($validated['details'] as $detail) {
            $waybill->details()->create([
                'description' => $detail['description'],
                'qty' => $detail['qty'],
                'price' => $detail['price'],
                'total' => $detail['qty'] * $detail['price'],
            ]);
        }

        return redirect()->route('waybills.index')->with('success', 'Waybill updated successfully.');
    }

    public function destroy($id)
    {
        $waybill = Waybill::findOrFail($id);
        $waybill->delete();

        return redirect()->route('waybills.index')->with('success', 'Waybill deleted successfully.');
    }
}

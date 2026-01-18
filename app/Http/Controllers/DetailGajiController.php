<?php

namespace App\Http\Controllers;

use App\Models\DetailGaji;
use Illuminate\Http\Request;

class DetailGajiController extends Controller
{
    public function index()
    {
        $detailGaji = DetailGaji::all();
        return view('detailgaji.index', compact('detailGaji'));
    }

    public function create()
    {
        return view('detailgaji.create');
    }

    public function store(Request $request)
    {
        // dd($request->all());
        DetailGaji::create($request->all());

        return redirect()->route('gajian.show', $request->gaji_id)
            ->with('success', 'Detail Gaji created successfully.');
    }

    public function show($id)
    {
        $detailGaji = DetailGaji::findOrFail($id);
        return view('detailgaji.show', compact('detailGaji'));
    }

    public function edit($id)
    {
        $detailGaji = DetailGaji::findOrFail($id);
        return view('detailgaji.edit', compact('detailGaji'));
    }

    public function update(Request $request, $id)
    {

        $detailGaji = DetailGaji::findOrFail($id);
        $detailGaji->update($request->all());

        return redirect()->route('gajian.show', $detailGaji->gaji_id)
            ->with('success', 'Detail Gaji updated successfully.');
    }

    public function destroy($id)
    {
        $detailGaji = DetailGaji::findOrFail($id);
        $detailGaji->delete();

        return redirect()->route('gajian.show', $detailGaji->gaji_id)
            ->with('success', 'Detail Gaji deleted successfully.');
    }
}

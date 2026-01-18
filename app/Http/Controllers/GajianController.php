<?php

namespace App\Http\Controllers;

use App\Models\Gaji;
use App\Models\Karyawan;
use App\Models\DetailGaji;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class GajianController extends Controller
{
    public function index()
    {
        $gajian = Gaji::all();
        // dd($gajian);
        return view('gajian.index', compact('gajian'));
    }

    public function create()
    {
        $karyawan = Karyawan::all();
        return view('gajian.create', compact('karyawan'));
    }

    public function store(Request $request)
    {
        Gaji::create($request->all());

        return redirect()->route('gajian.index')
            ->with('success', 'Gaji created successfully.');
    }

    public function show($id)
    {
        $gajian = Gaji::find($id);
        $detailGaji = DetailGaji::where('gaji_id', $id)->orderBy('id','asc')->get();

        // dd($gajian,$detailGaji);
        return view('gajian.print', compact('gajian', 'detailGaji'));
    }

    // public function print($id)
    // {
    //     $gajian = Gaji::find($id);
    //     $detailGaji = DetailGaji::where('gaji_id', $id)->get();

    //     // dd($detailGaji);
    //     // return view('gajian.print', compact('gajian', 'detailGaji'));
    //     $pdf = Pdf::loadView('gajian.print', compact('gajian', 'detailGaji'));
    //     return $pdf->download($gajian->no_kode . '.pdf');
    // }

    public function formattanggal2($tanggal)
    {
        // Pastikan $tanggal adalah instance Carbon
        return \Carbon\Carbon::parse($tanggal)->format('j F Y');
    }

    public function print($id)
    {
        $gajian = Gaji::with('karyawan')->find($id);
        $detailGaji = DetailGaji::where('gaji_id', $id)->orderBy('id','asc')->get();

        $pdf = Pdf::loadView('gajian.print', compact('gajian', 'detailGaji'));

        $pdf->setPaper('a4', 'portrait');

        // Mengunduh PDF dengan nama berdasarkan no_kode
        return $pdf->download($gajian->karyawan->nama . '_' . $this->formattanggal2($gajian->tanggal) . '.pdf');

    }

    public function formattanggal($tanggal)
    {
        $tanggal = date('d-m-Y', strtotime($tanggal));
        return $tanggal;
    }


    public function edit($id)
    {
        $gajian = Gaji::find($id);
        $karyawan = Karyawan::all();
        $detailGaji = DetailGaji::where('gaji_id', $id)->get();
        return view('gajian.show', compact('gajian', 'karyawan', 'detailGaji'));
    }

    public function update(Request $request, $id)
    {

        $gaji = Gaji::find($id);
        $gaji->update($request->all());

        return redirect()->route('gajian.index')
            ->with('success', 'Gaji updated successfully.');
    }

    public function destroy($id)
    {
        $gaji = Gaji::find($id);
        $gaji->delete();

        return redirect()->route('gajian.index')
            ->with('success', 'Gaji deleted successfully.');
    }


}

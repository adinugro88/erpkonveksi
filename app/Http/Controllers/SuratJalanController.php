<?php

namespace App\Http\Controllers;

use App\Models\SuratJalan;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class SuratJalanController extends Controller
{
    public function preview($id)
    {
        $suratJalan = SuratJalan::with(['details', 'jobCust.Customer'])->findOrFail($id);
        
        return view('pdf.surat-jalan', compact('suratJalan'))->with('previewMode', true);
    }

    public function print($id)
    {
        $suratJalan = SuratJalan::with(['details', 'jobCust.Customer'])->findOrFail($id);
        
        $pdf = Pdf::loadView('pdf.surat-jalan', compact('suratJalan'));
        $pdf->setPaper('a4', 'portrait');
        
        $fileName = str_replace('/', '-', $suratJalan->kode_sj) . '-' . \Carbon\Carbon::parse($suratJalan->tanggal)->format('Ymd') . '-' . $suratJalan->jenis . '.pdf';
        
        return $pdf->download($fileName);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Illuminate\Http\Request;
use App\Models\Gaji;
use Flasher\Prime\FlasherInterface;

class KaryawanController extends Controller
{
    public function index()
    {
        $data = Karyawan::all();
        return view('karyawan.index', compact('data'));
    }

    public function create()
    {
        return view('karyawan.create');
    }

    public function edit($id)
    {
        $data = Karyawan::find($id);
        return view('karyawan.edit', compact('data', 'id'));
    }

    public function destroy($id)
    {
        // First verify the karyawan exists
        $karyawan = Karyawan::find($id);
        if (!$karyawan) {
            return redirect()->route('karyawan.index')->with('error', 'Data Karyawan tidak ditemukan.');
        }

        // Count actual gaji records for this karyawan
        $gajiBulanCount = Gaji::where('karyawan_id', $id)->count();
        
        if ($gajiBulanCount > 0) {
            return redirect()->route('karyawan.index')->with('error', "Karyawan tidak bisa dihapus karena sudah memiliki {$gajiBulanCount} data gaji.");
        }
        
        // No gaji records, safe to delete
        $karyawan->delete();
        return redirect()->route('karyawan.index')->with('success', 'Data Karyawan berhasil dihapus.');
    }
}

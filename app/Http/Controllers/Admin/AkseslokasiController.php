<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Kendaraan;
use Illuminate\Support\Facades\Validator;

class AkseslokasiController extends Controller
{
    public function index()
    {
        $kendaraans = Kendaraan::all();
        return view('admin/akses_lokasi.index', compact('kendaraans'));
    }

    public function edit($id)
    {
        $akses_lokasi = Kendaraan::where('id', $id)->first();
        return view('admin/akses_lokasi.update', compact('akses_lokasi'));
    }

    public function update(Request $request, $id)
    {

        $akses_lokasi = Kendaraan::find($id);
        $akses_lokasi->akses_lokasi = $request->akses_lokasi;
        $akses_lokasi->save();
        return redirect('admin/akses_lokasi')->with('success', 'Berhasil memperbarui akses lokasi');
    }

    public function destroy($id)
    {
        $akses_lokasi = Kendaraan::find($id);
        $akses_lokasi->delete();
        return redirect('admin/akses_lokasi')->with('success', 'Berhasil menghapus akses lokasi');
    }
}
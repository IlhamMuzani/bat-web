<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Pembelian_ban;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use App\Models\Detail_pembelianban;
use App\Models\Notabon_ujs;
use App\Models\Penerimaan_kaskecil;

class LaporanNotabonController extends Controller
{
    public function index(Request $request)
    {
        // if (auth()->check() && auth()->user()->menu['laporan penerimaan kas kecil']) {
        $status = $request->status;
        $kategori = $request->kategori;
        $tanggal_awal = $request->tanggal_awal;
        $tanggal_akhir = $request->tanggal_akhir;

        $inquery = Notabon_ujs::orderBy('id', 'DESC');

        if ($status == "posting") {
            $inquery->where('status', $status);
        } else {
            $inquery->where('status', 'posting');
        }

        if ($tanggal_awal && $tanggal_akhir) {
            $inquery->whereDate('tanggal_awal', '>=', $tanggal_awal)
                ->whereDate('tanggal_awal', '<=', $tanggal_akhir);
        }

        // $inquery = $inquery->get();

        // kondisi sebelum melakukan pencarian data masih kosong
        $hasSearch = $kategori || ($tanggal_awal && $tanggal_akhir);
        $inquery = $hasSearch ? $inquery->get() : collect();

        return view('admin.laporan_notabon.index', compact('inquery'));
        // } else {
        //     tidak memiliki akses
        //     return back()->with('error', array('Anda tidak memiliki akses'));
        // }
    }

    public function print_notabon(Request $request)
    {
        // if (auth()->check() && auth()->user()->menu['laporan penerimaan kas kecil']) {
        $status = $request->status;
        $kategori = $request->kategori;
        $tanggal_awal = $request->tanggal_awal;
        $tanggal_akhir = $request->tanggal_akhir;

        $query = Notabon_ujs::orderBy('id', 'DESC');

        if ($status == "posting") {
            $query->where('status', $status);
        } else {
            $query->where('status', 'posting');
        }

        if ($tanggal_awal && $tanggal_akhir) {
            $query->whereDate('tanggal_awal', '>=', $tanggal_awal)
                ->whereDate('tanggal_awal', '<=', $tanggal_akhir);
        }

        $inquery = $query->orderBy('id', 'DESC')->get();

        $pdf = PDF::loadView('admin.laporan_notabon.print', compact('inquery'));
        return $pdf->stream('Laporan_Nota_Bon.pdf');
        // } else {
        //     // tidak memiliki akses
        //     return back()->with('error', array('Anda tidak memiliki akses'));
        // }
    }
}
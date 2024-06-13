<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\admin\RuteperjalananController;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Kendaraan;
use App\Models\Pelanggan;
use App\Models\Rute_perjalanan;
use App\Models\Spk;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class PenerimaansjController extends Controller
{
    public function index(Request $request)
    {
        $status_spk = $request->status_spk;
        $tanggal_awal = $request->tanggal_awal;
        $tanggal_akhir = $request->tanggal_akhir;

        $spks = Spk::query();

        if ($status_spk) {
            $spks->where('status_spk', $status_spk);
        }

        if ($tanggal_awal && $tanggal_akhir) {
            $spks->whereBetween('tanggal_awal', [$tanggal_awal, $tanggal_akhir]);
        } elseif ($tanggal_awal) {
            $spks->where('tanggal_awal', '>=', $tanggal_awal);
        } elseif ($tanggal_akhir) {
            $spks->where('tanggal_awal', '<=', $tanggal_akhir);
        } else {
            // Jika tidak ada filter tanggal hari ini
            $spks->whereDate('tanggal_awal', Carbon::today());
        }

        $spks->orderBy('id', 'DESC');
        $spks = $spks->get();

        return view('admin.penerimaan_sj.index', compact('spks'));
    }
    
    public function postingspkpenerimaan($id)
    {
        $ban = Spk::where('id', $id)->first();

        $ban->update([
            'status_spk' => 'sj'
        ]);

        return response()->json(['success' => 'Berhasil memposting penerimaan']);
    }

    public function unpostspkpenerimaan($id)
    {
        $ban = Spk::where('id', $id)->first();

        $ban->update([
            'status_spk' => 'memo'
        ]);

        return response()->json(['success' => 'Berhasil unpost penerimaan']);
    }
}
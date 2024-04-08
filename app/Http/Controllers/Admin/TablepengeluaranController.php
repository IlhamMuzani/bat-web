<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Dompdf\Dompdf;
use App\Models\Divisi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Faktur_ekspedisi;
use App\Models\Memo_ekspedisi;
use App\Models\Memotambahan;
use App\Models\Pengeluaran_kaskecil;
use Illuminate\Support\Facades\Validator;

class TablepengeluaranController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $inquery = Pengeluaran_kaskecil::whereDate('created_at', $today)
            ->orWhere(function ($query) use ($today) {
                $query->where('status', 'unpost')
                    ->whereNull('memo_ekspedisi_id')
                    ->whereNull('memotambahan_id')
                    ->whereDate('created_at', '<', $today);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.tablepengeluaran.index', compact('inquery'));
    }
}

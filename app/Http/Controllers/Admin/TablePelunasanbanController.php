<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Dompdf\Dompdf;
use App\Models\Divisi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Faktur_ekspedisi;
use App\Models\Faktur_pelunasan;
use App\Models\Faktur_pelunasanban;
use App\Models\Memo_ekspedisi;
use App\Models\Memotambahan;
use App\Models\Pembelian_ban;
use App\Models\Pengeluaran_kaskecil;
use Illuminate\Support\Facades\Validator;

class TablePelunasanbanController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $inquery = Faktur_pelunasanban::whereDate('created_at', $today)
            ->orWhere(function ($query) use ($today) {
                $query->where('status', 'unpost')
                    ->whereDate('created_at', '<', $today);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.tablepelunasanban.index', compact('inquery'));
    }
}

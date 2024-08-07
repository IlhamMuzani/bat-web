<?php

namespace App\Http\Controllers\admin;

use Carbon\Carbon;
use App\Models\Ban;
use App\Models\Merek;
use App\Models\Ukuran;
use App\Models\Typeban;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Models\Pembelian_ban;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use App\Models\Penerimaan_kaskecil;
use App\Models\Saldo;
use Illuminate\Support\Facades\Validator;
use Egulias\EmailValidator\Result\Reason\DetailedReason;

class InqueryPenerimaankaskecilController extends Controller
{
    public function index(Request $request)
    {
        Penerimaan_kaskecil::where([
            ['status', 'posting']
        ])->update([
            'status_notif' => true
        ]);

        $status = $request->status;
        $tanggal_awal = $request->tanggal_awal;
        $tanggal_akhir = $request->tanggal_akhir;

        $inquery = Penerimaan_kaskecil::query();

        if ($status) {
            $inquery->where('status', $status);
        }

        if ($tanggal_awal && $tanggal_akhir) {
            $inquery->whereBetween('tanggal_awal', [$tanggal_awal, $tanggal_akhir]);
        } elseif ($tanggal_awal) {
            $inquery->where('tanggal_awal', '>=', $tanggal_awal);
        } elseif ($tanggal_akhir) {
            $inquery->where('tanggal_awal', '<=', $tanggal_akhir);
        } else {
            // Jika tidak ada filter tanggal hari ini
            $inquery->whereDate('tanggal_awal', Carbon::today());
        }

        $inquery->orderBy('id', 'DESC');
        $inquery = $inquery->get();

        return view('admin.inquery_penerimaankaskecil.index', compact('inquery'));
    }

    public function edit($id)
    {
        // if (auth()->check() && auth()->user()->menu['inquery perpanjangan stnk']) {

        $inquery = Penerimaan_kaskecil::where('id', $id)->first();

        return view('admin.inquery_penerimaankaskecil.update', compact('inquery'));
        // } else {
        //     // tidak memiliki akses
        //     return back()->with('error', array('Anda tidak memiliki akses'));
        // }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nominal' => 'required',
                // 'keterangan' => 'required', // Menambahkan aturan unique
            ],
            [
                'nominal.required' => 'Masukkan nominal',
                // 'nominal.numeric' => 'Nominal harus berupa angka',
                // 'keterangan.required' => 'Masukkan keterangan',
            ]
        );

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return back()->withInput()->with('error', $error);
        }

        $penerimaan = Penerimaan_kaskecil::findOrFail($id);

        $tanggal1 = Carbon::now('Asia/Jakarta');
        $format_tanggal = $tanggal1->format('d F Y');

        $saldo = Saldo::latest()->first();
        $sisaSaldo = $saldo->sisa_saldo;

        $nominallama = $penerimaan->nominal;

        $nominalbaru = str_replace('.', '', $request->nominal);
        $hasil = $sisaSaldo - $nominallama + $nominalbaru;

        // return $hasil;

        $subTotalInput = $request->input('sub_total');

        // Hilangkan 'Rp' dan titik
        $cleanedSubTotal = str_replace(['Rp', '.'], '', $subTotalInput);
        // Ubah koma menjadi titik
        $cleanedSubTotal = str_replace(',', '.', $cleanedSubTotal);

        $saldoTerakhir = Saldo::latest()->first();
        $saldo = $saldoTerakhir->id;

        $tanggal1 = Carbon::now('Asia/Jakarta');
        $format_tanggal = $tanggal1->format('d F Y');

        $tanggal = Carbon::now()->format('Y-m-d');
                $penerimaan->update([
            'nominal' => $request->nominal ? str_replace('.', '', $request->nominal) : null,
            'keterangan' => $request->keterangan,
            'saldo_masuk' => $request->saldo_masuk,
            'sisa_saldo' => $request->sisa_saldo,
            'sub_total' => $cleanedSubTotal,
            'status' => 'posting',
        ]);

        Saldo::create([
            'tanggal' => Carbon::now('Asia/Jakarta'),
            'sisa_saldo' => $hasil,
            'status' => 'inquery',
        ]);

        $cetakpdf = Penerimaan_kaskecil::where('id', $id)->first();

        return view('admin.inquery_penerimaankaskecil.show', compact('cetakpdf'));
    }

    public function show($id)
    {
        $cetakpdf = Penerimaan_kaskecil::where('id', $id)->first();
        
        return view('admin.inquery_penerimaankaskecil.show', compact('cetakpdf'));
    }

    public function unpostpenerimaan($id)
    {
        // Cari penerimaan kas kecil berdasarkan ID
        $item = Penerimaan_kaskecil::findOrFail($id);

        // Ambil nominal dari penerimaan
        $nominal = $item->nominal;

        // Ambil saldo terakhir
        $lastSaldo = Saldo::latest()->first();

        // Periksa apakah saldo terakhir ditemukan
        if (!$lastSaldo) {
            return back()->with('error', 'Saldo tidak ditemukan');
        }

        // Hitung sisa saldo setelah dipotong
        $sisaSaldo = $lastSaldo->sisa_saldo - $nominal;

        // Perbarui saldo terakhir
        $lastSaldo->update(['sisa_saldo' => $sisaSaldo]);

        // Perbarui status penerimaan menjadi "unpost"
        $item->update(['status' => 'unpost']);

        // Redirect kembali dengan pesan sukses
        return back()->with('success', 'Penerimaan berhasil di-"unpost"');
    }

    public function postingpenerimaan($id)
    {
        // Cari penerimaan kas kecil berdasarkan ID
        $item = Penerimaan_kaskecil::findOrFail($id);

        // Ambil nominal dari penerimaan
        $nominal = $item->nominal;

        // Ambil saldo terakhir
        $lastSaldo = Saldo::latest()->first();

        // Periksa apakah saldo terakhir ditemukan
        if (!$lastSaldo) {
            return back()->with('error', 'Saldo tidak ditemukan');
        }

        // Hitung sisa saldo setelah dipotong
        $sisaSaldo = $lastSaldo->sisa_saldo + $nominal;

        // Perbarui saldo terakhir
        $lastSaldo->update(['sisa_saldo' => $sisaSaldo]);

        // Perbarui status penerimaan menjadi "unpost"
        $item->update(['status' => 'posting']);

        // Redirect kembali dengan pesan sukses
        return back()->with('success', 'Penerimaan berhasil di-"Posting"');
    }

    public function hapuspenerimaan($id)
    {
        $ban = Penerimaan_kaskecil::where('id', $id)->first();

        $ban->delete();
        return back()->with('success', 'Berhasil');
    }


    public function destroy($id)
    {
        $ban = Pembelian_ban::find($id);
        $ban->detail_ban()->delete();
        $ban->delete();

        return redirect('admin/inquery_pembelianban')->with('success', 'Berhasil menghapus Pembelian');
    }
}
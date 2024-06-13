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

class InquerySpkController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->status;
        $tanggal_awal = $request->tanggal_awal;
        $tanggal_akhir = $request->tanggal_akhir;

        $spks = Spk::query();

        if ($status) {
            $spks->where('status', $status);
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

        return view('admin.inqueryspk.index', compact('spks'));
    }

    public function edit($id)
    {
        $inquery = Spk::where('id', $id)->first();
        $today = Carbon::today();

        $kendaraans = Kendaraan::all();
        $drivers = User::whereHas('karyawan', function ($query) {
            $query->where('departemen_id', '2');
        })->get();
        $ruteperjalanans = Rute_perjalanan::all();
        $pelanggans = Pelanggan::all();


        $spks = Spk::whereDate('created_at', $today)
            ->orWhere(function ($query) use ($today) {
                $query->where('status', 'unpost')
                    ->whereDate('created_at', '<', $today);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.inqueryspk.update', compact('inquery', 'kendaraans', 'drivers', 'ruteperjalanans', 'pelanggans'));
    }


    public function update(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'kode_spk' => 'unique:spks,kode_spk',
                'kendaraan_id' => 'required',
                'pelanggan_id' => 'required',
                'user_id' => 'required',
                'rute_perjalanan_id' => 'required',
                'uang_jalan' => 'required',
            ],
            [
                'kode_spk.unique' => 'Kode spk sudah ada',
                'kendaraan_id.required' => 'Pilih no kabin',
                'user_id.required' => 'Pilih driver',
                'pelanggan_id.required' => 'Pilih Pelanggan',
                'rute_perjalanan_id.required' => 'Pilih rute perjalanan',
                'uang_jalan.*' => 'Uang jalan harus berupa angka atau dalam format Rupiah yang valid',
            ]
        );

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return back()->withInput()->with('error', $errors);
        }

        $spk = Spk::findOrFail($id);

        $spk->pelanggan_id = $request->pelanggan_id;
        $spk->kendaraan_id = $request->kendaraan_id;
        $spk->no_kabin = $request->no_kabin;
        $spk->golongan = $request->golongan;
        $spk->km_awal = $request->km_awal;
        $spk->user_id = $request->user_id;
        $spk->kode_driver = $request->kode_driver;
        $spk->nama_driver = $request->nama_driver;
        $spk->telp = $request->telp;
        $spk->rute_perjalanan_id = $request->rute_perjalanan_id;
        $spk->kode_rute = $request->kode_rute;
        $spk->nama_rute = $request->nama_rute;
        $spk->status = $request->status;
        $spk->saldo_deposit = str_replace(',', '.', str_replace('.', '', $request->saldo_deposit));
        $spk->uang_jalan = str_replace(',', '.', str_replace('.', '', $request->uang_jalan));

        $spk->save();

        return redirect('admin/inquery_spk')->with('success', 'Berhasil memperbarui spk');
    }



    public function postingspk($id)
    {
        $ban = Spk::where('id', $id)->first();

        $ban->update([
            'status' => 'posting'
        ]);

        return response()->json(['success' => 'Berhasil memposting spk']);
    }

    public function unpostspk($id)
    {
        $ban = Spk::where('id', $id)->first();

        $ban->update([
            'status' => 'unpost'
        ]);

        return response()->json(['success' => 'Berhasil unpost spk']);
    }
}
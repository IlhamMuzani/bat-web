<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\admin\RuteperjalananController;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Kendaraan;
use App\Models\Memo_ekspedisi;
use App\Models\Pelanggan;
use App\Models\Rute_perjalanan;
use App\Models\Spk;
use App\Models\User;
use Illuminate\Support\Facades\Validator;


class SpkController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $spks = Spk::whereDate('created_at', $today)
            ->orWhere(function ($query) use ($today) {
                $query->where('status', 'unpost')
                    ->whereDate('created_at', '<', $today);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.spk.index', compact('spks'));
    }


    public function create()
    {
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

        return view('admin.spk.create', compact('kendaraans', 'drivers', 'ruteperjalanans', 'pelanggans'));
    }

    public function store(Request $request)
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

        $kode = $this->kode();
        // tgl indo
        $tanggal = Carbon::now()->format('Y-m-d');
        $tanggal1 = Carbon::now('Asia/Jakarta');
        $format_tanggal = $tanggal1->format('d F Y');
        $cetakpdf = Spk::create(array_merge(
            $request->all(),
            [
                'admin' => auth()->user()->karyawan->nama_lengkap,
                'kode_spk' => $this->kode(),
                'voucher' => '0',
                'pelanggan_id' => $request->pelanggan_id,
                'kendaraan_id' => $request->kendaraan_id,
                'no_kabin' => $request->no_kabin,
                'no_pol' => $request->no_pol,
                'golongan' => $request->golongan,
                'km_awal' => $request->km_awal,
                'user_id' => $request->user_id,
                'kode_driver' => $request->kode_driver,
                'nama_driver' => $request->nama_driver,
                'telp' => $request->telp,
                'rute_perjalanan_id' => $request->rute_perjalanan_id,
                'kode_rute' => $request->kode_rute,
                'nama_rute' => $request->nama_rute,
                'saldo_deposit' => str_replace(',', '.', str_replace('.', '', $request->saldo_deposit)),
                'uang_jalan' => str_replace(',', '.', str_replace('.', '', $request->uang_jalan)),
                'kode_spk' => $this->kode(),
                'qrcode_spk' => 'https:///batlink.id/spk/' . $kode,
                'tanggal' => $format_tanggal,
                'tanggal_awal' => $tanggal,
                'status' => 'posting',
            ]
        ));
        return redirect('admin/spk')->with('success', 'Berhasil menambahkan spk');
    }


    public function kode()
    {
        $lastBarang = Spk::where('kode_spk', 'like', 'SPK%')->latest()->first();
        $lastMonth = $lastBarang ? date('m', strtotime($lastBarang->created_at)) : null;
        $currentMonth = date('m');
        if (!$lastBarang || $currentMonth != $lastMonth) {
            $num = 1;
        } else {
            $lastCode = $lastBarang->kode_spk;
            $parts = explode('/', $lastCode);
            $lastNum = end($parts);
            $num = (int) $lastNum + 1;
        }
        $formattedNum = sprintf("%03s", $num);
        $prefix = 'SPK';
        $tahun = date('y');
        $tanggal = date('dm');
        $newCode = $prefix . "/" . $tanggal . $tahun . "/" . $formattedNum;
        return $newCode;
    }
}
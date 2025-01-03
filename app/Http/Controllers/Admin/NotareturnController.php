<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Detail_nota;
use App\Models\Nota_return;
use App\Models\Return_ekspedisi;
use App\Models\Satuan;
use App\Models\Tarif;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class NotareturnController extends Controller
{
    public function index()
    {
        $returnbarangs = Return_ekspedisi::all();

        return view('admin.nota_returnbarang.index', compact('returnbarangs'));
    }

    public function store(Request $request)
    {
        $validasi_pelanggan = Validator::make(
            $request->all(),
            [
                'return_ekspedisi_id' => 'required',
            ],
            [
                'return_ekspedisi_id.required' => 'Pilih kode penerimaan',
            ]
        );

        $error_pelanggans = array();

        if ($validasi_pelanggan->fails()) {
            array_push($error_pelanggans, $validasi_pelanggan->errors()->all()[0]);
        }

        $error_pesanans = array();
        $data_barang = collect();

        if ($request->has('barang_id')) {
            for ($i = 0; $i < count($request->barang_id); $i++) {
                $validasi_produk = Validator::make($request->all(), [
                    'barang_id.' . $i => 'required',
                    'kode_barang.' . $i => 'required',
                    'nama_barang.' . $i => 'required',
                    'satuan.' . $i => 'required',
                    'jumlah.' . $i => 'required',
                    'harga.' . $i => 'required',
                    'total.' . $i => 'required',
                ]);

                if ($validasi_produk->fails()) {
                    array_push($error_pesanans, "Harga nomor " . ($i + 1) . " belum dimasukkan!"); // Corrected the syntax for concatenation and indexing
                }

                $barang_id = is_null($request->barang_id[$i]) ? '' : $request->barang_id[$i];
                $kode_barang = is_null($request->kode_barang[$i]) ? '' : $request->kode_barang[$i];
                $nama_barang = is_null($request->nama_barang[$i]) ? '' : $request->nama_barang[$i];
                $satuan = is_null($request->satuan[$i]) ? '' : $request->satuan[$i];
                $jumlah = is_null($request->jumlah[$i]) ? '' : $request->jumlah[$i];
                $harga = is_null($request->harga[$i]) ? '' : $request->harga[$i];
                $total = is_null($request->total[$i]) ? '' : $request->total[$i];

                $data_barang->push([
                    'barang_id' => $barang_id,
                    'kode_barang' => $kode_barang,
                    'nama_barang' => $nama_barang,
                    'satuan' => $satuan,
                    'jumlah' => $jumlah,
                    'harga' => $harga,
                    'total' => $total,
                ]);
            }
        }

        if ($error_pelanggans || $error_pesanans) {
            return back()
                ->withInput()
                ->with('error_pelanggans', $error_pelanggans)
                ->with('error_pesanans', $error_pesanans)
                ->with('data_barang', $data_barang);
        }


        $kode = $this->kode();
        // format tanggal indo
        $tanggal1 = Carbon::now('Asia/Jakarta');
        $format_tanggal = $tanggal1->format('d F Y');

        $tanggal = Carbon::now()->format('Y-m-d');
        $cetakpdf = Nota_return::create([
            'admin' => auth()->user()->karyawan->nama_lengkap,
            'kode_nota' => $this->kode(),
            'return_ekspedisi_id' => $request->return_ekspedisi_id,
            'nomor_suratjalan' => $request->nomor_suratjalan,
            'kode_return' => $request->kode_return,
            'pelanggan_id' => $request->pelanggan_id,
            'kode_pelanggan' => $request->kode_pelanggan,
            'nama_pelanggan' => $request->nama_pelanggan,
            'alamat_pelanggan' => $request->alamat_pelanggan,
            'telp_pelanggan' => $request->telp_pelanggan,
            'kendaraan_id' => $request->kendaraan_id,
            'no_kabin' => $request->no_kabin,
            'no_pol' => $request->no_pol,
            'jenis_kendaraan' => $request->jenis_kendaraan,
            'user_id' => $request->user_id,
            'kode_driver' => $request->kode_driver,
            'nama_driver' => $request->nama_driver,
            'telp' => $request->telp,
            'grand_total' => str_replace(',', '.', str_replace('.', '', $request->grand_total)),
            'tanggal' => $format_tanggal,
            'tanggal_awal' => $tanggal,
            'qrcode_nota' => 'https://javaline.id/nota_ekspedisi/' . $kode,
            'status' => 'posting',
            'status_notif' => false,
        ]);

        $transaksi_id = $cetakpdf->id;

        if ($cetakpdf) {
            foreach ($data_barang as $data_pesanan) {
                Detail_nota::create([
                    'nota_return_id' => $cetakpdf->id,
                    'barang_id' => $data_pesanan['barang_id'],
                    'kode_barang' => $data_pesanan['kode_barang'],
                    'nama_barang' => $data_pesanan['nama_barang'],
                    'satuan' => $data_pesanan['satuan'],
                    'jumlah' => $data_pesanan['jumlah'],
                    'harga' =>  str_replace(',', '.', str_replace('.', '', $data_pesanan['harga'])),
                    'total' =>  str_replace(',', '.', str_replace('.', '', $data_pesanan['total'])),
                ]);
                Barang::where('id', $data_pesanan['barang_id'])->increment('jumlah', $data_pesanan['jumlah']);
            }
        }

        $details = Detail_nota::where('nota_return_id', $cetakpdf->id)->get();

        return view('admin.nota_returnbarang.show', compact('cetakpdf', 'details'));
    }


    public function kode()
    {
        // Ambil kode memo terakhir yang sesuai format 'FL%' dan kategori 'Memo Perjalanan'
        $lastBarang = Nota_return::where('kode_return', 'like', 'FL%')
            ->orderBy('id', 'desc')
            ->first();

        // Inisialisasi nomor urut
        $num = 1;

        // Jika ada kode terakhir, proses untuk mendapatkan nomor urut
        if ($lastBarang) {
            $lastCode = $lastBarang->kode_return;

            // Pastikan kode terakhir sesuai dengan format FL[YYYYMMDD][NNNN]A
            if (preg_match('/^FL(\d{6})(\d{4})B$/', $lastCode, $matches)) {
                $lastDate = $matches[1]; // Bagian tanggal: ymd (contoh: 241125)
                $lastMonth = substr($lastDate, 2, 2); // Ambil bulan dari tanggal (contoh: 11)
                $currentMonth = date('m'); // Bulan saat ini

                if ($lastMonth === $currentMonth) {
                    // Jika bulan sama, tambahkan nomor urut
                    $lastNum = (int)$matches[2]; // Bagian nomor urut (contoh: 0001)
                    $num = $lastNum + 1;
                }
            }
        }

        // Formatkan nomor urut menjadi 4 digit
        $formattedNum = sprintf("%04s", $num);

        // Buat kode baru dengan tambahan huruf B di belakang
        $prefix = 'FL';
        $kodeMemo = $prefix . date('ymd') . $formattedNum . 'B'; // Format akhir kode memo

        return $kodeMemo;
    }

    public function show($id)
    {
        $cetakpdf = Nota_return::where('id', $id)->first();

        return view('admin.nota_returnbarang.show', compact('cetakpdf'));
    }

    public function cetakpdf($id)
    {
        $cetakpdf = Nota_return::where('id', $id)->first();
        $details = Detail_nota::where('nota_return_id', $cetakpdf->id)->get();

        $pdf = PDF::loadView('admin.nota_returnbarang.cetak_pdf', compact('cetakpdf', 'details'));
        $pdf->setPaper('letter', 'portrait'); // Set the paper size to portrait letter

        return $pdf->stream('Nota_return.pdf');
    }
}

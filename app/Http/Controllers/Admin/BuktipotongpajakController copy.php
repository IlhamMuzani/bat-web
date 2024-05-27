<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Bukti_potongpajak;
use App\Models\Detail_bukti;
use App\Models\Tagihan_ekspedisi;
use Illuminate\Support\Facades\Validator;

class BuktipotongpajakController extends Controller
{
    public function index()
    {
        $buktipotongpajaks = Bukti_potongpajak::get();
        return view('admin.bukti_potongpajak.index', compact('buktipotongpajaks'));
    }

    public function create()
    {
        $tagihanEkspedisis = Tagihan_ekspedisi::where(['status' => 'posting', 'status_terpakai' => null])->get();
        return view('admin.bukti_potongpajak.create', compact(
            'tagihanEkspedisis'
        ));
    }

    public function store(Request $request)
    {
        $validasi_barang = Validator::make($request->all(), [
            'kategori' => 'required',
            'kategoris' => 'required',
            'nomor_faktur' => 'required',
            'tanggal' => 'required',
            'grand_total' => 'required',
        ], [
            'kategori.required' => 'Pilih Status',
            'kategoris.required' => 'Pilih Kategori',
            'nomor_faktur.required' => 'Masukkan nomor faktur',
            'tanggal.required' => 'Pilih Tanggal',
            'grand_total.required' => 'Grand total kosong',
        ]);

        $error_barangs = array();

        if ($validasi_barang->fails()) {
            array_push($error_barangs, $validasi_barang->errors()->all()[0]);
        }

        $error_pesanans = array();
        $data_pembelians = collect();

        if ($request->has('tagihan_ekspedisi_id') || $request->has('kode_tagihan') || $request->has('tanggal') || $request->has('nama_pelanggan') || $request->has('total')) {
            for ($i = 0; $i < count($request->tagihan_ekspedisi_id); $i++) {
                // Check if either 'keterangan_tambahan' or 'nominal_tambahan' has input
                if (empty($request->tagihan_ekspedisi_id[$i]) && empty($request->kode_tagihan[$i]) && empty($request->tanggal[$i]) && empty($request->nama_pelanggan[$i]) && empty($request->total[$i])) {
                    continue; // Skip validation if both are empty
                }

                $validasi_produk = Validator::make($request->all(), [
                    'tagihan_ekspedisi_id.' . $i => 'required',
                ]);

                if ($validasi_produk->fails()) {
                    array_push($error_pesanans, "Invoice nomor " . ($i + 1) . " belum dilengkapi!");
                }

                $tagihan_ekspedisi_id = $request->tagihan_ekspedisi_id[$i] ?? '';
                $kode_tagihan = $request->kode_tagihan[$i] ?? '';
                $tanggal = $request->tanggal[$i] ?? '';
                $nama_pelanggan = $request->nama_pelanggan[$i] ?? '';
                $total = $request->total[$i] ?? '';

                $data_pembelians->push([
                    'tagihan_ekspedisi_id' => $tagihan_ekspedisi_id,
                    'kode_tagihan' => $kode_tagihan,
                    'tanggal' => $tanggal,
                    'nama_pelanggan' => $nama_pelanggan,
                    'total' => $total
                ]);
            }
        }

        if ($error_barangs || $error_pesanans) {
            return back()
                ->withInput()
                ->with('error_barangs', $error_barangs)
                ->with('error_pesanans', $error_pesanans)
                ->with('data_pembelians', $data_pembelians);
        }

        $kode = $this->kode();

        // tgl indo
        $tanggal1 = Carbon::now('Asia/Jakarta');
        $format_tanggal = $tanggal1->format('d F Y');
        $tanggal = Carbon::now()->format('Y-m-d');

        $pemasukan = Bukti_potongpajak::create([
            'user_id' => auth()->user()->id,
            'kategori' => $request->kategori,
            'kategoris' => $request->kategoris,
            'nomor_faktur' => $request->nomor_faktur,
            'tanggal' => $request->tanggal,
            'grand_total' =>  str_replace('.', '', $request->grand_total),
            'kode_bukti' => $kode,
            'tanggal' => $format_tanggal,
            'tanggal_awal' => $tanggal,
            'status' => 'posting',
        ]);

        if ($pemasukan) {
            foreach ($data_pembelians as $data_pesanan) {
                Detail_bukti::create([
                    'bukti_potongpajak_id' => $pemasukan->id,
                    'tagihan_ekspedisi_id' => $data_pesanan['tagihan_ekspedisi_id'],
                    'kode_tagihan' => $data_pesanan['kode_tagihan'],
                    'tanggal' => $data_pesanan['tanggal'],
                    'nama_pelanggan' => $data_pesanan['nama_pelanggan'],
                    'total' => $data_pesanan['total'],
                ]);
            }
        }

        return redirect('admin/bukti_potongpajak')->with('success', 'Berhasil menambah pemasukan');
    }

    public function edit($id)
    {
        $pemasukan = Bukti_potongpajak::where('id', $id)->first();
        $details = Detail_bukti::where('pemasukan_id', $id)
            ->select(
                'id as detail_id',
                'barang_id as id',
                'nama_barang',
                'harga_pcs',
                'harga_dus',
                'harga_renceng',
                'harga_pack',
                'satuan',
                'jumlah',
                'total',
            )
            ->get();
        $detail_id_data = Detail_bukti::where('pemasukan_id', $id)->pluck('id', 'barang_id')->toArray();
        $barangs = Tagihan_ekspedisi::get();

        return view('admin.pemasukan.update', compact('details', 'detail_id_data', 'pemasukan', 'barangs'));
    }

    public function update(Request $request, $id)
    {
        $validasi_pelanggan = Validator::make($request->all(), [
            'supplier_id' => 'required',
            'user_id' => 'required',
        ], [
            'supplier_id.required' => 'Pilih nama supplier!',
            'user_id.required' => 'Pilih nama sales!',
        ]);

        $error_pelanggans = array();
        $error_pesanans = array();
        $data_pembelians = collect();

        if ($validasi_pelanggan->fails()) {
            array_push($error_pelanggans, $validasi_pelanggan->errors()->all()[0]);
        }

        if ($request->has('id')) {
            foreach ($request->id as $key => $barang_id) {
                $validator_produk = Validator::make($request->all(), [
                    'harga.' . $barang_id => 'required',
                    'jumlah.' . $barang_id => 'required',
                ]);

                if ($validator_produk->fails()) {
                    $error_pesanans[] = "Tagihan_ekspedisi nomor " . ($key + 1) . " belum dilengkapi!";
                }

                $harga = $request->harga[$barang_id] ?? '';
                $satuan = $request->satuan[$barang_id] ?? '';
                $jumlah = $request->jumlah[$barang_id] ?? '';
                $total = $request->total[$barang_id] ?? '';

                $barang = Tagihan_ekspedisi::where('id', $barang_id)->first();

                $data_pembelians->push([
                    'id' => $barang_id,
                    'nama_barang' => $barang->nama_barang,
                    'harga_pcs' => $barang->harga_pcs,
                    'harga_dus' => $barang->harga_dus,
                    'harga_renceng' => $barang->harga_renceng,
                    'harga_pack' => $barang->harga_pack,
                    'harga' => $harga,
                    'satuan' => $satuan,
                    'jumlah' => $jumlah,
                    'total' => $total,
                ]);
            }
        }

        if ($validasi_pelanggan->fails() || $error_pesanans) {
            return back()
                ->withInput()
                ->with('error_pelanggans', $error_pelanggans)
                ->with('error_pesanans', $error_pesanans)
                ->with('data_pembelians', $data_pembelians);
        }

        Bukti_potongpajak::where('id', $id)->update([
            'grand_total' => $request->grand_total
        ]);

        foreach ($request->id as $barang_id) {
            $detail = Detail_bukti::where([
                ['pemasukan_id', $id],
                ['barang_id', $barang_id]
            ])->exists();
            if ($detail) {
                Detail_bukti::where([
                    ['pemasukan_id', $id],
                    ['barang_id', $barang_id]
                ])->update([
                    'satuan' => $request->satuan[$barang_id],
                    'jumlah' => $request->jumlah[$barang_id],
                    'total' => $request->total[$barang_id]
                ]);
            } else {
                $barang = Tagihan_ekspedisi::where('id', $barang_id)->first();
                Detail_bukti::create([
                    'pemasukan_id' => $id,
                    'barang_id' => $barang->id,
                    'kode_barang' => $barang->kode_barang,
                    'nama_barang' => $barang->nama_barang,
                    'harga_pcs' => $barang->harga_pcs,
                    'harga_dus' => $barang->harga_dus,
                    'harga_renceng' => $barang->harga_renceng,
                    'harga_pack' => $barang->harga_pack,
                    'satuan' => $request->satuan[$barang_id],
                    'jumlah' => $request->jumlah[$barang_id],
                    'total' => $request->total[$barang_id],
                ]);
            }
        }

        return redirect('admin/pemasukan')->with('success', 'Berhasil memperbarui pemasukan');
    }

    public function kode()
    {
        $item = Bukti_potongpajak::all();
        if ($item->isEmpty()) {
            $num = "000001";
        } else {
            $id = Bukti_potongpajak::getId();
            foreach ($id as $value);
            $idlm = $value->id;
            $idbr = $idlm + 1;
            $num = sprintf("%06s", $idbr);
        }
        $tahun = date('y');
        $data = 'AP';
        $tanggal = date('dm');
        $kode_item = $data . "/" . $tanggal . $tahun . "/" . $num;

        return $kode_item;
    }

    public function destroy($id)
    {
        $pemasukan = Bukti_potongpajak::find($id);
        $pemasukan->detail_pemasukan()->delete();
        $pemasukan->delete();

        return redirect('admin/pemasukan')->with('success', 'Berhasil menghapus pemasukan');
    }

    public function cetakpdf($id)
    {
        $pemasukan = Bukti_potongpajak::find($id);
        $details = Detail_bukti::where('pemasukan_id', $pemasukan->id)->get();

        $pdf = PDF::loadView('admin.pemasukan.cetak_pdf', compact('details', 'pemasukan'));
        $pdf->setPaper('letter', 'portrait');

        return $pdf->stream('Pemasukan_barang.pdf');
    }

    public function get_item($id)
    {
        $barang = Tagihan_ekspedisi::where('id', $id)->first();
        return $barang;
    }

    // public function delete_item($id)
    // {
    //     $detail = Detail_bukti::where('id', $id);
    //     if ($detail->exists()) {
    //         $detail->delete();
    //     }

    //     return true;
    // }
}

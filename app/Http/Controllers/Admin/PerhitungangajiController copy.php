<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Detail_gajikaryawan;
use App\Models\Detail_pengeluaran;
use App\Models\Detail_tariftambahan;
use App\Models\Karyawan;
use App\Models\Pengeluaran_kaskecil;
use App\Models\Perhitungan_gajikaryawan;
use Illuminate\Support\Facades\Validator;

class PerhitungangajiController extends Controller
{
    public function index()
    {
        $karyawans = Karyawan::get();
        return view('admin.perhitungan_gaji.index', compact('karyawans'));
    }

    public function store(Request $request)
    {
        $validasi_pelanggan = Validator::make(
            $request->all(),
            [
                'periode_awal' => 'required',
                'periode_akhir' => 'required',
            ],
            [
                'periode_awal.required' => 'Masukkan periode awal',
                'periode_akhir.required' => 'Masukkan periode akhir',
            ]
        );

        $error_pelanggans = array();

        if ($validasi_pelanggan->fails()) {
            array_push($error_pelanggans, $validasi_pelanggan->errors()->all()[0]);
        }

        $error_pesanans = array();
        $data_pembelians = collect();

        if ($request->has('karyawan_id')) {
            for ($i = 0; $i < count($request->karyawan_id); $i++) {
                $validasi_produk = Validator::make($request->all(), [
                    'karyawan_id.' . $i => 'required',
                    'kode_karyawan.' . $i => 'required',
                    'nama_lengkap.' . $i => 'required',
                    'gaji.' . $i => 'required',
                    'uang_makan.' . $i => 'required',
                    'uang_hadir.' . $i => 'required',
                    'hari_kerja.' . $i => 'required',
                    // 'lembur.' . $i => 'required',
                    // 'hasil_lembur.' . $i => 'required',
                    // 'storing.' . $i => 'required',
                    // 'hasil_storing.' . $i => 'required',
                    'gaji_kotor.' . $i => 'required',
                    // 'kurangtigapuluh.' . $i => 'required',
                    // 'lebihtigapuluh.' . $i => 'required',
                    // 'hasilkurang.' . $i => 'required',
                    // 'hasillebih.' . $i => 'required',
                    // 'pelunasan_kasbon.' . $i => 'required',
                    // 'potongan_bpjs.' . $i => 'required',
                    // 'absen.' . $i => 'required',
                    // 'hasil_absen.' . $i => 'required',
                    'gajinol_pelunasan.' . $i => 'required',
                    'gaji_bersih.' . $i => 'required',
                ]);

                if ($validasi_produk->fails()) {
                    array_push($error_pesanans, "Perhitungan " . $i + 1 . " belum dilengkapi!");
                }

                $karyawan_id = $request->karyawan_id[$i] ?? '';
                $kode_karyawan = $request->kode_karyawan[$i] ?? '';
                $nama_lengkap = $request->nama_lengkap[$i] ?? '';
                $gaji = $request->gaji[$i] ?? '';
                $uang_makan = $request->uang_makan[$i] ?? '';
                $uang_hadir = $request->uang_hadir[$i] ?? '';
                $hari_kerja = $request->hari_kerja[$i] ?? '';
                $lembur = $request->lembur[$i] ?? 0;
                $hasil_lembur = $request->hasil_lembur[$i] ?? 0;
                $storing = $request->storing[$i] ?? 0;
                $hasil_storing = $request->hasil_storing[$i] ?? 0;
                $gaji_kotor = $request->gaji_kotor[$i] ?? 0;
                $kurangtigapuluh = $request->kurangtigapuluh[$i] ?? 0;
                $lebihtigapuluh = $request->kurangtigapuluh[$i] ?? 0;
                $hasilkurang = $request->hasilkurang[$i] ?? 0;
                $hasillebih = $request->hasillebih[$i] ?? 0;
                $pelunasan_kasbon = $request->pelunasan_kasbon[$i] ?? 0;
                $potongan_bpjs = $request->potongan_bpjs[$i] ?? '';
                $lainya = $request->lainya[$i] ?? 0;
                $absen = $request->absen[$i] ?? 0;
                $hasil_absen = $request->hasil_absen[$i] ?? 0;
                $gajinol_pelunasan = $request->gajinol_pelunasan[$i] ?? 0;
                $gaji_bersih = $request->gaji_bersih[$i] ?? 0;

                $data_pembelians->push([
                    'karyawan_id' => $karyawan_id,
                    'kode_karyawan' => $kode_karyawan,
                    'nama_lengkap' => $nama_lengkap,
                    'gaji' => $gaji,
                    'uang_makan' => $uang_makan,
                    'uang_hadir' => $uang_hadir,
                    'hari_kerja' => $hari_kerja,
                    'lembur' => $lembur,
                    'hasil_lembur' => $hasil_lembur,
                    'storing' => $storing,
                    'hasil_storing' => $hasil_storing,
                    'gaji_kotor' => $gaji_kotor,
                    'kurangtigapuluh' => $kurangtigapuluh,
                    'lebihtigapuluh' => $lebihtigapuluh,
                    'hasilkurang' => $hasilkurang,
                    'hasillebih' => $hasillebih,
                    'pelunasan_kasbon' => $pelunasan_kasbon,
                    'potongan_bpjs' => $potongan_bpjs,
                    'lainya' => $lainya,
                    'absen' => $absen,
                    'hasil_absen' => $hasil_absen,
                    'gajinol_pelunasan' => $gajinol_pelunasan,
                    'gaji_bersih' => $gaji_bersih,
                ]);
            }
        } else {
        }

        if ($error_pelanggans || $error_pesanans) {
            return back()
                ->withInput()
                ->with('error_pelanggans', $error_pelanggans)
                ->with('error_pesanans', $error_pesanans)
                ->with('data_pembelians', $data_pembelians);
        }

        $kode = $this->kode();
        // format tanggal indo
        $tanggal1 = Carbon::now('Asia/Jakarta');
        $format_tanggal = $tanggal1->format('d F Y');
        $tanggal = Carbon::now()->format('Y-m-d');
        $cetakpdf = Perhitungan_gajikaryawan::create([
            'user_id' => auth()->user()->id,
            'kategori' => 'Mingguan',
            'kode_gaji' => $this->kode(),
            'periode_awal' => $request->periode_awal,
            'periode_akhir' => $request->periode_akhir,
            'keterangan' => $request->keterangan,
            'total_gaji' => str_replace(',', '.', str_replace('.', '', $request->total_gaji)),
            'total_pelunasan' => str_replace(',', '.', str_replace('.', '', $request->total_pelunasan)),
            'grand_total' => str_replace(',', '.', str_replace('.', '', $request->grand_total)),
            'tanggal' => $format_tanggal,
            'tanggal_awal' => $tanggal,
            'qr_code_perhitungan' => 'https://batlink.id/perhitungan_gaji/' . $kode,
            'status' => 'unpost',
            'status_notif' => false,
        ]);

        $transaksi_id = $cetakpdf->id;
        $kodeban = $this->kodegaji();
        if ($cetakpdf) {
            foreach ($data_pembelians as $data_pesanan) {
                // Simpan Detail_gajikaryawan baru
                $detailfaktur = Detail_gajikaryawan::create([
                    'kode_gajikaryawan' => $this->kodegaji(),
                    'kategori' => 'Mingguan',
                    'perhitungan_gajikaryawan_id' => $cetakpdf->id,
                    'karyawan_id' => $data_pesanan['karyawan_id'],
                    'kode_karyawan' => $data_pesanan['kode_karyawan'],
                    'nama_lengkap' => $data_pesanan['nama_lengkap'],
                    'gaji' => str_replace('.', '', $data_pesanan['gaji']),
                    'uang_makan' => str_replace('.', '', $data_pesanan['uang_makan']),
                    'uang_hadir' => str_replace('.', '', $data_pesanan['uang_hadir']),
                    'hari_kerja' => $data_pesanan['hari_kerja'],
                    'lembur' => $data_pesanan['lembur'],
                    'hasil_lembur' => str_replace('.', '', $data_pesanan['hasil_lembur']),
                    'storing' => $data_pesanan['storing'],
                    'hasil_storing' => str_replace('.', '', $data_pesanan['hasil_storing']),
                    'gaji_kotor' => str_replace('.', '', $data_pesanan['gaji_kotor']),
                    'kurangtigapuluh' => $data_pesanan['kurangtigapuluh'],
                    'lebihtigapuluh' => $data_pesanan['lebihtigapuluh'],
                    'hasilkurang' => str_replace('.', '', $data_pesanan['hasilkurang']),
                    'hasillebih' => str_replace('.', '', $data_pesanan['hasillebih']),
                    'pelunasan_kasbon' => str_replace('.', '', $data_pesanan['pelunasan_kasbon']),
                    'lainya' => str_replace('.', '', $data_pesanan['lainya']),
                    'potongan_bpjs' => !empty($data_pesanan['potongan_bpjs']) ? str_replace('.', '', $data_pesanan['potongan_bpjs']) : null,
                    'absen' => $data_pesanan['absen'],
                    'hasil_absen' => str_replace('.', '', $data_pesanan['hasil_absen']),
                    'gajinol_pelunasan' => str_replace('.', '', $data_pesanan['gajinol_pelunasan']),
                    'gaji_bersih' => str_replace('.', '', $data_pesanan['gaji_bersih']),
                    'status' => 'unpost',
                    'tanggal' => $format_tanggal,
                    'tanggal_awal' => $tanggal,
                ]);
            }
        }

        $kodepengeluaran = $this->kodepengeluaran();

        Pengeluaran_kaskecil::create([
            'perhitungan_gajikaryawan_id' => $cetakpdf->id,
            'user_id' => auth()->user()->id,
            'kode_pengeluaran' => $this->kodepengeluaran(),
            // 'kendaraan_id' => $request->kendaraan_id,
            'keterangan' => $request->keterangan,
            'grand_total' => str_replace(',', '.', str_replace('.', '', $request->total_gaji)),
            'jam' => $tanggal1->format('H:i:s'),
            'tanggal' => $format_tanggal,
            'tanggal_awal' => $tanggal,
            'qrcode_return' => 'https://batlink.id/pengeluaran_kaskecil/' . $kodepengeluaran,
            'status' => 'pending',
        ]);

        Detail_pengeluaran::create([
            'perhitungan_gajikaryawan_id' => $cetakpdf->id,
            'barangakun_id' => 1,
            'kode_detailakun' => $this->kodeakuns(),
            'kode_akun' => 'KA000001',
            'nama_akun' => 'GAJI & TUNJANGAN',
            'keterangan' => $request->keterangan,
            'nominal' => str_replace(',', '.', str_replace('.', '', $request->total_gaji)),
            'status' => 'pending',
        ]);


        $details = Detail_gajikaryawan::where('perhitungan_gajikaryawan_id', $cetakpdf->id)->get();

        return view('admin.perhitungan_gaji.show', compact('details', 'cetakpdf'));
    }


    public function kodeakuns()
    {
        $lastBarang = Detail_pengeluaran::latest()->first();
        if (!$lastBarang) {
            $num = 1;
        } else {
            $lastCode = $lastBarang->kode_detailakun;
            $num = (int) substr($lastCode, strlen('KKA')) + 1;
        }
        $formattedNum = sprintf("%06s", $num);
        $prefix = 'KKA';
        $newCode = $prefix . $formattedNum;
        return $newCode;
    }

    public function kodepengeluaran()
    {
        $lastBarang = Pengeluaran_kaskecil::latest()->first();
        if (!$lastBarang) {
            $num = 1;
        } else {
            $lastCode = $lastBarang->kode_pengeluaran;
            $num = (int) substr($lastCode, strlen('KK')) + 1;
        }
        $formattedNum = sprintf("%06s", $num);
        $prefix = 'KK';
        $newCode = $prefix . $formattedNum;
        return $newCode;
    }



    public function kode()
    {
        // Mengambil kode terbaru dari database dengan awalan 'MP'
        $lastBarang = Perhitungan_gajikaryawan::where('kode_gaji', 'like', 'GJM%')->latest()->first();

        // Jika tidak ada kode sebelumnya, mulai dengan 1
        if (!$lastBarang) {
            $num = 1;
        } else {
            // Jika ada kode sebelumnya, ambil nomor terakhir
            $lastCode = $lastBarang->kode_gaji;

            // Ambil nomor dari kode terakhir, tanpa awalan 'MP', lalu tambahkan 1
            $num = (int) substr($lastCode, strlen('GJM')) + 1;
        }

        // Format nomor dengan leading zeros sebanyak 6 digit
        $formattedNum = sprintf("%06s", $num);

        // Awalan untuk kode baru
        $prefix = 'GJM';

        // Buat kode baru dengan menggabungkan awalan dan nomor yang diformat
        $newCode = $prefix . $formattedNum;

        // Kembalikan kode
        return $newCode;
    }

    // public function kodegaji()
    // {
    //     $lastBarang = Detail_gajikaryawan::latest()->first();
    //     if (!$lastBarang) {
    //         $num = 1;
    //     } else {
    //         $lastCode = $lastBarang->kode_gajikaryawan;
    //         $num = (int) substr($lastCode, strlen('GK')) + 1;
    //     }
    //     $formattedNum = sprintf("%06s", $num);
    //     $prefix = 'GK';
    //     $newCode = $prefix . $formattedNum;
    //     return $newCode;
    // }

    public function kodegaji()
    {
        $gaji = Detail_gajikaryawan::all();
        if ($gaji->isEmpty()) {
            $num = "000001";
        } else {
            $id = Detail_gajikaryawan::getId();
            foreach ($id as $value);
            $idlm = $value->id;
            $idbr = $idlm + 1;
            $num = sprintf("%06s", $idbr);
        }

        $data = 'GK';
        $kodeGaji = $data . $num;
        return $kodeGaji;
    }


    public function cetakpdf($id)
    {
        $cetakpdf = Perhitungan_gajikaryawan::where('id', $id)->first();
        $details = Detail_gajikaryawan::where('perhitungan_gajikaryawan_id', $cetakpdf->id)->get();

        $pdf = PDF::loadView('admin.perhitungan_gaji.cetak_pdf', compact('cetakpdf', 'details'));
        $pdf->setPaper('letter', 'portrait'); // Set the paper size to portrait letter

        return $pdf->stream('Gaji_karyawan.pdf');
    }

    // public function get_item($id)
    // {
    //     $barang = Karyawan::where('id', $id)->first();
    //     return $barang;
    // }
}
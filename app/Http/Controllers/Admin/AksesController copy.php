<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Dompdf\Dompdf;
use App\Models\User;
use App\Models\Karyawan;
use App\Models\Departemen;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AksesController extends Controller
{
    public function index()
    {
        if (auth()->check() && auth()->user()->menu['akses']) {

            $aksess = User::where(['cek_hapus' => 'tidak'])->get();
            return view('admin/akses.index', compact('aksess'));
        } else {
            // tidak memiliki akses
            return back()->with('error', array('Anda tidak memiliki akses'));
        }
    }

    public function create()
    {
        if (auth()->check() && auth()->user()->menu['akses']) {

            $departemens = Departemen::all();
            $karyawans = Karyawan::where(['status' => 'null'])->get();
            return view('admin/user.create', compact('departemens', 'karyawans'));
        } else {
            // tidak memiliki akses
            return back()->with('error', array('Anda tidak memiliki akses'));
        }
    }

    public function karyawan($id)
    {
        $user = Karyawan::where('id', $id)->first();

        return json_decode($user);
    }

    public function edit($id)
    {
        if (auth()->check() && auth()->user()->menu['akses']) {

            $akses = User::where('id', $id)->first();
            return view('admin/akses.update', compact('akses'));
        } else {
            // tidak memiliki akses
            return back()->with('error', array('Anda tidak memiliki akses'));
        }
    }

    public function access($id)
    {
        if (auth()->check() && auth()->user()->menu['akses']) {

            $menus = array(
                'karyawan',
                'user',
                'akses',
                'departemen',
                'supplier',
                'pelanggan',
                'divisi mobil',
                'jenis kendaraan',
                'golongan',
                'kendaraan',
                'ukuran ban',
                'merek ban',
                'type ban',
                'ban',
                'nokir',
                'stnk',
                'part',
                'sopir',
                'rute perjalanan',
                'biaya tambahan',
                'potongan memo',
                'tarif',
                'satuan barang',
                'barang return',
                'akun',
                'update km',
                'perpanjangan stnk',
                'perpanjangan kir',
                'pemasangan ban',
                'pelepasan ban',
                'pemasangan part',
                'penggantian oli',
                'status perjalanan kendaraan',
                'pembelian ban',
                'pembelian part',
                'memo ekspedisi',
                'faktur ekspedisi',
                'invoice faktur ekspedisi',
                'return barang ekspedisi',
                'faktur pelunasan ekspedisi',
                'penerimaan kas kecil',
                'pengambilan kas kecil',
                'deposit sopir',
                'inquery penerimaan kas kecil',
                'inquery pengambilan kas kecil',
                'inquery deposit sopir',
                'inquery update km',
                'inquery pembelian ban',
                'inquery pembelian part',
                'inquery pemasangan ban',
                'inquery pelepasan ban',
                'inquery pemasangan part',
                'inquery penggantian oli',
                'inquery perpanjangan stnk',
                'inquery perpanjangan kir',
                'inquery memo ekspedisi',
                'inquery faktur ekspedisi',
                'inquery invoice faktur ekspedisi',
                'inquery return ekspedisi',
                'inquery pelunasan ekspedisi',
                'laporan pembelian ban',
                'laporan pembelian part',
                'laporan pemasangan ban',
                'laporan pelepasan ban',
                'laporan pemasangan part',
                'laporan penggantian oli',
                'laporan update km',
                'laporan status perjalanan kendaraan',
                'laporan kas kecil',
                'laporan penerimaan kas kecil',
                'laporan pengambilan kas kecil',
                'laporan mobil logistik',
                'laporan deposit sopir',
                'laporan memo ekspedisi',
                'laporan faktur ekspedisi',
                'laporan pph',
                'laporan invoice ekspedisi',
                'laporan return barang ekspedisi',
                'laporan pelunasan ekspedisi',
            );
            $akses = User::where('id', $id)->first();
            return view('admin.akses.access', compact('akses', 'menus'));
        } else {
            // tidak memiliki akses
            return back()->with('error', array('Anda tidak memiliki akses'));
        }
    }

    public function access_user(Request $request)
    {
        $menus = array(
            'karyawan',
            'user',
            'akses',
            'departemen',
            'supplier',
            'pelanggan',
            'divisi mobil',
            'jenis kendaraan',
            'golongan',
            'kendaraan',
            'ukuran ban',
            'merek ban',
            'type ban',
            'ban',
            'nokir',
            'stnk',
            'part',
            'sopir',
            'rute perjalanan',
            'biaya tambahan',
            'potongan memo',
            'tarif',
            'satuan barang',
            'barang return',
            'akun',
            'update km',
            'perpanjangan stnk',
            'perpanjangan kir',
            'pemasangan ban',
            'pelepasan ban',
            'pemasangan part',
            'penggantian oli',
            'status perjalanan kendaraan',
            'pembelian ban',
            'pembelian part',
            'memo ekspedisi',
            'faktur ekspedisi',
            'invoice faktur ekspedisi',
            'return barang ekspedisi',
            'faktur pelunasan ekspedisi',
            'penerimaan kas kecil',
            'pengambilan kas kecil',
            'deposit sopir',
            'inquery penerimaan kas kecil',
            'inquery pengambilan kas kecil',
            'inquery deposit sopir',
            'inquery update km',
            'inquery pembelian ban',
            'inquery pembelian part',
            'inquery pemasangan ban',
            'inquery pelepasan ban',
            'inquery pemasangan part',
            'inquery penggantian oli',
            'inquery perpanjangan stnk',
            'inquery perpanjangan kir',
            'inquery memo ekspedisi',
            'inquery faktur ekspedisi',
            'inquery invoice faktur ekspedisi',
            'inquery return ekspedisi',
            'inquery pelunasan ekspedisi',
            'laporan pembelian ban',
            'laporan pembelian part',
            'laporan pemasangan ban',
            'laporan pelepasan ban',
            'laporan pemasangan part',
            'laporan penggantian oli',
            'laporan update km',
            'laporan status perjalanan kendaraan',
            'laporan kas kecil',
            'laporan penerimaan kas kecil',
            'laporan pengambilan kas kecil',
            'laporan mobil logistik',
            'laporan deposit sopir',
            'laporan memo ekspedisi',
            'laporan faktur ekspedisi',
            'laporan pph',
            'laporan invoice ekspedisi',
            'laporan return barang ekspedisi',
            'laporan pelunasan ekspedisi',
        );

        $data = array();
        // Inisialisasi semua nilai menu menjadi false
        foreach ($menus as $menu) {
            $data[$menu] = false;
        }

        // Jika ada data yang dipilih, maka atur nilai menu menjadi true
        if ($request->has('menu') && is_array($request->menu)) {
            foreach ($request->menu as $selectedMenu) {
                if (in_array($selectedMenu, $menus)) {
                    $data[$selectedMenu] = true;
                }
            }
        }

        User::where('id', $request->id)->update([
            'menu' => json_encode($data),
            'tanggal_awal' => Carbon::now('Asia/Jakarta'),
        ]);

        return redirect('admin/akses')->with('success', 'Berhasil mengubah Akses');
    }

    public function accessdetail($id)
    {
        if (auth()->check() && auth()->user()->menu['akses']) {

            $fiturs = array(
                // karyawan
                'karyawan create',
                'karyawan update',
                'karyawan delete',
                'karyawan show',

                // user
                'user create',
                'user delete',

                // hak akses
                'hak akses create',

                // departemen
                'departemen create',
                'departemen update',

                // supplier
                'supplier create',
                'supplier update',
                'supplier delete',
                'supplier show',

                // pelanggan
                'pelanggan create',
                'pelanggan update',
                'pelanggan delete',
                'pelanggan show',

                // divisi
                'divisi create',
                'divisi update',
                'divisi delete',

                // jenis kendaraan   
                'jenis kendaraan create',
                'jenis kendaraan update',
                'jenis kendaraan delete',

                // golongan   
                'golongan create',
                'golongan update',
                'golongan delete',

                // kendaraan
                'kendaraan create',
                'kendaraan update',
                'kendaraan delete',
                'kendaraan show',

                // ukuran ban   
                'ukuran ban create',
                'ukuran ban update',
                'ukuran ban delete',

                // merek   
                'merek create',
                'merek update',
                'merek delete',

                // type   
                'type create',
                'type update',
                'type delete',

                // ban
                'ban create',
                'ban update',
                'ban delete',
                'ban show',

                // nokir
                'nokir print',
                'nokir create',
                'nokir update',
                'nokir delete',
                'nokir show',

                // stnk
                'stnk create',
                'stnk update',
                'stnk delete',
                'stnk show',

                // part
                'part create',
                'part update',
                'part delete',
                'part show',

                // sopir
                // 'sopir create',
                'sopir update',
                // 'sopir delete',
                // 'sopir show',

                // merek   
                'rute create',
                'rute update',
                'rute delete',

                // biaya tambahan   
                'biaya tambahan create',
                'biaya tambahan update',
                'biaya tambahan delete',

                // potongan memo   
                'potongan memo create',
                'potongan memo update',
                'potongan memo delete',

                // tarif   
                'tarif create',
                'tarif update',
                'tarif delete',

                // satuan barang   
                'satuan barang create',
                'satuan barang update',
                'satuan barang delete',

                // barang return   
                'barang return create',
                'barang return update',
                'barang return delete',

                // perpanjangan stnk   
                'perpanjangan stnk show',
                'perpanjangan stnk create',

                // perpanjangan kir   
                'perpanjangan kir show',
                'perpanjangan kir create',

                // penggantian oli 
                'penggantian oli create',

                // inquery penerimaan kas kecil   
                'inquery penerimaan kas kecil posting',
                'inquery penerimaan kas kecil unpost',
                'inquery penerimaan kas kecil update',
                'inquery penerimaan kas kecil delete',
                'inquery penerimaan kas kecil show',

                'inquery pengambilan kas kecil posting',
                'inquery pengambilan kas kecil unpost',
                'inquery pengambilan kas kecil update',
                'inquery pengambilan kas kecil delete',
                'inquery pengambilan kas kecil show',

                // inquery deposit sopir   
                'inquery deposit sopir posting',
                'inquery deposit sopir unpost',
                'inquery deposit sopir update',
                'inquery deposit sopir delete',
                'inquery deposit sopir show',

                // inquery update km   
                'inquery update km posting',
                'inquery update km unpost',
                'inquery update km update',
                'inquery update km delete',
                'inquery update km show',

                // inquery pembelian ban   
                'inquery pembelian ban posting',
                'inquery pembelian ban unpost',
                'inquery pembelian ban update',
                'inquery pembelian ban delete',
                'inquery pembelian ban show',

                // inquery pembelian part   
                'inquery pembelian part posting',
                'inquery pembelian part unpost',
                'inquery pembelian part update',
                'inquery pembelian part delete',
                'inquery pembelian part show',

                // inquery pemasangan ban   
                'inquery pemasangan ban posting',
                'inquery pemasangan ban unpost',
                'inquery pemasangan ban update',
                'inquery pemasangan ban delete',
                'inquery pemasangan ban show',

                // inquery pelepasan ban   
                'inquery pelepasan ban posting',
                'inquery pelepasan ban unpost',
                'inquery pelepasan ban update',
                'inquery pelepasan ban delete',
                'inquery pelepasan ban show',

                // inquery pemasangan part   
                'inquery pemasangan part posting',
                'inquery pemasangan part unpost',
                'inquery pemasangan part update',
                'inquery pemasangan part delete',
                'inquery pemasangan part show',

                // inquery penggantian oli   
                'inquery penggantian oli posting',
                'inquery penggantian oli unpost',
                'inquery penggantian oli update',
                'inquery penggantian oli delete',
                'inquery penggantian oli show',

                // inquery perpanjangan stnk   
                'inquery perpanjangan stnk posting',
                'inquery perpanjangan stnk unpost',
                'inquery perpanjangan stnk update',
                'inquery perpanjangan stnk delete',
                'inquery perpanjangan stnk show',

                // inquery perpanjangan kir   
                'inquery perpanjangan kir posting',
                'inquery perpanjangan kir unpost',
                'inquery perpanjangan kir update',
                'inquery perpanjangan kir delete',
                'inquery perpanjangan kir show',

                // inquery memo perjalanan   
                'inquery memo perjalanan posting',
                'inquery memo perjalanan unpost',
                'inquery memo perjalanan update',
                'inquery memo perjalanan delete',
                'inquery memo perjalanan show',

                // inquery memo borong   
                'inquery memo borong posting',
                'inquery memo borong unpost',
                'inquery memo borong update',
                'inquery memo borong delete',
                'inquery memo borong show',

                // inquery memo tambahan   
                'inquery memo tambahan posting',
                'inquery memo tambahan unpost',
                'inquery memo tambahan update',
                'inquery memo tambahan delete',
                'inquery memo tambahan show',

                // inquery faktur ekspedisi   
                'inquery faktur ekspedisi posting',
                'inquery faktur ekspedisi unpost',
                'inquery faktur ekspedisi update',
                'inquery faktur ekspedisi delete',
                'inquery faktur ekspedisi show',

                // inquery invoice ekspedisi   
                'inquery invoice ekspedisi posting',
                'inquery invoice ekspedisi unpost',
                'inquery invoice ekspedisi update',
                'inquery invoice ekspedisi delete',
                'inquery invoice ekspedisi show',

                // inquery return penerimaan barang    
                'inquery return penerimaan barang posting',
                'inquery return penerimaan barang unpost',
                'inquery return penerimaan barang update',
                'inquery return penerimaan barang delete',
                'inquery return penerimaan barang show',

                // inquery return nota barang    
                'inquery return nota barang posting',
                'inquery return nota barang unpost',
                'inquery return nota barang update',
                'inquery return nota barang delete',
                'inquery return nota barang show',

                // inquery return penjualan barang    
                'inquery return penjualan barang posting',
                'inquery return penjualan barang unpost',
                'inquery return penjualan barang update',
                'inquery return penjualan barang delete',
                'inquery return penjualan barang show',

                // inquery pelunasan ekspedisi    
                'inquery pelunasan ekspedisi posting',
                'inquery pelunasan ekspedisi unpost',
                'inquery pelunasan ekspedisi update',
                'inquery pelunasan ekspedisi delete',
                'inquery pelunasan ekspedisi show',

                // laporan pembelian ban
                'laporan pembelian ban cari',
                'laporan pembelian ban cetak',

                // laporan pembelian part
                'laporan pembelian part cari',
                'laporan pembelian part cetak',

                // laporan pemasangan ban
                'laporan pemasangan ban cari',
                'laporan pemasangan ban cetak',

                // laporan pelepasan ban
                'laporan pelepasan ban cari',
                'laporan pelepasan ban cetak',

                // laporan pemasangan part
                'laporan pemasangan part cari',
                'laporan pemasangan part cetak',

                // laporan penggantian oli
                'laporan penggantian oli cari',
                'laporan penggantian oli cetak',

                // laporan update km
                'laporan update km cari',
                'laporan update km cetak',

                // laporan status perjalanan
                'laporan status perjalanan cari',
                'laporan status perjalanan cetak',

                // laporan penerimaan kas kecil 
                'laporan penerimaan kas kecil cari',
                'laporan penerimaan kas kecil cetak',

                // laporan pengambilan kas kecil 
                'laporan pengambilan kas kecil cari',
                'laporan pengambilan kas kecil cetak',

                // laporan deposit sopir 
                'laporan deposit sopir cari',
                'laporan deposit sopir cetak',

                // laporan memo perjalanan 
                'laporan memo perjalanan cari',
                'laporan memo perjalanan cetak',

                // laporan memo borong 
                'laporan memo borong cari',
                'laporan memo borong cetak',

                // laporan memo tambahan 
                'laporan memo tambahan cari',
                'laporan memo tambahan cetak',

                // laporan faktur ekspedisi 
                'laporan faktur ekspedisi cari',
                'laporan faktur ekspedisi cetak',

                // laporan pph 
                'laporan pph cari',
                'laporan pph cetak',

                // laporan invoice ekspedisi 
                'laporan invoice ekspedisi cari',
                'laporan invoice ekspedisi cetak',

                // laporan penerimaan return  ekspedisi 
                'laporan penerimaan return cari',
                'laporan penerimaan return cetak',

                // laporan nota return 
                'laporan nota return cari',
                'laporan nota return cetak',

                // laporan penjualan 
                'laporan penjualan return cari',
                'laporan penjualan return cetak',

                // laporan pelunasan ekspedisi 
                'laporan pelunasan ekspedisi cari',
                'laporan pelunasan ekspedisi cetak',

            );
            $akses = User::where('id', $id)->first();
            return view('admin.akses.accessdetail', compact('akses', 'fiturs'));
        } else {
            // tidak memiliki akses
            return back()->with('error', array('Anda tidak memiliki akses'));
        }
    }


    public function access_userdetail(Request $request)
    {
        $fiturs = array(

            // karyawan
            'karyawan create',
            'karyawan update',
            'karyawan delete',
            'karyawan show',

            // user
            'user create',
            'user delete',

            // hak akses
            'hak akses create',

            // departemen
            'departemen create',
            'departemen update',

            // supplier
            'supplier create',
            'supplier update',
            'supplier delete',
            'supplier show',

            // pelanggan
            'pelanggan create',
            'pelanggan update',
            'pelanggan delete',
            'pelanggan show',

            // divisi
            'divisi create',
            'divisi update',
            'divisi delete',

            // jenis kendaraan   
            'jenis kendaraan create',
            'jenis kendaraan update',
            'jenis kendaraan delete',

            // golongan   
            'golongan create',
            'golongan update',
            'golongan delete',

            // kendaraan
            'kendaraan create',
            'kendaraan update',
            'kendaraan delete',
            'kendaraan show',

            // ukuran ban   
            'ukuran ban create',
            'ukuran ban update',
            'ukuran ban delete',

            // merek   
            'merek create',
            'merek update',
            'merek delete',

            // type   
            'type create',
            'type update',
            'type delete',

            // ban
            'ban create',
            'ban update',
            'ban delete',
            'ban show',

            // nokir
            'nokir print',
            'nokir create',
            'nokir update',
            'nokir delete',
            'nokir show',

            // stnk
            'stnk create',
            'stnk update',
            'stnk delete',
            'stnk show',

            // part
            'part create',
            'part update',
            'part delete',
            'part show',

            // sopir
            // 'sopir create',
            'sopir update',
            // 'sopir delete',
            // 'sopir show',

            // merek   
            'rute create',
            'rute update',
            'rute delete',

            // biaya tambahan   
            'biaya tambahan create',
            'biaya tambahan update',
            'biaya tambahan delete',

            // potongan memo   
            'potongan memo create',
            'potongan memo update',
            'potongan memo delete',

            // tarif   
            'tarif create',
            'tarif update',
            'tarif delete',

            // satuan barang   
            'satuan barang create',
            'satuan barang update',
            'satuan barang delete',

            // barang return   
            'barang return create',
            'barang return update',
            'barang return delete',

            // perpanjangan stnk   
            'perpanjangan stnk show',
            'perpanjangan stnk create',

            // perpanjangan kir   
            'perpanjangan kir show',
            'perpanjangan kir create',

            // penggantian oli 
            'penggantian oli create',

            // inquery penerimaan kas kecil   
            'inquery penerimaan kas kecil posting',
            'inquery penerimaan kas kecil unpost',
            'inquery penerimaan kas kecil update',
            'inquery penerimaan kas kecil delete',
            'inquery penerimaan kas kecil show',

            // inquery pengambilan kas kecil   
            'inquery pengambilan kas kecil posting',
            'inquery pengambilan kas kecil unpost',
            'inquery pengambilan kas kecil update',
            'inquery pengambilan kas kecil delete',
            'inquery pengambilan kas kecil show',

            // inquery deposit sopir   
            'inquery deposit sopir posting',
            'inquery deposit sopir unpost',
            'inquery deposit sopir update',
            'inquery deposit sopir delete',
            'inquery deposit sopir show',

            // inquery update km   
            'inquery update km posting',
            'inquery update km unpost',
            'inquery update km update',
            'inquery update km delete',
            'inquery update km show',

            // inquery pembelian ban   
            'inquery pembelian ban posting',
            'inquery pembelian ban unpost',
            'inquery pembelian ban update',
            'inquery pembelian ban delete',
            'inquery pembelian ban show',

            // inquery pembelian part   
            'inquery pembelian part posting',
            'inquery pembelian part unpost',
            'inquery pembelian part update',
            'inquery pembelian part delete',
            'inquery pembelian part show',

            // inquery pemasangan ban   
            'inquery pemasangan ban posting',
            'inquery pemasangan ban unpost',
            'inquery pemasangan ban update',
            'inquery pemasangan ban delete',
            'inquery pemasangan ban show',

            // inquery pelepasan ban   
            'inquery pelepasan ban posting',
            'inquery pelepasan ban unpost',
            'inquery pelepasan ban update',
            'inquery pelepasan ban delete',
            'inquery pelepasan ban show',

            // inquery pemasangan part   
            'inquery pemasangan part posting',
            'inquery pemasangan part unpost',
            'inquery pemasangan part update',
            'inquery pemasangan part delete',
            'inquery pemasangan part show',

            // inquery penggantian oli   
            'inquery penggantian oli posting',
            'inquery penggantian oli unpost',
            'inquery penggantian oli update',
            'inquery penggantian oli delete',
            'inquery penggantian oli show',

            // inquery perpanjangan stnk   
            'inquery perpanjangan stnk posting',
            'inquery perpanjangan stnk unpost',
            'inquery perpanjangan stnk update',
            'inquery perpanjangan stnk delete',
            'inquery perpanjangan stnk show',

            // inquery perpanjangan kir   
            'inquery perpanjangan kir posting',
            'inquery perpanjangan kir unpost',
            'inquery perpanjangan kir update',
            'inquery perpanjangan kir delete',
            'inquery perpanjangan kir show',

            // inquery memo perjalanan   
            'inquery memo perjalanan posting',
            'inquery memo perjalanan unpost',
            'inquery memo perjalanan update',
            'inquery memo perjalanan delete',
            'inquery memo perjalanan show',

            // inquery memo borong   
            'inquery memo borong posting',
            'inquery memo borong unpost',
            'inquery memo borong update',
            'inquery memo borong delete',
            'inquery memo borong show',

            // inquery memo tambahan   
            'inquery memo tambahan posting',
            'inquery memo tambahan unpost',
            'inquery memo tambahan update',
            'inquery memo tambahan delete',
            'inquery memo tambahan show',

            // inquery faktur ekspedisi   
            'inquery faktur ekspedisi posting',
            'inquery faktur ekspedisi unpost',
            'inquery faktur ekspedisi update',
            'inquery faktur ekspedisi delete',
            'inquery faktur ekspedisi show',

            // inquery invoice ekspedisi   
            'inquery invoice ekspedisi posting',
            'inquery invoice ekspedisi unpost',
            'inquery invoice ekspedisi update',
            'inquery invoice ekspedisi delete',
            'inquery invoice ekspedisi show',

            // inquery return penerimaan barang    
            'inquery return penerimaan barang posting',
            'inquery return penerimaan barang unpost',
            'inquery return penerimaan barang update',
            'inquery return penerimaan barang delete',
            'inquery return penerimaan barang show',

            // inquery return nota barang    
            'inquery return nota barang posting',
            'inquery return nota barang unpost',
            'inquery return nota barang update',
            'inquery return nota barang delete',
            'inquery return nota barang show',

            // inquery return penjualan barang    
            'inquery return penjualan barang posting',
            'inquery return penjualan barang unpost',
            'inquery return penjualan barang update',
            'inquery return penjualan barang delete',
            'inquery return penjualan barang show',

            // inquery pelunasan ekspedisi    
            'inquery pelunasan ekspedisi posting',
            'inquery pelunasan ekspedisi unpost',
            'inquery pelunasan ekspedisi update',
            'inquery pelunasan ekspedisi delete',
            'inquery pelunasan ekspedisi show',

            // laporan pembelian ban
            'laporan pembelian ban cari',
            'laporan pembelian ban cetak',

            // laporan pembelian part
            'laporan pembelian part cari',
            'laporan pembelian part cetak',

            // laporan pemasangan ban
            'laporan pemasangan ban cari',
            'laporan pemasangan ban cetak',

            // laporan pelepasan ban
            'laporan pelepasan ban cari',
            'laporan pelepasan ban cetak',

            // laporan pemasangan part
            'laporan pemasangan part cari',
            'laporan pemasangan part cetak',

            // laporan penggantian oli
            'laporan penggantian oli cari',
            'laporan penggantian oli cetak',

            // laporan update km
            'laporan update km cari',
            'laporan update km cetak',

            // laporan status perjalanan
            'laporan status perjalanan cari',
            'laporan status perjalanan cetak',

            // laporan penerimaan kas kecil 
            'laporan penerimaan kas kecil cari',
            'laporan penerimaan kas kecil cetak',

            // laporan pengambilan kas kecil 
            'laporan pengambilan kas kecil cari',
            'laporan pengambilan kas kecil cetak',

            // laporan deposit sopir 
            'laporan deposit sopir cari',
            'laporan deposit sopir cetak',

            // laporan memo perjalanan 
            'laporan memo perjalanan cari',
            'laporan memo perjalanan cetak',

            // laporan memo borong 
            'laporan memo borong cari',
            'laporan memo borong cetak',

            // laporan memo tambahan 
            'laporan memo tambahan cari',
            'laporan memo tambahan cetak',

            // laporan faktur ekspedisi 
            'laporan faktur ekspedisi cari',
            'laporan faktur ekspedisi cetak',

            // laporan pph 
            'laporan pph cari',
            'laporan pph cetak',

            // laporan invoice ekspedisi 
            'laporan invoice ekspedisi cari',
            'laporan invoice ekspedisi cetak',

            // laporan penerimaan return  ekspedisi 
            'laporan penerimaan return cari',
            'laporan penerimaan return cetak',

            // laporan nota return 
            'laporan nota return cari',
            'laporan nota return cetak',

            // laporan penjualan 
            'laporan penjualan return cari',
            'laporan penjualan return cetak',

            // laporan pelunasan ekspedisi 
            'laporan pelunasan ekspedisi cari',
            'laporan pelunasan ekspedisi cetak',

        );

        $data = array();
        // Inisialisasi semua nilai menu menjadi false
        foreach ($fiturs as $fitur) {
            $data[$fitur] = false;
        }

        // Jika ada data yang dipilih, maka atur nilai menu menjadi true
        if ($request->has('fitur') && is_array($request->fitur)) {
            foreach ($request->fitur as $selectedMenu) {
                if (in_array($selectedMenu, $fiturs)) {
                    $data[$selectedMenu] = true;
                }
            }
        }

        User::where('id', $request->id)->update([
            'fitur' => json_encode($data),
        ]);

        return redirect('admin/akses')->with('success', 'Berhasil mengubah Akses Menu');
    }

    public function destroy($id)
    {

        $user = User::find($id);
        Karyawan::where('id', $user->karyawan_id)->update([
            'status' => 'null'
        ]);
        $user->delete();

        return redirect('admin/user')->with('success', 'Berhasil menghapus user');
    }
}
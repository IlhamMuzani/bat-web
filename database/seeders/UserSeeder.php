<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {   
                $karyawans = [
            [
                'karyawan_id' => '1',
                'kode_user' => 'AB000001',
                'qrcode_user' => '6714059572',
                'menu' => json_encode(['akses' => true,
                    'karyawan' => true,
                    'user' => true,
                    'departemen' => true,
                    'supplier' => true,
                    'pelanggan' => true,
                    'kendaraan' => true,
                    'ban' => true,
                    'golongan' => true,
                    'divisi mobil' => true,
                    'jenis kendaraan' => true,
                    'ukuran ban' => true,
                    'merek ban' => true,
                    'type ban' => true,
                    'nokir' => true,
                    'stnk' => true,
                    'part' => true,
                    //opersional //
                    'update km' => true,
                    'perpanjangan stnk' => true,
                    'perpanjangan kir' => true,
                    'pemasangan ban' => true,
                    'pelepasan ban' => true,
                    'pemasangan part' => true,
                    'penggantian oli' => true,
                    'status perjalanan kendaraan' => true,
                    //transaksi//
                    'pembelian ban' => true,
                    'pembelian part' => true,
                    'inquery pembelian ban' => true,
                    'inquery pembelian part' => true,
                    'inquery pemasangan ban' => true,
                    'inquery pelepasan ban' => true,
                    'inquery pemasangan part' => true,
                    'inquery penggantian oli' => true,
                    'inquery update km' => true,
                    //laporan//
                    'laporan pembelian ban' => true,
                    'laporan pembelian part' => true,
                    'laporan pemasangan ban' => true,
                    'laporan pelepasan ban' => true,
                    'laporan pemasangan part' => true,
                    'laporan penggantian oli' => true,
                    'laporan update km' => true,
                    'laporan status perjalanan kendaraan' => true,
                ]),
                'password' => bcrypt('admin'),
                'cek_hapus' => 'tidak',
                'level' => 'admin',
            ],
        ];
        User::insert($karyawans);
    }
}
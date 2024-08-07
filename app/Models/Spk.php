<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Spk extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable =
    [
        'admin',
        'kategori',
        'kode_spk',
        'qrcode_spk',
        'user_id',
        'pelanggan_id',
        'kode_pelanggan',
        'nama_pelanggan',
        'alamat_pelanggan',
        'telp_pelanggan',
        'rute_perjalanan_id',
        'kendaraan_id',
        'no_kabin',
        'no_pol',
        'golongan',
        'km_awal',
        'kode_driver',
        'nama_driver',
        'telp',
        'kode_rute',
        'nama_rute',
        'saldo_deposit',
        'uang_jalan',
        'voucher',
        'status',
        'status_indikator',
        'status_spk',
        'tanggal',
        'tanggal_awal',



    ];


    use SoftDeletes;
    protected $dates = ['deleted_at'];



    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable('*');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class);
    }

    public function memo_ekspedisi()
    {
        return $this->hasMany(Memo_ekspedisi::class);
    }

    public function rute_perjalanan()
    {
        return $this->belongsTo(Rute_perjalanan::class);
    }
}
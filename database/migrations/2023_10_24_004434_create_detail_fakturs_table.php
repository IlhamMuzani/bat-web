<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_fakturs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('faktur_ekspedisi_id')->nullable();
            $table->foreign('faktur_ekspedisi_id')->references('id')->on('faktur_ekspedisis');
            $table->unsignedBigInteger('memo_ekspedisi_id')->nullable();
            $table->foreign('memo_ekspedisi_id')->references('id')->on('memo_ekspedisis');
            $table->unsignedBigInteger('memotambahan_id')->nullable();
            $table->foreign('memotambahan_id')->references('id')->on('memotambahans');
            $table->unsignedBigInteger('kendaraan_id')->nullable();
            $table->foreign('kendaraan_id')->references('id')->on('kendaraans');
            $table->string('kode_memo')->nullable();
            $table->string('nama_biaya')->nullable();
            $table->string('kode_driver')->nullable();
            $table->string('nama_driver')->nullable();
            $table->string('telp_driver')->nullable();
            $table->string('no_kabin')->nullable();
            $table->string('no_pol')->nullable();
            $table->string('nama_rute')->nullable();
            $table->string('tanggal_memo')->nullable();
            $table->string('kategori_memo')->nullable();
            $table->string('kode_memotambahan')->nullable();
            $table->string('tanggal_memotambahan')->nullable();
            $table->string('nama_drivertambahan')->nullable();
            $table->string('nama_rutetambahan')->nullable();
            $table->string('kode_memotambahans')->nullable();
            $table->string('tanggal_memotambahans')->nullable();
            $table->string('nama_drivertambahans')->nullable();
            $table->string('nama_rutetambahans')->nullable();
            $table->string('tanggal_awal')->nullable();
            $table->string('tanggal_akhir')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detail_fakturs');
    }
};
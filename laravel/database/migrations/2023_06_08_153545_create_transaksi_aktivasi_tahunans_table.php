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
        Schema::create('transaksi_aktivasi_tahunans', function (Blueprint $table) {
            $table->string('no_struk_aktivasi_tahunan')->primary();
            $table->string('id_member')->nullable();
            $table->foreign('id_member')->references('id_member')->on('members')->onDelete('cascade');
            $table->string('id_pegawai')->nullable();
            $table->foreign('id_pegawai')->references('id_pegawai')->on('pegawais')->onDelete('cascade');
            $table->date('tanggal_aktivasi');
            $table->date('masa_berlaku_member');
            $table->integer('total_bayar');
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
        Schema::dropIfExists('transaksi_aktivasi_tahunans');
    }
};

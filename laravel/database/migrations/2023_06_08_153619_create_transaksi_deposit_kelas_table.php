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
        Schema::create('transaksi_deposit_kelas', function (Blueprint $table) {
            $table->string('no_struk_deposit_kelas')->primary();
            $table->unsignedBigInteger('id_kelas');
            $table->foreign('id_kelas')->references('id_kelas')->on('kelas')->onDelete('cascade');
            $table->unsignedBigInteger('id_promo')->nullable();
            $table->foreign('id_promo')->references('id_promo')->on('promos')->onDelete('cascade');
            $table->string('id_pegawai')->nullable();
            $table->foreign('id_pegawai')->references('id_pegawai')->on('pegawais')->onDelete('cascade');
            $table->string('id_member')->nullable();
            $table->foreign('id_member')->references('id_member')->on('members')->onDelete('cascade');
            $table->date('tanggal_transaksi_deposit_kelas');
            $table->integer('deposit_kelas');
            $table->integer('bonus_deposit_kelas');
            $table->integer('jumlah_deposit_paket');
            $table->date('tanggal_berakhir_paket');
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
        Schema::dropIfExists('transaksi_deposit_kelas');
    }
};

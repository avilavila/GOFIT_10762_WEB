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
        Schema::create('transaksi_deposit_uangs', function (Blueprint $table) {
            $table->string('no_struk_deposit_uang')->primary();
            $table->unsignedBigInteger('id_promo')->nullable();
            $table->foreign('id_promo')->references('id_promo')->on('promos')->onDelete('cascade');
            $table->string('id_pegawai')->nullable();
            $table->foreign('id_pegawai')->references('id_pegawai')->on('pegawais')->onDelete('cascade');
            $table->string('id_member')->nullable();
            $table->foreign('id_member')->references('id_member')->on('members')->onDelete('cascade');
            $table->date('tanggal_transaksi_deposit_uang');
            $table->integer('deposit_uang');
            $table->integer('bonus_deposit');
            $table->integer('total_deposit_uang');
            $table->integer('sisa_deposit');
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
        Schema::dropIfExists('transaksi_deposit_uangs');
    }
};

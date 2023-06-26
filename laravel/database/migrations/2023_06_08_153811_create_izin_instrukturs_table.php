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
        Schema::create('izin_instrukturs', function (Blueprint $table) {
            $table->id('id_izin_instruktur');
            $table->unsignedBigInteger('id_instruktur');
            $table->foreign('id_instruktur')->references('id_instruktur')->on('instrukturs')->onDelete('cascade');
            $table->unsignedBigInteger('id_instruktur_pengganti')->nullable();
            $table->string('keterangan_izin');
            $table->date('tanggal_izin');
            $table->string('sesi_izin');
            $table->date('tanggal_buat');
            $table->string('status_izin')->nullable();
            $table->date('tanggal_konfirmasi')->nullable();
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
        Schema::dropIfExists('izin_instrukturs');
    }
};

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
        Schema::create('jadwal_harians', function (Blueprint $table) {
            $table->id('id_jadwal_harian');
            $table->date('tanggal_jadwal_harian');
            $table->unsignedBigInteger('id_jadwal_umum');
            $table->foreign('id_jadwal_umum')->references('id_jadwal_umum')->on('jadwal_umums')->onDelete('cascade');
            $table->unsignedBigInteger('id_instruktur');
            $table->foreign('id_instruktur')->references('id_instruktur')->on('instrukturs')->onDelete('cascade');
            $table->string('keterangan_jadwal_harian')->nullable();
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
        Schema::dropIfExists('jadwal_harians');
    }
};

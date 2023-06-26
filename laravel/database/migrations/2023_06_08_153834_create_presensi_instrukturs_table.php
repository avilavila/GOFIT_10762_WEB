<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePresensiInstruktursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('presensi_instrukturs', function (Blueprint $table) {
            $table->id('id_presensi_instruktur');
            $table->unsignedBigInteger('id_instruktur');
            $table->foreign('id_instruktur')->references('id_instruktur')->on('instrukturs')->onDelete('cascade');
            $table->unsignedBigInteger('id_jadwal_harian');
            $table->foreign('id_jadwal_harian')->references('id_jadwal_harian')->on('jadwal_harians')->onDelete('cascade');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->integer('keterlambatan');
            $table->integer('durasi_kelas');
            $table->string('status');
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
        Schema::dropIfExists('presensi_instrukturs');
    }
}

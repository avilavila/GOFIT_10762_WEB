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
        Schema::create('booking_presensi_kelas', function (Blueprint $table) {
            $table->string('no_struk_presensi_kelas')->primary();
            $table->string('id_member')->nullable();
            $table->foreign('id_member')->references('id_member')->on('members')->onDelete('cascade');
            $table->unsignedBigInteger('id_jadwal_harian')->nullable();
            $table->foreign('id_jadwal_harian')->references('id_jadwal_harian')->on('jadwal_harians')->onDelete('cascade');
            $table->string('jenis_booking_kelas');
            $table->time('jam_presensi_kelas')->nullable();
            $table->string('status_presensi_kelas')->nullable();
            $table->date('tanggal_pembuatan_booking_kelas');
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
        Schema::dropIfExists('booking_presensi_kelas');
    }
};

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
        Schema::create('booking_presensi_gyms', function (Blueprint $table) {
            $table->string('no_struk_presensi_gym')->primary();
            $table->string('id_member')->nullable();
            $table->foreign('id_member')->references('id_member')->on('members')->onDelete('cascade');
            $table->date('tanggal_booking_gym')->nullable();
            $table->string('slot_booking')->nullable();
            $table->string('status_presensi_gym')->nullable();
            $table->time('jam_presensi_gym')->nullable();
            $table->date('tanggal_pembuatan_booking_gym')->nullable();
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
        Schema::dropIfExists('booking_presensi_gyms');
    }
};

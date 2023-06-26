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
        Schema::create('members', function (Blueprint $table) {
            $table->string('id_member')->primary();
            $table->string('nama_member');
            $table->string('alamat_member');
            $table->date('tanggal_lahir_member');
            $table->string('telepon_member');
            $table->string('email_member');
            $table->string('username_member');
            $table->string('password_member');
            $table->date('tanggal_kadaluarsa_member');
            $table->integer('saldo_deposit');
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
        Schema::dropIfExists('members');
    }
};

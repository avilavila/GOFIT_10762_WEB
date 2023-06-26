<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pegawai extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_pegawai';

    public $incrementing = false;

    protected $fillable = [
        'id_pegawai',
        'nama_pegawai',
        'jabatan',
        'alamat_pegawai',
        'telepon_pegawai',
        'email_pegawai',
        'password_pegawai'
    ]; 
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class transaksi_aktivasi_tahunan extends Model
{
    use HasFactory;

    protected $primaryKey = 'no_struk_aktivasi_tahunan';

    public $incrementing = false;

    protected $fillable = [
        'no_struk_aktivasi_tahunan',
        'id_member',
        'id_pegawai',
        'tanggal_aktivasi',
        'masa_berlaku_member',
        'total_bayar'
    ]; 
}

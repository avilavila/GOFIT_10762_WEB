<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class transaksi_deposit_kelas extends Model
{
    use HasFactory;

    protected $primaryKey = 'no_struk_deposit_kelas';

    public $incrementing = false;

    protected $fillable = [
        'no_struk_deposit_kelas',
        'id_kelas',
        'id_promo',
        'id_pegawai',
        'id_member',
        'tanggal_transaksi_deposit_kelas',
        'deposit_kelas',
        'bonus_deposit_kelas',
        'jumlah_deposit_paket',
        'tanggal_berakhir_paket',
        'total_bayar'
    ]; 
}

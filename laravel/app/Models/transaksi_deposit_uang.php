<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class transaksi_deposit_uang extends Model
{
    use HasFactory;

    protected $primaryKey = 'no_struk_deposit_uang';

    public $incrementing = false;

    protected $fillable = [
        'no_struk_deposit_uang',
        'id_promo',
        'id_pegawai',
        'id_member',
        'tanggal_transaksi_deposit_uang',
        'deposit_uang',
        'bonus_deposit',
        'sisa_deposit',
        'total_deposit_uang'
    ]; 
}

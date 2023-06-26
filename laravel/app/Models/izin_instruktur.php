<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class izin_instruktur extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_izin_instruktur';

    protected $fillable = [
        'id_izin_instruktur',
        'id_instruktur',
        'id_instruktur_pengganti',
        'keterangan_izin',
        'tanggal_izin',
        'sesi_izin',
        'tanggal_buat',
        'status_izin',
        'tanggal_konfirmasi'
    ]; 
}

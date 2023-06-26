<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class presensi_instruktur extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_presensi_instruktur';

    protected $table = 'presensi_instrukturs';


    protected $fillable = [
        'id_instruktur',
        'id_jadwal_harian',
        'jam_mulai',
        'jam_selesai',
        'keterlambatan',
        'durasi_kelas',
        'status'
    ]; 
}

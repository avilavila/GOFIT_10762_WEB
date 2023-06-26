<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class booking_presensi_kelas extends Model
{
    use HasFactory;

    protected $primaryKey = 'no_struk_presensi_kelas';

    public $incrementing = false;

    protected $fillable = [
        'no_struk_presensi_kelas',
        'id_member',
        'tanggal_jadwal_harian',
        'jenis_booking_kelas',
        'jam_presensi_kelas',
        'status_presensi_kelas',
        'tanggal_pembuatan_booking_kelas'
    ]; 
}

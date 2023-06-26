<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class booking_presensi_gym extends Model
{
    use HasFactory;

    protected $primaryKey = 'no_struk_presensi_gym';

    public $incrementing = false;

    protected $fillable = [
        'no_struk_presensi_gym',
        'id_member',
        'tanggal_booking_gym',
        'slot_booking',
        'status_presensi_gym',
        'jam_presensi_gym',
        'tanggal_pembuatan_booking_gym'
    ]; 
}

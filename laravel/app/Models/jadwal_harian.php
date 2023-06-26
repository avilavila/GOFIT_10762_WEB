<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class jadwal_harian extends Model
{
    use HasFactory;

    protected $table = 'jadwal_harians';

    protected $primaryKey = 'id_jadwal_harian';

    protected $fillable = [
        'tanggal_jadwal_harian',
        'id_jadwal_umum',
        'id_instruktur',
        'keterangan_jadwal_harian'
    ]; 
}

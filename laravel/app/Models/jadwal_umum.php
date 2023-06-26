<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class jadwal_umum extends Model
{
    use HasFactory;

    protected $table = 'jadwal_umums';

    protected $primaryKey = 'id_jadwal_umum';

    protected $fillable = [
        'hari_jadwal_umum',
        'id_instruktur',
        'id_kelas',
        'waktu_jadwal_umum'
    ]; 
}

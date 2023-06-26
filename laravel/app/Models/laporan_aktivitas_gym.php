<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class laporan_aktivitas_gym extends Model
{
    use HasFactory;

    protected $table = 'laporan_aktivitas_gyms';

    protected $fillable = [
        'tanggal',
        'jumlah_member'
    ]; 
}

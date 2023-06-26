<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class laporan_pendapatan_bulanan extends Model
{
    use HasFactory;

    protected $fillable = [
        'bulan',
        'aktivasi',
        'deposit',
        'total'
    ]; 
}

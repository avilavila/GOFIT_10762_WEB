<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class deposit_kelas_member extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_deposit_member';

    protected $fillable = [
        'id_member',
        'id_kelas',
        'deposit_paket_kelas',
        'tanggal_kadaluarsa_kelas'
    ]; 
}

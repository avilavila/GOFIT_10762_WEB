<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class instruktur extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_instruktur';

    protected $fillable = [
        'nama_instruktur',
        'alamat_instruktur',
        'tanggal_lahir_instruktur',
        'telepon_instruktur',
        'email_instruktur',
        'username_instruktur',
        'password_instruktur'
    ]; 
}

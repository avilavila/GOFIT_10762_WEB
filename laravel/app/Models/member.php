<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class member extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_member';

    public $incrementing = false;

    protected $fillable = [
        'id_member',
        'nama_member',
        'alamat_member',
        'tanggal_lahir_member',
        'telepon_member',
        'email_member',
        'username_member',
        'password_member',
        'tanggal_kadaluarsa_member',
        'saldo_deposit'
    ]; 
}

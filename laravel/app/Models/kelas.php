<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class kelas extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_kelas',
        'nama_kelas',
        'harga_kelas'
    ]; 
}

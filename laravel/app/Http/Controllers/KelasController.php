<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\kelas;
use App\Http\Resources\KelasResource;
use Illuminate\Support\Facades\Validator;

class KelasController extends Controller
{
    public function index()
    {
        $kelas = Kelas::latest()->get();

        return new KelasResource(true, 'List Data Kelas', $kelas);
    }

    public function show($id)
    {
        $kelas = Kelas::find($id);

        return new KelasResource(true, 'Data Kelas ditemukan!', $kelas);
    }
}

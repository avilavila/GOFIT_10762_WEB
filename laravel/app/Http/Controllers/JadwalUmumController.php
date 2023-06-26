<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\jadwal_umum; /* Import Model */
use App\Models\kelas;
use App\Models\instruktur;
use App\Http\Resources\JadwalUmumResource;
use Illuminate\Support\Facades\Validator;

class JadwalUmumController extends Controller
{
    /**
    * index
    *
    * @return void
    */
    public function index()
    {
        $jadwal_umum = jadwal_umum::latest()->get();

        return new JadwalUmumResource(true, 'List Data Jadwal Umum', $jadwal_umum);
    }

    /**
    * create
    *
    * @return void
    */
    public function create()
    {
        $jadwal_umum = jadwal_umum::all();
        $kelas = Kelas::all();
        $instruktur = Instruktur::all();
        return view('jadwal_umum.create', compact('kelas', 'instruktur', 'jadwal_umum'));
    }

    public function show($id)
    {
        $jadwal_umum = jadwal_umum::find($id);

        return new JadwalUmumResource(true, 'Data Jadwal Umum ditemukan!', $jadwal_umum);
    }
    /**
    * store
    *
    * @param Request $request
    * @return void
    */
    public function store(Request $request)
    {
        //Validasi Formulir
        $validator = Validator::make($request->all(), [
            'hari_jadwal_umum' => 'required',
            'id_kelas' => 'required',
            'id_instruktur' => 'unique:jadwal_umums,id_instruktur,NULL,id_jadwal_umum,id_instruktur,' . 
                                $request->id_instruktur. ',hari_jadwal_umum,' . $request->hari_jadwal_umum . ',waktu_jadwal_umum,' .$request->waktu_jadwal_umum,
            'waktu_jadwal_umum' => 'required'
        ],
        [   'id_instruktur.required' => 'Tidak Boleh Kosong!',
            'id_instruktur.unique' => 'Jadwal Instruktur Bertabrakan!']);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //Fungsi Simpan Data ke dalam Database
        $jadwal_umum = jadwal_umum::create([
            'hari_jadwal_umum' => $request->hari_jadwal_umum,
            'id_kelas' => $request->id_kelas,
            'id_instruktur' => $request->id_instruktur,
            'waktu_jadwal_umum' => $request->waktu_jadwal_umum
        ]);

        return new JadwalUmumResource(true, 'Jadwal Umum Berhasil Ditambahkan', $jadwal_umum);
    }

     /**
     * destroy
     *
     * @param  mixed $id
     * @return void
     */
    public function destroy($id)
    {
        $jadwal_umum = jadwal_umum::where('id_jadwal_umum', $id)->delete();

        return new JadwalUmumResource(true, 'Jadwal Umum
        Berhasil Dihapus!', $jadwal_umum);
    }

    public function edit($id)
    {
        $jadwal_umum = jadwal_umum::findOrFail($id);
        $kelas = Kelas::all();
        $instrukturs = Instruktur::all();
        return view('jadwal_umum.edit', compact('jadwal_umum', 'kelas', 'instruktur'));
    }

     /** update
     *
     * @param  mixed $request
     * @param  mixed $post
     * @return void
     */
    public function update(Request $request, $id)
    {
         //Validasi Formulir
         $validator = Validator::make($request->all(), [
            'hari_jadwal_umum' => 'required',
            'id_kelas' => 'required',
            'id_instruktur' => 'unique:jadwal_umums,id_instruktur,NULL,id_jadwal_umum,id_instruktur,' . 
                                $request->id_instruktur. ',hari_jadwal_umum,' . $request->hari_jadwal_umum . ',waktu_jadwal_umum,' .$request->waktu_jadwal_umum,
            'waktu_jadwal_umum' => 'required'
        ],
        [   'id_instruktur.required' => 'Wajib pilih instruktur!',
            'id_instruktur.unique' => 'Jadwal Waktu Instruktur Bertabrakan!']);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //Fungsi Simpan Data ke dalam Database
        $jadwal_umum = jadwal_umum::where('id_jadwal_umum', $id)->update([
            'hari_jadwal_umum' => $request->hari_jadwal_umum,
            'id_kelas' => $request->id_kelas,
            'id_instruktur' => $request->id_instruktur,
            'waktu_jadwal_umum' => $request->waktu_jadwal_umum
        ]);
        
        return new JadwalUmumResource(true, 'Jadwal Umum Berhasil Diubah!', $jadwal_umum);
    }

    public function messages()
    {
        return [
            'id_instruktur.unique' => 'Jadwal Waktu Instruktur Bertabrakan!'
        ];
    }
}
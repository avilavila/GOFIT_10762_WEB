<?php

namespace App\Http\Controllers;

use App\Models\pegawai; /* import model pegawai */
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Redirect;
use App\Http\Resources\PegawaiResource;
use Illuminate\Support\Facades\Validator;


class PegawaiController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        //get Pegawai
        $pegawai = Pegawai::latest()->get();
        //render view with posts
        return new PegawaiResource(true, 'List Data Pegawai', $pegawai);
    }
    /**
     * create
     *
     * @return void
     */
    public function create()
    {
        return view('pegawai.create');
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
            'id_pegawai' => 'required',
            'nama_pegawai' => 'required',
            'jabatan' => 'required',
            'alamat_pegawai' => 'required',
            'telepon_pegawai' => 'required',
            'email_pegawai' => 'required',
            'password_pegawai' => 'required'

        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //Fungsi Post ke Database
        $pegawai = Pegawai::create([
            'id_pegawai' => $request->id_pegawai,
            'nama_pegawai' => $request->nama_pegawai,
            'jabatan' => $request->jabatan,
            'alamat_pegawai' => $request->alamat_pegawai,
            'telepon_pegawai' => $request->telepon_pegawai,
            'email_pegawai' => $request->email_pegawai,
            'password_pegawai' => $request->password_pegawai

        ]);
        return new PegawaiResource(true, 'Data Pegawai Berhasil Ditambahkan!', $pegawai);
    }

    public function show($id)
    {
        $pegawai = Pegawai::find($id);
        

        return new PegawaiResource(true, 'Data Pegawai ditemukan!', $pegawai);
    }

    public function destroy($id)
    {
        $pegawai = Pegawai::where('id_pegawai', $id)->delete();

        return new PegawaiResource(true, 'Data Pegawai
        Berhasil Dihapus!', $pegawai);
    }

    public function edit($id)
    {
        $pegawai = Pegawai::findOrFail($id);
        return view('pegawai.edit', compact('pegawai'));  
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'id_pegawai' => 'required',
            'nama_pegawai' => 'required',
            'jabatan' => 'required',
            'alamat_pegawai' => 'required',
            'telepon_pegawai' => 'required',
            'email_pegawai' => 'required',
            'password_pegawai' => 'required',
        ]);


        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $pegawais = Pegawai::findOrFail($id);

        if ($pegawais) {

            $pegawais->update([
                'id_pegawai' => $request->id_pegawai,
                'nama_pegawai' => $request->nama_pegawai,
                'alamat_pegawai' => $request->alamat_pegawai,
                'email_pegawai' => $request->email_pegawai,
                'password_pegawai' => $request->password_pegawai,
                'telepon_pegawai' => $request->telepon_pegawai,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Post Updated',
                'data'    => $pegawais
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Post Not Found',
        ], 404);
    }
}

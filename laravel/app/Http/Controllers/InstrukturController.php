<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\instruktur; /* Import Model */
use App\Http\Resources\InstrukturResource;
use Illuminate\Support\Facades\Validator;

class InstrukturController extends Controller
{
    /**
    * index
    *
    * @return void
    */
    public function index()
    {
        $instruktur = Instruktur::latest()->get();

        return new InstrukturResource(true, 'List Data Instruktur', $instruktur);
    }

    /**
    * create
    *
    * @return void
    */
    public function create()
    {
        return view('instruktur.create');
    }

    public function show($id)
    {
        $instruktur = Instruktur::find($id);

        return new InstrukturResource(true, 'Data Instruktur ditemukan!', $instruktur);
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
            'nama_instruktur' => 'required',
            'alamat_instruktur' => 'required',
            'tanggal_lahir_instruktur' => 'required',
            'telepon_instruktur' => 'required',
            'email_instruktur' => 'required',
            'username_instruktur' => 'required',
            'password_instruktur' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //Fungsi Post ke Database
        $instruktur = Instruktur::create([
            'nama_instruktur'  => $request->nama_instruktur,
            'alamat_instruktur'  => $request->alamat_instruktur,
            'tanggal_lahir_instruktur'  => $request->tanggal_lahir_instruktur,
            'telepon_instruktur'  => $request->telepon_instruktur,
            'email_instruktur'  => $request->email_instruktur,
            'username_instruktur'  => $request->username_instruktur,
            'password_instruktur'  => $request->password_instruktur,
        ]);

        return new InstrukturResource(true, 'Data Instruktur Berhasil Ditambahkan', $instruktur);
    }

     /**
     * destroy
     *
     * @param  mixed $id
     * @return void
     */
    public function destroy($id)
    {
        $instruktur = Instruktur::where('id_instruktur', $id)->delete();

        return new InstrukturResource(true, 'Data Instruktur
        Berhasil Dihapus!', $instruktur);
    }

    public function edit($id)
    {
        $instruktur = Instruktur::findOrFail($id);
        return view('instruktur.edit', compact('instruktur'));  
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
            'nama_instruktur' => 'required',
            'alamat_instruktur' => 'required',
            'tanggal_lahir_instruktur' => 'required',
            'telepon_instruktur' => 'required',
            'email_instruktur' => 'required',
            'username_instruktur' => 'required',
            'password_instruktur' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $instruktur = Instruktur::findOrFail($id);
        $instruktur->update([
            'nama_instruktur'  => $request->nama_instruktur,
            'alamat_instruktur'  => $request->alamat_instruktur,
            'tanggal_lahir_instruktur'  => $request->tanggal_lahir_instruktur,
            'telepon_instruktur'  => $request->telepon_instruktur,
            'email_instruktur'  => $request->email_instruktur,
            'username_instruktur' => $request->username_instruktur,
            'password_instruktur'  => $request->password_instruktur
        ]);
        
        return new InstrukturResource(true, 'Data Instruktur
        Berhasil Diubah!', $instruktur);
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\izin_instruktur; /* Import Model */
use App\Models\instruktur;
use App\Models\jadwal_harian;
use App\Models\jadwal_umum;
use App\Http\Resources\IzinInstrukturResource;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class IzinInstrukturController extends Controller
{
    /**
    * index
    *
    * @return void
    */
    public function index()
    {
        $izin_instruktur = izin_instruktur::latest()->get();

        return new IzinInstrukturResource(true, 'List Data Instruktur', $izin_instruktur);
    }

    /**
    * create
    *
    * @return void
    */
    public function create()
    {
        return view('izin_instruktur.create');
    }

    public function show($id)
    {
        $izin_instruktur = izin_instruktur::join('instrukturs', 'instrukturs.id_instruktur', '=', 'izin_instrukturs.id_instruktur_pengganti')
        ->where('izin_instrukturs.id_instruktur', 'like', $id)
        ->select('izin_instrukturs.*','instrukturs.nama_instruktur')->get();

        return new IzinInstrukturResource(true,'Data Izin Instruktur Berhasil didapatkan!', $izin_instruktur);
    }

    public function konfirmasi($id){
        $today = Carbon::now();
        $izin_instruktur =  izin_instruktur::where('id_izin_instruktur', $id)->first();
        // Tampilkan tanggal konfirmasi
        // echo $tanggal_konfirmasi;

        $jadwal_harian = jadwal_harian::join('jadwal_umums', 'jadwal_harians.id_jadwal_umum', '=', 'jadwal_umums.id_jadwal_umum')
        ->where('jadwal_harians.id_instruktur', $izin_instruktur->id_instruktur)
        ->where('jadwal_umums.waktu_jadwal_umum', $izin_instruktur->sesi_izin)
        ->where('jadwal_harians.tanggal_jadwal_harian', $izin_instruktur->tanggal_izin)
        ->value('jadwal_harians.id_jadwal_harian');

        $izin_instruktur = izin_instruktur::where('id_izin_instruktur', $id)->update([
            'status_izin' => "Dikonfirmasi",
            'tanggal_konfirmasi' => $today,
        ]);

        $izin_instruktur = izin_instruktur::where('id_izin_instruktur', $id)->first();
        $nama_instruktur = Instruktur::where('id_instruktur', $izin_instruktur->id_instruktur)->value('nama_instruktur');
        $instruktur_pengganti = Instruktur::where('id_instruktur', $izin_instruktur->id_instruktur_pengganti)->value('nama_instruktur');

        $update = jadwal_harian::where('id_jadwal_harian', $jadwal_harian)->update([
            'id_instruktur'  => $izin_instruktur->id_instruktur_pengganti,
            'keterangan_jadwal_harian' => 'Instruktur ' . $nama_instruktur . ' digantikan Instruktur ' . $instruktur_pengganti
        ]);
        
        return new IzinInstrukturResource(true,'Izin instruktur sudah dikonfirmasi', $izin_instruktur);   
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
        $validator = Validator::make($request->all(),[
            'id_instruktur' => 'required',
            'id_instruktur_pengganti' => 'required',
            'tanggal_izin' => 'required',
            'sesi_izin' => 'required',
            'keterangan_izin' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $izin_instruktur = izin_instruktur::get();

        $cekIzin = izin_instruktur::where('id_instruktur', $request->id_instruktur)
        ->where('tanggal_izin', $request->tanggal_izin)
        ->where('sesi_izin', $request->sesi_izin)
        ->count();

        if($cekIzin != 0){
        return new IzinInstrukturResource(false, 'Anda sudah mengajukan izin untuk jadwal tersebut', null);
        }
        $today= Carbon::today();
        $id = Instruktur::where('nama_instruktur', 'like', $request->id_instruktur_pengganti)->value('id_instruktur');
        //$id = Instruktur::where('nama_instruktur', 'like', $request->id_instruktur_pengganti)->get();


        $getid =  $request->id_instruktur;
        $jadwal = jadwal_harian::where('id_instruktur', $getid)->count();
        if($jadwal == 0){
        return new IzinInstrukturResource(false, 'Anda belum memiliki jadwal kelas', null);
        }
        if($id == null){
        return new IzinInstrukturResource(false, 'Instruktur Pengganti Tidak ditemukan!', null);
        }

        $jam = jadwal_umum::where('waktu_jadwal_umum', 'like', $request->sesi_izin)
        ->where('id_instruktur', $getid)
        ->value('id_jadwal_umum');
        // $jam = jadwal_umum::where('waktu_jadwal_umum', 'like', $request->sesi_izin)
        // ->where('id_instruktur', $request->id_instruktur)->get();
        return new IzinInstrukturResource(false, 'Instruktur pengganti ini jadwalnya bertabrakan', $jam);

        $check = jadwal_harian::join('jadwal_umums', 'jadwal_umums.id_jadwal_umum', '=', 'jadwal_harians.id_jadwal_umum')
        ->where('jadwal_harians.id_instruktur', $getid)
        ->where('jadwal_harians.tanggal_jadwal_harian', $request->tanggal_izin)
        ->where('jadwal_harians.id_jadwal_umum', $jam)
        ->count();

        $tabrakan = jadwal_harian::where('id_instruktur', $id)
        ->where('id_jadwal_umum', $jam)->count();

        if($tabrakan != 0){
            return new IzinInstrukturResource(false, 'Instruktur pengganti ini jadwalnya bertabrakan', $izin_instruktur);
        }

        if($check == 0){
            return new IzinInstrukturResource(false, 'Anda tidak memiliki jadwal di tanggal dan jam tersebut', $izin_instruktur);
        }

        //Fungsi Simpan Data ke dalam Database
        $izin_instruktur = izin_instruktur::create([
            'id_instruktur' => $request->id_instruktur,
            'id_instruktur_pengganti'  => $id, 
            'tanggal_buat'  => $today, 
            'tanggal_izin'  => $request->tanggal_izin,
            'sesi_izin'  => $request->sesi_izin,
            'keterangan_izin'  => $request->keterangan_izin,
            'status_konfirmasi'  => "Belum Dikonfirmasi",
            'tanggal_konfirmasi'  => NULL
        ]);

        $izin_instruktur = izin_instruktur::where(['id_instruktur' => $request->id_instruktur])
        ->get();

        return new IzinInstrukturResource(true, 'Data Izin Berhasil Ditambahkan!', $izin_instruktur);
    }

     /**
     * destroy
     *
     * @param  mixed $id
     * @return void
     */
    public function destroy($id)
    {
        $izin_instruktur = izin_instruktur::where('id_izin_instruktur', $id)->delete();

        return new IzinInstrukturResource(true, 'Data Instruktur
        Berhasil Dihapus!', $izin_instruktur);
    }

    public function edit($id)
    {
        $izin_instruktur = izin_instruktur::findOrFail($id);
        return view('izin_instruktur.edit', compact('izin_instruktur'));  
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
            'id_instruktur' => 'required',
            'id_instruktur_pengganti' => 'required',
            'keterangan_izin' => 'required',
            'tanggal_izin' => 'required',
            'sesi_izin' => 'required',
            'tanggal_buat' => 'required',
            'status_izin' => 'required',
            'tanggal_konfirmasi' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $izin_instruktur = izin_instruktur::findOrFail($id);
        $izin_instruktur->update([
            'id_instruktur'  => $request->id_instruktur,
            'id_instruktur_pengganti'  => $request->id_instruktur_pengganti,
            'keterangan_izin'  => $request->keterangan_izin,
            'tanggal_izin'  => $request->tanggal_izin,
            'sesi_izin'  => $request->sesi_izin,
            'tanggal_buat'  => $request->tanggal_buat,
            'status_izin'  => $request->status_izin,
            'tanggal_konfirmasi' => $request->tanggal_konfirmasi
        ]);
        
        return new IzinInstrukturResource(true, 'Data Izin Instruktur
        Berhasil Diubah!', $izin_instruktur);
    }
}
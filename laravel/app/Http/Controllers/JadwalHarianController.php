<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\jadwal_harian; /* Import Model */
use App\Models\jadwal_umum;
use App\Models\instruktur;
use App\Http\Resources\JadwalHarianResource;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class JadwalHarianController extends Controller
{
    /**
    * index
    *
    * @return void
    */
    public function index()
    {
        // $jadwal_harian = jadwal_harian::latest()->get();

        $jadwal_harian = jadwal_harian::join('instrukturs', 'instrukturs.id_instruktur', '=', 'jadwal_harians.id_instruktur')
        ->join('jadwal_umums', 'jadwal_umums.id_jadwal_umum', '=', 'jadwal_harians.id_jadwal_umum')
        ->join('kelas', 'kelas.id_kelas', '=', 'jadwal_umums.id_kelas')
        ->select('jadwal_harians.*','instrukturs.nama_instruktur', 'jadwal_umums.hari_jadwal_umum', 'jadwal_umums.waktu_jadwal_umum','kelas.nama_kelas')
        ->get();

        return new JadwalHarianResource(true, 'List Data Jadwal Harian', $jadwal_harian);
    }

    public function init()
    {
        //$truncate = JadwalHarian::truncate();

        // $check = jadwal_harian::count();

        // $check_umum = jadwal_umum::count();

        // if($check == $check_umum){
        //     return response()->json(['errors' => 'Jadwal Harian Terupdate!'], 422);
        // }

        // jadwal_harian::truncate();
        $startDate = Carbon::today();
        $endDate = Carbon::today();
        $hari = $startDate->format('l');
        if($startDate->isSunday()){
            $startDate->addDay();
            $startDate->addDay();
            $start = $startDate;
            $startSub = $start->subDay();

            $formattedStart = $startSub->format('Y-m-d');
            $endDate->addDay();
            $end = $endDate->endOfWeek(Carbon::SUNDAY);
            $formattedEnd = $end->format('Y-m-d');

            $check_umum = jadwal_umum::count();

            $check = jadwal_harian::where('tanggal_jadwal_harian', '>=', $formattedStart)
            ->where('tanggal_jadwal_harian', '<=', $formattedEnd)
            ->count();
        } else {
            $start = $startDate->startOfWeek();
            $formattedStart = $start->format('Y-m-d');
            $end = $endDate->endOfWeek(Carbon::SUNDAY);
            $formattedEnd = $end->format('Y-m-d');

            $check_umum = jadwal_umum::count();
            $check = jadwal_harian::where('tanggal_jadwal_harian', '>=', $formattedStart)
            ->where('tanggal_jadwal_harian', '<=', $formattedEnd)
            ->count();
        }

        if($check == $check_umum){
            return response()->json(['errors' => 'Jadwal Harian terupdate!'], 422);
        }

        $dates = jadwal_umum::where(['hari_jadwal_umum' => "Senin"])->select('id_jadwal_umum', 'id_instruktur')->get();
        // Loop through each row and insert the data into the destination table
        foreach ($dates as $date) {
            $check = jadwal_harian::where(['id_instruktur' => $date->id_instruktur])
            ->where(['tanggal_jadwal_harian' => $formattedStart])
            ->where(['id_jadwal_umum' => $date->id_jadwal_umum])
            ->count();

            if($check == 0){
                jadwal_harian::insert([
                    'tanggal_jadwal_harian' => $start,
                    'id_instruktur' => $date->id_instruktur, 
                    'keterangan_jadwal_harian' => "", 
                    'id_jadwal_umum' => $date->id_jadwal_umum
                ]);
            } 
        }
        $start->addDay();


        $dates = jadwal_umum::where(['hari_jadwal_umum' => "Selasa"])->select('id_jadwal_umum', 'id_instruktur')->get();
        // Loop through each row and insert the data into the destination table
        foreach ($dates as $date) {
            $check = jadwal_harian::where(['id_instruktur' => $date->id_instruktur])
            ->where(['tanggal_jadwal_harian' => $formattedStart])
            ->where(['id_jadwal_umum' => $date->id_jadwal_umum])
            ->count();

            if($check == 0){
                jadwal_harian::insert([
                    'tanggal_jadwal_harian' => $start,
                    'id_instruktur' => $date->id_instruktur, 
                    'keterangan_jadwal_harian' => "", 
                    'id_jadwal_umum' => $date->id_jadwal_umum
                ]);
            } 
        }
        $start->addDay();

        $dates = jadwal_umum::where(['hari_jadwal_umum' => "Rabu"])->select('id_jadwal_umum', 'id_instruktur')->get();
        // Loop through each row and insert the data into the destination table
        foreach ($dates as $date) {
            $check = jadwal_harian::where(['id_instruktur' => $date->id_instruktur])
            ->where(['tanggal_jadwal_harian' => $formattedStart])
            ->where(['id_jadwal_umum' => $date->id_jadwal_umum])
            ->count();

            if($check == 0){
                jadwal_harian::insert([
                    'tanggal_jadwal_harian' => $start,
                    'id_instruktur' => $date->id_instruktur, 
                    'keterangan_jadwal_harian' => "", 
                    'id_jadwal_umum' => $date->id_jadwal_umum
                ]);
            } 
        }
        $start->addDay();

        $dates = jadwal_umum::where(['hari_jadwal_umum' => "Kamis"])->select('id_jadwal_umum', 'id_instruktur')->get();
        // Loop through each row and insert the data into the destination table
        foreach ($dates as $date) {
            $check = jadwal_harian::where(['id_instruktur' => $date->id_instruktur])
            ->where(['tanggal_jadwal_harian' => $formattedStart])
            ->where(['id_jadwal_umum' => $date->id_jadwal_umum])
            ->count();

            if($check == 0){
                jadwal_harian::insert([
                    'tanggal_jadwal_harian' => $start,
                    'id_instruktur' => $date->id_instruktur, 
                    'keterangan_jadwal_harian' => "", 
                    'id_jadwal_umum' => $date->id_jadwal_umum
                ]);
            } 
        }
        $start->addDay();

        $dates = jadwal_umum::where(['hari_jadwal_umum' => "Jumat"])->select('id_jadwal_umum', 'id_instruktur')->get();
        // Loop through each row and insert the data into the destination table
        foreach ($dates as $date) {
            $check = jadwal_harian::where(['id_instruktur' => $date->id_instruktur])
            ->where(['tanggal_jadwal_harian' => $formattedStart])
            ->where(['id_jadwal_umum' => $date->id_jadwal_umum])
            ->count();

            if($check == 0){
                jadwal_harian::insert([
                    'tanggal_jadwal_harian' => $start,
                    'id_instruktur' => $date->id_instruktur, 
                    'keterangan_jadwal_harian' => "", 
                    'id_jadwal_umum' => $date->id_jadwal_umum
                ]);
            } 
        }
        $start->addDay();

        $dates = jadwal_umum::where(['hari_jadwal_umum' => "Sabtu"])->select('id_jadwal_umum', 'id_instruktur')->get();
        // Loop through each row and insert the data into the destination table
        foreach ($dates as $date) {
            $check = jadwal_harian::where(['id_instruktur' => $date->id_instruktur])
            ->where(['tanggal_jadwal_harian' => $formattedStart])
            ->where(['id_jadwal_umum' => $date->id_jadwal_umum])
            ->count();

            if($check == 0){
                jadwal_harian::insert([
                    'tanggal_jadwal_harian' => $start,
                    'id_instruktur' => $date->id_instruktur, 
                    'keterangan_jadwal_harian' => "", 
                    'id_jadwal_umum' => $date->id_jadwal_umum
                ]);
            } 
        }
        $start->addDay();

        $dates = jadwal_umum::where(['hari_jadwal_umum' => "Minggu"])->select('id_jadwal_umum', 'id_instruktur')->get();
        // Loop through each row and insert the data into the destination table
        foreach ($dates as $date) {
            $check = jadwal_harian::where(['id_instruktur' => $date->id_instruktur])
            ->where(['tanggal_jadwal_harian' => $formattedStart])
            ->where(['id_jadwal_umum' => $date->id_jadwal_umum])
            ->count();

            if($check == 0){
                jadwal_harian::insert([
                    'tanggal_jadwal_harian' => $start,
                    'id_instruktur' => $date->id_instruktur, 
                    'keterangan_jadwal_harian' => "", 
                    'id_jadwal_umum' => $date->id_jadwal_umum
                ]);
            } 
        }
        $start->addDay();

        $start->addDay();
        $jadwal_harian = jadwal_harian::where('tanggal_jadwal_harian', '>=', $formattedStart)
        ->where('tanggal_jadwal_harian', '<=', $start)
        ->get();

        return new JadwalHarianResource(true, 'List Data Jadwal Harian', $jadwal_harian);
    }

    /**
    * create
    *
    * @return void
    */
    public function create()
    {
        // $jadwal_umum = jadwal_umum::all();
        // $kelas = Kelas::all();
        // $instruktur = Instruktur::all();
        // return view('jadwal_umum.create', compact('kelas', 'instruktur', 'jadwal_umum'));
    }

    public function show($id)
    {
        $jadwal_harian = jadwal_harian::find($id);

        return new JadwalHarianResource(true, 'Data Jadwal Harian ditemukan!', $jadwal_harian);
    }

    public function liburkan($id){
        $jadwal_harian =  jadwal_harian::where('id_jadwal_harian', $id)->get();

        $jadwal_harian = jadwal_harian::where('id_jadwal_harian', $id)->update([
            'keterangan_jadwal_harian'  => "Libur"
        ]);
        
        return new JadwalHarianResource(true,'Jadwal Diliburkan!', $jadwal_harian);   
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
        // $validator = Validator::make($request->all(), [
        //     'hari_jadwal_umum' => 'required',
        //     'id_kelas' => 'required',
        //     'id_instruktur' => 'unique:jadwal_umums,id_instruktur,NULL,id_jadwal_umum,id_instruktur,' . 
        //                         $request->id_instruktur. ',hari_jadwal_umum,' . $request->hari_jadwal_umum . ',waktu_jadwal_umum,' .$request->waktu_jadwal_umum,
        //     'waktu_jadwal_umum' => 'required'
        // ],
        // [   'id_instruktur.required' => 'Tidak Boleh Kosong!',
        //     'id_instruktur.unique' => 'Jadwal Instruktur Bertabrakan!']);

        // if ($validator->fails()) {
        //     return response()->json($validator->errors(), 422);
        // }

        // //Fungsi Simpan Data ke dalam Database
        // $jadwal_umum = jadwal_umum::create([
        //     'hari_jadwal_umum' => $request->hari_jadwal_umum,
        //     'id_kelas' => $request->id_kelas,
        //     'id_instruktur' => $request->id_instruktur,
        //     'waktu_jadwal_umum' => $request->waktu_jadwal_umum
        // ]);

        // return new JadwalUmumResource(true, 'Jadwal Umum Berhasil Ditambahkan', $jadwal_umum);
    }

     /**
     * destroy
     *
     * @param  mixed $id
     * @return void
     */
    public function destroy($id)
    {
        // $jadwal_umum = jadwal_umum::where('id_jadwal_umum', $id)->delete();

        // return new JadwalUmumResource(true, 'Jadwal Umum
        // Berhasil Dihapus!', $jadwal_umum);
    }

    public function edit($id)
    {
        $jadwal_harian = jadwal_harian::findOrFail($id);
        $jadwal_umum = jadwal_umum::all();
        $instrukturs = Instruktur::all();
        return view('jadwal_harian.edit', compact('jadwal_harian', 'jadwal_umum', 'instruktur'));
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
            'tanggal_jadwal_harian' => 'required',
            'id_jadwal_umum' => 'required',
            'id_instruktur' => 'unique:jadwal_umums,id_instruktur,NULL,id_jadwal_umum,id_instruktur,' . 
                                $request->id_instruktur. ',hari_jadwal_umum,' . $request->hari_jadwal_umum . ',waktu_jadwal_umum,' .$request->waktu_jadwal_umum,
        ],
        [   'id_instruktur.required' => 'Wajib pilih instruktur!',
            'id_instruktur.unique' => 'Jadwal Waktu Instruktur Bertabrakan!']);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //Fungsi Simpan Data ke dalam Database
        $jadwal_harian = jadwal_harian::where('id_jadwal_harian', $id)->update([
            'tanggal_jadwal_harian' => $request->tanggal_jadwal_harian,
            'id_jadwal_umum' => $request->id_jadwal_umum,
            'id_instruktur' => $request->id_instruktur,
            'keterangan_jadwal_harian' => $request->keterangan_jadwal_harian
        ]);
        
        return new JadwalHarianResource(true, 'Jadwal Harian Berhasil Diubah!', $jadwal_harian);
    }

    // public function messages()
    // {
    //     return [
    //         'id_instruktur.unique' => 'Jadwal Waktu Instruktur Bertabrakan!'
    //     ];
    // }
}
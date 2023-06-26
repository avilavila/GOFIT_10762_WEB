<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Resources\PresensiInstrukturResource;
use Illuminate\Support\Facades\Validator;
use App\Models\presensi_instruktur; /* Import Model */
use App\Models\jadwal_harian; /* Import Model */
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;


class PresensiInstrukturController extends Controller
{
    /**
    * index
    *
    * @return void
    */
    public function index()
    {
        $presensi_instruktur = presensi_instruktur::latest()->get();

        return new PresensiInstrukturResource(true, 'List Data Presensi Instruktur',
        $presensi_instruktur);
    }

    public function show($id)
    {
        $presensi_instruktur = presensi_instruktur::find($id);
        
        return new PresensiInstrukturResource(true, 'Data Presensi ditemukan!', $presensi_instruktur);
    }

    public function reset()
    {
        $reset = presensi_instruktur::truncate();

        return new PresensiInstrukturResource(true, 'List Data Presensi Instruktur',
        $reset);
    }

    public function getData()
    {
        $date = Carbon::today();
        $today = $date->format('l');

        if($today == "Monday"){
            $data = jadwal_harian::join('instrukturs', 'instrukturs.id_instruktur', '=', 'jadwal_harians.id_instruktur')
                    ->join('jadwal_umums', 'jadwal_umums.id_jadwal_umum', '=', 'jadwal_harians.id_jadwal_umum')
                    ->join('kelas', 'kelas.id_kelas', '=', 'jadwal_umums.id_kelas')
                    ->where('jadwal_umums.hari_jadwal_umum', 'Senin')
                    ->select('jadwal_harians.*', 'jadwal_umums.hari_jadwal_umum', 'jadwal_umums.waktu_jadwal_umum', 'kelas.nama_kelas', 'instrukturs.nama_instruktur')->get();
        } else if ($today == "Tuesday")  {
            $data = jadwal_harian::join('instrukturs', 'instrukturs.id_instruktur', '=', 'jadwal_harians.id_instruktur')
                    ->join('jadwal_umums', 'jadwal_umums.id_jadwal_umum', '=', 'jadwal_harians.id_jadwal_umum')
                    ->join('kelas', 'kelas.id_kelas', '=', 'jadwal_umums.id_kelas')
                    ->where('jadwal_umums.hari_jadwal_umum', 'Selasa')
                    ->select('jadwal_harians.*', 'jadwal_umums.hari_jadwal_umum', 'jadwal_umums.waktu_jadwal_umum', 'kelas.nama_kelas', 'instrukturs.nama_instruktur')->get();
        } else if ($today == "Wednesday")  {
            $data = jadwal_harian::join('instrukturs', 'instrukturs.id_instruktur', '=', 'jadwal_harians.id_instruktur')
                    ->join('jadwal_umums', 'jadwal_umums.id_jadwal_umum', '=', 'jadwal_harians.id_jadwal_umum')
                    ->join('kelas', 'kelas.id_kelas', '=', 'jadwal_umums.id_kelas')
                    ->where('jadwal_umums.hari_jadwal_umum', 'Rabu')
                    ->select('jadwal_harians.*', 'jadwal_umums.hari_jadwal_umum', 'jadwal_umums.waktu_jadwal_umum', 'kelas.nama_kelas', 'instrukturs.nama_instruktur')->get();
        } else if ($today == "Thursday")  {
            $data = jadwal_harian::join('instrukturs', 'instrukturs.id_instruktur', '=', 'jadwal_harians.id_instruktur')
                    ->join('jadwal_umums', 'jadwal_umums.id_jadwal_umum', '=', 'jadwal_harians.id_jadwal_umum')
                    ->join('kelas', 'kelas.id_kelas', '=', 'jadwal_umums.id_kelas')
                    ->where('jadwal_umums.hari_jadwal_umum', 'Kamis')
                    ->select('jadwal_harians.*', 'jadwal_umums.hari_jadwal_umum', 'jadwal_umums.waktu_jadwal_umum', 'kelas.nama_kelas', 'instrukturs.nama_instruktur')->get();
        } else if ($today == "Friday")  {
            $data = jadwal_harian::join('instrukturs', 'instrukturs.id_instruktur', '=', 'jadwal_harians.id_instruktur')
                    ->join('jadwal_umums', 'jadwal_umums.id_jadwal_umum', '=', 'jadwal_harians.id_jadwal_umum')
                    ->join('kelas', 'kelas.id_kelas', '=', 'jadwal_umums.id_kelas')
                    ->where('jadwal_umums.hari_jadwal_umum', 'Jumat')
                    ->select('jadwal_harians.*', 'jadwal_umums.hari_jadwal_umum', 'jadwal_umums.waktu_jadwal_umum', 'kelas.nama_kelas', 'instrukturs.nama_instruktur')->get();
        } else if ($today == "Saturday")  {
            $data = jadwal_harian::join('instrukturs', 'instrukturs.id_instruktur', '=', 'jadwal_harians.id_instruktur')
                    ->join('jadwal_umums', 'jadwal_umum.id_jadwal_umum', '=', 'jadwal_harians.id_jadwal_umum')
                    ->join('kelas', 'kelas.id_kelas', '=', 'jadwal_umums.id_kelas')
                    ->where('jadwal_umums.hari_jadwal_umum', 'Sabtu')
                    ->select('jadwal_harians.*', 'jadwal_umums.hari_jadwal_umum', 'jadwal_umums.waktu_jadwal_umum', 'kelas.nama_kelas', 'instrukturs.nama_instruktur')->get();
        } else if ($today == "Sunday")  {
            $data = jadwal_harian::join('instrukturs', 'instrukturs.id_instruktur', '=', 'jadwal_harians.id_instruktur')
                    ->join('jadwal_umums', 'jadwal_umums.id_jadwal_umum', '=', 'jadwal_harians.id_jadwal_umum')
                    ->join('kelas', 'kelas.id_kelas', '=', 'jadwal_umums.id_kelas')
                    ->where('jadwal_umums.hari_jadwal_umum', 'Minggu')
                    ->select('jadwal_harians.*', 'jadwal_umums.hari_jadwal_umum', 'jadwal_umums.waktu_jadwal_umum', 'kelas.nama_kelas', 'instrukturs.nama_instruktur')->get();
        } 
            
        return new PresensiInstrukturResource(true, 'List Data Presensi Instruktur', $data);
    }

    public function getJadwalHarian()
    {
        $data = jadwal_harian::join('instrukturs', 'instrukturs.id_instruktur', '=', 'jadwal_harians.id_instruktur')
                    ->join('jadwal_umums', 'jadwal_umums.id_jadwal_umum', '=', 'jadwal_harians.id_jadwal_umum')
                    ->join('kelas', 'kelas.id_kelas', '=', 'jadwal_umums.id_kelas')
                    ->select('jadwal_harians.*', 'jadwal_umums.hari_jadwal_umum', 'jadwal_umums.waktu_jadwal_umum', 'kelas.nama_kelas', 'instrukturs.nama_instruktur')->get();
            
        return new PresensiInstrukturResource(true, 'List Data Presensi Instruktur', $data);
    }

    public function getHistori(Request $request)
    {
        $data = presensi_instruktur::join('jadwal_harians', 'jadwal_harians.id_jadwal_harian', '=', 'presensi_instrukturs.id_jadwal_harian')
                    ->join('instrukturs', 'instrukturs.id_instruktur', '=', 'jadwal_harians.id_instruktur')
                    ->join('jadwal_umums', 'jadwal_umums.id_jadwal_umum', '=', 'jadwal_harians.id_jadwal_umum')
                    ->join('kelas', 'kelas.id_kelas', '=', 'jadwal_umums.id_kelas')
                    ->where('presensi_instrukturs.id_instruktur', $request->id_instruktur)
                    ->select('presensi_instrukturs.*', 'kelas.nama_kelas', 'jadwal_umums.hari_jadwal_umum', 'jadwal_harians.tanggal_jadwal_harian')->get();
            
        return new PresensiInstrukturResource(true, 'List Data Presensi Instruktur', $data);
    }

    public function getLaporanKinerjaInstruktur()
    {
        $data = presensi_instruktur::join('jadwal_harians', 'jadwal_harians.id_jadwal_harian', '=', 'presensi_instrukturs.id_jadwal_harian')
                    ->join('jadwal_umums', 'jadwal_umums.id_jadwal_umum', '=', 'jadwal_harians.id_jadwal_umum')
                    ->whereMonth('jh.tanggal_jadwal_harian',$request->bulan)
                    ->whereYear('jh.tanggal_jadwal_harian',$request->tahun)
                    ->rightJoin('instrukturs', 'instrukturs.id_instruktur', '=', 'presensi_instrukturs.id_instruktur')
                    ->select(
                        'instrukturs.nama_instruktur', 
                        presensi_instruktur::raw('COUNT(presensi_instrukturs.id_instruktur) as jumlah_hadir'),
                        jadwal_harian::raw('(SELECT COUNT(keterangan_jadwal_harian) FROM jadwal_harians WHERE id_instruktur = presensi_instrukturs.id_instruktur AND keterangan_jadwal_harian = "Libur") as jumlah_libur'),
                        presensi_instruktur::raw('SUM(presensi_instrukturs.keterlambatan) as waktu_terlambat')
                        )
                    ->groupBy('presensi_instrukturs.id_instruktur')
                    ->groupBy('instrukturs.nama_instruktur')
                    ->groupBy('presensi_instrukturs.id_jadwal_harian')
                    ->orderBy('instrukturs.nama_instruktur', 'asc')
                    ->get();
            
        return new PresensiInstrukturResource(true, 'List Data Presensi Instruktur', $data);
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
            'id_instruktur' => 'required',
            'id_jadwal_harian' => 'required',
            'jam_mulai' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

        $getAll = presensi_instruktur::get();

        $time1 = Carbon::createFromFormat('H:i:s', '00:00:00');

        $tanggalJadwalHarian = jadwal_harian::where('id_jadwal_harian', $request->id_jadwal_harian)->value('tanggal_jadwal_harian');

        
        $jadwal_harian = presensi_instruktur::join('jadwal_harians', 'jadwal_harians.id_jadwal_harian', '=', 'presensi_instrukturs.id_jadwal_harian')
        ->where('jadwal_harians.tanggal_jadwal_harian', $tanggalJadwalHarian)
        ->where('presensi_instrukturs.durasi_kelas', 0)->count();

        $sudahSelesai = presensi_instruktur::join('jadwal_harians', 'jadwal_harians.id_jadwal_harian', '=', 'presensi_instrukturs.id_jadwal_harian')
        ->where('jadwal_harians.tanggal_jadwal_harian', $tanggalJadwalHarian)->value('presensi_instrukturs.durasi_kelas');

        if($sudahSelesai != 0){
            return new PresensiInstrukturResource(false, 'Kelas Sudah Selesai dan Presensi Sudah Tercatat!', $getAll);

        }
        if($jadwal_harian >= 1){
            return new PresensiInstrukturResource(false, 'Kelas Masih Dalam Proses!', $getAll);
        }

        $jamKelas = jadwal_harian::join('jadwal_umums', 'jadwal_umums.id_jadwal_umum', '=', 'jadwal_harians.id_jadwal_umum')
        ->where('jadwal_harians.id_jadwal_harian', $request->id_jadwal_harian)
        ->value('jadwal_umums.waktu_jadwal_umum');

        $telat = Carbon::createFromFormat('H:i:s', $jamKelas);
        $mulai = Carbon::createFromFormat('H:i:s', $request->jam_mulai);;
        $keterlambatan = $mulai->diffInSeconds($telat);

        //Fungsi Simpan Data ke dalam Database
        $presensi_instruktur = presensi_instruktur::create([
            'id_instruktur'  => $request->id_instruktur,
            'id_jadwal_harian'  => $request->id_jadwal_harian,
            'jam_mulai'  => $request->jam_mulai,
            'jam_selesai'  => "00:00:00",
            'keterlambatan'  => $keterlambatan,
            'durasi_kelas'  => 0,
            'status'  => "-",
        ]);

        $get = presensi_instruktur::where('id_instruktur', $request->id_instruktur)
        ->where('id_jadwal_harian', $request->id_jadwal_harian)
        ->where('jam_mulai', $request->jam_mulai)
        ->get();

        return new PresensiInstrukturResource(true, 'Kelas Berhasil Dimulai!', $get);
    }

    /** update
     *
     * @param  mixed $request
     * @param  mixed $post
     * @return void
     */
    public function setSelesai(Request $request)
    {
         //Validasi Formulir
         $validator = Validator::make($request->all(), [
            'id_instruktur' => 'required',
            'id_jadwal_harian' => 'required',
            'jam_selesai' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $getAll = presensi_instruktur::get();

        $getId =  presensi_instruktur::where('id_instruktur', $request->id_instruktur)
        ->where('id_jadwal_harian', $request->id_jadwal_harian)
        ->where('durasi_kelas', 0)->value('id_presensi_instruktur');

        $getCount =  presensi_instruktur::where('id_instruktur', $request->id_instruktur)
        ->where('id_jadwal_harian', $request->id_jadwal_harian)->count();

        $jamMulai =  presensi_instruktur::where('id_presensi_instruktur', $getId)->value('jam_mulai');
        $jamSelesai =  presensi_instruktur::where('id_presensi_instruktur', $getId)->value('jam_selesai');
        
        if($getCount == 0){
            return new PresensiInstrukturResource(false, 'Kelas Belum Dimulai!', $getAll);
        }

        if($jamSelesai != "00:00:00"){
            return new PresensiInstrukturResource(false, 'Kelas Sudah Selesai dan Presensi Sudah Tercatat!', $getAll);

        }
        
        $check = presensi_instruktur::where('id_presensi_instruktur', $getId)->value('durasi_kelas');

        $selesai = Carbon::createFromFormat('H:i:s', $request->jam_selesai);

        $durasi = $selesai->diffInSeconds($jamMulai);


        if($jamMulai == "00:00:00.000000"){
            return new PresensiInstrukturResource(false, 'Kelas Belum Dimulai!', $getAll);
        }

        

        //Fungsi Simpan Data ke dalam Database
        $presensi_instruktur = presensi_instruktur::where('id_presensi_instruktur', $getId)
        ->update([
            'jam_selesai' => $request->jam_selesai,
            'durasi_kelas'  => $durasi,
        ]);

        // alihkan halaman ke halaman jadwal_umum
        return new PresensiInstrukturResource(true, 'Kelas sudah selesai!', $getAll);
    }
}

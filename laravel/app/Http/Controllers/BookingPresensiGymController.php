<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\booking_presensi_gym; /* Import Model */
use App\Models\member;
use App\Http\Resources\BookingPresensiGymResource;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class BookingPresensiGymController extends Controller
{
    public function index()
    {
        $booking_presensi_gym = booking_presensi_gym::latest()->get();

        return new BookingPresensiGymResource(true, 'List Data Booking ', $booking_presensi_gym);
    }

    /**
    * create
    *
    * @return void
    */
    public function create()
    {
        return view('booking_presensi_gym.create');
    }

    public function show($id)
    {
        $booking_presensi_gym = booking_presensi_gym::join('members', 'members.id_member', '=', 'booking_presensi_gyms.id_member')
        ->where('booking_presensi_gyms.no_struk_presensi_gym', $id)
        ->select('booking_presensi_gyms.*','members.nama_member')
        ->first();

        return new BookingPresensiGymResource(true, 'Data Booking Presensi Gym ditemukan!', $booking_presensi_gym);
    }

    public function getData(Request $request){
        $booking_presensi_gym = booking_presensi_gym::join('members', 'members.id_member', '=', 'booking_presensi_gyms.id_member')
        ->where('booking_presensi_gyms.id_member', $request->id)
        ->select('booking_presensi_gyms.*', 'members.nama_member')
        ->get();
        return new BookingPresensiGymResource(true,'Data Booking Berhasil Ditemukan', $booking_presensi_gym);
    }

    public function getHistori(Request $request)
    {
        $booking_presensi_gym = booking_presensi_gym::where('id_member', $request->id_member)
        ->where('status_presensi_gym', '!=', 'Belum Dipresensi')
        ->orderBy('tanggal_booking_gym', 'asc')
        ->get();

        return new BookingPresensiGymResource(true, 'List Data Booking Presensi Gym didapatkan!',
        $booking_presensi_gym);
    }

    public function presensi($id){
        $today = Carbon::now();
        $booking_presensi_gym =  booking_presensi_gym::where('no_struk_presensi_gym', $id)->first();

        $cek =  booking_presensi_gym::where('no_struk_presensi_gym', $id)->value('status_presensi_gym');

        if($cek != NULL){
            return response()->json(['error' => 'Member ini sudah dipresensi'], 422);
        }
       

        $booking_presensi_gym = booking_presensi_gym::where('no_struk_presensi_gym', $id)->update([
            'status_presensi_gym' => "Hadir",
            'jam_presensi_gym' => $today,
        ]);

        $booking_presensi_gym = booking_presensi_gym::where('no_struk_presensi_gym', $id)->first();
        $nama_member = member::where('id_member', $booking_presensi_gym->id_member)->value('nama_member');

        // $update = jadwal_harian::where('id_jadwal_harian', $jadwal_harian)->update([
        //     'id_instruktur'  => $booking_presensi_gym->id_instruktur_pengganti,
        //     'keterangan_jadwal_harian' => 'Instruktur ' . $nama_instruktur . ' digantikan Instruktur ' . $instruktur_pengganti
        // ]);
        
        return new BookingPresensiGymResource(true,'Presensi sudah dilakukan', $booking_presensi_gym);   
    }

    public function tidakHadir($id){
        $today = Carbon::now();
        $booking_presensi_gym =  booking_presensi_gym::where('no_struk_presensi_gym', $id)->first();

        $cek =  booking_presensi_gym::where('no_struk_presensi_gym', $id)->value('status_presensi_gym');

        if($cek != NULL){
            return response()->json(['error' => 'Member ini sudah dipresensi'], 422);
        }

        // $jadwal_harian = jadwal_harian::join('jadwal_umums', 'jadwal_harians.id_jadwal_umum', '=', 'jadwal_umums.id_jadwal_umum')
        // ->where('jadwal_harians.id_instruktur', $booking_presensi_gym->id_instruktur)
        // ->where('jadwal_umums.waktu_jadwal_umum', $booking_presensi_gym->sesi_izin)
        // ->where('jadwal_harians.tanggal_jadwal_harian', $booking_presensi_gym->tanggal_izin)
        // ->value('jadwal_harians.id_jadwal_harian');

        $booking_presensi_gym = booking_presensi_gym::where('no_struk_presensi_gym', $id)->update([
            'status_presensi_gym' => "Tidak Hadir",
            'jam_presensi_gym' => "00:00:00",
        ]);

        $booking_presensi_gym = booking_presensi_gym::where('no_struk_presensi_gym', $id)->first();
        $nama_member = member::where('id_member', $booking_presensi_gym->id_member)->value('nama_member');

        // $update = jadwal_harian::where('id_jadwal_harian', $jadwal_harian)->update([
        //     'id_instruktur'  => $booking_presensi_gym->id_instruktur_pengganti,
        //     'keterangan_jadwal_harian' => 'Instruktur ' . $nama_instruktur . ' digantikan Instruktur ' . $instruktur_pengganti
        // ]);
        
        return new BookingPresensiGymResource(true,'Presensi sudah dilakukan', $booking_presensi_gym);   
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
            'id_member' => 'required',
            'tanggal_booking_gym' => 'required',
            'slot_booking' => 'required',
            // 'status_presensi_gym' => 'required',
            // 'jam_presensi_gym' => 'required',
            // 'tanggal_pembuatan_booking_gym' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $today = Carbon::today();

        $id = IdGenerator::generate(['table' => 'booking_presensi_gyms', 'field' => 'no_struk_presensi_gym', 'length' => 9, 'prefix' => date('y.m.')]);
        

        //Fungsi Post ke Database
        $booking_presensi_gym = booking_presensi_gym::create([
            'no_struk_presensi_gym'  => $id,
            'id_member'  => $request->id_member,
            'tanggal_booking_gym'  => $request->tanggal_booking_gym,
            'slot_booking'  => $request->slot_booking,
            // 'status_presensi_gym'  => "Belum Presensi",
            // 'jam_presensi_gym'  => $request->jam_presensi_gym,
            'tanggal_pembuatan_booking_gym'  => $today
            
        ]);

        $booking = booking_presensi_gym::get();

        return new BookingPresensiGymResource(true, 'Data Booking Presensi Berhasil Ditambahkan', $booking);
    }

     /**
     * destroy
     *
     * @param  mixed $id
     * @return void
     */
    public function destroy($id)
    {
        $booking_presensi_gym =  booking_presensi_gym::where('no_struk_presensi_gym', $id)->value('tanggal_booking_gym');
        $status =  booking_presensi_gym::where('no_struk_presensi_gym', $id)->value('status_presensi_gym');
        $date = Carbon::parse($booking_presensi_gym);
        $formattedDate = $date->format('Y-m-d');
        $today = Carbon::today();
        $formattedToday = $today->format('Y-m-d');
        $booking =  booking_presensi_gym::get();


        if($status != NULL){
            return new BookingPresensiGymResource(false,'Sudah dipresensi!', $booking);
        }
        if($formattedToday == $formattedDate){
            return new BookingPresensiGymResource(false,'Pembatalan kehadiran paling lambat H-1', $booking);
        }

        $booking_presensi_gym =  booking_presensi_gym::where('no_struk_presensi_gym', $id)->delete();

        $booking = booking_presensi_gym::get();

        return new BookingPresensiGymResource(true, 'Data Booking Presensi Berhasil Ditambahkan', $booking);
    }

    public function edit($id)
    {
        $booking_presensi_gym = booking_presensi_gym::findOrFail($id);
        return view('booking_presensi_gym.edit', compact('booking_presensi_gym'));  
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
            'id_member' => 'required',
            'tanggal_booking_gym' => 'required',
            'slot_booking' => 'required',
            'status_presensi_gym' => 'required',
            'jam_presensi_gym' => 'required',
            'tanggal_pembuatan_booking_gym' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $booking_presensi_gym = booking_presensi_gym::findOrFail($id);
        $booking_presensi_gym->update([
            'id_member'  => $request->id_member,
            'tanggal_booking_gym'  => $request->tanggal_booking_gym,
            'slot_booking'  => $request->slot_booking,
            'status_presensi_gym'  => $request->status_presensi_gym,
            'jam_presensi_gym'  => $request->jam_presensi_gym,
            'tanggal_pembuatan_booking_gym'  => $request->tanggal_pembuatan_booking_gym
        ]);
        
        return new BookingPresensiGymResource(true, 'Data Izin Instruktur
        Berhasil Diubah!', $booking_presensi_gym);
    }
}

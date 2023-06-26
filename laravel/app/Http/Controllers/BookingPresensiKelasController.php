<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\booking_presensi_kelas; /* Import Model */
use App\Models\member;
use App\Models\jadwal_harian;
use App\Models\deposit_kelas_member;
use App\Models\jadwal_umum;
use App\Models\kelas;
use App\Models\presensi_instruktur;
use App\Http\Resources\BookingPresensiKelasResource;
use Illuminate\Support\Facades\Validator;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Carbon\Carbon;

class BookingPresensiKelasController extends Controller
{
    public function index()
    {
        $booking_presensi_kelas = booking_presensi_kelas::latest()->get();

        return new BookingPresensiKelasResource(true, 'List Data Booking', $booking_presensi_kelas);
    }

    /**
    * create
    *
    * @return void
    */
    public function create()
    {
        return view('booking_presensi_kelas.create');
    }

    public function show($id){
        $check = booking_presensi_kelas::where('booking_presensi_kelas.no_struk_presensi_kelas', $id)
        ->value('jenis_booking_kelas');

        if($check != "Paket"){
            $booking_presensi_kelas = booking_presensi_kelas::join('members', 'members.id_member', '=', 'booking_presensi_kelas.id_member')
            ->join('jadwal_harians', 'jadwal_harians.id_jadwal_harian', '=', 'booking_presensi_kelas.id_jadwal_harian')
            ->join('instrukturs', 'instrukturs.id_instruktur', '=', 'jadwal_harians.id_instruktur')
            ->join('jadwal_umums', 'jadwal_umums.id_jadwal_umum', '=', 'jadwal_harians.id_jadwal_umum')
            ->join('kelas', 'kelas.id_kelas', '=', 'jadwal_umums.id_kelas')
            ->where('booking_presensi_kelas.no_struk_presensi_kelas', $id)
            ->select('booking_presensi_kelas.*', 'members.nama_member', 'kelas.nama_kelas', 'instrukturs.id_instruktur', 'instrukturs.nama_instruktur', 'kelas.harga_kelas', 'members.saldo_deposit')
            ->first();
        } else {
            $booking_presensi_kelas = booking_presensi_kelas::join('members', 'members.id_member', '=', 'booking_presensi_kelas.id_member')
            ->join('jadwal_harians', 'jadwal_harians.id_jadwal_harian', '=', 'booking_presensi_kelas.id_jadwal_harian')
            ->join('instrukturs', 'instrukturs.id_instruktur', '=', 'jadwal_harians.id_instruktur')
            ->join('jadwal_umums', 'jadwal_umums.id_jadwal_umum', '=', 'jadwal_harians.id_jadwal_umum')
            ->join('kelas', 'kelas.id_kelas', '=', 'jadwal_umums.id_kelas')
            ->join('deposit_kelas_members', 'deposit_kelas_members.id_member', '=', 'members.id_member') 
            ->where('booking_presensi_kelas.no_struk_presensi_kelas', $id)
            ->where('deposit_kelas_members.tanggal_kadaluarsa_kelas', '>', '0001-01-01')
            ->select('booking_presensi_kelas.*', 'members.nama_member', 'kelas.nama_kelas', 'instrukturs.id_instruktur', 'instrukturs.nama_instruktur', 'kelas.harga_kelas', 'deposit_kelas_members.deposit_paket_kelas', 'deposit_kelas_members.tanggal_kadaluarsa_kelas')
            ->first();
        }

        
        return new BookingPresensiKelasResource(true,'Data Booking Berhasil', $booking_presensi_kelas);
    }

    public function getDataBooking(Request $request)
    {
        $booking_presensi_kelas = booking_presensi_kelas::join('jadwal_harians', 'jadwal_harians.id_jadwal_harian', '=', 'booking_presensi_kelas.id_jadwal_harian')
        ->join('jadwal_umums', 'jadwal_umums.id_jadwal_umum', '=', 'jadwal_harians.id_jadwal_umum')
        ->join('kelas', 'kelas.id_kelas', '=', 'jadwal_umums.id_kelas')
        ->join('members', 'members.id_member', '=', 'booking_presensi_kelas.id_member')
        ->orderBy('jadwal_umums.waktu_jadwal_umum', 'asc')
        ->orderBy('jadwal_harians.tanggal_jadwal_harian', 'asc')
        ->where('booking_presensi_kelas.id_member', $request->id_member)
        ->select('booking_presensi_kelas.*', 'jadwal_umums.hari_jadwal_umum', 'jadwal_harians.tanggal_jadwal_harian', 'kelas.nama_kelas', 'members.nama_member')
        ->get();

        return new BookingPresensiKelasResource(true, 'List Data Booking Presensi Kelas didapatkan!',
        $booking_presensi_kelas);
    }

    public function getDataBookingInstruktur(Request $request)
    {
        $getAll = booking_presensi_kelas::get();
        $check = presensi_instruktur::where('id_instruktur', $request->id_instruktur)
        ->where('jam_selesai', '00:00:00.000000')
        ->count();
        $tanggal = Carbon::today();

        $formattedTanggal = $tanggal->format('Y-m-d');

        if($check == 0){
            return new BookingPresensiKelasResource(false, 'Anda belum dipresensi oleh MO!', $getAll);
        }

        $booking_presensi_kelas = booking_presensi_kelas::join('jadwal_harians', 'jadwal_harians.id_jadwal_harian', '=', 'booking_presensi_kelas.id_jadwal_harian')
        ->join('jadwal_umums', 'jadwal_umums.id_jadwal_umum', '=', 'jadwal_harians.id_jadwal_umum')
        ->join('kelas', 'kelas.id_kelas', '=', 'jadwal_umums.id_kelas')
        ->join('members', 'members.id_member', '=', 'booking_presensi_kelas.id_member')
        ->where('jadwal_harians.id_instruktur', $request->id_instruktur)
        ->where('jadwal_harians.tanggal_jadwal_harian', $formattedTanggal)
        ->orderBy('jadwal_umums.waktu_jadwal_umum', 'asc')
        ->orderBy('jadwal_harians.tanggal_jadwal_harian', 'asc')
        ->select('booking_presensi_kelas.*', 'jadwal_umums.hari_jadwal_umum', 'jadwal_harians.tanggal_jadwal_harian', 'kelas.nama_kelas', 'members.nama_member')
        ->get();

        return new BookingPresensiKelasResource(true, 'List Data Booking Presensi Kelas didapatkan!',
        $booking_presensi_kelas);
    }

    public function getHistori(Request $request)
    {
        $booking_presensi_kelas = booking_presensi_kelas::join('jadwal_harians', 'jadwal_harians.id_jadwal_harian', '=', 'booking_presensi_kelas.id_jadwal_harian')
        ->join('jadwal_umums', 'jadwal_umums.id_jadwal_umum', '=', 'jadwal_harians.id_jadwal_umum')
        ->join('kelas', 'kelas.id_kelas', '=', 'jadwal_umums.id_kelas')
        ->join('members', 'members.id_member', '=', 'booking_presensi_kelas.id_member')
        ->orderBy('jadwal_umums.waktu_jadwal_umum', 'asc')
        ->orderBy('jadwal_harians.tanggal_jadwal_harian', 'asc')
        ->where('booking_presensi_kelas.id_member', $request->id_member)
        ->select('booking_presensi_kelas.*', 'jadwal_umums.hari_jadwal_umum', 'jadwal_harians.tanggal_jadwal_harian', 'kelas.nama_kelas', 'members.nama_member')
        ->get();

        return new BookingPresensiKelasResource(true, 'List Data Booking Presensi Kelas didapatkan!',
        $booking_presensi_kelas);
    }

    public function getLaporanAktivitasKelas(Request $request)
    {
        $results = booking_presensi_kelas::join('jadwal_harians AS jh', 'jh.id_jadwal_harian', '=', 'booking_presensi_kelas.id_jadwal_harian')
        ->join('jadwal_umums', 'jadwal_umums.id_jadwal_umum', '=', 'jh.id_jadwal_umum')
        ->join('kelas', 'kelas.id_kelas', '=', 'jadwal_umums.id_kelas')
        ->join('instrukturs', 'instrukturs.id_instruktur', '=', 'jadwal_umums.id_instruktur')
        // tambah wheremonth dan year jh.jadwal_harian
        ->whereMonth('jh.tanggal_jadwal_harian',$request->bulan)
        ->whereYear('jh.tanggal_jadwal_harian',$request->tahun)

        ->groupBy('kelas.nama_kelas')
        ->groupBy('instrukturs.nama_instruktur')
        ->groupBy('jh.id_jadwal_harian')
        ->orderBy('kelas.nama_kelas', 'asc')
        ->select('kelas.nama_kelas', 'instrukturs.nama_instruktur', booking_presensi_kelas::raw('COUNT(booking_presensi_kelas.id_member) as jumlah_peserta'),
            jadwal_harian::raw('(SELECT COUNT(keterangan_jadwal_harian) FROM jadwal_harians WHERE id_jadwal_harian = jh.id_jadwal_harian AND keterangan_jadwal_harian = "Libur") as jumlah_libur')
        )->get();

        return new BookingPresensiKelasResource(true, 'List Data Booking Presensi Kelas didapatkan!',$results);
    }

    public function setHadir(Request $request)
    {
        $getAll = booking_presensi_kelas::get();

        $status = booking_presensi_kelas::where('booking_presensi_kelas.id_member', $request->id_member)
        ->where('booking_presensi_kelas.id_jadwal_harian', $request->id_jadwal_harian)->value('status_presensi_kelas');

        if($status != NULL){
            return new BookingPresensiKelasResource(false, 'Member ini sudah dipresensi!',$getAll);
        }

        $jenis = booking_presensi_kelas::where('booking_presensi_kelas.id_member', $request->id_member)
        ->where('booking_presensi_kelas.id_jadwal_harian', $request->id_jadwal_harian)->value('jenis_booking_kelas');

        $kelas = booking_presensi_kelas::join('jadwal_harians', 'jadwal_harians.id_jadwal_harian', '=', 'booking_presensi_kelas.id_jadwal_harian')
        ->join('jadwal_umums', 'jadwal_umums.id_jadwal_umum', '=', 'jadwal_harians.id_jadwal_umum')
        ->where('jadwal_harians.id_jadwal_harian', $request->id_jadwal_harian)
        ->value('jadwal_umums.id_kelas');

        if($jenis == "Paket"){
            $deposit_paket = deposit_kelas_member::where('id_member', $request->id_member)
            ->where('id_kelas', $kelas)
            ->where('deposit_paket_kelas', '>', 0)
            ->where('tanggal_kadaluarsa_kelas', '>', '0001-01-01')
            ->value('deposit_paket_kelas');

            $paket = deposit_kelas_member::where('id_member', $request->id_member)
            ->where('id_kelas', $kelas)
            ->where('tanggal_kadaluarsa_kelas', '>', '0001-01-01')
            ->update([
                'deposit_paket_kelas' => $deposit_paket - 1
            ]);

            // $booking_presensi_kelas = booking_presensi_kelas::where('booking_presensi_kelas.id_member', $request->id_member)
            // ->where('booking_presensi_kelas.id_jadwal_harian', $request->id_jadwal_harian)->update([
            //     'sisa_deposit' => $deposit_paket - 1
            // ]);
        } else {
            $saldo = Member::where('id_member', $request->id_member)->value('saldo');

            $harga = Kelas::where('id_kelas', $kelas)->value('harga_kelas');

            $member = Member::where('id_member', $request->id_member)->update([
                'saldo' => $saldo - $harga
            ]);

            // $booking_presensi_kelas = booking_presensi_kelas::where('booking_presensi_kelas.id_member', $request->id_member)
            // ->where('booking_presensi_kelas.id_jadwal_harian', $request->id_jadwal_harian)->update([
            //     'sisa_deposit' => $saldo - $harga
            // ]);
        }

        $booking_presensi_kelas = booking_presensi_kelas::where('booking_presensi_kelas.id_member', $request->id_member)
        ->where('booking_presensi_kelas.id_jadwal_harian', $request->id_jadwal_harian)->update([
            'status_presensi_kelas' => "Hadir",
            'jam_presensi_kelas' => $request->jam_presensi_kelas
        ]);

        return new BookingPresensiKelasResource(true, 'Data Member berhasil dipresensi menjadi HADIR!',$getAll);
    }

    public function setTidakHadir(Request $request)
    {
        $getAll = booking_presensi_kelas::get();

        $status = booking_presensi_kelas::where('booking_presensi_kelas.id_member', $request->id_member)
        ->where('booking_presensi_kelas.id_jadwal_harian', $request->id_jadwal_harian)->value('status_presensi_kelas');

        if($status != "Belum Dipresensi"){
            return new BookingPresensiKelasResource(false, 'Member ini sudah dipresensi!',$getAll);
        }

        $jenis = booking_presensi_kelas::where('booking_presensi_kelas.id_member', $request->id_member)
        ->where('booking_presensi_kelas.id_jadwal_harian', $request->id_jadwal_harian)->value('jenis_booking_presensi');

        $kelas = booking_presensi_kelas::join('jadwal_harians', 'jadwal_harians.id_jadwal_harian', '=', 'booking_presensi_kelas.id_jadwal_harian')
        ->join('jadwal_umums', 'jadwal_umums.id_jadwal_umum', '=', 'jadwal_harians.id_jadwal_umum')->value('jadwal_umums.id_kelas');

        if($jenis == "Paket"){
            $deposit_paket = deposit_kelas_member::where('id_member', $request->id_member)
            ->where('id_kelas', $kelas)
            ->where('deposit_paket_kelas', '>', 0)
            ->where('tanggal_kadaluarsa_kelas', '>', '0001-01-01')
            ->value('deposit_paket_kelas');

            $paket = deposit_kelas_member::where('id_member', $request->id_member)
            ->where('id_kelas', $kelas)
            ->where('tanggal_kadaluarsa_kelas', '>', '0001-01-01')
            ->update([
                'deposit_paket_kelas' => $deposit_paket - 1
            ]);

            $booking_presensi_kelas = booking_presensi_kelas::where('booking_presensi_kelas.id_member', $request->id_member)
            ->where('booking_presensi_kelas.id_jadwal_harian', $request->id_jadwal_harian)->update([
                'sisa_deposit' => $deposit_paket - 1
            ]);
        } else {
            $saldo = Member::where('id_member', $request->id_member)->value('saldo');

            $harga = Kelas::where('id_kelas', $kelas)->value('harga_kelas');

            $member = Member::where('id_member', $request->id_member)->update([
                'saldo' => $saldo - $harga
            ]);

            $booking_presensi_kelas = booking_presensi_kelas::where('booking_presensi_kelas.id_member', $request->id_member)
            ->where('booking_presensi_kelas.id_jadwal_harian', $request->id_jadwal_harian)->update([
                'sisa_deposit' => $saldo - $harga
            ]);
        }
        
        $booking_presensi_kelas = booking_presensi_kelas::where('booking_presensi_kelas.id_member', $request->id_member)
        ->where('booking_presensi_kelas.id_jadwal_harian', $request->id_jadwal_harian)->update([
            'status_presensi_kelas' => "Tidak Hadir",
            'jam_presensi_kelas' => $request->jam_presensi_kelas
        ]);


        return new BookingPresensiKelasResource(true, 'Data Member berhasil dipresensi menjadi TIDAK HADIR!',$getAll);
    }

    public function getJadwalHarian()
    {
        $todayStart = Carbon::today();
        $todayEnd = Carbon::today();
        $startWeek = $todayStart->startOfWeek();
        $endWeek = $todayEnd->endOfWeek();
        $formattedStart = $startWeek->format('Y-m-d');
        $formattedEnd = $endWeek->format('Y-m-d');
        $data = jadwal_harian::join('jadwal_umums', 'jadwal_umums.id_jadwal_umum', '=', 'jadwal_harians.id_jadwal_umum')
        ->join('kelas', 'kelas.id_kelas', '=', 'jadwal_umums.id_kelas')
        ->select('jadwal_harians.id_jadwal_harian', 'jadwal_harians.tanggal_jadwal_harian', 'jadwal_umums.hari_jadwal_umum', 'jadwal_umums.waktu_jadwal_umum','kelas.nama_kelas')
        ->where('jadwal_harians.tanggal_jadwal_harian', '>=', $formattedStart)
        ->where('jadwal_harians.tanggal_jadwal_harian', '<=', $formattedEnd)
        ->orderBy('jadwal_harians.tanggal_jadwal_harian', 'asc')
        ->get();

        return new BookingPresensiKelasResource(true, 'List Jadwal Harian didapatkan!',
        $data);
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
            'id_member' => 'required',
            'id_jadwal_harian' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $booking_presensi_kelas = booking_presensi_kelas::get();

        $id = IdGenerator::generate(['table' => 'booking_presensi_kelas', 'field' => 'no_struk_presensi_kelas', 'length' => 9, 'prefix' => date('y.m.')]);

        $today = Carbon::today();
        $formattedDate = $today->format('Y-m-d');

        $masa_aktif = Member::where('id_member', $request->id_member)->value('tanggal_kadaluarsa_member');

        $harga = jadwal_harian::join('jadwal_umums', 'jadwal_umums.id_jadwal_umum', '=', 'jadwal_harians.id_jadwal_umum')
        ->join('kelas', 'kelas.id_kelas', '=', 'jadwal_umums.id_kelas')
        ->where('jadwal_harians.id_jadwal_harian', $request->id_jadwal_harian)->value('kelas.harga_kelas');

        $kelas = jadwal_harian::join('jadwal_umums', 'jadwal_umums.id_jadwal_umum', '=', 'jadwal_harians.id_jadwal_umum')
        ->join('kelas', 'kelas.id_kelas', '=', 'jadwal_umums.id_kelas')
        ->where('jadwal_harians.id_jadwal_harian', $request->id_jadwal_harian)->value('kelas.id_kelas');
        
        $kuota = booking_presensi_kelas::where('id_jadwal_harian', $request->id_jadwal_harian)->count();

        $check = booking_presensi_kelas::where('id_member', $request->id_member)
        ->where('id_jadwal_harian', $request->id_jadwal_harian)->count();

        $count_deposit_kelas = deposit_kelas_member::where('id_member', $request->id_member)
        ->where('id_kelas', $kelas)
        ->where('tanggal_kadaluarsa_kelas', '>', '0001-01-01')->count();


        if($count_deposit_kelas == 0){
            $cek_deposit = Member::where('id_member', $request->id_member)->value('saldo_deposit');
            if($cek_deposit < $harga){
                return new BookingPresensiKelasResource(false, 'Maaf, saldo anda tidak mencukupi!', $booking_presensi_kelas);
            }
            if($kuota >= 10){
                return new BookingPresensiKelasResource(false, 'Maaf, kelas sudah penuh!', $booking_presensi_kelas);
            }
            if($check >= 1){
                return new BookingPresensiKelasResource(false, 'Maaf, anda sudah booking kelas ini!', $booking_presensi_kelas);
            }
            $booking_presensi_kelas = booking_presensi_kelas::create([
                'no_struk_presensi_kelas' => $id,
                'id_member'  => $request->id_member, 
                'id_jadwal_harian'  => $request->id_jadwal_harian, 
                'tanggal_pembuatan_booking_kelas' => $today,
                'jenis_booking_kelas' => "Reguler",
                'sisa_deposit' => 0,
                'status_presensi_kelas'  => "Belum Dipresensi",
                'jam_presensi_kelas'  => "00:00:00"
            ]);
        } else {
            $cek_deposit_kelas = deposit_kelas_member::where('id_member', $request->id_member)
            ->where('id_kelas', $kelas)
            ->where('tanggal_kadaluarsa_kelas', '>', '0001-01-01')
            ->value('deposit_paket_kelas');
            if($cek_deposit_kelas == 0){
                $cek_deposit = Member::where('id_member', $request->id_member)->value('saldo_deposit');
                if($cek_deposit < $harga){
                    return new BookingPresensiKelasResource(false, 'Maaf, saldo anda tidak mencukupi!', $booking_presensi_kelas);
                }
                if($kuota >= 10){
                    return new BookingPresensiKelasResource(false, 'Maaf, kelas sudah penuh!', $booking_presensi_kelas);
                }
                if($check >= 1){
                    return new BookingPresensiKelasResource(false, 'Maaf, anda sudah booking kelas ini!', $booking_presensi_kelas);
                }
                $booking_presensi_kelas = booking_presensi_kelas::create([
                    'no_struk_presensi_kelas' => $id,
                    'id_member'  => $request->id_member, 
                    'id_jadwal_harian'  => $request->id_jadwal_harian, 
                    'tanggal_pembuatan_booking_kelas' => $today,
                    'jenis_booking_kelas' => "Reguler",
                    'sisa_deposit' => 0,
                    'status_presensi_kelas'  => "Belum Dipresensi",
                    'jam_presensi_kelas'  => "00:00:00"
                ]);
            } else {
                if($masa_aktif < $formattedDate){
                    return new BookingPresensiKelasResource(false, 'Maaf, akun anda sudah tidak aktif lagi!', $booking_presensi_kelas);
                }
                if($kuota >= 10){
                    return new BookingPresensiKelasResource(false, 'Maaf, kelas sudah penuh!', $booking_presensi_kelas);
                }
                if($check >= 1){
                    return new BookingPresensiKelasResource(false, 'Maaf, anda sudah booking kelas ini!', $booking_presensi_kelas);
                }
                //Fungsi Simpan Data ke dalam Database
                $booking_presensi_kelas = booking_presensi_kelas::create([
                    'no_struk_presensi_kelas' => $id,
                    'id_member'  => $request->id_member, 
                    'id_jadwal_harian'  => $request->id_jadwal_harian, 
                    'tanggal_pembuatan_booking_kelas' => $today,
                    'jenis_booking_kelas' => "Paket",
                    'sisa_deposit' => 0,
                    'status_presensi_kelas'  => "Belum Dipresensi",
                    'jam_presensi_kelas'  => "00:00:00"
                ]);
            }
        }
        $booking_presensi_kelas = booking_presensi_kelas::get();
        return new BookingPresensiKelasResource(true, 'Data Booking Berhasil Ditambahkan!', $booking_presensi_kelas);
    
    }

     /**
     * destroy
     *
     * @param  mixed $id
     * @return void
     */
    public function destroy($id)
    {
        $booking_presensi_kelas = booking_presensi_kelas::where('no_struk_presensi_kelas', $id)->delete();

        return new BookingPresensiKelasResource(true, 'Data Booking
        Berhasil Dihapus!', $booking_presensi_kelas);
    }

    public function edit($id)
    {
        $booking_presensi_kelas = booking_presensi_kelas::findOrFail($id);
        return view('booking_presensi_kelas.edit', compact('booking_presensi_kelas'));  
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
            'id_jadwal_harian' => 'required',
            // 'jam_presensi_kelas' => 'required',
            // 'status_presensi_kelas' => 'required',
            'tanggal_pembuatan_booking_kelas' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $booking_presensi_kelas = booking_presensi_kelas::findOrFail($id);
        $booking_presensi_kelas->update([
            'id_member'  => $request->id_member,
            'id_jadwal_harian'  => $request->id_jadwal_harian,
            // 'jam_presensi_kelas'  => $request->jam_presensi_kelas,
            // 'status_presensi_kelas'  => $request->status_presensi_kelas,
            'tanggal_pembuatan_booking_kelas'  => $request->tanggal_pembuatan_booking_kelas
        ]);
        
        return new BookingPresensiKelasResource(true, 'Data Booking Kelas
        Berhasil Diubah!', $booking_presensi_kelas);
    }
}

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/login', 'App\Http\Controllers\Api\LoginController@index');
Route::get('/logout', 'App\Http\Controllers\Api\LoginController@logout');
Route::post('/jadwal_harian/init', 'App\Http\Controllers\JadwalHarianController@init')->name('jadwal_harian.init');
Route::put('/jadwal_harian/libur/{jadwal_harian}', 'App\Http\Controllers\JadwalHarianController@liburkan')->name('jadwal_harian.liburkan');
Route::put('/izin_instruktur/konfirmasi/{izin_instruktur}', 'App\Http\Controllers\IzinInstrukturController@konfirmasi')->name('izin_instruktur.konfirmasi');
Route::post('/izin_instruktur/search/{izin_instruktur}', 'App\Http\Controllers\IzinInstrukturController@search')->name('izin_instruktur.search');
Route::post('/izin_instruktur/getId', 'App\Http\Controllers\IzinInstrukturController@getId')->name('izin_instruktur.getId');

Route::put('/booking_presensi_gym/presensi/{booking_presensi_gym}', 'App\Http\Controllers\BookingPresensiGymController@presensi')->name('booking_presensi_gym.presensi');
Route::put('/booking_presensi_gym/tidakHadir/{booking_presensi_gym}', 'App\Http\Controllers\BookingPresensiGymController@tidakHadir')->name('booking_presensi_gym.tidakHadir');
Route::post('/booking_presensi_kelas/setHadir', 'App\Http\Controllers\BookingPresensiKelasController@setHadir')->name('bookingPresensiKelas.setHadir');
Route::post('/booking_presensi_kelas/setTidakHadir', 'App\Http\Controllers\BookingPresensiKelasController@setTidakHadir')->name('bookingPresensiKelas.setTidakHadir');

Route::post('/booking_presensi_kelas/getJadwalHarian', 'App\Http\Controllers\BookingPresensiKelasController@getJadwalHarian')->name('booking_presensi_kelas.getJadwalHarian');
Route::post('/booking_presensi_kelas/getDataBooking', 'App\Http\Controllers\BookingPresensiKelasController@getDataBooking')->name('booking_presensi_kelas.getDataBooking');
Route::post('/booking_presensi_kelas/getDataBookingInstruktur', 'App\Http\Controllers\BookingPresensiKelasController@getDataBookingInstruktur')->name('booking_presensi_kelas.getDataBookingInstruktur');

Route::post('/presensi_instruktur/getData', 'App\Http\Controllers\PresensiInstrukturController@getData')->name('presensi_instruktur.getData');
Route::post('/presensi_instruktur/setSelesai', 'App\Http\Controllers\PresensiInstrukturController@setSelesai')->name('presensi_instruktur.setSelesai');
Route::post('/deposit_kelas_member/getData', 'App\Http\Controllers\DepositKelasMemberController@getData')->name('deposit_kelas_member.getData');

Route::post('/booking_presensi_gym/getHistori', 'App\Http\Controllers\BookingPresensiGymController@getHistori')->name('booking_presensi_gym.getHistori');
Route::post('/booking_presensi_kelas/getHistori', 'App\Http\Controllers\BookingPresensiKelasController@getHistori')->name('booking_presensi_kelas.getHistori');
Route::post('/presensi_instruktur/getHistori', 'App\Http\Controllers\PresensiInstrukturController@getHistori')->name('presensi_instruktur.getHistori');

Route::post('/member/deaktivasiMember', 'App\Http\Controllers\MemberController@deaktivasiMember')->name('member.deaktivasiMember');
Route::post('/member/getDeactivated', 'App\Http\Controllers\MemberController@getDeactivated')->name('member.getDeactivated');
Route::post('/member/resetDeposit', 'App\Http\Controllers\MemberController@resetDeposit')->name('member.resetDeposit');
Route::post('/member/getResetDeposit', 'App\Http\Controllers\MemberController@getResetDeposit')->name('member.getResetDeposit');

Route::post('/laporan_aktivitas_gym/getLaporanAktivitasGym', 'App\Http\Controllers\LaporanAktivitasController@getLaporanAktivitasGym')->name('laporan_aktivitas_gym.getLaporanAktivitasGym');
Route::post('/laporan_pendapatan_bulanan/getLaporan', 'App\Http\Controllers\LaporanPendapatanController@getLaporan')->name('laporan_pendapatan_bulanan.getLaporan');
Route::post('/booking_presensi_kelas/getLaporanAktivitasKelas', 'App\Http\Controllers\BookingPresensiKelasController@getLaporanAktivitasKelas')->name('booking_presensi_kelas.getLaporanAktivitasKelas');
Route::post('/presensi_instruktur/getLaporanKinerjaInstruktur', 'App\Http\Controllers\PresensiInstrukturController@getLaporanKinerjaInstruktur')->name('presensi_instruktur.getLaporanKinerjaInstruktur');

Route::apiResource('/instruktur',
App\Http\Controllers\InstrukturController::class);

Route::apiResource('/member',
App\Http\Controllers\MemberController::class);

Route::apiResource('/jadwal_umum',
App\Http\Controllers\JadwalUmumController::class);

Route::apiResource('/kelas',
App\Http\Controllers\KelasController::class);

Route::apiResource('/pegawai',
App\Http\Controllers\PegawaiController::class);

Route::apiResource('/promo',
App\Http\Controllers\PromoController::class);

Route::apiResource('/jadwal_harian',
App\Http\Controllers\JadwalHarianController::class);

Route::apiResource('/transaksi_aktivasi_tahunan',
App\Http\Controllers\TransaksiAktivasiTahunanController::class);

Route::apiResource('/transaksi_deposit_uang',
App\Http\Controllers\TransaksiDepositUangController::class);

Route::apiResource('/transaksi_deposit_kelas',
App\Http\Controllers\TransaksiDepositKelasController::class);

Route::apiResource('/izin_instruktur',
App\Http\Controllers\IzinInstrukturController::class);

Route::apiResource('/booking_presensi_gym',
App\Http\Controllers\BookingPresensiGymController::class);

Route::apiResource('/booking_presensi_kelas',
App\Http\Controllers\BookingPresensiKelasController::class);

Route::apiResource('/deposit_kelas_member',
App\Http\Controllers\DepositKelasMemberController::class);

Route::apiResource('/presensi_instruktur',
App\Http\Controllers\PresensiInstrukturController::class);
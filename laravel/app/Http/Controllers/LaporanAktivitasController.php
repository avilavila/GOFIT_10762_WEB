<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\LaporanAktivitasResource;
use App\Models\laporan_aktivitas_gym; /* Import Model */
use App\Models\booking_presensi_gym; /* Import Model */
use App\Models\transaksi_deposit_kelas; /* Import Model */
use App\Models\member; /* Import Model */
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class LaporanAktivitasController extends Controller
{
        
    public function getLaporanAktivitasGym()
    {
        $truncate = laporan_aktivitas_gym::truncate();
        $bulan = Carbon::now()->formatLocalized('%B');
        $bulanSekarang = str_replace(
            ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
            $bulan
        );
        $tanggal = Carbon::now()->format('d');
        $tahun = Carbon::now()->format('Y');
        $month = Carbon::now()->format('m');
        $temp = 1;
        while($temp <= $tanggal){
            $compare = Carbon::createFromDate($tahun, $month, $temp)->toDateString();
            $jumlah_member = booking_presensi_gym::where('tanggal_booking_gym', $compare)
            ->where('status_presensi_gym', '!=', 'Belum Dipresensi')
            ->selectRaw('COUNT(id_member)')->value('COUNT(id_member)');
            $dataGabungan = implode(' ', [$temp, $bulanSekarang, $tahun]);
            $laporan = laporan_aktivitas_gym::create([
                'tanggal' => $dataGabungan,
                'jumlah_member' => $jumlah_member
            ]);
            $temp = $temp + 1;
        }

        $laporanGet = laporan_aktivitas_gym::get();
        return new LaporanAktivitasResource(true, 'List Data Laporan Aktivitas Gym didapatkan!', $laporanGet);
    }
}
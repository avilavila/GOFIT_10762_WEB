<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\LaporanPendapatanResource;
use App\Models\laporan_pendapatan_bulanan; /* Import Model */
use App\Models\transaksi_aktivasi_tahunan; /* Import Model */
use App\Models\transaksi_deposit_kelas; /* Import Model */
use App\Models\transaksi_deposit_uang; /* Import Model */
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class LaporanPendapatanController extends Controller
{
    /**
    * store
    *
    * @param Request $request
    * @return void
    */
    public function getLaporan(Request $request)
    {
        $truncate = laporan_pendapatan_bulanan::truncate();
        //Validasi Formulir
        $bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $temp = 0;
        $tempBulan = 1;

        while($temp < 12){
            $aktivasi = transaksi_aktivasi_tahunan::whereMonth('tanggal_aktivasi', '=', $tempBulan)
            ->whereYear('tanggal_aktivasi',$request->tahun)
            ->groupBy(transaksi_aktivasi_tahunan::raw('MONTH(tanggal_aktivasi)'))
            ->sum('total_bayar');
            $depositUang = transaksi_deposit_uang::whereMonth('tanggal_transaksi_deposit_uang', '=', $tempBulan)
            ->whereYear('tanggal_transaksi_deposit_uang',$request->tahun)
            ->groupBy(transaksi_deposit_uang::raw('MONTH(tanggal_transaksi_deposit_uang)'))
            ->sum('deposit_uang');
            $depositPaket = transaksi_deposit_kelas::whereMonth('tanggal_transaksi_deposit_kelas', '=', $tempBulan)
            ->whereYear('tanggal_transaksi_deposit_kelas',$request->tahun)
            ->groupBy(transaksi_deposit_kelas::raw('MONTH(tanggal_transaksi_deposit_kelas)'))
            ->sum('total_bayar');
            $laporan = laporan_pendapatan_bulanan::create([
                'bulan' => $bulan[$temp],
                'aktivasi' => $aktivasi,
                'deposit' => $depositUang + $depositPaket,
                'total' => $aktivasi + $depositUang + $depositPaket
            ]);
            $temp = $temp + 1;
            $tempBulan = $tempBulan + 1;
        }

        $laporanGet = laporan_pendapatan_bulanan::get();
        return new LaporanPendapatanResource(true, 'Data Laporan Bulanan Berhasil Dibuat!', $laporanGet);
    }
}
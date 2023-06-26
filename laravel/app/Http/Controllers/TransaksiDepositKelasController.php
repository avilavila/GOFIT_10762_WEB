<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\transaksi_deposit_kelas;
use App\Models\deposit_kelas_member;
use App\Models\promo;
use App\Models\member;
use App\Models\kelas;
use App\Models\pegawai;
use App\Http\Resources\TransaksiDepositKelasResource;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Carbon\Carbon;

class TransaksiDepositKelasController extends Controller
{
    /* index
    *
    * @return void
    */
    public function index()
    {
        $transaksi_deposit_kelas = transaksi_deposit_kelas::latest()->get();

        return new TransaksiDepositKelasResource(true, 'List Data Transaksi Deposit Paket Kelas', $transaksi_deposit_kelas);
    }

    public function show($id){
        $transaksi_deposit_kelas = transaksi_deposit_kelas::join('members', 'members.id_member', '=', 'transaksi_deposit_kelas.id_member')
        ->join('pegawais', 'pegawais.id_pegawai', '=', 'transaksi_deposit_kelas.id_pegawai')
        ->join('kelas', 'kelas.id_kelas', '=', 'transaksi_deposit_kelas.id_kelas')
        ->where('transaksi_deposit_kelas.no_struk_deposit_kelas', $id)
        ->select('transaksi_deposit_kelas.*','pegawais.nama_pegawai', 'members.nama_member', 'kelas.nama_kelas', 'kelas.harga_kelas')
        ->first();
        return new TransaksiDepositKelasResource(true,'Data Transaksi Deposit Kelas Paket Ditemukan', $transaksi_deposit_kelas);
    }

    public function destroy($id)
    {
        $transaksi_deposit_kelas =  transaksi_deposit_kelas::where('no_struk_deposit_kelas', $id)->delete();

        return new TransaksiDepositKelasResource(true,'Data Transaksi Deposit Kelas Berhasil dihapus!', $transaksi_deposit_kelas);
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
            'id_kelas' => 'required',
            'id_member' => 'required',
            'id_pegawai' => 'required',
            'tanggal_transaksi_deposit_kelas' => 'required',
            'deposit_kelas' => 'required'
        ]);
        //check inputan jumlah pembayaran
        
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        if($request->id_kelas == ""){
            return response()->json(['errors' => 'Harus pilih kelas!'],422);
        }
        $harga = Kelas::where('id_kelas', $request->id_kelas)
        ->value('harga_kelas');
        $check = $harga * $request->deposit_kelas;
        
        //check saldo member
        $saldo_deposit = Member::where([
            'id_member' => $request->id_member
        ])->value('saldo_deposit');
        if($saldo_deposit < $check){
            return response()->json(['errors' => 'Saldo tidak cukup!', 'data' => $saldo_deposit],422);
        }

        $jenis = Promo::where([
            'id_promo' => $request->id_promo
        ])->value('jenis_promo');
        $minimal = Promo::where([
            'id_promo' => $request->id_promo
        ])->value('minimal_transaksi');

        $idPromo = Promo::where('minimal_transaksi', $request->deposit_kelas)
        ->where('jenis_promo', 'Kelas Paket')
        ->value('id_promo');

        $bonus = Promo::where('minimal_transaksi', $request->deposit_kelas)
        ->where('jenis_promo', 'Kelas Paket')
        ->value('bonus');

        $id = IdGenerator::generate(['table' => 'transaksi_deposit_kelas', 'field' => 'no_struk_deposit_kelas', 'length' => 10, 'prefix' => date('y.m.')]);

        $inputDate = $request->tanggal_transaksi_deposit_kelas;

        $cekDeposit = deposit_kelas_member::where('id_member', $request->id_member)
        ->where('id_kelas', $request->id_kelas)
        ->value('deposit_paket_kelas');

        if($cekDeposit != '0'){
            return response()->json(['errors' => 'Member masih memiliki deposit kelas ini!', 'data' => $saldo_deposit],422);
        }

        if($request->deposit_kelas == '5'){
            $date = Carbon::parse($inputDate)->addMonth();
            $date->subDay();
        } else if($request->deposit_kelas == '10'){
            $date = Carbon::parse($inputDate)->addMonth();
            $date->addMonth();
            $date->subDay();
        }
        
        //Fungsi Simpan Data ke dalam Database
        $transaksi_deposit_kelas = transaksi_deposit_kelas::create([
            'no_struk_deposit_kelas' => $id,
            'id_kelas'  => $request->id_kelas, 
            'id_member'  => $request->id_member, 
            'id_pegawai'  => $request->id_pegawai, 
            'id_promo'  => $idPromo, 
            'tanggal_transaksi_deposit_kelas'  => $request->tanggal_transaksi_deposit_kelas,
            'deposit_kelas'  => $request->deposit_kelas,
            'bonus_deposit_kelas'  => $bonus,
            'jumlah_deposit_paket'  => $bonus + $request->deposit_kelas,
            'tanggal_berakhir_paket' => $date,
            'total_bayar' => $request->deposit_kelas * $harga
        ]);

        $saldo_deposit = Member::where([
            'id_member' => $request->id_member
        ])->value('saldo_deposit');

        $saldo_deposit = $saldo_deposit - ($request->deposit_kelas * $harga);

        $member = Member::where('id_member', $request->id_member)->update([
            'saldo_deposit'  => $saldo_deposit,
        ]);

        // $deposit_kelas_member = deposit_kelas_member::create([
        //     'id_deposit_member' => $request->id_deposit_member,
        //     'id_member'  => $request->id_member, 
        //     'id_kelas'  => $request->id_kelas, 
        //     'deposit_paket_kelas'  => $bonus + $request->deposit_kelas,
        //     'tanggal_kadaluarsa_kelas'  => $date
        // ]);


        return response()->json(['message' => 'success', 'data'=>$transaksi_deposit_kelas, 'saldo_deposit'=>$saldo_deposit],200);
    }
}

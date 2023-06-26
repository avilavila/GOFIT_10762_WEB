<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\transaksi_deposit_uang;
use App\Models\promo;
use App\Models\pegawai;
use App\Models\member;
use App\Http\Resources\TransaksiDepositUangResource;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class TransaksiDepositUangController extends Controller
{
/**
    * index
    *
    * @return void
    */
    public function index()
    {
        $transaksi_deposit_uang = transaksi_deposit_uang::latest()->get();

        return new TransaksiDepositUangResource(true, 'List Data Transaksi Deposit Uang', $transaksi_deposit_uang);
    }

    public function show($id){
        $transaksi_deposit_uang = transaksi_deposit_uang::join('members', 'members.id_member', '=', 'transaksi_deposit_uangs.id_member')
        ->join('pegawais', 'pegawais.id_pegawai', '=', 'transaksi_deposit_uangs.id_pegawai')
        ->where('transaksi_deposit_uangs.no_struk_deposit_uang', $id)
        ->select('transaksi_deposit_uangs.*','pegawais.nama_pegawai', 'members.nama_member')
        ->first();
        return new TransaksiDepositUangResource(true,'Data Transaksi Deposit Uang Ditemukan', $transaksi_deposit_uang);
    }

    public function destroy($id)
    {
        $transaksi_deposit_uang = transaksi_deposit_uang::where('no_struk_deposit_uang', $id)->delete();

        return new TransaksiDepositUangResource(true, 'Data Transaksi Deposit Uang Berhasil Dihapus!', $transaksi_deposit_uang);
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
            'id_pegawai' => 'required',
            'tanggal_transaksi_deposit_uang' => 'required',
            'deposit_uang' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        //cek role pegawai
        $role = Pegawai::where([
            'id_pegawai' => $request->id_pegawai
        ])->value('jabatan');

        if($role != "Kasir"){
            return response()->json(['errors' => 'Bukan Kasir! Yang melakukan transaksi hanya jabatan kasir..', 'data' => $role],422);
        }

        $jenis = Promo::where([
            'id_promo' => $request->id_promo
        ])->value('jenis_promo');
        $minimal = Promo::where([
            'id_promo' => $request->id_promo
        ])->value('minimal_transaksi');

        $id = IdGenerator::generate(['table' => 'transaksi_deposit_uangs', 'field' => 'no_struk_deposit_uang', 'length' => 10, 'prefix' => date('y.m.')]);
        
        if(!$request->id_promo){
            $bonus = 0;
        } else if ($jenis == "Kelas Paket"){
            return response()->json(['errors' => 'Promo ini hanya untuk Kelas Paket!'],422);
        } else {

            if($request->deposit_uang < $minimal){
                return response()->json(['errors' => 'Deposit harus sesuai minimal yang ditentukan untuk mendapatkan promo'],422);
            } else {
                $bonus = Promo::where([
                    'id_promo' => $request->id_promo
                ])->value('BONUS');
            } 
        }

        $saldo_deposit = Member::where([
            'id_member' => $request->id_member
        ])->value('saldo_deposit');
       
        //Fungsi Simpan Data ke dalam Database
        $transaksi_deposit_uang = transaksi_deposit_uang::create([
            'no_struk_deposit_uang' => $id,
            'id_promo' => $request->id_promo, 
            'id_pegawai' => $request->id_pegawai, 
            'id_member' => $request->id_member, 
            'tanggal_transaksi_deposit_uang'  => $request->tanggal_transaksi_deposit_uang,
            'deposit_uang'  => $request->deposit_uang,
            'bonus_deposit'  => $bonus,
            'sisa_deposit'  => $saldo_deposit,
            'total_deposit_uang'  => $bonus + $request->deposit_uang,
        ]);
        $saldo_deposit = $saldo_deposit + $bonus + $request->deposit_uang;

        $member = Member::where('id_member', $request->id_member)->update([
            'saldo_deposit'  => $saldo_deposit,
        ]);

        return response()->json(['message' => 'success', 'data'=>$transaksi_deposit_uang, 'saldo_deposit'=>$saldo_deposit],200);
    }
}

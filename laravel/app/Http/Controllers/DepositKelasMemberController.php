<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\deposit_kelas_member; /* Import Model */
use App\Http\Resources\DepositKelasMemberResource;
use Illuminate\Support\Facades\Validator;

class DepositKelasMemberController extends Controller
{
    /**
    * index
    *
    * @return void
    */
    public function index()
    {
        $deposit_kelas_member = deposit_kelas_member::latest()->get();

        return new DepositKelasMemberResource(true, 'List Data Deposit Kelas Member', $deposit_kelas_member);
    }

    public function show($id)
    {
        $deposit_kelas_member = deposit_kelas_member::find($id);
        

        return new DepositKelasMemberResource(true, 'Data Deposit Kelas Member ditemukan!', $deposit_kelas_member);
    }

    public function getData(Request $request){
        $deposit_kelas_member = deposit_kelas_member::join('kelas','kelas.id_kelas', '=', 'deposit_kelas_members.id_kelas')
        ->where('deposit_kelas_members.id_member', $request->id_member)
        ->where('deposit_kelas_members.tanggal_kadaluarsa_kelas', '>', '0001-01-01')
        ->select('deposit_kelas_members.*', 'kelas.nama_kelas')
        ->get();
        return new DepositKelasMemberResource(true,'Data Transaksi Deposit Kelas Paket Ditemukan', $deposit_kelas_member);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\transaksi_aktivasi_tahunan; /* Import Model */
use App\Models\pegawai;
use App\Models\member;
use App\Http\Resources\TransaksiAktivasiTahunanResource;
use Illuminate\Support\Facades\Validator;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Carbon\Carbon;

class TransaksiAktivasiTahunanController extends Controller
{
    /**
    * index
    *
    * @return void
    */
    public function index()
    {
        $transaksi_aktivasi_tahunan = transaksi_aktivasi_tahunan::latest()->get();

        return new TransaksiAktivasiTahunanResource(true, 'List Data Transaksi Aktivasi Tahunan', $transaksi_aktivasi_tahunan);
    }

    /**
    * create
    *
    * @return void
    */
    public function create()
    {
        return view('transaksi_aktivasi_tahunan.create');
    }

    public function show($id)
    {
        $transaksi_aktivasi_tahunan = transaksi_aktivasi_tahunan::join('members', 'members.id_member', '=', 'transaksi_aktivasi_tahunans.id_member')
        ->join('pegawais', 'pegawais.id_pegawai', '=', 'transaksi_aktivasi_tahunans.id_pegawai')
        ->where('transaksi_aktivasi_tahunans.no_struk_aktivasi_tahunan', $id)
        ->select('transaksi_aktivasi_tahunans.*','pegawais.nama_pegawai', 'members.nama_member')
        ->first();

        return new TransaksiAktivasiTahunanResource(true, 'Data Transaksi Aktivasi Tahunan ditemukan!', $transaksi_aktivasi_tahunan);
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
            'id_pegawai' => 'required',
            'tanggal_aktivasi' => 'required',
            'total_bayar' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $role = Pegawai::where([
            'id_pegawai' => $request->id_pegawai
        ])->value('jabatan');

        if($role != "Kasir"){
            return response()->json(['errors' => 'Bukan Kasir! Yang melakukan transaksi hanya jabatan kasir..', 'data' => $role],422);
        }

        $id = IdGenerator::generate(['table' => 'transaksi_aktivasi_tahunans', 'field' => 'no_struk_aktivasi_tahunan', 'length' => 10, 'prefix' => date('y.m.')]);
        
        $inputDate = $request->tanggal_aktivasi;

        $date = Carbon::parse($inputDate)->addYear();
        $date->subDay();

        //Fungsi Simpan Data ke dalam Database
        $transaksi_aktivasi_tahunan = transaksi_aktivasi_tahunan::create([
            'no_struk_aktivasi_tahunan'  => $id,
            'id_member'  => $request->id_member,
            'id_pegawai'  => $request->id_pegawai,
            'tanggal_aktivasi'  => $request->tanggal_aktivasi,
            'masa_berlaku_member'  => $date,
            'total_bayar'  => $request->total_bayar,
        ]);

        $member = Member::where('id_member', $request->id_member)->update([
            'tanggal_kadaluarsa_member' => $date
        ]);

        return new TransaksiAktivasiTahunanResource(true, 'Data Transaksi Aktivasi Tahunan Berhasil Ditambahkan', $transaksi_aktivasi_tahunan);
    }

     /**
     * destroy
     *
     * @param  mixed $id
     * @return void
     */
    public function destroy($id)
    {
        $transaksi_aktivasi_tahunan = transaksi_aktivasi_tahunan::where('no_struk_aktivasi_tahunan', $id)->delete();

        return new TransaksiAktivasiTahunanResource(true, 'Data Transaksi Aktivasi Tahunan Berhasil Dihapus!', $transaksi_aktivasi_tahunan);
    }

    public function edit($id)
    {
        // $transaksi_aktivasi_tahunan = transaksi_aktivasi_tahunan::findOrFail($id);
        // return view('transaksi_aktivasi_tahunan.edit', compact('transaksi_aktivasi_tahunan'));  
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
        // $validator = Validator::make($request->all(), [
        //     'id_member' => 'required',
        //     'id_pegawai' => 'required',
        //     'tanggal_aktivasi' => 'required',
        //     'masa_berlaku_member' => 'required',
        //     'total_bayar' => 'required',
        // ]);

        // if ($validator->fails()) {
        //     return response()->json($validator->errors(), 422);
        // }

        // $transaksi_aktivasi_tahunan = transaksi_aktivasi_tahunan::findOrFail($id);
        // $transaksi_aktivasi_tahunan->update([
        //     'id_member'  => $request->id_member,
        //     'id_pegawai'  => $request->id_pegawai,
        //     'tanggal_aktivasi'  => $request->tanggal_aktivasi,
        //     'masa_berlaku_member'  => $request->masa_berlaku_member,
        //     'total_bayar'  => $request->total_bayar,
        // ]);

        // return new TransaksiAktivasiTahunanResource(true, 'Data Transaksi Aktivasi Tahunan
        // Berhasil Diubah!', $transaksi_aktivasi_tahunan);
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member; /* Import Model */
use App\Models\deposit_kelas_member;
use App\Http\Resources\MemberResource;
use Illuminate\Support\Facades\Validator;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Carbon\Carbon;

class MemberController extends Controller
{
    /**
    * index
    *
    * @return void
    */
    public function index()
    {
        $member = Member::latest()->get();

        return new MemberResource(true, 'List Data Member', $member);
    }

    /**
    * create
    *
    * @return void
    */
    public function create()
    {
        return view('member.create');
    }

    public function show($id)
    {
        $member = Member::find($id);
        

        return new MemberResource(true, 'Data Member ditemukan!', $member);
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
            'nama_member' => 'required',
            'alamat_member' => 'required',
            'tanggal_lahir_member' => 'required',
            'telepon_member' => 'required',
            'email_member' => 'required',
            'username_member' => 'required',
            'password_member' => 'required',
            'tanggal_kadaluarsa_member' => 'required',
            'saldo_deposit' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $id = IdGenerator::generate(['table' => 'members', 'field' => 'id_member', 'length' => 9, 'prefix' => date('y.m.')]);
        
        //Fungsi Simpan Data ke dalam Database
        $member = Member::create([
            'id_member'  => $id,
            'nama_member'  => $request->nama_member,
            'alamat_member'  => $request->alamat_member,
            'tanggal_lahir_member'  => $request->tanggal_lahir_member,
            'telepon_member'  => $request->telepon_member,
            'email_member'  => $request->email_member,
            'username_member'  => $request->username_member,
            'password_member'  => $request->password_member,
            'tanggal_kadaluarsa_member' => $request->tanggal_kadaluarsa_member,
            'saldo_deposit' => $request->saldo_deposit,
        ]);

        return new MemberResource(true, 'Data Member Berhasil Ditambahkan', $member);
    }

    public function deaktivasiMember(){
        $today = Carbon::today();
        $member = Member::whereDate('tanggal_kadaluarsa_member', '<', $today)->update([
            'tanggal_kadaluarsa_member' => '0001-01-01'
        ]);

        $count = Member::where('tanggal_kadaluarsa_member', "0001-01-01")->get();

        return new MemberResource(true,'Data Berhasil Diubah', $count);
    }

    public function getDeactivated(){
        $today = Carbon::today();
        $formattedDate = $today->format('Y-m-d');
        $member = Member::where('tanggal_kadaluarsa_member', "0001-01-01")
        ->whereDate('updated_at', $formattedDate)->get();

        return new MemberResource(true,'Data Berhasil Diubah', $member);
    }

    public function resetDeposit(){
        $today = Carbon::today();
        $formattedDate = $today->format('Y-m-d');
        $deposit = deposit_kelas_member::where('tanggal_kadaluarsa_kelas', '<' , $formattedDate)
        ->where('tanggal_kadaluarsa_kelas', '>', '0001-01-01')->update([
            'deposit_paket_kelas' => 0,
            'tanggal_kadaluarsa_kelas' => '0001-01-01'
        ]);

        return new MemberResource(true,'Data Berhasil Diubah', $deposit);
    }

    public function getResetDeposit(){
        $today = Carbon::today();
        $formattedDate = $today->format('Y-m-d');
        $deposit = deposit_kelas_member::where('tanggal_kadaluarsa_kelas', "0001-01-01")
        ->where('deposit_paket_kelas', 0)
        ->whereDate('updated_at', $formattedDate)->get();

        return new MemberResource(true,'Data Berhasil Diubah', $deposit);
    }

     /**
     * destroy
     *
     * @param  mixed $id
     * @return void
     */
    public function destroy($id)
    {
        $member = Member::where('id_member', $id)->delete();

        return new MemberResource(true, 'Data Member
        Berhasil Dihapus!', $member);
    }

    public function edit($id)
    {
        $member = Member::findOrFail($id);
        return view('member.edit', compact('member'));  
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
            'nama_member' => 'required',
            'alamat_member' => 'required',
            'tanggal_lahir_member' => 'required',
            'telepon_member' => 'required',
            'email_member' => 'required',
            'username_member' => 'required',
            'password_member' => 'required',
            'tanggal_kadaluarsa_member' => 'required',
            'saldo_deposit' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $member = Member::findOrFail($id);
        $member->update([
            'nama_member'  => $request->nama_member,
            'alamat_member'  => $request->alamat_member,
            'tanggal_lahir_member'  => $request->tanggal_lahir_member,
            'telepon_member'  => $request->telepon_member,
            'email_member'  => $request->email_member,
            'username_member'  => $request->username_member,
            'password_member'  => $request->password_member,
            'tanggal_kadaluarsa_member' => $request->tanggal_kadaluarsa_member,
            'saldo_deposit' => $request->saldo_deposit,
        ]);

        return new MemberResource(true, 'Data Member
        Berhasil Diubah!', $member);
    }
}
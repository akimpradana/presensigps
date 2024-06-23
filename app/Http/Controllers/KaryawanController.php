<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Karyawan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class KaryawanController extends Controller
{
    public function index(request $request){
        $query = Karyawan::query();
        $query->select('karyawan.*', 'nama_jabatan');
        $query->join('jabatan', 'karyawan.kode_jbtn', '=', 'jabatan.kode_jbtn');
        $query->orderBy('nama_lengkap');
        //$query = Karyawan::orderBy('nama_lengkap');

        // Menerapkan filter jika 'nama_karyawan' ada dalam request
        if ($request->filled('nama_karyawan')) {
            $query->where('nama_lengkap', 'like', '%' . $request->nama_karyawan . '%');
        }
        $karyawan = $query->paginate(10);
        $jabatan = DB::table('jabatan')->get();
        return view('karyawan.index', compact('karyawan', 'jabatan'));
    }

    public function store(request $request){
        $nik =$request->nik;
        $nama_lengkap = $request->nama_lengkap;
        $kode_jbtn = $request->kode_jbtn;
        $no_hp = $request->no_hp;
        $password = Hash::make('12345');
        $remember_token = '';
        if($request->hasFile('foto')){
            $foto = $nik.".".$request->file('foto')->getClientOriginalExtension();
        }else{
            $foto = null;
        }

        try {
            $data = [
                'nik' =>$nik,
                'nama_lengkap' =>$nama_lengkap,
                'kode_jbtn' =>$kode_jbtn,
                'no_hp' =>$no_hp,
                'foto' =>$foto,
                'password' =>$password,
                'remember_token' => $remember_token
            ];
            $simpan = DB::table('karyawan')->insert($data);
            if ($simpan){
                if($request->hasFile('foto')){
                    $folderPath = "public/uploads/karyawan/";
                    $request->file('foto')->storeAs($folderPath, $foto);
                }
                return Redirect::back()->with(['success'=>'Data Berhasil Disimpan']);
            }
        } catch (\Exception $e) {
            //dd($e);
           return Redirect::back()->with(['warning'=>'Data Gagal Disimpan']);
        }

    }

    public function edit(request $request){
        $nik = $request->nil;
        $jabatan = DB::table('jabatan')->get();
        $karyawan = Karyawan::where('nik', $request->nik)->first();
        return view('karyawan.edit', compact('jabatan', 'karyawan'));
    }

    public function update($nik, Request $request){
        $nik =$request->nik;
        $nama_lengkap = $request->nama_lengkap;
        $kode_jbtn = $request->kode_jbtn;
        $no_hp = $request->no_hp;
        $password = Hash::make('12345');
        $remember_token = '';
        $old_foto=$request->old_foto;
        if($request->hasFile('foto')){
            $foto = $nik.".".$request->file('foto')->getClientOriginalExtension();
        }else{
            $foto = $old_foto;
        }

        try {
            $data = [
                'nama_lengkap' =>$nama_lengkap,
                'kode_jbtn' =>$kode_jbtn,
                'no_hp' =>$no_hp,
                'foto' =>$foto,
                'password' =>$password,
                'remember_token' => $remember_token
            ];
            $update = DB::table('karyawan')->where('nik',$nik)->update($data);
            if ($update){
                if($request->hasFile('foto')){
                    $folderPath = "public/uploads/karyawan/";
                    $folderPathOld = "public/uploads/karyawan/" .$old_foto ;
                    Storage::delete($folderPathOld);
                    $request->file('foto')->storeAs($folderPath, $foto);
                }
                return Redirect::back()->with(['success'=>'Data Berhasil Diupdate']);
            }
        } catch (\Exception $e) {
            //dd($e);
           return Redirect::back()->with(['warning'=>'Data Gagal Diupdate']);
        }
    }

    public function delete($nik){
        $delete = DB::table('karyawan')->where('nik',$nik)->delete();
        if($delete){
            return Redirect::back()->with(['success'=>'Data Berhasil Dihapus']);
        } else {
            return Redirect::back()->with(['Warning'=>'Data Gagal Dihapus']);
        }
    }
}

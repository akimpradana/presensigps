<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class JabatanController extends Controller
{
    public function index(){
        $jabatan = DB::table('jabatan')->orderBy('kode_jbtn')->get();
        return view ('jabatan.index', compact('jabatan'));
    }

    public function store( Request $request){
        $kode_jbtn = $request -> kode_jbtn;
        $nama_jabatan = $request -> nama_jabatan;
        $data =[
            'kode_jbtn' =>$kode_jbtn,
            'nama_jabatan' => $nama_jabatan
        ];

        $simpan = DB::table('jabatan')->insert($data);
        if ($simpan) {
            return Redirect::back()->with(['success' => 'Data Berhasil Disimpan']);
        }else{
            return Redirect::back()->with(['warning' => 'Data Gagal Disimpan']);
        }
    }

    public function edit(request $request){
        $kode_jbtn = $request ->kode_jbtn;
        $jabatan = DB::table('jabatan')->where('kode_jbtn',$kode_jbtn)->first();
        return view('jabatan.edit', compact('jabatan'));
    }

    public function update($kode_jbtn, Request $request){
        $nama_jabatan = $request->nama_jabatan;
        $data = [
            'nama_jabatan' =>$nama_jabatan
        ];
        $update = DB::table('jabatan')->where('kode_jbtn', $kode_jbtn)->update($data);
        if ($update) {
            return Redirect::back()->with(['success' => 'Data Berhasil Diupdate']);
        }else{
            return Redirect::back()->with(['warning' => 'Data Gagal Diupdate']);
        }
    }

    public function delete($kode_jbtn){
        $delete = DB::table('jabatan')->where('kode_jbtn',$kode_jbtn)->delete();
        if($delete){
            return Redirect::back()->with(['success'=>'Data Berhasil Dihapus']);
        } else {
            return Redirect::back()->with(['Warning'=>'Data Gagal Dihapus']);
        }
    }
}

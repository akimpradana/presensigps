<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class KonfigurasiController extends Controller
{
    public function lokasikantor(){
        $lok_kantor = DB::table('konfigurasi_lokasi')->where('id',1)->first();
        return view ('konfigurasi.lokasikantor', compact('lok_kantor'));
    }

    public function updatelokasikantor(Request $request){
        $lokasi_kantor = $request->lokasi_kantor;
        $radius = $request->radius;

        $update =DB::table('konfigurasi_lokasi')->where('id',1)->update([
            'lokasi_kantor' => $lokasi_kantor,
            'radius' => $radius
        ]);

        if ($update) {
            return Redirect::back()->with(['success' => 'Data Berhasil Diupdate']);
        }else{
            return Redirect::back()->with(['warning' => 'Data Gagal Diupdate']);
        }
    }
    public function jamkerja(){
        $jamKerja = DB::table('jam_kerja')->select('jam_masuk', 'jam_pulang')->first();
        return view ('konfigurasi.jamkerja', compact('jamKerja'));
    }

    public function updatejamkerja(Request $request){
        $jam_masuk = $request->jam_masuk;
        $jam_pulang = $request->jam_pulang;

        $update =DB::table('jam_kerja')->where('id',1)->update([
            'jam_masuk' => $jam_masuk,
            'jam_pulang' => $jam_pulang
        ]);

        if ($update) {
            return Redirect::back()->with(['success' => 'Data Berhasil Diupdate']);
        }else{
            return Redirect::back()->with(['warning' => 'Data Gagal Diupdate']);
        }
    }
}

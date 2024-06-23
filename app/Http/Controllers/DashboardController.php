<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Karyawan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        
        $hariini = date("Y-m-d");
        $bulanini = date("m") * 1;
        $tahunini = date("Y");
        $nik = auth::guard('karyawan')->user()->nik;
        $presensihariini=DB::table('presensi')->where('nik', $nik)->where('tgl_presensi', $hariini)->first();
        $historybulanini=DB::table('presensi')->whereRaw('MONTH(tgl_presensi)="' .$bulanini.'"')
            ->whereRaw('YEAR(tgl_presensi)="'.$tahunini.'"')  
            ->orderBy('tgl_presensi', 'desc')
            ->get();

        $namabulan = ["","Januari", "Februari", "Maret","April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", 
        "November", "Desember"];
        $karyawan = DB::table('karyawan')
            ->join('jabatan', 'karyawan.kode_jbtn', '=', 'jabatan.kode_jbtn')
            ->where('karyawan.nik', $nik)
            ->select('karyawan.*', 'jabatan.nama_jabatan')
            ->first();

        $jamkerja = DB::table('jam_kerja')->where('id', 1)->first();
        

        return view('dashboard.dashboard', compact('presensihariini','jamkerja', 'historybulanini', 'namabulan', 'bulanini', 'tahunini', 'karyawan'));
    }

    public function dashboardadmin(){
        $jamKerja = DB::table('jam_kerja')->select('jam_masuk', 'jam_pulang')->first();
        
        $hariini = date("Y-m-d");
        $rekappresensi = DB::table('presensi')
            ->selectRaw('
                SUM(IF(jam_in IS NOT NULL, 1, 0)) as jmlhadir, 
                SUM(IF(jam_in > ?, 1, 0)) as jmlterlambat', 
                [$jamKerja->jam_masuk]
            )
            ->where('tgl_presensi', $hariini)
            ->first();

        $rekapizin = DB::table('presensi')
            ->selectRaw('
                SUM(IF(status = "i", 1, 0)) as jmlizin, 
                SUM(IF(status = "s", 1, 0)) as jmlsakit'
            )
            ->where('tgl_presensi', $hariini)
            ->first();
        return view('dashboard.dashboardadmin', compact('rekappresensi','rekapizin'));
    }
}



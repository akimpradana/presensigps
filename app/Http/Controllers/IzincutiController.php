<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class IzincutiController extends Controller
{
    public function create(){
        $mastercuti = DB::table('master_cuti')->orderBy('kode_cuti')->get();
        return view('izincuti.create', compact('mastercuti'));
    }

    public function store(Request $request)
{
    $nik = auth::guard('karyawan')->user()->nik;
    $tgl_izin_dari = $request->tgl_izin_dari;
    $tgl_izin_sampai = $request->tgl_izin_sampai;
    $kode_cuti = $request->kode_cuti;
    $status = "c";
    $keterangan = $request->keterangan;

    $bulan = date("m", strtotime($tgl_izin_dari));
    $tahun = date("Y", strtotime($tgl_izin_sampai));
    $thn = substr($tahun, 2, 2);

    $lastizin = DB::table('pengajuan_izin')
        ->whereMonth('tgl_izin_dari', $bulan)
        ->whereYear('tgl_izin_dari', $tahun)
        ->orderBy('kode_izin', 'desc')
        ->first();

    $lastkodeizin = $lastizin ? $lastizin->kode_izin : null;
    $format = "IZ" . $bulan . $thn;
    $kode_izin = buatkode($lastkodeizin, $format, 3);  // Pastikan fungsi buatkode() ada dan berfungsi
    $validator = Validator::make($request->all(), [
        'tgl_izin_dari' => 'required|date|after_or_equal:today',
        'tgl_izin_sampai' => 'required|date|after_or_equal:today',
        'kode_cuti' => 'required',
        'keterangan' => 'required|string',
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    $data = [
        'kode_izin' => $kode_izin,
        'nik' => $nik,
        'tgl_izin_dari' => $tgl_izin_dari,
        'tgl_izin_sampai' => $tgl_izin_sampai,
        'kode_cuti' =>$kode_cuti,
        'status' => $status,
        'keterangan' => $keterangan
    ];

    $simpan = DB::table('pengajuan_izin')->insert($data);
    if ($simpan) {
        return redirect('/presensi/izin')->with(['success' => 'Data Berhasil Disimpan']);
    } else {
        return redirect('/presensi/izin')->with(['error' => 'Data Gagal Disimpan']);
    }
}
}

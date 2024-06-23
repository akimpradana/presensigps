<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage; 
use App\Models\Karyawan;
use App\Models\Presensi;


class PresensiController extends Controller
{
    public function create(){
        $hariini = date("Y-m-d");
        $nik =Auth::guard('karyawan')->user()->nik;
        $cek = DB::table('presensi')->where('tgl_presensi', $hariini)->where('nik', $nik)->count();
        $lok_kantor = DB::table('konfigurasi_lokasi')->where('id',1)->first();
        return view('presensi.create', compact('cek', 'lok_kantor'));
    }

    public function store(Request $request){
        $nik = Auth::guard('karyawan')->user()->nik;
        $tgl_presensi = date("Y-m-d");
        $jam = date("H:i:s");
        $jamKerja = DB::table('jam_kerja')->select('jam_pulang')->first();
        $lok_kantor = DB::table('konfigurasi_lokasi')->where('id', 1)->first();
        $lok = explode(",", $lok_kantor->lokasi_kantor);
        $latitudekantor = $lok[0];
        $longitudekantor = $lok[1];
        $lokasi = $request->lokasi;
        $lokasiuser = explode(",", $lokasi);
        $latitudeuser = $lokasiuser[0];
        $longitudeuser = $lokasiuser[1];
    
        $jarak = $this->distance($latitudekantor, $longitudekantor, $latitudeuser, $longitudeuser);
        $radius = round($jarak["meters"]);
    
        if ($radius > $lok_kantor->radius) {
            echo "0|Maaf Anda Berada Diluar Radius|coba";
        } else {
            // Check if already checked out today
            $presensi_pulang = DB::table('presensi')
                                ->where('tgl_presensi', $tgl_presensi)
                                ->where('nik', $nik)
                                ->first();
    
            if ($presensi_pulang && $presensi_pulang->jam_out !== null) {
                echo "0|Maaf Anda sudah melakukan absen pulang hari ini|coba";
            } elseif ($presensi_pulang && $presensi_pulang->jam_out === null) {
                $jam_sekarang = date("H:i:s");
                $jam_pulang = $jamKerja->jam_pulang;// jam pulang sore
            
                if ($jam_sekarang < $jam_pulang) {
                    echo "0|Maaf, Anda belum bisa melakukan absen pulang sebelum jam $jam_pulang sore|coba";
                } else {
                    // Update data pulang
                    $data_pulang = [
                        'jam_out' => $jam,
                        'lokasi_out' => $lokasi
                    ];
            
                    $update = DB::table('presensi')
                               ->where('tgl_presensi', $tgl_presensi)
                               ->where('nik', $nik)
                               ->update($data_pulang);
            
                    if ($update) {
                        echo "1|Terimakasih, Hati Hati Di Jalan|out";
                    } else {
                        echo "0|Maaf Absensi Gagal, Silahkan Ulangi Kembali woy";
                    }
                }
            } else {
                // Insert data masuk
                $data_masuk = [
                    'nik' => $nik,
                    'tgl_presensi' => $tgl_presensi,
                    'jam_in' => $jam,
                    'lokasi_in' => $lokasi,
                    'status'=>'h'
                ];
    
                $simpan = DB::table('presensi')->insert($data_masuk);
                if ($simpan) {
                    echo "1|Terimakasih, Selamat Bekerja|in";
                } else {
                    echo "0|Maaf Absensi Gagal, Silahkan Ulangi Kembali|in";
                }
            }
        }
    }

    //Menghitung Jarak
    function distance($lat1, $lon1, $lat2, $lon2)
    {
        $theta = $lon1 - $lon2;
        $miles = (sin(deg2rad($lat1)) * sin(deg2rad($lat2))) + (cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)));
        $miles = acos($miles);
        $miles = rad2deg($miles);
        $miles = $miles * 60 * 1.1515;
        $feet = $miles * 5280;
        $yards = $feet / 3;
        $kilometers = $miles * 1.609344;
        $meters = $kilometers * 1000;
        return compact('meters');
    }

    public function editprofile(){
        $nik = auth::guard('karyawan')->user()->nik;
        $karyawan = DB::table('karyawan')->where('nik', $nik)->first();
        return view('presensi.editprofile', compact('karyawan'));

    }
    public function updatefile(Request $request)
{
    $nik = Auth::guard('karyawan')->user()->nik;
    $nama_lengkap = $request->nama_lengkap;
    $no_hp = $request->no_hp;
    $foto = null; // Inisialisasi variabel foto

    // Jika ada file foto yang diunggah, proses nama file dan simpan
    if ($request->hasFile('foto')) {
        $foto = $nik . "." . $request->file('foto')->getClientOriginalExtension();
        $folderPath = "public/uploads/karyawan/";
        $request->file('foto')->storeAs($folderPath, $foto);
    } else {
        // Jika tidak ada file foto yang diunggah, gunakan foto yang sudah ada
        $karyawan = DB::table('karyawan')->where('nik', $nik)->first();
        $foto = $karyawan->foto;
    }

    // Inisialisasi array data untuk update
    $data = [
        'nama_lengkap' => $nama_lengkap,
        'no_hp' => $no_hp,
        'foto' => $foto
    ];

    // Jika password tidak kosong dan sesuai dengan konfirmasi password, tambahkan password ke dalam data
    

    if (!empty($request->password) && $request->password === $request->password_confirmation) {
        $password = Hash::make($request->password);
        $data['password'] = $password;
    } elseif (!empty($request->password) && $request->password !== $request->password_confirmation) {
        return Redirect::back()->with(['error' => 'Password dan Konfirmasi Password tidak cocok']);
    }

    // Lakukan update data karyawan
    $update = DB::table('karyawan')->where('nik', $nik)->update($data);

    // Periksa apakah update berhasil atau gagal
    if ($update) {
        return Redirect::back()->with(['success' => 'Data Berhasil Diupdate']);
    } else {
        return Redirect::back()->with(['error' => 'Data Gagal Diupdate']);
    }
}

    public function histori(){
        $namabulan = ["","Januari", "Februari", "Maret","April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", 
        "November", "Desember"];
        return view('presensi.histori', compact('namabulan'));
    }

    public function gethistori(Request $request){
        $bulan = $request -> bulan;
        $tahun = $request -> tahun;
        $jamkerja = DB::table('jam_kerja')->where('id', 1)->first();
        $nik = Auth::guard('karyawan')->user()->nik;

        $histori = DB::table('presensi')
        ->whereRaw('MONTH(tgl_presensi)="'.$bulan.'"')
        ->whereRaw('YEAR(tgl_presensi)="'.$tahun.'"')
        ->where('nik',$nik)
        ->orderBy('tgl_presensi')
        ->get();

        return view ('presensi.gethistori', compact('histori','jamkerja'));
    }
    

    public function izin(){
        $nik = auth::guard('karyawan')->user()->nik;
        $dataizin = DB::table('pengajuan_izin')
        ->leftjoin('master_cuti', 'pengajuan_izin.kode_cuti', '=', 'master_cuti.kode_cuti')
        ->where('nik', $nik)->get();
        return view('presensi.izin', compact('dataizin'));
    }

    public function buatizin(){
        
        return view('presensi.buatizin');
    }

    public function storeizin(Request $request){
        $nik = auth::guard('karyawan')->user()->nik;
        $tgl_izin = $request ->tgl_izin;
        $status = $request ->status;
        $keterangan = $request ->keterangan;

        $data =[
            'nik' => $nik,
            'tgl_izin' => $tgl_izin,
            'status' => $status,
            'keterangan' => $keterangan
        ];


        $simpan = DB::table('pengajuan_izin')->insert($data);
        if($simpan){
            return Redirect('/presensi/izin')->with(['success'=>'Data Berhasil Disimpan']);
        }else{
            return Redirect('/presensi/izin')->with(['error'=>'Data Gagal Disimpan']);
        }
        // dd($request->all());

        
    }

    public function monitoring(){
        return view('presensi.monitoring');
    }

    public function getpresensi(Request $request)
    {
        $tanggal = $request->tanggal;
        $jamKerja = DB::table('jam_kerja')->select('jam_masuk', 'jam_pulang')->first();
        $presensi = DB::table('presensi')
            ->select('presensi.*', 'karyawan.nama_lengkap', 'jabatan.nama_jabatan', 'karyawan.kode_jbtn')
            ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
            ->join('jabatan', 'karyawan.kode_jbtn', '=', 'jabatan.kode_jbtn')
            ->where('tgl_presensi', $tanggal)
            ->get();

        return view('presensi.getpresensi', compact('presensi', 'jamKerja'));
    }

    public function izinsakit(){
        $izinsakit = DB::table('pengajuan_izin')
        ->join('karyawan', 'pengajuan_izin.nik', '=', 'karyawan.nik')
        ->join('jabatan', 'karyawan.kode_jbtn', '=', 'jabatan.kode_jbtn')
        ->orderBy('tgl_izin_dari', 'desc')
        ->paginate(10);
        return view('presensi.izinsakit', compact('izinsakit'));
    }

    public function approveizinsakit(Request $request){
        $status_approval = $request -> status_approval;
        $kode_izin = $request ->kode_izin_form;
        $dataizin = DB::table('pengajuan_izin')->where('kode_izin', $kode_izin)->first();
        $nik = $dataizin->nik;
        $tgl_dari = $dataizin->tgl_izin_dari;
        $tgl_sampai = $dataizin->tgl_izin_sampai;
        $status = $dataizin->status;
        DB::beginTransaction() ;

        try {
            if ($status_approval == 1) {
                while (strtotime($tgl_dari) <= strtotime($tgl_sampai)) {
                    DB::table('presensi')->insert([
                        'nik' => $nik,
                        'tgl_presensi' => $tgl_dari,
                        'status' => $status,
                        'kode_izin' => $kode_izin
                    ]);
                    $tgl_dari = date("Y-m-d", strtotime("+1 days", strtotime($tgl_dari)));
                }
            }
    
            DB::table('pengajuan_izin')->where('kode_izin', $kode_izin)->update([
                'status_approval' => $status_approval
            ]);
    
            DB::commit(); // Komit transaksi jika tidak ada error
            return Redirect::back()->with(['success' => 'Data Berhasil Diproses']);
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaksi jika terjadi error
            return Redirect::back()->with(['warning' => 'Data Gagal Diproses: ' . $e->getMessage()]);
        }

    }

    public function batalkanizin($kode_izin){
        DB::beginTransaction();
        try {
            $update =DB::table('pengajuan_izin')->where('kode_izin',$kode_izin)->update([
                'status_approval' => 0
            ]);
            DB::table('presensi')->where('kode_izin',$kode_izin)->delete();
            DB::commit();
            return Redirect::back()->with(['success'=>'Data Berhasil Dibatalkan']);
        } catch (\Exception $e) {
            return Redirect::back()->with(['warning'=>'Data Gagal Dibatalkan']);
        }
    }

    public function cekpengajuanizin(Request $request)
    {
        $tgl_izin_dari = $request->tgl_izin_dari;
        $tgl_izin_sampai = $request->tgl_izin_sampai;

        $exists = DB::table('presensi')
                    ->whereBetween('tgl_presensi', [$tgl_izin_dari, $tgl_izin_sampai])
                    ->where('status', '!=', null)
                    ->exists();

        return response()->json(['exists' => $exists]);
    }

    public function showact($kode_izin){
        $dataizin =DB::table('pengajuan_izin')->where('kode_izin', $kode_izin)->first();
        return view('presensi.showact', compact('dataizin'));
    }

    public function deleteizin($kode_izin) {
        $cekdataizin = DB::table('pengajuan_izin')->where('kode_izin', $kode_izin)->first();
        $doc_sid = $cekdataizin->doc_sid;

        try {
            DB::table('pengajuan_izin')->where('kode_izin', $kode_izin)->delete();

            if ($doc_sid != null) {
                dd(Storage::delete('/public/upload/sid/'.$doc_sid));
            }
            return redirect('/presensi/izin')->with(['success' => 'Data Berhasil Dihapus']);
        } catch (\Exception $e) {
            return redirect('/presensi/izin')->with(['error' => 'Data Gagal Dihapus']);
        }
    }

    
    public function rekap(Request $request) {
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
    
        if (empty($bulan)) {
            $bulan = date('m');
        }
        if (empty($tahun)) {
            $tahun = date('Y');
        }
    
        $startDate = "{$tahun}-{$bulan}-01";
        $endDate = date("Y-m-t", strtotime($startDate));
    
        // Query untuk mengambil jam masuk dan jam pulang dari tabel jam_kerja
        $jamKerja = DB::table('jam_kerja')->select('jam_masuk', 'jam_pulang')->first();
        $jamMasuk = $jamKerja->jam_masuk;
        $jamPulang = $jamKerja->jam_pulang;
    
        $selectFields = [
            'k.nik',
            'k.nama_lengkap',
            DB::raw("SUM(CASE WHEN TIME(p.jam_in) > '{$jamMasuk}' THEN 1 ELSE 0 END) AS total_terlambat"),
            DB::raw("SEC_TO_TIME(SUM(TIME_TO_SEC(CASE WHEN TIME(p.jam_out) > '{$jamPulang}' THEN TIMEDIFF(p.jam_out, '{$jamPulang}') ELSE '00:00:00' END))) AS total_lembur")
        ];
    
        for ($i = 1; $i <= 31; $i++) {
            $day = str_pad($i, 2, '0', STR_PAD_LEFT);
            $selectFields[] = DB::raw("MAX(IF(DAY(p.tgl_presensi) = {$i}, 
                IF(p.jam_in IS NOT NULL, 
                    IF(TIME(p.jam_in) <= '{$jamMasuk}', 'H', 'H_late'), 
                    IF(p.status = 'i', 'I', 
                        IF(p.status = 's', 'S', 
                            IF(p.status = 'c', 'C', 'N')))), NULL)) AS tgl_{$i}");
        }
    
        $rekap = DB::table('karyawan as k')
            ->select($selectFields)
            ->leftJoin('presensi as p', function($join) use ($startDate, $endDate) {
                $join->on('k.nik', '=', 'p.nik')
                    ->whereBetween('p.tgl_presensi', [$startDate, $endDate]);
            })
            ->groupBy('k.nik', 'k.nama_lengkap')
            ->orderBy('k.nik')
            ->get();
    
        if (isset($_POST['exportexcel'])) {
            $time = date('d-M-Y H:i:s');
            header("Content-type: application/vnd-ms-excel");
            header("Content-Disposition: attachment; filename=Rekap Presensi Karyawan $time.xls");
        }
    
        return view('presensi.rekap', compact('bulan', 'tahun', 'namabulan', 'rekap'));
    }
    
    
    
    
    
    
    public function cetakrekap(Request $request) {
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
    
        if (empty($bulan)) {
            $bulan = date('m');
        }
        if (empty($tahun)) {
            $tahun = date('Y');
        }
    
        $startDate = "{$tahun}-{$bulan}-01";
        $endDate = date("Y-m-t", strtotime($startDate));
    
        // Query untuk mengambil jam masuk dan jam pulang dari tabel jam_kerja
        $jamKerja = DB::table('jam_kerja')->select('jam_masuk', 'jam_pulang')->first();
        $jamMasuk = $jamKerja->jam_masuk;
        $jamPulang = $jamKerja->jam_pulang;
    
        $selectFields = [
            'k.nik',
            'k.nama_lengkap',
            DB::raw("SUM(CASE WHEN TIME(p.jam_in) > '{$jamMasuk}' THEN 1 ELSE 0 END) AS total_terlambat"),
            DB::raw("SEC_TO_TIME(SUM(TIME_TO_SEC(CASE WHEN TIME(p.jam_out) > '{$jamPulang}' THEN TIMEDIFF(p.jam_out, '{$jamPulang}') ELSE '00:00:00' END))) AS total_lembur")
        ];
    
        for ($i = 1; $i <= 31; $i++) {
            $day = str_pad($i, 2, '0', STR_PAD_LEFT);
            $selectFields[] = DB::raw("MAX(IF(DAY(p.tgl_presensi) = {$i}, 
                IF(p.jam_in IS NOT NULL, 
                    IF(TIME(p.jam_in) <= '{$jamMasuk}', 'H', 'H_late'), 
                    IF(p.status = 'i', 'I', 
                        IF(p.status = 's', 'S', 
                            IF(p.status = 'c', 'C', 'N')))), NULL)) AS tgl_{$i}");
        }
    
        $rekap = DB::table('karyawan as k')
            ->select($selectFields)
            ->leftJoin('presensi as p', function($join) use ($startDate, $endDate) {
                $join->on('k.nik', '=', 'p.nik')
                    ->whereBetween('p.tgl_presensi', [$startDate, $endDate]);
            })
            ->groupBy('k.nik', 'k.nama_lengkap')
            ->orderBy('k.nik')
            ->get();
    
        if (isset($_POST['exportexcel'])) {
            $time = date('d-M-Y H:i:s');
            header("Content-type: application/vnd-ms-excel");
            header("Content-Disposition: attachment; filename=Rekap Presensi Karyawan $time.xls");
        }
    
        return view('presensi.cetakrekap', compact('bulan', 'tahun', 'namabulan', 'rekap'));
    }
    

    public function presensikaryawan(Request $request){
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
        return view('presensi.presensikaryawan', compact('karyawan', 'jabatan'));
    }

    public function detail($nik, Request $request) {
        $tanggal = $request->tanggal;
        $jamKerja = DB::table('jam_kerja')->select('jam_masuk', 'jam_pulang')->first();
    
        // Extract month and year from the provided date
        $month = date('m', $tanggal);
        $year = date('Y', $tanggal);
    
        // Mengambil data karyawan berdasarkan NIK
        $karyawan = Karyawan::where('nik', $nik)
        ->join('jabatan', 'karyawan.kode_jbtn', '=', 'jabatan.kode_jbtn')
        ->first();
        
        // Memeriksa apakah data karyawan ditemukan
        if (!$karyawan) {
            abort(404, 'Karyawan tidak ditemukan');
        }
    
        // Mengambil data presensi karyawan berdasarkan NIK dan filter berdasarkan bulan dan tahun
        $presensi = DB::table('presensi')
            ->select('presensi.*', 'karyawan.nama_lengkap', 'jabatan.nama_jabatan', 'karyawan.kode_jbtn')
            ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
            ->join('jabatan', 'karyawan.kode_jbtn', '=', 'jabatan.kode_jbtn')
            ->where('presensi.nik', $nik) // Filter berdasarkan NIK karyawan
            ->whereMonth('presensi.tgl_presensi', $month) // Filter berdasarkan bulan
            ->whereYear('presensi.tgl_presensi', $year) // Filter berdasarkan tahun
            ->orderBy('presensi.tgl_presensi', 'asc')
            ->get();
    
        return view("presensi.detail", compact('karyawan', 'presensi', 'jamKerja'));
    }

    
}

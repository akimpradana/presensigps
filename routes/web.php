<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\KonfigurasiController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\IzinabsenController;
use App\Http\Controllers\IzinsakitController;
use App\Http\Controllers\CutiController;
use App\Http\Controllers\IzincutiController;
use Illuminate\Notifications\HasApiTokens;


Route::middleware(['guest:karyawan'])->group(function(){
    Route::get('/', function () {
        return view('auth.login');
    })->name('login');
    route::post('/proseslogin',[AuthController::class, 'proseslogin']);
});

Route::middleware(['guest:user'])->group(function(){
    Route::get('/panel', function () {
        return view('auth.loginadmin');
    })->name('loginadmin');
    route::post('/prosesloginadmin',[AuthController::class, 'prosesloginadmin']);
});

route::middleware(['auth:karyawan'])->group(function(){
    route::get('/dashboard', [DashboardController::class, 'index']);
    route::get('/proseslogout',[AuthCOntroller::class, 'proseslogout']);

    //presensi
    Route::get('/presensi/create',[PresensiController::class,'create']);
    Route::POST('/presensi/store', [PresensiController::class, 'store']);

    //Edit Profile
    Route::get('/editprofile',[PresensiController::class,'editprofile']);
    Route::post('/presensi/{nik}/updatefile',[PresensiController::class,'updatefile']);

    //histori
    Route::get('/presensi/histori',[PresensiController::class,'histori']);
    Route::post('/gethistori', [PresensiController::class, 'getHistori'])->name('gethistori');

    //izin
    Route::get('/presensi/izin', [PresensiController::class, 'izin']);
    Route::get('/presensi/buatizin', [PresensiController::class, 'buatizin']);
    Route::post('/presensi/storeizin', [PresensiController::class, 'storeizin']);
    Route::post('/presensi/cekpengajuanizin', [PresensiController::class, 'cekpengajuanizin']);

    //izinabsen
    Route::get('/izinabsen', [IzinabsenController::class, 'create']);
    Route::post('/izinabsen/store', [IzinabsenController::class, 'store']);

    //izinsakit
    Route::get('/izinsakit', [IzinsakitController::class, 'create']);
    Route::post('/izinsakit/store', [IzinsakitController::class, 'store']);

    //izincuti
    Route::get('/izincuti', [IzincutiController::class, 'create']);
    Route::post('/izincuti/store', [IzincutiController::class, 'store']);

    Route::get('/izin/{kode_izin}/showact', [PresensiController::class, 'showact']);
    Route::get('/izin/{kode_izin}/delete', [PresensiController::class, 'deleteizin']);
});

Route::middleware(['auth:user'])->group(function(){
    route::get('/proseslogoutadmin',[AuthCOntroller::class, 'proseslogoutadmin']);
    Route::get('/panel/dashboardadmin', [DashboardController::class, 'dashboardadmin']);

    //karyawan
    Route::get('/karyawan',[KaryawanController::class, 'index']);
    Route::POST('/karyawan/store',[KaryawanController::class, 'store']);
    Route::POST('/karyawan/edit',[KaryawanController::class, 'edit']);
    Route::POST('/karyawan/{nik}/update',[KaryawanController::class, 'update']);
    Route::POST('/karyawan/{nik}/delete',[KaryawanController::class, 'delete']);

    //presensi monitoring
    Route::get('/presensi/monitoring', [PresensiController::class, 'monitoring']);
    Route::post('/getpresensi', [PresensiController::class, 'getpresensi']);
    Route::get('/presensi/izinsakit', [PresensiController::class, 'izinsakit']);
    Route::POST('/presensi/approveizinsakit', [PresensiController::class, 'approveizinsakit']);
    Route::get('/presensi/{kode_izin}/batalkanizin', [PresensiController::class, 'batalkanizin']);
    Route::get('/presensi/rekap', [PresensiController::class, 'rekap']);
    Route::POST('/presensi/cetakrekap', [PresensiController::class, 'cetakrekap']);
    Route::get('/presensi/presensikaryawan', [PresensiController::class, 'presensikaryawan']);
    Route::get('/presensi/{nik}/detail', [PresensiController::class, 'detail']);

    Route::post('/presensi/rekap', [PresensiController::class, 'rekap'])->name('presensi.rekap');

    //konfigurasi kantor dan jam kerja
    Route::get('/konfigurasi/lokasikantor', [KonfigurasiController::class, 'lokasikantor']);
    Route::POST('/konfigurasi/updatelokasikantor', [KonfigurasiController::class, 'updatelokasikantor']);
    Route::get('/konfigurasi/jamkerja', [KonfigurasiController::class, 'jamkerja']);
    Route::POST('/konfigurasi/updatejamkerja', [KonfigurasiController::class, 'updatejamkerja']);

    //jabatan
    Route::get('/jabatan', [JabatanController::class, 'index']);
    Route::POST('/jabatan/store', [JabatanController::class, 'store']);
    Route::POST('/jabatan/edit', [JabatanController::class, 'edit']);
    Route::POST('/jabatan/{kode_jbtn}/update', [JabatanController::class, 'update']);
    Route::POST('/jabatan/{kode_jbtn}/delete', [JabatanController::class, 'delete']);

    //cuti
    Route::get('/cuti', [CutiController::class, 'index']);
    Route::POST('/cuti/store', [CutiController::class, 'store']);
    Route::POST('/cuti/edit', [CutiController::class, 'edit']);
    Route::POST('/cuti/{kode_cuti}/update', [CutiController::class, 'update']);
    Route::POST('/cuti/{kode_cuti}/delete', [CutiController::class, 'delete']);
});

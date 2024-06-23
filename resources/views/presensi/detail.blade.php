@extends('layouts.admin.tabler')
@section('content')
@php
function selisih($jam_masuk, $jam_keluar){
    // Memecah waktu masuk menjadi jam, menit, dan detik
    list($h1, $m1, $s1) = explode(":", $jam_masuk);
    // Memecah waktu keluar menjadi jam, menit, dan detik
    list($h2, $m2, $s2) = explode(":", $jam_keluar);
    
    // Mengonversi waktu masuk ke dalam detik sejak Epoch
    $dtAwal = mktime($h1, $m1, $s1, "1", "1", "1");
    // Mengonversi waktu keluar ke dalam detik sejak Epoch
    $dtAkhir = mktime($h2, $m2, $s2, "1", "1", "1");
    
    // Menghitung selisih waktu dalam detik
    $dtSelisih = $dtAkhir - $dtAwal;
    
    // Mengonversi selisih waktu ke jam, menit, dan detik
    $jam = floor($dtSelisih / 3600);
    $menit = floor(($dtSelisih % 3600) / 60);
    $detik = $dtSelisih % 60;
    
    // Mengembalikan hasil dalam format "jam:menit:detik"
    return sprintf("%02d:%02d:%02d", $jam, $menit, $detik);
}
@endphp
<style>
    .badge-primary-white-text {
    color: white !important;
}
</style>
<!-- Page header -->
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <!-- Page pre-title -->
                <h2 class="page-title">
                    Detail Presensi
                </h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="input-icon">
                                    
                                    <h3>Detail Karyawan</h3>
                                    <p><strong>NIK:</strong> {{ $karyawan->nik }}</p>
                                    <p><strong>Nama Lengkap:</strong> {{ $karyawan->nama_lengkap }}</p>
                                    <p><strong>Jabatan:</strong>{{ $karyawan->nama_jabatan }} </p>
                                                    
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>NIK</th>
                                            <th>Nama Karyawan</th>
                                            <th>Jabatan</th>
                                            <th>Tanggal presensi</th>
                                            <th>Jam Masuk</th>
                                            <th>Jam Pulang</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($presensi as $d)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{$d->nik}}</td>
                                            <td>{{$d->nama_lengkap}}</td>
                                            <td>{{$d->nama_jabatan}}</td>
                                            <td>{{$d->tgl_presensi}}</td>
                                            <td>
                                                @if($d->jam_in != null)
                                                    {{$d->jam_in}}
                                                @else
                                                    @if($d->status == 'I')
                                                        <span class="badge bg-warning badge-primary-white-text">Izin Absen</span>
                                                    @elseif($d->status == 'S')
                                                        <span class="badge bg-info badge-primary-white-text">Izin Sakit</span>
                                                    @elseif($d->status == 'C')
                                                        <span class="badge bg-primary badge-primary-white-text">Izin Cuti</span>
                                                    @else
                                                        <span>-- : -- : --</span>
                                                    @endif
                                                @endif
                                            </td>
                                            <td>
                                                @if($d->jam_in != null && $d->jam_out != null)
                                                    {{$d->jam_out}}
                                                @else
                                                    <span>-- : -- : --</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($d->jam_in != null)
                                                @if($d->jam_in >= $jamKerja->jam_masuk)
                                                    @php
                                                        // Menghitung selisih waktu dalam menit
                                                        $jamterlambat = selisih($jamKerja->jam_masuk, $d->jam_in);
                                                    @endphp
                                                    <span class="badge bg-danger badge-primary-white-text">Terlambat {{$jamterlambat}}</span>
                                                @else
                                                    <span class="badge bg-success badge-primary-white-text">Tepat Waktu</span>
                                                @endif
                                            @else
                                                    @if($d->status == 'i')
                                                        <span class="badge bg-warning badge-primary-white-text">Izin Absen</span>
                                                    @elseif($d->status == 's')
                                                        <span class="badge bg-info badge-primary-white-text">Izin Sakit</span>
                                                    @elseif($d->status == 'c')
                                                        <span class="badge bg-primary badge-primary-white-text">Izin Cuti</span>
                                                    @else
                                                        <span class="badge bg-danger badge-primary-white-text">Belum Absen</span>
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

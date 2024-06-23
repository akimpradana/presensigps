@extends('layouts.presensi')
@section('content')
@php
    $displayIndex = 1;
@endphp
<style>
    .logout{
        position: absolute;
        color: white;
        font-size: 30px;
        text-decoration: none;
        right: 10px;
    }
    .logout :hover{
        color: white;
    }

    .badge-fixed-width {
        width: 80px; /* Sesuaikan lebar sesuai kebutuhan Anda */

        text-align: center;
    }
    

    .btn-sm ion-icon {
        font-size: 16px; /* Sesuaikan ukuran sesuai kebutuhan Anda */
    }

</style>
<div class="section"  id="user-section">
    <a href="/proseslogout" class="logout">
        <ion-icon name="log-out-outline"></ion-icon>
    </a>
            <div id="user-detail">
                <div class="avatar">
                    @if(!empty(Auth::guard('karyawan')->user()->foto))
                    @php
                    $path = Storage::url('uploads/karyawan/'.Auth::guard('karyawan')->user()->foto);
              
                    @endphp
                    <img src="{{url($path)}}" alt="avatar" class="imaged w64 rounded">
                    @else
                    <img src="assets/img/sample/avatar/avatar1.jpg" alt="avatar" class="imaged w64 rounded">
                    @endif
                </div>
                <div id="user-info">
                    <h2 id="user-name">{{ $karyawan->nama_lengkap }}</h2>
                    <span id="user-role">{{ $karyawan->nama_jabatan }}</span>
                    <div class="row">

                    </div>
                    
                </div>
                
            </div>
            <div class="mt-2">
                <span id="user-role">
                    {{ now()->locale('id_ID')->isoFormat('dddd') }}, 
                    {{ now()->locale('id_ID')->day }} 
                    {{ now()->locale('id_ID')->isoFormat('MMMM') }} 
                    {{ now()->locale('id_ID')->year }} pukul {{ now()->locale('id_ID')->format('H:i:s') }}.
                </span>
            </div>
        </div>

        {{-- <div class="section" id="menu-section">
            <div class="card">
                <div class="card-body text-center">
                    <div class="list-menu">
                        
                        <div class="item-menu text-center">
                            <div class="menu-icon">
                                <a href="" class="green" style="font-size: 40px;">
                                    <ion-icon name="person-sharp"></ion-icon>
                                </a>
                            </div>
                            <div class="menu-name">
                                <span class="text-center">Profil</span>
                            </div>
                        </div>
                        <div class="item-menu text-center">
                            <div class="menu-icon">
                                <a href="" class="danger" style="font-size: 40px;">
                                    <ion-icon name="calendar-number"></ion-icon>
                                </a>
                            </div>
                            <div class="menu-name">
                                <span class="text-center">Cuti</span>
                            </div>
                        </div>
                        <div class="item-menu text-center">
                            <div class="menu-icon">
                                <a href="" class="warning" style="font-size: 40px;">
                                    <ion-icon name="document-text"></ion-icon>
                                </a>
                            </div>
                            <div class="menu-name">
                                <span class="text-center">Histori</span>
                            </div>
                        </div>
                        <div class="item-menu text-center">
                            <div class="menu-icon">
                                <a href="" class="orange" style="font-size: 40px;">
                                    <ion-icon name="location"></ion-icon>
                                </a>
                            </div>
                            <div class="menu-name">
                                Lokasi
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
        <div class="section mt-2" id="presence-section">
            <div class="todaypresence">
                <div class="row">
                    <div class="col-6">
                        <div class="card gradasigreen">
                            <div class="card-body">
                                <div class="presencecontent">
                                    <div class="iconpresence">
                                        <ion-icon name="finger-print-outline"></ion-icon>
                                    </div>
                                    <div class="presencedetail">
                                        <h4 class="presencetitle">Masuk</h4>
                                        <span>{{$presensihariini != null && $presensihariini->jam_in != null ? $presensihariini->jam_in : 'Belum Absen'}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card gradasired">
                            <div class="card-body">
                                <div class="presencecontent">
                                    <div class="iconpresence">
                                        <ion-icon name="finger-print-outline"></ion-icon>
                                    </div>
                                    <div class="presencedetail">
                                        <h4 class="presencetitle">Pulang</h4>
                                        <span>{{$presensihariini != null && $presensihariini->jam_out != null ? $presensihariini->jam_out : 'Belum Absen'}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="rekappresensi">
                
            </div>
            <div class="presencetab mt-2">
                <h3> Riwayat Presensi Bulan {{$namabulan[$bulanini]}} Tahun {{$tahunini}}</h3>
                <div class="tab-pane fade show active" id="pilled" role="tabpanel">
                    <ul class="nav nav-tabs style1" role="tablist">
                        {{-- <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#home" role="tab">
                                Bulan Ini
                            </a>
                        </li> --}}
                        {{-- <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#profile" role="tab">
                                Leaderboard
                            </a>
                        </li> --}}
                    </ul>
                </div>
                <div class="tab-content mt-2" style="margin-bottom:100px;">
                    <div class="tab-pane fade show active" id="home" role="tabpanel">
                        <ul class="listview image-listview">
                            @foreach($historybulanini as $index => $d)
                            @if($d->jam_in != null)
                                <ul class="listview image-listview">
                                    <li>
                                        <div class="item">
                                            {{-- <div class="icon-box">
                                                <span class="number-icon">{{ $displayIndex }}</span>
                                            </div> --}}
                                            <div class="in">
                                                <span class="number-icon">{{ $displayIndex }}</span>
                                                <div>{{ date("d-m-y", strtotime($d->tgl_presensi)) }}</div>
                                                <span class="badge badge-success badge-fixed-width">{{ $d->jam_in }}</span>
                                                <span class="badge badge-danger badge-fixed-width">{{ $d->jam_out != null ? $d->jam_out : 'Belum Absen' }}</span>
                                                <button type="button" class="btn btn-primary btn-sm" onclick="showDetail('{{ $d->id }}', '{{ date("d-m-y", strtotime($d->tgl_presensi)) }}', '{{ $d->jam_in }}', '{{ $d->jam_out != null ? $d->jam_out : 'Belum Absen' }}')">
                                                    <ion-icon name="search-outline"></ion-icon>
                                                </button>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                                @php
                                    $displayIndex++;
                                @endphp
                            @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
                {{-- yang benar dibawah ini --}}
                {{-- <div class="tab-content mt-2" style="margin-bottom:100px;">
                    <div class="tab-pane fade show active" id="home" role="tabpanel">
                        <ul class="listview image-listview">
                           @foreach($historybulanini as $d)
                            <li>
                                <div class="item">
                                    <div class="icon-box bg-primary">
                                        <ion-icon name="finger-print-outline"></ion-icon>
                                    </div>
                                    <div class="in">
                                        <div>{{date("d-m-y",strtotime($d->tgl_presensi))}}</div>
                                        <span class="badge badge-success">{{$d->jam_in}}</span>
                                        <span class="badge badge-danger">{{$d->jam_out !=null && $presensihariini != null  ? $d->jam_out :
                                        'Belum Absen'}}</span>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                            
                        </ul>
                    </div> --}}
                    {{-- smpai ini --}}
                    {{-- <div class="tab-pane fade" id="profile" role="tabpanel">
                        <ul class="listview image-listview">
                            @foreach($historybulanini as $d)
                            <li>
                                <div class="item">
                                    <img src="assets/img/sample/avatar/avatar1.jpg" alt="image" class="image">
                                    <div class="in">
                                        <div>Edward Lindgren</div>
                                        <span class="text-muted">Designer</span>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div> --}}

                </div>
            </div>
        </div>
@endsection


<script>
    function showDetail(id, tgl_presensi, jam_in, jam_out) {
        // Tentukan status berdasarkan waktu masuk
        var status;
        var jammasuk = "{{$jamkerja->jam_masuk}}";
        var jam_masuk_threshold = jammasuk;
    
        // Fungsi untuk membandingkan waktu
        function compareTime(time1, time2) {
            var t1 = new Date('1970-01-01T' + time1 + 'Z');
            var t2 = new Date('1970-01-01T' + time2 + 'Z');
            return t1.getTime() - t2.getTime();
        }
    
        if (compareTime(jam_in, jam_masuk_threshold) > 0) {
            status = '<span class="badge bg-danger">Terlambat</span>';
        } else {
            status = '<span class="badge bg-success">Tepat Waktu</span>';
        }
    
        // Tampilkan pop-up SweetAlert2
        Swal.fire({
            title: 'Detail Presensi',
            html: `
                <p>Tanggal: ${tgl_presensi}</p>
                <p>Jam Masuk: ${jam_in}</p>
                <p>Jam Pulang: ${jam_out}</p>
                <p>Status: ${status}</p>
            `,
            icon: 'info',
            confirmButtonText: 'Tutup'
        });
    }
    </script>
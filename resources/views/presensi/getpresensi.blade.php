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

@foreach ($presensi as $d)
    <tr>
        <td>{{$loop->iteration}}</td>
        <td>{{$d->nik}}</td>
        <td>{{$d->nama_lengkap}}</td>
        <td>{{$d->nama_jabatan}}</td>
        <td>
            @if($d->jam_in != null)
                {{$d->jam_in}}
            @else
                {!! $d->status == 'I' ? '<span class="badge bg-warning">Izin Absen</span>' : 
                ($d->status == 'S' ? '<span class="badge bg-info">Izin Sakit</span>' : 
                ($d->status == 'c' ? '<span class="badge bg-primary">Izin Cuti</span>' : '<span>-- : -- : --</span>')) !!}
            @endif
        </td>
        <td>
            @if($d->jam_in != null && $d->jam_out != null)
                {{$d->jam_out}}
            @else
                {!! '<span >-- : -- : --</span>' !!}
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
                {!! $d->status == 'i' ? '<span class="badge bg-warning">Izin Absen</span>' : 
                ($d->status == 's' ? '<span class="badge bg-info">Izin Sakit</span>' : 
                ($d->status == 'C' ? '<span class="badge bg-primary">Izin Cuti</span>' : 
                '<span class="badge bg-danger">Belum Absen</span>')) !!}
            @endif
        </td>
    </tr>
@endforeach
<style>
    .badge-primary-white-text {
        color: white;
    }
</style>
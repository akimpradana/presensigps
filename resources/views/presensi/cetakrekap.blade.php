<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>A4</title>

  <!-- Normalize or reset CSS with your favorite library -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css">

  <!-- Load paper.css for happy printing -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.4.1/paper.css">

  <!-- Set page size here: A5, A4 or A3 -->
  <!-- Set also "landscape" if you need -->
  <style>@page { size: A4 }
    .tabelpresensi{
        width: 100%;
        margin-top: 20px;
        border-collapse: collapse;
    }
    .tabelpresensi tr th{
        border: 1px solid #131212;
        padding: 5px;
        background-color: #dbdbdb;
        font-size: 10px;
    }
    .tabelpresensi tr td{
        border: 1px solid #131212;
        padding: 5px;
        font-size: 12px;
    }
</style>
</head>

<!-- Set "A5", "A4" or "A3" for class name -->
<!-- Set also "landscape" if you need -->
<body class="A4 landscape">

  <!-- Each sheet element should have the class "sheet" -->
  <!-- "padding-**mm" is optional: you can set 10, 15, 20 or 25 -->
  <section class="sheet padding-10mm">

    <!-- Write HTML just like a web page -->
    <table style="width: 100%">
        <tr>
            <td style="width: 30px">
                <img src="{{ asset('assets/img/logopresensi.png') }}" width="120" height="120" alt="">
            </td>
            <td>
                <h3>
                    LAPORAN REKAP PRESENSI KARYAWAN<br>
                    PERIODE {{ strtoupper($namabulan[(int) $bulan]) }} {{ $tahun }}<br>
                    CV. BERKAH KONVEKSI
                </h3>
            </td>
        </tr>
    </table>
    <table class="tabelpresensi">
        <tr>
            <th rowspan="2">NIK</th>
            <th rowspan="2">Nama Karyawan</th>
            <th colspan="31">Tanggal</th>
            <th rowspan="2">Total Hadir</th>
            <th rowspan="2">Izin</th>
            <th rowspan="2">Alfa</th>
            <th rowspan="2">Terlambat</th>
            <th rowspan="2">Lembur</th>
        </tr>
        <tr>
            @for($i = 1; $i <= 31; $i++)
                <th>{{ $i }}</th>
            @endfor
        </tr>
        @foreach ($rekap as $d)
        <tr>
            <td>{{ $d->nik }}</td>
            <td>{{ $d->nama_lengkap }}</td>

            @php
                $totalHadir = 0;
                $totalIzin = 0;
                $totalTidakHadir = 0;
            @endphp

            @for ($i = 1; $i <= 31; $i++)
                @php
                    $tgl = "tgl_" . $i;
                    $status = $d->$tgl;
                    $statusText = '';
                    $color = 'black';

                    switch ($status) {
                        case 'H':
                            $totalHadir++;
                            $statusText = 'Hadir';
                            break;
                        case 'H_late':
                            $totalHadir++;
                            $statusText = 'Hadir';
                            $color = 'red';
                            break;
                        case 'I':
                            $totalIzin++;
                            $statusText = 'Izin';
                            break;
                        case 'S':
                            $totalIzin++;
                            $statusText = 'Sakit';
                            break;
                        case 'C':
                            $totalIzin++;
                            $statusText = 'Cuti';
                            break;
                        case 'N':
                        default:
                            $totalTidakHadir++;
                            $statusText = '-';
                            break;
                    }
                @endphp
                <td style="color: {{ $color }}">{{ $statusText }}</td>
            @endfor

            <td>{{ $totalHadir }}</td>
            <td>{{ $totalIzin }}</td>
            <td>{{ $totalTidakHadir }}</td>
            <td>{{ $d->total_terlambat }}</td> <!-- Tampilkan total terlambat -->
            @php
                $totalLembur = $d->total_lembur;
                if ($totalLembur !== null) {
                    $totalLembur = explode('.', $totalLembur)[0]; // Remove microseconds
                } else {
                    $totalLembur = '00:00:00';
                }
            @endphp
            <td>{{ $totalLembur}}</td> <!-- Tampilkan total lembur -->
        </tr>
    @endforeach
    </table>
    

  </section>



</body>

</html>
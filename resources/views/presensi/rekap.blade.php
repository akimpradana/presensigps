@extends('layouts.admin.tabler')
@section('content')
    <!-- Page header -->

    <style>
        .table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch; /* Untuk scroll yang halus di iOS */
        }

    .center-text {
        text-align: center;
        vertical-align: middle;
    }

    </style>
    <div class="page-header d-print-none">
        <div class="container-xl">
          <div class="row g-2 align-items-center">
            <div class="col">
              <!-- Page pre-title -->

              <h2 class="page-title">
               Rekap Laporan Presensi
              </h2>
            </div>
          </div>
        </div>
      </div>

      {{-- <div class="page-body">
        <div class="container">
            <div class="row">
                <div class="col-4">
                    <div class="card">
                        <div class="card-body">
                            @if(Session::get('success'))
                                <div class="alert alert-success">
                                   {{session::get('success')}}
                                </div>
                                @endif

                                @if(Session::get('warning'))
                                <div class="alert alert-warning">
                                    {{session::get('warning')}}
                                </div>
                                @endif
                        <form action="/presensi/cetakrekap" target="_blank" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <select name="bulan" class="form-select w-100" id="bulan">
                                            <option value="">Bulan</option>
                                            @for ($i = 1; $i <= 12; $i++)
                                                <option value="{{ $i }}" {{date("m")==$i ? 'selected' : ''}}>{{ $namabulan[$i] }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-12">
                                    <div class="input-icon">
                                        <select name="tahun" class="form-select w-100" id="tahun">
                                            <option value="">Tahun</option>
                                            @php
                                                $tahunmulai =2023;
                                                $tahunini =date("Y");
                                            @endphp
                                            @for ($tahun=$tahunmulai; $tahun<=$tahunini; $tahun++)
                                            <option value="{{$tahun}}" {{date("Y")==$tahun ? 'selected' : ''}}>{{$tahun}}</option>
                                                
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-6">
                                    <div class="form-group">
                                        <button type="submit" name="cetak" class="btn btn-primary w-100"><svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-printer"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" /><path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" /><path d="M7 13m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z" /></svg>
                                            Cetak</button>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <button type="submit" name="exportexcel" class="btn btn-success w-100"><svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-download"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" /><path d="M7 11l5 5l5 -5" /><path d="M12 4l0 12" /></svg>
                                            Export Excel</button>
                                    </div>
                                </div>
                            </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
      </div> --}}

      <div class="page-body">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            @if(Session::get('success'))
                                <div class="alert alert-success">
                                   {{session::get('success')}}
                                </div>
                                @endif

                                @if(Session::get('warning'))
                                <div class="alert alert-warning">
                                    {{session::get('warning')}}
                                </div>
                                @endif
                            <form action="{{ route('presensi.rekap') }}" method="POST">
                                @csrf
                            <div class="row">
                                <div class="col-5">
                                    <div class="form-group">
                                        <select name="bulan" class="form-select w-100" id="bulanSelect">
                                            <option value="">Bulan</option>
                                            @for ($i = 1; $i <= 12; $i++)
                                            <option value="{{ $i }}" 
                                                {{ request('bulan') == $i ? 'selected' : (date('n') == $i ? 'selected' : '') }}>
                                                {{ $namabulan[$i] }}
                                            </option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="col-5">
                                    <div class="input-icon">
                                        <select name="tahun" class="form-select w-100" id="tahunSelect">
                                            <option value="">Tahun</option>
                                            @php
                                                $tahunmulai =2023;
                                                $tahunini =date("Y");
                                            @endphp
                                            @for ($tahun = $tahunmulai; $tahun <= $tahunini; $tahun++)
                                            <option value="{{ $tahun }}" 
                                                {{ request('tahun') == $tahun ? 'selected' : (date('Y') == $tahun ? 'selected' : '') }}>
                                                {{ $tahun }}
                                            </option>
                                        @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="col-2">
                                <div class="input-icon">
                                    <button type="submit" name="cari" class="btn btn-success w-100"><svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-download"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" /><path d="M7 11l5 5l5 -5" /><path d="M12 4l0 12" /></svg>
                                        Cari</button>
                                </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover center-text">
                                            <thead>
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

                                            </thead>
                                            
                                        </table>
                                        
                                    </div>
                                    
                                </div>
                            </div>
                            </form>
                            <form action="/presensi/cetakrekap" target="_blank" method="POST">
                                @csrf
                                <input type="hidden" name="bulan" value="{{ request('bulan') }}">
                                <input type="hidden" name="tahun" value="{{ request('tahun') }}">
                                <div class="row mt-3">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <button type="submit" name="cetak" class="btn btn-primary w-100"><svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-printer"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" /><path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" /><path d="M7 13m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z" /></svg>
                                                Cetak</button>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <button type="submit" name="exportexcel" class="btn btn-success w-100"><svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-download"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" /><path d="M7 11l5 5l5 -5" /><path d="M12 4l0 12" /></svg>
                                                Export Excel</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

<script>


    document.getElementById('bulan').addEventListener('change', function() {
        var selectedMonth = this.value;
        window.location.href = "/presensi?bulan=" + selectedMonth;
    });

</script>
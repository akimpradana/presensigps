@extends('layouts.admin.tabler')
@section('content')

<style>
    .badge-primary-white-text {
        color: white;
    }
    .center-text {
        text-align: center;
        vertical-align: middle;
    }
</style>
<!-- Page header -->
<div class="page-header d-print-none">
    <div class="container-xl">
      <div class="row g-2 align-items-center">
        <div class="col">
          <!-- Page pre-title -->
          <h2 class="page-title">
            Data Izin / Sakit
          </h2>
        </div>
      </div>
    </div>
  </div>

<div class="page-body">
    <div class="container-xl">
        <div class="row">
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
        </div>
        <div class="row">                   
            <div class="col-12">
                <table class="table table-bordered">
                    <thead class="center-text">
                        <tr>
                        <th>No.</th>
                        <th>Kode Izin</th>
                        <th>Tanggal</th>
                        <th>Nik</th>
                        <th>Nama</th>
                        <th>Jabatan</th>
                        <th>Status</th>
                        <th>Keterangan</th>
                        <th>Satatus Approve</th>
                        <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($izinsakit as $d)
                        <tr>
                            <td style="text-align: center; vertical-align: middle;">{{$loop->iteration}}</td>
                            <td>{{$d->kode_izin}}</td>
                            <td>{{date('d-m-Y',strtotime($d->tgl_izin_dari))}} s/d {{date('d-m-Y',strtotime($d->tgl_izin_sampai))}}</td>
                            <td>{{$d->nik}}</td>
                            <td>{{$d->nama_lengkap}}</td>
                            <td>{{$d->nama_jabatan}}</td>
                            <td>{{$d->status=="i" ? "izin" :"Sakit"}}</td>
                            <td>{{$d->keterangan}}</td>
                            <td>
                                @if ($d->status_approval==1)
                                    <span class="badge bg-success badge-primary-white-text w-100">Disetujui</span>
                                @elseif($d->status_approval==2)
                                    <span class="badge bg-danger badge-primary-white-text w-100">Ditolak</span>
                                @else
                                <span class="badge bg-warning badge-primary-white-text w-100">Pending</span>
                                @endif
                            </td>
                            <td>
                                @if ($d->status_approval==0)
                                <a href="#" class="btn btn-sm btn-primary approve w-100" id="" kode_izin="{{$d->kode_izin}}"><svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-external-link"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 6h-6a2 2 0 0 0 -2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-6" /><path d="M11 13l9 -9" /><path d="M15 4h5v5" /></svg>Aksi</a>
                                @else
                                <a href="/presensi/{{$d->kode_izin}}/batalkanizin" id="batalkan" class="btn btn-sm btn-danger batalkan-confirm w-100"><svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-square-letter-x"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 3m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v14a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z" /><path d="M10 8l4 8" /><path d="M10 16l4 -8" /></svg>Batalkan</a>
                                @endif
                                
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{$izinsakit->links('vendor.pagination.bootstrap-5')}}
            </div>
        </div>
    </div>
</div>
{{-- modal edit data --}}
<div class="modal modal-blur fade" id="modal-izinsakit" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Izin/Sakit</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="/presensi/approveizinsakit" method="POST">
            @csrf
            <input type="hidden" name="kode_izin_form" id="kode_izin_form">
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <select name="status_approval" id="status_approval" class="form-select w-100">
                            <option value="1">Disetujui</option>
                            <option value="2">Ditolak</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-12">
                    <div class="form-group">
                        <button class="btn btn-primary w-100" type="submit">Submit</button>
                    </div>
                </div>
            </div>
          </form>
          
        </div>
      </div>
    </div>
  </div>
@endsection

@push('myscript')
    <script>
        $(function(){
            $(".approve").click(function(e){
                e.preventDefault();
                var kode_izin =$(this).attr("kode_izin");
                $("#kode_izin_form").val(kode_izin);
                $("#modal-izinsakit").modal("show");
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
            var elements = document.querySelectorAll('.batalkan-confirm');
            
            elements.forEach(function(element) {
                element.addEventListener('click', function(event) {
                    event.preventDefault(); // Mencegah link agar tidak langsung berpindah halaman
                    
                    Swal.fire({
                        title: 'Apakah Anda yakin ingin membatalkan pengajuan izin ini?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, batalkan!',
                        cancelButtonText: 'Tidak'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = this.href; // Jika dikonfirmasi, lanjutkan ke URL yang dituju
                        }
                    });
                });
            });
        });
                // $(".batalkan-confirm").click(function(e){
        //         var form = $(this).closest('form');
        //         e.preventDefault();
        //         Swal.fire({
        //             title: "Apakah Anda Yakin Ingin Membatalkan Pengajuan Izin Ini?",
        //             icon: "warning",
        //             showCancelButton: true,
        //             confirmButtonColor: "#3085d6",
        //             cancelButtonColor: "#d33",
        //             confirmButtonText: "Batalkan"
                    
                    
        //         });
        //     })
    </script>
@endpush


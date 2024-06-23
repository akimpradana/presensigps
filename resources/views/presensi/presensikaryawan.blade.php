@extends('layouts.admin.tabler')
@section('content')
<!-- Page header -->
<style>
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
            Presensi Karyawan
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
                        <div class="row">
                            <div class="col-12">
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
                        </div>

                        <div class="row mt-2">
                            <div class="col-12">
                                <form action="/presensi/presensikaryawan" method="GET">
                                    <div class="row">
                                        <div class="col-5">
                                            <div class="form-group">
                                                <input type="text" name="nama_karyawan" id="nama_karyawan" class="form-control" placeholder="Nama Karyawan">
                                            </div>
                                        </div>
                                        <div class="col-2">
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary w-100"><svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-search"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" /></svg>
                                                Cari</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="row mt-2">
                                <div class="col-12">
                                    <table class="table table-bordered">
                                        <thead class="center-text">
                                            <tr>
                                                <th>No</th>
                                                <th>NIK</th>
                                                <th>Nama</th>
                                                <th>Jabatan</th>
                                                <th>No. HP</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($karyawan as $d)
                                <tr>
                                    <td style="text-align: center; vertical-align: middle;">{{$loop->iteration + $karyawan->firstItem() - 1}}</td>
                                    <td>{{$d->nik}}</td>
                                    <td>{{$d->nama_lengkap}}</td>
                                    <td>{{$d->nama_jabatan}}</td>
                                    <td>{{$d->no_hp}}</td>
                                    <td style="text-align: center; vertical-align: middle;">
                                        
                                            <a href="/presensi/{{$d->nik}}/detail" class="edit btn btn-info btn-sm w-100">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-edit">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                    <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                                    <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                                                    <path d="M16 5l3 3" />
                                                </svg>
                                                Detail
                                            </a>
                                            @csrf
                                        
                                    </td>
                                </tr>
                            @endforeach
                                        </tbody>
                                    </table>
                                    {{$karyawan->links('vendor.pagination.bootstrap-5')}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

  {{-- modal edit data
  <div class="modal modal-blur fade" id="modal-editkaryawan" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">edit Data Karyawan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="loadeditform">
          
          
        </div>
      </div>
    </div>
  </div> --}}
@endsection

@push('myscript')
    <script>
        $(function(){
            

            $(".edit").click(function(){
                var nik = $(this).attr('nik');
                $.ajax({
                    type:'POST',
                    url:'/karyawan/edit',
                    chace:false,
                    data:{
                    _token:"{{csrf_token();}}",
                    nik:nik
                    },
                    success:function(respond){
                        $("#loadeditform").html(respond);
                    }
                })
                $("#modal-editkaryawan").modal("show")
            });

            // $(".delete-confirm").click(function(e){
            //     var form = $(this).closest('form');
            //     e.preventDefault();
            //     Swal.fire({
            //         title: "Apakah Anda Yakin Ingin Menghapus Data Ini?",
            //         text: "Data Akan Terhapus Secara Permanen",
            //         icon: "warning",
            //         showCancelButton: true,
            //         confirmButtonColor: "#3085d6",
            //         cancelButtonColor: "#d33",
            //         confirmButtonText: "Hapus"
            //         }).then((result) => {
            //         if (result.isConfirmed) {
            //             form.submit();
            //             Swal.fire({
            //             title: "Deleted!",
            //             text: "Data Berhasil Dihapus",
            //             icon: "success"
            //             });
            //         }
            //     });
            // })

            $("#frmKaryawan").submit(function(){
                var nik =$("#nik").val();
                var nama_lengkap =$("#nama_lengkap").val();
                var kode_jbtn =$("#kode_jbtn").val();
                var no_hp =$("#no_hp").val();
                if(nik ==""){
                    Swal.fire({
                    title: 'Waring!',
                    text: 'Nik Harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'ok'
                    }).then((result)=>{
                        $($nik.focus());
                    });
                    return false;
                } else if(nama_lengkap ==""){
                    Swal.fire({
                    title: 'Waring!',
                    text: 'Nama Harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'ok'
                    }).then((result)=>{
                        $($nama_lengkap.focus());
                    });
                    return false;
                } else if(kode_jbtn ==""){
                    Swal.fire({
                    title: 'Waring!',
                    text: 'Jabatan Harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'ok'
                    }).then((result)=>{
                        $($kode_jbtn.focus());
                    });
                    return false;
                }else if(no_hp ==""){
                    Swal.fire({
                    title: 'Waring!',
                    text: 'No Hp Harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'ok'
                    }).then((result)=>{
                        $($no_hp.focus());
                    });
                    return false;
                } 
            });
        });
    </script>
@endpush
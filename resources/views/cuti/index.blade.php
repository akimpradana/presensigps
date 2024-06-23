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
            Data Cuti
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
                        <div class="row">
                            <div class="col-12">
                                <a href="#" class="btn btn-primary" id="btntambahCuti"> 
                                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-plus"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                                    Tambah Data </a>
                            </div>
                        </div>
                        {{-- <div class="row mt-2">
                            <div class="col-12">
                                <form action="/jabatan" method="GET">
                                    <div class="row">
                                        <div class="col-5">
                                            <div class="form-group">
                                                <input type="text" name="nama_jabatan" id="nama_jabatan" class="form-control" placeholder="Jabatan">
                                            </div>
                                        </div>
                                        <div class="col-2">
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary"><svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-search"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" /></svg>
                                                Cari</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div> --}}
                            <div class="row mt-2">
                                <div class="col-12">
                                    <table class="table table-bordered">
                                        <thead class="center-text">
                                            <tr>
                                                <th>No</th>
                                                <th>Kode Cuti</th>
                                                <th>Nama Cuti</th>
                                                <th>Jumlah Hari</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                          @foreach ($cuti as $d)
                                              <tr>
                                                <td style="text-align: center; vertical-align: middle;">{{$loop->iteration}}</td>
                                                <td>{{$d->kode_cuti}}</td>
                                                <td>{{$d->nama_cuti}}</td>
                                                <td>{{$d->jml_hari}}</td>
                                                <td style="text-align: center; vertical-align: middle;">
                                                  <div class="btn-group">
                                                      <a href="#"  class="edit btn btn-info btn-sm" kode_cuti="{{$d->kode_cuti}}"><svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-edit"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>
                                                      Edit</a>
                                                      <form action="/cuti/{{$d->kode_cuti}}/delete" method="POST" style="margin-left: 5px">
                                                      @csrf
                                                  
                                                      <a class="btn btn-danger btn-sm delete-confirm" >
                                                          <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-trash"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                                                      Delete</a>
                                                      </form>
                                                  </div>
                                                  
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
</div>
{{-- modal tambah data --}}
<div class="modal modal-blur fade" id="modal-inputcuti" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Data Cuti</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="/cuti/store" method="POST" id="frmCuti" >
            @csrf
            <div class="row">
                <div class="col-12">
                    <div class="input-icon">
                        <span class="input-icon-addon">
                          <!-- Download SVG icon from http://tabler-icons.io/i/search -->
                          <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-barcode"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7v-1a2 2 0 0 1 2 -2h2" /><path d="M4 17v1a2 2 0 0 0 2 2h2" /><path d="M16 4h2a2 2 0 0 1 2 2v1" /><path d="M16 20h2a2 2 0 0 0 2 -2v-1" /><path d="M5 11h1v2h-1z" /><path d="M10 11l0 2" /><path d="M14 11h1v2h-1z" /><path d="M19 11l0 2" /></svg>                        </span>
                        <input type="text" value="" class="form-control" placeholder="Kode Cuti" id="kode_cuti" name="kode_cuti" aria-label="Search in website">
                      </div>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-12">
                    <div class="input-icon">
                        <span class="input-icon-addon">
                          <!-- Download SVG icon from http://tabler-icons.io/i/search -->
                          <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-user"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /></svg>                        </span>
                        <input type="text" value="" class="form-control" placeholder="Nama Cuti" id="nama_cuti" name="nama_cuti" aria-label="Search in website">
                      </div>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-12">
                    <div class="input-icon">
                        <span class="input-icon-addon">
                          <!-- Download SVG icon from http://tabler-icons.io/i/search -->
                          <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-user"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /></svg>                        </span>
                        <input type="text" value="" class="form-control" placeholder="Jumlah Hari" id="jml_hari" name="jml_hari" aria-label="Search in website">
                      </div>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-12">
                    <div class="form-group">
                        <button class="btn btn-primary w-100"> Simpan
                        </button>
                    </div>
                </div>
            </div>
          </form>
          
        </div>
        
      </div>
    </div>
  </div>

  {{-- modal edit data --}}
  <div class="modal modal-blur fade" id="modal-editCuti" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Data Cuti</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="loadeditform">
          
          
        </div>
      </div>
    </div>
  </div>
@endsection

@push('myscript')
    <script>
        $(function(){
            $("#btntambahCuti").click(function(){
                $("#modal-inputcuti").modal("show")
            });

            $(".edit").click(function(){
                var kode_cuti = $(this).attr('kode_cuti');
                $.ajax({
                    type:'POST',
                    url:'/cuti/edit',
                    chace:false,
                    data:{
                    _token:"{{csrf_token();}}",
                    kode_cuti:kode_cuti
                    },
                    success:function(respond){
                        $("#loadeditform").html(respond);
                    }
                })
                $("#modal-editCuti").modal("show")
            });

            $(".delete-confirm").click(function(e){
                var form = $(this).closest('form');
                e.preventDefault();
                Swal.fire({
                    title: "Apakah Anda Yakin Ingin Menghapus Data Ini?",
                    text: "Data Akan Terhapus Secara Permanen",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Hapus"
                    }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                        Swal.fire({
                        title: "Deleted!",
                        text: "Data Berhasil Dihapus",
                        icon: "success"
                        });
                    }
                });
            })

            $("#frmCuti").submit(function(){
                var kode_cuti =$("#kode_cuti").val();
                var nama_cuti =$("#nama_cuti").val();
                var jml_hari =$("#jml_hari").val();

                if(kode_cuti ==""){
                    Swal.fire({
                    title: 'Waring!',
                    text: 'Kode Cuti Harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'ok'
                    }).then((result)=>{
                        $($kode_cuti.focus());
                    });
                    return false;
                } else if(nama_cuti ==""){
                    Swal.fire({
                    title: 'Waring!',
                    text: 'Nama Cuti Harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'ok'
                    }).then((result)=>{
                        $($nama_cuti.focus());
                    });
                    return false;
                } else if(jml_hari ==""){
                    Swal.fire({
                    title: 'Waring!',
                    text: 'Jumlah Hari Harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'ok'
                    }).then((result)=>{
                        $($jml_hari.focus());
                    });
                    return false;
                }
            });
        });
    </script>
@endpush
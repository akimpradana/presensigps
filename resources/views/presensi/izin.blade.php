@extends('layouts.presensi')
@section('header')
<!-- App Header -->
<div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Data Izin</div>
    <div class="right"></div>
</div>

<style>
    .historicontent{
        display: flex;
    }
    .datapresensi{
        margin-left: 10px;
    }
    .status{
        position: absolute;
        right: 20px;
    }
</style>

<!-- * App Header -->
@endsection
@section('content')
<div class="row" style="margin-top:70px">
    <div class="col">
        @php
           $messagesuccess = Session::get('success');
           $messageerror = Session::get('error'); 
        @endphp
        @if(Session::get('success'))
        <div class="alert alert-success">
            {{$messagesuccess}}
        </div>
        @endif
        @if(Session::get('error'))
        <div class="alert alert-error">
            {{$messageerror}}
        </div>
        @endif
    </div>
</div>
<div class="row">
    <div class="col">
        @foreach($dataizin as $index=>$d)
        @php
            if ($d->status=="i") {
                $status="izin";
            } else if ($d->status=="s") {
                $status="Sakit";
            }elseif($d->status=="c") {
                $status="Cuti";
            }else{
                $status="Not Found";
            }
            
        @endphp
        <div class="card mt-1 card_izin" kode_izin="{{$d->kode_izin}}" status_approval="{{$d->status_approval}}" data-toggle="modal" data-target="#actionSheetIconed">
            <div class="card-body">
                <div class="historicontent">
                    <div class="iconpresensi ">
                        @if ($d->status == "i")
                        <ion-icon name="document-text-outline" style="font-size:38px; color:rgb(50, 118, 255)"></ion-icon>
                        @elseif($d->status =="s")
                        <ion-icon name="medkit-outline" style="font-size:38px; color:rgb(242, 125, 8)"></ion-icon>
                        @elseif($d->status =="c")
                        <ion-icon name="calendar-outline" style="font-size:38px; color:rgb(47, 242, 8)"></ion-icon>
                        @endif
                        
                        </div>
                        <div class="datapresensi">
                            <h3 style="line-height: 3px">{{date("d-m-Y",strtotime($d->tgl_izin_dari))}} ({{$status}})</h3>
                            <small>{{date("d-m-Y",strtotime($d->tgl_izin_dari))}} s/d {{date("d-m-Y",strtotime($d->tgl_izin_sampai))}}</small>
                            <p>{{$d->keterangan}}
                            <br>
                            @if ($d->status=="c")
                                <span class="badge bg-warning">{{$d->nama_cuti}}</span>
                            @endif
                            </p>
                        </div>
                        <div class="status">
                            @if($d->status_approval ==0)
                            <span class="badge bg-warning">Pending</span>
                            @elseif($d->status_approval ==1)
                            <span class="badge bg-success">Disetujui</span>
                            @elseif($d->status_approval ==2)
                            <span class="badge bg-danger">Ditolak</span>
                            @endif
                            <p style="margin-top:5px; font-weight:bold">{{hitunghari1($d->tgl_izin_dari,$d->tgl_izin_sampai)}} Hari</p>
                        </div>
                </div>
                
                
            </div>
        </div>
        {{-- <ul class="listview image-listview">
            <li>
                <div class="item">
                    <div class="in">
                        <div>
                        <b>{{date("d-m-y",strtotime($d->tgl_izin_dari))}} ({{$d->status=="s" ? "Sakit" : "izin"}})</b>
                        <div>
                            <b class="text-muted">{{$d->keterangan}}</b>
                        </div>
                        </div>   
                    </div>
                    @if($d->status_approval ==0)
                    <span class="badge bg-warning">Waiting</span>
                    @elseif($d->status_approval ==1)
                    <span class="badge bg-success">approved</span>
                    @elseif($d->status_approval ==2)
                    <span class="badge bg-danger">rejected</span>
                    @endif
                </div>
            </li>
        </ul>     --}}
            
        @endforeach
    </div>
</div>
{{-- <div class="fab-button bottom-right" style="margin-bottom: 70px">
    <a href="/presensi/buatizin" class="fab">
        <ion-icon name="add-outline"></ion-icon>
    </a>
</div> --}}
<div class="fab-button animate bottom-right dropdown" style="margin-bottom: 70px">
    <a href="#" class="fab bg-primary" data-toggle="dropdown">
        <ion-icon name="add-outline" role="img" class="md hydrated" aria-label="add outline"></ion-icon>
    </a>
    <div class="dropdown-menu">
        <a class="dropdown-item bg-primary" href="/izinabsen">
            <ion-icon name="document-text-outline" role="img" class="md hydrated" aria-label="image outline"></ion-icon>
            <p>Izin Absen</p>
        </a>
        <a class="dropdown-item bg-primary" href="/izinsakit">
            <ion-icon name="document-text-outline" role="img" class="md hydrated" aria-label="image outline"></ion-icon>
            <p>Izin Sakit</p>
        </a>
        <a class="dropdown-item bg-primary" href="/izincuti">
            <ion-icon name="document-text-outline" role="img" class="md hydrated" aria-label="image outline"></ion-icon>
            <p>Izin Cuti</p>
        </a>
    </div>
</div>

<div class="modal fade action-sheet" id="actionSheetIconed" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Aksi</h5>
            </div>
            <div class="modal-body" id="showact">
 
            </div>
        </div>
    </div>
</div>
<div class="modal fade dialogbox" id="deleteConfirm" data-backdrop="static" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Yakin Dihapus ?</h5>
            </div>
            <div class="modal-body">
                Data Pengajuan Izin Akan dihapus
            </div>
            <div class="modal-footer">
                <div class="btn-inline">
                    <a href="#" class="btn btn-text-secondary" data-dismiss="modal">Batalkan</a>
                    <a href="" class="btn btn-text-primary" id="hapuspengajuan">Hapus</a>
                </div>
            </div>
        </div>
 </div>
</div>
@endsection

@push('myscript')
    <script>
        $(function(){
            $(".card_izin").click(function(e){
                var kode_izin =$(this).attr("kode_izin");
                var status_approval = $(this).attr("status_approval");

                if (status_approval==1) {
                    Swal.fire({
                    title: 'Terimakasih',
                    text: 'Data Pengajuan Izin Sudah Disetujui',
                    icon: 'success'
                })
                }else{
                    $("#showact").load('/izin/'+kode_izin+'/showact');
                }
               
            });
        });
    </script>
@endpush
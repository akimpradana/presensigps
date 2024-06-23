@extends('layouts.presensi')
@section('header')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/css/materialize.min.css">
<style>
    .datepicker-modal{
        max-height: 430px !important;
    }
    .datepicker-date-display{
        background-color: #0f3a7e !important;
    }
</style>
<!-- App Header -->
<div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Form Izin Cuti</div>
    <div class="right"></div>
</div>
<!-- * App Header -->
@endsection
@section('content')
<div class="row" style="margin-top: 70px">
    <div class="col">
        <form method="POST" action="/izincuti/store" id="frmIzin" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <input type="text" id="tgl_izin_dari" name="tgl_izin_dari"  class="form-control datepicker" placeholder="Dari">
            </div>
            <div class="form-group">
                <input type="text" id="tgl_izin_sampai" name="tgl_izin_sampai" class="form-control datepicker" placeholder="Sampai">
            </div>
            <div class="form-group">
                <input type="text" id="jml_hari" name="jml_hari" class="form-control" readonly placeholder="Jumlah Hari">
            </div>
            <div class="form-group">
                <select type="text" id="kode_cuti" name="kode_cuti" class="form-control selectmaterialize"  >
                    <option value="">Pilih Kategori Cuti</option>
                    @foreach ($mastercuti as $c)
                        <option value="{{$c->kode_cuti}}">{{$c->nama_cuti}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <textarea name="keterangan" id="keterangan" cols="30" rows="5" class="form-control" placeholder="Keterangan"></textarea>
            </div>
            <div class="form-group">
                <button class="btn btn-primary w-100">Kirim</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('myscript')
<script>
    var currYear = (new Date()).getFullYear();
        $(document).ready(function() {
        $(".datepicker").datepicker({
            format: "yyyy-mm-dd"  
              
        });
        
        document.getElementById('frmIzin').addEventListener('submit', function(event) {
            // Get the input values
            var tglIzinDari = new Date(document.getElementById('tgl_izin_dari').value);
            var tglIzinSampai = new Date(document.getElementById('tgl_izin_sampai').value);
            var today = new Date();
            
            // Setel waktu ke 00:00:00 untuk membandingkan hanya tanggal
            today.setHours(0,0,0,0);
            
            // Periksa apakah tanggal yang dipilih sebelum hari ini
            if (tglIzinDari < today || tglIzinSampai < today) {
                alert('Tanggal izin tidak boleh sebelum hari ini.');
                event.preventDefault(); // Cegah pengiriman form
            }
        });

        function loadjumlahhari() {
            // Mengambil nilai dari elemen input dengan ID 'tgl_izin_dari' dan 'tgl_izin_sampai'
            var dari = $("#tgl_izin_dari").val(); 
            var sampai = $("#tgl_izin_sampai").val(); 

            // Membuat objek Date dari nilai yang diambil
            var date1 = new Date(dari); 
            var date2= new Date(sampai); 

            // Menghitung selisih waktu antara dua tanggal dalam milidetik
            var Difference_In_Time = date2.getTime() - date1.getTime(); 

            // Menghitung jumlah hari antara dua tanggal dengan membagi selisih waktu dengan jumlah milidetik dalam satu hari
            var Difference_In_Days = Difference_In_Time / (1000 * 3600 * 24); 

            // Menentukan jumlah hari izin berdasarkan input
            var jmlhari;
            if (dari == "" || sampai == "") {
                jmlhari = 0; // Jika salah satu input tanggal kosong, jumlah hari izin adalah 0
            } else {
                jmlhari = Difference_In_Days + 1; // Jika kedua tanggal terisi, hitung selisih hari dan tambahkan 1 hari
            }

            // Menampilkan jumlah hari izin pada elemen input dengan ID 'jml_hari'
            $("#jml_hari").val(jmlhari + " Hari"); 
        }
        

            $("#tgl_izin_dari, #tgl_izin_sampai").change(function() {
            var tgl_izin_dari = $("#tgl_izin_dari").val();
            var tgl_izin_sampai = $("#tgl_izin_sampai").val();

            if (tgl_izin_dari && tgl_izin_sampai) {
                $.ajax({
                    type: 'POST',
                    url: '/presensi/cekpengajuanizin',
                    data: {
                        _token: "{{ csrf_token() }}",
                        tgl_izin_dari: tgl_izin_dari,
                        tgl_izin_sampai: tgl_izin_sampai
                    },
                    cache: false,
                    success: function(respond) {
                        if (respond.exists) {
                            Swal.fire({
                                title: 'Oops !',
                                text: 'Anda Sudah Melakukan Input Pengajuan Izin Pada Tanggal Tersebut!',
                                icon: 'warning'
                            }).then((result) => {
                                $("#tgl_izin_dari").val("");
                                $("#tgl_izin_sampai").val("");
                            });
                        } else {
                            var startDate = new Date(tgl_izin_dari);
                            var endDate = new Date(tgl_izin_sampai);
                            var timeDiff = endDate.getTime() - startDate.getTime();
                            var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24)) + 1;
                            $("#jml_hari").val(diffDays);
                        }
                    }
                });
            }
        });

        $("#frmIzin").submit(function(){
            var tgl_izin_dari = $("#tgl_izin_dari").val();
            var tgl_izin_sampai = $("#tgl_izin_sampai").val();
            var keterangan = $("#keterangan").val();
            var kode_cuti = $("#kode_cuti").val();
            if(tgl_izin_dari == "" ||tgl_izin_sampai == ""){
                Swal.fire({
                    title: 'Oops !',
                    text: 'Tanggal Harus Diisi',
                    icon: 'warning'
                })
                return false;
            }else if(kode_cuti == ""){
                Swal.fire({
                    title: 'Oops !',
                    text: 'Kategori Cuti Harus Diisi',
                    icon: 'warning'
                })
                return false;
            }else if(keterangan == ""){
                Swal.fire({
                    title: 'Oops !',
                    text: 'Keterangan Harus Diisi',
                    icon: 'warning'
                })
                return false;
            }
        });

});

</script>
@endpush
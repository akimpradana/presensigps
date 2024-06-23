@extends('layouts.presensi')
@section('header')
<!-- App Header -->
<div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="javascript:;" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">E-Presensi</div>
        <div class="right"></div>
    </div>
    <!-- * App Header -->
@endsection
<style>
    #map { 
        height: 400px; }
</style>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

@section('content')
<div class="row" style="margin-top: 60px;">
    <div class="col">
        {{-- untuk menampilkan lokasi user --}}
        <input type="hidden" id="lokasi"> 
        <div class=web-capture"></div> 
    </div>
</div>

<div class="row mt-2">
    <div class="col">
        <div id="map"></div>
    </div>
</div>

<div class="row">
    <div class="col">
        @if ($cek > 0)
        <button id="takeabsen" class="btn btn-danger btn-block">
            <ion-icon name="location-outline"></ion-icon>
            Absen Pulang</button>
        @else
        <button id="takeabsen" class="btn btn-primary btn-block">
            <ion-icon name="location-outline"></ion-icon>
            Absen Masuk</button>
        @endif
        
    </div>
</div>
@endsection



@push('myscript')
<script>

    var lokasi = document.getElementById('lokasi');
    if(navigator.geolocation){
        navigator.geolocation.getCurrentPosition(successCallback, errorCallback);
    }
    function successCallback(position){
        lokasi.value = position.coords.latitude + "," + position.coords.longitude;
        var map = L.map('map').setView([position.coords.latitude, position.coords.longitude], 18);
        var lokasi_kantor = "{{$lok_kantor->lokasi_kantor}}";
        var lok = lokasi_kantor.split(",");
        var lat_kantor = lok[0];
        var long_kantor = lok[1];
        var radius = "{{$lok_kantor->radius}}";
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);
        var marker = L.marker([position.coords.latitude, position.coords.longitude]).addTo(map);
        var circle = L.circle([lat_kantor,long_kantor], {
            color: 'red',
            fillColor: '#f03',
            fillOpacity: 0.5,
            radius: radius
        }).addTo(map);  
    }
    function errorCallback(){

    }

    $("#takeabsen").click(function(e){
        var lokasi =$('#lokasi').val();
        $.ajax({
            type:'POST',
            url:'/presensi/store',
            data:{
                _token:"{{csrf_token()}}",
                lokasi:lokasi
        },
        cache:false,
        success:function(respond){  
            var status = respond.split("|");
            if(status[0]== '1'){
                Swal.fire({
                    title: 'Berhasil !',
                    text: status[1],
                    icon: 'success'
                })
                setTimeout("location.href='/dashboard'", 3000);
            }else {

                    Swal.fire({
                    title: 'Error !',
                    text: status[1],
                    icon: 'error',
                    });   
            }
        }
        });
    });
</script>
@endpush

    

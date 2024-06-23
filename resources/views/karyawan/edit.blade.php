<form action="/karyawan/{{$karyawan->nik}}/update" method="POST" id="frmKaryawan"  enctype="multipart/form-data">
    @csrf
    
    <div class="row">
        <div class="col-12">
            <div class="input-icon">
                <span class="input-icon-addon">
                  <!-- Download SVG icon from http://tabler-icons.io/i/search -->
                  <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-barcode"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7v-1a2 2 0 0 1 2 -2h2" /><path d="M4 17v1a2 2 0 0 0 2 2h2" /><path d="M16 4h2a2 2 0 0 1 2 2v1" /><path d="M16 20h2a2 2 0 0 0 2 -2v-1" /><path d="M5 11h1v2h-1z" /><path d="M10 11l0 2" /><path d="M14 11h1v2h-1z" /><path d="M19 11l0 2" /></svg>                        </span>
                <input type="text" value="{{$karyawan->nik}}" readonly class="form-control" placeholder="Nik" id="nik" name="nik" aria-label="Search in website">
              </div>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-12">
            <div class="input-icon">
                <span class="input-icon-addon">
                  <!-- Download SVG icon from http://tabler-icons.io/i/search -->
                  <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-user"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /></svg>                        </span>
                <input type="text" value="{{$karyawan->nama_lengkap}}" class="form-control" placeholder="Nama lengkap" id="nama_lengkap" name="nama_lengkap" aria-label="Search in website">
              </div>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-12">
            <select name="kode_jbtn" id="kode_jbtn" class="form-select"  >
                
                <option value="" >Jabatan</option>
                @foreach ($jabatan as $d)
                <option {{$karyawan->kode_jbtn == $d->kode_jbtn ? 'selected' : ''}} value="{{$d->kode_jbtn}}">
                {{$d->nama_jabatan}}
                </option>
                @endforeach
            </select>
      
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-12">
            <div class="input-icon">
                <span class="input-icon-addon">
                  <!-- Download SVG icon from http://tabler-icons.io/i/search -->
                  <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-phone"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2" /></svg>                        </span>
                <input type="text" value="{{$karyawan->no_hp}}" class="form-control" placeholder="No. Hp" id="no_hp" name="no_hp" aria-label="Search in website">
              </div>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-12">
            <input type="file" name="foto" class="form-control">
            <input type="hidden" name="old_foto" value="{{$karyawan->foto}}">
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
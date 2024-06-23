<style>
    .badge-fixed-width {
        width: 80px; /* Sesuaikan lebar sesuai kebutuhan Anda */

        text-align: center;
    }
    

    .btn-sm ion-icon {
        font-size: 16px; /* Sesuaikan ukuran sesuai kebutuhan Anda */
    }
</style>
@foreach ($histori as $index => $d)
    @if($d->jam_in != null)
        <ul class="listview image-listview">
            <li>
                <div class="item">
                    <div class="icon-box">
                        <span class="number-icon">{{ $index + 1 }}</span>
                    </div>
                    <div class="in">
                        <div>{{ date("d-m-y", strtotime($d->tgl_presensi)) }}</div>
                        <span class="badge badge-success badge-fixed-width">{{ $d->jam_in }}</span>
                        <span class="badge badge-danger badge-fixed-width">{{ $d->jam_out != null ? $d->jam_out : 'Belum Absen' }}</span>
                        <button type="button" class="btn btn-primary btn-sm" onclick="showDetail('{{ $d->id }}', '{{ date("d-m-y", strtotime($d->tgl_presensi)) }}', '{{ $d->jam_in }}', '{{ $d->jam_out != null ? $d->jam_out : 'Belum Absen' }}')">
                            Detail
                        </button>
                    </div>
                </div>
            </li>
        </ul>
    @else
        <ul class="listview image-listview">
            <li>
                <div class="item">
                    <div class="icon-box">
                        <span class="number-icon">{{ $index + 1 }}</span>
                    </div>
                    <div class="in">
                        <div>{{ date("d-m-y", strtotime($d->tgl_presensi)) }}</div>
                        <span class="badge badge-danger">{{ $d->status }}</span> <!-- Assuming you have a status field -->
                        <button type="button" class="btn btn-primary btn-sm" onclick="showDetail('{{ $d->id }}', '{{ date("d-m-y", strtotime($d->tgl_presensi)) }}', null, null, '{{ $d->status }}')">
                            Detail
                        </button>
                    </div>
                </div>
            </li>
        </ul>
    @endif
@endforeach

<script>
function showDetail(id, tgl_presensi, jam_in, jam_out, status = null) {
    // Tentukan status berdasarkan waktu masuk jika jam_in tidak null
    var statusText;
    var jammasuk = "{{$jamkerja->jam_masuk}}";
    var jam_masuk_threshold = jammasuk;

    // Fungsi untuk membandingkan waktu
    function compareTime(time1, time2) {
        var t1 = new Date('1970-01-01T' + time1 + 'Z');
        var t2 = new Date('1970-01-01T' + time2 + 'Z');
        return t1.getTime() - t2.getTime();
    }

    if (jam_in !== null) {
        if (compareTime(jam_in, jam_masuk_threshold) > 0) {
            statusText = '<span class="badge bg-danger">Terlambat</span>';
        } else {
            statusText = '<span class="badge bg-success">Tepat Waktu</span>';
        }
    } else {
        switch (status) {
            case 'i':
                statusText = '<span class="badge bg-warning">Izin</span>';
                break;
            case 's':
                statusText = '<span class="badge bg-info">Sakit</span>';
                break;
            case 'c':
                statusText = '<span class="badge bg-primary">Cuti</span>';
                break;
            default:
                statusText = '<span class="badge bg-danger">Tidak Hadir</span>';
                break;
        }
    }

    // Tampilkan pop-up SweetAlert2
    Swal.fire({
        title: 'Detail Presensi',
        html: `
            <p>Tanggal: ${tgl_presensi}</p>
            ${jam_in !== null ? `<p>Jam Masuk: ${jam_in}</p>` : ''}
            ${jam_out !== null ? `<p>Jam Pulang: ${jam_out}</p>` : ''}
            <p>Status: ${statusText}</p>`,
        icon: 'info',
        confirmButtonText: 'Tutup'
    });
}
</script>
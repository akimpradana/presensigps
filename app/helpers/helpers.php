<?php

if (!function_exists('hitunghari1')) {
    function hitunghari1($tanggal_mulai, $tanggal_akhir) {
        $tanggal_1 = date_create($tanggal_mulai);
        $tanggal_2 = date_create($tanggal_akhir);
        $diff = date_diff($tanggal_1, $tanggal_2);
        return $diff->days + 1;
    }
}


if (!function_exists('buatkode')) {
    function buatkode($nomor_terakhir, $kunci, $jumlah_karakter = 0)
    {
        // Mencari nomor baru dengan memecah nomor terakhir dan menambahkan 1
        $nomor_baru = intval(substr($nomor_terakhir, strlen($kunci))) + 1;
        // Menambahkan nol didepan nomor baru sesuai panjang jumlah karakter
        $nomor_baru_plus_nol = str_pad($nomor_baru, $jumlah_karakter, "0", STR_PAD_LEFT);
        // Menyusun kunci dan nomor baru
        $kode = $kunci . $nomor_baru_plus_nol;
        return $kode;
    }
}
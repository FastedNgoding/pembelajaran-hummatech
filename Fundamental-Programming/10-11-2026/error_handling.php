<?php

try {
    $pembagi = 0;
    
    if ($pembagi === 0) {
        throw new Exception("tidak bisa membagi angka dengan nol!");
    }
    
    $hasil = 100 / $pembagi;
    echo "Hasil: " . $hasil;

} catch (Exception $e) {
    echo "Terjadi kesalahan: " . $e->getMessage();

} finally {
    echo "\nProses selesai.";
}
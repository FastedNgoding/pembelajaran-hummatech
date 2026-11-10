<?php 

function tambah(int $angka1, int $angka2) {
    return "Hasil dari {$angka1} + {$angka2} adalah " . $angka1 + $angka2 . PHP_EOL;
}

function pengurangan(int $angka1, int $angka2) {
    return "Hasil dari {$angka1} - {$angka2} adalah " . $angka1 - $angka2 . PHP_EOL;
}

function perkalian(int $angka1, int $angka2) {
    return "Hasil dari {$angka1} x {$angka2} adalah " . $angka1 * $angka2 . PHP_EOL;
}

function pembagian(int $angka1, int $angka2) {
    return "Hasil dari {$angka1} / {$angka2} adalah " . $angka1 / $angka2 . PHP_EOL;
}

echo pembagian(10, 2);
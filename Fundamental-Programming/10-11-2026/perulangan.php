<?php 

// perulangan atau looping

$count = 0;

// Menghitung sampai 100 menggunakan while loop
while ($count !== 101) {
    echo $count . PHP_EOL;
    $count++;
}

// Menghitung sampai 100 menggunakan For Loop
for ($i=0; $i <= 100; $i++) { 
    echo $i . PHP_EOL;
}

// Menghitung sampai 100 menggunakan do while loop
$count2 = 0;
do {
    echo $count2 . PHP_EOL;
    $count2++;
} while ($count2 <= 100);

// foreach loop digunakan untuk array
$data = [
    'nama' => 'John Doe',
    'umur' => 30,
    'pekerjaan' => 'Programmer'
];

foreach ($data as $key => $value) {
    echo $key . ': ' . $value . PHP_EOL;
}
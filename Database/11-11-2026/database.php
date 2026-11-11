<?php

$host = '127.0.0.1';
$db   = 'db_testing_pembelajaran';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

$pdo = new PDO($dsn, $user, $pass, $options);

$stmtDept = $pdo->prepare("INSERT INTO departemen (nama_departemen) VALUES (?)");
$stmtDept->execute(['Teknologi']);
$deptId = $pdo->lastInsertId();

$stmtJabatan = $pdo->prepare("INSERT INTO jabatan (nama_jabatan, gaji_pokok) VALUES (?, ?)");
$stmtJabatan->execute(['Senior Engineer', 15000000.00]);
$jabatanId = $pdo->lastInsertId();

$stmtKaryawan = $pdo->prepare("
    INSERT INTO karyawan (departemen_id, jabatan_id, nik, nama_lengkap, tanggal_masuk, is_aktif) 
    VALUES (?, ?, ?, ?, ?, ?)
");
$stmtKaryawan->execute([$deptId, $jabatanId, 'EMP001', 'Budi Santoso', '2024-01-15', 1]);
$karyawanId = $pdo->lastInsertId();

$stmtProyek = $pdo->prepare("INSERT INTO proyek (nama_proyek, biaya) VALUES (?, ?)");
$stmtProyek->execute(['Modernisasi Sistem', 250000000.00]);
$proyekId = $pdo->lastInsertId();

$stmtAssign = $pdo->prepare("INSERT INTO karyawan_proyek (karyawan_id, proyek_id, peran) VALUES (?, ?, ?)");
$stmtAssign->execute([$karyawanId, $proyekId, 'Lead Architect']);

$stmtSelect = $pdo->prepare("
    SELECT 
        k.nik,
        k.nama_lengkap,
        d.nama_departemen,
        j.nama_jabatan,
        j.gaji_pokok,
        p.nama_proyek,
        kp.peran
    FROM karyawan k
    INNER JOIN departemen d ON k.departemen_id = d.id
    INNER JOIN jabatan j ON k.jabatan_id = j.id
    LEFT JOIN karyawan_proyek kp ON k.id = kp.karyawan_id
    LEFT JOIN proyek p ON kp.proyek_id = p.id
    WHERE k.id = ?
");
$stmtSelect->execute([$karyawanId]);
$hasil = $stmtSelect->fetchAll();

$stmtUpdate = $pdo->prepare("UPDATE karyawan SET nama_lengkap = ? WHERE id = ?");
$stmtUpdate->execute(['Budi Santoso, S.Kom.', $karyawanId]);

$stmtDelete = $pdo->prepare("DELETE FROM karyawan_proyek WHERE karyawan_id = ? AND proyek_id = ?");
$stmtDelete->execute([$karyawanId, $proyekId]);
CREATE DATABASE IF NOT EXISTS belajar_db;
USE belajar_db;

CREATE TABLE IF NOT EXISTS departemen (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_departemen VARCHAR(100) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS jabatan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_jabatan VARCHAR(100) NOT NULL,
    gaji_pokok DECIMAL(12, 2) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS karyawan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    departemen_id INT NOT NULL,
    jabatan_id INT NOT NULL,
    nik VARCHAR(20) NOT NULL UNIQUE,
    nama_lengkap VARCHAR(150) NOT NULL,
    tanggal_masuk DATE NOT NULL,
    is_aktif TINYINT(1) DEFAULT 1,
    CONSTRAINT fk_karyawan_departemen 
        FOREIGN KEY (departemen_id) REFERENCES departemen(id) 
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_karyawan_jabatan 
        FOREIGN KEY (jabatan_id) REFERENCES jabatan(id) 
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS proyek (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_proyek VARCHAR(150) NOT NULL,
    biaya DECIMAL(15, 2) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS karyawan_proyek (
    karyawan_id INT NOT NULL,
    proyek_id INT NOT NULL,
    peran VARCHAR(100) NOT NULL,
    PRIMARY KEY (karyawan_id, proyek_id),
    CONSTRAINT fk_kp_karyawan 
        FOREIGN KEY (karyawan_id) REFERENCES karyawan(id) 
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_kp_proyek 
        FOREIGN KEY (proyek_id) REFERENCES proyek(id) 
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;
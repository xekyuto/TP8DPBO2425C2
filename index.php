<?php

// Memuat file konfigurasi database
require_once "config.php";

// Memuat class induk koneksi database DB
require_once "models/DB.php";

// Memuat class model Dosen 
require_once "models/Dosen.php";

// Memuat class model Prodi 
require_once "models/Prodi.php";

// Memuat class engine template HTML
require_once "views/Template.php";

// Memuat class view DosenView 
require_once "views/DosenView.php";

// Memuat class controller DosenController 
require_once "controllers/DosenController.php";

// Membuat objek controller utama untuk mengelola modul Dosen
$controller = new DosenController();

// Membaca parameter 'aksi' dari URL
$aksi = $_GET['aksi'] ?? 'index';

// Membaca parameter 'id' dari URL jika tersedia 
$id = $_GET['id'] ?? null;

// Menentukan method controller yang dijalankan berdasarkan nilai parameter $aksi
switch ($aksi) {
    // Jika URL berisi ?aksi=tambah, jalankan proses tambah dosen
    case 'tambah':
        $controller->tambah();
        break;

    // Jika URL berisi ?aksi=ubah, jalankan proses ubah dosen berdasarkan $id
    case 'ubah':
        // Memastikan parameter $id tersedia sebelum memanggil method ubah()
        if ($id !== null) {
            $controller->ubah($id);
        } else {
            // Jika $id kosong, kembalikan pengguna ke halaman utama
            header("Location: index.php");
        }
        break;

    // Jika URL berisi ?aksi=hapus, jalankan proses hapus dosen berdasarkan $id
    case 'hapus':
        // Memastikan parameter $id tersedia sebelum memanggil method hapus()
        if ($id !== null) {
            $controller->hapus($id);
        } else {
            // Jika $id kosong, kembalikan pengguna ke halaman utama
            header("Location: index.php");
        }
        break;

    // Jika tidak ada parameter aksi khusus, tampilkan halaman utama daftar dosen
    default:
        $controller->index();
        break;
}
<?php

// Memuat file konfigurasi database
require_once "config.php";

// Memuat class induk koneksi database DB
require_once "models/DB.php";

// Memuat class model Prodi
require_once "models/Prodi.php";

// Memuat class engine template HTML
require_once "views/Template.php";

// Memuat class view ProdiView
require_once "views/ProdiView.php";

// Memuat class controller ProdiController
require_once "controllers/ProdiController.php";

// Membuat objek controller untuk mengelola modul Prodi
$controller = new ProdiController();

// Membaca parameter 'aksi' dari URL (jika tidak ada, default bernilai 'index')
$aksi = $_GET['aksi'] ?? 'index';

// Membaca parameter 'id' dari URL jika tersedia (jika tidak ada, bernilai null)
$id = $_GET['id'] ?? null;

// Menentukan method controller yang dijalankan berdasarkan nilai parameter $aksi
switch ($aksi) {
    // Jika URL berisi ?aksi=tambah, jalankan proses tambah prodi
    case 'tambah':
        $controller->tambah();
        break;

    // Jika URL berisi ?aksi=ubah, jalankan proses ubah prodi berdasarkan $id
    case 'ubah':
        // Memastikan parameter $id tersedia sebelum memanggil method ubah()
        if ($id !== null) {
            $controller->ubah($id);
        } else {
            // Jika $id kosong, kembalikan pengguna ke halaman prodi.php
            header("Location: prodi.php");
        }
        break;

    // Jika URL berisi ?aksi=hapus, jalankan proses hapus prodi berdasarkan $id
    case 'hapus':
        // Memastikan parameter $id tersedia sebelum memanggil method hapus()
        if ($id !== null) {
            $controller->hapus($id);
        } else {
            // Jika $id kosong, kembalikan pengguna ke halaman prodi.php
            header("Location: prodi.php");
        }
        break;

    // Jika tidak ada parameter aksi khusus, tampilkan halaman daftar prodi
    default:
        $controller->index();
        break;
}
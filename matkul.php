<?php

// Memuat file konfigurasi database
require_once "config.php";

// Memuat class induk koneksi database DB
require_once "models/DB.php";

// Memuat class model Matkul
require_once "models/Matkul.php";

// Memuat class model Dosen (dibutuhkan untuk pilihan dropdown dosen pengampu)
require_once "models/Dosen.php";

// Memuat class engine template HTML
require_once "views/Template.php";

// Memuat class view MatkulView
require_once "views/MatkulView.php";

// Memuat class controller MatkulController
require_once "controllers/MatkulController.php";

// Membuat objek controller untuk mengelola modul Mata Kuliah
$controller = new MatkulController();

// Membaca parameter 'aksi' dari URL (jika tidak ada, default bernilai 'index')
$aksi = $_GET['aksi'] ?? 'index';

// Membaca parameter 'id' dari URL jika tersedia (jika tidak ada, bernilai null)
$id = $_GET['id'] ?? null;

// Menentukan method controller yang dijalankan berdasarkan nilai parameter $aksi
switch ($aksi) {
    // Jika URL berisi ?aksi=tambah, jalankan proses tambah mata kuliah
    case 'tambah':
        $controller->tambah();
        break;

    // Jika URL berisi ?aksi=ubah, jalankan proses ubah mata kuliah berdasarkan $id
    case 'ubah':
        // Memastikan parameter $id tersedia sebelum memanggil method ubah()
        if ($id !== null) {
            $controller->ubah($id);
        } else {
            // Jika $id kosong, kembalikan pengguna ke halaman matkul.php
            header("Location: matkul.php");
        }
        break;

    // Jika URL berisi ?aksi=hapus, jalankan proses hapus mata kuliah berdasarkan $id
    case 'hapus':
        // Memastikan parameter $id tersedia sebelum memanggil method hapus()
        if ($id !== null) {
            $controller->hapus($id);
        } else {
            // Jika $id kosong, kembalikan pengguna ke halaman matkul.php
            header("Location: matkul.php");
        }
        break;

    // Jika tidak ada parameter aksi khusus, tampilkan halaman daftar mata kuliah
    default:
        $controller->index();
        break;
}
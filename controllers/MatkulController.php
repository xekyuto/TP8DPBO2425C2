<?php

// Mendefinisikan class Controller MatkulController untuk mengatur alur request, Model, dan View Mata Kuliah
class MatkulController
{
    // Properti privat untuk menyimpan objek model Matkul
    private $matkulModel;

    // Properti privat untuk menyimpan objek model Dosen
    private $dosenModel;

    // Properti privat untuk menyimpan objek view MatkulView
    private $view;

    // Constructor yang otomatis menginisialisasi objek Model dan View saat Controller dibuat
    public function __construct()
    {
        // Membuat instance model Matkul dengan konfigurasi database dari class Config
        $this->matkulModel = new Matkul(Config::$db_host, Config::$db_user, Config::$db_pass, Config::$db_name);

        // Membuat instance model Dosen untuk mengambil daftar dosen pengampu pada dropdown form
        $this->dosenModel = new Dosen(Config::$db_host, Config::$db_user, Config::$db_pass, Config::$db_name);

        // Membuat instance view MatkulView untuk merender tampilan HTML
        $this->view = new MatkulView();
    }

    // Method utama untuk menampilkan halaman daftar seluruh mata kuliah
    public function index()
    {
        // Membuka koneksi database melalui model Matkul
        $this->matkulModel->open();

        // Menjalankan query pengambilan seluruh data mata kuliah beserta nama dosen pengampunya
        $this->matkulModel->getAllMatkul();

        // Menyiapkan array kosong untuk menampung seluruh baris hasil query
        $data = [];

        // Mengambil setiap baris hasil query satu per satu hingga habis
        while ($row = $this->matkulModel->getResult()) {
            // Memasukkan baris data ke dalam array $data
            $data[] = $row;
        }

        // Menutup koneksi database setelah data selesai diambil
        $this->matkulModel->close();

        // Mengirimkan array data mata kuliah ke MatkulView untuk ditampilkan dalam tabel
        $this->view->render($data);
    }

    // Method untuk menangani tampilan form tambah mata kuliah dan proses penyimpanan data baru
    public function tambah()
    {
        // Mengecek apakah pengguna mengirimkan data form menggunakan metode POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Membuka koneksi database
            $this->matkulModel->open();

            // Menyimpan data kiriman form ($_POST) ke tabel matkul melalui model
            $this->matkulModel->tambahMatkul($_POST);

            // Menutup koneksi database
            $this->matkulModel->close();

            // Mengarahkan kembali browser ke halaman utama matkul.php setelah data tersimpan
            header("Location: matkul.php");
            // Menghentikan eksekusi script agar redirect langsung berjalan
            exit;
        }

        // Jika bukan POST (pengguna baru membuka halaman form), ambil daftar dosen untuk dropdown
        $daftarDosen = $this->ambilSemuaDosen();

        // Menampilkan form tambah mata kuliah kosong beserta pilihan dosen pengampu
        $this->view->renderForm($daftarDosen);
    }

    // Method untuk menangani tampilan form ubah mata kuliah dan proses pembaruan data
    public function ubah($id)
    {
        // Mengecek apakah pengguna mengirimkan perubahan data form menggunakan metode POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Membuka koneksi database
            $this->matkulModel->open();

            // Memperbarui data mata kuliah berdasarkan $id dan isi $_POST
            $this->matkulModel->ubahMatkul($id, $_POST);

            // Menutup koneksi database
            $this->matkulModel->close();

            // Mengarahkan kembali browser ke halaman utama matkul.php setelah data diperbarui
            header("Location: matkul.php");
            // Menghentikan eksekusi script agar redirect langsung berjalan
            exit;
        }

        // Jika bukan POST, ambil data lama mata kuliah berdasarkan $id untuk mengisi form
        $this->matkulModel->open();
        $this->matkulModel->getMatkulById($id);
        $matkul = $this->matkulModel->getResult();
        $this->matkulModel->close();

        // Jika data mata kuliah dengan ID tersebut tidak ditemukan di database, kembalikan ke matkul.php
        if (!$matkul) {
            header("Location: matkul.php");
            exit;
        }

        // Mengambil seluruh daftar dosen untuk pilihan dropdown
        $daftarDosen = $this->ambilSemuaDosen();

        // Menampilkan form ubah mata kuliah yang sudah terisi data lama mata kuliah tersebut
        $this->view->renderForm($daftarDosen, $matkul);
    }

    // Method untuk menangani penghapusan data mata kuliah berdasarkan ID
    public function hapus($id)
    {
        // Membuka koneksi database
        $this->matkulModel->open();

        // Menghapus baris data mata kuliah dengan ID yang diminta
        $this->matkulModel->hapusMatkul($id);

        // Menutup koneksi database
        $this->matkulModel->close();

        // Mengarahkan kembali browser ke halaman utama matkul.php setelah data terhapus
        header("Location: matkul.php");
        // Menghentikan eksekusi script agar redirect langsung berjalan
        exit;
    }

    // Method pembantu (helper) privat untuk mengambil semua data dosen dalam bentuk array
    private function ambilSemuaDosen()
    {
        // Membuka koneksi database melalui model Dosen
        $this->dosenModel->open();

        // Menjalankan query pengambilan seluruh data dosen
        $this->dosenModel->getAllDosen();

        // Menyiapkan array kosong untuk menampung hasil query dosen
        $daftarDosen = [];

        // Mengambil setiap baris dosen dan memasukkannya ke array
        while ($row = $this->dosenModel->getResult()) {
            $daftarDosen[] = $row;
        }

        // Menutup koneksi database
        $this->dosenModel->close();

        // Mengembalikan array berisi seluruh daftar dosen
        return $daftarDosen;
    }
}
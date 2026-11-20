<?php

class DosenController
{
    // Properti privat untuk menyimpan objek model Dosen
    private $dosenModel;

    // Properti privat untuk menyimpan objek model Prodi
    private $prodiModel;

    // Properti privat untuk menyimpan objek view DosenView
    private $view;

    // Constructor yang otomatis menginisialisasi objek Model dan View saat Controller dibuat
    public function __construct()
    {
        // Membuat instance model Dosen dengan konfigurasi database dari class Config
        $this->dosenModel = new Dosen(Config::$db_host, Config::$db_user, Config::$db_pass, Config::$db_name);

        // Membuat instance model Prodi untuk mengambil daftar prodi pada dropdown form
        $this->prodiModel = new Prodi(Config::$db_host, Config::$db_user, Config::$db_pass, Config::$db_name);

        // Membuat instance view DosenView untuk merender tampilan HTML
        $this->view = new DosenView();
    }

    // Method utama untuk menampilkan halaman daftar seluruh dosen
    public function index()
    {
        // Membuka koneksi database melalui model Dosen
        $this->dosenModel->open();

        // Menjalankan query pengambilan seluruh data dosen beserta nama prodinya
        $this->dosenModel->getAllDosen();

        // Menyiapkan array kosong untuk menampung seluruh baris hasil query
        $data = [];

        // Mengambil setiap baris hasil query satu per satu hingga habis
        while ($row = $this->dosenModel->getResult()) {
            // Memasukkan baris data ke dalam array $data
            $data[] = $row;
        }

        // Menutup koneksi database setelah data selesai diambil
        $this->dosenModel->close();

        // Mengirimkan array data dosen ke DosenView untuk ditampilkan dalam tabel
        $this->view->render($data);
    }

    // Method untuk menangani tampilan form tambah dosen dan proses penyimpanan data baru
    public function tambah()
    {
        // Mengecek apakah pengguna mengirimkan data form menggunakan metode POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Membuka koneksi database
            $this->dosenModel->open();

            // Menyimpan data kiriman form ($_POST) ke tabel dosen melalui model
            $this->dosenModel->tambahDosen($_POST);

            // Menutup koneksi database
            $this->dosenModel->close();

            // Mengarahkan kembali browser ke halaman utama index.php setelah data tersimpan
            header("Location: index.php");
            // Menghentikan eksekusi script agar redirect langsung berjalan
            exit;
        }

        // Jika bukan POST (pengguna baru membuka halaman form), ambil daftar prodi untuk dropdown
        $daftarProdi = $this->ambilSemuaProdi();

        // Menampilkan form tambah dosen kosong beserta pilihan prodi
        $this->view->renderForm($daftarProdi);
    }

    // Method untuk menangani tampilan form ubah dosen dan proses pembaruan data
    public function ubah($id)
    {
        // Mengecek apakah pengguna mengirimkan perubahan data form menggunakan metode POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Membuka koneksi database
            $this->dosenModel->open();

            // Memperbarui data dosen berdasarkan $id dan isi $_POST
            $this->dosenModel->ubahDosen($id, $_POST);

            // Menutup koneksi database
            $this->dosenModel->close();

            // Mengarahkan kembali browser ke halaman utama index.php setelah data diperbarui
            header("Location: index.php");
            // Menghentikan eksekusi script
            exit;
        }

        // Jika bukan POST, ambil data lama dosen berdasarkan $id untuk mengisi form
        $this->dosenModel->open();
        $this->dosenModel->getDosenById($id);
        $dosen = $this->dosenModel->getResult();
        $this->dosenModel->close();

        // Jika data dosen dengan ID tersebut tidak ditemukan di database, kembalikan ke index.php
        if (!$dosen) {
            header("Location: index.php");
            exit;
        }

        // Mengambil seluruh daftar prodi untuk pilihan dropdown
        $daftarProdi = $this->ambilSemuaProdi();

        // Menampilkan form ubah dosen yang sudah terisi data lama dosen tersebut
        $this->view->renderForm($daftarProdi, $dosen);
    }

    // Method untuk menangani penghapusan data dosen berdasarkan ID
    public function hapus($id)
    {
        // Membuka koneksi database
        $this->dosenModel->open();

        // Menghapus baris data dosen dengan ID yang diminta
        $this->dosenModel->hapusDosen($id);

        // Menutup koneksi database
        $this->dosenModel->close();

        // Mengarahkan kembali browser ke halaman utama index.php setelah data terhapus
        header("Location: index.php");
        // Menghentikan eksekusi script
        exit;
    }

    // Method pembantu (helper) privat untuk mengambil semua data prodi dalam bentuk array
    private function ambilSemuaProdi()
    {
        // Membuka koneksi database melalui model Prodi
        $this->prodiModel->open();

        // Menjalankan query pengambilan seluruh data prodi
        $this->prodiModel->getAllProdi();

        // Menyiapkan array kosong untuk menampung hasil query prodi
        $daftarProdi = [];

        // Mengambil setiap baris prodi dan memasukkannya ke array
        while ($row = $this->prodiModel->getResult()) {
            $daftarProdi[] = $row;
        }

        // Menutup koneksi database
        $this->prodiModel->close();

        // Mengembalikan array berisi seluruh daftar prodi
        return $daftarProdi;
    }
}
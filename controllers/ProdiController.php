<?php

// Mendefinisikan class Controller ProdiController untuk mengatur alur request, Model, dan View Prodi
class ProdiController
{
    // Properti privat untuk menyimpan objek model Prodi
    private $prodiModel;

    // Properti privat untuk menyimpan objek view ProdiView
    private $view;

    // Constructor yang otomatis menginisialisasi objek Model dan View saat Controller dibuat
    public function __construct()
    {
        // Membuat instance model Prodi dengan konfigurasi database dari class Config
        $this->prodiModel = new Prodi(Config::$db_host, Config::$db_user, Config::$db_pass, Config::$db_name);

        // Membuat instance view ProdiView untuk merender tampilan HTML
        $this->view = new ProdiView();
    }

    // Method utama untuk menampilkan halaman daftar seluruh program studi
    public function index()
    {
        // Membuka koneksi database melalui model Prodi
        $this->prodiModel->open();

        // Menjalankan query pengambilan seluruh data prodi
        $this->prodiModel->getAllProdi();

        // Menyiapkan array kosong untuk menampung seluruh baris hasil query
        $data = [];

        // Mengambil setiap baris hasil query satu per satu hingga habis
        while ($row = $this->prodiModel->getResult()) {
            // Memasukkan baris data ke dalam array $data
            $data[] = $row;
        }

        // Menutup koneksi database setelah data selesai diambil
        $this->prodiModel->close();

        // Mengirimkan array data prodi ke ProdiView untuk ditampilkan dalam tabel
        $this->view->render($data);
    }

    // Method untuk menangani tampilan form tambah prodi dan proses penyimpanan data baru
    public function tambah()
    {
        // Mengecek apakah pengguna mengirimkan data form menggunakan metode POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Membuka koneksi database
            $this->prodiModel->open();

            // Menyimpan data kiriman form ($_POST) ke tabel prodi melalui model
            $this->prodiModel->tambahProdi($_POST);

            // Menutup koneksi database
            $this->prodiModel->close();

            // Mengarahkan kembali browser ke halaman utama prodi.php setelah data tersimpan
            header("Location: prodi.php");
            // Menghentikan eksekusi script agar redirect langsung berjalan
            exit;
        }

        // Jika bukan POST (pengguna baru membuka halaman form), tampilkan form tambah prodi kosong
        $this->view->renderForm();
    }

    // Method untuk menangani tampilan form ubah prodi dan proses pembaruan data
    public function ubah($id)
    {
        // Mengecek apakah pengguna mengirimkan perubahan data form menggunakan metode POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Membuka koneksi database
            $this->prodiModel->open();

            // Memperbarui data prodi berdasarkan $id dan isi $_POST
            $this->prodiModel->ubahProdi($id, $_POST);

            // Menutup koneksi database
            $this->prodiModel->close();

            // Mengarahkan kembali browser ke halaman utama prodi.php setelah data diperbarui
            header("Location: prodi.php");
            // Menghentikan eksekusi script agar redirect langsung berjalan
            exit;
        }

        // Jika bukan POST, ambil data lama prodi berdasarkan $id untuk mengisi form
        $this->prodiModel->open();
        $this->prodiModel->getProdiById($id);
        $prodi = $this->prodiModel->getResult();
        $this->prodiModel->close();

        // Jika data prodi dengan ID tersebut tidak ditemukan di database, kembalikan ke prodi.php
        if (!$prodi) {
            header("Location: prodi.php");
            exit;
        }

        // Menampilkan form ubah prodi yang sudah terisi data lama prodi tersebut
        $this->view->renderForm($prodi);
    }

    // Method untuk menangani penghapusan data prodi berdasarkan ID
    public function hapus($id)
    {
        // Membuka koneksi database
        $this->prodiModel->open();

        // Mengecek terlebih dahulu apakah prodi masih memiliki dosen terdaftar (ON DELETE RESTRICT)
        if ($this->prodiModel->cekDipakaiDosen($id)) {
            // Menutup koneksi database
            $this->prodiModel->close();

            // Menampilkan peringatan JavaScript agar tidak terjadi error fatal Foreign Key MySQL
            echo "<script>
                    alert('Gagal menghapus! Prodi ini masih memiliki data dosen yang terdaftar.');
                    window.location.href = 'prodi.php';
                  </script>";
            exit;
        }

        // Jika aman (tidak ada dosen terkait), hapus baris data prodi dengan ID yang diminta
        $this->prodiModel->hapusProdi($id);

        // Menutup koneksi database
        $this->prodiModel->close();

        // Mengarahkan kembali browser ke halaman utama prodi.php setelah data terhapus
        header("Location: prodi.php");
        // Menghentikan eksekusi script agar redirect langsung berjalan
        exit;
    }
}
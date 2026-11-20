<?php

class DB{
    private $db_host = "";//properti privat untuk menyimpan alamat host database
    private $db_user = "";//properti privat untuk menyimpan username database
    private $db_pass = "";//properti privat untuk menyimpan password database
    private $db_name = "";//properti privat untuk menyimpan nama database
    private $db_link = null;//properti privat untuk menyimpan objek koneksi aktif dari mysqli_connect
    private $result = null;//properti privat untuk menyimpan hasil eksekusi query terakhir

    // Constructor yang otomatis dijalankan saat objek DB atau turunannya dibuat
    public function __construct($db_host, $db_user, $db_pass, $db_name)
    {
        // Mengisi properti host dari parameter constructor
        $this->db_host = $db_host;

        // Mengisi properti user dari parameter constructor
        $this->db_user = $db_user;

        // Mengisi properti password dari parameter constructor
        $this->db_pass = $db_pass;

        // Mengisi properti nama database dari parameter constructor
        $this->db_name = $db_name;
    }

    // Method untuk membuka koneksi ke server MySQL
    public function open()
    {
        // Membuat koneksi MySQL menggunakan fungsi prosedural mysqli_connect
        $this->db_link = mysqli_connect($this->db_host, $this->db_user, $this->db_pass, $this->db_name);

        // Mengecek apakah koneksi gagal dibuat
        if (!$this->db_link) {
            // Menghentikan program dan menampilkan pesan error koneksi jika gagal
            die("Koneksi database gagal: " . mysqli_connect_error());
        }
    }

    // Method untuk mengeksekusi perintah SQL (SELECT, INSERT, UPDATE, DELETE)
    public function execute($query)
    {
        // Menjalankan query pada koneksi aktif dan menyimpan hasilnya ke properti $result
        $this->result = mysqli_query($this->db_link, $query);

        // Mengecek apakah query mengalami kesalahan sintaks atau kendala database
        if (!$this->result) {
            // Menghentikan program dan menampilkan pesan error SQL dari MySQL
            die("Query error: " . mysqli_error($this->db_link));
        }

        // Mengembalikan hasil eksekusi query
        return $this->result;
    }

    // Method untuk mengambil satu baris data hasil query SELECT dalam bentuk array asosiatif
    public function getResult()
    {
        // Mengambil baris berikutnya dari $this->result (mengembalikan null jika baris sudah habis)
        return mysqli_fetch_assoc($this->result);
    }

    // Method untuk membersihkan karakter khusus pada input string agar aman dari SQL Injection
    public function escapeString($str)
    {
        // Mengamankan string berdasarkan karakter set koneksi database aktif
        return mysqli_real_escape_string($this->db_link, $str);
    }

    // Method untuk menutup koneksi database setelah selesai digunakan
    public function close()
    {
        // Mengecek apakah koneksi database masih terbuka
        if ($this->db_link) {
            // Menutup koneksi MySQL untuk menghemat memori server
            mysqli_close($this->db_link);
        }
    }
}
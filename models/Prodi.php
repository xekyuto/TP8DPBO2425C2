<?php

// Mendefinisikan class Model Prodi yang mewarisi fitur koneksi database dari class DB
class Prodi extends DB
{
    // Method untuk mengambil seluruh data program studi dari tabel 'prodi'
    public function getAllProdi()
    {
        // Menyusun query SQL untuk mengambil semua kolom prodi diurutkan berdasarkan id terkecil
        $query = "SELECT * FROM prodi ORDER BY id ASC";

        // Menjalankan query melalui method execute() milik class induk DB
        return $this->execute($query);
    }

    // Method untuk mengambil satu data prodi spesifik berdasarkan ID (dipakai saat halaman Ubah dibuka)
    public function getProdiById($id)
    {
        // Mengonversi parameter $id menjadi integer untuk mencegah SQL Injection
        $id = (int) $id;

        // Menyusun query SQL untuk mencari baris prodi dengan id yang sesuai
        $query = "SELECT * FROM prodi WHERE id = $id";

        // Menjalankan query SQL dan mengembalikan hasilnya
        return $this->execute($query);
    }

    // Method untuk menyimpan data program studi baru ke dalam tabel 'prodi'
    public function tambahProdi($data)
    {
        // Mengamankan input nama prodi dari karakter khusus berbahaya
        $nama_prodi = $this->escapeString($data['nama_prodi']);

        // Mengamankan input nama fakultas dari karakter khusus berbahaya
        $fakultas = $this->escapeString($data['fakultas']);

        // Menyusun query SQL INSERT untuk menambahkan baris baru ke tabel 'prodi'
        $query = "INSERT INTO prodi (nama_prodi, fakultas) 
                  VALUES ('$nama_prodi', '$fakultas')";

        // Menjalankan query INSERT ke database
        return $this->execute($query);
    }

    // Method untuk memperbarui data program studi yang sudah ada berdasarkan ID
    public function ubahProdi($id, $data)
    {
        // Mengonversi parameter $id menjadi integer
        $id = (int) $id;

        // Mengamankan input nama prodi yang baru
        $nama_prodi = $this->escapeString($data['nama_prodi']);

        // Mengamankan input nama fakultas yang baru
        $fakultas = $this->escapeString($data['fakultas']);

        // Menyusun query SQL UPDATE untuk mengubah kolom pada baris prodi dengan id tertentu
        $query = "UPDATE prodi 
                  SET nama_prodi = '$nama_prodi', 
                      fakultas = '$fakultas' 
                  WHERE id = $id";

        // Menjalankan query UPDATE ke database
        return $this->execute($query);
    }

    // Method untuk mengecek apakah prodi masih digunakan oleh data di tabel 'dosen' (aturan ON DELETE RESTRICT)
    public function cekDipakaiDosen($id)
    {
        // Mengonversi parameter $id menjadi integer
        $id = (int) $id;

        // Menyusun query SQL untuk menghitung jumlah dosen yang terdaftar pada prodi tersebut
        $query = "SELECT COUNT(*) AS total FROM dosen WHERE prodi_id = $id";

        // Menjalankan query perhitungan
        $this->execute($query);

        // Mengambil hasil baris perhitungan dalam bentuk array asosiatif
        $row = $this->getResult();

        // Mengembalikan nilai true jika masih ada dosen (total > 0), atau false jika kosong
        return ($row['total'] > 0);
    }

    // Method untuk menghapus data program studi dari database berdasarkan ID
    public function hapusProdi($id)
    {
        // Mengonversi parameter $id menjadi integer
        $id = (int) $id;

        // Menyusun query SQL DELETE untuk menghapus baris prodi dengan id yang sesuai
        $query = "DELETE FROM prodi WHERE id = $id";

        // Menjalankan query DELETE ke database
        return $this->execute($query);
    }
}
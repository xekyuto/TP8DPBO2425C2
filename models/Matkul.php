<?php

// Mendefinisikan class Model Matkul yang mewarisi fitur koneksi database dari class DB
class Matkul extends DB
{
    // Method untuk mengambil seluruh data mata kuliah beserta nama dosen pengampunya melalui relasi JOIN
    public function getAllMatkul()
    {
        // Menyusun query SQL JOIN antara tabel 'matkul' dan 'dosen' berdasarkan kecocokan dosen_id
        $query = "SELECT matkul.*, dosen.nama AS nama_dosen 
                  FROM matkul 
                  JOIN dosen ON matkul.dosen_id = dosen.id 
                  ORDER BY matkul.id ASC";

        // Menjalankan query SQL dan mengembalikan hasilnya
        return $this->execute($query);
    }

    // Method untuk mengambil satu data mata kuliah spesifik berdasarkan ID (dipakai saat halaman Ubah dibuka)
    public function getMatkulById($id)
    {
        // Mengonversi parameter $id menjadi integer untuk mencegah SQL Injection
        $id = (int) $id;

        // Menyusun query SQL untuk mencari baris matkul dengan id yang sesuai
        $query = "SELECT * FROM matkul WHERE id = $id";

        // Menjalankan query SQL dan mengembalikan hasilnya
        return $this->execute($query);
    }

    // Method untuk menyimpan data mata kuliah baru ke dalam tabel 'matkul'
    public function tambahMatkul($data)
    {
        // Mengamankan input kode mata kuliah dari karakter khusus berbahaya
        $kode_matkul = $this->escapeString($data['kode_matkul']);

        // Mengamankan input nama mata kuliah dari karakter khusus berbahaya
        $nama_matkul = $this->escapeString($data['nama_matkul']);

        // Mengonversi input jumlah SKS menjadi bilangan bulat (integer)
        $sks = (int) $data['sks'];

        // Mengonversi input dosen_id menjadi bilangan bulat (integer)
        $dosen_id = (int) $data['dosen_id'];

        // Menyusun query SQL INSERT untuk menambahkan baris baru ke tabel 'matkul'
        $query = "INSERT INTO matkul (kode_matkul, nama_matkul, sks, dosen_id) 
                  VALUES ('$kode_matkul', '$nama_matkul', $sks, $dosen_id)";

        // Menjalankan query INSERT ke database
        return $this->execute($query);
    }

    // Method untuk memperbarui data mata kuliah yang sudah ada berdasarkan ID
    public function ubahMatkul($id, $data)
    {
        // Mengonversi parameter $id menjadi integer
        $id = (int) $id;

        // Mengamankan input kode mata kuliah yang baru
        $kode_matkul = $this->escapeString($data['kode_matkul']);

        // Mengamankan input nama mata kuliah yang baru
        $nama_matkul = $this->escapeString($data['nama_matkul']);

        // Mengonversi input jumlah SKS yang baru menjadi integer
        $sks = (int) $data['sks'];

        // Mengonversi input dosen_id yang baru menjadi integer
        $dosen_id = (int) $data['dosen_id'];

        // Menyusun query SQL UPDATE untuk mengubah kolom pada baris matkul dengan id tertentu
        $query = "UPDATE matkul 
                  SET kode_matkul = '$kode_matkul', 
                      nama_matkul = '$nama_matkul', 
                      sks = $sks, 
                      dosen_id = $dosen_id 
                  WHERE id = $id";

        // Menjalankan query UPDATE ke database
        return $this->execute($query);
    }

    // Method untuk menghapus data mata kuliah dari database berdasarkan ID
    public function hapusMatkul($id)
    {
        // Mengonversi parameter $id menjadi integer
        $id = (int) $id;

        // Menyusun query SQL DELETE untuk menghapus baris matkul dengan id yang sesuai
        $query = "DELETE FROM matkul WHERE id = $id";

        // Menjalankan query DELETE ke database
        return $this->execute($query);
    }
}
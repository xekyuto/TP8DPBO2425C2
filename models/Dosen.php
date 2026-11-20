<?php

class Dosen extends DB
{
    // Method untuk mengambil seluruh data dosen beserta nama prodi melalui relasi JOIN
    public function getAllDosen()
    {
        // Menyusun query SQL JOIN antara tabel 'dosen' dan 'prodi' berdasarkan kecocokan prodi_id
        $query = "SELECT dosen.*, prodi.nama_prodi 
                  FROM dosen 
                  JOIN prodi ON dosen.prodi_id = prodi.id 
                  ORDER BY dosen.id ASC";

        // Menjalankan query SQL dan mengembalikan hasilnya
        return $this->execute($query);
    }

    // Method untuk mengambil satu data dosen spesifik berdasarkan ID (dipakai saat halaman Ubah dibuka)
    public function getDosenById($id)
    {
        // Mengonversi parameter $id menjadi integer untuk mencegah SQL Injection
        $id = (int) $id;

        // Menyusun query SQL untuk mencari baris dosen dengan id yang sesuai
        $query = "SELECT * FROM dosen WHERE id = $id";

        // Menjalankan query SQL dan mengembalikan hasilnya
        return $this->execute($query);
    }

    // Method untuk menyimpan data dosen baru ke dalam tabel 'dosen'
    public function tambahDosen($data)
    {
        // Mengamankan input nama dosen dari karakter berbahaya
        $nama = $this->escapeString($data['nama']);

        // Mengamankan input NIDN dosen dari karakter berbahaya
        $nidn = $this->escapeString($data['nidn']);

        // Mengamankan input nomor HP dosen dari karakter berbahaya
        $no_hp = $this->escapeString($data['no_hp']);

        // Mengamankan input tanggal bergabung dosen dari karakter berbahaya
        $tanggal_gabung = $this->escapeString($data['tanggal_gabung']);

        // Mengonversi input prodi_id menjadi tipe integer
        $prodi_id = (int) $data['prodi_id'];

        // Menyusun query SQL INSERT untuk menambahkan baris baru ke tabel 'dosen'
        $query = "INSERT INTO dosen (nama, nidn, no_hp, tanggal_gabung, prodi_id) 
                  VALUES ('$nama', '$nidn', '$no_hp', '$tanggal_gabung', $prodi_id)";

        // Menjalankan query INSERT ke database
        return $this->execute($query);
    }

    // Method untuk memperbarui data dosen yang sudah ada berdasarkan ID
    public function ubahDosen($id, $data)
    {
        // Mengonversi parameter $id menjadi integer
        $id = (int) $id;

        // Mengamankan input nama dosen yang baru
        $nama = $this->escapeString($data['nama']);

        // Mengamankan input NIDN dosen yang baru
        $nidn = $this->escapeString($data['nidn']);

        // Mengamankan input nomor HP dosen yang baru
        $no_hp = $this->escapeString($data['no_hp']);

        // Mengamankan input tanggal bergabung yang baru
        $tanggal_gabung = $this->escapeString($data['tanggal_gabung']);

        // Mengonversi input prodi_id yang baru menjadi integer
        $prodi_id = (int) $data['prodi_id'];

        // Menyusun query SQL UPDATE untuk mengubah kolom pada baris dosen dengan id tertentu
        $query = "UPDATE dosen 
                  SET nama = '$nama', 
                      nidn = '$nidn', 
                      no_hp = '$no_hp', 
                      tanggal_gabung = '$tanggal_gabung', 
                      prodi_id = $prodi_id 
                  WHERE id = $id";

        // Menjalankan query UPDATE ke database
        return $this->execute($query);
    }

    // Method untuk menghapus data dosen dari database berdasarkan ID
    public function hapusDosen($id)
    {
        // Mengonversi parameter $id menjadi integer
        $id = (int) $id;

        // Menyusun query SQL DELETE untuk menghapus baris dosen dengan id yang sesuai
        $query = "DELETE FROM dosen WHERE id = $id";

        // Menjalankan query DELETE ke database
        return $this->execute($query);
    }
}
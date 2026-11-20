<?php

// Mendefinisikan class View ProdiView untuk menyusun tampilan HTML halaman Prodi
class ProdiView
{
    // Method untuk merender halaman utama tabel daftar program studi
    public function render($daftarProdi)
    {
        // Menyiapkan variabel string kosong untuk menampung seluruh baris <tr> tabel
        $barisTabel = "";

        // Melakukan perulangan untuk setiap baris data prodi yang diterima dari Controller
        foreach ($daftarProdi as $row) {
            // Mengamankan nilai id agar aman ditampilkan di HTML
            $id = htmlspecialchars($row['id']);

            // Mengamankan nilai nama prodi dari karakter HTML berbahaya (XSS)
            $nama_prodi = htmlspecialchars($row['nama_prodi']);

            // Mengamankan nilai nama fakultas
            $fakultas = htmlspecialchars($row['fakultas']);

            // Merangkai satu baris <tr> HTML lengkap dengan kolom data dan tombol tindakan Ubah/Hapus
            $barisTabel .= "<tr>
                <th>{$id}</th>
                <td>{$nama_prodi}</td>
                <td>{$fakultas}</td>
                <td>
                    <a class='btn btn-sm btn-warning' href='prodi.php?aksi=ubah&id={$id}'>Ubah</a>
                    <a class='btn btn-sm btn-danger' href='prodi.php?aksi=hapus&id={$id}' onclick=\"return confirm('Yakin ingin menghapus data prodi ini?')\">Hapus</a>
                </td>
            </tr>";
        }

        // Membuat objek Template dengan memuat kerangka HTML dari templates/prodi.html
        $tpl = new Template("templates/prodi.html");

        // Mengganti penanda DATA_TABEL di HTML dengan rangkaian baris tabel yang sudah dibuat
        $tpl->replace("DATA_TABEL", $barisTabel);

        // Menampilkan halaman HTML final ke browser
        $tpl->write();
    }

    // Method untuk merender form Tambah Prodi (jika $prodi null) atau Ubah Prodi (jika $prodi terisi)
    public function renderForm($prodi = null)
    {
        // Mengecek apakah form sedang dalam mode Ubah (true jika $prodi tidak null)
        $modeUbah = ($prodi !== null);

        // Menentukan judul form berdasarkan mode Tambah atau Ubah
        $judul = $modeUbah ? "Ubah Data Prodi" : "Tambah Data Prodi";

        // Menentukan URL tujuan submit form berdasarkan mode Tambah atau Ubah
        $aksi = $modeUbah ? "prodi.php?aksi=ubah&id=" . $prodi['id'] : "prodi.php?aksi=tambah";

        // Menentukan teks pada tombol submit (Ubah atau Simpan)
        $teksTombol = $modeUbah ? "Ubah" : "Simpan";

        // Mengisi nilai awal input nama_prodi (terisi data lama jika mode Ubah, kosong jika mode Tambah)
        $nama_prodi = $modeUbah ? htmlspecialchars($prodi['nama_prodi']) : "";

        // Mengisi nilai awal input fakultas
        $fakultas = $modeUbah ? htmlspecialchars($prodi['fakultas']) : "";

        // Membuat objek Template dengan memuat kerangka form dari templates/prodi_form.html
        $tpl = new Template("templates/prodi_form.html");

        // Mengganti seluruh placeholder pada template form dengan nilai yang sesuai
        $tpl->replace("DATA_JUDUL", $judul);
        $tpl->replace("DATA_AKSI", $aksi);
        $tpl->replace("DATA_NAMA_PRODI", $nama_prodi);
        $tpl->replace("DATA_FAKULTAS", $fakultas);
        $tpl->replace("DATA_TOMBOL", $teksTombol);

        // Menampilkan halaman form HTML final ke browser
        $tpl->write();
    }
}
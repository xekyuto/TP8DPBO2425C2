<?php

// Mendefinisikan class View MatkulView untuk menyusun tampilan HTML halaman Mata Kuliah
class MatkulView
{
    // Method untuk merender halaman utama tabel daftar mata kuliah
    public function render($daftarMatkul)
    {
        // Menyiapkan variabel string kosong untuk menampung seluruh baris <tr> tabel
        $barisTabel = "";

        // Melakukan perulangan untuk setiap baris data mata kuliah yang diterima dari Controller
        foreach ($daftarMatkul as $row) {
            // Mengamankan nilai id agar aman ditampilkan di HTML
            $id = htmlspecialchars($row['id']);

            // Mengamankan nilai kode mata kuliah dari karakter HTML berbahaya (XSS)
            $kode_matkul = htmlspecialchars($row['kode_matkul']);

            // Mengamankan nilai nama mata kuliah
            $nama_matkul = htmlspecialchars($row['nama_matkul']);

            // Mengamankan nilai jumlah SKS
            $sks = htmlspecialchars($row['sks']);

            // Mengamankan nilai nama dosen pengampu hasil JOIN
            $nama_dosen = htmlspecialchars($row['nama_dosen']);

            // Merangkai satu baris <tr> HTML lengkap dengan kolom data dan tombol tindakan Ubah/Hapus
            $barisTabel .= "<tr>
                <th>{$id}</th>
                <td>{$kode_matkul}</td>
                <td>{$nama_matkul}</td>
                <td>{$sks}</td>
                <td>{$nama_dosen}</td>
                <td>
                    <a class='btn btn-sm btn-warning' href='matkul.php?aksi=ubah&id={$id}'>Ubah</a>
                    <a class='btn btn-sm btn-danger' href='matkul.php?aksi=hapus&id={$id}' onclick=\"return confirm('Yakin ingin menghapus data mata kuliah ini?')\">Hapus</a>
                </td>
            </tr>";
        }

        // Membuat objek Template dengan memuat kerangka HTML dari templates/matkul.html
        $tpl = new Template("templates/matkul.html");

        // Mengganti penanda DATA_TABEL di HTML dengan rangkaian baris tabel yang sudah dibuat
        $tpl->replace("DATA_TABEL", $barisTabel);

        // Menampilkan halaman HTML final ke browser
        $tpl->write();
    }

    // Method untuk merender form Tambah Matkul (jika $matkul null) atau Ubah Matkul (jika $matkul terisi)
    public function renderForm($daftarDosen, $matkul = null)
    {
        // Mengecek apakah form sedang dalam mode Ubah (true jika $matkul tidak null)
        $modeUbah = ($matkul !== null);

        // Menentukan judul form berdasarkan mode Tambah atau Ubah
        $judul = $modeUbah ? "Ubah Data Mata Kuliah" : "Tambah Data Mata Kuliah";

        // Menentukan URL tujuan submit form berdasarkan mode Tambah atau Ubah
        $aksi = $modeUbah ? "matkul.php?aksi=ubah&id=" . $matkul['id'] : "matkul.php?aksi=tambah";

        // Menentukan teks pada tombol submit (Ubah atau Simpan)
        $teksTombol = $modeUbah ? "Ubah" : "Simpan";

        // Mengisi nilai awal input kode_matkul (terisi data lama jika mode Ubah, kosong jika mode Tambah)
        $kode_matkul = $modeUbah ? htmlspecialchars($matkul['kode_matkul']) : "";

        // Mengisi nilai awal input nama_matkul
        $nama_matkul = $modeUbah ? htmlspecialchars($matkul['nama_matkul']) : "";

        // Mengisi nilai awal input sks
        $sks = $modeUbah ? htmlspecialchars($matkul['sks']) : "";

        // Menyimpan dosen_id milik mata kuliah yang sedang diubah (kosong jika mode Tambah)
        $dosenTerpilih = $modeUbah ? $matkul['dosen_id'] : "";

        // Menyiapkan string penampung untuk seluruh tag <option> dropdown dosen pengampu
        $opsiDosen = "";

        // Melakukan perulangan untuk setiap data dosen dari database
        foreach ($daftarDosen as $dosen) {
            // Menambahkan atribut 'selected' jika id dosen sama dengan dosen_id mata kuliah saat ini
            $selected = ($dosen['id'] == $dosenTerpilih) ? "selected" : "";

            // Mengamankan id dosen
            $dosenId = htmlspecialchars($dosen['id']);

            // Menggabungkan nama dosen dan NIDN untuk teks pilihan dropdown
            $labelDosen = htmlspecialchars($dosen['nama'] . " (NIDN: " . $dosen['nidn'] . ")");

            // Merangkai tag <option> HTML untuk satu dosen
            $opsiDosen .= "<option value='{$dosenId}' {$selected}>{$labelDosen}</option>";
        }

        // Membuat objek Template dengan memuat kerangka form dari templates/matkul_form.html
        $tpl = new Template("templates/matkul_form.html");

        // Mengganti seluruh placeholder pada template form dengan nilai yang sesuai
        $tpl->replace("DATA_JUDUL", $judul);
        $tpl->replace("DATA_AKSI", $aksi);
        $tpl->replace("DATA_KODE_MATKUL", $kode_matkul);
        $tpl->replace("DATA_NAMA_MATKUL", $nama_matkul);
        $tpl->replace("DATA_SKS", $sks);
        $tpl->replace("DATA_DOSEN", $opsiDosen);
        $tpl->replace("DATA_TOMBOL", $teksTombol);

        // Menampilkan halaman form HTML final ke browser
        $tpl->write();
    }
}
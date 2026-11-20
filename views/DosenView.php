<?php

// Mendefinisikan class View DosenView untuk menyusun tampilan HTML halaman Dosen
class DosenView
{
    // Method untuk merender halaman utama tabel daftar dosen
    public function render($daftarDosen)
    {
        // Menyiapkan variabel string kosong untuk menampung seluruh baris <tr> tabel
        $barisTabel = "";

        // Melakukan perulangan untuk setiap baris data dosen yang diterima dari Controller
        foreach ($daftarDosen as $row) {
            // Mengamankan nilai id agar aman ditampilkan di HTML
            $id = htmlspecialchars($row['id']);

            // Mengamankan nilai nama dosen dari karakter HTML berbahaya (XSS)
            $nama = htmlspecialchars($row['nama']);

            // Mengamankan nilai NIDN dosen
            $nidn = htmlspecialchars($row['nidn']);

            // Mengamankan nilai nomor HP dosen
            $no_hp = htmlspecialchars($row['no_hp']);

            // Mengamankan nilai tanggal gabung dosen
            $tanggal_gabung = htmlspecialchars($row['tanggal_gabung']);

            // Mengamankan nilai nama prodi hasil JOIN
            $nama_prodi = htmlspecialchars($row['nama_prodi']);

            // Merangkai satu baris <tr> HTML lengkap dengan kolom data dan tombol tindakan Ubah/Hapus
            $barisTabel .= "<tr>
                <th>{$id}</th>
                <td>{$nama}</td>
                <td>{$nidn}</td>
                <td>{$no_hp}</td>
                <td>{$tanggal_gabung}</td>
                <td>{$nama_prodi}</td>
                <td>
                    <a class='btn btn-sm btn-warning' href='index.php?aksi=ubah&id={$id}'>Ubah</a>
                    <a class='btn btn-sm btn-danger' href='index.php?aksi=hapus&id={$id}' onclick=\"return confirm('Yakin ingin menghapus data dosen ini?')\">Hapus</a>
                </td>
            </tr>";
        }

        // Membuat objek Template dengan memuat kerangka HTML dari templates/dosen.html
        $tpl = new Template("templates/dosen.html");

        // Mengganti penanda DATA_TABEL di HTML dengan rangkaian baris tabel yang sudah dibuat
        $tpl->replace("DATA_TABEL", $barisTabel);

        // Menampilkan halaman HTML final ke browser
        $tpl->write();
    }

    // Method untuk merender form Tambah Dosen (jika $dosen null) atau Ubah Dosen (jika $dosen terisi)
    public function renderForm($daftarProdi, $dosen = null)
    {
        // Mengecek apakah form sedang dalam mode Ubah (true jika $dosen tidak null)
        $modeUbah = ($dosen !== null);

        // Menentukan judul form berdasarkan mode Tambah atau Ubah
        $judul = $modeUbah ? "Ubah Data Dosen" : "Tambah Data Dosen";

        // Menentukan URL tujuan submit form berdasarkan mode Tambah atau Ubah
        $aksi = $modeUbah ? "index.php?aksi=ubah&id=" . $dosen['id'] : "index.php?aksi=tambah";

        // Menentukan teks pada tombol submit (Ubah atau Simpan)
        $teksTombol = $modeUbah ? "Ubah" : "Simpan";

        // Mengisi nilai awal input nama (terisi data lama jika mode Ubah, kosong jika mode Tambah)
        $nama = $modeUbah ? htmlspecialchars($dosen['nama']) : "";

        // Mengisi nilai awal input NIDN
        $nidn = $modeUbah ? htmlspecialchars($dosen['nidn']) : "";

        // Mengisi nilai awal input nomor HP
        $no_hp = $modeUbah ? htmlspecialchars($dosen['no_hp']) : "";

        // Mengisi nilai awal input tanggal gabung
        $tanggal_gabung = $modeUbah ? htmlspecialchars($dosen['tanggal_gabung']) : "";

        // Menyimpan prodi_id milik dosen yang sedang diubah (kosong jika mode Tambah)
        $prodiTerpilih = $modeUbah ? $dosen['prodi_id'] : "";

        // Menyiapkan string penampung untuk seluruh tag <option> dropdown prodi
        $opsiProdi = "";

        // Melakukan perulangan untuk setiap data prodi dari database
        foreach ($daftarProdi as $prodi) {
            // Menambahkan atribut 'selected' jika id prodi sama dengan prodi_id dosen saat ini
            $selected = ($prodi['id'] == $prodiTerpilih) ? "selected" : "";

            // Mengamankan id prodi
            $prodiId = htmlspecialchars($prodi['id']);

            // Menggabungkan nama prodi dan fakultas untuk teks pilihan dropdown
            $labelProdi = htmlspecialchars($prodi['nama_prodi'] . " (" . $prodi['fakultas'] . ")");

            // Merangkai tag <option> HTML untuk satu program studi
            $opsiProdi .= "<option value='{$prodiId}' {$selected}>{$labelProdi}</option>";
        }

        // Membuat objek Template dengan memuat kerangka form dari templates/dosen_form.html
        $tpl = new Template("templates/dosen_form.html");

        // Mengganti seluruh placeholder pada template form dengan nilai yang sesuai
        $tpl->replace("DATA_JUDUL", $judul);
        $tpl->replace("DATA_AKSI", $aksi);
        $tpl->replace("DATA_NAMA", $nama);
        $tpl->replace("DATA_NIDN", $nidn);
        $tpl->replace("DATA_NO_HP", $no_hp);
        $tpl->replace("DATA_TANGGAL_GABUNG", $tanggal_gabung);
        $tpl->replace("DATA_PRODI", $opsiProdi);
        $tpl->replace("DATA_TOMBOL", $teksTombol);

        // Menampilkan halaman form HTML final ke browser
        $tpl->write();
    }
}
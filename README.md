# Tugas Praktikum 8 DPBO - Sistem Akademik (PHP MVC)

* **Nama:** Athar Ghaisan Herman
* **NIM:** 2410957
* **Kelas:** C2*

---

## 1. Desain Program & Relasi Database

Aplikasi ini dibangun menggunakan pola arsitektur **Model-View-Controller (MVC)** berbasis PHP OOP dan MySQL (`tp_mvc25`). Database terdiri dari tiga tabel yang saling berelasi:

1. **`prodi`** *(Tabel Induk)*
   * `id` (INT, Primary Key, Auto Increment)
   * `nama_prodi` (VARCHAR)
   * `fakultas` (VARCHAR)
2. **`dosen`** *(Berelasi Many-to-One ke `prodi`)*
   * `id` (INT, Primary Key, Auto Increment)
   * `nama` (VARCHAR)
   * `nidn` (VARCHAR)
   * `no_hp` (VARCHAR)
   * `tanggal_gabung` (DATE)
   * `prodi_id` (INT, Foreign Key $\rightarrow$ `prodi.id`, `ON UPDATE CASCADE`, `ON DELETE RESTRICT`)
3. **`matkul`** *(Berelasi Many-to-One ke `dosen`)*
   * `id` (INT, Primary Key, Auto Increment)
   * `kode_matkul` (VARCHAR)
   * `nama_matkul` (VARCHAR)
   * `sks` (INT)
   * `dosen_id` (INT, Foreign Key $\rightarrow$ `dosen.id`, `ON UPDATE CASCADE`, `ON DELETE CASCADE`)

### Struktur Arsitektur MVC
* **`config.php` & `models/DB.php`:** Mengatur konfigurasi kredensial dan *wrapper* koneksi `mysqli` (open, execute, fetch, escape string, close).
* **`models/` (`Dosen.php`, `Prodi.php`, `Matkul.php`):** Mewarisi `DB` untuk menangani seluruh operasi query SQL (`SELECT JOIN`, `INSERT`, `UPDATE`, `DELETE`) dan validasi relasi.
* **`templates/` (`*.html`):** Berisi antarmuka HTML murni berbasis Bootstrap 5 dengan penanda (`DATA_...`) tanpa logika PHP.
* **`views/` (`Template.php`, `DosenView.php`, `ProdiView.php`, `MatkulView.php`):** Membaca file template HTML, menyuntikkan baris tabel serta opsi *dropdown* dinamis, lalu merendernya ke browser.
* **`controllers/` (`DosenController.php`, `ProdiController.php`, `MatkulController.php`):** Menjadi penghubung antara *request* pengguna, pengolahan data di Model, dan rendering tampilan di View.
* **Entry Points (`index.php`, `prodi.php`, `matkul.php`):** Menangani parameter URL (`?aksi=tambah`, `?aksi=ubah`, `?aksi=hapus`) dan meneruskannya ke Controller terkait.

---

## 2. Penjelasan Alur Aplikasi

1. Pengguna mengakses halaman utama (`index.php` untuk **Dosen**, `prodi.php` untuk **Prodi**, atau `matkul.php` untuk **Mata Kuliah**).
2. *Entry point* membaca parameter `aksi` dari URL dan memanggil method yang sesuai pada **Controller**.
3. **Controller** membuka koneksi melalui **Model**, menjalankan query yang diminta (termasuk `JOIN` antar-tabel untuk menampilkan nama prodi pada daftar dosen dan nama dosen pengampu pada daftar mata kuliah), lalu menutup koneksi.
4. Data hasil query dikirimkan ke **View** untuk disusun ke dalam kerangka **Template** HTML dan ditampilkan ke layar pengguna.
5. Saat pengguna mengirim form **Tambah** atau **Ubah** melalui metode `POST`, Controller meneruskan input ke Model untuk disimpan ke database, lalu melakukan *redirect* kembali ke halaman daftar utama.
6. Saat pengguna menekan tombol **Hapus**, Controller memeriksa batasan relasi (misalnya mencegah penghapusan Prodi jika masih memiliki Dosen terdaftar) sebelum menghapus data.

---

## 3. Dokumentasi Pengujian

Video *screen record* hasil pengujian fitur CRUD pada seluruh tabel tersedia di dalam folder [`Dokumentasi/`](./Dokumentasi/).

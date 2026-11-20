<?php

class Template
{
    // Properti privat untuk menyimpan lokasi/nama file HTML
    private $filename = "";

    // Properti privat untuk menyimpan seluruh isi teks HTML dari file tersebut
    private $content = "";

    // Constructor yang langsung membaca isi file HTML saat objek Template diinstansiasi
    public function __construct($filename = "")
    {
        // Menyimpan nama file ke properti $filename
        $this->filename = $filename;

        // Membaca seluruh baris file menjadi array lalu menggabungkannya menjadi satu string utuh
        $this->content = implode("", @file($filename));
    }

    // Method untuk menghapus sisa placeholder berawalan DATA_ yang tidak terpakai
    public function clear()
    {
        // Mencari pola teks DATA_HURUF_ANGKA dengan regex dan menggantinya menjadi string kosong
        $this->content = preg_replace("/DATA_[A-Z0-9_]+/", "", $this->content);
    }

    // Method untuk mengganti placeholder tertentu (misal DATA_TABEL) dengan nilai baru
    public function replace($old = "", $new = "")
    {
        // Mengecek jika data pengganti bertipe bilangan bulat (integer)
        if (is_int($new)) {
            // Memformat angka integer menjadi string
            $value = sprintf("%d", $new);
        } elseif (is_float($new)) {
            // Memformat angka desimal (float) menjadi string
            $value = sprintf("%f", $new);
        } elseif (is_array($new)) {
            // Menyiapkan string penampung jika data pengganti berupa array
            $value = "";
            // Melakukan perulangan untuk menggabungkan setiap elemen array menjadi satu string
            foreach ($new as $item) {
                $value .= $item . " ";
            }
        } else {
            // Jika sudah bertipe string, langsung gunakan nilainya
            $value = $new;
        }

        // Mengganti seluruh kemunculan teks $old di dalam $this->content dengan $value
        $this->content = str_replace($old, $value, $this->content);
    }

    // Method untuk membersihkan sisa placeholder lalu menampilkan hasil HTML ke browser
    public function write()
    {
        // Menghapus placeholder yang belum terisi agar tidak muncul di layar pengguna
        $this->clear();

        // Mencetak kode HTML final ke browser
        echo $this->content;
    }

    // Method untuk mengembalikan string HTML final tanpa langsung mencetaknya
    public function getContent()
    {
        // Menghapus placeholder yang belum terisi
        $this->clear();

        // Mengembalikan isi HTML dalam bentuk string
        return $this->content;
    }
}
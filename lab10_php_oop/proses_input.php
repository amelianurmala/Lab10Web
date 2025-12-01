<?php
include "database.php";

$db = new Database();

// Ambil input POST
$nim    = isset($_POST['txtnim']) ? $_POST['txtnim'] : '';
$nama   = isset($_POST['txtnama']) ? $_POST['txtnama'] : '';
$alamat = isset($_POST['txtalamat']) ? $_POST['txtalamat'] : '';

if ($nim === '' && $nama === '' && $alamat === '') {
    echo "Tidak ada data yang dikirim.";
    exit;
}

// Simpan ke tabel mahasiswa
$data = [
    "nim"    => $nim,
    "nama"   => $nama,
    "alamat" => $alamat
];

$insert = $db->insert("mahasiswa", $data);

if ($insert) {
    echo "Data berhasil disimpan.<br>";
    echo "<a href='form_input.php'>Kembali</a>";
} else {
    echo "Gagal menyimpan data.<br>";
}
?>
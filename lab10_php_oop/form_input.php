<?php
include "form.php";
include "database.php";

$db = new Database();
$message = "";

$nim = "";
$nama = "";
$alamat = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nim = $_POST['txtnim'];
    $nama = $_POST['txtnama'];
    $alamat = $_POST['txtalamat'];

    if ($db->insert("mahasiswa", [
        "nim" => $nim,
        "nama" => $nama,
        "alamat" => $alamat
    ])) {
        $message = "<div class='alert-success'>Data berhasil disimpan!</div>";
    }
}

echo "<html><head>
<title>Mahasiswa</title>
<link rel='stylesheet' href='style.css'>
</head><body>";

echo "<div class='form-wrapper'>";

echo "<h2>Form Input Mahasiswa</h2>";
echo "<p class='lead'>Silahkan isi data mahasiswa di bawah ini.</p>";

echo $message;

$form = new Form("form_input.php", "Simpan");
$form->addField("txtnim", "NIM");
$form->addField("txtnama", "Nama");
$form->addField("txtalamat", "Alamat");
$form->displayForm();

echo "
<script>
document.getElementsByName('txtnim')[0].value = '".htmlspecialchars($nim, ENT_QUOTES)."';
document.getElementsByName('txtnama')[0].value = '".htmlspecialchars($nama, ENT_QUOTES)."';
document.getElementsByName('txtalamat')[0].value = '".htmlspecialchars($alamat, ENT_QUOTES)."';
</script>
";

echo "</div></body></html>";
?>

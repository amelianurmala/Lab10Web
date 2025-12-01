# Lab10Web

*Nama    : Amelia Nurmala Dewi*

*Kelas  : TI.24.A2*

*Nim   : 312410199*




# Praktikum 10: PHP OOP

## 1. Membuat Struktur Folder Project
Pertama saya membuat struktur folder seperti berikut:

```
project/
│── form.php          # Class untuk membuat form input dinamis
│── database.php      # Class untuk koneksi database dan insert/update/delete
│── form_input.php    # Halaman utama menampilkan form dan tabel mahasiswa
│── style.css         # Styling tampilan form dan tabel
│── index.php         # Dashboard / halaman utama (opsional)
│── demo_mobil.php    # Demo OOP mobil (contoh lain)
```

---

## 2. Membuat Class Form (form.php)
Pada langkah ini saya membuat file `form.php` yang berisi class Form untuk membuat input dinamis.  
Class ini digunakan agar form bisa dipanggil berulang kali tanpa menulis HTML berulang.

Berikut isi file `form.php`:

```php
<?php
class Form
{
    private $fields = array();
    private $action;
    private $submit = "Submit Form";
    private $jumField = 0;
    public function __construct($action, $submit)
    {
        $this->action = $action;
        $this->submit = $submit;
    }
    public function displayForm()
    {
        echo "<form action='".$this->action."' method='POST'>";
        echo '<table width="100%" border="0">';
        for ($j=0; $j<count($this->fields); $j++) {
            echo "<tr><td align='right'>".$this->fields[$j]['label']."</td>";
            echo "<td><input type='text' name='".$this->fields[$j]['name']."'></td></tr>";
        }
        echo "<tr><td colspan='2'>";
        echo "<input type='submit' value='".$this->submit."'></td></tr>";
        echo "</table>";
    }
    public function addField($name, $label)
    {
        $this->fields[$this->jumField]['name'] = $name;
        $this->fields[$this->jumField]['label'] = $label;
        $this->jumField++;
    }
}
?>
````

---

## 3. Membuat Koneksi Database Menggunakan Class (database.php)

Setelah form selesai, saya membuat class `Database` untuk koneksi dan insert data.

```php
<?php
class Database {
    private $host = "localhost";
    private $user = "root";
    private $pass = "";
    private $dbname = "latihan_oop";

    public $conn;

    public function __construct() {
        $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->dbname);
    }

    public function insert($table, $data) {
        $columns = implode(",", array_keys($data));
        $values = "'" . implode("','", $data) . "'";

        $sql = "INSERT INTO $table ($columns) VALUES ($values)";
        return $this->conn->query($sql);
    }
}
?>
```

---

## 4. Membuat Database di phpMyAdmin

Saya membuat database sesuai modul:

```
Nama database : latihan_oop
Nama tabel    : mahasiswa

Field:
- nim (varchar)
- nama (varchar)
- alamat (varchar)
```

SQL untuk membuat tabel:

```sql
CREATE TABLE mahasiswa (
  nim VARCHAR(20),
  nama VARCHAR(50),
  alamat VARCHAR(100)
);
```
Database dan tabel dibuat untuk menyimpan data mahasiswa

---

## 5. Menggabungkan Form + Database di form_input.php

Pada bagian ini saya menghubungkan form dengan database.
Setiap data yang diinput akan disimpan ke tabel *mahasiswa*.

Saya juga menambahkan pesan **"Data berhasil disimpan"** yang tetap muncul tanpa menghapus data yang sudah diinput.

```php
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
```

---

## 6. Dashboard

File `index.php` atau halaman utama berisi daftar mahasiswa dan tombol navigasi. Contoh kode sederhana dashboard:

```php
<?php
include "database.php";

$db = new Database();

$result = $db->conn->query("SELECT * FROM mahasiswa");
?>

<html>
<head>
<title>Dashboard Mahasiswa</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<h2>Dashboard Mahasiswa</h2>
<a href="form_input.php">Tambah Mahasiswa</a>
<table border="1" cellpadding="10">
<tr>
<th>NIM</th>
<th>Nama</th>
<th>Alamat</th>
<th>Aksi</th>
</tr>

<?php while($row = $result->fetch_assoc()): ?>
<tr>
<td><?= $row['nim'] ?></td>
<td><?= $row['nama'] ?></td>
<td><?= $row['alamat'] ?></td>
<td>
<a href="detail.php?nim=<?= $row['nim'] ?>">Detail</a> |
<a href="edit.php?nim=<?= $row['nim'] ?>">Edit</a> |
<a href="hapus.php?nim=<?= $row['nim'] ?>" onclick="return confirm('Yakin?')">Hapus</a>
</td>
</tr>
<?php endwhile; ?>

</table>
</body>
</html>
```

---

## 7. Menambah Data Mahasiswa

File `form_input.php` untuk menambah mahasiswa menggunakan class `Form` dan `Database`:

```php
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

$form = new Form("form_input.php", "Simpan");
$form->addField("txtnim", "NIM");
$form->addField("txtnama", "Nama");
$form->addField("txtalamat", "Alamat");

echo "<html><head><link rel='stylesheet' href='style.css'></head><body>";
echo "<h2>Tambah Mahasiswa</h2>";
echo $message;
$form->displayForm();
echo "</body></html>";
?>
```

## 8. Tampilan Detail Mahasiswa

Halaman `detail.php` menampilkan data lengkap mahasiswa:

```php
<?php
include "database.php";
$db = new Database();

$nim = $_GET['nim'];
$result = $db->conn->query("SELECT * FROM mahasiswa WHERE nim='$nim'");
$row = $result->fetch_assoc();
?>

<html>
<head><title>Detail Mahasiswa</title></head>
<body>
<h2>Detail Mahasiswa</h2>
<p>NIM: <?= $row['nim'] ?></p>
<p>Nama: <?= $row['nama'] ?></p>
<p>Alamat: <?= $row['alamat'] ?></p>
<a href="index.php">Kembali</a>
</body>
</html>
```

---

## 9. Edit Data Mahasiswa

File `edit.php` memungkinkan update data mahasiswa:

```php
<?php
include "database.php";
include "form.php";

$db = new Database();
$nim = $_GET['nim'];
$result = $db->conn->query("SELECT * FROM mahasiswa WHERE nim='$nim'");
$row = $result->fetch_assoc();

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['txtnama'];
    $alamat = $_POST['txtalamat'];

    $db->conn->query("UPDATE mahasiswa SET nama='$nama', alamat='$alamat' WHERE nim='$nim'");
    $message = "Data berhasil diupdate!";
}

$form = new Form("edit.php?nim=$nim", "Update");
$form->addField("txtnim", "NIM"); // read-only
$form->addField("txtnama", "Nama");
$form->addField("txtalamat", "Alamat");

echo "<html><head><link rel='stylesheet' href='style.css'></head><body>";
echo "<h2>Edit Mahasiswa</h2>";
echo $message;
$form->displayForm();
echo "<script>
document.getElementsByName('txtnim')[0].value='".htmlspecialchars($row['nim'], ENT_QUOTES)."';
document.getElementsByName('txtnim')[0].readOnly = true;
document.getElementsByName('txtnama')[0].value='".htmlspecialchars($row['nama'], ENT_QUOTES)."';
document.getElementsByName('txtalamat')[0].value='".htmlspecialchars($row['alamat'], ENT_QUOTES)."';
</script>";
echo "</body></html>";
?>
```

---

## 10. Menghapus Data

File `hapus.php` untuk menghapus mahasiswa:

```php
<?php
include "database.php";
$db = new Database();

$nim = $_GET['nim'];
$db->conn->query("DELETE FROM mahasiswa WHERE nim='$nim'");
header("Location: index.php");
?>
```

---

## 11. Demo OOP Mobil

File `demo_mobil.php` sebagai contoh penggunaan OOP di PHP:

```php
<?php
class Mobil {
    public $merk;
    public $warna;

    public function __construct($merk, $warna) {
        $this->merk = $merk;
        $this->warna = $warna;
    }

    public function info() {
        return "Mobil $this->merk berwarna $this->warna";
    }
}

$mobil1 = new Mobil("Toyota", "Merah");
$mobil2 = new Mobil("Honda", "Hitam");

echo $mobil1->info() . "<br>";
echo $mobil2->info();
?>
```


# Dokumentasi Langkah-Langkah

## 1. Membuat Struktur Folder Project
<img width="213" height="286" alt="image" src="https://github.com/user-attachments/assets/c7ce96e7-a50a-4722-bbed-1cc269979283" />

## 2. Membuat database dan tabel di phpMyAdmin
<img width="1366" height="722" alt="Screenshot (1285)" src="https://github.com/user-attachments/assets/d4d53ad7-d0de-4454-9f1d-aaf36259c2bc" />
<img width="1366" height="722" alt="Screenshot (1286)" src="https://github.com/user-attachments/assets/3677a810-bf8f-4dff-9e56-59e5ae38f87c" />
<img width="1366" height="718" alt="Screenshot (1287)" src="https://github.com/user-attachments/assets/3542256b-5f6b-4ce6-991e-d871a6e5a694" />

## 3. Dashboard
<img width="1366" height="677" alt="Screenshot (1307)" src="https://github.com/user-attachments/assets/d3999514-3685-4e72-a395-8f87a289f3fb" />

## 4. Menambah data
<img width="1366" height="679" alt="Screenshot (1312)" src="https://github.com/user-attachments/assets/25018d2f-4dac-4eb3-b2d0-11410fed39a3" />

## 5. Pesan “Data Berhasil Disimpan”
<img width="1366" height="683" alt="Screenshot (1313)" src="https://github.com/user-attachments/assets/dba4a4d3-fc14-41c7-86dd-100c90d5ad0f" />

## 6. Tampilan Sesudah menambah data
<img width="1366" height="768" alt="Screenshot (1327)" src="https://github.com/user-attachments/assets/15772a22-c324-4789-acac-08fcfcc73826" />

## 7. Tampilan detail mahasiswa
<img width="1366" height="677" alt="Screenshot (1324)" src="https://github.com/user-attachments/assets/d49b46ad-ed3a-49f7-b347-3657abc63321" />

## 8. Edit data
<img width="1366" height="626" alt="Screenshot (1316)" src="https://github.com/user-attachments/assets/79098ccf-7b37-4c76-ace9-acecfce6d56c" />

## 9. Menghapus Data
<img width="1366" height="768" alt="Screenshot (1329)" src="https://github.com/user-attachments/assets/e77f51c5-f6af-459f-88d2-99e50cc24764" />

## 10. Demo oop mobil
<img width="1366" height="679" alt="Screenshot (1325)" src="https://github.com/user-attachments/assets/13d889d5-9c1f-46c6-9fe0-972f26bde892" />










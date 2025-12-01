# Lab10Web

*Nama    : Amelia Nurmala Dewi*

*Kelas  : TI.24.A2*

*Nim   : 312410199*




# Praktikum 10: PHP OOP

## 1. Membuat Struktur Folder Project
Pertama saya membuat struktur folder seperti berikut:

```

project/
│── form.php
│── database.php
│── form_input.php
│── style.css

````

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

## 6. Mendesain Form dengan CSS Modern (style.css)

Agar form tidak terlihat seperti form biasa, saya membuat desain modern dengan efek:

* glassmorphism
* warna gradasi hijau → biru → ungu
* input glowing
* layout modern (bukan tabel jadul)

Contoh isi style.css (ringkas):

```css
body {
  background: linear-gradient(135deg, #2ecc71, #3498db, #6a5acd);
  font-family: Poppins;
}

.form-wrapper {
  width: 520px;
  padding: 32px;
  background: rgba(255,255,255,0.12);
  border-radius: 24px;
  backdrop-filter: blur(14px);
  box-shadow: 0 14px 40px rgba(0,0,0,0.25);
}

input[type="text"] {
  width: 100%;
  padding: 14px 16px;
  border-radius: 14px;
  background: rgba(255,255,255,0.18);
  border: 1px solid rgba(255,255,255,0.28);
  color: white;
  transition: .22s;
}

input[type="text"]:focus {
  background: rgba(255,255,255,0.28);
  border-color: #fff;
}

input[type="submit"] {
  margin-top: 16px;
  padding: 14px;
  border-radius: 14px;
  background: linear-gradient(90deg, #3498db, #6a5acd);
  color: white;
  font-weight: 700;
}
```
---

# Dokumentasi Langkah-Langkah

## 1. Membuat Struktur Folder Project
<img width="289" height="183" alt="image" src="https://github.com/user-attachments/assets/1eadc25a-1073-4414-ba53-399b7fd8e718" />

## 2. Membuat database dan tabel di phpMyAdmin
<img width="1366" height="722" alt="Screenshot (1285)" src="https://github.com/user-attachments/assets/d4d53ad7-d0de-4454-9f1d-aaf36259c2bc" />
<img width="1366" height="722" alt="Screenshot (1286)" src="https://github.com/user-attachments/assets/3677a810-bf8f-4dff-9e56-59e5ae38f87c" />
<img width="1366" height="718" alt="Screenshot (1287)" src="https://github.com/user-attachments/assets/3542256b-5f6b-4ce6-991e-d871a6e5a694" />

## 3. Tampilan form sebelum styling
<img width="1366" height="673" alt="Screenshot (1291)" src="https://github.com/user-attachments/assets/1f3848a7-838d-4338-b5fa-4d265a10fb72" />

## 4. Menambahkan style.css 
<img width="1366" height="679" alt="Screenshot (1297)" src="https://github.com/user-attachments/assets/9cfce43e-037f-4fac-ab9c-c16cc79df2ff" />

## 5. Pesan “Data Berhasil Disimpan”
<img width="1366" height="683" alt="Screenshot (1298)" src="https://github.com/user-attachments/assets/21eef5b6-e82d-4446-8c35-f7a17650bfad" />




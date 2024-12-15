<?php
session_start();
// Koneksi ke database
$host = "localhost";
$username = "root";
$password = "";
$dbname = "db_desawisata";
$conn = new mysqli($host, $username, $password, $dbname);
$id_mitra = $_GET['id'];

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Mengaktifkan error reporting
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // Mendapatkan data dari form
    $nama = $conn->real_escape_string($_POST['nama']);
    $slogan = $conn->real_escape_string($_POST['slogan']);
    $deskripsi = $conn->real_escape_string($_POST['deskripsi']);
    $alamat = $conn->real_escape_string($_POST['alamat']);
    $harga = $conn->real_escape_string($_POST['harga']);

    // Mengelola file foto
    $foto = $_FILES['foto'];
    $fotoName = basename($foto['name']);
    $fotoTmpName = $foto['tmp_name'];
    $fotoError = $foto['error'];
    $fotoSize = $foto['size'];

    // Validasi file
    $fotoExt = strtolower(pathinfo($fotoName, PATHINFO_EXTENSION));
    $allowedExt = ['jpg', 'jpeg', 'png'];

    //Generate ID WISATA
    $query_last_wisata = "SELECT MAX(id_wisata) AS last_id FROM tb_wisata WHERE id_mitra=$id_mitra";
    $result = mysqli_query($conn, $query_last_wisata);
    $row = mysqli_fetch_assoc($result);

    if ($row['last_id']) {
        // Ekstrak nomor urut terakhir
        $last_id = substr($row['last_id'], -3); // Ambil 3 digit terakhir
        $new_number = str_pad((int)$last_id + 1, 2, '0', STR_PAD_LEFT); // Tambahkan 1
    } else {
        $new_number = '01'; // Jika tidak ada data, mulai dari 01
    }

    // Buat ID transaksi
    $kode_wisata = '3'; // Kode ID transaksi
    $nomor_mitra = str_pad(substr($id_mitra, -2), 2, '0', STR_PAD_LEFT); // Dua digit terakhir ID mitra
    $id_wisata = $kode_wisata . $nomor_mitra . $new_number;

    if (in_array($fotoExt, $allowedExt) && $fotoError === 0 && $fotoSize <= 2000000) { // 2MB max
        $uploadsDir = 'uploads/';
        
        // Membuat folder jika belum ada
        if (!is_dir($uploadsDir)) {
            mkdir($uploadsDir, 0777, true);
        }

        $fotoPath = $uploadsDir . uniqid('foto_', true) . '.' . $fotoExt;

        // Memindahkan file ke folder uploads
        if (move_uploaded_file($fotoTmpName, $fotoPath)) {
            // Query untuk menyimpan data ke database
            $sql = "INSERT INTO tb_wisata (id_wisata, nama, id_mitra, slogan, deskripsi, alamat, foto, harga_tiket) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);

            if ($stmt === false) {
                echo "<script>alert('Error preparing statement: " . $conn->error . "'); window.history.back();</script>";
                exit;
            }

            // Bind parameter
            $stmt->bind_param("ssisssss",$id_wisata ,$nama, $id_mitra, $slogan, $deskripsi, $alamat, $fotoPath, $harga);

            // Cek apakah query berhasil dijalankan
            if ($stmt->execute()) {
                echo "<script>alert('Data berhasil ditambahkan!'); window.location.href='index.php?id=$id_mitra';</script>";
            } else {
                echo "<script>alert('Terjadi kesalahan saat menambahkan data: " . $stmt->error . "'); window.history.back();</script>";
            }

            $stmt->close();
        } else {
            echo "<script>alert('Gagal mengunggah file foto.'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('File foto tidak valid atau terlalu besar (maks 2MB).'); window.history.back();</script>";
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Wisata Baru</title>
    <link rel="stylesheet" href="css/pages/Upload/style.css?v=<?= time(); ?>">
</head>
<body>
    <div class="background">
        <div class="form-container">
            <h2>Tambah Wisata Baru</h2>
            <form action="" method="POST" enctype="multipart/form-data">
                <label for="foto">Upload Foto Wisata :</label>
                <input type="file" id="foto" name="foto" required>

                <label for="nama">Nama Wisata :</label>
                <input type="text" id="nama" name="nama" placeholder="Masukkan nama wisata" required>

                <label for="slogan">Slogan :</label>
                <input type="text" id="slogan" name="slogan" placeholder="Masukkan nama slogan" required>

                <label for="deskripsi">Deskripsi Wisata :</label>
                <textarea id="deskripsi" name="deskripsi" rows="4" placeholder="Deskripsi wisata....." required></textarea>

                <label for="alamat">Alamat Wisata :</label>
                <input type="text" id="alamat" name="alamat" placeholder="Masukkan alamat wisata" required>

                <label for="harga">Harga Tiket :</label>
                <input type="number" id="harga" name="harga" placeholder="Masukkan harga tiket" required>

                <button type="submit" class="submit-button">Tambah Wisata</button>
            </form>
        </div>
    </div>
</body>
</html>

<?php
include("connection.php");

$id = $_GET['id'];
$id_wisata = $_GET['dest'];

$wisata_query = mysqli_query($connect, "SELECT * from tb_wisata WHERE id_wisata = $id_wisata");
$wisata = mysqli_fetch_all($wisata_query, MYSQLI_ASSOC);

if (isset($_POST['update'])) {
    $nama = $_POST["nama"];
    $slogan = $_POST["slogan"];
    $deskripsi = $_POST["deskripsi"];
    $alamat = $_POST["alamat"];
    $harga = $_POST["harga"];

    // Tangani file foto
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['foto']['tmp_name'];
        $file_name = $_FILES['foto']['name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($file_ext, $allowed_ext)) {
            // Nama file unik
            $new_file_name = uniqid('wisata_', true) . '.' . $file_ext;
            $upload_dir = 'uploads/'; // Pastikan folder ini ada dan memiliki izin tulis
            $upload_path = $upload_dir . $new_file_name;

            // Pindahkan file ke folder upload
            if (move_uploaded_file($file_tmp, $upload_path)) {
                $foto_path = $upload_path;
            } else {
                echo "<script>alert('Gagal mengupload foto.');</script>";
                exit;
            }
        } else {
            echo "<script>alert('Format file tidak valid. Hanya JPG, JPEG, PNG, dan GIF yang diperbolehkan.');</script>";
            exit;
        }
    } else {
        // Jika tidak ada file baru, gunakan foto lama
        $foto_path = $wisata[0]['foto'];
    }

    // Query update dengan jalur foto baru
    $query = "UPDATE tb_wisata SET 
              foto='$foto_path',
              nama='$nama',
              slogan='$slogan',
              deskripsi='$deskripsi',
              alamat='$alamat',
              harga_tiket='$harga'
              WHERE id_wisata='$id_wisata'";
    $hasil = mysqli_query($connect, $query);

    if ($hasil) {
        echo "<script>alert('Data berhasil diupdate!');</script>";
        header("Location: wisata.php?id=$id&dest=$id_wisata");
    } else {
        echo "<script>alert('Data gagal diupdate!');</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Wisata</title>
    <link rel="stylesheet" href="css/pages/Upload/style.css?v=<?= time(); ?>">
</head>
<body>
    <div class="background">
        <div class="form-container">
            <h2>Edit Wisata</h2>
            <form action="" method="POST" enctype="multipart/form-data">
                <label for="foto">Upload Foto Wisata :</label>
                <?php if (!empty($wisata[0]['foto'])): ?>
                    <img src="<?= htmlspecialchars($wisata[0]['foto']) ?>" alt="<?= htmlspecialchars($wisata[0]['nama']) ?>" alt="Foto Wisata" style="max-width: 200px; display: block;">
                <?php endif; ?>
                <input type="file" id="foto" name="foto" value="<?php echo $wisata[0]['foto'];?>" required>

                <label for="nama">Nama Wisata :</label>
                <input type="text" id="nama" name="nama" placeholder="Masukkan nama wisata" value="<?php echo $wisata[0]['nama'];?>" required>

                <label for="slogan">Slogan :</label>
                <input type="text" id="slogan" name="slogan" placeholder="Masukkan nama slogan" value="<?php echo $wisata[0]['slogan'];?>" required>

                <label for="deskripsi">Deskripsi Wisata :</label>
                <textarea id="deskripsi" name="deskripsi" rows="4" placeholder="Deskripsi wisata....." required>
                <?= htmlspecialchars($wisata[0]['deskripsi']) ?>
                </textarea>

                <label for="alamat">Alamat Wisata :</label>
                <input type="text" id="alamat" name="alamat" placeholder="Masukkan alamat wisata" value="<?php echo $wisata[0]['alamat'];?>" required>

                <label for="harga">Harga Tiket :</label>
                <input type="number" id="harga" name="harga" placeholder="Masukkan harga tiket" value="<?php echo $wisata[0]['harga_tiket'];?>" required>

                <button type="submit" class="submit-button" name="update">Edit Wisata</button>
            </form>
        </div>
    </div>
</body>
</html>
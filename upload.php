<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Wisata Baru</title>
    <link rel="stylesheet" href="css\pages\Upload\style.css">
</head>
<body>
    <div class="background">
        <div class="form-container">
            <h2>Tambah Wisata Baru</h2>
            <form action="/submit" method="POST" enctype="multipart/form-data">
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

                <button type="submit" class="submit-button">Tambah Wisata</button>
            </form>
        </div>
    </div>
</body>
</html>

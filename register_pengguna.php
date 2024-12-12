<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Pengguna</title>
    <link rel="stylesheet" href="css\pages\RegisterPengguna\style.css">
</head>
<body>
    <div class="container">
        <div class="card">
            <img src="images\saly.png" alt="Icon" class="icon">
            <h1>Register Pengguna</h1>
            <form action="#" method="post" class="form">
                <label for="nama">Nama</label>
                <input type="text" id="nama" name="nama" required>

                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>

                <label for="gender">Jenis Kelamin</label>
                <select id="gender" name="gender" required>
                    <option value="laki-laki">Laki-laki</option>
                    <option value="perempuan">Perempuan</option>
                </select>

                <label for="tanggal-lahir">Tanggal Lahir</label>
                <input type="date" id="tanggal-lahir" name="tanggal-lahir" required>

                <label for="nomor-hp">Nomor HP</label>
                <input type="tel" id="nomor-hp" name="nomor-hp" required>

                <label for="alamat">Alamat</label>
                <input type="text" id="alamat" name="alamat" required>

                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>

                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>

                <label for="confirm-password">Confirm Password</label>
                <input type="password" id="confirm-password" name="confirm-password" required>

                <button type="submit" class="btn">Register</button>
            </form>
        </div>
    </div>
</body>
</html>

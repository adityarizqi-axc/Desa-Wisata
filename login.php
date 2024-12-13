<?php
include('connection.php'); // Koneksi database

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $isFound = false;
    // Query untuk mendapatkan data pengguna
    $users_query = mysqli_query($connect, "SELECT id_pengguna as id, username, password from tb_pengguna UNION SELECT id_mitra as id, username, password from tb_mitra");
    $users = mysqli_fetch_all($users_query, MYSQLI_ASSOC);

    foreach ($users as $user){
        if($user["username"] == $username && $user["password"] ==$password){
            $isFound = true;
            $id = $user['id'];

            header("Location: index.php?id=$id");
            exit;
        }
    }

    if (!$isFound) {
        $error = 'Username atau password salah.';

        header("Refresh: 5; url=login.php");
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css\pages\HalamanMasuk\style.css?v=1.0">
</head>
<body>
    <div class="container">
        <div class="card">
            <img src="images\saly.png" alt="Icon" class="icon">
            <h1>Masuk</h1>
            <form action="#" method="post" class="form">
                <input type="username" placeholder="Username" name="username" required>
                <input type="password" placeholder="Kata sandi" name="password" required>
                <button type="submit" class="btn">MASUK</button>
                <p class="register-link">Belum punya akun? <a href="#" onclick="window.location.href='role.php'">Register</a></p>
            </form>
            <?php if ($error): ?>
                <?php echo"<div class='error'>".$error."</div>" ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

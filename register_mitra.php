<?php
    
    require 'connection.php';

    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
        $nama = $_POST["nama"];
        $username = $_POST["username"];
        $no_hp = $_POST["nomor-hp"];
        $email = $_POST["email"];
        $password = $_POST["password"];

        
        $password_hashed = password_hash($password, PASSWORD_BCRYPT);

        
        $query_sql = "INSERT INTO tb_mitra (nama, username, no_hp, email, password)
                      VALUES ('$nama', '$username', '$no_hp', '$email', '$password')";

        if (mysqli_query($connect, $query_sql)) {
            header("Location: login.php");
            exit; 
        } else {
            echo "Pendaftaran gagal: " . mysqli_error($conn);
        }
    }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Mitra</title>
    <link rel="stylesheet" href="css/pages/RegisterMitra/style.css?v=1.0">
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="header">
                <img src="images/saly.png" alt="Icon" class="icon">
                <h1>Register Mitra</h1>
            </div>
            <form action="register_mitra.php" method="post" class="form">
                <input type="text" id="nama" name="nama" placeholder="Nama" required>
                <input type="text" id="username" name="username" placeholder="Username" required>
                <input type="tel" id="nomor-hp" name="nomor-hp" placeholder="Nomor HP" required>
                <input type="email" id="email" name="email" placeholder="Email" required>
                <input type="password" id="password" name="password" placeholder="Password" required>
                <button type="submit" class="btn">Register</button>
            </form>
        </div>
    </div>
</body>
</html>

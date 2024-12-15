<?php
include('connection.php'); // Koneksi database

$error = '';
$id = $_GET['id'];

$role = substr($id, 0, 1);

if ($role == '1') {
  $user_query = mysqli_query($connect, "SELECT * from tb_pengguna WHERE id_pengguna = $id");
  $user = mysqli_fetch_all($user_query, MYSQLI_ASSOC);
} elseif ($role == '2') {
  $user_query = mysqli_query($connect, "SELECT * from tb_mitra WHERE id_mitra = $id");
  $user = mysqli_fetch_all($user_query, MYSQLI_ASSOC);
} else {
    echo "<h1>Akses tidak dikenal</h1>";
    echo "<p>ID Anda: $id</p>";
}

//Fetch Tiket
$riwayat_query = mysqli_query($connect,"SELECT
    p.id_transaksi,
    r.nama AS nama,
    q.tanggal_pemesanan,
    p.tanggal_datang,
    COUNT(p.id_wisata) AS jumlah_tiket,
    SUM(p.harga) AS harga_tiket
FROM id_tiket p
INNER JOIN tb_transaksi q ON p.id_transaksi = q.id_transaksi
INNER JOIN tb_wisata r ON p.id_wisata = r.id_wisata
WHERE q.id_pengguna = '$id' AND q.hidden = 0
GROUP BY p.id_transaksi, r.nama, q.tanggal_pemesanan, p.tanggal_datang;");

$riwayat = mysqli_fetch_all($riwayat_query, MYSQLI_ASSOC);
//User Profile Update
if(isset($_POST['update'])) {
  $nama = $_POST["nama"];
  $username = $_POST["username"];
  $gender = $_POST["gender"];
  $tanggal_lahir = $_POST["tanggal-lahir"];
  $alamat = $_POST["alamat"];
  $email = $_POST["email"];
  $no_hp = $_POST["nomor-hp"];

  $query = "UPDATE tb_pengguna SET 
            nama='$nama' ,
            username='$username' ,
            jenis_kelamin ='$gender',
            tanggal_lahir ='$tanggal_lahir',
            alamat ='$alamat',
            email ='$email',
            no_hp ='$no_hp'
            WHERE id_pengguna='$id'";
  $hasil = mysqli_query($connect, $query);

  if($hasil){
    echo "<script>alert('Data berhasil diupdate!');</script>";
    header("Location: profil.php?id=$id");
  } else {
    echo "<script>alert('Data gagal diupdate!');</script>";
  }
}

if(isset($_POST['update-pass'])) {
  $old_pass = $_POST["Password-Lama"];
  $new_pass = $_POST["Password-Baru"];
  $test_pass = $_POST["Konfirmasi-Password"];
  
  if($test_pass==$new_pass && $old_pass==$user[0]["password"]){
    $query = "UPDATE tb_pengguna SET 
            password ='$new_pass' 
            WHERE id_pengguna='$id'";
    $hasil = mysqli_query($connect, $query);

    if($hasil){
      echo "<script>alert('Data berhasil diupdate!');</script>";
    } else {
      echo "<script>alert('Data gagal diupdate!');</script>";
    }

  }else{
    echo "<script>alert('Konfirmasi password atau password lama salah');</script>";
  }
}

//Mitra Profile Update
if(isset($_POST['update-m'])) {
  $nama = $_POST["nama"];
  $username = $_POST["username"];
  $email = $_POST["email"];
  $no_hp = $_POST["nomor-hp"];

  $query = "UPDATE tb_mitra SET 
            nama='$nama' ,
            username='$username' ,
            email ='$email',
            no_hp ='$no_hp'
            WHERE id_mitra='$id'";
  $hasil = mysqli_query($connect, $query);

  if($hasil){
    echo "<script>alert('Data berhasil diupdate!');</script>";
    header("Location: profil.php?id=$id");
  } else {
    echo "<script>alert('Data gagal diupdate!');</script>";
  }

  if(isset($_POST['update-pass-m'])) {
    $old_pass = $_POST["Password-Lama"];
    $new_pass = $_POST["Password-Baru"];
    $test_pass = $_POST["Konfirmasi-Password"];
    
    if($test_pass==$new_pass && $old_pass==$user[0]["password"]){
      $query = "UPDATE tb_mitra SET 
              password ='$new_pass'
              WHERE id_mitra='$id'";
      $hasil = mysqli_query($connect, $query);
  
      if($hasil){
        echo "<script>alert('Data berhasil diupdate!');</script>";
      } else {
        echo "<script>alert('Data gagal diupdate!');</script>";
      }
  
    }else{
      echo "<script>alert('Konfirmasi password atau password lama salah');</script>";
    }
  }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profil</title>
  <link rel="stylesheet" href="css/pages/Profil/style.css?v=<?= time(); ?>">
</head>
<body>
  <header>
    <div class="nav-container">
      <div class="logo">Wisata Suka Lestari</div>
      <nav>
        <ul>
          <li><a href="index.php?id=<?=$id?>">Beranda</a></li>
          <li><a href="profil.php?id=<?=$id?>" >Profil</a></li>
        </ul>

      </nav>
    </div>
  </header>

  <main>
    <section class="hero-section">
      <h1>Halo, <?= htmlspecialchars($user[0]['nama'])?></h1>
    </section>
    
    <?php 
    if ($role == '1'){
    ?>
    <section class="user-profile">
    <div class="container">
        <div class="card">
            <form action="#" method="post" class="form">
                <label for="nama">Nama</label>
                <input type="text" id="nama" name="nama" value="<?php echo $user[0]["nama"]; ?>" required>

                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="<?php echo $user[0]["username"]; ?>" required>

                <label for="gender">Jenis Kelamin</label>
                <select id="gender" name="gender" required>
                    <option value="laki-laki" <?= $user[0]['jenis_kelamin'] == 'Laki-Laki' ? 'selected' : '' ?>>Laki-laki</option>
                    <option value="perempuan" <?= $user[0]['jenis_kelamin'] == 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
                </select>

                <label for="tanggal-lahir">Tanggal Lahir</label>
                <input type="date" id="tanggal-lahir" name="tanggal-lahir" value="<?php echo $user[0]["tanggal_lahir"]; ?>" required>

                <label for="nomor-hp">Nomor HP</label>
                <input type="tel" id="nomor-hp" name="nomor-hp" value="<?php echo $user[0]["no_hp"]; ?>" required>

                <label for="alamat">Alamat</label>
                <input type="text" id="alamat" name="alamat" value="<?php echo $user[0]["alamat"]; ?>" required>

                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo $user[0]["email"]; ?>" required>
            
                <button type="submit" class="btn" name="update">Ubah Profil</button>
            </form>

            <form action="#" method="post" class="form">
                <label for="password-lama">Password Lama</label>
                <input type="password" id="password-lama" name="Password-Lama" required>

                <label for="password">Password Baru</label>
                <input type="password" id="password" name="Password-Baru" required>

                <label for="confirm-password">Konfirmasi Password</label>
                <input type="password" id="confirm-password" name="Konfirmasi-Password" required>

                <button type="submit" class="btn" name="update-pass">Ubah Password</button>
            </form>
        </div>
    </div>
    </section>
  <section class="riwayat-section">
  <h2>Riwayat Pembelian</h2>
  <button class="delete" onclick="window.location.href='hapus_riwayat.php?id=<?= $id ?>'">Hapus Riwayat</button>
  <table class="riwayat-table">
    <!-- Header Tabel -->
    <thead class="riwayat-header">
      <tr>
        <th>ID Transaksi</th>
        <th>Tempat Wisata</th>
        <th>Tanggal Transaksi</th>
        <th>Jumlah Tiket</th>
        <th>Total Harga</th>
      </tr>
    </thead>
    <!-- Isi Tabel -->
    <tbody>
      <?php for ($i = 0; $i < count($riwayat); $i++): ?>
        <tr class="riwayat-card">
          <td>ID <?= htmlspecialchars($riwayat[$i]['id_transaksi']) ?></td>
          <td><?= htmlspecialchars($riwayat[$i]['nama']) ?></td>
          <td><?= htmlspecialchars($riwayat[$i]['tanggal_datang']) ?></td>
          <td><?= htmlspecialchars($riwayat[$i]['jumlah_tiket']) ?></td>
          <td>Rp <?= number_format($riwayat[$i]['harga_tiket'], 0, ',', '.') ?></td>
        </tr>
      <?php endfor; ?>
    </tbody>
  </table>
</section>


    <?php }elseif($role=='2'){ ?>
    
    <section class="mitra-profile">
    <div class="container">
        <div class="card">
            <div class="header">
            </div>
            <form action="#" method="post" class="form">
                <input type="text" id="nama" name="nama" placeholder="Nama" value="<?php echo $user[0]["nama"]; ?>" required>
                <input type="text" id="username" name="username" placeholder="Username" value="<?php echo $user[0]["username"]; ?>" required>
                <input type="tel" id="nomor-hp" name="nomor-hp" placeholder="Nomor HP" value="<?php echo $user[0]["no_hp"]; ?>" required>
                <input type="email" id="email" name="email" placeholder="Email" value="<?php echo $user[0]["email"]; ?>" required>
                <button type="submit" class="btn" name="update-m">Ubah Profil</button>
            </form>
            <form action="#" method="post" class="form">
                <input type="password" id="password-lama" name="password-lama" placeholder="Password-Lama" required>
                <input type="password" id="password" name="password" placeholder="Password-Baru" required>
                <input type="password" id="confirm-password" name="confirm-password" placeholder="Konfirmasi-Password" required>
                <button type="submit" class="btn" name="update-pass-m" >Ubah password</button>
            </form>
        </div>
    </div>
    </section>

    <?php } ?>
    <button type="submit" class="btn-log-out" onclick="return confirm('Apakah Anda yakin ingin Log Out?') ? window.location.href='login.php' : false;">Log Out</button>
    <button class="delete-account" onclick="return confirm('Apakah Anda yakin ingin menghapus akun ini?') ? window.location.href='hapus_akun.php?id=<?= $id ?>' : false;">Hapus Akun</button>
  </main>
</body>
</html>

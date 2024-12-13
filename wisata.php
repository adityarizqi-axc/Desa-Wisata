<?php
include('connection.php');
// Periksa apakah parameter id tersedia di URL
if (!isset($_GET['id'])) {
    echo "<h1>Akses ditolak</h1>";
    echo "<p>Anda belum login. <a href='login.php'>Login di sini</a></p>";
    exit;
}

// Ambil ID dari URL
$id = $_GET['id'];
$id_wisata = $_GET['dest'];

// Ambil angka depan ID untuk menentukan role
$role = substr($id, 0, 1);

if ($role == '1') {
    // Role pengguna
} elseif ($role == '2') {
    // Role mitra
} else {
    echo "<h1>Akses tidak dikenal</h1>";
    echo "<p>ID Anda: $id</p>";
}

//Fetch Wisata
$query_wisata = mysqli_query($connect, "SELECT  * from tb_wisata WHERE id_wisata = $id_wisata");
$wisata = mysqli_fetch_all($query_wisata, MYSQLI_ASSOC);

$query_review = mysqli_query($connect,"SELECT
    p.id_pengguna,
    p.id_wisata,
    p.bintang,
    p.catatan,
    COALESCE(q.username, 0) AS username
FROM
    tb_review p
LEFT JOIN
    tb_pengguna q
ON
    p.id_pengguna = q.id_pengguna
WHERE
    p.id_wisata = $id_wisata");

$review = mysqli_fetch_all($query_review, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detail Wisata</title>
  <link rel="stylesheet" href="css\pages\Detail-Wisata\style.css?v=1.0">
</head>
<body>
  <header>
    <div class="nav-container">
      <div class="logo">Wisata Suka Lestari</div>
      <nav>
        <ul>
          <li><a href="#">Beranda</a></li>
          <li><a href="#">Profil</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <main>
    <section class="hero-section">
      
    <img src="data:image/jpeg;base64,<?= base64_encode($wisata[0]['foto']) ?>" alt="<?= htmlspecialchars($wisata['nama']) ?>">
      <div class="hero-content">
        <h1><?= htmlspecialchars($wisata[0]['nama']) ?></h1>
        <h4><?= htmlspecialchars($wisata[0]['slogan']) ?></h4>
        <button class="btn-primary">Beli Tiket</button>
      </div>
    </section>

    <section class="content-section">
      <h2>Deskripsi</h2>
      <p><?= htmlspecialchars($wisata[0]['deskripsi']) ?></p>
    </section>

    <section class="content-section">
      <h2>Lokasi</h2>
      <p><?= htmlspecialchars($wisata[0]['alamat']) ?></p>
    </section>

    <section class="review-section">
      <h2>Review</h2>
      <?php
        for ($i = 0; $i < count($review); $i++){
          echo '<div class="review-card">';
          echo '<h3>' . htmlspecialchars($review[$i]["username"]) . 
               '<span class="stars">';

          for ($j = $review[$i]["bintang"]; $j > 0; $j--) {
              echo '★';
          }
          echo '</span></h3>';
      
          // Menampilkan catatan
          echo '<p>' . htmlspecialchars($review[$i]["catatan"]) . '</p>';
          echo '</div>';
        }
      ?>

    </section>
  </main>
</body>
</html>

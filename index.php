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
$query_wisata = mysqli_query($connect, "SELECT 
    w.id_wisata,
    w.nama,
    w.foto,
    w.slogan,
    w.deskripsi,
    COALESCE(AVG(r.bintang), 0) AS rata_rata_rating
FROM 
    tb_wisata w
LEFT JOIN 
    tb_review r
ON 
    w.id_wisata = r.id_wisata
GROUP BY 
    w.id_wisata, w.nama, w.foto, w.slogan, w.deskripsi;");
$wisata = mysqli_fetch_all($query_wisata, MYSQLI_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Wisata Suka Lestari</title>
  <link rel="stylesheet" href="css\style.css?v=1.0">
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
  <div class="hero-container">
    <div class="hero-content">
      <h1>Jelajahi Desa Suka Lestari dimulai Dari Sini</h1>
    </div>
    <div class="hero-image">
      <img src="images\island.png" alt="Desa Suka Lestari">
    </div>
  </div>
  </section>

  <section class="popular-places" id="popular-places">
      <h2>Tempat Wisata Populer</h2>
      <div class="cards-container">
        <?php foreach ($wisata as $item): ?>
          <div class="card">
            <img src="data:image/jpeg;base64,<?= base64_encode($item['foto']) ?>" alt="<?= htmlspecialchars($item['nama']) ?>">
            <div class="card-content">
              <h3><?= htmlspecialchars($item['nama']) ?></h3>
              <h5><?= htmlspecialchars($item['slogan']) ?></h5>
              <div class="card-footer">
                <?if ($item['id_wisata'] ==  ?>
                <span>Rating: <?= htmlspecialchars($item['rata_rata_rating']) ?>/5</span>
                <button onclick="window.location.href='wisata.php?id=<?=$id?>&dest=<?=$item['id_wisata']?>'">Lihat Selengkapnya</button>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </section>
  </main>
</body>
</html>

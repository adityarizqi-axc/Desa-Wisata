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
} elseif ($role == '2') {
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
  WHERE
      id_mitra = $id
  GROUP BY 
      w.id_wisata, w.nama, w.foto, w.slogan, w.deskripsi;");
} else {
    echo "<h1>Akses tidak dikenal</h1>";
    echo "<p>ID Anda: $id</p>";
}

$wisata = mysqli_fetch_all($query_wisata, MYSQLI_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Wisata Suka Lestari</title>
  <link rel="stylesheet" href="css\style.css?v=<?= time(); ?>">
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
  <div class="hero-container">
    <div class="hero-content">
      <?php if($role == '1'){ ?>
      <h1>Jelajahi Desa Suka Lestari dimulai Dari Sini</h1>
      <?php }elseif($role == '2'){ ?>
      <h1>Dashboard Mitra</h1>
      <?php } ?>
    </div>
    <div class="hero-image">
      <img src="images\island.png" alt="Desa Suka Lestari">
    </div>
  </div>
  </section>

  <section class="popular-places" id="popular-places">
      <?php if($role == '1'){ ?>
      <h2>Tempat Wisata Populer</h2>
      <?php }elseif($role == '2'){ ?>
      <h2>Tempat Wisata Anda</h2>
      <button class="tambah-btn" onclick="window.location.href='upload.php?id=<?=$id?>'">Tambah Wisata</button>
      <?php } ?>
      <div class="cards-container">
        <?php foreach ($wisata as $item): ?>
          <div class="card">
          <img src="<?= htmlspecialchars($item['foto']) ?>" alt="<?= htmlspecialchars($item['nama']) ?>" class="card-image">
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

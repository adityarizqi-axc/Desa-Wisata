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
    p.id_review,
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

//Fetch tiket
$query_tiket = mysqli_query($connect, "SELECT tanggal_datang, COUNT(tanggal_datang) as jumlah_tiket, SUM(harga) as pendapatan FROM id_tiket WHERE id_wisata = $id_wisata Group by tanggal_datang");
$tiket = mysqli_fetch_all($query_tiket, MYSQLI_ASSOC);
print_r($tiket);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detail Wisata</title>
  <link rel="stylesheet" href="css\pages\Detail-Wisata\style.css?v=<?= time(); ?>">
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
    <img src="<?= htmlspecialchars($wisata[0]['foto']) ?>" alt="<?= htmlspecialchars($wisata[0]['nama']) ?>" class="card-image">
      <div class="hero-content">
        <h1><?= htmlspecialchars($wisata[0]['nama']) ?></h1>
        <h4><?= htmlspecialchars($wisata[0]['slogan']) ?></h4>
        <?php if($role == '1'){ ?>
          <button class="btn-primary" onclick="window.location.href='Beli_Tiket.php?id=<?=$id?>&dest=<?=$id_wisata?>'">Beli Tiket</button>
        <?php }elseif($role == '2'){ ?>
          <div class="mitra-button">
          <button class="edit" onclick="window.location.href='edit_wisata.php?id=<?=$id?>&dest=<?=$id_wisata?>'">Edit</button>
          <button class="delete" onclick="window.location.href='delete_wisata.php?id=<?=$id?>&dest=<?=$id_wisata?>'">Delete</button>
        </div>
        <?php } ?>
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
          echo '<h3>'.$review[$i]["username"]. 
               '<span class="stars">';

          for ($j = $review[$i]["bintang"]; $j > 0; $j--) {
              echo '★';
          }

          if($role == '2'){
          echo '<a href="hapus_review.php?id='.$id.'&idrev='.$review[$i]["id_review"].'&dest='.$id_wisata.'" class="delete-review" onclick="return confirm(\'Yakin ingin menghapus review ini?\')">Hapus Review</a>';
          }

          echo '</span></h3>';
          // Menampilkan catatan
          echo '<p>'.$review[$i]["catatan"].'</p>';
          echo '</div>';
        }
      ?>

      <?php if($role == '1'){ ?>
        <form action="#" method="post" class="form">

                <label for="jumlah">Bintang</label>
                <input type="number" id="bintang" name="bintang" min="1" max="5" required>

                <label for="review">Deskripsi</label>
                <input type="text" id="catatan" name="catatan" required>

                <button type="submit" class="btn-primary" name="review">Kirim Review</button>
        </form>
      <?php }if($role == '2'){?>
        <section class="riwayat-section">
          <h2>Tiket Terjual</h2>
          <button class="delete-riwayat" onclick="return confirm('Apakah Anda yakin ingin menghapus riwayat tiket?') ? window.location.href='hapus_riwayat.php?id=<?=$id?>&dest=<?=$id_wisata?>' : false;">Hapus Data Tiket</button>
          <table class="riwayat-table">
            <!-- Header Tabel -->
            <thead class="riwayat-header">
              <tr>
                <th>Tanggal Datang</th>
                <th>Jumlah Tiket</th>
                <th>Pendapatan</th>
              </tr>
            </thead>
            <!-- Isi Tabel -->
            <tbody>
              <?php for ($i = 0; $i < count($tiket); $i++): ?>
                <tr class="riwayat-card">
                  <td><?= htmlspecialchars($tiket[$i]['tanggal_datang']) ?></td>
                  <td><?= htmlspecialchars($tiket[$i]['jumlah_tiket']) ?></td>
                  <td>Rp<?= htmlspecialchars($tiket[$i]['pendapatan']) ?></td>
                </tr>
              <?php endfor; ?>
            </tbody>
          </table>
        </section>
      <?php }
      if(isset($_POST['review'])){
        $bintang = $_POST["bintang"];
        $catatan = $_POST["catatan"];
      
        $query_review = "INSERT tb_review SET 
                  Bintang='$bintang' ,
                  Catatan='$catatan' ,
                  id_pengguna ='$id',
                  id_wisata ='$id_wisata'";
        $hasil = mysqli_query($connect, $query_review);
      
        if($hasil){
          echo "<script>alert('Review berhasil diunggah!');</script>";
          header("Location: wisata.php?id=$id&dest=$id_wisata");
        } else {
          echo "<script>alert('Review gagal diunggah!');</script>";
        }
      }
      ?>

    </section>
  </main>
</body>
</html>

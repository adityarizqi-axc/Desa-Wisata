<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Riwayat Pembelian</title>
  <link rel="stylesheet" href="css\pages\Profil\style.css">
</head>
<body>
  <header>
    <div class="nav-container">
      <div class="logo">Wisata Suka Lestari</div>
      <nav>
        <ul>
          <li><a href="#">Beranda</a></li>
          <li><a href="#">Daftar Wisata</a></li>
          <li><a href="#"><i class="user-icon">👤</i></a></li>
        </ul>
      </nav>
    </div>
  </header>

  <main>
    <section class="hero-section">
      <h1>Halo, Cahaya Dewi</h1>
    </section>
    
    <section class="user-profile">
    <div class="container">
        <div class="card">
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

                <button type="submit" class="btn">Ubah Profil</button>
            </form>
        </div>
    </div>
    </section>
    
    <section class="mitra-profile">
    <div class="container">
        <div class="card">
            <div class="header">
            </div>
            <form action="#" method="post" class="form">
                <input type="text" id="nama" name="nama" placeholder="Nama" required>
                <input type="text" id="username" name="username" placeholder="Username" required>
                <input type="tel" id="nomor-hp" name="nomor-hp" placeholder="Nomor HP" required>
                <input type="email" id="email" name="email" placeholder="Email" required>
                <input type="password" id="password" name="password" placeholder="Password" required>
                <button type="submit" class="btn">Ubah Profil</button>
            </form>
        </div>
    </div>
    </section>

    <section class="riwayat-section">
      <h2>Riwayat Pembelian</h2>
      <div class="riwayat-card">
        <span class="title">Pura Tanah Lot</span>
        <span class="date">14/5/24</span>
        <span class="price">Rp45.000</span>
      </div>
      <div class="riwayat-card">
        <span class="title">Pura Tanah Lot</span>
        <span class="date">24/3/24</span>
        <span class="price">Rp45.000</span>
      </div>
    </section>
  </main>
</body>
</html>

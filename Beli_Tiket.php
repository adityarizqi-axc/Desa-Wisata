<?php
include("connection.php");

$id_pengguna = $_GET['id']; // Ganti sesuai dengan session ID pengguna aktif
$id_wisata = $_GET['dest'];

$query_fetch_wisata = mysqli_query($connect, "SELECT * from tb_wisata WHERE id_wisata = $id_wisata");
$wisata = mysqli_fetch_all($query_fetch_wisata, MYSQLI_ASSOC);
$harga_tiket = $wisata[0]['harga_tiket'];

//Generate ID TRANSAKSI
$query_last_transaksi = "SELECT MAX(id_transaksi) AS last_id FROM tb_transaksi";
$result = mysqli_query($connect, $query_last_transaksi);
$row = mysqli_fetch_assoc($result);

if ($row['last_id']) {
    // Ekstrak nomor urut terakhir
    $last_id = substr($row['last_id'], -4); // Ambil 4 digit terakhir
    $new_number = str_pad((int)$last_id + 1, 4, '0', STR_PAD_LEFT); // Tambahkan 1 dan padding
} else {
    $new_number = '0001'; // Jika tidak ada data, mulai dari 0001
}

// Buat ID transaksi
$kode_transaksi = '9'; // Kode ID transaksi
$nomor_mitra = str_pad(substr($wisata[0]['id_mitra'], -2), 2, '0', STR_PAD_LEFT); // Dua digit terakhir ID mitra
$nomor_wisata = str_pad(substr($id_wisata, -2), 2, '0', STR_PAD_LEFT); // Dua digit terakhir ID wisata
$id_transaksi = $kode_transaksi . $nomor_mitra . $nomor_wisata . $new_number;




if(isset($_POST['beli_tiket'])){
    $tanggal_datang = $_POST['tanggal-datang'];
    $jumlah_tiket = $_POST['jumlah'];
    $total_harga = $_POST['total-harga'];
    $tanggal_pemesanan = date('Y-m-d'); // Format: YYYY-MM-DD
    $status_pembayaran = 'Lunas';

    // Simpan transaksi ke tabel tb_transaksi
    $query_transaksi = "INSERT INTO tb_transaksi (id_transaksi, id_pengguna, tanggal_pemesanan, status_pembayaran, total_harga) 
                        VALUES ('$id_transaksi','$id_pengguna', '$tanggal_pemesanan', '$status_pembayaran', '$total_harga')";
    if (mysqli_query($connect, $query_transaksi)) {

        // Generate tiket untuk setiap tiket yang dipesan
        for ($i = 0; $i < $jumlah_tiket; $i++) {
            
            $query_last_tiket = "SELECT MAX(id_tiket) AS last_id FROM id_tiket WHERE id_wisata=$id_wisata";
            $result = mysqli_query($connect, $query_last_tiket);
            $row = mysqli_fetch_assoc($result);

            if ($row['last_id']) {
                // Ekstrak nomor urut terakhir
                $last_id = substr($row['last_id'], -4); // Ambil 4 digit terakhir
                $new_number = str_pad((int)$last_id + 1, 4, '0', STR_PAD_LEFT); // Tambahkan 1 dan padding
            } else {
                $new_number = '0001'; // Jika tidak ada data, mulai dari 0001
            }

            $kode_tiket = '8';
            $nomor_mitra = str_pad(substr($wisata[0]['id_mitra'], -2), 2, '0', STR_PAD_LEFT); // Dua digit terakhir ID mitra
            $nomor_wisata = str_pad(substr($id_wisata, -2), 2, '0', STR_PAD_LEFT); // Dua digit terakhir ID wisata
            $id_tiket = $kode_tiket . $nomor_mitra . $nomor_wisata . $new_number;

            $query_tiket = "INSERT INTO id_tiket(id_tiket, id_transaksi, id_wisata, harga, tanggal_datang) VALUES ('$id_tiket','$id_transaksi', '$id_wisata','$harga_tiket','$tanggal_datang')";
            mysqli_query($connect, $query_tiket);
        }

        echo "<script>alert('Transaksi berhasil! Tiket Anda telah dibuat.'); window.location.href='index.php?id=$id_pengguna';</script>";
    } else {
        echo "<script>alert('Gagal menyimpan transaksi.'); window.history.back();</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi Tiket</title>
    <link rel="stylesheet" href="css\pages\RegisterPengguna\style.css">
    <script>
        const hargaPerTiket = <?= $wisata[0]['harga_tiket'] ?>;

        function updateTotal() {
            const jumlahTiket = parseInt(document.getElementById('jumlah').value) || 0;
            const totalHarga = hargaPerTiket * jumlahTiket;
            document.getElementById('total-harga').value = totalHarga;
        }
    </script>
</head>
<body>
    <div class="container">
        <div class="card">
            <img src="images\saly.png" alt="Icon" class="icon">
            <h1>Transaksi Tiket</h1>
            <h3>Tempat Wisata</h3>
            <form action="#" method="post" class="form">
                <label for="tanggal-datang">Tanggal Datang</label>
                <input type="date" id="tanggal-datang" name="tanggal-datang" required>

                <label for="jumlah">jumlah tiket</label>
                <input type="number" id="jumlah" name="jumlah" oninput="updateTotal()" required>

                <label for="harga">total harga</label>
                <input type="number" id="total-harga" name="total-harga" readonly>

                <button type="submit" class="btn" name="beli_tiket">Bayar Sekarang</button>
            </form>
        </div>
    </div>
</body>
</html>

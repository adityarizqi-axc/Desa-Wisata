<?php
// Koneksi ke database
include 'connection.php';
$id_user = $_GET['id'];
$id_wisata = $GET['dest'];
// Tangkap ID dari parameter URL
if (isset($_GET['dest'])) {
    $id = intval($_GET['dest']); // Pastikan ID berupa integer untuk keamanan
    // Query untuk menghapus review berdasarkan ID
    $query = "DELETE FROM tb_tiket WHERE id_wisata = $id_wisata";

    if (mysqli_query($connect, $query)) {
        // Redirect kembali ke halaman sebelumnya
        header("Location: wisata.php?id=$id_user&dest=$id_wisata");
        exit;
    } else {
        echo "Gagal menghapus tiket: " . mysqli_error($connect);
    }
} else {
    echo "ID wisata tidak ditemukan.";
}
?>

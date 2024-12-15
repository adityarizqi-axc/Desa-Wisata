<?php
// Koneksi ke database
include 'connection.php';
$id_user = $_GET['id'];
$id_wisata = $_GET['dest'];
// Tangkap ID dari parameter URL
if (isset($_GET['idrev'])) {
    $id = intval($_GET['idrev']); // Pastikan ID berupa integer untuk keamanan
    // Query untuk menghapus review berdasarkan ID
    $query = "DELETE FROM tb_review WHERE id_review = $id";

    if (mysqli_query($connect, $query)) {
        // Redirect kembali ke halaman sebelumnya
        header("Location: wisata.php?id=$id_user&dest=$id_wisata");
        exit;
    } else {
        echo "Gagal menghapus review: " . mysqli_error($connect);
    }
} else {
    echo "ID review tidak ditemukan.";
}
?>

<?php
// Koneksi ke database
include 'connection.php';
$id_user = $_GET['id'];
// Tangkap ID dari parameter URL
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Pastikan ID berupa integer untuk keamanan
    // Query untuk menghapus review berdasarkan ID
    $query = "DELETE FROM tb_pengguna WHERE id_pengguna = $id";

    if (mysqli_query($connect, $query)) {
        // Redirect kembali ke halaman sebelumnya
        header("Location: login.php");
        exit;
    } else {
        echo "Gagal menghapus akun: " . mysqli_error($connect);
    }
} else {
    echo "ID pengguna tidak ditemukan.";
}
?>

<?php
    $connect = new mysqli('localhost','root','','db_desawisata');

    if(!$connect){
        echo "Koneksi Database Gagal";
    }
?>
<?php
    $connect = new mysqli('localhost','root','','db_desawisata');

    if(!$connect){
        echo "Gagal";
    }
    else{
        echo "Berhasil";
    }
?>
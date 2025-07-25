<?php
include '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $stok = $_POST['stok'];
    $harga = $_POST['harga'];

    $query = "INSERT INTO stok_makanan (nama, stok, harga) VALUES ('$nama', '$stok', '$harga')";
    mysqli_query($conn, $query);
    
    header("Location: index.php");
    exit();
}
?>
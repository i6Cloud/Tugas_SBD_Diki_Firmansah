<?php
include '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $stok = $_POST['stok'];
    $harga = $_POST['harga'];

    $query = "UPDATE stok_makanan SET nama='$nama', stok='$stok', harga='$harga' WHERE id=$edit_id";
    mysqli_query($conn, $query);

    header("Location: index.php");
    exit();
}
?>
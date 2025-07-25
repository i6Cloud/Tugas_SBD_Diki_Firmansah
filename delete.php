<?php
include 'config.php';

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM stok_makanan WHERE id=$id");
    header("Location: index.php");
    exit();
}
header("location: ../index.php");
exit();
?>
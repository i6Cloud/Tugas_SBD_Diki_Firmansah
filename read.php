<?php
include '../config.php';

$where = "WHERE 1=1";
if (!empty($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $where .= " AND nama LIKE '%$search%'";
}
if (!empty($_GET['stok_filter']))
    if ($_GET['stok_filter'] == 'kurang30') {
        $where .= " AND stok < 30";
    } elseif ($_GET['stok_filter'] == 'lebih50') {
        $where .= " AND stok > 50";
}

$result = mysqli_query($conn, "SELECT * FROM stok_makanan $where ORDER BY nama ASC");
?>
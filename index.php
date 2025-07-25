<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_stok_makanan"; // sesuai permintaan user

$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
    die("Koneksi gagal:" . mysqli_connect_error());
}

// Inisialisasi variabel
$nama = $stok = $harga = "";
$edit_id = null;

// Tambah / Edit Data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $stok = $_POST['stok'];
    $harga = $_POST['harga'];

    if (!empty($_POST['id'])) {
        $edit_id = $_POST['id'];
        $query = "UPDATE stok_makanan SET nama='$nama', stok='$stok', harga='$harga' WHERE id=$edit_id";
    } else {
        $query = "INSERT INTO stok_makanan (nama, stok, harga) VALUES ('$nama', '$stok', '$harga')";
    }
    mysqli_query($conn, $query);
    header("Location: index.php");
    exit();
}

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM stok_makanan WHERE id=$id");
    header("Location: index.php");
    exit();
}

if (isset($_GET['edit'])) {
    $edit_id = $_GET['edit'];
    $data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM stok_makanan WHERE id=$edit_id"));
    $nama = $data['Nama'];
    $stok = $data['Stok'];
    $harga = $data['Harga'];
}

// Filter & Search
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

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Manajemen Stok Makanan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f5f7fa;
      font-family: 'Segoe UI', sans-serif;
    }
    h2 {
      text-align: center;
      margin-bottom: 30px;
      color: #2c3e50;
    }
    .form-section, .table-section {
      background-color: #ffffff;
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.06);
    }
    .table thead th {
      background-color: #d6eaf8;
      text-align: center;
    }
    .table tbody td {
      vertical-align: middle;
      text-align: center;
    }
    .table-hover tbody tr:hover {
      background-color: #f1f9ff;
    }
    .btn-primary, .btn-success {
      width: 100%;
    }
    .form-label {
      font-weight: 600;
    }
  </style>
  <script>
    function confirmHapus() {
      return confirm("Yakin ingin menghapus data ini?");
    }
  </script>
</head>
<body>
  <div class="container py-5">
    <h2>Manajemen Stok Makanan</h2>

    <div class="row">
      <!-- Form Input -->
      <div class="col-md-4 mb-4">
        <div class="form-section">
          <h5 class="text-center"><?= $edit_id ? 'Edit Data' : 'Tambah Data' ?></h5>
          <form method="post" class="mt-3">
            <input type="hidden" name="id" value="<?= $edit_id ?>">
            <div class="mb-3">
              <label class="form-label">Nama Makanan</label>
              <input type="text" name="nama" class="form-control" value="<?= $nama ?>" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Stok</label>
              <input type="number" name="stok" class="form-control" value="<?= $stok ?>" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Harga (Rp)</label>
              <input type="number" name="harga" class="form-control" value="<?= $harga ?>" required>
            </div>
            <button type="submit" class="btn btn-success"><?= $edit_id ? 'Update' : 'Tambah' ?></button>
            <?php if ($edit_id): ?>
              <a href="index.php" class="btn btn-secondary mt-2">Batal</a>
            <?php endif; ?>
          </form>
        </div>
      </div>

      <!-- Tabel & Filter -->
      <div class="col-md-8">
        <div class="table-section">
          <form class="row g-2 mb-4" method="get">
            <div class="col-md-5">
              <input type="text" name="search" class="form-control" placeholder="Cari nama makanan..." value="<?= $_GET['search'] ?? '' ?>">
            </div>
            <div class="col-md-4">
              <select name="stok_filter" class="form-select">
                <option value="">Filter Stok</option>
                <option value="kurang30" <?= ($_GET['stok_filter'] ?? '') == 'kurang30' ? 'selected' : '' ?>>Stok &lt; 30</option>
                <option value="lebih50" <?= ($_GET['stok_filter'] ?? '') == 'lebih50' ? 'selected' : '' ?>>Stok &gt; 50</option>
              </select>
            </div>
            <div class="col-md-3 d-flex">
              <button class="btn btn-primary me-2">Terapkan</button>
              <a href="index.php" class="btn btn-outline-secondary">Reset</a>
            </div>
          </form>

          <table class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Stok</th>
                <th>Harga</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php $no = 1; while ($row = mysqli_fetch_assoc($result)): ?>
              <tr>
                <td><?= $no++ ?></td>
                <td><?= $row['Nama'] ?></td>
                <td><?= $row['Stok'] ?></td>
                <td>Rp <?= number_format($row['Harga'], 0, ',', '.') ?></td>
                <td>
                  <a href="?edit=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                  <a href="?hapus=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirmHapus()">Hapus</a>
                </td>
              </tr>
              <?php endwhile; ?>
              <?php if (mysqli_num_rows($result) == 0): ?>
              <tr><td colspan="5" class="text-center text-muted">Data tidak ditemukan</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</body>
</html>

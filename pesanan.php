<?php
session_start();
if (!isset($_SESSION['login'])) {
    header('Location: login.php');
    exit;
}
include 'koneksi.php';

$menu = mysqli_query($conn, "SELECT * FROM menu");

if (isset($_POST['simpan'])) {
    $menu_id = $_POST['menu_id'];
    $jumlah = $_POST['jumlah'];
    $dataMenu = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM menu WHERE id=$menu_id"));
    $total = $dataMenu['harga'] * $jumlah;
    mysqli_query($conn, "INSERT INTO transaksi (menu_id, jumlah, total) VALUES ('$menu_id', '$jumlah', '$total')");
    header('Location: pesanan.php');
}

$transaksi = mysqli_query($conn, "SELECT transaksi.*, menu.nama FROM transaksi JOIN menu ON transaksi.menu_id = menu.id ORDER BY transaksi.tanggal DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-success">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">Kasir</a>
        <div>
            <a href="logout.php" class="btn btn-light">Logout</a>
        </div>
    </div>
</nav>
<div class="container mt-4">
    <h3>Input Pesanan</h3>
    <form method="POST" class="row g-3 mb-3">
        <div class="col-auto">
            <select name="menu_id" class="form-select" required>
                <option value="">Pilih Menu</option>
                <?php foreach($menu as $row): ?>
                    <option value="<?= $row['id'] ?>"><?= $row['nama'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-auto">
            <input type="number" name="jumlah" class="form-control" placeholder="Jumlah" required>
        </div>
        <div class="col-auto">
            <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
        </div>
    </form>
    <h3>Riwayat Transaksi</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Menu</th>
                <th>Jumlah</th>
                <th>Total</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1; foreach($transaksi as $row): ?>
            <tr>
                <td><?= $i++ ?></td>
                <td><?= $row['nama'] ?></td>
                <td><?= $row['jumlah'] ?></td>
                <td>Rp<?= number_format($row['total'],0,',','.') ?></td>
                <td><?= $row['tanggal'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>

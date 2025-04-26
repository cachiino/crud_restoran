<?php
session_start();
if (!isset($_SESSION['login'])) {
    header('Location: login.php');
    exit;
}
include 'koneksi.php';

$menu = mysqli_query($conn, "SELECT * FROM menu");

if (isset($_POST['tambah'])) {
    $nama = $_POST['nama'];
    $harga = $_POST['harga'];
    mysqli_query($conn, "INSERT INTO menu (nama, harga) VALUES ('$nama', '$harga')");
    header('Location: index.php');
}

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM menu WHERE id=$id");
    header('Location: index.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Kasir</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-success">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Kasir</a>
        <div>
            <a href="pesanan.php" class="btn btn-light">Pesanan</a>
            <a href="logout.php" class="btn btn-light">Logout</a>
        </div>
    </div>
</nav>
<div class="container mt-4">
    <h3>Dashboard Kasir</h3>
    <form method="POST" class="row g-3 mb-3">
        <div class="col-auto">
            <input type="text" name="nama" class="form-control" placeholder="Nama Menu" required>
        </div>
        <div class="col-auto">
            <input type="number" name="harga" class="form-control" placeholder="Harga" required>
        </div>
        <div class="col-auto">
            <button type="submit" name="tambah" class="btn btn-success">Tambah</button>
        </div>
    </form>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama</th>
                <th>Harga</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1; foreach($menu as $row): ?>
            <tr>
                <td><?= $i++ ?></td>
                <td><?= $row['nama'] ?></td>
                <td>Rp<?= number_format($row['harga'],0,',','.') ?></td>
                <td>
                    <a href="index.php?hapus=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus menu?')">Hapus</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>

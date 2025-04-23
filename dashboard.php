<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);
session_start();
if (!isset($_SESSION["login"])) {
    header("Location: index.php");
    exit;
}
/$conn = new mysqli("localhost", "root", "", "crud_restoran");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST["update"])) {
    $id = $_POST["id"];
    $nama = $_POST["nama"];
    $harga = $_POST["harga"];
    $conn->query("UPDATE menu SET nama='$nama', harga=$harga WHERE id=$id");
    header("Location: dashboard.php"); // supaya form kembali ke mode normal
}


if (isset($_POST["tambah"])) {
    $nama = $_POST["nama"];
    $harga = $_POST["harga"];
    $conn->query("INSERT INTO menu (nama, harga) VALUES ('$nama', $harga)");
}
$edit_mode = false;
$edit_data = null;
if (isset($_GET["edit"])) {
    $edit_mode = true;
    $id = $_GET["edit"];
    $edit_data = $conn->query("SELECT * FROM menu WHERE id=$id")->fetch_assoc();
}
if (isset($_GET["hapus"])) {
    $id = $_GET["hapus"];
    $conn->query("DELETE FROM menu WHERE id=$id");
}
$result = $conn->query("SELECT * FROM menu");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Kasir</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include 'navbar.php'; ?>
<div class="container mt-4">
    <h3>Dashboard Kasir</h3>
    
    <form method="post" class="row g-2 mb-4">
    <input type="hidden" name="id" value="<?= $edit_data['id'] ?? '' ?>">
    <div class="col-md-5">
        <input type="text" name="nama" class="form-control" placeholder="Nama Menu"
            value="<?= $edit_data['nama'] ?? '' ?>" required>
    </div>
    <div class="col-md-3">
        <input type="number" name="harga" class="form-control" placeholder="Harga"
            value="<?= $edit_data['harga'] ?? '' ?>" required>
    </div>
    <div class="col-md-2">
        <button name="<?= $edit_mode ? 'update' : 'tambah' ?>" class="btn btn-<?= $edit_mode ? 'warning' : 'success' ?> w-100">
            <?= $edit_mode ? 'Update' : 'Tambah' ?>
        </button>
    </div>
</form>

    <table class="table table-bordered">
        <thead><tr><th>#</th><th>Nama</th><th>Harga</th><th>Aksi</th></tr></thead>
        <tbody>
            <?php $i = 1; while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><?= $row["nama"] ?></td>
                    <td>Rp<?= number_format($row["harga"]) ?></td>
                    <td>
                        <a href="?edit=<?= $row["id"] ?>" class="btn btn-warning btn-sm">Edit</a>
                         <a href="?hapus=<?= $row["id"] ?>" class="btn btn-danger btn-sm">Hapus</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>

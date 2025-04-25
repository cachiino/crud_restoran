<?php
session_start();
if (!isset($_SESSION["login"])) {
    header("Location: index.php");
    exit;
}
require 'config.php';

// Buat tabel jika belum ada
$conn->query("CREATE TABLE IF NOT EXISTS transaksi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    menu_id INT,
    jumlah INT,
    total INT,
    tanggal DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY(menu_id) REFERENCES menu(id)
)");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $menu_id = $_POST["menu_id"];
    $jumlah = $_POST["jumlah"];
    $get_harga = $conn->query("SELECT harga FROM menu WHERE id=$menu_id")->fetch_assoc();
    $total = $get_harga["harga"] * $jumlah;
    $conn->query("INSERT INTO transaksi (menu_id, jumlah, total) VALUES ($menu_id, $jumlah, $total)");
}

$menus = $conn->query("SELECT * FROM menu");
$result = $conn->query("SELECT t.*, m.nama FROM transaksi t JOIN menu m ON t.menu_id = m.id ORDER BY t.tanggal DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Pesanan & Riwayat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container mt-4">
    <h4>Input Pesanan</h4>
    <form method="post" class="row g-2 mb-4">
        <div class="col-md-6">
            <select name="menu_id" class="form-select" required>
                <option value="">Pilih Menu</option>
                <?php while ($m = $menus->fetch_assoc()): ?>
                    <option value="<?= $m["id"] ?>"><?= $m["nama"] ?> - Rp<?= number_format($m["harga"]) ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="col-md-3">
            <input type="number" name="jumlah" class="form-control" placeholder="Jumlah" required>
        </div>
        <div class="col-md-3">
            <button class="btn btn-primary w-100">Simpan</button>
        </div>
    </form>

    <h4>Riwayat Transaksi</h4>
    <table class="table table-bordered">
        <thead><tr><th>#</th><th>Menu</th><th>Jumlah</th><th>Total</th><th>Tanggal</th></tr></thead>
        <tbody>
            <?php $i = 1; while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><?= $row["nama"] ?></td>
                    <td><?= $row["jumlah"] ?></td>
                    <td>Rp<?= number_format($row["total"]) ?></td>
                    <td><?= $row["tanggal"] ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>

<?php
session_start();
if (!isset($_SESSION["login"])) {
    header("Location: index.php");
    exit;
}
$conn = new mysqli("localhost", "root", "", "crud_restoran");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$result = $conn->query("SELECT t.*, m.nama FROM transaksi t JOIN menu m ON t.menu_id = m.id ORDER BY t.tanggal DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Riwayat Transaksi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container mt-4">
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

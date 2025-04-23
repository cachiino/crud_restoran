<?php
session_start();
if (!isset($_SESSION["login"])) {
    header("Location: index.php");
    exit;
}
$conn = new mysqli("localhost", "root", "", "crud_restoran");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$conn->query("CREATE TABLE IF NOT EXISTS transaksi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    menu_id INT,
    jumlah INT,
    total INT,
    tanggal DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY(menu_id) REFERENCES menu(id)
)");

$menus = $conn->query("SELECT * FROM menu");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $menu_id = $_POST["menu_id"];
    $jumlah = $_POST["jumlah"];
    $get_harga = $conn->query("SELECT harga FROM menu WHERE id=$menu_id")->fetch_assoc();
    $total = $get_harga["harga"] * $jumlah;
    $conn->query("INSERT INTO transaksi (menu_id, jumlah, total) VALUES ($menu_id, $jumlah, $total)");
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Pesanan</title>
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
</div>
</body>
</html>

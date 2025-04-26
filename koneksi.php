<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "kasirdb";

$conn = mysqli_connect($host, $user, $pass);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Buat database kalau belum ada
$sql = "CREATE DATABASE IF NOT EXISTS $db";
if (mysqli_query($conn, $sql)) {
    mysqli_select_db($conn, $db);
} else {
    die("Error creating database: " . mysqli_error($conn));
}

// Buat tabel users kalau belum ada
mysqli_query($conn, "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL
)");

// Insert user admin kalau belum ada
$check = mysqli_query($conn, "SELECT * FROM users WHERE username='admin'");
if (mysqli_num_rows($check) == 0) {
    $password = password_hash('123', PASSWORD_DEFAULT);
    mysqli_query($conn, "INSERT INTO users (username, password) VALUES ('admin', '$password')");
}

// Buat tabel menu kalau belum ada
mysqli_query($conn, "CREATE TABLE IF NOT EXISTS menu (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    harga INT NOT NULL
)");

// Buat tabel transaksi kalau belum ada
mysqli_query($conn, "CREATE TABLE IF NOT EXISTS transaksi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    menu_id INT,
    jumlah INT,
    total INT,
    tanggal TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (menu_id) REFERENCES menu(id)
)");
?>

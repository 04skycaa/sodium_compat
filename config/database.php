<?php
$servername = "localhost";
$username = "root"; // Ganti dengan 'root'
$password = "";      // Biarkan kosong
$dbname = "e_simaksi";

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
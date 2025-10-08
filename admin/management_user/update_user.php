<?php
include '../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id_pengguna'];
    $nama = $_POST['nama_lengkap'];
    $email = $_POST['email'];
    $telepon = $_POST['nomor_telepon'];
    $alamat = $_POST['alamat'];
    $peran = $_POST['peran'];

    $sql = "UPDATE pengguna SET 
            nama_lengkap='$nama', 
            email='$email', 
            nomor_telepon='$telepon', 
            alamat='$alamat', 
            peran='$peran'
            WHERE id_pengguna='$id'";

    if ($conn->query($sql)) {
        echo "success";
    } else {
        echo "error";
    }
}
?>

<?php 
include '../../config/database.php';

// Ambil data pengguna berdasarkan ID
$id = isset($_GET['id']) ? $_GET['id'] : '';

if ($id) {
    $query = "SELECT * FROM pengguna WHERE id_pengguna = '$id'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        echo "<script>alert('Data pengguna tidak ditemukan'); window.location.href='management_user.php';</script>";
        exit;
    }
} else {
    echo "<script>alert('ID pengguna tidak diberikan'); window.location.href='management_user.php';</script>";
    exit;
}

// Variabel notifikasi
$notif = '';

// Proses update data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_lengkap = $_POST['nama_lengkap'];
    $email = $_POST['email'];
    $nomor_telepon = $_POST['nomor_telepon'];
    $alamat = $_POST['alamat'];
    $peran = $_POST['peran'];
    $dibuat_pada = $_POST['dibuat_pada'];

    $update = "UPDATE pengguna SET 
        nama_lengkap='$nama_lengkap', 
        email='$email', 
        nomor_telepon='$nomor_telepon', 
        alamat='$alamat', 
        peran='$peran',
        dibuat_pada='$dibuat_pada'
        WHERE id_pengguna='$id'";

    if ($conn->query($update)) {
        $notif = 'success';
    } else {
        $notif = 'failed';
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Data User</title>
  <link rel="stylesheet" href="../../assets/css/style.css">
  <!-- Tambahkan SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<div class="form-container">
  <h2>Edit Data User</h2>
  <form id="editUserForm">
    <input type="hidden" name="id_pengguna" value="<?= $user['id_pengguna']; ?>">

    <div class="input-group">
      <input type="text" name="nama_lengkap" value="<?= $user['nama_lengkap']; ?>" required>
      <label>Nama Lengkap</label>
    </div>

    <div class="input-group">
      <input type="email" name="email" value="<?= $user['email']; ?>" required>
      <label>Email</label>
    </div>

    <div class="input-group">
      <input type="text" name="nomor_telepon" value="<?= $user['nomor_telepon']; ?>">
      <label>No. Telepon</label>
    </div>

    <div class="input-group">
      <input type="text" name="alamat" value="<?= $user['alamat']; ?>">
      <label>Alamat</label>
    </div>

    <div class="input-group">
      <select name="peran" required>
        <option value="" disabled>Pilih Role</option>
        <option value="admin" <?= $user['peran'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
        <option value="pendaki" <?= $user['peran'] === 'pendaki' ? 'selected' : ''; ?>>Pendaki</option>
      </select>
      <label>Role</label>
    </div>

    <button type="submit">Simpan</button>
  </form>
</div>

<script src="../../assets/js/edit_user.js"></script>
</body>
</html>


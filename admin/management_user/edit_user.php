<?php 
include '../../config/database.php';
header('X-Content-Type-Options: nosniff');

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

// Jika ada request POST dari fetch()
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json; charset=utf-8');

    $id = $_POST['id_pengguna'];
    $nama_lengkap = $_POST['nama_lengkap'];
    $email = $_POST['email'];
    $nomor_telepon = $_POST['nomor_telepon'];
    $alamat = $_POST['alamat'];
    $peran = $_POST['peran'];
    $dibuat_pada = $_POST['dibuat_pada'] ?? date('Y-m-d H:i:s');

    $update = "UPDATE pengguna SET 
        nama_lengkap='$nama_lengkap', 
        email='$email', 
        nomor_telepon='$nomor_telepon', 
        alamat='$alamat', 
        peran='$peran',
        dibuat_pada='$dibuat_pada'
        WHERE id_pengguna='$id'";

    if ($conn->query($update)) {
        echo json_encode(['status' => 'success', 'message' => 'Data pengguna berhasil diperbarui!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui data.', 'error' => $conn->error]);
    }
    exit;
}
?>

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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.getElementById('editUserForm').addEventListener('submit', async function (e) {
  e.preventDefault();
  const form = e.target;

  try {
    const res = await fetch('edit_user.php?id=<?= $user['id_pengguna']; ?>', {
      method: 'POST',
      body: new FormData(form)
    });

    const json = await res.json();

    if (json.status === 'success') {
      Swal.fire({
        icon: 'success',
        title: json.message,
        showConfirmButton: false,
        timer: 1200
      }).then(() => {
        window.location.href = 'management_user.php';
      });
    } else {
      Swal.fire({
        icon: 'error',
        title: 'Gagal',
        text: json.message + (json.error ? ' (' + json.error + ')' : '')
      });
    }
  } catch (err) {
    console.error(err);
    Swal.fire({
      icon: 'error',
      title: 'Kesalahan',
      text: 'Terjadi kesalahan jaringan atau server.'
    });
  }
});
</script>

<?php
include __DIR__ . '/../../config/database.php';

$isModal = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
$query = "SELECT * FROM kategori_pengeluaran";
$result = mysqli_query($conn, $query);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    error_log("Form diterima di tambah_pengeluaran.php; isModal=" . ($isModal ? '1' : '0'));

    $id_admin = 1; 
    $id_kategori = mysqli_real_escape_string($conn, $_POST['id_kategori'] ?? '');
    $jumlah = mysqli_real_escape_string($conn, $_POST['jumlah'] ?? '');
    $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan'] ?? '');
    $tanggal = mysqli_real_escape_string($conn, $_POST['tanggal_pengeluaran'] ?? '');

    if ($id_kategori === '' || $jumlah === '' || $tanggal === '') {
        if ($isModal) {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => 'Data tidak lengkap']);
        } else {
            echo "<script>alert('Data tidak lengkap'); history.back();</script>";
        }
        exit;
    }

    $sql = "INSERT INTO pengeluaran (id_admin, id_kategori, jumlah, keterangan, tanggal_pengeluaran, dibuat_pada)
            VALUES ('$id_admin', '$id_kategori', '$jumlah', '$keterangan', '$tanggal', NOW())";

    if (mysqli_query($conn, $sql)) {
        if ($isModal) {
            header('Content-Type: application/json');
            echo json_encode([
            'status' => 'success',
            'message' => 'Data pengeluaran berhasil disimpan!'
    ]);
        } else {
            echo "<script>alert('Data pengeluaran berhasil disimpan!'); window.location='pembukuan.php';</script>";
        }
    } else {
        $err = mysqli_error($conn);
        error_log("MySQL error: $err");
        if ($isModal) {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => $err]);
        } else {
            echo "Error: " . $err;
        }
    }
    exit;
}
?>

<?php if (!$isModal): ?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Tambah Pengeluaran</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<?php endif; ?>

<div class="form-container">
  <h2>Data Pengeluaran</h2>
  <form id="formPengeluaran" method="POST" action="tambah_pengeluaran.php">
    <div class="input-group">
      <input type="date" name="tanggal_pengeluaran" id="tanggal_pengeluaran" required>
      <label for="tanggal_pengeluaran">Tanggal pengeluaran</label>
    </div>

    <div class="input-group">
      <select name="id_kategori" id="id_kategori" required>
        <option value="" disabled selected hidden>Pilih kategori</option>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
          <option value="<?= $row['id_kategori'] ?>"><?= htmlspecialchars($row['nama_kategori']) ?></option>
        <?php endwhile; ?>
      </select>
      <label for="id_kategori">Kategori pengeluaran</label>
    </div>

    <div class="input-group">
      <input type="number" name="jumlah" id="jumlah" required>
      <label for="jumlah">Jumlah pengeluaran</label>
    </div>

    <div class="input-group">
      <textarea name="keterangan" id="keterangan"></textarea>
      <label for="keterangan">Deskripsi pengeluaran</label>
    </div>

    <button type="submit" class="btn green">Simpan</button>
  </form>
</div>

<?php if (!$isModal): ?>
<script src="../assets/js/script.js"></script>
</body>
</html>
<?php endif; ?>

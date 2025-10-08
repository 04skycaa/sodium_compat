<?php
include __DIR__ . '/../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tanggal = $_POST['tanggal_kuota'];
    $kuota = $_POST['kuota_maksimal'];

    $sql = "INSERT INTO kuota_harian (tanggal_kuota, kuota_maksimal, kuota_terpesan)
            VALUES ('$tanggal', '$kuota', 0)";

    if ($conn->query($sql)) {
        echo json_encode(['status' => 'success', 'message' => 'Kuota berhasil ditambahkan!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menambahkan kuota.']);
    }
    exit;
}
?>

<div class="modal-content" style="animation: popIn 0.4s ease;">
  <span class="close-btn" id="closeModal">&times;</span>
  <h3 style="text-align:center; color:#35542E; margin-bottom:15px;">Tambah Kuota Pendakian</h3>

  <form id="formTambahKuota">
    <div class="input-group">
      <input type="date" name="tanggal_kuota" id="tanggal_kuota" placeholder=" " required>
      <label for="tanggal_kuota">Tanggal Kuota</label>
    </div>

    <div class="input-group">
      <input type="number" name="kuota_maksimal" id="kuota_maksimal" placeholder=" " required>
      <label for="kuota_maksimal">Kuota Maksimal</label>
    </div>

    <button type="submit" style="width:100%; margin-top:10px;">Tambah</button>
  </form>
</div>

<?php
include __DIR__ . '/../../config/database.php';
?>

<div class="modal-content animate">
  <span class="close-btn" id="closeModal">&times;</span>
  <h2 style="text-align:center; color:#b71c1c;">Konfirmasi Hapus</h2>
  <p style="text-align:center; margin:20px 0;">
    Apakah kamu yakin ingin menghapus kuota ini?<br>
    <strong>ID Kuota: <?= htmlspecialchars($id) ?></strong>
  </p>

  <form id="formHapusKuota">
    <input type="hidden" name="id_kuota" value="<?= htmlspecialchars($id) ?>">
    <div style="text-align:center;">
      <button type="submit" class="btn btn-danger">Hapus</button>
    </div>
  </form>
</div>

<script>
document.getElementById("formHapusKuota").addEventListener("submit", async (e) => {
  e.preventDefault();
  const formData = new FormData(e.target);
  const res = await fetch("hapus.php", { method: "POST", body: formData });
  const data = await res.json();

  if (data.status === "success") {
    alert(data.message);
    location.reload();
  } else {
    alert(data.message);
  }
});
</script>

<?php
include __DIR__ . '/../../config/database.php';
header('X-Content-Type-Options: nosniff'); 

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_kuota'])) {
    $id = intval($_POST['id_kuota']);
    $tanggal = $_POST['tanggal_kuota'] ?? '';
    $kuota = intval($_POST['kuota_maksimal'] ?? 0);
    $stmt = $conn->prepare("UPDATE kuota_harian SET tanggal_kuota = ?, kuota_maksimal = ? WHERE id_kuota = ?");
    $stmt->bind_param('sii', $tanggal, $kuota, $id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Kuota berhasil diperbarui!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui kuota.', 'error' => $stmt->error]);
    }
    $stmt->close();
    exit;
}

$id = 0;
if (isset($_GET['id'])) $id = intval($_GET['id']);
elseif (isset($_GET['id_kuota'])) $id = intval($_GET['id_kuota']);

$data = null;
if ($id > 0) {
    $stmt = $conn->prepare("SELECT * FROM kuota_harian WHERE id_kuota = ? LIMIT 1");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $data = $res->fetch_assoc();
    $stmt->close();
}

if (!$data) {
    echo '<div class="modal-content"><p>Data tidak ditemukan.</p><span class="close-btn" id="closeModal" style="left:12px; right:auto;">&times;</span></div>';
    exit;
}

?>
<div class="modal-content" style="animation: popIn 0.35s ease;">
  <span class="close-btn" id="closeModal" style="left:12px; right:auto;">&times;</span>

  <h3 style="text-align:center; color:#35542E; margin-bottom:12px;">Edit Kuota Pendakian</h3>

  <form id="formEditKuota">
    <input type="hidden" name="id_kuota" value="<?= htmlspecialchars($data['id_kuota']) ?>">

    <div class="input-group">
      <input type="date" name="tanggal_kuota" value="<?= htmlspecialchars($data['tanggal_kuota']) ?>" required placeholder=" ">
      <label for="tanggal_kuota">Tanggal Kuota</label>
    </div>

    <div class="input-group">
      <input type="number" name="kuota_maksimal" value="<?= htmlspecialchars($data['kuota_maksimal']) ?>" required placeholder=" ">
      <label for="kuota_maksimal">Kuota Maksimal</label>
    </div>

    <button type="submit" style="width:100%; margin-top:8px;">Simpan Perubahan</button>
  </form>
</div>

<script>
document.getElementById('formEditKuota').addEventListener('submit', async function (e) {
  e.preventDefault();
  const form = e.target;
  try {
    const res = await fetch('simaksi/admin/kuota_pendakian/edit.php', {
      method: 'POST',
      body: new FormData(form)
    });

    const json = await res.json();
    if (json.status === 'success') {
      if (window.Swal) {
        Swal.fire({ icon: 'success', title: json.message, showConfirmButton: false, timer: 1200 })
          .then(() => location.reload());
      } else {
        alert(json.message);
        location.reload();
      }
    } else {
      if (window.Swal) {
        Swal.fire({ icon: 'error', title: 'Gagal', text: json.message + (json.error ? ' (' + json.error + ')' : '') });
      } else {
        alert(json.message);
      }
    }
  } catch (err) {
    console.error(err);
    alert('Terjadi kesalahan jaringan.');
  }
});

document.getElementById('closeModal').addEventListener('click', function () {
  const overlay = document.getElementById('modalOverlay');
  if (overlay) {
    overlay.style.animation = 'fadeOut 0.25s ease';
    setTimeout(() => {
      overlay.style.display = 'none';
      document.getElementById('modalBody').innerHTML = '';
    }, 260);
  }
});
</script>

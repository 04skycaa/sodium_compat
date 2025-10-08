<?php
include __DIR__ . '/../../config/database.php';

// Hitung total kuota bulan ini
$currentMonth = date('m');
$currentYear = date('Y');
$sqlBulanIni = "SELECT SUM(kuota_maksimal) AS total_kuota FROM kuota_harian 
                WHERE MONTH(tanggal_kuota) = '$currentMonth' AND YEAR(tanggal_kuota) = '$currentYear'";
$totalKuotaBulanIni = $conn->query($sqlBulanIni)->fetch_assoc()['total_kuota'] ?? 0;

// Hitung total terdaftar
$sqlTerdaftar = "SELECT SUM(kuota_terpesan) AS total_terpesan FROM kuota_harian 
                 WHERE MONTH(tanggal_kuota) = '$currentMonth' AND YEAR(tanggal_kuota) = '$currentYear'";
$totalTerdaftar = $conn->query($sqlTerdaftar)->fetch_assoc()['total_terpesan'] ?? 0;

// Sisa
$kuotaTersisa = $totalKuotaBulanIni - $totalTerdaftar;

// Ambil data semua kuota
$sql = "SELECT * FROM kuota_harian ORDER BY tanggal_kuota DESC";
$data = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Kuota Pendakian</title>
  <link rel="stylesheet" href="/simaksi/assets/css/style.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<div class="keuangan-container">
  <div class="keuangan-header">
    <h2>Kuota Pendakian</h2>
  </div>

  <div class="status-bar">
    <div class="card green">Kuota Bulan Ini <span><?= $totalKuotaBulanIni ?></span></div>
    <div class="card red">Terdaftar <span><?= $totalTerdaftar ?></span></div>
    <div class="card blue">Tersisa <span><?= $kuotaTersisa ?></span></div>
  </div>

   <div class="filter-section">
        <input type="date" id="filterDate" placeholder="Tanggal pembuatan">
        <button class="filter-btn" id="applyFilter">Terapkan</button>
    </div>

  <div class="table-container">
    <table class="data-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Tanggal Kuota</th>
          <th>Kuota Maksimal</th>
          <th>Kuota Terpesan</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
      <?php if ($data->num_rows > 0): ?>
        <?php while ($row = $data->fetch_assoc()): ?>
          <?php $status = ($row['kuota_maksimal'] - $row['kuota_terpesan']) > 0 ? 'Tersedia' : 'Penuh'; ?>
          <tr>
            <td><?= $row['id_kuota'] ?></td>
            <td><?= date('d-m-Y', strtotime($row['tanggal_kuota'])) ?></td>
            <td><?= $row['kuota_maksimal'] ?></td>
            <td><?= $row['kuota_terpesan'] ?></td>
            <td><?= $status ?></td>
            <td>
              <button class="btn blue btn-edit" data-id="<?= $row['id_kuota'] ?>">Edit</button>
              <button class="btn red btn-hapus" data-id="<?= $row['id_kuota'] ?>">Hapus</button>
            </td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr><td colspan="6" style="text-align:center;">Belum ada data.</td></tr>
      <?php endif; ?>
      </tbody>
    </table>
  </div>

  <div class="action-bar">
    <button class="btn dark" id="tambahKuota">Tambah Kuota</button>
  </div>
</div>

<!-- Modal -->
<div class="modal-overlay" id="modalOverlay" style="display:none;">
  <div class="modal-body" id="modalBody"></div>
  <button id="closeModal">Tutup</button>
</div>

<script src="/simaksi/assets/js/kuota_pendakian.js"></script>
</body>
</html>

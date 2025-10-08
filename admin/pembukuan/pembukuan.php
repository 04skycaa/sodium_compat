<?php
include __DIR__ . '/../../config/database.php';

// Filter tanggal
$startDate = isset($_GET['start']) ? $_GET['start'] : '';
$endDate = isset($_GET['end']) ? $_GET['end'] : '';

// Query pemasukan
$sqlPemasukan = "
    SELECT 
        id_pemasukan AS id,
        NULL AS id_kategori,
        jumlah,
        keterangan,
        tanggal_pemasukan AS tanggal,
        'Pemasukan' AS jenis,
        NULL AS nama_kategori
    FROM pemasukan
";
if ($startDate && $endDate) {
    $sqlPemasukan .= " WHERE tanggal_pemasukan BETWEEN '$startDate' AND '$endDate'";
}

// Query pengeluaran (dengan join kategori)
$sqlPengeluaran = "
    SELECT 
        p.id_pengeluaran AS id,
        p.id_kategori,
        p.jumlah,
        p.keterangan,
        p.tanggal_pengeluaran AS tanggal,
        'Pengeluaran' AS jenis,
        k.nama_kategori
    FROM pengeluaran p
    LEFT JOIN kategori_pengeluaran k ON p.id_kategori = k.id_kategori
";
if ($startDate && $endDate) {
    $sqlPengeluaran .= " WHERE p.tanggal_pengeluaran BETWEEN '$startDate' AND '$endDate'";
}

// Gabung data
$sql = "($sqlPemasukan) UNION ALL ($sqlPengeluaran) ORDER BY tanggal DESC";
$result = $conn->query($sql);

// Hitung total
$data = [];
$totalPemasukan = 0;
$totalPengeluaran = 0;
$totalTransaksi = 0;

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
        if ($row['jenis'] === 'Pemasukan') {
            $totalPemasukan += $row['jumlah'];
        } else {
            $totalPengeluaran += $row['jumlah'];
        }
        $totalTransaksi++;
    }
}

$keuntungan = $totalPemasukan - $totalPengeluaran;
$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembukuan</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="keuangan-container">
    <div class="keuangan-header">
        <h2>Keuangan</h2>
        <div class="right-controls">
            <div class="search-box">
                <input type="text" id="searchInput" placeholder="Cari transaksi...">
            </div>
            <td>
        <a href="hapus_pengeluaran.php?id=<?= $row['id_pengeluaran'] ?>" class="btn red" onclick="return confirm('Yakin hapus data ini?')">Hapus</a>
</td>

        </div>
    </div>

    <div class="status-bar">
        <div class="card green">
            Keuntungan
            <span>Rp <?= number_format($keuntungan, 0, ',', '.') ?></span>
        </div>
        <div class="card red">
            Pengeluaran
            <span>Rp <?= number_format($totalPengeluaran, 0, ',', '.') ?></span>
        </div>
        <div class="card blue">
            Pemasukan
            <span>Rp <?= number_format($totalPemasukan, 0, ',', '.') ?></span>
        </div>
        <div class="card dark">
            Jumlah Transaksi
            <span><?= $totalTransaksi ?></span>
        </div>
    </div>

    <!-- Filter tanggal -->
    <div class="filter-section">
        <form method="GET">
            <input type="date" name="start" value="<?= $startDate ?>">
            <input type="date" name="end" value="<?= $endDate ?>">
            <button type="submit" class="filter-btn">Terapkan</button>
        </form>
    </div>

    <!-- Tabel data -->
    <table class="data-table">
        <thead>
            <tr>
                <th>Pilih</th>
                <th>Tanggal</th>
                <th>Jenis</th>
                <th>Kategori</th>
                <th>Keterangan</th>
                <th>Jumlah</th>
                <th>aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($data)): ?>
                <?php foreach ($data as $row): ?>
                    <tr data-id="<?= $row['id'] ?>" data-type="<?= $row['jenis'] ?>">
                        <td><input type="checkbox" class="select-row" <?= $row['jenis'] == 'Pemasukan' ? 'disabled' : '' ?>></td>
                        <td><?= htmlspecialchars($row['tanggal']) ?></td>
                        <td><?= htmlspecialchars($row['jenis']) ?></td>
                        <td><?= htmlspecialchars($row['nama_kategori'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($row['keterangan']) ?></td>
                        <td>Rp <?= number_format($row['jumlah'], 0, ',', '.') ?></td>
                        <td><button class="edit-btn btn blue" data-id="<?= $row['id'] ?>">Edit</button></td>

                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="6" style="text-align:center;">Tidak ada data</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Modal -->
    <div id="modalOverlay" class="modal-overlay" style="display:none;">
        <div class="modal-content">
            <span id="closeModal" class="close-btn">&times;</span>
            <div id="modalBody">Memuat...</div>
        </div>
    </div>

    <!-- Tombol bawah -->
    <div class="action-bar">
        <button class="btn green" id="addBtn">Tambah Pengeluaran</button>
        <button class="btn dark" id="excelBtn">Unduh Excel</button>
    </div>
</div>

<script src="../assets/js/script.js"></script>
</body>
</html>

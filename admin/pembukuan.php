<?php
include '../config/database.php';

// Proses penambahan transaksi baru
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jenis_transaksi = $_POST['jenis_transaksi'];
    $deskripsi = $_POST['deskripsi'];
    $jumlah = $_POST['jumlah'];
    $tanggal = date('Y-m-d H:i:s');

    if ($jenis_transaksi === 'pemasukan') {
        $sql = "INSERT INTO pemasukan (keterangan, jumlah, tanggal_pemasukan) VALUES (?, ?, ?)";
    } elseif ($jenis_transaksi === 'pengeluaran') {
        $sql = "INSERT INTO pengeluaran (keterangan, jumlah, tanggal_pengeluaran) VALUES (?, ?, ?)";
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sds", $deskripsi, $jumlah, $tanggal);

    if ($stmt->execute()) {
        $success_message = "Transaksi " . $jenis_transaksi . " berhasil ditambahkan!";
    } else {
        $error_message = "Error: " . $stmt->error;
    }
    $stmt->close();
}

// ini buat ambil data dari tabel pemasukan
$pemasukan = [];
$sql_pemasukan = "SELECT 'pemasukan' as jenis, keterangan as deskripsi, jumlah as jumlah, tanggal_pemasukan as tanggal FROM pemasukan";
$result_pemasukan = $conn->query($sql_pemasukan);
if ($result_pemasukan && $result_pemasukan->num_rows > 0) {
    while ($row = $result_pemasukan->fetch_assoc()) {
        $pemasukan[] = $row;
    }
}

// ini buat ambil data dari tabel pengeluaran
$pengeluaran = [];
$sql_pengeluaran = "SELECT 'pengeluaran' as jenis, keterangan as deskripsi, jumlah as jumlah, tanggal_pengeluaran as tanggal FROM pengeluaran";
$result_pengeluaran = $conn->query($sql_pengeluaran);
if ($result_pengeluaran && $result_pengeluaran->num_rows > 0) {
    while ($row = $result_pengeluaran->fetch_assoc()) {
        $pengeluaran[] = $row;
    }
}
//ini gabungin data dari dua tabel terus diurutkan berdasarkan tanggal terbaru
$transaksi_gabungan = array_merge($pemasukan, $pengeluaran);
usort($transaksi_gabungan, function($a, $b) {
    return strtotime($b['tanggal']) - strtotime($a['tanggal']);
});

$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembukuan - Simaksi Admin</title>
    <link rel="stylesheet" href="../assets/css/pembukuan.css">
</head>
<body>
        <main class="main-content">
            <header>
                <h1>Pembukuan</h1>
            </header>

            <?php if (isset($success_message)): ?>
                <div class="alert success"><?php echo htmlspecialchars($success_message); ?></div>
            <?php endif; ?>

            <?php if (isset($error_message)): ?>
                <div class="alert error"><?php echo htmlspecialchars($error_message); ?></div>
            <?php endif; ?>

            <section class="form-section">
                <h2>Tambah Transaksi Baru</h2>
                <form action="pembukuan.php" method="POST">
                    <div class="form-group">
                        <label for="jenis_transaksi">Jenis Transaksi:</label>
                        <select id="jenis_transaksi" name="jenis_transaksi" required>
                            <option value="pemasukan">Pemasukan</option>
                            <option value="pengeluaran">Pengeluaran</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="deskripsi">Deskripsi:</label>
                        <input type="text" id="deskripsi" name="deskripsi" required>
                    </div>
                    <div class="form-group">
                        <label for="jumlah">Jumlah (Rp):</label>
                        <input type="number" id="jumlah" name="jumlah" step="0.01" min="0" required>
                    </div>
                    <button type="submit" class="btn">Simpan Transaksi</button>
                </form>
            </section>

            <section class="table-section">
                <h2>Riwayat Transaksi</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Jenis</th>
                            <th>Deskripsi</th>
                            <th>Jumlah</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($transaksi_gabungan)): ?>
                            <?php foreach ($transaksi_gabungan as $item): ?>
                                <tr class="<?php echo ($item['jenis'] == 'pemasukan') ? 'pemasukan-row' : 'pengeluaran-row'; ?>">
                                    <td><?php echo ucfirst(htmlspecialchars($item['jenis'])); ?></td>
                                    <td><?php echo htmlspecialchars($item['deskripsi']); ?></td>
                                    <td>Rp <?php echo number_format($item['jumlah'], 2, ',', '.'); ?></td>
                                    <td><?php echo htmlspecialchars($item['tanggal']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4">Belum ada transaksi.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </section>
        </main>
</body>
</html>
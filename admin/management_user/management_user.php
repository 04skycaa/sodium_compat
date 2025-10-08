<?php
include __DIR__ . '/../../config/database.php';

$filterDate = isset($_GET['date']) ? $_GET['date'] : '';
$filterName = isset($_GET['name']) ? $_GET['name'] : '';
$filterRole = isset($_GET['role']) ? $_GET['role'] : '';

$sql = "SELECT id_pengguna, nama_lengkap, email, nomor_telepon, alamat, peran, dibuat_pada FROM pengguna";
$conditions = [];

if (!empty($filterDate)) {
    $conditions[] = "DATE(dibuat_pada) = '" . $conn->real_escape_string($filterDate) . "'";
}
if (!empty($filterName)) {
    $filterNameEscaped = $conn->real_escape_string($filterName);
    $conditions[] = "(nama_lengkap LIKE '%$filterNameEscaped%' OR id_pengguna LIKE '%$filterNameEscaped%')";
}
if (!empty($filterRole)) {
    $conditions[] = "peran = '" . $conn->real_escape_string($filterRole) . "'";
}

if (count($conditions) > 0) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Management User</title>
    <link rel="stylesheet" href="/simaksi/assets/css/style.css">
</head>
<body>
<div class="keuangan-container">
    <div class="keuangan-header">
        <h2>Management User</h2>
        <div class="right-controls">
            <div class="search-box">
                <input type="text" id="searchInput" placeholder="Cari pengguna...">
                <i class="bx bx-search"></i>
            </div>
        </div>
    </div>

    <div class="filter-section">
        <div class="input-group">
            <input 
            type="date" 
            name="date" 
            id="filterDate" 
            value="<?= htmlspecialchars($filterDate) ?>" 
            placeholder=" " 
            >
            <label for="filterDate">Tanggal</label>
        </div>

        <div class="input-group">
            <input 
            type="text" 
            name="name" 
            id="filterName" 
            value="<?= htmlspecialchars($filterName) ?>" 
            placeholder=" " 
            >
            <label for="filterName">Nama</label>
        </div>

        <div class="input-group">
            <select name="role" id="filterRole">
            <option value="">Semua Role</option>
            <option value="admin" <?= $filterRole === 'admin' ? 'selected' : '' ?>>Admin</option>
            <option value="pendaki" <?= $filterRole === 'pendaki' ? 'selected' : '' ?>>Pendaki</option>
            </select>
            <label for="filterRole">Role</label>
        </div>

        <button class="filter-btn" id="applyFilter">Terapkan</button>
        </div>


    <div class="table-wrapper">
        <table class="data-table" id="userTable">
            <thead>
                <tr>
                    <th>ID User</th>
                    <th>Nama User</th>
                    <th>Email</th>
                    <th>No. Telepon</th>
                    <th>Alamat</th>
                    <th>Role</th>
                    <th>Tanggal Pembuatan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($users)): ?>
                    <?php foreach ($users as $row): ?>
                        <tr data-id="<?= htmlspecialchars($row['id_pengguna']) ?>">
                        <td><?= htmlspecialchars($row['id_pengguna'] ?? '') ?></td>
                        <td><?= htmlspecialchars($row['nama_lengkap'] ?? '') ?></td>
                        <td><?= htmlspecialchars($row['email'] ?? '') ?></td>
                        <td><?= htmlspecialchars($row['nomor_telepon'] ?? '') ?></td>
                        <td><?= htmlspecialchars($row['alamat'] ?? '') ?></td>
                        <td><?= htmlspecialchars($row['peran'] ?? '') ?></td>
                        <td><?= htmlspecialchars($row['dibuat_pada'] ?? '') ?></td>
                        <td>
                            <button class="btn blue edit-btn" data-id="<?= $row['id_pengguna'] ?>">Edit</button>
                            <button class="btn red delete-btn" data-id="<?= $row['id_pengguna'] ?>">Hapus</button>
                        </td>
                        </tr>

                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="8" style="text-align:center;">Tidak ada data pengguna</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div id="modalOverlay" class="modal-overlay" style="display:none;">
        <div class="modal-content">
            <span id="closeModal" class="close-btn">&times;</span>
            <div id="modalBody">Memuat...</div>
        </div>
    </div>
</div>

<script src="/simaksi/assets/js/management_user.js"></script>
</body>
</html>

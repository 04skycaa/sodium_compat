<?php

include '../config/database.php';

// Ambil data pengguna dari database
$users = [];
// Asumsikan username adalah 'nama_lengkap', email adalah 'email', dan password adalah 'kata_sandi_hash'
$sql = "SELECT nama_lengkap, email, kata_sandi_hash, peran FROM pengguna";
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
    <title>Manajemen Pengguna - Dashboard Admin</title>
    <link rel="stylesheet" href="../assets/css/management_user.css">
</head>
<body>
    <main class="main-content">
        <header>
            <h1>Manajemen Pengguna</h1>
        </header>
        
        <section class="table-section">
            <h2>Daftar Pengguna</h2>
            <table class="user-table">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Password (Hash)</th>
                        <th>Role</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($users)): ?>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($user['nama_lengkap']); ?></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td><?php echo htmlspecialchars($user['kata_sandi_hash']); ?></td>
                                <td><?php echo htmlspecialchars($user['peran']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4">Tidak ada data pengguna.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>
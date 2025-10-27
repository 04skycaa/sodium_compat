<?php
include __DIR__ . '/../../config/supabase.php';
$kodeBooking = $_GET['kode_booking'] ?? null;
$namaKetua = $_GET['nama_ketua'] ?? null;
$select_query = 'id_reservasi,kode_reservasi,tanggal_pendakian,jumlah_pendaki,total_harga,status,profiles(nama_lengkap),status_sampah';
$tanggalAwalBulan = date('Y-m-01');
$tanggalAkhirBulan = date('Y-m-t'); 

$endpointBulanIni = 'reservasi?tanggal_pendakian=gte.' . $tanggalAwalBulan . '&tanggal_pendakian=lte.' . $tanggalAkhirBulan . '&select=' . $select_query . '&order=tanggal_pendakian.asc';

$reservasiBulanIni = supabase_request('GET', $endpointBulanIni);
if (!$reservasiBulanIni || isset($reservasiBulanIni['error'])) {
    $reservasiBulanIni = [];
}

$dataTabel = $reservasiBulanIni; 

if ($kodeBooking || $namaKetua) {
    $query_parts = [];
    $search_endpoint = 'reservasi?select=' . $select_query . '&order=tanggal_pendakian.desc';

    if ($kodeBooking) {
        $query_parts[] = 'kode_reservasi=ilike.*' . urlencode($kodeBooking) . '*';
    }
    
    if (!empty($query_parts)) {
        $search_endpoint = 'reservasi?' . implode('&', $query_parts) . '&select=' . $select_query . '&order=tanggal_pendakian.desc';
    }

    $dataHasilPencarian = supabase_request('GET', $search_endpoint);

    if (!$dataHasilPencarian || isset($dataHasilPencarian['error'])) {
        $dataHasilPencarian = [];
    }
    
    if ($namaKetua) {
        $namaKetuaLower = strtolower($namaKetua);
        $dataHasilPencarian = array_filter($dataHasilPencarian, function($row) use ($namaKetuaLower) {
            $namaLengkap = strtolower($row['profiles']['nama_lengkap'] ?? '');
            return str_contains($namaLengkap, $namaKetuaLower);
        });
    }
    
    $dataTabel = $dataHasilPencarian;
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Reservasi dan Validasi Data</title>
    <link rel="stylesheet" href="/simaksi/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
 
</head>
<body>

<div class="content-wrapper">

    <div class="secondary-nav">
        <a href="index.php?page=reservasi" class="active" id="nav-reservasi">Reservasi</a>
        <a href="#tambah" id="nav-tambah-reservasi">Tambah Reservasi</a>
    </div>

    <div id="main-content-area">

        <div id="reservasi-content">
            
            <div class="search-container">
                <div class="search-header"> <i class="fa-solid fa-search"></i> Cari Reservasi </div>
                <form action="" method="GET" class="search-form">
                    <input type="hidden" name="page" value="reservasi">
                    <div class="input-group">
                        <label for="kodeBookingInput"><i class="fa-solid fa-hashtag"></i> Kode Booking</label>
                        <input type="text" name="kode_booking" id="kodeBookingInput" class="search-input" placeholder="" value="<?= htmlspecialchars($kodeBooking ?? '') ?>">
                    </div>
                    <div class="input-group">
                        <label for="namaKetuaInput"><i class="fa-solid fa-user-group"></i> Nama Ketua Rombongan</label>
                        <input type="text" name="nama_ketua" id="namaKetuaInput" class="search-input" placeholder="" value="<?= htmlspecialchars($namaKetua ?? '') ?>">
                    </div>
                    <button type="submit" class="btn-cari">
                        <i class="fa-solid fa-search"></i> Cari
                    </button>
                    <?php if ($kodeBooking || $namaKetua): ?>
                        <a href="index.php?page=reservasi" class="btn red" style="align-self: flex-end; padding: 12px 15px; height: 44px;">Reset</a>
                    <?php endif; ?>
                </form>
            </div>
            
            <div class="data-section">
                <div class="data-header"> <i class="fa-solid fa-calendar-check"></i> Reservasi Bulan Ini </div>
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th><i class="fa-solid fa-hashtag"></i> Kode Booking</th>
                                <th><i class="fa-solid fa-user-group"></i> Nama Ketua</th>
                                <th><i class="fa-solid fa-calendar"></i> Tgl. Pendakian</th>
                                <th><i class="fa-solid fa-person-hiking"></i> Jumlah </th>
                                <th><i class="fa-solid fa-money-bill-wave"></i> Status </th>
                                <th><i class="fa-solid fa-trash-can-arrow-up"></i> Status Sampah</th>
                                <th><i class="fa-solid fa-gears"></i> AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if (!empty($dataTabel)): ?>
                            <?php foreach ($dataTabel as $index => $row): ?>
                                <tr style="--animation-order: <?= $index + 1 ?>;">
                                    <td><?= htmlspecialchars($row['kode_reservasi'] ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars($row['profiles']['nama_lengkap'] ?? 'N/A') ?></td>
                                    <td><?= date('d-m-Y', strtotime($row['tanggal_pendakian'] ?? '')) ?></td>
                                    <td><?= htmlspecialchars($row['jumlah_pendaki'] ?? '0') ?></td>
                                    <td>
                                        <?php $status = $row['status'] ?? null; $status_class = strtolower(str_replace([' ', '_'], '-', $status ?? 'none')); ?>
                                        <span class="status-badge status-<?= htmlspecialchars($status_class) ?>">
                                            <?= htmlspecialchars(ucwords(str_replace('_',' ',$status ?? 'N/A'))) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php $statusSampah = $row['status_sampah'] ?? 'belum_dicek'; $status_class_sampah = strtolower(str_replace([' ', '_'], '-', $statusSampah)); ?>
                                        <span class="status-badge status-<?= htmlspecialchars($status_class_sampah) ?>">
                                            <?= htmlspecialchars(ucwords(str_replace('_',' ',$statusSampah))) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn blue btn-validasi" data-id="<?= htmlspecialchars($row['id_reservasi'] ?? '') ?>"> <i class="fa-solid fa-check-to-slot"></i> Validasi </button>
                                        <button class="btn red btn-hapus" data-id="<?= htmlspecialchars($row['id_reservasi'] ?? '') ?>"> <i class="fa-solid fa-trash-can"></i> Hapus </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="7" style="text-align:center;">Menampilkan 0 dari 0 entri.</td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="pagination-info"> <span>Menampilkan <?= count($dataTabel ?? []) ?> dari <?= count($dataTabel ?? []) ?> entri</span> <div class="pagination-controls"> <button disabled><</button> <button>1</button> <button disabled>></button> </div> </div>
            </div>
        </div>
        </div>
    </div>

<div class="modal-overlay" id="modal-validasi">...</div> 

<script src="/simaksi/assets/js/reservasi.js"></script>

</body>
</html>
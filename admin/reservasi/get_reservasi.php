<?php
include __DIR__ . '/../../config/supabase.php';

if (!isset($_GET['id'])) {
    echo '<p class="error-message">Error: ID Reservasi tidak ditemukan.</p>';
    exit;
}

$id_reservasi = $_GET['id'];
$select_query = 'id_reservasi, id_pengguna, kode_reservasi, tanggal_pendakian, jumlah_pendaki, jumlah_tiket_parkir, total_harga, status, profiles(nama_lengkap,email)';
$reservasi_endpoint = 'reservasi?id_reservasi=eq.' . $id_reservasi . '&select=' . urlencode(trim($select_query)) . '&limit=1';
$reservasi_data = supabase_request('GET', $reservasi_endpoint);

// Query barang bawaan
$barang_select = 'id_barang,nama_barang,jumlah,jenis_sampah';
$barang_endpoint = 'barang_bawaan_sampah?id_reservasi=eq.' . $id_reservasi . '&select=' . urlencode(trim($barang_select));
$barang_data = supabase_request('GET', $barang_endpoint);

if (!$reservasi_data || empty($reservasi_data)) {
    echo '<p class="error-message">Error: Tidak ada data reservasi ditemukan untuk ID: ' . htmlspecialchars($id_reservasi) . '.</p>';
    exit;
}
if (isset($reservasi_data['error']) || (isset($reservasi_data[0]) && isset($reservasi_data[0]['error']))) {
     echo '<p class="error-message">Error saat mengambil data reservasi (ID: ' . htmlspecialchars($id_reservasi) . ').</p>';
     echo '<p class="error-message">Detail Error Supabase: <pre>' . htmlspecialchars(print_r($reservasi_data, true)) . '</pre></p>';
     exit;
}

$reservasi = $reservasi_data[0];
$current_status = $reservasi['status'] ?? null;
?>

<div class="detail-reservasi-modal">

    <div class="detail-section info-section">
    <h4 class="section-title">
        <i class="fa-solid fa-user-check icon-title"></i> Informasi Pemesan
    </h4>
    <div class="form-display-container-float"> <div class="input-group readonly">
            <span class="form-value-display code" id="display_kode"><?= htmlspecialchars($reservasi['kode_reservasi'] ?? 'N/A') ?></span>
            <label for="display_kode"><i class="fa-solid fa-barcode"></i> Kode Reservasi</label>
        </div>

        <div class="input-group readonly">
            <span class="form-value-display" id="display_nama"><?= htmlspecialchars($reservasi['profiles']['nama_lengkap'] ?? 'N/A') ?></span>
            <label for="display_nama"><i class="fa-solid fa-user"></i> Nama Ketua</label>
        </div>

        <div class="input-group readonly">
            <span class="form-value-display" id="display_email"><?= htmlspecialchars($reservasi['profiles']['email'] ?? 'N/A') ?></span>
            <label for="display_email"><i class="fa-solid fa-envelope"></i> Email</label>
        </div>

        <div class="input-group readonly">
            <span class="form-value-display" id="display_tgl"><?= date('d M Y', strtotime($reservasi['tanggal_pendakian'] ?? '')) ?></span>
            <label for="display_tgl"><i class="fa-solid fa-calendar-day"></i> Tgl. Naik</label>
        </div>

        <div class="input-group readonly">
            <span class="form-value-display" id="display_jml_pendaki"><?= htmlspecialchars($reservasi['jumlah_pendaki'] ?? '0') ?> orang</span>
            <label for="display_jml_pendaki"><i class="fa-solid fa-users"></i> Jumlah Pendaki</label>
        </div>

        <div class="input-group readonly">
            <span class="form-value-display" id="display_parkir"><?= htmlspecialchars($reservasi['jumlah_tiket_parkir'] ?? '0') ?></span>
            <label for="display_parkir"><i class="fa-solid fa-motorcycle"></i> Tiket Parkir</label>
        </div>

        <div class="input-group readonly full-width">
            <span class="form-value-display total-bayar" id="display_total">Rp <?= number_format($reservasi['total_harga'] ?? 0, 0, ',', '.') ?></span>
            <label for="display_total"><i class="fa-solid fa-money-bill-wave"></i> Total Bayar</label>
        </div>

        <div class="input-group readonly full-width">
            <span class="form-value-display" id="display_status">
                 <span class="status-badge status-<?= strtolower(str_replace(' ', '-', $current_status ?? '')) ?>">
                     <?= htmlspecialchars(ucwords(str_replace('_', ' ', $current_status ?? 'N/A'))) ?>
                 </span>
            </span>
             <label for="display_status"><i class="fa-solid fa-flag"></i> Status</label>
         </div>

    </div> </div>
    <div class="detail-section barang-section">
        <h4 class="section-title">
             <i class="fa-solid fa-box-archive icon-title"></i> Barang Bawaan & Potensi Sampah
        </h4>

        <div id="barang-list-container">
            <?php if ($barang_data && !empty($barang_data) && !isset($barang_data['error'])): ?>
                <table class="detail-table barang-table">
                    <thead>
                        <tr>
                            <th>Nama Barang</th>
                            <th>Jumlah</th>
                            <th>Jenis Sampah</th>
                            <th class="aksi-col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($barang_data as $barang): ?>
                            <tr>
                                <td><?= htmlspecialchars($barang['nama_barang'] ?? 'N/A') ?></td>
                                <td class="jml-col"><?= htmlspecialchars($barang['jumlah'] ?? '0') ?></td>
                                <td><?= htmlspecialchars(ucfirst($barang['jenis_sampah'] ?? 'N/A')) ?></td>
                                <td class="aksi-col">
                                    <button class="btn-icon btn-delete-item" data-item-id="<?= htmlspecialchars($barang['id_barang'] ?? '') ?>" title="Hapus Barang">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p id="no-barang-message" class="empty-message">Belum ada data barang bawaan.</p>
                <?php if(isset($barang_data['error'])) { echo '<p class="error-message">Gagal memuat barang: ' . htmlspecialchars(print_r($barang_data, true)). '</p>'; } ?>
            <?php endif; ?>
        </div>

        <button class="btn green btn-xs" id="btn-show-add-item-form">
            <i class="fa-solid fa-plus"></i> Tambah Barang
        </button>

        <form id="form-tambah-item" class="form-inline-section" style="display: none;">
            <h5><i class="fa-solid fa-pencil"></i> Tambah Barang Baru</h5>
            <div class="form-group-inline">
                <input type="text" id="add-nama-barang" placeholder="Nama Barang" required>
                <input type="number" id="add-jumlah-barang" placeholder="Jumlah" min="1" value="1" required>

                <select id="add-jenis-sampah" required>
                    <option value="">Jenis Sampah</option>
                    <option value="organik">Organik</option>
                    <option value="non-organik">Non-Organik</option> </select>
                <button type="submit" class="btn blue btn-xs" id="btn-save-item">
                    <i class="fa-solid fa-save"></i> Simpan
                </button>
                <button type="button" class="btn gray btn-xs" id="btn-cancel-add-item">
                    Batal
                </button>
            </div>
        </form>
    </div>

    <div class="detail-section modal-actions">
         <h4 class="section-title">
             <i class="fa-solid fa-cogs icon-title"></i> Aksi Reservasi
         </h4>
        <?php if ($current_status == 'menunggu_pembayaran'): ?>
             <button class="btn green btn-setujui" data-id="<?= $id_reservasi ?>" data-next-status="terkonfirmasi">
                <i class="fa-solid fa-check"></i> Tandai Sudah Bayar (Konfirmasi)
            </button>
             <button class="btn red btn-batal" data-id="<?= $id_reservasi ?>" data-next-status="dibatalkan">
                <i class="fa-solid fa-times"></i> Batalkan Reservasi
            </button>
            <p class="action-info">Status akan berubah menjadi "Terkonfirmasi" atau "Dibatalkan".</p>

        <?php elseif ($current_status == 'terkonfirmasi'): ?>
            <button class="btn blue btn-selesai" data-id="<?= $id_reservasi ?>" data-next-status="selesai">
                <i class="fa-solid fa-flag-checkered"></i> Tandai Selesai Pendakian
            </button>
             <button class="btn red btn-batal" data-id="<?= $id_reservasi ?>" data-next-status="dibatalkan">
                <i class="fa-solid fa-times"></i> Batalkan Reservasi
            </button>
            <p class="action-info">Status akan berubah menjadi "Selesai" atau "Dibatalkan".</p>

        <?php elseif ($current_status == 'selesai'): ?>
            <p class="action-info">Pendakian sudah selesai. Tidak ada aksi lebih lanjut.</p>
             <button class="btn gray btn-reopen" data-id="<?= $id_reservasi ?>" data-next-status="terkonfirmasi">
                <i class="fa-solid fa-undo"></i> Buka Kembali (Set ke Terkonfirmasi)
            </button>

        <?php elseif ($current_status == 'dibatalkan'): ?>
            <p class="action-info">Reservasi ini sudah dibatalkan.</p>
             <button class="btn gray btn-reopen" data-id="<?= $id_reservasi ?>" data-next-status="menunggu_pembayaran">
                <i class="fa-solid fa-undo"></i> Buka Kembali (Set ke Menunggu Pembayaran)
            </button>

        <?php else: // Status tidak dikenali atau null ?>
            <p class="action-info">Tidak ada aksi tersedia untuk status "<?= htmlspecialchars(ucwords(str_replace('_', ' ', $current_status ?? 'N/A'))) ?>".</p>
        <?php endif; ?>
    </div>
</div>
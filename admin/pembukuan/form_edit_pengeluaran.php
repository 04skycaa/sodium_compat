<?php
// PERHATIAN: Asumsikan file ini dipanggil dari /admin/index.php, 
// sehingga path relatif ke config/supabase.php adalah ../../config/supabase.php
include __DIR__ . '/../../config/supabase.php';

// Ambil ID dari query string
$id_pengeluaran = $_GET['id'] ?? null;

if (!$id_pengeluaran) {
    http_response_code(400);
    echo '<div class="alert error-alert">ID Pengeluaran tidak ditemukan.</div>';
    exit;
}

// 1. Ambil data pengeluaran berdasarkan ID dengan relasi kategori
// Gunakan endpoint tunggal untuk efisiensi
$data_pengeluaran = supabase_request('GET', "pengeluaran?id_pengeluaran=eq.$id_pengeluaran&select=*,kategori_pengeluaran(id_kategori,nama_kategori)");

// Periksa apakah data berhasil diambil
if (isset($data_pengeluaran['error']) || empty($data_pengeluaran)) {
    http_response_code(404);
    echo '<div class="alert error-alert">Data pengeluaran dengan ID tersebut tidak ditemukan.</div>';
    exit;
}

$pengeluaran = $data_pengeluaran[0];
$tanggal_edit = date('Y-m-d', strtotime($pengeluaran['tanggal_pengeluaran']));
$keterangan_edit = htmlspecialchars($pengeluaran['keterangan']);
$jumlah_edit = $pengeluaran['jumlah'];
$id_kategori_saat_ini = $pengeluaran['id_kategori'];

// 2. Ambil semua kategori untuk dropdown
$kategori_data = supabase_request('GET', 'kategori_pengeluaran?select=id_kategori,nama_kategori');
$kategori_options = $kategori_data && !isset($kategori_data['error']) ? $kategori_data : [];

?>

<form id="editPengeluaranForm" action="pembukuan/proses_pembukuan.php" method="POST">
    <!-- ID yang akan diedit -->
    <input type="hidden" name="form_action" value="edit">
    <input type="hidden" name="id_pengeluaran" value="<?= htmlspecialchars($id_pengeluaran) ?>">

    <div class="form-grid">
        <div class="form-group-modern">
            <label for="edit_jumlah" class="form-label-modern">
                <i class="fa-solid fa-dollar-sign"></i> Jumlah Pengeluaran (Rp)
            </label>
            <input 
                type="number" 
                id="edit_jumlah" 
                name="jumlah" 
                class="input-modern" 
                placeholder="Masukkan jumlah pengeluaran" 
                value="<?= htmlspecialchars($jumlah_edit) ?>" 
                required
            >
        </div>

        <!-- Tanggal -->
        <div class="form-group-modern">
            <label for="edit_tanggal_pengeluaran" class="form-label-modern">
                <i class="fa-solid fa-calendar-alt"></i> Tanggal
            </label>
            <input 
                type="date" 
                id="edit_tanggal_pengeluaran" 
                name="tanggal_pengeluaran" 
                class="input-modern" 
                value="<?= htmlspecialchars($tanggal_edit) ?>" 
                required
            >
        </div>

        <!-- Kategori -->
        <div class="form-group-modern grid-col-span-2">
            <label for="edit_kategori" class="form-label-modern">
                <i class="fa-solid fa-tags"></i> Kategori
            </label>
            <select id="edit_kategori" name="id_kategori" class="input-modern" required>
                <option value="">Pilih Kategori</option>
                <?php foreach ($kategori_options as $kat): ?>
                    <option 
                        value="<?= htmlspecialchars($kat['id_kategori']) ?>"
                        <?= $id_kategori_saat_ini == $kat['id_kategori'] ? 'selected' : '' ?>
                    >
                        <?= htmlspecialchars($kat['nama_kategori']) ?>
                    </option>
                <?php endforeach; ?>
                <?php if (empty($kategori_options)): ?>
                    <option value="" disabled>Gagal memuat kategori</option>
                <?php endif; ?>
            </select>
        </div>

        <!-- Keterangan -->
        <div class="form-group-modern grid-col-span-2">
            <label for="edit_keterangan" class="form-label-modern">
                <i class="fa-solid fa-info-circle"></i> Keterangan
            </label>
            <textarea 
                id="edit_keterangan" 
                name="keterangan" 
                class="input-modern" 
                placeholder="Masukkan keterangan pengeluaran" 
                rows="4" 
                required
            ><?= $keterangan_edit ?></textarea>
        </div>

    </div>
    
    <button type="submit" class="btn-blue-submit" style="margin-top: 20px;">
        <i class="fa-solid fa-save"></i> Perbarui Pengeluaran
    </button>
</form>
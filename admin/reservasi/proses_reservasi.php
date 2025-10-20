<?php
header('Content-Type: application/json');
include __DIR__ . '/../../config/supabase.php';

$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['action'])) {
    echo json_encode(['success' => false, 'message' => 'Aksi tidak valid.']);
    exit;
}

$action = $input['action'];
$id_reservasi = $input['id'] ?? null; 
$item_id = $input['item_id'] ?? null; 

$response = ['success' => false, 'message' => 'Terjadi kesalahan.'];

switch ($action) {
    case 'update_status':
        if ($id_reservasi && isset($input['status'])) {
            $new_status = $input['status'];
            $allowed_statuses = ['menunggu_pembayaran', 'terkonfirmasi', 'dibatalkan', 'selesai'];
            if (!in_array($new_status, $allowed_statuses)) {
                 $response['message'] = 'Status reservasi baru tidak valid.';
                 break;
            }

            $endpoint = 'reservasi?id_reservasi=eq.' . $id_reservasi;
            $data_update = ['status' => $new_status]; 
            $result = supabase_request('PATCH', $endpoint, json_encode($data_update));
            if (is_array($result) && empty($result)) {
                $response = ['success' => true, 'message' => 'Status reservasi berhasil diperbarui.'];
            } else {
                $response['message'] = 'Gagal memperbarui status reservasi: ' . ($result['message'] ?? print_r($result, true));
            }
        } else {
            $response['message'] = 'ID reservasi atau status tidak lengkap.';
        }
        break;

    case 'delete':
        if ($id_reservasi) {
            $endpoint_barang = 'barang_bawaan_sampah?id_reservasi=eq.' . $id_reservasi;
            supabase_request('DELETE', $endpoint_barang);
            $endpoint_reservasi = 'reservasi?id_reservasi=eq.' . $id_reservasi;
            $result = supabase_request('DELETE', $endpoint_reservasi);
            if (is_array($result) && empty($result)) {
                $response = ['success' => true, 'message' => 'Reservasi berhasil dihapus.'];
            } else {
                $response['message'] = 'Gagal menghapus reservasi: ' . ($result['message'] ?? print_r($result, true));
            }
        } else {
            $response['message'] = 'ID reservasi tidak ditemukan.';
        }
        break;

    case 'create':
         $id_pengguna = $input['id_pengguna'] ?? null;
         $tgl_pendakian = $input['tanggal_pendakian'] ?? null;
         $jml_pendaki = $input['jumlah_pendaki'] ?? 0;
         $jml_parkir = $input['jumlah_tiket_parkir'] ?? 0;
         if (!$id_pengguna || !$tgl_pendakian || $jml_pendaki <= 0) {
             $response['message'] = 'Data form tambah reservasi tidak lengkap.'; break;
         }
         $harga_tiket_masuk = 20000; $harga_tiket_parkir = 5000;
         $total_harga = ($jml_pendaki * $harga_tiket_masuk) + ($jml_parkir * $harga_tiket_parkir);
         $data_baru = [
             'kode_reservasi' => 'R' . date('Ymd') . strtoupper(bin2hex(random_bytes(3))),
             'id_pengguna' => $id_pengguna, 'tanggal_pendakian' => $tgl_pendakian,
             'jumlah_pendaki' => $jml_pendaki, 'jumlah_tiket_parkir' => $jml_parkir,
             'total_harga' => $total_harga,
             'status' => 'menunggu_pembayaran', 
             'status_sampah' => 'belum_dicek' 
         ];
         $result = supabase_request('POST', 'reservasi', json_encode($data_baru));
         if (!isset($result['error'])) {
              $response = ['success' => true, 'message' => 'Reservasi baru berhasil ditambahkan.'];
         } else {
              $response['message'] = 'Gagal menambah reservasi: ' . ($result['message'] ?? print_r($result, true));
         }
        break;

    // untuk menambah barang bawaan sampah ke reservasi
    case 'add_barang':
        $nama_barang = $input['nama_barang'] ?? null;
        $jumlah = $input['jumlah'] ?? null;
        $jenis_sampah = $input['jenis_sampah'] ?? null;

        if ($id_reservasi && $nama_barang && $jumlah && $jenis_sampah) {
            $allowed_jenis = ['organik', 'non-organik']; 
             if (!in_array($jenis_sampah, $allowed_jenis)) {
                 $response['message'] = 'Jenis sampah tidak valid.';
                 break;
            }
            $data_barang = [
                'id_reservasi' => $id_reservasi,
                'nama_barang' => $nama_barang,
                'jumlah' => $jumlah,
                'jenis_sampah' => $jenis_sampah
            ];
            $endpoint_add = 'barang_bawaan_sampah';
            error_log("DEBUG - Data Barang Dikirim: " . print_r($data_barang, true));
            $result = supabase_request('POST', $endpoint_add, json_encode($data_barang));
            error_log("DEBUG - Hasil Supabase Add Barang: " . print_r($result, true));

            if (!isset($result['error'])) {
                 $response = ['success' => true, 'message' => 'Barang berhasil ditambahkan.'];
            } else {
                 $response['message'] = 'Gagal menambah barang: ' . ($result['message'] ?? print_r($result, true));
            }
        } else {
            $response['message'] = 'Data barang tidak lengkap.';
        }
        break;
    // untuk hapus barang bawaan sampah berdasarkan item_id
    case 'delete_barang':
        if ($item_id) { 
            $endpoint_delete = 'barang_bawaan_sampah?id_barang=eq.' . $item_id; // Gunakan id_barang
            $result = supabase_request('DELETE', $endpoint_delete);
            if (is_array($result) && empty($result)) {
                $response = ['success' => true, 'message' => 'Barang berhasil dihapus.'];
            } else {
                $response['message'] = 'Gagal menghapus barang: ' . ($result['message'] ?? print_r($result, true));
            }
        } else { $response['message'] = 'ID barang tidak ditemukan.'; }
        break;

    // untuk memperbarui status sampah reservasi
    case 'update_status_sampah':
        if ($id_reservasi && isset($input['status_sampah'])) {
            $new_status_sampah = $input['status_sampah'];
            $allowed_sampah_statuses = ['belum_dicek', 'sesuai', 'tidak_sesuai'];
            if (!in_array($new_status_sampah, $allowed_sampah_statuses)) {
                 $response['message'] = 'Status sampah baru tidak valid.';
                 break;
            }

            $endpoint = 'reservasi?id_reservasi=eq.' . $id_reservasi;
            $data_update = ['status_sampah' => $new_status_sampah]; 
            $result = supabase_request('PATCH', $endpoint, json_encode($data_update));

            if (is_array($result) && empty($result)) {
                $response = ['success' => true, 'message' => 'Status sampah berhasil diperbarui.'];
            } else {
                $response['message'] = 'Gagal memperbarui status sampah: ' . ($result['message'] ?? print_r($result, true));
            }
        } else {
            $response['message'] = 'ID reservasi atau status sampah tidak lengkap.';
        }
        break;

    default:
        $response['message'] = 'Aksi tidak dikenali.';
        break;
}

echo json_encode($response);
?>
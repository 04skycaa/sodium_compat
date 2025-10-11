<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require __DIR__ . '/../../config/supabase.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Metode tidak diizinkan.']);
    exit;
}

$action = $_POST['action'] ?? null;

switch ($action) {
    case 'tambah':
        tambahKuota();
        break;
    case 'edit':
        editKuota();
        break;
    case 'hapus':
        hapusKuota();
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Aksi tidak valid.']);
        break;
}

function tambahKuota() {
    $tanggal = $_POST['tanggal_kuota'] ?? '';
    $maksimal = $_POST['kuota_maksimal'] ?? 0;

    if (empty($tanggal) || empty($maksimal)) {
        echo json_encode(['success' => false, 'message' => 'Semua field wajib diisi.']);
        return;
    }
    
    $check = supabase_request('GET', 'kuota_harian?tanggal_kuota=eq.' . $tanggal);
    if (!empty($check)) {
        echo json_encode(['success' => false, 'message' => 'Kuota untuk tanggal ' . date('d-m-Y', strtotime($tanggal)) . ' sudah ada.']);
        return;
    }

    $data = [
        'tanggal_kuota' => $tanggal,
        'kuota_maksimal' => (int)$maksimal,
        'kuota_terpesan' => 0 
    ];

    $result = supabase_request('POST', 'kuota_harian', $data);

    if (isset($result['error']) || !$result) {
        echo json_encode(['success' => false, 'message' => 'Gagal menambahkan kuota ke database.']);
    } else {
        echo json_encode(['success' => true, 'message' => 'Kuota berhasil ditambahkan.']);
    }
}

function editKuota() {
    $id = $_POST['id_kuota'] ?? '';
    $tanggal = $_POST['tanggal_kuota'] ?? '';
    $maksimal = $_POST['kuota_maksimal'] ?? 0;

    if (empty($id) || empty($tanggal) || empty($maksimal)) {
        echo json_encode(['success' => false, 'message' => 'Data tidak lengkap.']);
        return;
    }

    $data = [
        'tanggal_kuota' => $tanggal,
        'kuota_maksimal' => (int)$maksimal
    ];

    $result = supabase_request('PATCH', 'kuota_harian?id_kuota=eq.' . $id, $data);

    if (!$result || isset($result['error'])) {
        $errorMessage = 'Gagal mengupdate kuota di database.';
        if (isset($result['error']['message'])) {
            $errorMessage = $result['error']['message'];
        }
        echo json_encode(['success' => false, 'message' => $errorMessage]);
    } else {
        echo json_encode(['success' => true, 'message' => 'Kuota berhasil diperbarui.']);
    }
}

function hapusKuota() {
    $id = $_POST['id_kuota'] ?? '';

    if (empty($id)) {
        echo json_encode(['success' => false, 'message' => 'ID Kuota tidak ditemukan.']);
        return;
    }
    
    $result = supabase_request('DELETE', 'kuota_harian?id_kuota=eq.' . $id);

    if (isset($result['error'])) {
        $errorMessage = isset($result['error']['message']) ? $result['error']['message'] : 'Gagal menghapus kuota.';
        echo json_encode(['success' => false, 'message' => $errorMessage]);
    } else {
        echo json_encode(['success' => true, 'message' => 'Kuota berhasil dihapus.']);
    }
}
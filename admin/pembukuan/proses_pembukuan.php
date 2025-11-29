<?php
session_start(); 
include __DIR__ . '/../../config/supabase.php';
 
$form_action = $_POST['form_action'] ?? '';
$jumlah = $_POST['jumlah'] ?? 0;
$tanggal = $_POST['tanggal_pengeluaran'] ?? '';
$keterangan = $_POST['keterangan'] ?? '';
$id_kategori = $_POST['id_kategori'] ?? null;
$id_pengeluaran = $_POST['id_pengeluaran'] ?? null;  
$is_ajax_request = ($form_action == 'edit');
 
function sendJsonResponse($status, $message, $httpCode = 200) { 
    if ($httpCode !== 200) {
        http_response_code($httpCode);
    }
    header('Content-Type: application/json');
    echo json_encode(['status' => $status, 'message' => $message]);
    exit;
}

if (empty($jumlah) || empty($tanggal) || empty($keterangan) || empty($id_kategori)) {
    $error_msg = 'Semua field wajib diisi.'; 
    sendJsonResponse('error', $error_msg, 400);
} 

if (!isset($_SESSION['user_id'])) {
    $error_msg = 'Sesi Anda tidak valid. Silakan login kembali.';
    sendJsonResponse('error', $error_msg, 401);
}
 
$data_to_send = [
    'jumlah' => (int)$jumlah,
    'tanggal_pengeluaran' => $tanggal,
    'keterangan' => $keterangan,
    'id_kategori' => (int)$id_kategori,
    'id_admin' => $_SESSION['user_id']  
];


$result = null;
$success_msg = '';
$error_msg_default = 'Gagal memproses data';

if ($form_action == 'tambah') { 
    $result = supabase_request('POST', 'pengeluaran', $data_to_send);
    $success_msg = 'Pengeluaran baru berhasil dicatat';
    $error_msg_default = 'Gagal mencatat pengeluaran';

} elseif ($form_action == 'edit' && !empty($id_pengeluaran)) { 
    $result = supabase_request('PATCH', 'pengeluaran?id_pengeluaran=eq.' . (int)$id_pengeluaran, $data_to_send);
    $success_msg = 'Data pengeluaran berhasil diperbarui';
    $error_msg_default = 'Gagal memperbarui data pengeluaran';

} else { 
    sendJsonResponse('error', 'Aksi yang diminta tidak valid atau ID pengeluaran kosong.', 400);
}
 
if ($result && !isset($result['error'])) {
    sendJsonResponse('success', $success_msg);

} else { 
    $api_error = $result['error']['message'] ?? $result['message'] ?? $error_msg_default;
    sendJsonResponse('error', $api_error, 500);
}

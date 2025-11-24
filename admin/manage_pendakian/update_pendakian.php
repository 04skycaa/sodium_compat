<?php
header('Content-Type: application/json');

include __DIR__ . '/../../config/supabase.php'; 

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => true, 'message' => 'Metode request tidak diizinkan.']);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$idReservasi = $data['id_reservasi'] ?? null;
$idPendaki = $data['id_pendaki'] ?? null;

$namaLengkap = $data['nama_lengkap'] ?? null;
$nik = $data['nik'] ?? null;
$alamat = $data['alamat'] ?? null;
$nomorTelepon = $data['nomor_telepon'] ?? null;
$kontakDarurat = $data['kontak_darurat'] ?? null;
if (empty($idReservasi) && !isset($idPendaki)) { 
    http_response_code(400);
    echo json_encode(['error' => true, 'message' => 'ID Reservasi dan ID Pendaki wajib diisi untuk pembaruan data.']);
    exit;
}

$updatePayload = [
    'nama_lengkap' => $namaLengkap,
    'nik' => $nik,
    'alamat' => $alamat,
    'nomor_telepon' => $nomorTelepon,
    'kontak_darurat' => $kontakDarurat,
];

$tableName = 'pendaki_rombongan';
$endpoint = $tableName . '?id_reservasi=eq.' . urlencode($idReservasi) . '&id_pendaki=eq.' . urlencode($idPendaki);

$response = supabase_request('PATCH', $endpoint, $updatePayload);

if (!$response || isset($response['error'])) {
    http_response_code(500);
    $errorMessage = 'Gagal memperbarui data. (Supabase Error)';
    if (isset($response['message'])) {
        $errorMessage = $response['message'];
    }

    echo json_encode(['error' => true, 'message' => $errorMessage]);
    exit;
}

echo json_encode(['error' => false, 'message' => 'Data pendaki rombongan berhasil diperbarui.']);
?>
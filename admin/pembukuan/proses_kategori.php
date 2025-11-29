<?php 

error_reporting(E_ALL);
ini_set('display_errors', 1);
 
require __DIR__ . '/../../config/supabase.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { 
    http_response_code(405); 
    echo json_encode(['status' => 'error', 'message' => 'Metode tidak diizinkan.']);
    exit;
}
 
$nama_kategori = $_POST['nama_kategori'] ?? '';

if (empty($nama_kategori)) {
    http_response_code(400);  
    echo json_encode(['status' => 'error', 'message' => 'Nama kategori tidak boleh kosong.']);
    return;
}
 
$data = [
    'nama_kategori' => $nama_kategori
];
 
$result = supabase_request('POST', 'kategori_pengeluaran', $data);
 
if (isset($result['error']) || !$result || !isset($result[0])) {
    http_response_code(500);  
    $errorMessage = $result['error']['message'] ?? 'Gagal menambahkan data ke database.'; 
    if (isset($result['error']['details']) && strpos($result['error']['details'], 'duplicate key') !== false) {
        $errorMessage = "Nama kategori '{$nama_kategori}' sudah ada.";
        http_response_code(409); 
    }
    echo json_encode(['status' => 'error', 'message' => $errorMessage]);
} else { 
    $newCategory = $result[0];
    echo json_encode([
        'status' => 'success', 
        'message' => 'Kategori baru berhasil ditambahkan.',
        'new_kategori' => [ 
            'id_kategori' => $newCategory['id_kategori'],
            'nama_kategori' => $newCategory['nama_kategori']
        ]
    ]);
}
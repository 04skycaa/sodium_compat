<?php
// ==========================================================
// FILE: api/bikin_reservasi.php
// Endpoint API untuk CREATE Reservasi dan ambil data harga.
// ==========================================================
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Perbaikan: Asumsi file config.php berada di direktori yang sama
require_once 'config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PATCH');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

try {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // --- Perbaikan di sini: Ambil kolom nama_item dan harga dari pengaturan_biaya ---
        $response = makeSupabaseRequest('pengaturan_biaya?select=nama_item,harga', 'GET');
        
        if (isset($response['error'])) {
            http_response_code(500);
            echo json_encode(['error' => $response['error']]);
            exit;
        }
        
        // Mengubah array hasil menjadi map agar mudah diakses di Frontend
        $pricing_map = [];
        foreach ($response['data'] as $item) {
            $pricing_map[$item['nama_item']] = (int)$item['harga'];
        }
        
        // Mengirim data harga dalam format map
        echo json_encode([
            'status' => 'success', 
            'data' => $pricing_map
        ]);

    } 
    elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);

        // ... [Logika POST tetap sama] ...
        
        // Validate required fields
        if (!isset($data['tanggal_pendakian']) || !isset($data['jumlah_pendaki']) || 
            !isset($data['jumlah_tiket_parkir']) || !isset($data['total_harga']) || 
            !isset($data['id_pengguna']) || !isset($data['anggota_rombongan'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Data tidak lengkap']);
            exit;
        }

        $tanggal_pendakian = $data['tanggal_pendakian'];
        $jumlah_pendaki = $data['jumlah_pendaki'];
        $jumlah_tiket_parkir = $data['jumlah_tiket_parkir'];
        $total_harga = $data['total_harga'];
        $jumlah_potensi_sampah = $data['jumlah_potensi_sampah'] ?? 0;
        $id_pengguna = $data['id_pengguna']; 
        $anggota_rombongan = $data['anggota_rombongan'];
        $barang_bawaan = $data['barang_bawaan'] ?? [];

        // Check quota availability manually by fetching from kuota_harian table
        $kuota_response = makeSupabaseRequest(
            'kuota_harian?select=kuota_maksimal,kuota_terpesan&tanggal_kuota=eq.' . urlencode($tanggal_pendakian), 
            'GET'
        );

        if (isset($kuota_response['error'])) {
            http_response_code(500);
            echo json_encode(['error' => 'Gagal memeriksa kuota: ' . $kuota_response['error']]);
            exit;
        }

        $kuota_data = $kuota_response['data'];
        $available_quota = 50; 
        
        if (!empty($kuota_data)) {
            $available_quota = $kuota_data[0]['kuota_maksimal'] - $kuota_data[0]['kuota_terpesan'];
        }
        
        if ($available_quota < $jumlah_pendaki) {
            http_response_code(400);
            echo json_encode(['error' => 'Kuota tidak mencukupi untuk tanggal tersebut. Tersedia: ' . $available_quota . ', Dibutuhkan: ' . $jumlah_pendaki]);
            exit;
        }

        // Check if user exists in profiles
        $user_response = makeSupabaseRequest('profiles?select=id,nama_lengkap&' . 'id=eq.' . urlencode($id_pengguna), 'GET');
        
        if (isset($user_response['error'])) {
            http_response_code(500);
            echo json_encode(['error' => $user_response['error']]);
            exit;
        }

        $user_id = null;
        if (isset($user_response['data']) && count($user_response['data']) > 0) {
            $user_id = $user_response['data'][0]['id'];
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'User tidak ditemukan di database. Harap daftarkan user terlebih dahulu sebelum membuat reservasi.']);
            exit;
        }

        // Generate reservation code
        $kode_reservasi = 'R' . date('Ymd') . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 6));

        // Insert reservation
        $reservation_data = [
            'id_pengguna' => $user_id,
            'kode_reservasi' => $kode_reservasi,
            'tanggal_pendakian' => $tanggal_pendakian,
            'jumlah_pendaki' => $jumlah_pendaki,
            'jumlah_tiket_parkir' => $jumlah_tiket_parkir,
            'total_harga' => $total_harga,
            'jumlah_potensi_sampah' => $jumlah_potensi_sampah,
            'status' => 'pending', // Set status awal
        ];
        
        $reservation_response = makeSupabaseRequest('reservasi', 'POST', $reservation_data);

        if (isset($reservation_response['error'])) {
            http_response_code(500);
            echo json_encode(['error' => 'Gagal membuat reservasi: ' . $reservation_response['error']]);
            exit;
        }

        $id_reservasi = $reservation_response['data'][0]['id_reservasi'];

        // Insert group members (pendaki_rombongan)
        foreach ($anggota_rombongan as $pendaki) {
            $pendaki_data = [
                'id_reservasi' => $id_reservasi,
                'nama_lengkap' => $pendaki['nama_lengkap'],
                'nik' => $pendaki['nik'],
                'alamat' => $pendaki['alamat'],
                'nomor_telepon' => $pendaki['nomor_telepon'],
                'kontak_darurat' => $pendaki['kontak_darurat'],
                'url_surat_sehat' => $pendaki['url_surat_sehat'] ?? null
            ];
            
            $pendaki_response = makeSupabaseRequest('pendaki_rombongan', 'POST', $pendaki_data);

            if (isset($pendaki_response['error'])) {
                http_response_code(500);
                echo json_encode(['error' => 'Gagal menambahkan anggota rombongan: ' . $pendaki_response['error']]);
                exit;
            }
        }

        // Insert waste items (barang_bawaan_sampah)
        foreach ($barang_bawaan as $barang) {
            $barang_data = [
                'id_reservasi' => $id_reservasi,
                'nama_barang' => $barang['nama_barang'],
                'jenis_sampah' => $barang['jenis_sampah'],
                'jumlah' => $barang['jumlah'] ?? 1 
            ];
            
            $barang_response = makeSupabaseRequest('barang_bawaan_sampah', 'POST', $barang_data);

            if (isset($barang_response['error'])) {
                http_response_code(500);
                echo json_encode(['error' => 'Gagal menambahkan barang bawaan: ' . $barang_response['error']]);
                exit;
            }
        }

        // Update kuota_terpesan (Hanya jika reservasi berhasil)
        $update_kuota_endpoint = 'kuota_harian?tanggal_kuota=eq.' . urlencode($tanggal_pendakian);
        $kuota_update_data = ['kuota_terpesan' => (int)$kuotaTerpesan + (int)$jumlah_pendaki];
        makeSupabaseRequest($update_kuota_endpoint, 'PATCH', $kuota_update_data);
        
        // Return success response
        echo json_encode([
            'success' => true,
            'message' => 'Reservasi berhasil dibuat',
            'kode_reservasi' => $kode_reservasi
        ]);
    } else {
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>

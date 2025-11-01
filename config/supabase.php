<?php
// ... (Kode supabaseUrl, supabaseKey, dan fungsi supabase_request Anda yang sudah ada di sini) ...

$supabaseUrl = "https://kitxtcpfnccblznbagzx.supabase.co";
$supabaseKey = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImtpdHh0Y3BmbmNjYmx6bmJhZ3p4Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NTk1ODIxMzEsImV4cCI6MjA3NTE1ODEzMX0.OySigpw4AWI3G7JW_8r8yXu7re0Mr9CYv8u3d9Fr548"; // Sebaiknya gunakan Service Key untuk operasi backend

// Fungsi utama request Anda (tidak diubah)
function supabase_request($method, $endpoint, $data = null, $extra_headers = []) {
    global $supabaseUrl, $supabaseKey;
    // ... (Implementasi cURL Anda) ...
    $url = "$supabaseUrl/rest/v1/$endpoint";
    $method = strtoupper($method);

    $headers = [
        "apikey: {$supabaseKey}",
        "Authorization: Bearer {$supabaseKey}",
        "Accept: application/json" 
    ];

    $headers = array_merge($headers, $extra_headers);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
    curl_setopt($ch, CURLOPT_TIMEOUT, 30); 
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method); 

    if (($method === 'POST' || $method === 'PATCH') && $data !== null) {
        $payload = json_encode($data);
        if (json_last_error() !== JSON_ERROR_NONE) {
            curl_close($ch);
            return ["error" => ["message" => "Gagal encode data JSON: " . json_last_error_msg()]];
        }
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Content-Length: ' . strlen($payload);
        $headers[] = 'Prefer: return=representation'; 
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    } elseif ($method === 'DELETE') {
        $headers[] = 'Prefer: return=minimal';
    }

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response_body = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
    error_log("DEBUG - Supabase Raw Response (Code: {$http_code}): " . $response_body);
    $curl_error = curl_error($ch); 
    curl_close($ch);

    if ($curl_error) {
        return ["error" => ["message" => "Kesalahan cURL: " . $curl_error]];
    }

    $decoded_response = json_decode($response_body, true);
    if (json_last_error() !== JSON_ERROR_NONE && !empty(trim($response_body))) {
        return ["error" => ["message" => "Gagal decode respons JSON", "raw_response" => $response_body, "http_code" => $http_code]];
    }

    if ($http_code >= 200 && $http_code < 300) {
        if ($http_code === 204) {
            return [];
        }
        return $decoded_response ?? []; 
    } else {
        $errorMessage = $decoded_response['message'] ?? 'Terjadi kesalahan tidak diketahui dari server.';
        error_log("Supabase Request Error ({$http_code}) to {$endpoint}: " . print_r($decoded_response, true));
        return ["error" => ["message" => $errorMessage, "details" => $decoded_response, "http_code" => $http_code]];
    }
}


/**
 * Fungsi Pembantu untuk mengambil daftar profiles (id, nama_lengkap)
 * Digunakan di tambah_reservasi.php
 */
function fetchProfiles() {
    // Endpoint: /profiles?select=id,nama_lengkap
    $response = supabase_request('GET', 'profiles?select=id,nama_lengkap');
    
    if (isset($response['error'])) {
        error_log("Gagal fetch profiles: " . $response['error']['message']);
        return [];
    }
    return $response;
}


/**
 * Fungsi Pembantu untuk mengambil kuota harian
 * Digunakan di api_kuota.php
 */
function fetchKuota($tanggal) {
    // Endpoint: /kuota_harian?tanggal_kuota=eq.[tanggal]&select=kuota_maksimal,kuota_terpesan
    $filter = urlencode("tanggal_kuota=eq.{$tanggal}&select=kuota_maksimal,kuota_terpesan");
    $response = supabase_request('GET', "kuota_harian?{$filter}");
    
    if (isset($response['error'])) {
        error_log("Gagal fetch kuota untuk tanggal {$tanggal}: " . $response['error']['message']);
        return ["error" => $response['error']['message']];
    }
    return $response;
}
?>
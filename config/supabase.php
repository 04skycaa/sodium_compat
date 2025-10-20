<?php
$supabaseUrl = "https://kitxtcpfnccblznbagzx.supabase.co";
$supabaseKey = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImtpdHh0Y3BmbmNjYmx6bmJhZ3p4Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NTk1ODIxMzEsImV4cCI6MjA3NTE1ODEzMX0.OySigpw4AWI3G7JW_8r8yXu7re0Mr9CYv8u3d9Fr548"; // Sebaiknya gunakan Service Key untuk operasi backend

// Fungsi untuk melakukan request ke Supabase REST API
function supabase_request($method, $endpoint, $data = null, $extra_headers = []) {
    global $supabaseUrl, $supabaseKey;

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
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Mengembalikan hasil sebagai string
    curl_setopt($ch, CURLOPT_TIMEOUT, 30); // Timeout 30 detik
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method); // Set metode HTTP (GET, POST, PATCH, DELETE)

    // Pengaturan spesifik untuk metode
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

    // Decode respons JSON
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
?>
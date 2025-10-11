<?php
$supabaseUrl = "https://kitxtcpfnccblznbagzx.supabase.co";
$supabaseKey = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImtpdHh0Y3BmbmNjYmx6bmJhZ3p4Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NTk1ODIxMzEsImV4cCI6MjA3NTE1ODEzMX0.OySigpw4AWI3G7JW_8r8yXu7re0Mr9CYv8u3d9Fr548";

function supabase_request($method, $endpoint, $data = null) {
    global $supabaseUrl, $supabaseKey;

    $url = "$supabaseUrl/rest/v1/$endpoint";
    $method = strtoupper($method);
    $headers = [
        "apikey: $supabaseKey",
        "Authorization: Bearer $supabaseKey",
        "Prefer: return=representation"
    ];

    $options = [
        "http" => [
            "method" => $method,
            "ignore_errors" => true 
        ]
    ];

    if (($method === 'POST' || $method === 'PATCH') && $data !== null) {
        $headers[] = "Content-Type: application/json"; 
        $options["http"]["content"] = json_encode($data);
    }

    $options["http"]["header"] = implode("\r\n", $headers);
    $context = stream_context_create($options);
    $result = @file_get_contents($url, false, $context);

    if ($result === false) {
        return ["error" => ["message" => "Gagal terhubung ke server Supabase."]];
    }

    $decoded_response = json_decode($result, true);
    
    if (isset($http_response_header[0]) && strpos($http_response_header[0], '200') === false && strpos($http_response_header[0], '201') === false && strpos($http_response_header[0], '204') === false) {
        $errorMessage = $decoded_response['message'] ?? 'Terjadi kesalahan tidak diketahui.';
        return ["error" => ["message" => $errorMessage, "details" => $decoded_response]];
    }
    
    return $decoded_response;
}
?>
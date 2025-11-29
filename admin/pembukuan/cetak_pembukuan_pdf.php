<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include __DIR__ . '/../../config/supabase.php';

$startDate = $_GET['start_date'] ?? 'Awal';
$endDate = $_GET['end_date'] ?? 'Akhir';
$jenisTransaksi = $_GET['jenis_transaksi'] ?? 'semua';
$pemasukan_data = supabase_request('GET', 'pemasukan?select=*,tanggal_pemasukan,keterangan,jumlah');
$pengeluaran_data = supabase_request('GET', 'pengeluaran?select=*,keterangan,jumlah,tanggal_pengeluaran,kategori_pengeluaran(nama_kategori),deleted_at&deleted_at=is.null'); 

$all_transactions = [];

if ($pemasukan_data && !isset($pemasukan_data['error'])) { 
    foreach ($pemasukan_data as $item) {
        $all_transactions[] = [
            'tanggal' => $item['tanggal_pemasukan'],
            'jenis' => 'Pemasukan',
            'deskripsi' => $item['keterangan'],
            'kategori' => 'Pemasukan',
            'jumlah' => $item['jumlah']
        ];
    }
}

if ($pengeluaran_data && !isset($pengeluaran_data['error'])) { 
    foreach ($pengeluaran_data as $item) {
        $all_transactions[] = [
            'tanggal' => $item['tanggal_pengeluaran'],
            'jenis' => 'Pengeluaran',
            'deskripsi' => $item['keterangan'],
            'kategori' => $item['kategori_pengeluaran']['nama_kategori'] ?? 'Tanpa Kategori', 
            'jumlah' => $item['jumlah']
        ];
    }
}
 
usort($all_transactions, function($a, $b) {
    return strtotime($a['tanggal']) - strtotime($b['tanggal']);  
});
 
$filtered_transactions = array_filter($all_transactions, function($trans) use ($startDate, $endDate, $jenisTransaksi) {
    $tanggal_transaksi = strtotime($trans['tanggal']); 
    $startTs = $startDate !== 'Awal' ? strtotime($startDate) : 0; 
    $endTs = $endDate !== 'Akhir' ? strtotime($endDate . ' 23:59:59') : PHP_INT_MAX; 
    
    if ($startTs && $tanggal_transaksi < $startTs) return false;
    if ($endTs && $tanggal_transaksi > $endTs) return false; 
    if ($jenisTransaksi !== 'semua' && $trans['jenis'] !== $jenisTransaksi) return false;
    
    return true;
});
 
$total_pemasukan = 0;
$total_pengeluaran = 0;
foreach ($filtered_transactions as $trans) {
    if ($trans['jenis'] === 'Pemasukan') {
        $total_pemasukan += $trans['jumlah'];
    } else {
        $total_pengeluaran += $trans['jumlah'];
    }
}
$saldo_akhir = $total_pemasukan - $total_pengeluaran;
$jumlah_transaksi = count($filtered_transactions);

function format_rupiah($number) {
    return 'Rp ' . number_format($number, 0, ',', '.');
} 

$html_content = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Pembukuan - ' . date('Y-m-d') . '</title>
    <style>
        /* Gaya CSS khusus untuk Dompdf */
        body { font-family: sans-serif; margin: 0; padding: 0; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #007bff; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 24px; color: #333; }
        .periode { font-size: 14px; color: #555; margin-top: 5px; }
        
        .ringkasan { margin-bottom: 30px; border: 1px solid #ddd; border-radius: 5px; padding: 15px; background-color: #f9f9f9; }
        .ringkasan table { width: 100%; border-collapse: collapse; }
        .ringkasan td { padding: 8px 0; font-size: 14px; }
        .ringkasan .label { width: 50%; font-weight: 600; color: #555; }
        .ringkasan .value { text-align: right; font-weight: 700; }
        .ringkasan .saldo-akhir { border-top: 2px solid #007bff; font-size: 16px; color: #000; padding-top: 10px; margin-top: 5px; }

        .section-title { font-size: 18px; font-weight: 700; color: #333; margin: 25px 0 10px 0; border-bottom: 1px solid #ccc; padding-bottom: 5px; }

        .data-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .data-table th, .data-table td { border: 1px solid #ddd; padding: 10px; text-align: left; font-size: 12px; }
        .data-table th { background-color: #007bff; color: white; }
        
        .green-text { color: #28a745; }
        .red-text { color: #dc3545; }
    </style>
</head>
<body>

    <div class="header">
        <h1>LAPORAN PEMBUKUAN KEUANGAN</h1>
        <div class="periode">
            Periode: ' . htmlspecialchars($startDate) . ' s.d. ' . htmlspecialchars($endDate) . ' | Jenis Transaksi: ' . htmlspecialchars(ucfirst($jenisTransaksi)) . '
        </div>
    </div>

    <div class="ringkasan">
        <table>
            <tr>
                <td class="label">Total Pemasukan:</td>
                <td class="value green-text">' . format_rupiah($total_pemasukan) . '</td>
            </tr>
            <tr>
                <td class="label">Total Pengeluaran:</td>
                <td class="value red-text">' . format_rupiah($total_pengeluaran) . '</td>
            </tr>
            <tr>
                <td class="label saldo-akhir">SALDO AKHIR:</td>
                <td class="value saldo-akhir">' . format_rupiah($saldo_akhir) . '</td>
            </tr>
            <tr>
                <td class="label">Total Jumlah Transaksi:</td>
                <td class="value">' . $jumlah_transaksi . '</td>
            </tr>
        </table>
    </div>

    <h2 class="section-title">Detail Transaksi</h2>
    
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 15%;">Tanggal</th>
                <th style="width: 10%;">Jenis</th>
                <th style="width: 35%;">Keterangan / Deskripsi</th>
                <th style="width: 20%;">Kategori</th>
                <th style="width: 20%; text-align: right;">Jumlah</th>
            </tr>
        </thead>
        <tbody>';
        
foreach ($filtered_transactions as $trans) {
    $jenis_color = $trans['jenis'] === 'Pemasukan' ? 'green-text' : 'red-text';
    $prefix = $trans['jenis'] === 'Pemasukan' ? '+' : '-';

    $html_content .= '
        <tr>
            <td>' . date('d M Y', strtotime($trans['tanggal'])) . '</td>
            <td>' . htmlspecialchars($trans['jenis']) . '</td>
            <td>' . htmlspecialchars($trans['deskripsi']) . '</td>
            <td>' . htmlspecialchars($trans['kategori']) . '</td>
            <td class="' . $jenis_color . '" style="text-align: right;">' . $prefix . ' ' . format_rupiah($trans['jumlah']) . '</td>
        </tr>';
}

$html_content .= '
        </tbody>
    </table>

</body>
</html>
';
require_once __DIR__ . '/../../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true); 

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html_content);
$dompdf->setPaper('A4', 'portrait');
 
$dompdf->render();
 
$dompdf->stream('Laporan_Pembukuan_Periode_' . $startDate . '_sd_' . $endDate . '.pdf', array("Attachment" => true));

exit;
?>
<?php
header('Content-Type: application/json');

// URL Firebase
$url = "https://monitoring-lampu-iot-212398-default-rtdb.firebaseio.com/laporan/light_sensor.json";

// Ambil data dari Firebase
$response = file_get_contents($url);

// Ubah ke array
$data = json_decode($response, true);

// Bersihkan karakter escape dari kunci
$cleanedData = [];
foreach ($data as $key => $value) {
    $cleanedKey = trim($key, '\"');  // Menghapus tanda kutip dan escape characters
    $cleanedData[$cleanedKey] = $value;
}

// Kirim kembali ke frontend sebagai JSON
echo json_encode($cleanedData);
?>
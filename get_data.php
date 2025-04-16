<?php
header('Content-Type: application/json');

// URL Firebase
$url = "https://monitoring-lampu-iot-212398-default-rtdb.firebaseio.com/iot_lampu_212398.json";

// Ambil data dari Firebase
$response = file_get_contents($url);

// Ubah ke array
$data = json_decode($response, true);

// Kirim kembali ke frontend sebagai JSON
echo json_encode($data);
?>
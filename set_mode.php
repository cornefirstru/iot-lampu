<?php
// Pastikan headernya JSON
header('Content-Type: application/json');

// Cek apakah parameter 'mode' ada
if (isset($_GET['mode'])) {
    $mode = $_GET['mode'];

    // URL Firebase untuk update data
    $firebaseUrl = "https://monitoring-lampu-iot-212398-default-rtdb.firebaseio.com/iot_lampu_212398.json";

    // Data untuk dikirim ke Firebase
    $data = [
        'mode_212398' => $mode,  // update mode lampu
    ];

    // Ubah data menjadi JSON
    $jsonData = json_encode($data);

    // Inisialisasi curl untuk update Firebase
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $firebaseUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH"); // Gunakan PATCH untuk update sebagian data
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

    // Eksekusi request
    $response = curl_exec($ch);

    // Cek apakah request berhasil
    if(curl_errno($ch)) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . curl_error($ch)]);
    } else {
        // Kirim response sukses
        echo json_encode(['success' => true]);
    }

    // Tutup curl
    curl_close($ch);

} else {
    echo json_encode(['success' => false, 'message' => 'Mode tidak ditemukan']);
}
?>

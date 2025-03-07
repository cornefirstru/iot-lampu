<?php
if (isset($_GET['status'])) {
    $status = $_GET['status'];

    // Validasi hanya menerima "on" atau "off"
    if ($status !== "on" && $status !== "off") {
        echo json_encode(["success" => false, "error" => "Nilai harus 'on' atau 'off'"]);
        exit();
    }

    // URL Firebase
    $url = "https://lampu-iot-29a38-default-rtdb.firebaseio.com/Lampu.json";

    // Data yang akan dikirim
    $data = json_encode($status);

    // Konfigurasi cURL
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    // Eksekusi cURL
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Cek apakah permintaan berhasil
    if ($httpCode == 200) {
        echo json_encode(["success" => true, "status" => $status]);
    } else {
        echo json_encode(["success" => false, "error" => "Gagal mengubah data di Firebase"]);
    }
} else {
    echo json_encode(["success" => false, "error" => "Parameter status tidak ditemukan"]);
}
?>
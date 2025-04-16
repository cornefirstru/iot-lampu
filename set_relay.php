<?php
if (isset($_GET['status'])) {
    $status = $_GET['status'];

    if ($status === "on" || $status === "off") {
        // URL Firebase Realtime Database (ganti sesuai project kamu)
        $firebase_url = "https://monitoring-lampu-iot-212398-default-rtdb.firebaseio.com/iot_lampu_212398/lampu_status_212398.json";

        $data = json_encode($status);

        $options = [
            'http' => [
                'method' => 'PUT',
                'header' => 'Content-type: application/json',
                'content' => $data
            ]
        ];

        $context = stream_context_create($options);
        $result = file_get_contents($firebase_url, false, $context);

        if ($result !== false) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "error" => "Gagal mengirim data ke Firebase"]);
        }
    } else {
        echo json_encode(["success" => false, "error" => "Status tidak valid"]);
    }
} else {
    echo json_encode(["success" => false, "error" => "Parameter status tidak ditemukan"]);
}
?>
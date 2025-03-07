<?php
$url = "https://lampu-iot-29a38-default-rtdb.firebaseio.com/Lampu.json";
$response = file_get_contents($url);

if ($response !== false) {
    $relayStatus = json_decode($response, true);

    // Pastikan nilai hanya "on" atau "off"
    if ($relayStatus === "on" || $relayStatus === "off") {
        echo json_encode(["relay" => $relayStatus]);
    } else {
        echo json_encode(["error" => "Nilai tidak valid di Firebase"]);
    }
} else {
    echo json_encode(["error" => "Gagal mengambil data dari Firebase"]);
}
?>
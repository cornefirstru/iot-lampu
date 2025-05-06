<?php
// Misal pakai Firebase Realtime Database REST API
if (isset($_GET['threshold'])) {
    $threshold = intval($_GET['threshold']);

    $url = "https://your-project.firebaseio.com/light_sensor_threshold_212398.json";
    $data = json_encode($threshold);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false]);
}
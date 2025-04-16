<?php
if (isset($_GET['status'])) {
    $status = $_GET['status'];
    if ($status == "on" || $status == "off") {
        $url = "https://monitoring-lampu-iot-212398-default-rtdb.firebaseio.com/iot_lampu_212398/perintah_manual_212398.json";
        $data = json_encode($status);
        $options = [
            'http' => [
                'method' => 'PUT',
                'header' => 'Content-type: application/json',
                'content' => $data
            ]
        ];
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false]);
    }
}
?>
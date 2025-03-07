<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontrol Relay</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
    body {
        background-color: #f8f9fa;
        text-align: center;
        padding: 40px;
    }

    .status-box {
        font-size: 24px;
        font-weight: bold;
        padding: 20px;
        border-radius: 10px;
        display: inline-block;
        transition: 0.3s;
    }

    .relay-on {
        background-color: #28a745;
        color: white;
    }

    .relay-off {
        background-color: #dc3545;
        color: white;
    }

    .btn-custom {
        width: 180px;
        font-size: 20px;
        padding: 12px;
        margin: 10px;
    }
    </style>
</head>

<body>

    <h2 class="mb-4">Kontrol Relay</h2>

    <p id="relayStatus" class="status-box relay-off">Mengambil data...</p>

    <div class="mt-4">
        <button class="btn btn-success btn-custom" onclick="setRelay('on')">Nyalakan Relay</button>
        <button class="btn btn-danger btn-custom" onclick="setRelay('off')">Matikan Relay</button>
    </div>

    <script>
    function fetchRelayStatus() {
        fetch('get_relay.php')
            .then(response => response.json())
            .then(data => {
                let relayBox = document.getElementById("relayStatus");
                if (data.relay === "on") {
                    relayBox.innerText = "Relay: ON";
                    relayBox.className = "status-box relay-on";
                } else if (data.relay === "off") {
                    relayBox.innerText = "Relay: OFF";
                    relayBox.className = "status-box relay-off";
                } else {
                    relayBox.innerText = "Error membaca status";
                    relayBox.className = "status-box relay-off";
                }
            })
            .catch(error => console.error("Error:", error));
    }

    function setRelay(status) {
        fetch('set_relay.php?status=' + status)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    setTimeout(fetchRelayStatus, 500); // Beri jeda sebelum update
                } else {
                    alert("Gagal mengubah relay!");
                }
            })
            .catch(error => console.error("Error:", error));
    }

    setInterval(fetchRelayStatus, 2000); // Perbarui status setiap 2 detik
    </script>

</body>

</html>
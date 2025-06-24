<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontrol Lampu IoT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
    body {
        display: flex;
        min-height: 100vh;
        margin: 0;
        font-family: 'Segoe UI', sans-serif;
    }

    .sidebar {
        width: 250px;
        background: linear-gradient(180deg, #212529, #343a40);
        color: white;
        padding: 30px 20px;
        box-shadow: 2px 0 10px rgba(0, 0, 0, 0.2);
    }

    .sidebar h4 {
        margin-bottom: 30px;
    }

    .sidebar a {
        color: #ccc;
        display: flex;
        align-items: center;
        padding: 10px 0;
        text-decoration: none;
        font-size: 16px;
    }

    .sidebar a i {
        margin-right: 10px;
    }

    .sidebar a:hover {
        color: white;
    }

    .main-content {
        flex-grow: 1;
        padding: 40px;
        background-color: #f1f3f5;
    }

    .status-box {
        font-size: 18px;
        padding: 18px;
        border-radius: 10px;
        margin-bottom: 20px;
        font-weight: 500;
    }

    .on {
        background: linear-gradient(90deg, #28a745, #218838);
        color: white;
    }

    .off {
        background: linear-gradient(90deg, #dc3545, #c82333);
        color: white;
    }

    .btn-custom {
        width: 180px;
        padding: 12px;
        font-size: 16px;
        margin: 10px;
    }

    .card-header {
        font-weight: bold;
    }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Gauge Plugin -->
    <script
        src="https://cdn.jsdelivr.net/npm/chartjs-plugin-doughnutlabel@1.0.1/dist/chartjs-plugin-doughnutlabel.min.js">
    </script>


</head>

<body>

    <div class="sidebar">
        <h4><i class="bi bi-lightbulb-fill me-2"></i>Smart Lampu</h4>
        <a href="#"><i class="bi bi-speedometer2"></i> Dashboard</a>
        <a href="login.php"><i class="bi bi-toggle-on"></i> Log out</a>
        <!-- <a href="laporan_sensor_lampu"><i class="bi bi-file-earmark-text"></i> Laporan Sensor Cahaya</a> -->
    </div>

    <div class="main-content">
        <h2 class="mb-4">Kontrol Lampu IoT</h2>

        <div id="statusLampuWrapper" class="d-flex justify-content-between align-items-center status-box off">
            <div id="statusLampuText">Lampu: Mengambil...</div>
            <button id="toggleLampuBtn" class="btn btn-sm btn-outline-light" onclick="toggleLampu()">Toggle</button>
        </div>

        <div class="status-box bg-warning text-white d-flex justify-content-between align-items-center">
            <div>Ambang Batas Kegelapan: <span id="ambangBatasText">--</span></div>
            <div>
                <input type="number" id="ambangBatasInput" class="form-control form-control-sm d-inline-block"
                    style="width: 80px;" />
                <button class="btn btn-sm btn-light" onclick="updateAmbangBatas()">Simpan</button>
            </div>
        </div>

        <div id="sensorCahaya" class="status-box bg-secondary text-white">Sensor Cahaya: --</div>
        <h4>Sensor Cahaya</h4>
        <!-- Tambahkan pembungkus luar untuk centering -->
        <div style="display: flex; justify-content: center; align-items: center;">
            <div style="width: 400px; height: 400px;">
                <canvas id="gaugeChart"></canvas>
            </div>
        </div>

        <!-- Mode -->
        <div id="mode" class="status-box bg-info text-white">Mode: --</div>
        <div><button id="modeToggleBtn" class="status-box btn btn-info btn-sm" onclick="toggleMode()">Ubah Mode</button>
        </div>
        <div></div>
        <div id="timestamp" class="status-box bg-dark text-white">Waktu: --</div>
        <script>
        let gaugeChart;

        function initGauge() {
            const ctx = document.getElementById('gaugeChart').getContext('2d');
            gaugeChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Cahaya'],
                    datasets: [{
                        label: 'Light',
                        data: [0, 100],
                        backgroundColor: ['#ffc107', '#e0e0e0'],
                        borderWidth: 0,
                        cutout: '80%'
                    }]
                },
                options: {
                    cutout: '80%',
                    rotation: -90,
                    circumference: 180,
                    responsive: true,
                    plugins: {
                        doughnutlabel: {
                            labels: [{
                                text: '0',
                                font: {
                                    size: 30,
                                    weight: 'bold'
                                }
                            }, {
                                text: 'Lux'
                            }]
                        }
                    }
                }
            });
        }

        function updateGauge(value) {
            gaugeChart.data.datasets[0].data[0] = value;
            gaugeChart.data.datasets[0].data[1] = 1000 - value;
            gaugeChart.options.plugins.doughnutlabel.labels[0].text = value;
            gaugeChart.update();
        }

        function fetchData() {
            fetch('get_data.php')
                .then(res => res.json())
                .then(data => {
                    const lampuStatus = (data.lampu_status_212398 || "--").toLowerCase();
                    const statusBox = document.getElementById("statusLampuWrapper");
                    const statusText = document.getElementById("statusLampuText");
                    const toggleBtn = document.getElementById("toggleLampuBtn");

                    statusText.innerText = "Lampu: " + lampuStatus.toUpperCase();
                    statusBox.className = "d-flex justify-content-between align-items-center status-box " + (
                        lampuStatus === 'on' ? 'on' : 'off');

                    if (lampuStatus === 'on') {
                        toggleBtn.innerHTML = '<i class="bi bi-power"></i> Matikan';
                        toggleBtn.className = 'btn btn-sm btn-danger';
                        toggleBtn.dataset.status = 'off';
                    } else {
                        toggleBtn.innerHTML = '<i class="bi bi-lightbulb"></i> Nyalakan';
                        toggleBtn.className = 'btn btn-sm btn-success';
                        toggleBtn.dataset.status = 'on';
                    }

                    document.getElementById("sensorCahaya").innerText = "Sensor Cahaya: " + (data
                        .light_sensor_212398 || "--");
                    document.getElementById("mode").innerText = "Mode: " + (data.mode_212398 || "--").toUpperCase();
                    document.getElementById("timestamp").innerText = "Waktu: " + (data.timestanp || "--");


                    let light = parseInt(data.light_sensor_212398) || 0;
                    updateGauge(light);

                    // Cek jika mode auto, maka nonaktifkan tombol
                    const mode = data.mode_212398 || "";
                    toggleLampuButtonState(mode);

                    document.getElementById("ambangBatasText").innerText = data.light_sensor_threshold_212398 ||
                        "--";

                })
                .catch(err => {
                    console.error("Gagal mengambil data:", err);
                });
        }

        function updateAmbangBatas() {
            const newThreshold = parseInt(document.getElementById("ambangBatasInput").value);
            if (isNaN(newThreshold)) {
                alert("Nilai ambang batas tidak valid.");
                return;
            }

            fetch("https://monitoring-lampu-iot-212398-default-rtdb.firebaseio.com/iot_lampu_212398/light_sensor_threshold_212398.json", {
                    method: "PUT",
                    body: JSON.stringify(newThreshold),
                    headers: {
                        "Content-Type": "application/json"
                    }
                })
                .then(res => res.json())
                .then(data => {
                    console.log("Ambang batas berhasil diperbarui:", data);
                    fetchData(); // refresh tampilan
                })
                .catch(err => {
                    console.error("Gagal mengupdate ambang batas:", err);
                });
        }



        function toggleLampuButtonState(mode) {
            const toggleBtn = document.getElementById("toggleLampuBtn");
            if (mode === 'auto') {
                // Nonaktifkan tombol jika mode auto
                toggleBtn.disabled = true;
                toggleBtn.innerHTML = '<i class="bi bi-lock"></i> Mode Auto (Tombol dinonaktifkan)';
                toggleBtn.classList.add('btn-secondary');
            } else {
                // Aktifkan tombol jika mode manual
                toggleBtn.disabled = false;
                toggleBtn.classList.remove('btn-secondary');
                toggleBtn.innerHTML = toggleBtn.dataset.status === 'on' ? '<i class="bi bi-lightbulb"></i> Nyalakan' :
                    '<i class="bi bi-power"></i> Matikan';
            }
        }

        function setManual(status) {
            fetch('set_manual.php?status=' + status)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        fetchData();
                    } else {
                        alert("Gagal mengirim perintah manual");
                    }
                });
        }

        function toggleMode() {
            const currentMode = document.getElementById("mode").innerText.split(": ")[1].toLowerCase();
            const newMode = currentMode === 'manual' ? 'auto' : 'manual';

            // Kirim permintaan untuk mengubah mode di Firebase
            fetch('set_mode.php?mode=' + newMode) // Pastikan 'mode' ada di URL
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Mode berhasil diubah, perbarui UI
                        document.getElementById("mode").innerText = "Mode: " + newMode.charAt(0).toUpperCase() +
                            newMode.slice(1);
                    } else {
                        alert("Gagal mengubah mode");
                    }
                })
                .catch(err => {
                    console.error("Gagal mengubah mode:", err);
                });
        }



        function toggleLampu() {
            const btn = document.getElementById("toggleLampuBtn");
            const status = btn.dataset.status;
            setManual(status);
        }

        window.onload = () => {
            initGauge();
            fetchData();
            setInterval(fetchData, 500);
        };
        </script>


</body>

</html>
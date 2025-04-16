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
        <a href="#"><i class="bi bi-toggle-on"></i> Kontrol</a>
        <a href="laporan_sensor_lampu"><i class="bi bi-file-earmark-text"></i> Laporan Sensor Cahaya</a>
        <a href="#"><i class="bi bi-file-earmark-text"></i> Laporan Perubahan Status</a>
    </div>

    <div class="main-content">
        <h2 class="mb-4">Kontrol Lampu IoT</h2>

        <div id="statusLampu" class="status-box off">Lampu: Mengambil...</div>
        <div id="sensorCahaya" class="status-box bg-secondary text-white">Sensor Cahaya: --</div>
        <h4>Sensor Cahaya</h4>
        <!-- Tambahkan pembungkus luar untuk centering -->
        <div style="display: flex; justify-content: center; align-items: center;">
            <div style="width: 400px; height: 400px;">
                <canvas id="gaugeChart"></canvas>
            </div>
        </div>

        <div id="mode" class="status-box bg-info text-white">Mode: --</div>
        <div id="manualCommand" class="status-box bg-warning text-dark">Perintah Manual: --</div>
        <div id="timestamp" class="status-box bg-dark text-white">Waktu: --</div>

        <div class="mt-4">
            <button class="btn btn-success btn-custom" onclick="setManual('on')">
                <i class="bi bi-toggle-on"></i> Nyalakan Manual
            </button>
            <button class="btn btn-danger btn-custom" onclick="setManual('off')">
                <i class="bi bi-toggle-off"></i> Matikan Manual
            </button>
        </div>
    </div>

    <script>
    function fetchData() {
        fetch('get_data.php')
            .then(response => response.json())
            .then(data => {
                document.getElementById("statusLampu").innerText = "Lampu: " + (data.lampu_status_212398 || "--")
                    .toUpperCase();
                document.getElementById("statusLampu").className = "status-box " + (data.lampu_status_212398 ===
                    'on' ? 'on' : 'off');

                document.getElementById("sensorCahaya").innerText = "Sensor Cahaya: " + (data.light_sensor_212398 ||
                    "--");
                document.getElementById("mode").innerText = "Mode: " + (data.mode_212398 || "--").toUpperCase();
                document.getElementById("manualCommand").innerText = "Perintah Manual: " + (data
                    .perintah_manual_212398 || "--").toUpperCase();
                document.getElementById("timestamp").innerText = "Waktu: " + (data.timestanp || "--");
                // Update gauge chart dengan nilai sensor cahaya
                gaugeChart.data.datasets[0].data[0] = data.light_sensor_212398 || 0;
                gaugeChart.update();

            })
            .catch(err => {
                console.error("Gagal mengambil data:", err);
            });

    }

    function setManual(status) {
        fetch('set_manual.php?status=' + status)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    fetchData(); // refresh data
                } else {
                    alert("Gagal mengirim perintah manual");
                }
            });
    }

    setInterval(fetchData, 2000);
    </script>

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
                    data: [0, 100], // [nilai sensor, sisa]
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
        gaugeChart.data.datasets[0].data[1] = 100 - value;
        gaugeChart.options.plugins.doughnutlabel.labels[0].text = value;
        gaugeChart.update();
    }

    window.onload = () => {
        initGauge();
        fetchData();
        setInterval(fetchData, 2000);
    };

    function fetchData() {
        fetch('get_data.php')
            .then(res => res.json())
            .then(data => {
                let light = parseInt(data.light_sensor_212398) || 0;
                updateGauge(light);
                // sisipkan kode lainnya untuk update elemen teks
            });
    }
    </script>


</body>

</html>
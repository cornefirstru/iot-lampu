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
</head>

<body>

    <div class="sidebar">
        <h4><i class="bi bi-lightbulb-fill me-2"></i>Smart Lampu</h4>
        <a href="../index.php"><i class="bi bi-speedometer2"></i> Dashboard</a>
        <a href="#"><i class="bi bi-toggle-on"></i> Kontrol</a>
        <a href="index.php"><i class="bi bi-file-earmark-text"></i> Laporan Sensor Cahaya</a>
        <a href="#"><i class="bi bi-file-earmark-text"></i> Laporan Perubahan Status</a>
    </div>

    <div class="container">
        <h2 class="text-center">Laporan Perubahan Kontrol</h2>

        <!-- Tombol Cetak Laporan -->
        <div class="text-center mb-3">
            <button class="btn btn-primary" onclick="printReport()">Cetak Laporan</button>
        </div>

    </div>

    <script>
    // Fungsi untuk mengambil data dari PHP dan menampilkan di tabel
    function fetchData() {
        fetch('get_data.php') // Panggil file PHP yang sudah disiapkan
            .then(response => response.json())
            .then(data => {
                const laporanData = document.getElementById('laporanData');
                laporanData.innerHTML = ''; // Kosongkan tabel sebelumnya
                if (data) {
                    let no = 1;
                    for (let timestamp in data) {
                        // Pastikan ada data dan timestamp
                        if (data[timestamp]) {
                            // Menambahkan data ke tabel
                            laporanData.innerHTML += `
                                <tr>
                                    <td>${no}</td>
                                    <td>${data[timestamp]}</td>  <!-- Nilai Sensor Cahaya -->
                                    <td>${timestamp}</td>  <!-- Timestamp -->
                                </tr>
                            `;
                            no++;
                        }
                    }
                } else {
                    laporanData.innerHTML = `
                        <tr>
                            <td colspan="3">Tidak ada data laporan</td>
                        </tr>
                    `;
                }
            })
            .catch(err => console.error('Error fetching data:', err));
    }

    // Panggil fungsi fetchData saat halaman dimuat
    window.onload = fetchData;

    // Fungsi untuk mencetak laporan
    function printReport() {
        const printWindow = window.open('', '', 'height=800,width=1200');
        printWindow.document.write('<html><head><title>Laporan Light Sensor</title>');
        printWindow.document.write('</head><body>');
        printWindow.document.write('<h2>Laporan Light Sensor</h2>');
        printWindow.document.write('<table border="1" style="width:100%; border-collapse: collapse;">');
        printWindow.document.write('<thead><tr><th>No</th><th>Nilai Sensor Cahaya</th><th>Timestamp</th></tr></thead>');
        printWindow.document.write('<tbody>');

        const tableRows = document.querySelectorAll('#laporanData tr');
        tableRows.forEach((row, index) => {
            const columns = row.querySelectorAll('td');
            printWindow.document.write('<tr>');
            columns.forEach(col => {
                printWindow.document.write('<td>' + col.innerHTML + '</td>');
            });
            printWindow.document.write('</tr>');
        });

        printWindow.document.write('</tbody></table>');
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.print();
    }
    </script>
</body>


</html>
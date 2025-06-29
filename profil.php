<?php
session_start();
include 'koneksi.php'; // Pastikan file koneksi database sudah benar

// Cek apakah user sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];

// Ambil data user dari database
$query = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Proses update data jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $email = $_POST['email'];

    $update = "UPDATE users SET nama = ?, email = ? WHERE username = ?";
    $stmt = $conn->prepare($update);
    $stmt->bind_param("sss", $nama, $email, $username);
    if ($stmt->execute()) {
        echo "<script>alert('Profil berhasil diperbarui');window.location='profil.php';</script>";
        exit();
    } else {
        echo "<script>alert('Gagal memperbarui profil');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Profil Saya</title>
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
        .profile-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.07);
            padding: 32px 28px;
            max-width: 480px;
            margin: 0 auto;
        }
        .profile-card h2 {
            margin-bottom: 24px;
            text-align: center;
        }
        .form-label {
            font-weight: 500;
        }
        .btn-primary {
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h4><i class="bi bi-lightbulb-fill me-2"></i>Smart Lampu</h4>
        <a href="index.php"><i class="bi bi-speedometer2"></i> Dashboard</a>
        <a href="profil.php"><i class="bi bi-person"></i> Profil</a>
        <a href="login.php"><i class="bi bi-toggle-on"></i> Log out</a>
    </div>
    <div class="main-content">
        <div class="profile-card">
            <a href="index.php" class="btn btn-secondary mb-3"><i class="bi bi-arrow-left"></i> Kembali</a>
            <h2>Profil Akun</h2>
            <form method="POST" action="">
                <div class="mb-3">
                    <label class="form-label">NIK (Username)</label>
                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" disabled>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input type="text" name="nama" class="form-control" value="<?php echo htmlspecialchars($user['nama'] ?? ''); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['password'] ?? ''); ?>" required>
                </div>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </form>
        </div>
    </div>
</body>
</html>
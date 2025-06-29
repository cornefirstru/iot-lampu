<?php
session_start();

// Konfigurasi koneksi database
$host = "localhost";
$user = "root"; // default phpMyAdmin user
$pass = ""; // sesuaikan dengan password phpMyAdmin kamu
$db   = "iot_lampu";

// Buat koneksi
$conn = new mysqli($host, $user, $pass, $db);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data dari form
$username = $_POST['username'];
$password = $_POST['password'];

// Validasi panjang password harus minimal 7 karakter
if (strlen($password) < 7) {
    echo "<script>alert('Password harus minimal 7 karakter.');window.location.href='login.php';</script>";
    exit;
}

// Cari user di database
$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();
    
    // Verifikasi password langsung (tanpa hash)
    if ($password === $row['password']) {
        $_SESSION['username'] = $row['username'];
        header("Location: index.php");
        exit;
    } else {
        echo "<script>alert('Password salah.');window.location.href='login.php';</script>";
        exit;
    }
} else {
    echo "<script>alert('Username tidak ditemukan.');window.location.href='login.php';</script>";
    exit;
}
// Tutup koneksi

$stmt->close();
$conn->close();
?>

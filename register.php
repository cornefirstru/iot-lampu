<?php
// Koneksi ke database
$host = "localhost";
$user = "root";
$pass = "";
$db   = "iot_lampu";
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Proses registrasi
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nik = trim($_POST["nik"]);
    $password = trim($_POST["password"]);
    $confirm  = trim($_POST["confirm"]);

    // Validasi NIK
    if (empty($nik) || empty($password) || empty($confirm)) {
        $message = "Semua field harus diisi.";
    } elseif (!ctype_digit($nik) || strlen($nik) != 16) {
        $message = "NIK harus terdiri dari 16 digit angka.";
    } elseif ($password !== $confirm) {
        $message = "Konfirmasi password tidak cocok.";
    } else {
        // Cek NIK sudah ada atau belum
        $stmt = $conn->prepare("SELECT id FROM user WHERE username = ?");
        $stmt->bind_param("s", $nik);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $message = "NIK sudah terdaftar.";
        } else {
            // Simpan user baru ke kolom username (isi NIK) dan pass
            $stmt = $conn->prepare("INSERT INTO user (username, pass) VALUES (?, ?)");
            $stmt->bind_param("ss", $nik, $password);
            if ($stmt->execute()) {
                $message = "Registrasi berhasil. Silakan login.";
            } else {
                $message = "Registrasi gagal. Silakan coba lagi.";
            }
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register Akun</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #4facfe, #00f2fe);
            display: flex;
            height: 100vh;
            align-items: center;
            justify-content: center;
            margin: 0;
        }

        .register-container {
            background: #ffffff;
            padding: 40px 30px;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }

        .register-container h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #4facfe;
            border: none;
            color: white;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        button:hover {
            background-color: #00c6ff;
        }

        .footer-text {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #777;
        }

        .message {
            text-align: center;
            margin-bottom: 20px;
            color: #d8000c;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>Register Akun</h2>
        <?php if ($message) echo "<div class='message'>$message</div>"; ?>
        <form method="POST" action="">
            <label>NIK</label>
            <input type="text" name="nik" maxlength="16" pattern="\d{16}" required placeholder="Masukkan 16 digit NIK">

            <label>Password</label>
            <input type="password" name="password" required>

            <label>Konfirmasi Password</label>
            <input type="password" name="confirm" required>

            <button type="submit">Register</button>
        </form>
        <div class="footer-text">
            <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
        </div>
        <div class="footer-text">
            © <?= date("Y"); ?> Aplikasi Login
        </div>
    </div>
</body>
</html>
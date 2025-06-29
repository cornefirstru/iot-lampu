<?php
$host = "localhost";
$user = "root"; // default phpMyAdmin user
$pass = ""; // sesuaikan dengan password phpMyAdmin kamu
$db   = "iot_lampu";

// Buat koneksi
$conn = new mysqli($host, $user, $pass, $db);
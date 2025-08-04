<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['email'])) {
    http_response_code(401);
    exit;
}

$email = $_SESSION['email'];
$name = trim($_POST['name']);
$password = trim($_POST['password']);

if ($name === "" || $password === "") {
    http_response_code(400);
    echo "Nama dan password tidak boleh kosong.";
    exit;
}

// Enkripsi password
$password_hashed = password_hash($password, PASSWORD_DEFAULT);

// Jalankan query update
$query = "UPDATE users SET name = ?, password = ? WHERE email = ?";
$stmt = mysqli_prepare($conn, $query);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "sss", $name, $password_hashed, $email);
    mysqli_stmt_execute($stmt);

    // Update session agar navbar juga ikut berubah
    $_SESSION['name'] = $name;

    echo 'success';
} else {
    echo 'prepare failed';
}
?>

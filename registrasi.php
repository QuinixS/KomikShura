<?php
session_start();
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name     = $_POST['name'];
    $email    = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Cek apakah email sudah terdaftar
    $cek = $conn->prepare("SELECT email FROM users WHERE email = ?");
    if (!$cek) {
        die("Query gagal disiapkan: " . $conn->error);
    }

    $cek->bind_param("s", $email);
    $cek->execute();
    $cek->store_result();

    if ($cek->num_rows > 0) {
        echo "Email sudah terdaftar!";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        if (!$stmt) {
            die("Query insert gagal: " . $conn->error);
        }

        $stmt->bind_param("sss", $name, $email, $password);
        if ($stmt->execute()) {
            $_SESSION['email'] = $email;
            $_SESSION['name'] = $name;
            header("Location: index.php");
            exit;
        } else {
            echo "Terjadi kesalahan saat registrasi.";
        }
    }

    $cek->close();
    $conn->close();
}
?>

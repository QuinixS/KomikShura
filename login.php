<?php
include 'koneksi.php';

$loginError = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email    = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT name, password FROM users WHERE email = ?");
    if (!$stmt) {
        die("Query error: " . $conn->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($name, $hashedPassword);
        $stmt->fetch();

        if (password_verify($password, $hashedPassword)) {
            $_SESSION['email'] = $email;
            $_SESSION['name'] = $name;
        
            // Redirect ke halaman sebelumnya kalau ada
            if (isset($_SESSION['login_redirect'])) {
                $redirect = $_SESSION['login_redirect'];
                unset($_SESSION['login_redirect']);
                header("Location: $redirect");
            } else {
                header("Location: index.php");
            }
            exit;
        }
    }
    $_SESSION['login_error'] = "Email atau password salah!";

    $stmt->close();
    $conn->close();
}
?>

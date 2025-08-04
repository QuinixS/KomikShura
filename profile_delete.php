<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['email'])) {
    http_response_code(401);
    exit;
}

$email = $_SESSION['email'];

$query = "DELETE FROM users WHERE email = ?";
$stmt = mysqli_prepare($conn, $query);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);

    session_destroy();
    echo 'deleted';
} else {
    echo 'prepare failed';
}
?>

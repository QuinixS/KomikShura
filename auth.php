<?php
if (!isset($_SESSION['email'])) {
    $_SESSION['login_redirect'] = $_SERVER['REQUEST_URI']; 
    echo "<script>
        alert('Silakan login terlebih dahulu!');
        window.location.href = 'index.php';
    </script>";
    exit;
}
?>

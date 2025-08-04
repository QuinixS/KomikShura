<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['email'])) {
    echo json_encode(['success' => false, 'error' => 'Silahkan Login Terlebih Dahulu']);
    exit;
}

$email = trim($_SESSION['email']);
$title = trim(mysqli_real_escape_string($conn, $_POST['title']));
$image = trim(mysqli_real_escape_string($conn, $_POST['image']));
$genre = trim(mysqli_real_escape_string($conn, $_POST['genre']));
$chapter = trim(mysqli_real_escape_string($conn, $_POST['chapter']));
$link = trim(mysqli_real_escape_string($conn, $_POST['link'] ?? ''));
$action = $_POST['action'] ?? '';

if ($action === 'remove') {
    $delete = mysqli_query($conn, "DELETE FROM bookmarks WHERE user_email = '$email' AND manga_title = '$title'");
    if ($delete && mysqli_affected_rows($conn) > 0) {
        echo json_encode(['success' => true, 'action' => 'removed', 'message' => 'Berhasil dihapus dari bookmark!']);
    } else {
        echo json_encode(['success' => false, 'error' => 'Manga tidak ditemukan di bookmark']);
    }
} elseif ($action === 'add') {
    // CEK dengan prepare statement (lebih akurat)
    $stmt = mysqli_prepare($conn, "SELECT 1 FROM bookmarks WHERE user_email = ? AND manga_title = ?");
    mysqli_stmt_bind_param($stmt, "ss", $email, $title);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        // sudah ada
        echo json_encode(['success' => false, 'error' => 'Manga sudah ada di bookmark']);
    } else {
        // belum ada, insert
        $insert = mysqli_query($conn, "INSERT INTO bookmarks (user_email, manga_title, manga_image, manga_genre, chapter_info, manga_link) 
                               VALUES ('$email', '$title', '$image', '$genre', '$chapter', '$link')");
        if ($insert) {
            echo json_encode(['success' => true, 'action' => 'added', 'message' => 'Berhasil ditambahkan ke bookmark!']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Gagal menambahkan ke bookmark']);
        }
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Permintaan tidak valid']);
}
?>

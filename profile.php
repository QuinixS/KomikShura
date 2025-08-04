<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['email'])) {
    echo json_encode(['status' => 'unauthorized']);
    exit;
}

$email = $_SESSION['email'];
$query = "SELECT * FROM users WHERE email = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Komik Shura - Profil</title>

  <!-- Favicon -->
  <link rel="icon" type="image/png" href="/assets/Favicon.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
  <link rel="stylesheet" href="style.css">
</head>

<body class="bg-light">

<!-- Navbar (bisa kamu paste dari index.php kalau ingin tampil) -->
 <!-- Navbar Start -->
<nav class="navbar navbar-expand-lg bg-body-tertiary py-4 sticky-top">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">Komik Shura</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" 
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <!-- Kiri -->
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="Bookmark.php">Bookmark</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="Latest_Update.php">Latest Update</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Genre
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="Latest_Update.php?genre=Action">Action</a>            </li>
            <li><a class="dropdown-item" href="Latest_Update.php?genre=Adventure">Adventure</a></li>
            <li><a class="dropdown-item" href="Latest_Update.php?genre=Comedy">Comedy</a></li>
            <li><a class="dropdown-item" href="Latest_Update.php?genre=Drama">Drama</a></li>
            <li><a class="dropdown-item" href="Latest_Update.php?genre=Fantasy">Fantasy</a></li>
            <li><a class="dropdown-item" href="Latest_Update.php?genre=Isekai">Isekai</a></li>
            <li><a class="dropdown-item" href="Latest_Update.php?genre=Martial Arts">Martial Arts</a></li>
            <li><a class="dropdown-item" href="Latest_Update.php?genre=Mystery">Mystery</a></li>
            <li><a class="dropdown-item" href="Latest_Update.php?genre=Pyschological">Psychological</a></li>
            <li><a class="dropdown-item" href="Latest_Update.php?genre=Romance">Romance</a></li>
            <li><a class="dropdown-item" href="Latest_Update.php?genre=Slice Of Life">Slice Of Life</a></li>
            <li><a class="dropdown-item" href="Latest_Update.php?genre=Sports">Sports</a></li>
            <li><a class="dropdown-item" href="Latest_Update.php?genre=School Life">School Life</a></li>
            <li><a class="dropdown-item" href="Latest_Update.php?genre=Shounen">Shounen</a></li>           
            <li><a class="dropdown-item" href="Latest_Update.php?genre=Seinen">Seinen</a></li>
          </ul>
        </li>
      </ul>

<!-- Kanan (ikon user + nama kalau login) -->
<ul class="navbar-nav mb-2 mb-lg-0">
  <li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
      <i class="fas fa-user-circle fa-2x me-1"></i>
      <?php if (isset($_SESSION['name'])): ?>
        <span class="d-none d-lg-inline"><?= htmlspecialchars($_SESSION['name']) ?></span>
      <?php endif; ?>
    </a>
    <ul class="dropdown-menu dropdown-menu-end">
      <?php if (isset($_SESSION['email'])): ?>
        <li><a class="dropdown-item" href="profile.php">Profil</a></li>
        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
      <?php else: ?>
        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#loginModal">Login</a></li>
        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#registerModal">Register</a></li>
      <?php endif; ?>
    </ul>
  </li>
</ul>
</nav>
<!-- Navbar End -->


<!-- Bootstrap Modal for Login -->
<?php if (!empty($loginError)): ?>
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      Swal.fire({
        icon: 'error',
        title: '<strong class="text-danger">ERROR 404!</strong>',
        html: '<b>Email atau Password</b> yang kamu masukkan salah!',
        background: '#fff',
        iconColor: '#dc3545', 
        confirmButtonText: 'Oke',
        confirmButtonColor: '#dc3545', 
        customClass: {
          popup: 'swal2-border-radius',
          title: 'fs-4',
          htmlContainer: 'fs-6',
          confirmButton: 'btn btn-danger'
        }
      }).then(() => {
        // Buka kembali modal login setelah alert ditutup
        var loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
        loginModal.show();
      });
    });
  </script>
<?php endif; ?>
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content login-content">
      <div class="modal-header border-0">
        <h2 class="modal-title" id="loginModalLabel">Login</h2>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form id="login-form" method="POST" action="index.php">
  <label for="login-email">Email</label>
  <input type="email" name="email" id="login-email" required />
  <label for="login-password">Password</label>
  <input type="password" name="password" id="login-password" required />
  <button type="submit">Login</button>
</form>

        <p>Don't have an account? <a href="#" data-bs-toggle="modal" data-bs-target="#registerModal">Register Here</a></p>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap Modal for Register -->
<div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content register-content">
      <div class="modal-header border-0">
        <h2 class="modal-title" id="registerModalLabel">Register</h2>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form id="register-form" method="POST" action="registrasi.php">
  <label for="register-username">Username</label>
  <input type="text" name="name" id="register-username" required />
  <label for="register-email">Email</label>
  <input type="email" name="email" id="register-email" required />
  <label for="register-password">Password</label>
  <input type="password" name="password" id="register-password" required />
  <label for="register-confirm-password">Confirm Password</label>
  <input type="password" name="confirm_password" id="register-confirm-password" required />
  <button type="submit">Register</button>
</form>

        <p>Already have an account? <a href="#" data-bs-toggle="modal" data-bs-target="#loginModal">Login here</a></p>
      </div>
    </div>
  </div>
</div>
    <!-- Navbar End -->

    <!-- Hero Section Start -->
    <section class="hero">
      <div class="hero-content">
        <h1>Komik Shura</h1>
        <img src="https://images8.alphacoders.com/135/1354199.jpeg" alt="" /> 
      </div>
    </section> 
    <!-- Hero Section End -->

<div class="container py-5">
  <h2 class="mb-4">Profil Pengguna</h2>
  <form id="profileForm">
    <div class="mb-3">
      <label for="name" class="form-label">Nama</label>
      <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($user['name']) ?>" readonly>
    </div>
    <div class="mb-3">
      <label for="email" class="form-label">Email</label>
      <input type="email" class="form-control" id="email" value="<?= htmlspecialchars($user['email']) ?>" readonly>
    </div>
    <div class="mb-3">
      <label for="password" class="form-label">Password</label>
      <input type="text" class="form-control" id="password" name="password" value="<?= htmlspecialchars($user['password']) ?>" readonly>
    </div>
    <div class="d-flex gap-2">
      <button type="button" class="btn btn-primary" id="editBtn">Edit</button>
      <button type="button" class="btn btn-success d-none" id="confirmBtn">Confirm</button>
      <button type="button" class="btn btn-danger ms-auto" id="deleteBtn">Hapus Akun</button>
    </div>
  </form>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function () {
  $('#editBtn').on('click', function () {
    $('#name, #password').prop('readonly', false);
    $('#editBtn').addClass('d-none');
    $('#confirmBtn').removeClass('d-none');
  });

  $('#confirmBtn').on('click', function () {
    $.ajax({
      type: 'POST',
      url: 'profile_update.php',
      data: {
        name: $('#name').val(),
        password: $('#password').val()
      },
      success: function (response) {
        if (response.trim() === 'success') {
          Swal.fire('Berhasil', 'Profil berhasil diperbarui.', 'success').then(() => location.reload());
        } else {
          Swal.fire('Gagal', response, 'error');
        }
      }
    });
  });

  $('#deleteBtn').on('click', function () {
    Swal.fire({
      title: 'Yakin ingin menghapus akun?',
      text: 'Tindakan ini tidak dapat dibatalkan!',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Ya, hapus',
      cancelButtonText: 'Batal',
      confirmButtonColor: '#d33'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          type: 'POST',
          url: 'profile_delete.php',
          success: function (response) {
            if (response.trim() === 'deleted') {
              window.location.href = 'index.php';
            } else {
              Swal.fire('Gagal', 'Akun tidak berhasil dihapus.', 'error');
            }
          }
        });
      }
    });
  });
});
</script>

</body>
</html>

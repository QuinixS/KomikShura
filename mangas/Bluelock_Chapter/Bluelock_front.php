<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Komik Shura</title>
    <!-- Custom CSS -->
    <link rel="icon" type="image/png" href="/assets/Favicon.png">
    <link rel="stylesheet" href="../../style.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Boxicons -->
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css" />
    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet" />
  </head>
  <body>
    <!-- Navbar Start -->
<nav class="navbar navbar-expand-lg bg-body-tertiary py-4 sticky-top">
  <div class="container-fluid">
    <a class="navbar-brand" href="../../index.php">Komik Shura</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" 
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <!-- Kiri -->
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="../../Bookmark.php">Bookmark</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../../Latest_Update.php">Latest Update</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Genre
          </a>
           <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="../../Latest_Update.php?genre=Action">Action</a>            </li>
            <li><a class="dropdown-item" href="../../Latest_Update.php?genre=Adventure">Adventure</a></li>
            <li><a class="dropdown-item" href="../../Latest_Update.php?genre=Comedy">Comedy</a></li>
            <li><a class="dropdown-item" href="../../Latest_Update.php?genre=Drama">Drama</a></li>
            <li><a class="dropdown-item" href="../../Latest_Update.php?genre=Fantasy">Fantasy</a></li>
            <li><a class="dropdown-item" href="../../Latest_Update.php?genre=Isekai">Isekai</a></li>
            <li><a class="dropdown-item" href="../../Latest_Update.php?genre=Martial Arts">Martial Arts</a></li>
            <li><a class="dropdown-item" href="../../Latest_Update.php?genre=Mystery">Mystery</a></li>
            <li><a class="dropdown-item" href="../../Latest_Update.php?genre=Pyschological">Psychological</a></li>
            <li><a class="dropdown-item" href="../../Latest_Update.php?genre=Romance">Romance</a></li>
            <li><a class="dropdown-item" href="../../Latest_Update.php?genre=Slice Of Life">Slice Of Life</a></li>
            <li><a class="dropdown-item" href="../../Latest_Update.php?genre=Sports">Sports</a></li>
            <li><a class="dropdown-item" href="../../Latest_Update.php?genre=School Life">School Life</a></li>
            <li><a class="dropdown-item" href="../../Latest_Update.php?genre=Shounen">Shounen</a></li>           
            <li><a class="dropdown-item" href="../../Latest_Update.php?genre=Seinen">Seinen</a></li>
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
        <li><a class="dropdown-item" href="#">Profil</a></li>
        <li><a class="dropdown-item" href="../../logout.php">Logout</a></li>
      <?php else: ?>
        <li><a class="dropdown-item" href="../../index.php?login=true">Login</a></li>
        <li><a class="dropdown-item" href="../../index.php?register=true">Register</a></li>
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
      <form id="login-form" method="POST" action="../../login.php">
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
      <form id="register-form" method="POST" action="../../registrasi.php">
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

    <!-- Manga Detail Section Start -->
     <section class="manga-detail">
      <div class="manga-detail-content">
        <div class="box">
          <div class="left">
            <img src="../../assets/Bluelock.jpg" alt="" />
          </div>
          <div class="right">
            <div class="genres">
            <span>Sports</span><span>Drama</span><span>School Life</span><span>Slife of Life</span>
            </div>
            <h4>Blue Lock</h4>
            <h5>
            Yoichi Isagi lost the opportunity to go to the national high school championships because he passed to his teammate who missed instead of shooting himself. Isagi is one of 300 U-18 strikers chosen by Jinpachi Ego, a man who was hired by the Japan Football Association after the 2018 FIFA World Cup, to guide Japan to winning the World Cup by destroying Japanese football. Ego's plan is to isolate the 300 strikers into a prison-like institution called "Blue Lock", in order to create the world's biggest "egotist"/striker, which has been lacking in Japanese football.
            </h5>
            <div class="detail-button">
              <div class="wishlist"> 
              <a href="#" 
                      class="wishlist-icon"
                      data-title="Blue Lock"
                      data-image="assets/Bluelock.jpg"
                      data-genre="Sports, Drama, School Life, Slife of Life"
                      data-chapter="Yoichi Isagi lost the opportunity to go to the national high school championships because he passed to his teammate who missed instead of shooting himself. Isagi is one of 300 U-18 strikers chosen by Jinpachi Ego."
                      data-link="mangas/Bluelock_Chapter/Bluelock_front.php">
                      <i class="bx bx-bookmark"></i>
              </a>
              </div>
              <button>First Chapter</button>
              <button>Latest Chapter</button>
          </div>
          </div>
        </div>
      </div>
      <div class="manga-chapter-content">
        <div id="chapters"></div> <!-- Daftar chapter akan muncul di sini -->
    </div>
    
    <!-- Manga Detail Section End -->

  </body>
  
  <script src="../../javascripts_file/Bookmark.js"></script>
  <script src="Front.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>  
</html>

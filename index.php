<?php
session_start();
$loginError = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email'])) {
    include 'login.php';

    if (isset($_SESSION['login_error'])) {
        $loginError = $_SESSION['login_error'];
        unset($_SESSION['login_error']);
    }
}
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
    <link rel="stylesheet" href="style.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Boxicons -->
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css" />
    <!-- Google Fonts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet" />
  </head>
  <body>
<!-- Navbar Start -->
<nav class="navbar navbar-expand-lg bg-body-tertiary py-4 sticky-top">
  <div class="container-fluid">
    <a class="navbar-brand" href="">Komik Shura</a>
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

    <!-- Popular Section Start -->
<section class="popular">
  <h1>Popular Titles</h1>
  <div id="popularCarousel" class="carousel slide" data-bs-ride="carousel">
    <!-- Carousel Indicators (3 Dots) -->
    <div class="carousel-indicators ">
      <button type="button" data-bs-target="#popularCarousel" data-bs-slide-to="0" class="active" aria-current="true"></button>
      <button type="button" data-bs-target="#popularCarousel" data-bs-slide-to="1"></button>
      <button type="button" data-bs-target="#popularCarousel" data-bs-slide-to="2"></button>
    </div>

    <div class="carousel-inner">
      <!-- Slide 1 (First 2 Cards) -->
      <div class="carousel-item active">
        <div class="popular-content">
          <a href="./mangas/Frieren_Chapter/Frieren_front.php" class="box-link">
            <div class="box">
              <div class="left"><img src="./assets/Frieren.jpg" alt="Frieren" /></div>
              <div class="right">
                <div class="genres">
                  <span>Adventure</span><span>Fantasy</span><span>Comedy</span><span>Drama</span>
                </div>
                <h4>Sousou no Frieren</h4>
                <h5>The adventure is over but life goes on for an elf mage just beginning to learn what living is all about. Elf mage Frieren and her courageous fellow adventurers have defeated the Demon King and brought peace to the land. With the great struggle over, they all go their separate ways to live a quiet life. But as an elf, Frieren, nearly immortal, will long outlive the rest of her former party. How will she come to terms with the mortality of her friends? How can she find fulfillment in her own life, and can she learn to understand what life means to the humans around her? Frieren begins a new journey to find the answer.</h5>
              </div>
            </div>
          </a>
     <a href="./mangas/Yotsuba_Chapter/Yotsuba_front.php" class="box-link">
  <div class="box">
    <div class="left"><img src="./assets/Yotsuba.jpg" alt="Yotsuba" /></div>
    <div class="right">
      <div class="genres">
        <span>Comedy</span>
        <span>Drama</span>
        <span>Slice Of Life</span>
      </div>
      <h4>Yotsuba&!</h4>
      <h5>Yotsuba is a strange little girl with a big personality! Even in the most trivial, unremarkable encounters, Yotsuba's curiosity and enthusiasm quickly turns the everyday into the extraordinary!</h5>
    </div>
  </div>
</a>

        </div>
      </div>

      <!-- Slide 2 (Next 2 Cards) -->
      <div class="carousel-item">
        <div class="popular-content">
        <a href="./mangas/Flower_Chapter/Flower_front.php" class="box-link">
          <div class="box">
            <div class="left"><img src="./assets/Flower.jpg" alt="Flower" /></div>
            <div class="right">
              <div class="genres">
                <span>Romance</span>
                <span>Comedy</span>
                <span>Drama</span>
                <span>School Life</span>
                <span>Slice Of Life</span>
              </div>
              <h4>The Fragrant Flower Blooms with Dignity</h4>
              <h5>In a certain place, there are two neighboring high schools. Chidori High School, a bottom-feeder boys' school where idiots gather, and Kikyo Girls' School, a well-established girls' school. Rintaro Tsumugi, a strong and quiet second year student at Chidori High School, meets Kaoruko Waguri, a girl who comes as a customer while helping out at his family's cake shop. Rintaro feels comfortable spending time with Kaoruko, but she is a student at Kikyo Girls, a neighboring school that thoroughly dislikes Chidori High.</h5>
            </div>
          </div>
        </a>
        <a href="./mangas/Vindland_Chapter/Vindland_front.php" class="box-link">
          <div class="box">
            <div class="left"><img src="./assets/Vindland Saga.jpg" alt="Chainsaw Man" /></div>
            <div class="right">
              <div class="genres"> <span>Action</span>
                <span>Adventure</span>
                <span>Drama</span>
                <span>Historical</span>
                <span>Seinen</span>
                <span>Romance</span></div>
              <h4>Vindland Saga</h4>
              <h5>As a child, Thorfinn sat at the feet of the great Leif Ericson and thrilled to wild tales of a land far to the west. But his youthful fantasies were shattered by a mercenary raid. Raised by the Vikings who murdered his family, Thorfinn became a terrifying warrior, forever seeking to kill the band's leader, Askeladd, and avenge his father. Sustaining Thorfinn through his ordeal are his pride in his family and his dreams of a fertile westward land, a land without war or slavery… the land Leif called Vinland.</h5>
            </div>
          </div>
       </a>
        </div>
      </div>

      <!-- Slide 3 (Last 2 Cards) -->
      <div class="carousel-item">
        <div class="popular-content">
        <a href="./mangas/Climb_Chapter/Climb_front.php" class="box-link">
          <div class="box">
            <div class="left"><img src="./assets/Climb.jpg" alt="Death Note" /></div>
            <div class="right">
              <div class="genres"> 
                <span>Drama</span>
                <span>Psychological</span>
                <span>Seinen</span>
                <span>Sports</span>
              </div>
              <h4>The Climber</h4>
              <h5>On his first day of transferring to a new high school, a loner named Mori Buntarou, is cajoled by a classmate into climbing the school building. Despite knowing that one misstep could send him spiraling to his death, he moves forward, and upon finally reaching the top, Mori experiences a sense of fulfillment. That feeling, which seems to be telling him, "You're alive!" gives birth to an adrenaline for rock-climbing.</h5>
            </div>
          </div>
        </a>
        <a href="./mangas/Relife_Chapter/Relife_front.php" class="box-link">
          <div class="box">
            <div class="left"><img src="./assets/Relife.jpg" alt="Relife" /></div>
            <div class="right">
              <div class="genres">  
                <span>Romance</span>
                <span>Comedy</span>
                <span>Drama</span>
                <span>School Life</span></div>
              <h4>ReLIFE</h4>
              <h5>Kaizaki Arata is a 27-year-old unemployed man who, after quitting his last job after three months, has failed every interview since. Enter Yoake Ryou; representative of the ReLIFE Organization. He offers Kaizaki a pill that changes his appearance to that of his 17-year-old self; thus, Kaizaki becomes the subject of a one-year experiment in which he begins his life as a third year high school student once again.</h5>
            </div>
          </div>
        </a>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- Popular Section End -->

    <!-- Mangas Section Start -->
    <section class="mangas">
      <h1>All Mangas</h1>
      <div class="search-sort-box">
        <div class="search">
          <form class="search-form">
            <input type="text" placeholder="Search..." class="search-input" />
            <button type="submit" class="search-button">Search</button>
          </form>
        </div>
      </div>
      <div class="mangas-content">
    <div class="box" data-link="mangas/Bluelock_Chapter/Bluelock_front.php">
  <div class="left">
    <img src="./assets/Bluelock.jpg" alt="Blue Lock" />
  </div>
  <div class="right">
    <div class="genres">
      <span>Sports</span>
      <span>Drama</span>
      <span>School Life</span>
      <span>Slice Of Life</span>
    </div>
    <h4>Blue Lock</h4>
    <div class="rating">
      <p>Yoichi Isagi lost the opportunity to go to the national high school championships because he passed to his teammate who missed instead of shooting himself. Isagi is one of 300 U-18 strikers chosen by Jinpachi Ego</p>
    </div>
  </div>
  <div class="wishlist">
    <a href="#"
       class="wishlist-icon"
       data-title="Blue Lock"
       data-image="./assets/Bluelock.jpg"
       data-genre="Sports, Drama, School Life, Slice Of Life"
       data-chapter="Yoichi Isagi lost the opportunity to go to the national high school championships because he passed to his teammate who missed instead of shooting himself. Isagi is one of 300 U-18 strikers chosen by Jinpachi Ego"
       data-link="mangas/Bluelock_Chapter/Bluelock_front.php">
      <i class="bx bx-bookmark"></i>
    </a>
  </div>
</div>

        <div class="box" data-link="mangas/Saitou_Chapter/Saitou_front.php">
          <div class="left">
            <img src="./assets/Saitou.jpg" alt="" />
          </div>
          <div class="right">
            <div class="genres">
              <span>Comedy</span>
              <span>Seinen</span>
              <span>Advanture</span>
              <span>Fantasy</span>
              <span>Isekai</span>
            </div>
            <h4>Handyman Saitou In Another World</h4>
            <div class="rating">
              <p>Handyman Saitou has never been anyone special. All his life, he’s had average grades, ordinary athletic skill, a commonplace job… But his unremarkable path takes a turn when he wakes up in another world.</p>
            </div>
          </div>
          <div class="wishlist">
          <a href="#"
            class="wishlist-icon"
            data-title="Handyman Saitou In Another World"
            data-image="./assets/Saitou.jpg"
            data-genre="Comedy, Seinen, Advanture, Fantasy, Isekai"
            data-chapter="Handyman Saitou has never been anyone special. All his life, he’s had average grades, ordinary athletic skill, a commonplace job… But his unremarkable path takes a turn when he wakes up in another world."
            data-link="mangas/Saitou_Chapter/Saitou_front.php">
            <i class="bx bx-bookmark"></i>
          </a>
          </div>
        </div>
        <div class="box" data-link="mangas/Flower_Chapter/Flower_front.php">
          <div class="left">
            <img src="./assets/Flower.jpg" alt="" />
          </div>
          <div class="right">
            <div class="genres">
              <span>Romance</span>
              <span>School Life</span>
              <span>Shoujo</span>
            </div>
            <h4>The Fragrant Flower Blooms With Dignity</h4>
            <div class="rating">
              <p>In a certain place, there are two neighboring high schools. Chidori High School, a bottom-feeder boys' school where idiots gather, and Kikyo Girls' School, a well-established girls' school.</p>
            </div>
          </div>
          <div class="wishlist">
          <a href="#" 
            class="wishlist-icon"
            data-title="The Fragrant Flower Blooms with Dignity"
            data-image="assets/Flower.jpg"
            data-genre="Romance, Comedy, Drama, School Life, Slice Of Life"
            data-chapter="In a certain place, there are two neighboring high schools. Chidori High School, a bottom-feeder boys' school where idiots gather, and Kikyo Girls' School, a well-established girls' school."
            data-link="mangas/Flower_Chapter/Flower_front.php">
            <i class="bx bx-bookmark"></i>
          </a>
        </div>
        </div>
        <div class="box" data-link="mangas/Vindland_Chapter/Vindland_front.php">
          <div class="left">
            <img src="./assets/Vindland Saga.jpg" alt="" />
          </div>
          <div class="right">
            <div class="genres">
                <span>Adventure</span>
                <span>Drama</span>
                <span>Historical</span>
                <span>Seinen</span>
                <span>Romance</span>
            </div>
            <h4>Vindland Saga</h4>
            <div class="rating">
              <p>As a child, Thorfinn sat at the feet of the great Leif Ericson and thrilled to wild tales of a land far to the west. But his youthful fantasies were shattered by a mercenary raid.</p>
            </div>
          </div>
          <div class="wishlist">
          <a href="#" 
            class="wishlist-icon"
            data-title="Vindland Saga"
            data-image="assets/Vindland Saga.jpg"
            data-genre="Action, Advanture, Drama, Historical, Seinen, Romance"
            data-chapter="As a child, Thorfinn sat at the feet of the great Leif Ericson and thrilled to wild tales of a land far to the west. But his youthful fantasies were shattered by a mercenary raid."
            data-link="mangas/Vinland_Chapter/Vindland_front.php">
              <i class="bx bx-bookmark"></i>
            </a>
          </div>
        </div>
        <div class="box" data-link="mangas/Boruto_Chapter/Boruto_front.php">
          <div class="left">
            <img src="./assets/Boruto.jpg" alt="" />
          </div>
          <div class="right">
            <div class="genres">
              <span>Adventure</span>
              <span>Fantasy</span>
              <span>Martial Arts</span>
              <span>Shounen</span>
            </div>
            <h4>Boruto: Two Blue Vortex</h4>
            <div class="rating">
              <p>With everyone's memories having been altered, Boruto finds himself being hunted by his own village. After escaping with Sasuke, what future awaits Boruto...?</p>
            </div>
          </div>
          <div class="wishlist">
          <a href="#" 
            class="wishlist-icon"
            data-title="Boruto: Two Blue Vortex"
            data-image="assets/Boruto.jpg"
            data-genre="Advanture, Fantasy, Martial Arts, Shounen"
            data-chapter="With everyone's memories having been altered, Boruto finds himself being hunted by his own village. After escaping with Sasuke, what future awaits Boruto...?"
            data-link="mangas/Boruto_Chapter/Boruto_front.php">
            <i class="bx bx-bookmark"></i>
          </a>
          </div>
        </div>
        <div class="box" data-link="mangas/Shikkaku_Chapter/Shikkaku_front.php">
          <div class="left">
            <img src="././assets/Isekai Shikkaku.jpg" alt="" />
          </div>
          <div class="right">
            <div class="genres">
              <span>Comedy</span>
              <span>Fantasy</span>
              <span>Isekai</span>
              <span>Romance</span>
              <span>Seinen</span>
            </div>
            <h4>Isekai Shikkaku</h4>
            <div class="rating">
              <p>A second life in another world with cute girls by your side and video gamey powers—sounds like a dream, right? Not so for a certain melancholy author, who would quite literally rather drop dead.</p>
            </div>
          </div>
          <div class="wishlist">
          <a href="#" 
            class="wishlist-icon"
            data-title="Isekai Shukkaku"
            data-image="assets/Isekai Shikkaku.jpg"
            data-genre="Comedy, Fantasy, Isekai, Romance, Seinen"
            data-chapter="A second life in another world with cute girls by your side and video gamey powers—sounds like a dream, right? Not so for a certain melancholy author, who would quite literally rather drop dead."
            data-link="mangas/Shikkaku_Chapter/Shikkaku_front.php">
            <i class="bx bx-bookmark"></i>
          </a>
          </div>
        </div>
        <div class="box" data-link="mangas/Bocchi_Chapter/Bocchi_front.php">
          <div class="left">
            <img src="./assets/Bocchi.jpg" alt="" />
          </div>
          <div class="right">
            <div class="genres">
              <span>Comedy</span>
              <span>4-Koma</span>
              <span>Slice Of Life</span>
              <span>Music</span>
            </div>
            <h4>Bocchi The Rock</h4>
            <div class="rating">
              <p>Hitori Gotou is a high school girl who's starting to learn to play the guitar because she dreams of being in a band, but she's so shy that she hasn't made a single friend. However her dream might come true after she meets Nijika Ijichi.</p>
            </div>
          </div>
          <div class="wishlist">
          <a href="#" 
            class="wishlist-icon"
            data-title="Bocchi The Rock"
            data-image="assets/Bocchi.jpg"
            data-genre="Comedy, 4-Koma, Slice Of Life, Music"
            data-chapter="Hitori Gotou is a high school girl who's starting to learn to play the guitar because she dreams of being in a band, but she's so shy that she hasn't made a single friend. However her dream might come true after she meets Nijika Ijichi."
            data-link="mangas/Bocchi_Chapter/Bocchi_front.php">
            <i class="bx bx-bookmark"></i>
          </a>
          </div>
        </div>
        <div class="box" data-link="mangas/Climb_Chapter/Climb_front.php">
          <div class="left">
            <img src="./assets/Climb.jpg" alt="" />
          </div>
          <div class="right">
            <div class="genres">
                <span>Drama</span>
                <span>Psychological</span>
                <span>Seinen</span>
                <span>Sports</span>
            </div>
            <h4>The Climber</h4>
            <div class="rating">
              <p>On his first day of transferring to a new high school, a loner named Mori Buntarou, is cajoled by a classmate into climbing the school building. Despite knowing that one misstep could send him spiraling to his death, he moves forward</p>
            </div>
          </div>
          <div class="wishlist">
          <a href="#" 
            class="wishlist-icon"
            data-title="The Climber"
            data-image="assets/Climb.jpg"
            data-genre="Drama, Pyschological, Seinen, Sports"
            data-chapter="On his first day of transferring to a new high school, a loner named Mori Buntarou, is cajoled by a classmate into climbing the school building. Despite knowing that one misstep could send him spiraling to his death, he moves forward"
            data-link="mangas/Climb_Chapter/Climb_front.php">
            <i class="bx bx-bookmark"></i>
          </a>
          </div>
        </div>
        <div class="box" data-link="mangas/Relife_Chapter/Relife_front.php">
          <div class="left">
            <img src="./assets/Relife.jpg" alt="" />
          </div>
          <div class="right">
            <div class="genres">
                <span>Romance</span>
                <span>Comedy</span>
                <span>Drama</span>
                <span>Slice Of Life</span>
            </div>
            <h4>ReLIFE</h4>
            <div class="rating">
              <p>Kaizaki Arata is a 27-year-old unemployed man who, after quitting his last job after three months, has failed every interview since. Enter Yoake Ryou; representative of the ReLIFE Organization.</p>
            </div>
          </div>
          <div class="wishlist">
          <a href="#" 
            class="wishlist-icon"
            data-title="ReLIFE"
            data-image="assets/Relife.jpg"
            data-genre="Romance, Comedy, Drama, Slice of Life"
            data-chapter="Kaizaki Arata is a 27-year-old unemployed man who, after quitting his last job after three months, has failed every interview since. Enter Yoake Ryou; representative of the ReLIFE Organization."
            data-link="mangas/Relife_Chapter/Relife_front.php">
            <i class="bx bx-bookmark"></i>
          </a>
         </div>
        </div>
        <div class="box" data-link="mangas/Frieren_Chapter/Frieren_front.php">
          <div class="left">
            <img src="./assets/Frieren.jpg" alt="" />
          </div>
          <div class="right">
            <div class="genres">
                <span>Action</span>
                <span>Fantasy</span>
                <span>Comedy</span>
                <span>Drama</span>
            </div>
            <h4>Sousou no Frieren</h4>
            <div class="rating">
              <p>The adventure is over but life goes on for an elf mage just beginning to learn what living is all about. Elf mage Frieren and her courageous fellow adventurers have defeated the Demon King and brought peace to the land.</p>
            </div>
          </div>
          <div class="wishlist">
          <a href="#" 
                class="wishlist-icon"
                data-title="Sousou no Frieren"
                data-image="assets/Frieren.jpg"
                data-genre="Adventure, Fantasy"
                data-chapter="The adventure is over but life goes on for an elf mage just beginning to learn what living is all about. Elf mage Frieren and her courageous fellow adventurers have defeated the Demon King and brought peace to the land. "
                data-link="mangas/Frieren_Chapter/Frieren_front.php">
                <i class="bx bx-bookmark"></i>
              </a>
          </div>
        </div>
        <div class="box" data-link="mangas/Dungeon_Chapter/Dungeon_front.php">
          <div class="left">
            <img src="./assets/Doungen_Meshi.jpg" alt="" />
          </div>
          <div class="right">
            <div class="genres">
              <span>Action</span>
              <span>Comedy</span>
              <span>Drama</span>
              <span>Fantasy</span>
            </div>
            <h4>Doungen Meshi</h4>
            <div class="rating">
              <p>After his sister is devoured by a dragon and losing all their supplies in a failed dungeon raid, Laios and his party are determined to save his sister before she gets digested. Completely broke and having to resort to eating monsters as food</p>
            </div>
          </div>
          <div class="wishlist">
          <a href="#" 
                class="wishlist-icon"
                data-title="Dungeon Meshi"
                data-image="assets/Doungen_Meshi.jpg"
                data-genre="Action, Comedy, Drama, Fantasy"
                data-chapter="After his sister is devoured by a dragon and losing all their supplies in a failed dungeon raid, Laios and his party are determined to save his sister before she gets digested. Completely broke and having to resort to eating monsters as food"
                data-link="mangas/Dungeon_Chapter/Dungeon_front.php">
                <i class="bx bx-bookmark"></i>
              </a>
          </div>
        </div>
        <div class="box" data-link="mangas/Blue_Chapter/Blue_front.php">
          <div class="left">
            <img src="./assets/Blue_Box.jpg" alt="" />
          </div>
          <div class="right">
            <div class="genres">
              <span>Comedy</span>
              <span>Romance</span>
              <span>Drama</span>
              <span>School Life</span>
              <span>Sports</span>
            </div>
            <h4>Blue Box</h4>
            <div class="rating">
              <p>Taiki Inomata is on the boys' badminton team at sports powerhouse Eimei Junior and Senior High. He's in love with basketball player Chinatsu Kano, the older girl he trains alongside every morning in the gym. </p>
            </div>
          </div>
          <div class="wishlist">
          <a href="#" 
                class="wishlist-icon"
                data-title="Blue Box"
                data-image="assets/Blue_Box.jpg"
                data-genre="Comedy, Romance, Drama, School Life, Sports"
                data-chapter="Taiki Inomata is on the boys' badminton team at sports powerhouse Eimei Junior and Senior High. He's in love with basketball player Chinatsu Kano, the older girl he trains alongside every morning in the gym."
                data-link="mangas/Blue_Chapter/Blue_front.php">
                <i class="bx bx-bookmark"></i>
              </a>
          </div>
        </div>
        <div class="box" data-link="mangas/Yotsuba_Chapter/Yotsuba_front.php">
          <div class="left">
            <img src="./assets/Yotsuba.jpg" alt="" />
          </div>
          <div class="right">
            <div class="genres">
                <span>Comedy</span>
                <span>Drama</span>
                <span>Slice of Life</span>
            </div>
            <h4>Yotsuba&!</h4>
            <div class="rating">
              <p>Yotsuba is a strange little girl with a big personality! Even in the most trivial, unremarkable encounters, Yotsuba's curiosity and enthusiasm quickly turns the everyday into the extraordinary!</p>
            </div>
          </div>
          <div class="wishlist">
          <a href="#" 
            class="wishlist-icon"
            data-title="Yotsuba&!"
            data-image="assets/Yotsuba.jpg"
            data-genre="Comedy, Drama, Slice of Life"
            data-chapter="Yotsuba is a strange little girl with a big personality! Even in the most trivial, unremarkable encounters, Yotsuba's curiosity and enthusiasm quickly turns the everyday into the extraordinary!"
            data-link="mangas/Yotsuba_Chapter/Yotsuba_front.php">
            <i class="bx bx-bookmark"></i>
          </a>
        </div>
        </div>
        <div class="box" data-link="mangas/Idol_Chapter/Idol_front.php">
          <div class="left">
            <img src="./assets/Idol.jpg" alt="" />
          </div>
          <div class="right">
            <div class="genres">
                <span>Drama</span>
                <span>Romance</span>
                <span>Comedy</span>
                </div>
            <h4>Damedol to Sekai ni Hitori Dake no Fan</h4>
            <div class="rating">
              <p> Urumin is an idol, but she can't sing or dance well, and she's got a dishonest character. Kimiya is the only fan in the world who likes her.</p>
            </div>
          </div>
          <div class="wishlist">
          <a href="#" 
            class="wishlist-icon"
            data-title="Damedol to Sekai ni Hitori Dake no Fan"
            data-image="assets/idol.jpg"
            data-genre="Drama, Romance, Comedy"
            data-chapter="Urumin is an idol, but she can't sing or dance well, and she's got a dishonest character. Kimiya is the only fan in the world who likes her."
            data-link="mangas/Idol_Chapter/Idol_front.php">
            <i class="bx bx-bookmark"></i>
          </a>
          </div>
        </div>
        <div class="box" data-link="mangas/Extra_Chapter/Extra_front.php">
          <div class="left">
            <img src="./assets/The_Extra.jpg" alt="" />
          </div>
          <div class="right">
            <div class="genres">
                <span>Adventure</span>
                <span>Isekai</span>
                <span>Fantasy</span>
                <span>Romance</span>
                <span>School Life</span>
            </div>
            <h4>The Extra’s Academy Survival Guide</h4>
            <div class="rating">
              <p>Ed Rothstaylor is a third-rate villain in a game, disowned by his family and kicked out of the dormitory for his misdeeds. One day, our main character wakes up as this very Ed</p>
            </div>
          </div>
          <div class="wishlist">
          <a href="#" 
            class="wishlist-icon"
            data-title="The Extra’s Academy Survival Guide"
            data-image="assets/The_Extra.jpg"
            data-genre="Advanture, Isekai, Fantasy, Romance, School Life"
            data-chapter="Ed Rothstaylor is a third-rate villain in a game, disowned by his family and kicked out of the dormitory for his misdeeds. One day, our main character wakes up as this very Ed"
            data-link="mangas/Extra_Chapter/Extra_front.php">
            <i class="bx bx-bookmark"></i>
          </a>
          </div>
        </div>
      </div>
      <div class="pagination">
      <button id="prevBtn">Previous</button>
      <span id="pageNumber">1</span>
      <button id="nextBtn">Next</button>
    </div>
    </section> 
  </body>

  <!-- Check jika di mangas dia mo login -->
  <?php if (isset($_GET['login'])): ?>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
        loginModal.show();
    });
</script>
<?php elseif (isset($_GET['register'])): ?>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var registerModal = new bootstrap.Modal(document.getElementById('registerModal'));
        registerModal.show();
    });
</script>
<?php endif; ?>

<!-- Script File -->
  <script src="javascripts_file/search_pagination.js"></script>
  <script src="javascripts_file/search.js"></script>
  <script src="javascripts_file/wishlist.js"></script>
  <script src="javascripts_file/Pagination.js"></script>
  <script src="javascripts_file/Bookmark.js"></script>
  <script src="javascripts_file/box.js"></script>
   <script src="javascripts_file/chatbot.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</html>

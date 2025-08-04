<?php
session_start();
include 'auth.php';
include 'koneksi.php'; 

if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit;
}

$email = mysqli_real_escape_string($conn, $_SESSION['email']);
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet" />
  </head>
  <body>
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
            <li><a class="dropdown-item" href="Latest_Update.php?genre=Advanture">Adventure</a></li>
            <li><a class="dropdown-item" href="Latest_Update.php?genre=Comedy">Comedy</a></li>
            <li><a class="dropdown-item" href="Latest_Update.php?genre=Drama">Drama</a></li>
            <li><a class="dropdown-item" href="Latest_Update.php?genre=Fantasy">Fantasy</a></li>
            <li><a class="dropdown-item" href="Latest_Update.php?genre=Isekai">Isekai</a></li>
            <li><a class="dropdown-item" href="Latest_Update.php?genre=Martial Arts">Martial Arts</a></li>
            <li><a class="dropdown-item" href="Latest_Update.php?genre=Mystery">Mystery</a></li>
            <li><a class="dropdown-item" href="Latest_Update.php?genre=Pyschological">Psychological</a></li>
            <li><a class="dropdown-item" href="Latest_Update.php?genre=Romance">Romance</a></li>
            <li><a class="dropdown-item" href="Latest_Update.php?genre=Slife Of Life">Slice Of Life</a></li>
            <li><a class="dropdown-item" href="Latest_Update.php?genre=Sports">Sports</a></li>
            <li><a class="dropdown-item" href="Latest_Update.php?genre=School Life">School Life</a></li>
            <li><a class="dropdown-item" href="Latest_Update.php?genre=Shounen">Shounen</a></li>           
            <li><a class="dropdown-item" href="Latest_Update.php?genre=Seinen">Seinen</a></li>
          </ul>
        </li>
      </ul>

      <!-- Kanan (ikon user + dropdown login/register) -->
<ul class="navbar-nav mb-2 mb-lg-0">
  <li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
      <i class="fas fa-user-circle fa-2x me-1"></i>
      <?php if (isset($_SESSION['name'])): ?>
        <span><?= htmlspecialchars($_SESSION['name']) ?></span>
      <?php endif; ?>
    </a>
    <ul class="dropdown-menu dropdown-menu-end">
      <?php if (isset($_SESSION['email'])): ?>
        <li><a class="dropdown-item" href="#">Profil</a></li>
        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
      <?php else: ?>
        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#loginModal">Login</a></li>
        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#registerModal">Register</a></li>
      <?php endif; ?>
    </ul>
  </li>
</ul>

    </div>
  </div>
</nav>  
      <!-- Hero Section Start -->
    <section class="hero">
      <div class="hero-content">
        <h1>Komik Shura</h1>
        <img src="https://images8.alphacoders.com/135/1354199.jpeg" alt="" /> 
      </div>
    </section>
      
  

        <section class="mangas">
          <h1>Your Bookmark</h1>
            <div class="mangas-content">
            <?php
$query = "SELECT * FROM bookmarks WHERE user_email = '$email'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
?>
    <div class="box" data-link="<?= htmlspecialchars($row['manga_link']) ?>">
      <div class="left">
        <img src="<?= htmlspecialchars($row['manga_image']) ?>" alt="<?= htmlspecialchars($row['manga_title']) ?>" />
      </div>
      <div class="right">
      <div class="genres">
  <?php
    $genres = explode(',', $row['manga_genre']); // Pecah berdasarkan koma
    foreach ($genres as $genre) {
        echo '<span>' . htmlspecialchars(trim($genre)) . '</span>';
    }
  ?>
</div>
        <h4><?= htmlspecialchars($row['manga_title']) ?></h4>
        <div class="rating">
          <p><?= htmlspecialchars($row['chapter_info']) ?></p>
        </div>
      </div>
      <div class="wishlist">
        <a href="#" class="wishlist-icon active" data-title="<?= htmlspecialchars($row['manga_title']) ?>"><i class="bx bxs-bookmark"></i></a>
      </div>
    </div>
<?php
    }
} else {
    echo "<p style='text-align:center; font-weight:bold;'>Belum ada bookmark.</p>";
}
?>

              </div>
            </div>
            <div class="pagination">
            <button id="prevBtn">Previous</button>
            <span id="pageNumber">1</span>
            <button id="nextBtn">Next</button>
          </div>
          </section> 
    <!-- My Library Section End -->
  </body>
   <!-- Scrips js -->

  <script src="javascripts_file/Pagination_Bookmark.js"></script>
  <script src="javascripts_file/Bookmark.js"></script>
  <script src="javascripts_file/box.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</html>

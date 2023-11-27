<?php
session_start(); // Ensure session is started

// Check if user is authenticated
if (!isset($_SESSION['valid'])) {
    header("Location: login.php"); // Redirect to login.php since index.php doesn't exist
    exit;
}

include "dbconfig.php"; // Include database configuration
$con = mysqli_connect($server, $login, $password, $dbname); // Establish a database connection

// Check if ID is set in the session, redirect if not
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

// Fetch user details based on the user's ID
$id = $_SESSION['id'];
$query = mysqli_query($con, "SELECT * FROM USERS WHERE id = $id");

// Initialize variables
$res_Uname = "";
$res_email = "";
$res_role = "";
$userFound = false;

while ($result = mysqli_fetch_assoc($query)) {
    $res_Uname = $result['username'];
    $res_email = $result['email'];
    $res_role = $result['role'];
    $userFound = true; // Mark that user is found
}

// If user is not found, redirect to login with an error
if (!$userFound) {
    $_SESSION['error'] = "User not found. Please login again.";
    header("Location: login.php");
    exit;
}
function getGreeting(){
    $hour = date('H');
    if($hour < 12){
        return "Good Morning";
    } elseif($hour < 18){
        return "Good Afternoon";
    } else {
        return "Good Evening";
    }
}
?>

<!DOCTYPE html>
<html lang="en"> 
  <head><script src="js/color-modes.js"></script>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.118.2">
    <title>Music Box</title>
    <link rel="canonical" href="https://getbootstrap.com/docs/5.3/examples/blog-rtl/">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@docsearch/css@3">
    <link href="css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Amiri:wght@400;700&amp;display=swap" rel="stylesheet">
    <link href="css/blog.rtl.css" rel="stylesheet">
  </head>
  <body>
    <svg xmlns="http://www.w3.org/2000/svg" class="d-none">
      <symbol id="check2" viewBox="0 0 16 16">
        <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
      </symbol>
      <symbol id="circle-half" viewBox="0 0 16 16">
        <path d="M8 15A7 7 0 1 0 8 1v14zm0 1A8 8 0 1 1 8 0a8 8 0 0 1 0 16z"/>
      </symbol>
      <symbol id="moon-stars-fill" viewBox="0 0 16 16">
        <path d="M6 .278a.768.768 0 0 1 .08.858 7.208 7.208 0 0 0-.878 3.46c0 4.021 3.278 7.277 7.318 7.277.527 0 1.04-.055 1.533-.16a.787.787 0 0 1 .81.316.733.733 0 0 1-.031.893A8.349 8.349 0 0 1 8.344 16C3.734 16 0 12.286 0 7.71 0 4.266 2.114 1.312 5.124.06A.752.752 0 0 1 6 .278z"/>
        <path d="M10.794 3.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387a1.734 1.734 0 0 0-1.097 1.097l-.387 1.162a.217.217 0 0 1-.412 0l-.387-1.162A1.734 1.734 0 0 0 9.31 6.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387a1.734 1.734 0 0 0 1.097-1.097l.387-1.162zM13.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.156 1.156 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.156 1.156 0 0 0-.732-.732l-.774-.258a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732L13.863.1z"/>
      </symbol>
      <symbol id="sun-fill" viewBox="0 0 16 16">
        <path d="M8 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0zm0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13zm8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5zM3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8zm10.657-5.657a.5.5 0 0 1 0 .707l-1.414 1.415a.5.5 0 1 1-.707-.708l1.414-1.414a.5.5 0 0 1 .707 0zm-9.193 9.193a.5.5 0 0 1 0 .707L3.05 13.657a.5.5 0 0 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0zm9.193 2.121a.5.5 0 0 1-.707 0l-1.414-1.414a.5.5 0 0 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .707zM4.464 4.465a.5.5 0 0 1-.707 0L2.343 3.05a.5.5 0 1 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .708z"/>
      </symbol>
    </svg>

    <div class="dropdown position-fixed bottom-0 end-0 mb-3 me-3 bd-mode-toggle">
      <button class="btn btn-bd-primary py-2 dropdown-toggle d-flex align-items-center"
              id="bd-theme"
              type="button"
              aria-expanded="false"
              data-bs-toggle="dropdown"
              aria-label="Toggle theme (auto)">
        <svg class="bi my-1 theme-icon-active" width="1em" height="1em"><use href="#circle-half"></use></svg>
        <span class="visually-hidden" id="bd-theme-text">Toggle theme</span>
      </button>
      <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="bd-theme-text">
        <li>
          <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="light" aria-pressed="false">
            <svg class="bi me-2 opacity-50 theme-icon" width="1em" height="1em"><use href="#sun-fill"></use></svg>
            Light
            <svg class="bi ms-auto d-none" width="1em" height="1em"><use href="#check2"></use></svg>
          </button>
        </li>
        <li>
          <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="dark" aria-pressed="false">
            <svg class="bi me-2 opacity-50 theme-icon" width="1em" height="1em"><use href="#moon-stars-fill"></use></svg>
            Dark
            <svg class="bi ms-auto d-none" width="1em" height="1em"><use href="#check2"></use></svg>
          </button>
        </li>
        <li>
          <button type="button" class="dropdown-item d-flex align-items-center active" data-bs-theme-value="auto" aria-pressed="true">
            <svg class="bi me-2 opacity-50 theme-icon" width="1em" height="1em"><use href="#circle-half"></use></svg>
            Auto
            <svg class="bi ms-auto d-none" width="1em" height="1em"><use href="#check2"></use></svg>
          </button>
        </li>
      </ul>
    </div>

    
<svg xmlns="http://www.w3.org/2000/svg" class="d-none">
  <symbol id="aperture" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24">
    <circle cx="12" cy="12" r="10"/>
    <path d="M14.31 8l5.74 9.94M9.69 8h11.48M7.38 12l5.74-9.94M9.69 16L3.95 6.06M14.31 16H2.83m13.79-4l-5.74 9.94"/>
  </symbol>
  <symbol id="cart" viewBox="0 0 16 16">
    <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .49.598l-1 5a.5.5 0 0 1-.465.401l-9.397.472L4.415 11H13a.5.5 0 0 1 0 1H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l.84 4.479 9.144-.459L13.89 4H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
  </symbol>
  <symbol id="chevron-right" viewBox="0 0 16 16">
    <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"/>
  </symbol>
</svg>

<div class="container">
  <header class="border-bottom lh-1 py-3">
    <div class="row flex-nowrap justify-content-between align-items-center">
      <div class="col-4 pt-1">
          <div class="dropdown">
          <button class="btn dropdown-toggle d-flex align-items-center"
                  type="button"
                  aria-expanded="false"
                  data-bs-toggle="dropdown">
                  <svg class="bi my-1 theme-icon-active" width="5px" height="5px"></svg>
                  <img src="./images/profile-1.jpg" width="40px" height="40px">
            <span class="visually-hidden">Settings</span>
          </button>
          <ul class="dropdown-menu dropdown-menu-end shadow">
            <li>
              <a href="../static/genProfileSignedIn.php"><button type="button" class="dropdown-item d-flex align-items-center" aria-pressed="false">
                <svg class="bi me-2 opacity-50 theme-icon" width="1em" height="1em"></svg>
                My Profile
                <svg class="bi ms-auto d-none" width="1em" height="1em"></svg>
              </button></a>
            </li>
            <li>
              <a href="#"><button type="button" class="dropdown-item d-flex align-items-center" aria-pressed="false">
                <svg class="bi me-2 opacity-50 theme-icon" width="1em" height="1em"></svg>
                Notifications
                <svg class="bi ms-auto d-none" width="1em" height="1em"></svg>
              </button></a>
            </li>
            <li>
              <button type="button" class="dropdown-item d-flex align-items-center" aria-pressed="false">
                <svg class="bi me-2 opacity-50 theme-icon" width="1em" height="1em"></svg>
                <?php
                echo "<a href='edit.php?Id=$id'>Change Profile</a>";
                ?>               
                <svg class="bi ms-auto d-none" width="1em" height="1em"></svg>
              </button>
            </li>
            <li>
              <a href="../static/logout.php"><button type="button" class="dropdown-item d-flex align-items-center" aria-pressed="false">
                <svg class="bi me-2 opacity-50 theme-icon" width="1em" height="1em"></svg>
                Log Out
                <svg class="bi ms-auto d-none" width="1em" height="1em"></svg>
              </button> </a>
            </li>

          </ul>
        </div>
      </div>
      <div class="col-4 text-center">
        <a class="blog-header-logo text-body-emphasis text-decoration-none" href="#">MUSIC BOX</a>
      </div>
      <div class="col-4 d-flex justify-content-end align-items-center">
        <a class="link-secondary" href="#" aria-label="">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="mx-3" role="img" viewBox="0 0 24 24"><title>TITLE</title><circle cx="10.5" cy="10.5" r="7.5"/><path d="M21 21l-5.2-5.2"/></svg>
        </a>
   
      </div>
    </div>
  </header>

  <div class="nav-scroller py-1 mb-3 border-bottom">
  <nav class="nav nav-underline justify-content-between">
      <a class="nav-item nav-link link-body-emphasis" href="home.php">HOME</a>
      <a class="nav-item nav-link link-body-emphasis" href="#">MUSIC</a>
      <a class="nav-item nav-link link-body-emphasis" href="#">MEMBERS</a>
      <a class="nav-item nav-link link-body-emphasis" href="faq.php">FAQ</a>
      <a class="nav-item nav-link link-body-emphasis" href="about.php">ABOUT</a> 
    </nav>
  </div>
</div>

<main class="container">
      <div class="row mb-2 border rounded">
        <div class="col-auto d-none d-lg-block">
          <div id="content" class="bodySec">
      <div class="faq">
        <h1>Frequently Asked Question</h1>  </br > 
        <div class="row">
          <div class="explore">
            <h2>What is Music Box?</h2>
            <p>Music Box is a social platform where everyone can connect over their love
                of music, as is also stated on our About page. Users can evaluate and rate
                music from accross the globe and share their thoughts. In order to gather
                and arrange music, users can create their own playlist. 
            </p> </br >
            <h2>Is music playable on this website?</h2>
            <p>No, we do not offer the ability to steam music, but we have integreate Spotify 
                and plan to add more platforms in the future.
            </p></br >
            <h2>Is it necessary to register in order to utilize Music Box?</h2>
            <p>No, while you don't need an account to browse the entire Music Box, you will need
                one to rate, review, and create your playlists.</p></br >               
            <h2>Does this service have a fee attached to it?</h2>
            <p>No, the application is free to use for everyone. </p>
          </div>
        </div>
      </div>
    </main>
  </body>
</html>

<script src="js/bootstrap.bundle.min.js"></script>

<style>    
#content{
    text-align: center;
    float: center;
}
  .bd-placeholder-img {
    font-size: 1.125rem;
    text-anchor: middle;
    -webkit-user-select: none;
    -moz-user-select: none;
    user-select: none;
  }

  @media (min-width: 768px) {
    .bd-placeholder-img-lg {
      font-size: 3.5rem;
    }
  }

  .b-example-divider {
    width: 100%;
    height: 3rem;
    background-color: rgba(0, 0, 0, .1);
    border: solid rgba(0, 0, 0, .15);
    border-width: 1px 0;
    box-shadow: inset 0 .5em 1.5em rgba(0, 0, 0, .1), inset 0 .125em .5em rgba(0, 0, 0, .15);
  }

  .b-example-vr {
    flex-shrink: 0;
    width: 1.5rem;
    height: 100vh;
  }

  .bi {
    vertical-align: -.125em;
    fill: currentColor;
  }

  .nav-scroller {
    position: relative;
    z-index: 2;
    height: 2.75rem;
    overflow-y: hidden;
  }

  .nav-scroller .nav {
    display: flex;
    flex-wrap: nowrap;
    padding-bottom: 1rem;
    margin-top: -1px;
    overflow-x: auto;
    text-align: center;
    white-space: nowrap;
    -webkit-overflow-scrolling: touch;
  }

  .btn-bd-primary {
      --bd-violet-bg: #202976;
      --bd-violet-rgb: 112.520718, 44.062154, 249.437846;

      --bs-btn-font-weight: 600;
      --bs-btn-color: var(--bs-white);
      --bs-btn-bg: var(--bd-violet-bg);
      --bs-btn-border-color: var(--bd-violet-bg);
      --bs-btn-hover-color: var(--bs-white);
      --bs-btn-hover-bg: #202976;
      --bs-btn-hover-border-color: #202976;
      --bs-btn-focus-shadow-rgb: var(--bd-violet-rgb);
      --bs-btn-active-color: var(--bs-btn-hover-color);
      --bs-btn-active-bg: #202976;
      --bs-btn-active-border-color: #202976;
    }

  .bd-mode-toggle {
    z-index: 1500;
  }

  .bd-mode-toggle .dropdown-menu .active .bi {
    display: block !important;
  }

.faq h1 {
    text-align: center;
    font-size: 35px;
}
.faq .explore p {
    font-size: 15px;
}

.faq {
    width: 60%;
    margin: auto;
    padding-top: 25px;
}

.faq .explore {
    box-sizing: border-box;
    transition: 0.5s;
}

.faq h2 {
    font-size: 25px;
    font-family:'Times New Roman', Times, serif;
}

.faq p {
    font-size: 20px;
    font-family:'Times New Roman', Times, serif;
}
</style>
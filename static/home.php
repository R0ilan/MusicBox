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
function getGreeting()
{
  $hour = date('H');
  if ($hour < 12) {
    return "Good Morning";
  } elseif ($hour < 18) {
    return "Good Afternoon";
  } else {
    return "Good Evening";
  }
}

$sql = "SELECT concat(u.fname, ' ', lname) as name, p.user_id, p.content, p.date FROM POSTS p
    JOIN USERS u on p.user_id=u.id
    ORDER BY date DESC;";
$result = mysqli_query($con, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <script src="js/color-modes.js"></script>
  <script src="js/index.js"></script>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
  <meta name="generator" content="Hugo 0.118.2">
  <title>Music Box</title>
  <link rel="canonical" href="https://getbootstrap.com/docs/5.3/examples/blog-rtl/">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@docsearch/css@3">
  <link rel="stylesheet" href="https://unicons.iconscout.com/release/v2.1.6/css/unicons.css">
  <link href="css/bootstrap.rtl.min.css" rel="stylesheet">

  <!--JQuery-->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="js/bootstrap.bundle.min.js"></script>

  <script>
    jQuery(document).ready(function($) {
      $.ajax({
        url: 'get_friends.php',
        type: 'post',
        data: '',
        dataType: 'json',
        success: function(response) {
          for (friend of response['friends']) {
            let li = document.createElement('li');
            let a = document.createElement('a');
            a.setAttribute('class', 'd-flex flex-column flex-lg-row gap-3 align-items-start align-items-lg-center py-3 link-body-emphasis text-decoration-none border-top');
            a.setAttribute('href', '#');
            li.appendChild(a);

            let svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
            svg.setAttribute('class', 'bd-placeholder-img');
            svg.setAttribute('width', '100%');
            svg.setAttribute('height', '96');
            svg.setAttribute('aria-hidden', 'true');
            svg.setAttribute('preserveAspectRatio', 'xMidYMid slice');
            svg.setAttribute('focusable', 'false');
            a.appendChild(svg);

            let rect = document.createElementNS('http://www.w3.org/2000/svg', 'rect');
            rect.setAttribute('width', '100%');
            rect.setAttribute('height', '100%');
            rect.setAttribute('fill', '#777');
            svg.appendChild(rect);

            let div = document.createElement('div');
            div.setAttribute('class', 'col-lg-8');
            a.appendChild(div);

            let h6 = document.createElement('h6');
            h6.setAttribute('class', 'mb-0');
            h6.textContent = `${friend['name']} Profile`;
            div.appendChild(h6);

            // TODO: Make profile link functional.

            $('#friendList').append(li);
          }
        },
        error: () => {
          console.log('get_friends.php failed.');
        }
      })
    });
  </script>

  <link href="https://fonts.googleapis.com/css?family=Amiri:wght@400;700&amp;display=swap" rel="stylesheet">
  <link href="css/blog.rtl.css" rel="stylesheet">
</head>

<body>
  <svg xmlns="http://www.w3.org/2000/svg" class="d-none">
    <symbol id="check2" viewBox="0 0 16 16">
      <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z" />
    </symbol>
    <symbol id="circle-half" viewBox="0 0 16 16">
      <path d="M8 15A7 7 0 1 0 8 1v14zm0 1A8 8 0 1 1 8 0a8 8 0 0 1 0 16z" />
    </symbol>
    <symbol id="moon-stars-fill" viewBox="0 0 16 16">
      <path d="M6 .278a.768.768 0 0 1 .08.858 7.208 7.208 0 0 0-.878 3.46c0 4.021 3.278 7.277 7.318 7.277.527 0 1.04-.055 1.533-.16a.787.787 0 0 1 .81.316.733.733 0 0 1-.031.893A8.349 8.349 0 0 1 8.344 16C3.734 16 0 12.286 0 7.71 0 4.266 2.114 1.312 5.124.06A.752.752 0 0 1 6 .278z" />
      <path d="M10.794 3.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387a1.734 1.734 0 0 0-1.097 1.097l-.387 1.162a.217.217 0 0 1-.412 0l-.387-1.162A1.734 1.734 0 0 0 9.31 6.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387a1.734 1.734 0 0 0 1.097-1.097l.387-1.162zM13.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.156 1.156 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.156 1.156 0 0 0-.732-.732l-.774-.258a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732L13.863.1z" />
    </symbol>
    <symbol id="sun-fill" viewBox="0 0 16 16">
      <path d="M8 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0zm0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13zm8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5zM3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8zm10.657-5.657a.5.5 0 0 1 0 .707l-1.414 1.415a.5.5 0 1 1-.707-.708l1.414-1.414a.5.5 0 0 1 .707 0zm-9.193 9.193a.5.5 0 0 1 0 .707L3.05 13.657a.5.5 0 0 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0zm9.193 2.121a.5.5 0 0 1-.707 0l-1.414-1.414a.5.5 0 0 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .707zM4.464 4.465a.5.5 0 0 1-.707 0L2.343 3.05a.5.5 0 1 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .708z" />
    </symbol>
  </svg>

  <div class="dropdown position-fixed bottom-0 end-0 mb-3 me-3 bd-mode-toggle">
    <button class="btn btn-bd-primary py-2 dropdown-toggle d-flex align-items-center" id="bd-theme" type="button" aria-expanded="false" data-bs-toggle="dropdown" aria-label="Toggle theme (auto)">
      <svg class="bi my-1 theme-icon-active" width="1em" height="1em">
        <use href="#circle-half"></use>
      </svg>
      <span class="visually-hidden" id="bd-theme-text">Toggle theme</span>
    </button>
    <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="bd-theme-text">
      <li>
        <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="light" aria-pressed="false">
          <svg class="bi me-2 opacity-50 theme-icon" width="1em" height="1em">
            <use href="#sun-fill"></use>
          </svg>
          Light
          <svg class="bi ms-auto d-none" width="1em" height="1em">
            <use href="#check2"></use>
          </svg>
        </button>
      </li>
      <li>
        <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="dark" aria-pressed="false">
          <svg class="bi me-2 opacity-50 theme-icon" width="1em" height="1em">
            <use href="#moon-stars-fill"></use>
          </svg>
          Dark
          <svg class="bi ms-auto d-none" width="1em" height="1em">
            <use href="#check2"></use>
          </svg>
        </button>
      </li>
      <li>
        <button type="button" class="dropdown-item d-flex align-items-center active" data-bs-theme-value="auto" aria-pressed="true">
          <svg class="bi me-2 opacity-50 theme-icon" width="1em" height="1em">
            <use href="#circle-half"></use>
          </svg>
          Auto
          <svg class="bi ms-auto d-none" width="1em" height="1em">
            <use href="#check2"></use>
          </svg>
        </button>
      </li>
    </ul>
  </div>

  <svg xmlns="http://www.w3.org/2000/svg" class="d-none">
    <symbol id="aperture" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24">
      <circle cx="12" cy="12" r="10" />
      <path d="M14.31 8l5.74 9.94M9.69 8h11.48M7.38 12l5.74-9.94M9.69 16L3.95 6.06M14.31 16H2.83m13.79-4l-5.74 9.94" />
    </symbol>
    <symbol id="cart" viewBox="0 0 16 16">
      <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .49.598l-1 5a.5.5 0 0 1-.465.401l-9.397.472L4.415 11H13a.5.5 0 0 1 0 1H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l.84 4.479 9.144-.459L13.89 4H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
    </symbol>
    <symbol id="chevron-right" viewBox="0 0 16 16">
      <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z" />
    </symbol>
  </svg>

  <div class="container">
    <header class="border-bottom lh-1 py-3">
      <div class="row flex-nowrap justify-content-between align-items-center">
        <div class="col-4 pt-1">
          <div class="dropdown">
          </div>
        </div>
        <div class="col-4 text-center">
          <a class="blog-header-logo text-body-emphasis text-decoration-none" href="#">MUSIC BOX</a>
        </div>
        <div class="col-4 d-flex justify-content-end align-items-center">
          <a class="link-secondary" href="#" aria-label="">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="mx-3" role="img" viewBox="0 0 24 24">
              <title>TITLE</title>
              <circle cx="10.5" cy="10.5" r="7.5" />
              <path d="M21 21l-5.2-5.2" />
            </svg>
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

  <!-------------------------------- MAIN ----------------------------------->
  <main>
    <div class="container">
      <!----------------- LEFT -------------------->
      <div class="left">
        <a class="profile">
          <div class="profile-photo">
            <img src="./images/profile-1.jpg">
          </div>
          <div class="handle">
            <h4>Jared Williams</h4>
            <p class="text-muted">
            @<b><?php echo $res_Uname; ?></b>
            <br>
            <b><?php echo $res_role; ?></b>
            </p>
          </div>
        </a>
        <!----------------- SIDEBAR -------------------->
        <div class="sidebar">
          <a class="menu-item">
            <span><i class="uil uil-home"></i></span>
            <h3>Home</h3>
          </a>
          <a class="menu-item">
            <span><i class="uil uil-compass"></i></span>
            <h3>Profile</h3>
          </a>
          <a class="menu-item">
            <span><i class="uil uil-bell"></i></span>
            <h3>Notification</h3>
          </a>
          <a class="menu-item">
            <span><i class="uil uil-setting"></i></span>
            <h3>Edit Profile</h3>
          </a>
          <a class="menu-item">
            <span><i class="uil uil-palette"></i></span>
            <h3>Log Out</h3>
          </a>
        </div>
        <!----------------- END OF SIDEBAR -------------------->
      </div>

            <!----------------- MIDDLE -------------------->
            <div class="middle">
                <form action="posts_backend.php" method="post" class="create-post">
                    <div class="profile-photo">
                        <img src="./images/profile-1.jpg">
                    </div>
                    <input type="text" placeholder="What's are you listening to, Amy ?" name="create-post">
                    <input type="submit" value="Post" class="btn btn-primary">
                </form>
                <!----------------- FEEDS -------------------->
                <div class="feeds">
                  <!----------------- FEED dynamic -------------------->
                  <?php
                    if ($result->num_rows > 0) {
                        // Output data of each row
                        while($row = $result->fetch_assoc()) {
                            echo '<div class="feed">';
                            echo '<div class="head">';
                            echo '<div class="user">';
                            echo '<div class="profile-photo">';
                            echo '<img src="./images/profile-'. $row["user_id"] .'.jpg">'; // Assuming you have user profile images named as profile-user_id.jpg
                            echo '</div>';
                            echo '<div class="info">';
                            echo '<h3>'. $row["name"] .'</h3>'; // Replace with actual user name if available
                            echo '<small>'. $row["date"] .'</small>'; // Format date as required
                            echo '</div></div>';
                            echo '<span class="edit"><i class="uil uil-ellipsis-h"></i></span></div>';
                            //echo '<div class="photo"><img src="./images/feed-'. $row["id"] .'.jpg"></div>'; // Assuming you have post images named as feed-post_id.jpg
                            echo '<div class="action-buttons"><div class="interaction-buttons"><span><i class="uil uil-heart"></i></span><span><i class="uil uil-comment-dots"></i></span><span><i class="uil uil-share-alt"></i></span></div><div class="bookmark"><span><i class="uil uil-bookmark-full"></i></span></div></div>';
                            //echo '<div class="liked-by"><p>Liked by <b>'. $row["likes"] .' others</b></p></div>'; // Replace with actual likes data if available
                            echo '<div class="caption"><p><b>'. $row["name"] .'</b> '. $row["content"] .'</p></div>'; // Replace with actual user name if available
                            echo '<div class="comments text-muted">View all comments</div></div>'; // Replace with actual comments data if available
                        }
                    } else {
                        echo "No user's posts found";
                    }
                    ?>
                    <!----------------- FEED 1 -------------------->
                    <div class="feed">
                        <div class="head">
                            <div class="user">
                                <div class="profile-photo">
                                    <img src="./images/profile-13.jpg">
                                </div>
                                <div class="info">
                                    <h3>Lana Rose</h3>
                                    <small>New York City, 15 Minutes Ago</small>
                                </div>
                            </div>
                            <span class="edit">
                                <i class="uil uil-ellipsis-h"></i>
                            </span>
                        </div>

                        <div class="photo">
                            <img src="./images/feed-1.jpg">
                        </div>

                        <div class="action-buttons">
                            <div class="interaction-buttons">
                                <span><i class="uil uil-heart"></i></span>
                                <span><i class="uil uil-comment-dots"></i></span>
                                <span><i class="uil uil-share-alt"></i></span>
                            </div>
                            <div class="bookmark">
                                <span><i class="uil uil-bookmark-full"></i></span>
                            </div>
                        </div>

                        <div class="liked-by">
                            <span><img src="./images/profile-10.jpg"></span>
                            <span><img src="./images/profile-4.jpg"></span>
                            <span><img src="./images/profile-15.jpg"></span>
                            <p>Liked by <b>Ernest Achiever</b> and <b>2,323 others</b></p>
                        </div>

                        <div class="caption">
                            <p><b>Lana Rose</b> Christmas is coming and I am Excited and cant Wait! Listen to My favorite christmas songs here!
                                <span class="harsh-tag">#ChristmasSongs</span>
                            </p>
                        </div>

                        <div class="comments text-muted">
                            View all 277 comments
                        </div>
                    </div>
                    <!----------------- END OF FEED 1 -------------------->

                    <!----------------- FEED 2 -------------------->
                    <div class="feed">
                        <div class="head">
                            <div class="user">
                                <div class="profile-photo">
                                    <img src="./images/profile-10.jpg">
                                </div>
                                <div class="info">
                                    <h3>Clara Dwayne</h3>
                                    <small>Miami, 2 Hours Ago</small>
                                </div>
                            </div>
                            <span class="edit">
                                <i class="uil uil-ellipsis-h"></i>
                            </span>
                        </div>

                        <div class="photo">
                            <img src="./images/feed-3.jpg">
                        </div>

                        <div class="action-buttons">
                            <div class="interaction-buttons">
                                <span><i class="uil uil-heart"></i></span>
                                <span><i class="uil uil-comment-dots"></i></span>
                                <span><i class="uil uil-share-alt"></i></span>
                            </div>
                            <div class="bookmark">
                                <span><i class="uil uil-bookmark-full"></i></span>
                            </div>
                        </div>

                        <div class="liked-by">
                            <span><img src="./images/profile-11.jpg"></span>
                            <span><img src="./images/profile-5.jpg"></span>
                            <span><img src="./images/profile-16.jpg"></span>
                            <p>Liked by <b>Diana Rose</b> and <b>2, 323 others</b></p>
                        </div>

                        <div class="caption">
                            <p><b>Clara Dwayne</b> Nothing like listening to music to unwind, Join me and listen to my relaxing music playlist!
                                <span class="harsh-tag">#lifestyle</span>
                            </p>
                        </div>

                        <div class="comments text-muted">
                            View all 100 comments
                        </div>
                    </div>
                    <!----------------- END OF FEED 2 -------------------->

                    <!----------------- FEED 3 -------------------->
                    <div class="feed">
                        <div class="head">
                            <div class="user">
                                <div class="profile-photo">
                                    <img src="./images/profile-4.jpg">
                                </div>
                                <div class="info">
                                    <h3>Rosalinda Clark</h3>
                                    <small>New York, 50 Minutes Ago</small>
                                </div>
                            </div>
                            <span class="edit">
                                <i class="uil uil-ellipsis-h"></i>
                            </span>
                        </div>

                        <div class="photo">
                            <img src="./images/feed-4.jpg">
                        </div>

                        <div class="action-buttons">
                            <div class="interaction-buttons">
                                <span><i class="uil uil-heart"></i></span>
                                <span><i class="uil uil-comment-dots"></i></span>
                                <span><i class="uil uil-share-alt"></i></span>
                            </div>
                            <div class="bookmark">
                                <span><i class="uil uil-bookmark-full"></i></span>
                            </div>
                        </div>

                        <div class="liked-by">
                            <span><img src="./images/profile-12.jpg"></span>
                            <span><img src="./images/profile-13.jpg"></span>
                            <span><img src="./images/profile-14.jpg"></span>
                            <p>Liked by <b>Clara Dwayne</b> and <b>2,663 others</b></p>
                        </div>

                        <div class="caption">
                            <p><b>Rosalinda Clark</b> Met some really amazing people this weekend at my sisters bachelorette party. Thank you to <b>@lisa_sand123</b> and <b>@debbieBite456</b> for sharing some amazing party starter songs with me! Now I'll always be the lift of the party!
                                <span class="harsh-tag">#PartyStarter</span>
                            </p>
                        </div>

                        <div class="comments text-muted">
                            View all 50 comments
                        </div>
                    </div>
                    <!----------------- END OF FEED 3 -------------------->

                    <!----------------- FEED 4 -------------------->
                    <div class="feed">
                        <div class="head">
                            <div class="user">
                                <div class="profile-photo">
                                    <img src="./images/profile-5.jpg">
                                </div>
                                <div class="info">
                                    <h3>Alexandria Riana</h3>
                                    <small>Dubai, 1 Hour Ago</small>
                                </div>
                            </div>
                            <span class="edit">
                                <i class="uil uil-ellipsis-h"></i>
                            </span>
                        </div>

                        <div class="photo">
                            <img src="./images/feed-5.jpg">
                        </div>

                        <div class="action-buttons">
                            <div class="interaction-buttons">
                                <span><i class="uil uil-heart"></i></span>
                                <span><i class="uil uil-comment-dots"></i></span>
                                <span><i class="uil uil-share-alt"></i></span>
                            </div>
                            <div class="bookmark">
                                <span><i class="uil uil-bookmark-full"></i></span>
                            </div>
                        </div>

                        <div class="liked-by">
                            <span><img src="./images/profile-10.jpg"></span>
                            <span><img src="./images/profile-4.jpg"></span>
                            <span><img src="./images/profile-15.jpg"></span>
                            <p>Liked by <b>Lana Rose</b> and <b>5,323 others</b></p>
                        </div>

                        <div class="caption">
                            <p><b>Alexandria Riana</b> I am always on the go with Clients, these are some songs that I found help me stay motivated when on the move and working!
                                <span class="harsh-tag">#GrindHarder</span>
                            </p>
                        </div>

                        <div class="comments text-muted">
                            View all 540 comments
                        </div>
                    </div>
                    <!----------------- END OF FEED 4 -------------------->

                    <!----------------- FEED 5 -------------------->
                    <div class="feed">
                        <div class="head">
                            <div class="user">
                                <div class="profile-photo">
                                    <img src="./images/profile-7.jpg">
                                </div>
                                <div class="info">
                                    <h3>Keylie Hadid</h3>
                                    <small>Dubai, 3 Hours Ago</small>
                                </div>
                            </div>
                            <span class="edit">
                                <i class="uil uil-ellipsis-h"></i>
                            </span>
                        </div>

                        <div class="photo">
                            <img src="./images/feed-7.jpg">
                        </div>

                        <div class="action-buttons">
                            <div class="interaction-buttons">
                                <span><i class="uil uil-heart"></i></span>
                                <span><i class="uil uil-comment-dots"></i></span>
                                <span><i class="uil uil-share-alt"></i></span>
                            </div>
                            <div class="bookmark">
                                <span><i class="uil uil-bookmark-full"></i></span>
                            </div>
                        </div>

                        <div class="liked-by">
                            <span><img src="./images/profile-10.jpg"></span>
                            <span><img src="./images/profile-4.jpg"></span>
                            <span><img src="./images/profile-15.jpg"></span>
                            <p>Liked by <b>Riana Rose</b> and <b>1,226 others</b></p>
                        </div>

                        <div class="caption">
                            <p><b>Keylie Hadid</b> New Study music playlist on my page! These are songs i found while traveling for work, listen for yourself and leave a rating!
                                <span class="harsh-tag">#StudyMusic</span>
                            </p>
                        </div>

                        <div class="comments text-muted">
                            View all 199 comments
                        </div>
                    </div>
                    <!----------------- END OF FEED 5 -------------------->

                    <!----------------- FEED 6 -------------------->
                    <div class="feed">
                        <div class="head">
                            <div class="user">
                                <div class="profile-photo">
                                    <img src="./images/profile-15.jpg">
                                </div>
                                <div class="info">
                                    <h3>Benjamin Dwayne</h3>
                                    <small>New York, 5 Hours Ago</small>
                                </div>
                            </div>
                            <span class="edit">
                                <i class="uil uil-ellipsis-h"></i>
                            </span>
                        </div>

                        <div class="photo">
                            <img src="./images/feed-2.jpg">
                        </div>

                        <div class="action-buttons">
                            <div class="interaction-buttons">
                                <span><i class="uil uil-heart"></i></span>
                                <span><i class="uil uil-comment-dots"></i></span>
                                <span><i class="uil uil-share-alt"></i></span>
                            </div>
                            <div class="bookmark">
                                <span><i class="uil uil-bookmark-full"></i></span>
                            </div>
                        </div>

                        <div class="liked-by">
                            <span><img src="./images/profile-10.jpg"></span>
                            <span><img src="./images/profile-4.jpg"></span>
                            <span><img src="./images/profile-15.jpg"></span>
                            <p>Liked by <b>Ernest Achiever</b> and <b>2, 323 others</b></p>
                        </div>

                        <div class="caption">
                            <p><b>Benjamin Dwayne</b> Hey all! Please go listen to my new single "Given" now out on Spotify and all other platforms! Please leave a positive rating!!! xoxoxo <span class="harsh-tag">#Artist</span></p>
                        </div>

                        <div class="comments text-muted">
                            View all 277 comments
                        </div>
                    </div>
                    <!----------------- END OF FEED 6 -------------------->

                    <!----------------- FEED 7 -------------------->
                    <div class="feed">
                        <div class="head">
                            <div class="user">
                                <div class="profile-photo">
                                    <img src="./images/profile-3.jpg">
                                </div>
                                <div class="info">
                                    <h3>Indiana Ellison</h3>
                                    <small>Qatar, 8 Hours Ago</small>
                                </div>
                            </div>
                            <span class="edit">
                                <i class="uil uil-ellipsis-h"></i>
                            </span>
                        </div>

                        <div class="photo">
                            <img src="./images/feed-6.jpg">
                        </div>

                        <div class="action-buttons">
                            <div class="interaction-buttons">
                                <span><i class="uil uil-heart"></i></span>
                                <span><i class="uil uil-comment-dots"></i></span>
                                <span><i class="uil uil-share-alt"></i></span>
                            </div>
                            <div class="bookmark">
                                <span><i class="uil uil-bookmark-full"></i></span>
                            </div>
                        </div>

                        <div class="liked-by">
                            <span><img src="./images/profile-10.jpg"></span>
                            <span><img src="./images/profile-4.jpg"></span>
                            <span><img src="./images/profile-15.jpg"></span>
                            <p>Liked by <b>Benjamin Dwayne</b> and <b>2, 323 others</b></p>
                        </div>

                        <div class="caption">
                            <p><b>Indiana Ellison</b> Thank you Kylie for the new study music! make sure to follow her page <b>@KeyHadid</b> for more awesome music!
                                <span class="harsh-tag">#NewMusic</span>
                            </p>
                        </div>

                        <div class="comments text-muted">
                            View all 277 comments
                        </div>
                    </div>
                    <!----------------- END OF FEED 7 -------------------->
                </div>
                <!----------------- END OF FEEDS -------------------->
            </div>
            <!----------------- END OF MIDDLE -------------------->
    </main>

</body>

</html>

<style>
  #content {
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
  /*=============== general =============*/
  :root {
        --primary-color-hue: 252;
        --dark-color-lightness: 17%;
        --light-color-lightness: 95%;
        --white-color-lightness: 100%;

        --color-white: hsl(252, 30%, var(--white-color-lightness));
        --color-light: hsl(0, 0%, 100%(--light-color-lightness));
        --color-grey: hsl(0, 0%, 100%);
        --color-primary: hsl(var(--primary-color-hue), 241, 64%, 30%);
        --color-secondary: hsl(0, 24%, 90%);
        --color-success: hsl(120, 95%, 65%);
        --color-danger: hsl(0, 95%, 65%);
        --color-dark: hsl(252, 30%, var(--dark-color-lightness));
        --color-black: hsl(252, 30%, 10%);

        --border-radius: 2rem;
        --card-border-radius: 1rem;
        --btn-padding: 0.6rem 2rem;
        --search-padding: 0.6rem 1rem;
        --card-padding: 1rem;

        --sticky-top-left: 5.4rem;
        --sticky-top-right: -18rem;

    }
  
    /* =============== Main ============== */
    main {
        position: relative;
        top: 1.4rem;
    }

    main .container {
        display: grid;
        grid-template-columns: 18vw auto 20vw;
        position: relative;
    }

  /* =============== Left ============== */
  main .container .left {
    height: max-content;
    position: sticky;
    top: var(--sticky-top-left);
  }

  main .container .left .profile {
    padding: var(--card-padding);
    background: var(--color-white);
    border-radius: var(--card-border-radius);
    display: flex;
    align-items: center;
    column-gap: 1rem;
    width: 100%;
  }

  .profile-photo {
    width: 2.7rem;
    aspect-ratio: 1/1;
    border-radius: 50%;
    overflow: hidden;
  }

  img {
    display: block;
    width: 100%;
  }

  /* =============== Sidebar ============== */
  .left .sidebar {
    margin-top: 1rem;
    background: var(--color-primary);
    border-radius: var(--card-border-radius);
  }

  .left .sidebar .menu-item {
    display: flex;
    align-items: center;
    height: 4rem;
    cursor: pointer;
    transition: all 300ms ease;
    position: relative;
  }

  .left .sidebar .menu-item:hover {
    background: var(--color-light);
  }

  .left .sidebar i {
    font-size: 1.4rem;
    color: var(--color-grey);
    margin-left: 2rem;
    position: relative;
  }

  .left .sidebar {
        background: var(--color-light);
    }

    .left .sidebar i,
    .left .sidebar h3 {
      margin-left: 1.5rem;
    font-size: 1.4rem;
        color: var(--color-primary);
    }
  
    .left .sidebar .active::before {
        content: "";
        display: block;
        width: 0.5rem;
        height: 100%;
        position: absolute;
        background: var(--color-primary);
    }

    .left .btn {
        margin-top: 1rem;
        width: 100%;
        text-align: center;
        padding: 1rem 0;
        margin-bottom: 0.7rem;
    }

      /* =============== Create Post ============== */
      .middle .create-post {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 1rem;
        background: var(--color-white);
        padding: 0.4rem var(--card-padding);
        border-radius: var(--border-radius);
        position: relative;
        left: 50px;
    }

    .middle .create-post input[type="text"] {
        width: 100%;
        justify-self: start;
        padding-left: 1rem;
        background: transparent;
        color: var(--color-dark);
        margin-right: 1rem;
    }

    /* =============== Feeds ============== */
    .middle .feeds .feed {
        background: var(--color-white);
        border-radius: var(--card-border-radius);
        padding: var(--card-padding);
        margin: 1rem 0;
        font-size: 0.85rem;
        line-height: 1.5;
        position: relative;
        left: 50px;
        width: 120%;
    }

    .middle .feed .head {
        display: flex;
        justify-content: space-between;
    }

    .middle .feed .user {
        display: flex;
        gap: 1rem;
    }

    .middle .feed .photo {
        border-radius: var(--card-border-radius);
        overflow: hidden;
        margin: 0.7rem 0;
    }

    .middle .feed .action-buttons {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 1.4rem;
        margin: 0.6rem 0;
    }

    .middle .liked-by {
        display: flex;
    }

    .middle .liked-by span {
        width: 1.4rem;
        height: 1.4rem;
        display: block;
        border-radius: 50%;
        overflow: hidden;
        border: 2px solid var(--color-white);
        margin-left: -0.6rem;
    }

    .middle .liked-by span:first-child {
        margin: 0;
    }

    .middle .liked-by p {
        margin-left: 0.5rem;
    }
</style>
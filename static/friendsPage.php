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
                data: $('#getFriends').serialize(),
                dataType: 'json',
                success: function(response) {
                    for (friend of response['friends']) {
                        let li = document.createElement('li');
                        let a = document.createElement('a');
                        a.setAttribute('class', 'd-flex flex-column flex-lg-row gap-3 align-items-start align-items-lg-center py-3 link-body-emphasis text-decoration-none border-top');
                        a.setAttribute('href', `friendsPage.php`);
                        li.appendChild(a);

                        let img = document.createElement('img');
                        img.setAttribute('src', 'images/profile-4.jpg'); // Replace with the URL of the friend's profile image
                        img.setAttribute('class', 'profile-image');
                        a.appendChild(img);

                        let div = document.createElement('div');
                        div.setAttribute('class', 'col-lg-8');
                        a.appendChild(div);

                        let h6 = document.createElement('h6');
                        h6.setAttribute('class', 'mb-0');
                        h6.textContent = `${friend['name']}`;
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

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        jQuery(document).ready(function($) {
            $('#getReviewsByUser > button').click(function(e) {
                e.preventDefault();
                $.ajax({
                    url: 'get_reviews_by_user.php',
                    type: 'post',
                    data: $('#getReviewsByUser').serialize(),
                    dataType: 'json',
                    success: function(response) {
                        $('#getReviewsByUserResult').html(JSON.stringify(response));
                    },
                    error: () => {
                        console.log('failed');
                    }
                })
            });
        });
    </script>
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
                        <img src="./images/profile-3.jpg">
                    </div>
                    <div class="handle">
                        <p class="text-muted">
                            @<b>amitch</b>
                            <br>
                            <b>Avid Listener</b>
                        </p>
                    </div>
                </a>
            </div>

            <!----------------- MIDDLE -------------------->
            <div class="middle">
                <div class="row">
                    <div class="col-md border rounded">
                        Recently Listened To
                        <div id="recentlyPlayedTracks" class="row align-items">
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md border rounded">
                        Favorites
                        <div class="row align-items-center">
                            <div class="col">
                                <img src="" width="100px" height="150px">
                            </div>
                            <div class="col">
                                <img src="" width="100px" height="150px">
                            </div>
                            <div class="col">
                                <img src="" width="100px" height="150px">
                            </div>
                            <div class="col">
                                <img src="" width="100px" height="150px">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!----------------- END OF MIDDLE -------------------->
            <!----------------- RIGHT -------------------->
            <div class="right">
                <div class="reviews">
                    <h4>Reviews</h4>
                    <form id="getReviewsByUser" method="post">
                        <button type="submit">Show Reviews</button>
                    </form>
                    <p id="getReviewsByUserResult"></p>
                </div>
            </div>
            <!----------------- END OF RIGHT -------------------->
        </div>
    </main>

    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        jQuery(document).ready(function($) {
            $.ajax({
                url: 'get_recently_played_tracks.php',
                type: 'post',
                data: 'number_of_tracks=4', // Limit to top 4 tracks
                dataType: 'json',
                success: function(response) {
                    for (recently_played_track of response['recently_played_tracks']) {
                        // Get album image URL
                        let imgSrc = ''
                        let imgWidth = 200
                        let imgHeight = 0
                        if (recently_played_track['track']['album']['images'].length > 0) {
                            imgSrc = recently_played_track['track']['album']['images'][0]['url'];
                            imgWidth = recently_played_track['track']['album']['images'][0]['width'];
                            imgHeight = recently_played_track['track']['album']['images'][0]['height'];

                            // Rescale to have width of 50
                            imgHeight = imgHeight * 200 / imgWidth;
                            imgWidth = 200;
                        }

                        let div = document.createElement('div');
                        div.setAttribute('class', 'col');

                        let img = document.createElement('img');
                        img.setAttribute('src', imgSrc);
                        img.setAttribute('width', `${imgWidth}px`);
                        img.setAttribute('height', `${imgHeight}px`);
                        div.appendChild(img);

                        $('#recentlyPlayedTracks').append(div);
                    }
                },
                error: () => {
                    console.log('get_recently_played_tracks.php failed.');
                }
            })
        });
    </script>
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
        column-gap: 2rem;
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
        font-size: 13px;
    }

    main .container .left .profile h4 {
        font-size: 20px;
    }

    .profile-photo {
        width: 4.7rem;
        aspect-ratio: 1/1;
        border-radius: 50%;
        overflow: hidden;
    }

    img {
        display: block;
        width: 100%;
    }

    /* =============== Right ============== */
    main .container .right .friends {
        height: max-content;
        position: sticky;
        top: var(--sticky-top-right);
        bottom: 0;
    }

    /* =============== Reviews ============== */
    .right .reviews {
        margin-top: 0;
    }

    .right .reviews h4 {
        margin: 0;
        top: var(--sticky-top-right);
    }

    .right .reviews {
        background: var(--color-white);
        padding: var(--card-padding);
        border-radius: var(--card-border-radius);
        margin-bottom: 0.7rem;
    }
</style>
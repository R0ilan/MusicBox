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

<head>
  <title>Music Box</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="style.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
</head>

<body style="background-color:black;">
    <div class="topnav" id="myTopnav">
        <a href="index.html" class="active">MUSIC BOX</a>
        <a href="index.html">Home</a>
        <a href="about.html">About</a>
        <a href="">Music</a>
        <a href="faq.html">FAQ</a>
        <a href="login.php">Login/Signup</a>
        <a href="javascript:void(0);" class="icon" onclick="myFunction()">
            <i class="fa fa-bars"></i>
        </a>
    </div>

    <script>
        function myFunction() {
            var x = document.getElementById("myTopnav");
            if (x.className === "topnav") {
            x.className += " responsive";
            } else {
            x.className = "topnav";
            }
        }
    </script>  

</body>
</html>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE-edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="style.css">
        <title> MusicBox: Welcome </title>          
    </head>
    <body>
        <div class="nav">
            <div class="logo">
                <p><a href="home.php">MusicBox</a></p>
            </div>
            <div class="right-links">
                <?php
                echo "<a href='edit.php?Id=$id'>Change Profile</a>";
                ?>
                <a href="logout.php"> <button class="btn">Logout</button></a>
            </div>
        </div>
        <main>
            <div class="main-box top">
                <div class="top">
                    <div class="box">
                        <p>Hello <b><?php echo $res_Uname; ?></b>, Welcome!</p>
                    </div>
                    <div class="box">
                        <p>Your Email is <b><?php echo $res_email; ?></b>.</p>
                    </div>
                </div>
            </div>
            <div class="bottom">
                <div class="box">
                    <p>And your role is: <b><?php echo $res_role; ?></b>.</p>
                </div>
            </div>
        <div class="container">
            <div class="box mail-box">
                <!-- Dynamic greeting -->
                <div class="top">
                    <h2 class="message"><?php echo getGreeting(); ?>, <?php echo $_SESSION['username']; ?>!</h2>
                </div>

                <!-- Display key features or links -->
                <div class="bottom">
                    <div class="feature-box">
                        <a href="#">New Music Tracks</a>
                        <p>Check out the latest tracks added this week!</p>
                    </div>
                    <div class="feature-box">
                        <a href="#">My Playlists</a>
                        <p>Jump back into your favorite tunes.</p>
                    </div>
                    <div class="feature-box">
                        <a href="#">Settings</a>
                        <p>Personalize your MusicBox experience.</p>
                    </div>
                </div>
            </div>
        </div>
        </main>
    </body>
</html>

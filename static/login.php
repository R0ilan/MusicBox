<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Redirect to home if user is already logged in
if (isset($_SESSION['valid']) && $_SESSION['valid'] === true) {
    header("Location: genProfileSignedIn.php");
    exit;
}

// Display error message if we get one
$errorMessage = "";
if (isset($_SESSION['error'])) {
    $errorMessage = $_SESSION['error'];
    unset($_SESSION['error']); // Clear the error message from session
}

// Include database configuration
include "dbconfig.php";

// Connect to the database
$con = mysqli_connect($server, $login, $password, $dbname);

// Check the connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['submit'])) {
        $username = mysqli_real_escape_string($con, $_POST['username']);
        // Hash the entered password using SHA-256 for comparison
        $hashed_password = hash('sha256', $_POST['password']);

        $result = mysqli_query($con, "SELECT * FROM USERS WHERE username= '$username' AND password = '$hashed_password'");

        // Check if the query was successful
        if (!$result) {
            die("Select error: " . mysqli_error($con));
        }

        $row = mysqli_fetch_assoc($result);

        if (is_array($row) && !empty($row)) {
            $_SESSION['username'] = $row['username'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['fname'] = $row['fname'];
            $_SESSION['lname'] = $row['lname'];
            $_SESSION['role'] = $row['role'];
            $_SESSION['id'] = $row['id'];
            $_SESSION['valid'] = true; // Set the session valid flag

            header("Location: genProfileSignedIn.php");
            exit;
        } else {
            $errorMessage = "Wrong Username or Password";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE-edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>MusicBox: Login</title>
</head>
<body>
    <div class="container">
        <div class="box form-box">
            <?php if ($errorMessage): ?>
                <div class='message'>
                    <p><?php echo $errorMessage; ?></p>
                </div>
            <?php endif; ?>
            <header>Login</header>
            <form action="login.php" method="post">
                <div class="field input">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" required>
                </div>
                <div class="field input">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" required>
                </div>
                </br >
                <div class="field">
                    <input type="submit" name="submit" class="btn" value="Login" required>
                </div>
                <div class="links">
                    Don't have an account? <a href="signup.php">Register Here</a>
                </div>
                <div class="navigate">
                    <a href="home.html">Home</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

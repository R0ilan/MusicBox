<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE-edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>MusicBox: Sign Up</title>
</head>

<body>
    <div class="container">
        <div class="box form-box">

            <?php
            // Include database configuration
            include "dbconfig.php";

            // Connect to the database
            $con = mysqli_connect($server, $login, $password, $dbname);

            // Check the connection
            if (!$con) {
                die("Connection failed: " . mysqli_connect_error());
            }

            if (isset($_POST['submit'])) {
                $username = mysqli_real_escape_string($con, $_POST['username']);

                // Hash the password using SHA-256
                $hashed_password = hash('sha256', $_POST['password']);

                $fname = mysqli_real_escape_string($con, $_POST['fname']);
                $lname = mysqli_real_escape_string($con, $_POST['lname']);
                $email = mysqli_real_escape_string($con, $_POST['email']);
                $role = mysqli_real_escape_string($con, $_POST['role']);

                //verifying the unique email
                $verify_query = mysqli_query($con, "SELECT email FROM USERS where email = '$email';");

                if (mysqli_num_rows($verify_query) != 0) {
                    echo "<div class='message'>
                                                <p>This Email is already in use, Please try another one.</p>
                                        </div> <br/>";
                    echo "<a href='javascript:self.history.back()'><button class= 'btn'>Go Back</button>";
                } else {
                    $insert_query = "INSERT INTO USERS(username,password,email,fname,lname,role) VALUES('$username','$hashed_password','$email','$fname','$lname','$role')";
                    if (mysqli_query($con, $insert_query)) {
                        echo "<div class='message'>
                                                <p>Registration is Successful!</p>
                                        </div> <br/>";
                        echo "<a href='login.php'><button class= 'btn'>Login Now</button>";
                    } else {
                        echo "Error: " . $insert_query . "<br>" . mysqli_error($con);
                    }
                }
            } else {
            ?>

            <header>Sign Up</header>
            <form action="" method="post">
                <div class="field input">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" autocomplete="off" required>
                </div>
                <div class="field input">
                    <label for="password">Password </label>
                    <input type="password" name="password" id="password" autocomplete="off" required>
                </div>
                <div class="field input">
                    <label for="fname">First Name</label>
                    <input type="text" name="fname" id="fname" autocomplete="off" required>
                </div>
                <div class="field input">
                    <label for="lname">Last Name </label>
                    <input type="text" name="lname" id="lname" autocomplete="off" required>
                </div>
                <div class="field input">
                    <label for="email">Email </label>
                    <input type="email" name="email" id="email" autocomplete="off" required>
                </div>
                                <div class="field">
                    <label for="role">Choose your role</label>
                    <select name="role" id="role" required>
                       <option value="" disabled selected>Please select one of the following options</option>
                        <option value="Avid Listener">Avid Listener</option>
                        <option value="Indie Artist">Indie Artist</option>
                        <option value="Professional Reviewer">Professional Reviewer</option>
                    </select>
                </div>
                <div class="field">
                    <input type="submit" name="submit" class="btn" value="Register" required>
                </div>
                <div class="links">
                    Already a member? <a href="login.php">Login</a>
                </div>
            </form>
            <?php } ?>
            <div class="navigate">
                    <a href="home.php">Home</a>
            </div>
        </div>
    </div>
</body>

</html>

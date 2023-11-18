<?php

session_start();

// Include database configuration
include "dbconfig.php";

// Connect to the database
$con = mysqli_connect($server, $login, $password, $dbname);

// Check the connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}
if(!isset($_SESSION['valid'])){
    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html>
<head>
                <meta charset="UTF-8">
                <meta http-equiv="X-UA-Compatible" content="IE-edge">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <link rel="stylesheet" href="style.css">
</head>
<body>
                <div class="container">
                        <div class="box form-box">

<?php
if(isset($_POST['submit'])){
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    $id = $_SESSION['id'];

    $edit_query = mysqli_query($con,"UPDATE USERS SET username='$username',email='$email', role='$role' WHERE id = '$id' ") or die("error occurred");
    if($edit_query){
        echo "<div class='message'>
              <p>Profile Updated</p>
              </div> <br/>";
        echo "<a href='home.php'><button class= 'btn'>Go Home</button>";
    }
} else {
    $id = $_SESSION['id'];
    $query = mysqli_query($con,"SELECT * FROM USERS WHERE id = $id");

    while($result = mysqli_fetch_assoc($query)){
        $res_Uname = $result['username'];
        $res_Email = $result['email'];
        $res_Role = $result['role'];
    }
?>
                                <header>Change Profile</header>
                                <form action="" method="post">
                                        <div class="field input">
                                                <label for="username">Username: </label>
                                                <input type="text" name="username" id="username" value="<?php echo $res_Uname; ?>" autocomplete="on" required>
                                        </div>
                                        <div class="field input">
                                                <label for="password">Email: </label>
                                                <input type="text" name="email" id="email" value="<?php echo $res_Email; ?>" autocomplete="on" required>
                                        </div>
                                        <div class="field">
    <label for="role">Role:</label>
    <select name="role" id="role" required>
        <option value="" disabled selected>Please select one of the following options</option>
        <option value="Avid Listener">Avid Listener</option>
        <option value="Indie Artist">Indie Artist</option>
        <option value="Professional Reviewer">Professional Reviewer</option>
    </select>
</div>

                                                <div class="field">
                                                <input type="submit" name="submit" class="btn" value="Update" required>
                                        </div>
                                        <div class="navigate">
                    <a href="genProfileSignedIn.php">Home</a>
                </div>

    </form>
</div> 
<?php } ?>

</body>
</html>

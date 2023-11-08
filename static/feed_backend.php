<?php
include 'dbconfig.php';
session_start();
$con=mysqli_connect($server,$login,$password,$dbname)
    or die("<br>Cannot connect to DB\n");

if(isset($_POST['feed'])){
    $feed = $_POST['feed'];
    $username = $_POST['user'];
    
    // Getting the user id from the username
    $user_id_query = "SELECT id FROM USERS WHERE username='$username'";
    $user_id_execution = mysqli_query($con, $user_id_query);
    $user_id = mysqli_fetch_row($user_id_execution);

    // Inserting the feed into the database
    $query = "INSERT INTO FEED (user_id, feed) VALUES ($user_id[0], '$feed')";
    $result = mysqli_query($con, $query);

    echo "Post added successfully.";
}
mysqli_close($con);
?>
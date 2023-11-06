<?php
session_start();
include "dbconfig.php";
// Create a connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Attemping with us with id 'i'
$userID = 1; 

// Query to get the user's name
$sql = "SELECT fname FROM USERS WHERE id = $userID";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Fetch the user's name
    $row = $result->fetch_assoc();
    $userName = $row["fname"];
} else {
    $userName = "User not found"; // Handle the case when the user is not found
}

// Close the database connection
$conn->close();
?>

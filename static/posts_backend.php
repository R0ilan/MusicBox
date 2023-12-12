<?php
 include "dbconfig.php";
 session_start();
 $con = mysqli_connect($server, $login, $password, $dbname)
     or die("<br>Cannot connect to DB\n");

 // Function to post
 function post($con, $id, $content)
 {
     $sql = "INSERT INTO POSTS (user_id, content, date) VALUES ($id, '$content', NOW())";
     if (mysqli_query($con, $sql)) {
         echo "New record created successfully";
     } else {
         echo "Error: " . $sql . "<br>" . mysqli_error($con);
     }
 }
 // Function to edit post
 function editPost($con, $id, $newContent)
 {
     $sql = "UPDATE POSTS SET content='$newContent', date=NOW() WHERE id=$id";
     if (mysqli_query($con, $sql)) {
         echo "Record updated successfully";
     } else {
         echo "Error updating record: " . mysqli_error($con);
     }
 }

 // Function to delete post
 function deletePost($con, $id)
 {
     $sql = "DELETE FROM POSTS WHERE id=$id";
     if (mysqli_query($con, $sql)) {
         echo "Record deleted successfully";
     } else {
         echo "Error deleting record: " . mysqli_error($con);
     }
 }

 if (isset($_POST['create-post'])) {
     $content = $_POST['create-post'];
     $id = $_SESSION['id'];
     #$id=2;
     post($con, $id, $content);
 } elseif (isset($_POST['delete-post'])) {
     for ($i = 0; $i < count($_POST['post-id']); $i++) {
         $id = $_POST['post-id'][$i];
         deletePost($con, $id);
     }
     #$id = $_POST['post-id']; // Use a hidden field in your form to send the post id
     #deletePost($con, $id);
 }

 header("Location: home.php");
 mysqli_close($con);
 ?>
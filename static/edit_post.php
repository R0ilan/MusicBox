<?php
 include "dbconfig.php";
 session_start();
 $con = mysqli_connect($server, $login, $password, $dbname)
     or die("<br>Cannot connect to DB\n");

 function editPost($con, $id, $newContent)
 {
     $sql = "UPDATE POSTS SET content='$newContent', date=NOW() WHERE id=$id";
     if (mysqli_query($con, $sql)) {
         echo "Record updated successfully";
     } else {
         echo "Error updating record: " . mysqli_error($con);
     }
 }

 if (isset($_POST['edited-post'])) {
     $post_id = $_POST['post-id'];
     $newContent = $_POST['edited-post'];
     editPost($con, $post_id, $newContent);
     #relocate to home after 2 seconds
     echo "<br>Redirecting to home page in 2 seconds...";
     header("Refresh:2; url=home.php");
 }
 else {
     for ($i = 0; $i < count($_POST['post-id']); $i++) {
         $post_id = $_POST['post-id'][$i];
         echo "Edit your post: ";
         echo "<form action='edit_post.php' method='post'>";
         echo "<input type='text' name='edited-post'>";
         echo "<input type='hidden' name='post-id' value='$post_id'>";
         echo "<button type='submit' value='Update'>Update</button>";
         echo "</form>";
     }
 }
 ?>
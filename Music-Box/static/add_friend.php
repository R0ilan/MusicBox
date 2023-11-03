<?php
    include "dbconfig.php";

    // Default to false to indicate failure
    $php_return = "false";

    session_start();

    // User ID of currently logged-in user is "id" session variable
    if (!isset($_SESSION["id"]) or !isset($_POST["friend_user_id"]))
    {
        exit("false"); // Failed
    }

    $mysql = mysqli_connect($server, $login, $password, $dbname);

    if (!$mysql)
    {
        exit("false"); // Failed
    }

    $stmt = mysqli_prepare($mysql, "INSERT INTO FRIENDS (user_id, friend_user_id) VALUES (?, ?);");
    mysqli_stmt_bind_param($stmt, "ii", $_SESSION["id"], $_POST["friend_user_id"]);

    if (mysqli_stmt_execute($stmt))
    {
        $php_return = "true"; // Success
        mysqli_stmt_free_result($stmt);
    }

    mysqli_close($mysql);

    echo $php_return;
?>
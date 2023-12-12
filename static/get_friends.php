<?php
    include "dbconfig.php";

    // Default to false to indicate failure
    $php_return = "false";

    session_start();

    // User ID of currently logged-in user is "id" session variable
    if (!isset($_SESSION["id"]))
    {
        exit("false"); // Failed
    }

    $mysql = mysqli_connect($server, $login, $password, $dbname);

    if (!$mysql)
    {
        exit("false"); // Failed
    }

    // Get all friends for this user
    $stmt = mysqli_prepare($mysql,
        "SELECT id, username, CONCAT(fname, ' ', lname) AS name, role
        FROM USERS WHERE id IN
            (SELECT friend_user_id
            FROM FRIENDS
            WHERE user_id=?);");
    mysqli_stmt_bind_param($stmt, "i", $_SESSION["id"]);
    mysqli_stmt_bind_result($stmt, $result_id, $result_username, $result_name, $result_role);

    if (mysqli_stmt_execute($stmt))
    {
        // Will be empty if no results are found
        $php_return = array("friends" => []);

        while (mysqli_stmt_fetch($stmt))
        {
            array_push($php_return["friends"], array(
                "id" => $result_id,
                "username" => $result_username,
                "name" => $result_name,
                "role" => $result_role
            ));
        }

        mysqli_stmt_free_result($stmt);
    }

    mysqli_close($mysql);

    echo json_encode($php_return);
?>
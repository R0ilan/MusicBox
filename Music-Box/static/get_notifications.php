<?php
    include "dbconfig.php";

    // Default to false to indicate failure
    $php_return = "false";

    if (!isset($_POST["user_id"]))
    {
        exit("false"); // Failed
    }

    $mysql = mysqli_connect($server, $login, $password, $dbname);

    if (!$mysql)
    {
        exit("false"); // Failed
    }

    // Get all notications for this user as associative array for JSON decoding
    $stmt = mysqli_prepare($mysql, "SELECT notification_id, notification_text, notification_time FROM NOTIFICATIONS WHERE user_id=?;");
    mysqli_stmt_bind_param($stmt, "i", $_POST["user_id"]);
    mysqli_stmt_bind_result($stmt, $result_notification_id, $result_notification_text, $result_notification_time);

    if (mysqli_stmt_execute($stmt))
    {
        // Will be empty if no results are found
        $php_return = array("notifications" => []);

        while (mysqli_stmt_fetch($stmt))
        {
            array_push($php_return["notifications"], array(
                "notification_id" => $result_notification_id,
                "notification_text" => $result_notification_text,
                "notification_time" => $result_notification_time
            ));
        }
    }

    echo json_encode($php_return);
?>
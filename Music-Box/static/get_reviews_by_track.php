<?php
    include "dbconfig.php";

    // Default to false to indicate failure
    $php_return = "false";

    session_start();

    if (!isset($_POST["track_spotify_id"]))
    {
        exit("false"); // Failed
    }

    $mysql = mysqli_connect($server, $login, $password, $dbname);

    if (!$mysql)
    {
        exit("false"); // Failed
    }

    // Get all notications for this user as associative array for JSON decoding
    $stmt = mysqli_prepare($mysql, "SELECT review_id, user_id, track_spotify_id, review_text, review_time, review_rating FROM REVIEWS WHERE track_spotify_id=?;");
    mysqli_stmt_bind_param($stmt, "s", $_POST["track_spotify_id"]);
    mysqli_stmt_bind_result($stmt, $result_review_id, $result_user_id, $result_track_spotify_id, $result_review_text, $result_review_time, $result_review_rating);

    if (mysqli_stmt_execute($stmt))
    {
        // Will be empty if no results are found
        $php_return = array("reviews" => []);

        while (mysqli_stmt_fetch($stmt))
        {
            array_push($php_return["reviews"], array(
                "review_id" => $result_review_id,
                "user_id" => $result_user_id,
                "track_spotify_id" => $result_track_spotify_id,
                "review_text" => $result_review_text,
                "review_time" => $result_review_time,
                "review_rating" => $result_review_rating
            ));
        }
    }

    echo json_encode($php_return);
?>
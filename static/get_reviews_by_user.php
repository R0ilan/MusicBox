<?php
    include "dbconfig.php";
    include "spotify_api_config.php";

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

    // Get all notications for this user as associative array for JSON decoding
    $stmt = mysqli_prepare($mysql, "SELECT review_id, user_id, track_spotify_id, review_text, review_time FROM REVIEWS WHERE user_id=?;");    
    mysqli_stmt_bind_param($stmt, "i", $_SESSION["id"]);
    mysqli_stmt_bind_result($stmt, $result_review_id, $result_user_id, $result_track_spotify_id, $result_review_text, $result_review_time);
    
    if (mysqli_stmt_execute($stmt))
    {
        // Will be empty if no results are found
        $php_return = array("reviews" => []);

        while (mysqli_stmt_fetch($stmt))
        {
            // Get track information from Spotify API and append to output
            $track = spotify_api_get_request("v1/tracks/" . $result_track_spotify_id);

            if ($track)
            {
                array_push($php_return["reviews"], array(
                    "review_id" => $result_review_id,
                    "user_id" => $result_user_id,
                    "track" => $track,
                    "review_text" => $result_review_text,
                    "review_time" => $result_review_time
                ));
            }
        }
    }
    echo json_encode($php_return);
?>
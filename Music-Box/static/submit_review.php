<?php
    include "dbconfig.php";
    include "spotify_api_config.php";

    // Default to false to indicate failure
    $php_return = "false";

    session_start();

    // User ID of currently logged-in user is "id" session variable
    if (!isset($_SESSION["id"]) or !isset($_POST["review_text"]) or !isset($_POST["track_spotify_id"]))
    {
        exit("false"); // Failed
    }

    $mysql = mysqli_connect($server, $login, $password, $dbname);

    if (!$mysql)
    {
        exit("false"); // Failed
    }

    // Insert review into database
    $stmt = mysqli_prepare($mysql,
        "INSERT INTO REVIEWS (user_id, track_spotify_id, review_text, review_time) VALUES (?, ?, ?, Now());");
    mysqli_stmt_bind_param($stmt, "iss", $_SESSION["id"], $_POST["track_spotify_id"], $_POST["review_text"]);

    if (mysqli_stmt_execute($stmt))
    {
        // Check if reviewer is a professional reviewer. If so, artists will have to be notified.
        mysqli_stmt_free_result($stmt);
        $stmt = mysqli_prepare($mysql, "SELECT role FROM USERS WHERE id=?");
        mysqli_stmt_bind_param($stmt, "i", $_SESSION["id"]);
        mysqli_stmt_bind_result($stmt, $result_role);

        if (mysqli_stmt_execute($stmt))
        {
            if (mysqli_stmt_fetch($stmt))
            {
                $php_return = "true"; // Success

                if ($result_role == "Professional Reviewer")
                {
                    // Get artists for this track from Spotify API
                    $track = spotify_api_get_request("v1/tracks/" . $_POST["track_spotify_id"]);

                    if ($track and isset($track["artists"]) and isset($track["name"]))
                    {
                        // Extract artist IDs
                        $artist_ids = [];
                        foreach ($track["artists"] as $artist)
                        {
                            if (isset($artist["id"]))
                            {
                                array_push($artist_ids, $artist["id"]);
                            }
                        }

                        // Find which of these artists have a user on MusicBox
                        $notification_message = "A professional reviewer has reviewed your track " . $track["name"];
                        $bind_str = "?";
                        $bind_str = $bind_str . str_repeat(",?", sizeof($artist_ids) - 1);
                        mysqli_stmt_free_result($stmt);
                        $stmt = mysqli_prepare($mysql, "SELECT user_id FROM USERS_ARTISTS WHERE artist_spotify_id IN ($bind_str)");
                        mysqli_stmt_bind_param($stmt, str_repeat("s", sizeof($artist_ids)), ...$artist_ids);
                        mysqli_stmt_bind_result($stmt, $result_user_id);

                        if (mysqli_stmt_execute($stmt))
                        {
                            $artist_user_ids = [];
                            while (mysqli_stmt_fetch($stmt))
                            {
                                array_push($artist_user_ids, $result_user_id);
                            }

                            // Notify artists
                            foreach ($artist_user_ids as $artist_user_id)
                            {
                                mysqli_stmt_free_result($stmt);
                                $stmt = mysqli_prepare($mysql, "INSERT INTO NOTIFICATIONS (user_id, notification_text, notification_time) VALUES (?, ?, Now())");
                                mysqli_stmt_bind_param($stmt, "is", $artist_user_id, $notification_message);
                                mysqli_stmt_execute($stmt);
                            }
                        }
                    }
                }
            }

            mysqli_stmt_free_result($stmt);
        }
    }

    mysqli_close($mysql);

    echo $php_return;
?>
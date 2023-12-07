<?php
    include "dbconfig.php";
    include "spotify_api_config.php";

    // Default to false to indicate failure
    $php_return = "false";

    session_start();

    // User ID of currently logged-in user is "id" session variable
    if (!isset($_SESSION["id"]) or !isset($_POST["number_of_tracks"]))
    {
        exit("false"); // Failed
    }

    $mysql = mysqli_connect($server, $login, $password, $dbname);

    if (!$mysql)
    {
        exit("false"); // Failed
    }

    // Get recently played tracks in order of most recent first up to a limited number of tracks
    $stmt = mysqli_prepare($mysql, "SELECT track_spotify_id, play_count, last_played_time FROM PLAYED_TRACKS WHERE user_id=? ORDER BY last_played_time DESC LIMIT ?;");
    mysqli_stmt_bind_param($stmt, "ii", $_SESSION["id"], $_POST["number_of_tracks"]);
    mysqli_stmt_bind_result($stmt, $result_track_spotify_id, $result_play_count, $result_last_played_time);

    if (mysqli_stmt_execute($stmt))
    {
        // Will be empty if no results are found
        $php_return = array("recently_played_tracks" => []);

        while (mysqli_stmt_fetch($stmt))
        {
            // Get track information from Spotify API and append to output
            $track = spotify_api_get_request("v1/tracks/" . $result_track_spotify_id);

            if ($track)
            {
                array_push($php_return["recently_played_tracks"], array(
                    "track" => $track,
                    "play_count" => $result_play_count,
                    "last_played_time" => $result_last_played_time
                ));
            }
        }

        mysqli_stmt_free_result($stmt);
    }

    mysqli_close($mysql);

    echo json_encode($php_return);
?>
<?php
    include "spotify_api_config.php";

    // Default to false to indicate failure
    $php_return = "false";

    if (!isset($_POST["search_limit"]) or !isset($_POST["search_q"]))
    {
        exit("false"); // Failed
    }

    $php_return = spotify_api_get_request("v1/search?type=track&q=" . urlencode($_POST["search_q"]) . "&limit=" . $_POST["search_limit"]);

    // Spotify search API does not allow filtering by explicit flag, so do it here.
    // Note: checkbox inputs are not submitted if unchecked.
    if (!isset($_POST["search_explicit"]))
    {
        // Recreate list of tracks to only include non-explicit tracks
        $new_tracks = array();
        
        if (isset($php_return["tracks"]) and isset($php_return["tracks"]["items"]))
        {
            foreach ($php_return["tracks"]["items"] as $track)
            {
                if (!$track["explicit"])
                {
                    array_push($new_tracks, $track);
                }
            }

            $php_return["tracks"]["items"] = $new_tracks;
        }
    }

    echo json_encode($php_return);
?>
<?php
    include "spotify_api_config.php";

    // Default to false to indicate failure
    $php_return = "false";

    if (!isset($_POST["search_limit"]) or !isset($_POST["search_q"]))
    {
        exit("false"); // Failed
    }

    $php_return = spotify_api_get_request("v1/search?type=track&q=" . urlencode($_POST["search_q"]) . "&limit=" . $_POST["search_limit"]);

    echo json_encode($php_return);
?>
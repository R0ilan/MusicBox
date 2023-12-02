<?php
    // Gets Spotify access token from cookie or creates a new one if not set. Returns the token
    // or false if it failed.
    function get_spotify_access_token()
    {
        $access_token = false; // Default to false to indicate failure

        // First check for cookie with token
        if (isset($_COOKIE["spotify_api_access_token"]))
        { 
            $access_token = $_COOKIE["spotify_api_access_token"];
        }
        else
        {
            // Client ID and secret are from Spotify app
            $request_options = array(
                "http" => array(
                    "method" => "POST",
                    "header" => "Content-Type: application/x-www-form-urlencoded",
                    "content" => http_build_query(
                        array(
                            "grant_type" => "client_credentials",
                            "client_id" => "e3496a408cac4396bfd7ed52eac80fc2",
                            "client_secret" => "d7809ff97c5641eb8fa816d55d04e564"
                        )
                    )
                )
            );

            $context = stream_context_create($request_options);
            $credentials_str = file_get_contents("https://accounts.spotify.com/api/token", false, $context);
            $credentials = json_decode($credentials_str, true);

            // If successful, then set cookie and set return value 
            if ($credentials and isset($credentials["access_token"]) and isset($credentials["expires_in"]))
            {
                setcookie("spotify_api_access_token", $credentials["access_token"], time() + $credentials["expires_in"]);
                $access_token = $credentials["access_token"];
            }
        }

        return $access_token;
    }

    // Makes an HTTP GET request to spotify API.
    // $url_sub: URL portion appended to "https://api.spotify.com/".
    function spotify_api_get_request($url_sub)
    {
        $data = false; // Default to false to indicate failure

        $access_token = get_spotify_access_token();

        if ($access_token)
        {
            $request_options = array(
                "http" => array(
                    "header" => "Authorization: Bearer " . $access_token
                )
            );

            $context = stream_context_create($request_options);
            $data_str = file_get_contents("https://api.spotify.com/" . $url_sub, false, $context);
            $data = json_decode($data_str, true);
        }

        return $data;
    }
?>
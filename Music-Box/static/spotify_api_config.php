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
            $token_url = "https://accounts.spotify.com/api/token";

            $ch = curl_init($token_url);
    
            if ($ch)
            {
                // Client ID and secret are from Spotify app
                $client_id = "e3496a408cac4396bfd7ed52eac80fc2";
                $client_secret = "d7809ff97c5641eb8fa816d55d04e564";
                $http_header = array("Content-Type: application/x-www-form-urlencoded");
                $http_body = "grant_type=client_credentials&client_id=$client_id&client_secret=$client_secret";

                // Make POST request using client ID and secret to get token
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $http_header);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $http_body);
    
                $credentials_str = curl_exec($ch);
    
                if ($credentials_str)
                {
                    $credentials = json_decode($credentials_str, true);

                    // If successful, then set cookie and set return value 
                    if ($credentials and isset($credentials["access_token"]) and isset($credentials["expires_in"]))
                    {
                        setcookie("spotify_api_access_token", $credentials["access_token"], time() + $credentials["expires_in"]);
                        $access_token = $credentials["access_token"];
                    }
                }

                curl_close($ch);
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
            $ch = curl_init("https://api.spotify.com/" . $url_sub);

            if ($ch)
            {
                $http_header = ["Authorization: Bearer " . $access_token];

                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $http_header);

                $data_str = curl_exec($ch);

                if ($data_str)
                {
                    $data = json_decode($data_str, true);
                }
            }
        }

        return $data;
    }
?>
<?php 
session_start(); // Ensure session is started

// Check if user is authenticated
if (!isset($_SESSION['valid'])) {
    header("Location: login.php"); // Redirect to login.php since index.php doesn't exist
    exit;
}

include "dbconfig.php"; // Include database configuration
$con = mysqli_connect($server, $login, $password, $dbname); // Establish a database connection

// Check if ID is set in the session, redirect if not
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

?>

<!DOCTYPE html>
<html>
<head><script src="js/color-modes.js"></script>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
<meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
<meta name="generator" content="Hugo 0.118.2">
<title>Music Box Bootstrap</title>

<link rel="canonical" href="https://getbootstrap.com/docs/5.3/examples/blog-rtl/">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@docsearch/css@3">

<link href="css/bootstrap.rtl.min.css" rel="stylesheet">
<!-- Custom styles for this template -->
<link href="https://fonts.googleapis.com/css?family=Amiri:wght@400;700&amp;display=swap" rel="stylesheet">
<!-- Custom styles for this template -->
<link href="css/blog.rtl.css" rel="stylesheet">
<style>
    .ft{
        text-align: center;
    }
    
        .main-search-input {
            padding: 0 10px 0 0;
            border-radius: 1px;
            margin-top: 20px;
        }

    
        .main-search-input:before {
            content: '';
            position: absolute;
            width: 50px;
            height: 1px;
            background: rgba(255,255,255,0.41);
            left: 50%;
            margin-left: -25px;
        }

        .main-search-input-item {
            float: left;
            width: 100%;
            border: 1px solid rgba(116, 116, 116, 0.41);
            box-sizing: border-box;
            height: 50px;
            position: relative;
        }

        .main-search-input-item input:first-child {
            border-radius: 100%;
        }

        .main-search-input-item input {
            float: left;
            border: none;
            width: 100%;
            height: 50px;
            padding-left: 20px;
        }

        .main-search-button{

            color: rgba(255,255,255,0.41) ;
        }

        .main-search-button {
            position: absolute;
            right: 0px;
            height: 50px;
            width: 120px;
            color: black;
            top: 0;
            border: none;
            border-top-right-radius: 0px;
            border-bottom-right-radius: 0px;
            cursor: pointer;
            font-family: 'Amiri';
            font-style: normal;
            font-weight: 400;
        }

        .main-search-input-wrap {
            max-width: 500px;
            margin: 20px auto;
            position: relative;
        }

        :focus {
            outline: 0;
        }


        @media only screen and (max-width: 768px){
                .main-search-input {
                    background: rgba(255,255,255,0.2);
                    padding: 14px 20px 10px;
                    border-radius: 10px;
                    box-shadow: 0px 0px 0px 10px rgba(255,255,255,0.0);
                }

                .main-search-input-item {
                    width: 100%;
                    height: 50px;
                    border: none;
                    margin-bottom: 10px;
                }

             
                .main-search-button {
                    position: relative;
                    float: left;
                    width: 100%;
                    border-radius: 6px;
                }
        }
        .searchInput::-webkit-input-placeholder {
            font-family: 'Amiri';
            font-style: normal;
            font-weight: 400;
        }

            .searchInput:-ms-input-placeholder {
                font-family: 'Amiri';
            font-style: normal;
            font-weight: 400;
        }

            .searchInput:-moz-placeholder {
                font-family: 'Amiri';
            font-style: normal;
            font-weight: 400;
        }

            .searchInput::-moz-placeholder {
                font-family: 'Amiri';
            font-style: normal;
            font-weight: 400;
        }

    *,*:before,*:after {
                          transition:.25s ease-in-out;
                        }


                        h1 {
                          font-size:30px;
                          text-align:center;
                          padding:0 0 25px 0;
                          color:#606060;
                        }

                        .checkbox-label {
                          display:block;
                          background:#f3f3f3;
                          height:20px;
                          width:50px;
                          border-radius:50px;
                          margin: auto;
                          position:relative;
                          box-shadow:0 0 0 2px #dddddd;
                          .on {
                            display:block;
                            position:absolute;
                            z-index:0;
                            left:0;
                            opacity:1;
                            min-width:100px;
                            opacity:0;
                            color:rgba(19,191,17,1);
                          }
                          .off {
                            display:block;
                            position:absolute;
                            z-index:0;
                            right:100px;
                            text-align:right;
                            opacity:1;
                            min-width:100px;
                            opacity:1;
                            color:#bbbbbb;
                          }
                          &:before {
                            content:'';
                            display:block;
                            position:absolute;
                            z-index:1;
                            top:0;
                            left:0;
                            border-radius:50px;
                            height:20px;
                            width:20px;
                            background:white;
                            box-shadow:0 3px 3px rgba(0,0,0,.2),0 0 0 2px #dddddd;
                          }
                        }

                        .checkbox {
                          position:absolute;
                          left:-5000px;
                          &:checked {
                            + .checkbox-label {
                              background:rgba(19,191,17,1);
                              box-shadow:0 0 0 2px rgba(19,191,17,1);
                              .on {
                                left:100px;
                                opacity:1;
                              }
                              .off {
                                right:0px;
                                opacity:0;
                              }
                              &:before {
                                left:30px;
                                box-shadow:0 3px 3px rgba(0,0,0,.2),0 0 0 2px rgba(19,191,17,1);
                              }
                            }
                          }
                        }
          img{
            height: 100px;
            width: 100px;
          }
          #profImg{
            height: 40px;
            width: 40px;
          }
    
          .results{
            margin: auto;
            width: 50%;
          }

    
</style>

<!--JQuery-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    jQuery(document).ready(function($) {
      $('[name="searchInput"]').on("submit", function(e) {
        e.preventDefault();
        $.ajax({
          url: 'search_tracks.php',
          type: 'post',
          data: $('[name="searchInput"]').serialize(),
          dataType: 'json',
          success: function(response) {
            // First clear current results
            $('.results').empty();

            console.log(response); // DEBUG

            // Dynamically create
            if (response && 'tracks' in response && 'items' in response['tracks']) {
              for (track of response['tracks']['items']) {
                let divTrack = document.createElement('div');
                // divTrack.setAttrbute('class', );  TODO: make a class for this

                let imgAlbum = document.createElement('img');

                if (track['album']['images'].length > 0) {
                  imgAlbum.setAttribute('src', track['album']['images'][0]['url']);
                }

                divTrack.appendChild(imgAlbum);

                let pTrackName = document.createElement('p');
                pTrackName.textContent = track['name'];
                divTrack.appendChild(pTrackName);

                let pAlbumName = document.createElement('p');
                pAlbumName.textContent = track['album']['name'];
                divTrack.appendChild(pAlbumName);

                if (track['explicit']) {
                  let pExplicit = document.createElement('p');
                  pExplicit.textContent = '(Explicit)';
                  divTrack.appendChild(pExplicit);
                }

                $('.results').append(divTrack);
              }
            }
          },
          error: () => {
            console.log('search_tracks.php failed.');
          }
        });
      });
    });
</script>
</head>

<body>
    <svg xmlns="http://www.w3.org/2000/svg" class="d-none">
      <symbol id="check2" viewBox="0 0 16 16">
        <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
      </symbol>
      <symbol id="circle-half" viewBox="0 0 16 16">
        <path d="M8 15A7 7 0 1 0 8 1v14zm0 1A8 8 0 1 1 8 0a8 8 0 0 1 0 16z"/>
      </symbol>
      <symbol id="moon-stars-fill" viewBox="0 0 16 16">
        <path d="M6 .278a.768.768 0 0 1 .08.858 7.208 7.208 0 0 0-.878 3.46c0 4.021 3.278 7.277 7.318 7.277.527 0 1.04-.055 1.533-.16a.787.787 0 0 1 .81.316.733.733 0 0 1-.031.893A8.349 8.349 0 0 1 8.344 16C3.734 16 0 12.286 0 7.71 0 4.266 2.114 1.312 5.124.06A.752.752 0 0 1 6 .278z"/>
        <path d="M10.794 3.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387a1.734 1.734 0 0 0-1.097 1.097l-.387 1.162a.217.217 0 0 1-.412 0l-.387-1.162A1.734 1.734 0 0 0 9.31 6.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387a1.734 1.734 0 0 0 1.097-1.097l.387-1.162zM13.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.156 1.156 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.156 1.156 0 0 0-.732-.732l-.774-.258a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732L13.863.1z"/>
      </symbol>
      <symbol id="sun-fill" viewBox="0 0 16 16">
        <path d="M8 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0zm0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13zm8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5zM3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8zm10.657-5.657a.5.5 0 0 1 0 .707l-1.414 1.415a.5.5 0 1 1-.707-.708l1.414-1.414a.5.5 0 0 1 .707 0zm-9.193 9.193a.5.5 0 0 1 0 .707L3.05 13.657a.5.5 0 0 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0zm9.193 2.121a.5.5 0 0 1-.707 0l-1.414-1.414a.5.5 0 0 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .707zM4.464 4.465a.5.5 0 0 1-.707 0L2.343 3.05a.5.5 0 1 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .708z"/>
      </symbol>
    </svg>

    <div class="dropdown position-fixed bottom-0 end-0 mb-3 me-3 bd-mode-toggle">
      <button class="btn btn-bd-primary py-2 dropdown-toggle d-flex align-items-center"
              id="bd-theme"
              type="button"
              aria-expanded="false"
              data-bs-toggle="dropdown"
              aria-label="Toggle theme (auto)">
        <svg class="bi my-1 theme-icon-active" width="1em" height="1em"><use href="#circle-half"></use></svg>
        <span class="visually-hidden" id="bd-theme-text">Toggle theme</span>
      </button>
      <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="bd-theme-text">
        <li>
          <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="light" aria-pressed="false">
            <svg class="bi me-2 opacity-50 theme-icon" width="1em" height="1em"><use href="#sun-fill"></use></svg>
            Light
            <svg class="bi ms-auto d-none" width="1em" height="1em"><use href="#check2"></use></svg>
          </button>
        </li>
        <li>
          <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="dark" aria-pressed="false">
            <svg class="bi me-2 opacity-50 theme-icon" width="1em" height="1em"><use href="#moon-stars-fill"></use></svg>
            Dark
            <svg class="bi ms-auto d-none" width="1em" height="1em"><use href="#check2"></use></svg>
          </button>
        </li>
        <li>
          <button type="button" class="dropdown-item d-flex align-items-center active" data-bs-theme-value="auto" aria-pressed="true">
            <svg class="bi me-2 opacity-50 theme-icon" width="1em" height="1em"><use href="#circle-half"></use></svg>
            Auto
            <svg class="bi ms-auto d-none" width="1em" height="1em"><use href="#check2"></use></svg>
          </button>
        </li>
      </ul>
    </div>

    
<svg xmlns="http://www.w3.org/2000/svg" class="d-none">
  <symbol id="aperture" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24">
    <circle cx="12" cy="12" r="10"/>
    <path d="M14.31 8l5.74 9.94M9.69 8h11.48M7.38 12l5.74-9.94M9.69 16L3.95 6.06M14.31 16H2.83m13.79-4l-5.74 9.94"/>
  </symbol>
  <symbol id="cart" viewBox="0 0 16 16">
    <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .49.598l-1 5a.5.5 0 0 1-.465.401l-9.397.472L4.415 11H13a.5.5 0 0 1 0 1H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l.84 4.479 9.144-.459L13.89 4H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
  </symbol>
  <symbol id="chevron-right" viewBox="0 0 16 16">
    <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"/>
  </symbol>
</svg>

<div class="container">
  <header class="border-bottom lh-1 py-3">
    <div class="row flex-nowrap justify-content-between align-items-center">
      <div class="col-4 pt-1">
        <div class="dropdown">
          <button class="btn dropdown-toggle d-flex align-items-center"
                  type="button"
                  aria-expanded="false"
                  data-bs-toggle="dropdown">
                  <svg class="bi my-1 theme-icon-active" width="5px" height="5px"><img src="images/profImage.jpeg" width="40px" height="40px"></svg>
            <span class="visually-hidden">Settings</span>
          </button>
          <ul class="dropdown-menu dropdown-menu-end shadow">
            <li>
              <button type="button" class="dropdown-item d-flex align-items-center" aria-pressed="false">
                <svg class="bi me-2 opacity-50 theme-icon" width="1em" height="1em"></svg>
                Sign In
                <svg class="bi ms-auto d-none" width="1em" height="1em"></svg>
              </button>
            </li>
            <li>
              <button type="button" class="dropdown-item d-flex align-items-center" aria-pressed="false">
                <svg class="bi me-2 opacity-50 theme-icon" width="1em" height="1em"></svg>
                Sign Up
                <svg class="bi ms-auto d-none" width="1em" height="1em"></svg>
              </button>
            </li>
            
          </ul>
        </div>





      </div>
      <div class="col-4 text-center">
        <a class="blog-header-logo text-body-emphasis text-decoration-none" href="#">MUSIC BOX</a>
      </div>
      <div class="col-4 d-flex justify-content-end align-items-center">
        <a class="link-secondary" href="#" aria-label="">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="mx-3" role="img" viewBox="0 0 24 24"><title>TITLE</title><circle cx="10.5" cy="10.5" r="7.5"/><path d="M21 21l-5.2-5.2"/></svg>
        </a>
   
      </div>
    </div>
  </header>

  <div class="nav-scroller py-1 mb-3 border-bottom">
  <nav class="nav nav-underline justify-content-between">
      <a class="nav-item nav-link link-body-emphasis" href="home.php">HOME</a>
      <a class="nav-item nav-link link-body-emphasis" href="#">MUSIC</a>
      <a class="nav-item nav-link link-body-emphasis" href="#">MEMBERS</a>
      <a class="nav-item nav-link link-body-emphasis" href="faq.php">FAQ</a>
      <a class="nav-item nav-link link-body-emphasis" href="about.php">ABOUT</a> 
    </nav>
  </div>
</div>

<body>
    <div class="col-4 pt-1">
        <input class="checkbox" id="checkbox1" type="checkbox"/>
          <label for="checkbox1" class="checkbox-label">
              <span class="on">Explicit on.</span>
              <span class="off">Explicit off.</span>
          </label>
    </div>
    
    <div class="p-4 p-md-5 mb-4">
        <div class="main-search-input-wrap">

                <div class="main-search-input-item">
                    <form name="searchInput" action="musicSearch.php" method="post">
                    <input type="hidden" name="search_limit" value="25">
                    <input class ="searchInput" type="text"  name="search_q" placeholder="Search For Music!" font-family="Amiri">

                    <button id="submitBtn" type="submit" value="Search" class="main-search-button">Search</button> 
                        <script>
                          $(document).ready(function(){
                              $('#searchTxt').keypress(function(e){
                              if(e.keyCode==13)
                                $('#submitBtn').click();
                              });
                           });
                        </script>

                  </form>
               </div>
                                                
               
            </div>

    </div>

    <div class="row mb-2">
         <div class="border rounded">
            <!-- Backend results from the search should be shown here -->
            <div class="results">
                
            </div>
        </div>
    </div>


</body>

<script src="js/bootstrap.bundle.min.js"></script>

</html>

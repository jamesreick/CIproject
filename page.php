<?php 
   require 'index.php';
   include_once 'lastFM.php';
   include_once 'database.php';

   $conn = getDB();
   $lastFM = new LastFM($apiKey);
   $lastFM->updateDB();
   $lastFM->getTopArtists();
   $lastFM->getChartTopTracks();
   //$getArtistFeild = "drake";
   //$lastFM->getArtists($getArtistFeild);
   $artistRedirect = 'artistProfile.php';
   
   
   if(isset($_POST['submit'])){
       $getField = $_POST['getField'];
       if (preg_match('/\s/', $getField)){
           $getField = str_replace(" ", "+", $getField);
           $getField= strtolower($getField);
           $lastFM->getTopTracks($getField);
       }else{
           $lastFM->getTopTracks($getField);
       }
       header ('Location: http://localhost:8080/njtest/search/seachIndex.php');
   }
   
   
   ?>
<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta charset="utf-8" />
        <link href="https://font.goggleapis.com/css?fmaily=Work+Sans:300" />
        <link rel="stylesheet" href="css/styles.css" href="css/stylesChart.css" />
    </head>
    <title>Music Discovery</title>
    <body>
        <div id="element-1" class="element">Music Discovery</div>
        <div class="header"></div>
        <input type="checkbox" class="openSidebarMenu" id="openSidebarMenu" />
        <label for="openSidebarMenu" class="sidebarIconToggle">
            <div class="spinner diagonal part-1"></div>
            <div class="spinner horizontal"></div>
            <div class="spinner diagonal part-2"></div>
        </label>
        <div id="sidebarMenu">
            <ul class="sidebarMenuInner">
                <li><a href="http://localhost:8080/njtest/charts.php" target="_blank">Charts</a></li>
                <li><a href="https://instagram.com/plavookac" target="_blank">Music</a></li>
                <li><a href="https://twitter.com/plavookac" target="_blank">Music</a></li>
                <li><a href="https://www.youtube.com/channel/UCDfZM0IK6RBgud8HYGFXAJg" target="_blank">Music</a></li>
                <li><a href="https://www.linkedin.com/in/plavookac/" target="_blank">Music</a></li>
            </ul>
        </div>
        <canvas id="gradient-canvas" data-transition-in></canvas>
        <script type="module">
            import { Gradient } from "./css/Gradient.js";

            // Create your instance
            const gradient = new Gradient();

            // Call `initGradient` with the selector to your canvas
            gradient.initGradient("#gradient-canvas");
        </script>
        <div class="layout">
            <div class="container">
                <form action="" method="POST" class="search-bar">
                    <input type="text" placeholder="Search favorite artist, song, or album" name="getField" />
                    <button name="submit"><img src="css/images/search.png" /></button>
                </form>
            </div>
        </div>
        <p>Discover your favorite songs, artists, and ablums all at the click of a button.</p>
    </body>
</html>

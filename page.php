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
      <meta name= "viewport" content="width=device-width, initial-scale=1.0">
      <meta charset="utf-8">
      <link href="https://font.goggleapis.com/css?fmaily=Work+Sans:300">
      <link rel="stylesheet" href="css/styles.css">
   </head>
   <title>Music Discovery</title>
   <body>
      <div class="topnav">
         <a class="active" href="#home">Home</a>
         <a href="#news">Blank</a>
         <a href="#contact">Blank</a>
         <a href="#about">Blank</a>
      </div>
      <div class="layout">
         <div class="container">
            <form action="" method="POST" class="search-bar">
               <input type="text" placeholder="Search favorite artist, song, or album" name ="getField">
               <button name="submit"><img src="css/images/search.png"></button>
            </form>
         </div>
         <div class="row">
            <div class="column">
         <?php
            $sql = "SELECT *
                    FROM topartists";
  
            $results = mysqli_query($conn, $sql);
            
            if ($results === false){
            echo mysqli_error($conn);
            }else{
            $data = mysqli_fetch_all($results, MYSQLI_ASSOC);
            }?>
            <h1> Top Artists </h1>
            <table class="content-table">
               <thead>
                  <tr>
                     <th>Rank</th>
                     <th>Name</th>
                     <th>Listeners</th>
                     <th>Playcount</th>
                  </tr>
               </thead>
               <tbody>
                  <?php $i= 0; foreach ($data as $data): ?>
                  <tr>
                     <?php $i++;?>
                     <td><?php echo $i?></td>
                     <td><a href=<?= ($data['topArtistURL']); ?>><?= 
                        ($data['topArtistName']); ?></a> </td>
                     <td><?= $data['topArtistlisteners'];?> </td>
                     <td><?= $data['topArtistPlayCount'];?> </td>
                     <?php endforeach; ?>
                  </tr>
               </tbody>
            </table>
            </div>
            <div class="column">
            <?php
    
               $sql = "SELECT *
                       FROM topsongs";
      
               $results = mysqli_query($conn, $sql);
                
                if ($results === false){
                echo mysqli_error($conn);
                }else{
                $data = mysqli_fetch_all($results, MYSQLI_ASSOC);
                }?>
            <h2> Top Songs </h2>
            <table class="content-table-2">
               <thead>
                  <tr>
                     <th>Rank</th>
                     <th>Song</th>
                     <th>Listeners</th>
                     <th>Playcount</th>
                  </tr>
               </thead>
               <tbody>
                  <?php $i= 0; foreach ($data as $data): ?>
                  <tr>
                     <?php $i++;?>
                     <td><?php echo $i?></td>
                     <td><a href=<?= ($data['topChartArtistURL']); ?>><?= 
                        ($data['topChartSongName']); ?> - <?= $data['topChartArtistName'];?> </a> </td>
                     <td><?= $data['topChartSongListeners'];?> </td>
                     <td><?= $data['topChartSongPlayCount'];?> </td>
                     <?php endforeach; ?>
                  </tr>
               </tbody>
            </table>
            </div>
         </div>
      </div>
   </body>
</html>
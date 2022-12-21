<?php 
   include_once 'lastFM.php';
   include_once 'database.php';
   include_once 'config.php';

   $conn = getDB();
   $lastFM = new LastFM($apiKey);
   $lastFM->updateDB();
   $lastFM->getTopArtists();
   $lastFM->getChartTopTracks();
   //$lastFM->youtubeVideo($q);
   //$getArtistFeild = "drake";
   //$lastFM->getArtists($getArtistFeild);
   $artistRedirect = 'artistProfile.php';
   
   
   if(isset($_POST['submit'])){
       $getField = $_POST['getField'];
       $q = $_POST['getField'];
       $search = $_POST['getField'];
       $lastFM->search_function($search);
       $python_return = exec("python index.py $getField");
       //echo $python_return;
       if (preg_match('/\s/', $q)){
           $q = str_replace(" ", "+", $getField);
           $q= strtolower($q);
           $lastFM->youtubeVideo($q);
           
       }else{
            $lastFM->youtubeVideo($q);
       }
       //echo $getField;
       header ('Location: http://localhost/CIproject/searchIndex.php');
   }
   
   
   ?>
<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta charset="utf-8" />
        <link href="https://font.goggleapis.com/css?fmaily=Work+Sans:300" />
        <link rel="stylesheet" href="css/styles.css"/>
        <link rel="stylesheet" href="css/stylesChart.css"/>
    </head>
    <body>
    <a href="index.php">
            <div id="element-1" class="element">Music Discovery</div>
        </a>
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
        <div class = bottomLayout>
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
            <h1>Top Artists</h1>
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

<?php

require 'lastFM.php';
$conn = getDB();
$lastFM = new LastFM($apiKey);

    if(isset($_POST['submit'])){
        $lastFM->updateDB();
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
        <link rel="stylesheet" href="css/styles.css" />
        <link rel="stylesheet" href="css/newstyles.css" />
        <link rel="stylesheet" href="css/search-index-chart.css" />
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
        <div class="media-player">
        <?php
            $sql = "SELECT *
                    FROM videos";
            $results = mysqli_query($conn, $sql);
            if ($results === false){
                echo mysqli_error($conn);
                }else{
                $data = mysqli_fetch_all($results, MYSQLI_ASSOC);
                }

            foreach($data as $data){
                echo "<div class='col-md-6'>";
                echo '<iframe width="560" height="315"
                src="https://youtube.com/embed/' . $data['video_id'] . '"
                frameborder="0" allow="accelerometer"; encrypted-media;
                gyroscope; picture-in-picture" allowfullscreen></iframe>';
                echo "</div>";
            }
        echo "</div>";
            ?>
            <h1>Top Songs</h1>
         <div class="row">
            <div class="column">
         <?php
            $sql = "SELECT *
                    FROM genius";
  
            $results = mysqli_query($conn, $sql);
            
            if ($results === false){
            echo mysqli_error($conn);
            }else{
            $data = mysqli_fetch_all($results, MYSQLI_ASSOC);
            }?>
            <table class="content-table">
               <thead>
                  <tr>
                     <th></th>
                     <th>Title</th>
                     <th>Name</th>
                     <th>Lyrics</th>
                  </tr>
               </thead>
               <tbody>
                  <?php foreach ($data as $data): ?>
                  <tr>
                      <td><img src="<?php echo $data['IMAGE_URL']; ?>"></td>
                     <td><?= $data['TITLE'];?> </td>
                          <td><?= $data['ARTIST_NAME'];?> </td>
                          <td><a href="<?= $data['LYRICS_PATH'];?>" target="_blank">Lyrics</a></td>
                     <?php endforeach; ?>
                  </tr>
               </tbody>
            </table>
            </div>
         </div>
        </div>
            <div class = bottomLayout>
                <div class="table-header">Related Artists</div>
        <div class="table-users">
        
            <?php
            $sql = "SELECT *
                    FROM spotify_artist";
  
            $results = mysqli_query($conn, $sql);
            
            if ($results === false){
            echo mysqli_error($conn);
            }else{
            $data = mysqli_fetch_all($results, MYSQLI_ASSOC);
            }?>
            <?php $i=0; foreach ($data as $data): ?>
                     <ul>
                        <li><img src= <?php echo $data['artist_image']; ?> alt="" /></td>
                        <li><?= $data['artist_name'];?> </td>
                     </ul>
                     <?php if (++$i == 5)
                    break; endforeach; ?>


        

    </body>
</html>

<?php 
   include_once 'index.php'; 

   $conn = getDB();
?>

<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta charset="utf-8" />
        <link href="https://font.goggleapis.com/css?fmaily=Work+Sans:300" />
        <link rel="stylesheet" href="css/searchstyles.css" />
    </head>
    <body>
        <div class="table-users">
        <div class="table-header">Related Artists</div>
        
        <table cellspacing="0">
            <tr>
                <th>Rank</th>
                <th></th>
                <th>Artist</th>
                <th>Followers</th>
            </tr>
            <?php
            $sql = "SELECT *
                    FROM spotify_artist";
  
            $results = mysqli_query($conn, $sql);
            
            if ($results === false){
            echo mysqli_error($conn);
            }else{
            $data = mysqli_fetch_all($results, MYSQLI_ASSOC);
            }?>
            
            <?php $i= 0; foreach ($data as $data): ?>
                  <tr>
                     <?php $i++;?>
                     <td><?php echo $i?></td>
                     <td><img src= <?php echo $data['artist_image']; ?> alt="" /></td>
                     <td><?= $data['artist_name'];?> </td>
                     <td><?= $data['follower'];?> </td>
                     <?php endforeach; ?>
                  </tr>
        </table>
        </div>

        

    </body>
</html>

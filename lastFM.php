<?php 
require 'database.php';
require 'config.php';
class LastFM{

    public $apiKey;

    function __construct($api){
        $this->apiKey = $api;
    }

    function getChartTopTracks(){
        $conn = getDB();

        $curl = curl_init('http://ws.audioscrobbler.com/2.0/?method=chart.gettoptracks&api_key='.$this->apiKey.'&format=json');

        curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl,CURLOPT_HEADER,0);
        curl_setopt($curl,CURLOPT_TIMEOUT,3);
        $topChartTrackData = curl_exec($curl);
        curl_close($curl);
        //echo $data;

        $topChartArtistsString = json_decode($topChartTrackData, true);
        //var_dump($topChartArtistsString);
            

           $topChartArtistsString = $topChartArtistsString['tracks']['track'];
            for($i=0; $i<10; $i++){
                $items = $topChartArtistsString[$i];
                $topChartSongName = mysqli_real_escape_string($conn ,$items['name']);
                //echo "</br>Name: ".$items['name'];
                $topChartSongPlayCount = $items['playcount'];
                //echo " Playcount: ".$items['playcount'];
                $topChartSongListeners = $items['listeners'];
                $topChartArtistURL = $items['url'];
                //echo " URL: ".$items['url'];
                $topChartArtistName = mysqli_real_escape_string($conn ,$items['artist']['name']);
                //echo "</br>Name: ".$items['artist']['name'];

                $sql =  "INSERT INTO topsongs
                (topChartSongName, topChartSongPlayCount, topChartSongListeners, topChartArtistURL, topChartArtistName)
                VALUES
                ('$topChartSongName', $topChartSongPlayCount, $topChartSongListeners, '$topChartArtistURL', '$topChartArtistName')";
                mysqli_query($conn, $sql);

            }
    }

   function getTopTracks($artist){

        $conn = getDB();
        $curl = curl_init('http://ws.audioscrobbler.com/2.0/?method=artist.gettoptracks&artist='.$artist.'&api_key='.$this->apiKey.'&format=json');

        curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl,CURLOPT_HEADER,0);
        curl_setopt($curl,CURLOPT_TIMEOUT,3);
        $data = curl_exec($curl);
        curl_close($curl);
        //echo $data;

        $string = json_decode($data, true);

        $string = $string['toptracks']['track'];
              for($i=0; $i<10; $i++){
                $items = $string[$i];
                $name = mysqli_real_escape_string($conn ,$items['name']);
                //echo "</br>Name: ".$items['name']; 
                $playcount = $items['playcount'];
                //echo " Playcount: ".$items['playcount'];
                $url = $items['url'];
                //echo " URL: ".$items['url'];
                $artist =mysqli_real_escape_string($conn, $items['artist']['name']);
                //echo $items['artist']['name'];
                $rank = $items['@attr']['rank'];
                //echo $items['@attr']['rank'];

                $sql =  "INSERT INTO artist
                          (artistName, artistPlaycount, artistURL, artist, artistRank)
                          VALUES
                          ('$name', $playcount, '$url', '$artist', $rank)";
                mysqli_query($conn, $sql);
            }
    }

    function getTopArtists(){

        $conn = getDB();

        $curl = curl_init('http://ws.audioscrobbler.com/2.0/?method=chart.gettopartists&api_key='.$this->apiKey.'&format=json');

        curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl,CURLOPT_HEADER,0);
        curl_setopt($curl,CURLOPT_TIMEOUT,3);
        $topArtistData = curl_exec($curl);
        curl_close($curl);
        //echo $data;

        $topArtistsString = json_decode($topArtistData, true);
        //var_dump($topArtistsString);
            

            $topArtistsString = $topArtistsString['artists']['artist'];
            for($i=0; $i<10; $i++){
                $items = $topArtistsString[$i];
                $topArtistName = mysqli_real_escape_string($conn ,$items['name']);
                //echo "</br>Name: ".$items['name'];
                $topArtistPlayCount = $items['playcount'];
                //echo " Playcount: ".$items['playcount'];
                $topArtistURL = $items['url'];
                //echo " URL: ".$items['url'];
                $topArtistlisteners = $items['listeners'];

                $sql =  "INSERT INTO topartists
                (topArtistName, topArtistPlayCount, topArtistURL, topArtistlisteners)
                VALUES
                ('$topArtistName', $topArtistPlayCount, '$topArtistURL', $topArtistlisteners)";
                mysqli_query($conn, $sql);

            }
    }

    /*function getArtists($artistSearch){

        $conn = getDB();

        $curl = curl_init('http://ws.audioscrobbler.com/2.0/?method=artist.search&artist='.$artistSearch.'&api_key='.$this->apiKey.'&format=json');

        curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl,CURLOPT_HEADER,0);
        curl_setopt($curl,CURLOPT_TIMEOUT,3);
        $artistData = curl_exec($curl);
        curl_close($curl);
        //echo $data;

        $artistsString = json_decode($artistData, true);
        //var_dump($artistsString);
            

            $artistsString = $artistsString['artists']['artist'];
            for($i=0; $i<10; $i++){
                $items = $topArtistsString[$i];
                $topArtistName = mysqli_real_escape_string($conn ,$items['name']);
                //echo "</br>Name: ".$items['name'];
                $topArtistPlayCount = $items['playcount'];
                //echo " Playcount: ".$items['playcount'];
                $topArtistURL = $items['url'];
                //echo " URL: ".$items['url'];
                $topArtistlisteners = $items['listeners'];

                $sql =  "INSERT INTO topartists
                (topArtistName, topArtistPlayCount, topArtistURL, topArtistlisteners)
                VALUES
                ('$topArtistName', $topArtistPlayCount, '$topArtistURL', $topArtistlisteners)";
                mysqli_query($conn, $sql);

            }
    }*/

        function youtubeVideo($q){

        $key = "AIzaSyBrUZOegVqYsBJdzCrg6xTK6T6AP721Jhk";
        $base_url = "https://www.googleapis.com/youtube/v3/";

        $API_URL = curl_init($base_url . "search?&key=" . $key . "&q=" . $q . "music-video&type=video&part=snippet&maxResults=3");

        curl_setopt($API_URL, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($API_URL, CURLOPT_HEADER, 0);
        curl_setopt($API_URL, CURLOPT_TIMEOUT, 3);
        $videos = curl_exec($API_URL);
        curl_close($API_URL);

        $videos = json_decode($videos, true);
        //var_dump($videos);
        //$videos = json_decode( file_get_contents( $API_URL ) );

        $conn = getDB();

        $videos = $videos['items'];
        //var_dump($videos);

        for ($i = 0; $i < 3; $i++) {

            $items = $videos[$i];
            $videoId = $items['id']['videoId'];
            //var_dump($videoId);
            $title = $items['snippet']['title'];
            //var_dump($title);
            $thumbnail = $items['snippet']['thumbnails']['high']['url'];
            //var_dump($thumbnail);
            $publishedAt = $items['snippet']['publishedAt'];
            //var_dump($publishedAt);

            $sql = "INSERT INTO `videos`
                    (id, video_type, video_id, title, thumbnail_url)
                    VALUES
                    (NULL, 1, '$videoId', '$title', '$thumbnail')";

            mysqli_query($conn, $sql);

        }
        }

        function search_function($search) {
            $conn = getDB();
    
            
                error_reporting(0);
                ini_set('display_errors', 0);
    
                $search = str_replace(' ', '%20', $search);
                //echo $search_term;
    
                $curl = curl_init();
                
                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://api.genius.com/search?q='.$search.'",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET",
                    CURLOPT_HTTPHEADER => array("Authorization: Bearer xU__gJenf4uG5tqBpdMdgf3Oy5OdlSGUfCCzd-XicJdIaS_fHtINytZeYH7_8rYn")
                ));
    
                $response = curl_exec($curl);
                $err = curl_error($curl);
                //echo $response;
                curl_close($curl);
    
                if ($err) {
                    echo "cURL Error #:" . $err;
                } else {
                    //echo $response;
                    $data = json_decode($response, true);
                    //print_r($data);
    
                    for ($index = 0; $index < 5; $index++) {
                        $API_PATH       = mysqli_real_escape_string($conn, "https://genius.com" . $data['response']['hits'][$index]['result']['api_path']);
                        $ARTIST_NAME    = mysqli_real_escape_string($conn, $data['response']['hits'][$index]['result']['artist_names']);
                        $FULL_TITLE     = mysqli_real_escape_string($conn, $data['response']['hits'][$index]['result']['full_title']);
                        $ID             = mysqli_real_escape_string($conn, $data['response']['hits'][$index]['result']['id']);
                        $LYRICS_PATH    = mysqli_real_escape_string($conn, "https://genius.com" . $data['response']['hits'][$index]['result']['path']);
                        $TITLE          = mysqli_real_escape_string($conn, $data['response']['hits'][$index]['result']['title']);
                        $IMAGE_URL      = mysqli_real_escape_string($conn, $data['response']['hits'][$index]['result']['header_image_url']);
    
                        $sql = "INSERT INTO genius (API_PATH, ARTIST_NAME, FULL_TITLE, ID, LYRICS_PATH, TITLE, IMAGE_URL) VALUES ('$API_PATH', '$ARTIST_NAME', '$FULL_TITLE', '$ID', '$LYRICS_PATH', '$TITLE', '$IMAGE_URL')";
                        mysqli_query($conn, $sql);
                    }
    
    
            }
    
        }
    

    function updateDB(){
        $conn = getDB();

        $sql = "DELETE
                FROM topartists";
        $sql_1 = "DELETE
                  FROM topsongs";
        $sql_2 = "DELETE
                  FROM spotify_artist";
        $sql_3 = "DELETE
                    FROM videos";
        $sql_4 = "DELETE
                    FROM genius";

        mysqli_query($conn, $sql);
        mysqli_query($conn, $sql_1);
        mysqli_query($conn, $sql_2);
        mysqli_query($conn, $sql_3);
        mysqli_query($conn, $sql_4);
    }
}

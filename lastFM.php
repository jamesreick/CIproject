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

    function updateDB(){
        $conn = getDB();

        $sql = "DELETE
                FROM topartists";
        $sql_1 = "DELETE
                  FROM topsongs";

        mysqli_query($conn, $sql);
        mysqli_query($conn, $sql_1);

    }
}
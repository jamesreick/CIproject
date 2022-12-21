<?php
    include_once 'database.php';
	
    function search_function() {
        $conn = getDB();

        if(isset($_POST['submit'])) { 
            
            $search_var = $_POST['search_term'];
        
            error_reporting(0);
            ini_set('display_errors', 0);

            $search = str_replace(' ', '%20', $search_var);
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

    }

    function clear_database() {
        $conn = getDB();

        $sql = "DELETE FROM genius";

        mysqli_query($conn, $sql);
    }
?>
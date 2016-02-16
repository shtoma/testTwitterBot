<?php
    require_once('twitteroauth/twitteroauth.php');
    require_once('config.php');
    
    $q = $_GET['q'];

    $conn = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET,ACCESS_TOKEN, ACCESS_TOKEN_SECRET);
    
    $result = searchTweetByQuery($conn, $q);
    header('content-type: application/json; charset=utf-8');
    header("access-control-allow-origin: *");
    echo json_encode($result);
    function searchTweetByQuery($conn, $query){
        $query  = array(q => $query);
        $result = $conn->get("search/tweets", $query);
        return $result;
    }
?>

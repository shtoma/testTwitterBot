<?php
    require_once('twitteroauth/twitteroauth.php');
    require_once('config.php');

    $target_account = "fuyukoharumachi";    
    $status = array(
        q => $target_account
    );
    $conn = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET,ACCESS_TOKEN, ACCESS_TOKEN_SECRET);
    $result = $conn->get('users/search',$status);
    //var_dump($result);
    
    $target_id = $result["0"]->id;
    
    $status = array(
        user_id => $target_id
    );
    $result = $conn->post("friendships/create", $status);
    var_dump($result);

?>

<?php
    require_once('twitteroauth/twitteroauth.php');
    require_once('config.php');
    require_once('/usr/local/twitter/TomaTwiTest/logic/CommonConf.php');
    
    $this_year = date("Y");
    $commonConf = new CommonConf();
    $authors = $commonConf->getCopyRightExpiredAuthorNames($this_year);
    $messages = array();
    if (count($authors) != 0){
        foreach ($authors as $author){
            $messages[] = "今年著作権が切れるのは".$author."です";
        }
    }
    var_dump($messages);
//    $conn = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET,ACCESS_TOKEN, ACCESS_TOKEN_SECRET);
      
//    tweetAuther($conn, $expired_message_array,"Expired");
    

    function tweetAuther($conn, $messages,$debug="debug"){
        if(count($messages) == 0) {
            echo "This year No $debug Authers\n";
            return;
        }
        foreach($messages as $message){
            echo "$debug : tweet [$message]<br>";
            $status = array(status => $message);
            $result = $conn->post("statuses/update", $status);
            var_dump($result);
        }
    }
?>

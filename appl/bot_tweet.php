<?php
    require_once('twitteroauth/twitteroauth.php');
    require_once('config.php');
    require_once('/usr/local/twitter/TomaTwiTest/logic/CommonConf.php');
    
    $today = date("n-j");
    $commonConf = new CommonConf();
    $birth_author = $commonConf->getBirthAuthor($today);
    $dead_author  = $commonConf->getDiedAuthor($today);
    if (count($birth_author) != 0){
        $birth_message_array = array();
        foreach ($birth_author as $k => $v){
            $year_old              = date("Y") - $v['birth-year'];
            $author_wiki           = "http://ja.wikipedia.org/wiki/". urlencode($v['author']);
            $birth_message_array[] = "本日".date("n月j日")."誕生日の作家は,".$v['author'].'('.$author_wiki.')'."です。".'('.$v['birth-year'].'年生,生誕'.$year_old.'年).  #'.$v['author'];
        }
    }
    if (count($dead_author) != 0){
        $dead_message_array = array();
        foreach ($dead_author as $k => $v){
            $year_after_dead      = date("Y") - $v['dead-year'];
            $author_wiki           = "http://ja.wikipedia.org/wiki/". urlencode($v['author']);
            $dead_message_array[] = "本日".date("n月j日")."は,".$v['author'].'('.$author_wiki.')'."の没後".$year_after_dead."年です。  #".$v['author'];
        }
    }


    $conn = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET,ACCESS_TOKEN, ACCESS_TOKEN_SECRET);
    
    tweetAuther($conn, $birth_message_array,"Birth"); 
    tweetAuther($conn, $dead_message_array,"Dead");
//var_dump(searchTweetByMessage($conn, "小田実")); 
    
    function getBirthAuther($date) {
        return array("$date birth author1", "$date birth author2");
    }
    
    function getDiedAuther($date) {
        return array("$date died author1", "$date died author2","テスト日本語著作者");
    }

    function tweetAuther($conn, $messages,$debug="debug"){
        if(count($messages) == 0) {
            echo "Today No $debug Authers\n";
            return;
        }
        foreach($messages as $message){
            echo "$debug : tweet [$message]<br>";
            $status = array(status           => $message,
                            rpp              => 5,
                            include_entities => 1,
                     );
            $result = $conn->post("statuses/update", $status);
            var_dump($result);
        }
    }


    function searchTweetByMessage($conn, $message){
        $query  = array(q => $message);
        $result = $conn->get("search", $query);
        return $result;
    }
?>

<?php
    require_once('/usr/local/twitter/TomaTwiTest/logic/CommonConf.php');
    $commonConf = new CommonConf();
    $today = date("n-j");
var_dump($today);
    var_dump($commonConf->getDiedAuthor($today));

?>

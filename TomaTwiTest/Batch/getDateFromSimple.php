<?php
    define(DATA_SIMPLE, "/usr/local/twitter/TomaTwiTest/data/xmls/author/simple/");
    define(DATA_OUT,    "/usr/local/twitter/TomaTwiTest/conf/testDate.csv");
    require_once("/usr/local/twitter/TomaTwiTest/logic/CommonConf.php");
   
    $commonConf = new CommonConf();
    $list = $commonConf->getAutherNames();
    $ofp = fopen(DATA_OUT, "a");

    foreach ($list as $name){
        $name = rtrim($name);
        $xml = DATA_SIMPLE.$name.".xml";
        if(!file_exists($xml)) continue;
echo "xml = ".$xml."\n";
        $target_fp = fopen($xml,"r");
        while(! feof($target_fp)){
            $result = array('birth-year','birth-month','birth-day','died-year','died-month','died-day');
            $line = fgets($target_fp,9182);
            preg_match_all("/([1-2][0-9]{3})年([0-9]{1,2})月([0-9]{1,2})日/i",$line,$regex);
            $count = count($regex[0], COUNT_RECURSIVE);
            if($count == 0) continue;
            if($count == 2) {
                $result["birth-year"]  = $regex[1][0];
                $result['birth-month'] = $regex[2][0];
                $result['birth-day']   = $regex[3][0];
                $result['died-year']   = $regex[1][1];
                $result['died-month']  = $regex[2][1];
                $result['died-day']    = $regex[3][1];
                $died_date  = $result['died-year']."-".$result['died-month']."-".$result['died-day'];
            } elseif($count == 1){
                $result["birth-year"]  = $regex[1][0];
                $result['birth-month'] = $regex[2][0];
                $result['birth-day']   = $regex[3][0];
                $died_date  = "";
            }
            $birth_date = $result['birth-year']."-".$result['birth-month']."-".$result['birth-day'];
            fwrite($ofp,"$name,$birth_date,$died_date\n");
        }
        fclose($target_fp);
    }
    fclose($ofp);
?>

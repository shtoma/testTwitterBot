<?php
    define(DATA_DETAIL, "/usr/local/twitter/TomaTwiTest/data/xmls/author/detail/");
    define(DATA_OUT,    "/usr/local/twitter/TomaTwiTest/conf/testDateDetail.csv");
    require_once("/usr/local/twitter/TomaTwiTest/logic/CommonConf.php");
   
    $commonConf = new CommonConf();
    $list = $commonConf->getAutherNames();
    $ofp = fopen(DATA_OUT, "a");

    foreach ($list as $name){
        $name = rtrim($name);
        $xml = DATA_DETAIL.$name.".xml";
        if(!file_exists($xml)) continue;
echo "xml = ".$xml."\n";
        $xml_obj = simplexml_load_file($xml);
var_dump($xml_obj);
        $result = array('birth-year','birth-month','birth-day','died-year','died-month','died-day');
        $birth_date = $xml_obj->data->birth_date;
        $death_date = $xml_obj->data->death_date;
        preg_match_all("/([1-2][0-9]{3})年([0-9]{1,2})月([0-9]{1,2})日/i",$birth_date,$birth_regex);
        preg_match_all("/([1-2][0-9]{3})年([0-9]{1,2})月([0-9]{1,2})日/i",$death_date,$death_regex);
var_dump($birth_regex);
var_dump($death_regex);
        $birth_count = count($birth_regex[0], COUNT_RECURSIVE);
        $death_count = count($death_regex[0], COUNT_RECURSIVE);
        $died_date  = "";
        if($birth_count == 1) {
            $result["birth-year"]  = $birth_regex[1][0];
            $result['birth-month'] = $birth_regex[2][0];
            $result['birth-day']   = $birth_regex[3][0];
            $birth_date = $result['birth-year']."-".$result['birth-month']."-".$result['birth-day'];
        }

        if($death_count == 1) {
            $result['died-year']   = $death_regex[1][0];
            $result['died-month']  = $death_regex[2][0];
            $result['died-day']    = $death_regex[3][0];
            $died_date  = $result['died-year']."-".$result['died-month']."-".$result['died-day'];
        }
        fwrite($ofp,"$name,$birth_date,$died_date\n");
    }
    fclose($ofp);
?>

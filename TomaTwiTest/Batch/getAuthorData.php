<?php
    require_once('/usr/local/twitter/TomaTwiTest/logic/CommonConf.php');
    $commonConf = new CommonConf();
    $list = $commonConf->getAuthorNames();
    $api_url_base = "http://ws.zubapita-t.com/index.php?keywords=%s&type=xml&search=equal";
    
    foreach ($list as $name){
echo "author = $name\n";
        $name = rtrim($name);
        $check_result = null;
        $api_url      = str_replace('%s',$name,$api_url_base);
        $check_result    = callRetry($api_url,5);
        //$check_result    = simplexml_load_file($api_url);
        //$check_result = new SimpleXMLElement($check_xml);
        if($check_result->error) {
            echo "$name calling [$api_url] failed\n\n";
            continue;
        }
echo "check_result\n";
echo "=========================\n";
var_dump($check_result);
echo "=========================\n";
        $no_data_flg = true;
        //write simple data
        $no_data_flg = writeSimpleXML($check_result,$name, $no_data_flg);
/*
        if(isset($check_result->title)){
             if($check_result->title == $name){
                 writeXML($check_result->lead, $name, "simple");
             } else {
                 echo "$name simple xml is not defined\n";
             }
             $no_data_flg = false;
        }
*/
        //write detail data
        if(isset($check_result->items->item)){
            foreach($check_result->items->item as $item){
                if($item->title == $name){
                    $actual_api_url = str_replace('&amp;', '&', $item->link);
                }
                if(!isset($actual_api_url)){
                    echo "$name actual_api_url is not defined.\n";
                    continue;
                }
                $result = callRetry($actual_api_url,5);
                if(isset($result->infos->info->data->birth_date)){
                    echo "write detail $name.xml\n";
                    $no_data_flg = false;
var_dump($result);
                    writeXML($result->infos->info->asXML(), $name,"detail");
                } else {
                    echo "$actual_api_url NOT have birth_date\n";
                    echo "$name birth_date = $result->birth_date\n";
                    $no_data_flg = writeSimpleXML($result,$name, $no_data_flg);
                }
            }
        } 

        if($no_data_flg) {
            echo "$name API info NOT exists.\n";
            error_log($name."\n", 3, "./error.log");
        }
    }
    
    function writeXML($str_xml,$name,$mode){
        $fp = fopen(DATA_DIR.$mode."/".$name.".xml","w");
        fwrite($fp,$str_xml);
        fclose($fp);
    }


    function getXMLwithCURL($url){
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // 戻り値を文字列で
        $xml_raw = curl_exec($ch);
        $xml_data = simplexml_load_string($xml_raw);
        return $xml_data;
    }
    
    function callRetry($url,$num){
        $check_result    = getXMLwithCURL($url);
        if($check_result->error) {
            for ($i=1; $i < $num+1; $i++){
                echo "retry($i) start\n";
                $check_result    = getXMLwithCURL($url);
                if(!$check_result->error) break;
                sleep(5);
            }
        }
        return $check_result;
    } 

    function writeSimpleXML($xml_obj,$name, &$no_data_flg){
        if(isset($xml_obj->title)){
             if($xml_obj->title == $name){
                 writeXML($xml_obj->lead, $name, "simple");
                 $no_data_flg = false;
             } else {
                 echo "$name simple xml is not defined\n";
             }
        }
        return $no_data_flg;
    }
?>


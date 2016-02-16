<?php
    $approot = "/usr/local/twitter/TomaTwiTest/";
    define('APPROOT',$approot);
    define(DATA_DIR, APPROOT."data/xmls/author/");
    Class CommonConf{

        public function getArrayFromAuthorCSV(){
            $result = array();
            $fp = fopen(APPROOT."conf/author.csv","r");
            while(! feof ($fp) ){
                $line = fgets($fp, 9182);
                list($author, $birth_date, $dead_date) = explode(",",$line);
                $result[$author]['birth_date'] = rtrim($birth_date);
                $result[$author]['dead_date']  = rtrim($dead_date);
            }
            return $result;
        }

        public function getBirthAuthor($date) {
            $result     = array();
            $csv_data   = $this->getArrayFromAuthorCSV();
            foreach ($csv_data as $author => $value){
                $birth_date = $csv_data[$author]['birth_date'];
                list($birth_year, $birth_month, $birth_day) = explode("-",$birth_date);
                if ($birth_month."-".$birth_day == $date){
                    $data     = array("author" => $author, 'birth-year' => $birth_year);
                    $result[] = $data;
                }
            }
            return $result;
        }

        public function getDiedAuthor($date) {
            $result = array();
            $csv_data   = $this->getArrayFromAuthorCSV();
            foreach ($csv_data as $author => $value){
                $dead_date = $csv_data[$author]['dead_date'];
                list($dead_year, $dead_month, $dead_day) = explode("-",$dead_date);
                if ($dead_month."-".$dead_day == $date){
                    $data     = array("author" => $author, 'dead-year' => $dead_year);
                    $result[] = $data;
                }
            }
            return $result;
        }
 
        public function getAuthorNames() {
            $result = array();
            $csv_data   = $this->getArrayFromAuthorCSV();
            foreach ($data as $author => $value){
                $result[] = $author;
            }
            return $result;
        }

        public function getExpiredYear($author, $nation="Ja"){
            $died_year = $this->getDiedYear($author);
            if($died_year != null) return $died_year+$this->getLegalExpireYear($nation);
            return null;
        }

        public function getLegalExpireYear($nation="Ja"){
            return 50;
        }


        public function getDeadDate($author){
            $csv_data   = $this->getArrayFromAuthorCSV();
            $dead_date = $csv_data[$author]['dead_date'];
            if($dead_date != null && $dead_date != "" ) return $dead_date;
            return null;
        }

        public function getDiedYear($author){
            $dead_date = $this->getDeadDate($author);
            if($dead_date != null) { 
                $dead_ymd  = explode("-",$dead_date);
                $dead_year = rtrim($dead_ymd[0]);
                if($dead_year != "")  return $dead_year;
            }
            return null;
        }


        public function getCopyRightExpiredAuthorNames($target_year) {
            $result = array();
            $authors = $this->getAuthorNames();
            foreach ($authors as $author){
                $expired_year = $this->getExpiredYear($author);
                if($expired_year != null && $expired_year < 2020) echo "$author is expired at $expired_year \n";
                if($expired_year == $target_year) $result[] = $author;
            }
            return $result;
        }
    }
?>

<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * @author Kamyar
 */

class ShortenURLModel extends CI_Model {
    
    /**
     * 
     * @param type $dbc
     * @param type $url
     * @param type $url_md5
     * @return int
     * @author Kamyar
     */
    public function getShortURL(&$dbc, &$url, &$url_md5) {
        $obj = [
            "stat"      =>      -1,
            "msg"       =>      "",
            "data"      =>      [],
            "count"     =>      0
        ];
        
        $url_time = NULL;
        $url_full = NULL;
        
        $check = $this->urlExists($dbc, $url_md5); //Check if the given URL is already available in our database
        
        if(!is_null($check)) { //URL exists in database
            $url_time = $check["url_time"]; //Get the stored timestamp
            $url_full = $check["url_full"]; //Get the stored full URL
        } else { //It's a new URL
            $url_time = base_convert(intval(time()), 10, 26); /*
                                                               * Assign the timestamp to every new URL.
                                                               * Then, convert the decimal-based time into base 26 to make it even shorted :) Let's call it Kamyar-Universally-Unique-Identifier KUUID :p
                                                               * The base-26-timestamp is actually the short form of the URL.
                                                               * As the time is always increasing therefore, it is always unique. The best candidate to make a KUUID.
                                                               */
            $this->insertURL($dbc, $url, $url_md5, $url_time); //Insert the new URL into our database
        }
        
        $obj["data"] = [ //Finally, return the timestamp as it is representing the short form of the URL
            "short_url"     =>      base_url() . $url_time
        ];
        $obj["stat"] = 0;
        
        return $obj;
    }
    
    /**
     * 
     * @param type $dbc
     * @param type $url_md5
     * @return type
     * @author Kamyar
     */
    private function urlExists(&$dbc, &$url_md5) {
        $statement = new Cassandra\SimpleStatement("SELECT url_time, url_full FROM url WHERE url_md5 = ? ;"); //Selection is based on the MD5 hash
        $options = new Cassandra\ExecutionOptions(["arguments" => [$url_md5]]);
        $data = $dbc->execute($statement, $options);
        if(!is_null($data)) {
            $data = $data->first();
            if(!is_null($data)) { //Return if found
                return [
                    "url_time"      =>      $data["url_time"],
                    "url_full"      =>      $data["url_full"]
                ];
            }
        }
        return NULL; //Return NULL if found nothing
    }
    
    /**
     * 
     * @param type $dbc
     * @param type $url
     * @param type $url_md5
     * @param type $url_time
     * @author Kamyar
     */
    private function insertURL(&$dbc, &$url, &$url_md5, &$url_time) {
        /*
         * Insertion is done by populating two tables: 'url' & 'url_front'
         * 
         * The table 'url' stores the full URL along with its timestamp and MD5 hash.
         * This table is useful to check if the given URL exists in the table.
         * 
         * The reason behind 'url_front' is to retrieve the full URL by its timestamp.
         * Therefore, the full URL can be selected by its timestamp
         * 
         * This scheme is to overcome the selection limitation exists in NoSQL database systems.
         */
        $statement = NULL;
        $options = NULL;
        //Insert into table: url-----------------------------------------------/
        $statement = new Cassandra\SimpleStatement("INSERT INTO url (url_md5, url_time, url_full) VALUES (?, ?, ?) ;");
        $options = new Cassandra\ExecutionOptions(["arguments" => [
            $url_md5, 
            $url_time, 
            $url
        ]]);
        $dbc->execute($statement, $options);
        //Insert into table: url_front-----------------------------------------/
        unset($statement);
        unset($options);
        $statement = new Cassandra\SimpleStatement("INSERT INTO url_front (url_time, url_md5) VALUES (?, ?) ;");
        $options = new Cassandra\ExecutionOptions(["arguments" => [
            $url_time, 
            $url_md5
        ]]);
        $dbc->execute($statement, $options);
    }
    
    /**
     * 
     * @param type $dbc
     * @param type $url_time
     * @return int
     * @author Kamyar
     */
    public function getFullURL(&$dbc, &$url_time) {
        $obj = [
            "stat"      =>      -1,
            "msg"       =>      "The URL does not exist.",
            "data"      =>      [],
            "count"     =>      0
        ];
        
        $statement = new Cassandra\SimpleStatement("SELECT url_md5 FROM url_front WHERE url_time = ? ;"); //Retrieve the MD5 hash based on the given shortURL (timestamp)
        $options = new Cassandra\ExecutionOptions(["arguments" => [
            $url_time
        ]]);
        $check = $dbc->execute($statement, $options);
        
        if(!is_null($check)) {
            $check = $check->first();
            if(!is_null($check)) { //If the entity exists
                $url_md5 = $check["url_md5"];
                unset($statement);
                unset($options);
                $statement = new Cassandra\SimpleStatement("SELECT url_full FROM url WHERE url_md5 = ? ;"); //Then, retrieve the full URL
                $options = new Cassandra\ExecutionOptions(["arguments" => [$url_md5]]);
                $data = $dbc->execute($statement, $options);
                if(!is_null($data)) {
                    $data = $data->first();
                    if(!is_null($data)) {
                        $obj["msg"] = "URL found";
                        $obj["data"] = [
                            "url"      =>      $data["url_full"]
                        ];
                        $obj["stat"] = 0;
                    }
                }
            }
        }
        
        return $obj;
    }
}
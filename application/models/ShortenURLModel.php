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
    
    public function getShortURL(&$dbc, &$url, &$url_md5) {
        $obj = [
            "stat"      =>      -1,
            "msg"       =>      "",
            "data"      =>      [],
            "count"     =>      0
        ];
        
        $url_time = NULL;
        $url_full = NULL;
        
        $check = $this->urlExists($dbc, $url_md5);
        
        if(!is_null($check)) { //URL exists in database
            $url_time = $check["url_time"];
            $url_full = $check["url_full"];
        } else { //It's a new URL
            $url_time = time();
            $this->insertURL($dbc, $url, $url_md5, $url_time);
        }
        
        $obj["data"] = [
            "short_url"     =>      base_url() . strval($url_time)
        ];
        $obj["stat"] = 0;
        
        return $obj;
    }
    
    private function urlExists(&$dbc, &$url_md5) {
        $statement = new Cassandra\SimpleStatement("SELECT url_time, url_full FROM url WHERE url_md5 = ? ;");
        $options = new Cassandra\ExecutionOptions(["arguments" => [$url_md5]]);
        $data = $dbc->execute($statement, $options);
        if(!is_null($data)) {
            $data = $data->first();
            if(!is_null($data)) {
                return [
                    "url_time"      =>      strval($data["url_time"]->value()),
                    "url_full"      =>      $data["url_full"]
                ];
            }
        }
        return NULL;
    }
    
    private function insertURL(&$dbc, &$url, &$url_md5, &$url_time) {
        $statement = NULL;
        $options = NULL;
        //Insert into table: url-----------------------------------------------/
        $statement = new Cassandra\SimpleStatement("INSERT INTO url (url_md5, url_time, url_full) VALUES (?, ?, ?) ;");
        $options = new Cassandra\ExecutionOptions(["arguments" => [
            $url_md5, 
            new Cassandra\Bigint($url_time), 
            $url
        ]]);
        $dbc->execute($statement, $options);
        //Insert into table: url_front-----------------------------------------/
        unset($statement);
        unset($options);
        $statement = new Cassandra\SimpleStatement("INSERT INTO url_front (url_time, url_md5) VALUES (?, ?) ;");
        $options = new Cassandra\ExecutionOptions(["arguments" => [
            new Cassandra\Bigint($url_time), 
            $url_md5
        ]]);
        $dbc->execute($statement, $options);
    }
}
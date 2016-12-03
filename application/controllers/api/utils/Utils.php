<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * @author Kamyar
 */

require_once 'application/libraries/REST_Controller.php';

class Utils extends REST_Controller {
    
    /**
     * 
     * @param type $obj
     * @author Kamyar
     */
    public function output_json($obj) {
        $this->output
                ->set_content_type('application/json','utf-8')
                ->set_output(json_encode($obj, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }
    
    /**
     * 
     * @return type
     * @author Kamyar
     */
    public function cs_connect() {
        include APPPATH . '/config/database.php';
        $cs = $db["cassandra"];
        $cluster = Cassandra::cluster()
                ->withContactPoints($cs["hostname"])
                ->withPort($cs["port"])
                ->withDefaultConsistency(Cassandra::CONSISTENCY_QUORUM)
                ->withCredentials($cs["username"], $cs["password"])
                ->build();
        $connection = [
            "session"       =>      $cluster->connect($cs["database"]),
            "svrhost"       =>      $cs["hostname"],
            "svrid"         =>      $cs["serverid"]
        ];
        return $connection;
    }
}
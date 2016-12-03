<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * @author Kamyar
 */

require_once dirname(__FILE__) . '/../utils/Utils.php';

class GetURL extends Utils {
    
    public function index_get() {
        $obj = [
            "stat"      =>      -1,
            "msg"       =>      "",
            "data"      =>      [],
            "count"     =>      0
        ];
        
        $url_time = $this->_get_args["shortUrl"];
        
        try {
            if(isset($url_time) && !empty($url_time) && !is_null($url_time)) {
                if(!is_numeric($url_time)) {
                    throw new Exception("Invalid input.");
                } else {
                    $url_time = intval($url_time);
                }
            } else {
                throw new Exception("Missing argument.");
            }
        } catch (Exception $ex) {
            $obj["msg"] = $ex->getMessage();
            $this->output_json($obj);
            return;
        }
        
        $conobj = $this->cs_connect();
        $dbc = $conobj["session"];
        
        $this->load->model("ShortenURLModel");
        $obj = $this->ShortenURLModel->getFullURL($dbc, $url_time);
        
        $this->output_json($obj);
    }
}
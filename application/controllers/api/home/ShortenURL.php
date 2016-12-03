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

class ShortenURL extends Utils {
    
    /**
     * @author Kamyar
     */
    public function index_get() {
        $obj = [
            "stat"      =>      -1,
            "msg"       =>      "",
            "data"      =>      [],
            "count"     =>      0
        ];
        
        $url = $this->_get_args["url"];
        
        $obj["data"] = $url;
        
        $this->output_json($obj);
    }
}
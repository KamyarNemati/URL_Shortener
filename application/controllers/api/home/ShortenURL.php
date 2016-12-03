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
        $url_md5 = NULL;
        
        //Check if the input is valid
        try {
            if(isset($url) && !empty($url) && !is_null($url)) {
                $url_md5 = md5($url); //Get the MD5 hash
            } else {
                throw new Exception("Missing argument: url");
            }
        } catch (Exception $ex) { //Just in case of any invalid input
            $obj["msg"] = $ex->getMessage();
            $this->output_json($obj);
            return;
        }
        
        $conobj = $this->cs_connect(); //Establish the Cassandra connection
        $dbc = $conobj["session"];
        
        $this->load->model("ShortenURLModel"); //Load model
        $obj = $this->ShortenURLModel->getShortURL($dbc, $url, $url_md5);
        
        $this->output_json($obj);
    }
}
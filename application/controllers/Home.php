<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * @author Kamyar
 */

require_once dirname(__FILE__) . '/api/utils/Utils.php';

class Home extends CI_Controller {
    
    /**
     * @author Kamyar
     */
    public function index() {
        $this->load->view("home/home");
    }
    
    /**
     * 
     * @param type $data
     * @author Kamyar
     */
    public function view($data) { //This controller is to retrieve the short URL and redirect user to the corresponding page if the URL has already been shorten before
        $utils = new Utils();
        $conobj = $utils->cs_connect();
        $dbc = $conobj["session"];
        
        $utils->load->model("ShortenURLModel");
        $obj = $utils->ShortenURLModel->getFullURL($dbc, $data);
        if($obj["stat"] == 0) {
            $url = $obj["data"]["url"];
            redirect($url);
        } else {
            $this->load->view("home/home");
        }
    }
}
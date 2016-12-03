<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * @author Kamyar
 */

class Home extends CI_Controller {
    
    /**
     * @author Kamyar
     */
    public function index() {
        $this->load->view("home/home");
    }
}
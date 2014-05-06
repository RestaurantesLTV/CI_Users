<?php

class Administracion extends CI_Controller {

    function __construct() {
        parent::__construct();

        // Load the Library
        $this->load->library(array('user', 'user_manager'));
        $this->load->helper('url');
    }
    
    function index(){
            $this->user->on_invalid_session('login'); // Vamos al controlador 'login''
    }

}

<?php


class Autentificacion extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
        
        $usuario = null;
        
        $username = $this->session->userdata('username');
        $pass = $this->session->userdata('pass');
        
        // Si existen la sesion:
        if($username && $pass){
                // http://ellislab.com/codeigniter/user-guide/general/urls.html
                redirect('administracion','refresh'); // Redirect al controlador 'request'
        }else{
            if($this->input->post('username') && $this->input->post('pass')){
                
            }else{
                $this->CI->session->set_flashdata('error_message', 'Rellene los campos!');
                redirect('autentificacion','refresh');
            }
        }
        //if (session_is_valid() && isset(session_is_valid){
        //  creamos objeto Usuario con los datos de la sesion
        //  $usuario = new Usuario(sesion['username'], sesion['pass'], true);
        //  
        //  hacemos query a la BD para saber la fecha de ultimo logeo
        //  ...
        //  Si todo va bien, actualizamos la fecha
        //  
        //}else{
        //  if(!$_POST){
        //      // Cargar vista de error
        //  }
        //  $usuario = new Usuario($_POST["username"], $_POST["password"], false); 
        //  BD: existe?
        //  BD: Son correctos los datos?
        //  BD: Actualizar Fecha lastlogin a la actual del sistema
        //  
        //  
        //  Mostrar Vista de sesion creada
        //}
       // $this->load->library('calendar');
    }
    
    
}

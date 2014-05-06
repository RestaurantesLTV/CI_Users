<?php

/**
 * @author Victor Arnau <vicargoapp@gmail.com>
 * Esta clase permite implementar un sistema de usuarios basico en CI, con una base de datos
 * como apoyo para guardar sus datos. 
 */
class Usuario {
    private $id = null;
    private $permisos;
    private $nombre;
    private $username;
    private $password;
    private $email;
    private $descripcion;
    private $CI;
    private $ultimaConn;
    private $estaConectado;
    private $estaLogeado;
    private $hashActivado;

    /**
     * 
     * @param String $nickname - nombre del usuario
     * @param String $password - contraseña
     * @param boolean - Indica si están codificados los campos que se le pasan al constructor. Por defecto: true
     */
    public function __construct($username, $password, $isHash = true) {
        $this->CI = & get_instance();

        $this->username = $username;
        $this->password = $password;
    }

    /**
     * Recoge el nombre
     * @author Victor Arnau <vicargoapp en gmail.com>
     */
    public function getUsername() {
        $this->username;
    }

    /**
     * Recoge el password
     * @author Victor Arnau <vicargoapp en gmail.com>
     */
    public function getPassword() {
        $this->password;
    }

    /**
     * 
     * @return el parametro ultimaConn actualizado
     * @author Victor Arnau <vicargoapp en gmail.com>
     * @todo Falta definir lo que devuelve. Metodo incompleto.
     */
    function actualizarUltimaConexion() {
        /* Arreglado por unscathed18 */
        if ($this->id != null) {
            if (!$this->existeUsuario($this)) {
                throw new Exception("Llamada a metodo: 'actualizarUltimaConexion' --> El usuario no existe!");
            }
        }
    }

    /**
     * 
     * @param Usuario $usuario
     * Comprobara si el usuario introducido existe en la BD
     * @author Victor Arnau <vicargoapp en gmail.com>
     */
    public function existeUsuario(Usuario $usuario) {
        $username = "";
        if ($this->isHash) {
            //desncriptar
            $username = $this->encrypt->decode($usuario->getUsername());
            $consulta = $this->CI->db->from('usuario');
        } else {
            $username = $this->getUsername();
            $this->CI->db->where('usuarios', $username);
        }

        $resultado = $this->CI->db->get()->result(); // Devuelve UN indice (una fila) en forma de objeto.
        if (!$resultado) {
            return false;
        }
        $this->id = $resultado->id;

        return true;
    }

    /**
     * 
     * @param type $username - nombre de usuario
     * @param type $password - contraseña
     * @return boolean - True si se ha conectado / False si falla login o pass
     * @author Victor Arnau <vicargoapp en gmail.com>
     */
    public function login() {
        if ($this->hashActivado) {
            $this->username = $this->encrypt->decode($this->username);
            $this->password = $this->encrypt->decode($this->password);
            $this->hashActivado = false;
        }

        $user_query = $this->CI->db->from("usuario");
        $user_query = $this->CI->db->where('username', $this->username);

        if ($user_query->num_rows() != 1) {
            return false;
        }

        $user_query = $user_query->row();

        /* USERNAME */
        if ($user_query->username != $this->username) {
            return false;
        }

        /* PASSWORD */

        if ($user_query->password != $this->password) {
            return false;
        }

        // Si llegamos hasta aqui, la autentificacion es correcta:
        $this->crearSesion($this->encrypt->encode($user_query->username), $this->encrypt->encode($user_query->password));
        $this->actualizarUltimaConexion();
    }

    /**
     * 
     * @param type $username - nombre de usuario
     * @param type $password - contraseña
     * Crea una sesion 
     * @author Victor Arnau <vicargoapp en gmail.com>
     */
    private function crearSesion($username, $password) {
        $this->CI->session->set_userdata(array('username' => $username, 'password' => $password, 'activo' => true));
    }

    public static function sesionExiste() {
        if ($this->session->userdata('password') && $this->session->userdata('username')) {
            return new Usuario($this->session->userdata('username'), $this->session->userdata('password'));
        }
        return null;
    }
    
    public static function crearDesdeSesion(){
        return new Persona($this->session->userdata('username'),$this->session->userdata('password'));
    }

}

<?php

/**
 * Manage sessions
 * 
 * @author  Sergiu Pirlici      <www.spirlici.com>
 */
class Session {
    
    /**
     * Class constructor
     */
    public function __construct(){
        // init the session
        $this->init();
    }
    
    /**
     * Init the session
     */
    public function init(){
        
        /*
         * Marks the cookie as accessible only through the HTTP protocol. This means that the cookie won't 
         * be accessible by scripting languages, such as JavaScript. This setting can effectively help to 
         * reduce identity theft through XSS attacks (although it is not supported by all browsers).
         */
        ini_set('session.cookie_httponly',1);
        
        /*
         * session.use_only_cookies specifies whether the module will only use cookies to store the session 
         * id on the client side. Enabling this setting prevents attacks involved passing session ids in URLs.
         */
        ini_set('session.use_only_cookies',1);
        
        // start the session
        session_start();
    }
    
    /**
     * Set a session variable
     * 
     * @param   string  $name    Variable name
     * @param   string  $value   Variable value
     */
    public function set($name, $value) {
        $_SESSION[$name] = $value;
    }
    
    /**
     * Set a session variable
     * 
     * @param   string  $name    Variable name
     * @param   string  $value   Variable value
     */
    public function get($name) {
        if(!empty($_SESSION[$name])) return $_SESSION[$name];
    }
    
    /**
     * Magic method __setter
     */
    public function __set($name, $value){
        return $this->set($name, $value);
    }
    
    /**
     * Magic method __getter
     */
    public function __get($name){
        return $this->get($name);
    }
    
    /**
     * Magic method __unset
     * 
     * Unset a session variable
     */
    public function __unset($name) {
        unset($_SESSION[$name]);
    }
    
    /**
     * Magic method __isset
     */
    public function __isset($name) {
        return isset($_SESSION[$name]);
    }
    
}
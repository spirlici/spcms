<?php

/**
 * Main class
 * 
 * From this class will be extended `Controller` and `Model` classes
 * 
 * @author  Sergiu Pirlici      <www.spirlici.com>
 */
class MainClass {
    
    /**
     * @var     Db      $db     Database connection
     */
    public static $db;
    
    /**
     * Load a model<br>
     * This function will load an model and return an instance of this model
     * 
     * @param   string  $model      Model's name
     * @return  mixed               Model Instance 
     */
    public function loadModel($name) {
            
        static $loaded_models;
        
        if(empty($loaded_models[$name])) {
            $model = strtolower($name);
            
            // Check if controller exists
            if(is_file($file = APPDIR.'models'.DS.$model.'.model.php')) {
                include_once($file);
                $model = ucfirst($model);
                if(class_exists($model)) {
                    
                    $model = new $model;
                }
                else {
                    throw new Exception('Could not find model "'.ucfirst($model).'"');
                }
            }
            $loaded_models[$name] = $model;
        }
        
        return $loaded_models[$name];
    }
    
    /**
     * Getter function<br>
     * By default this function will try to load `$name` model<br>
     * So on our Models and Controllers we will be able to call `$this->ModelName->fooMethod()`
     * 
     * @param   string   $name   Property name
     * @return  mixed
     */
    public function __get($name){
        return $this->loadModel($name);
    }
    
    /**
     * Return an instance of Db Class
     * If not connection to the database is made then firstly will try to connect to the database
     * If connection to the database fails then `false` value will be returned
     * 
     * @return  Mixed;
     */
    public function db(){
        if(!self::$db) {
            self::$db = new Db($this->config('db'));
            self::$db->connect();
        }
        return self::$db;
    }

    /**
     * Return an config item
     * 
     * @param   string  $name   name of the item
     */
    public function config($name){
        static $config;
        if(!$config) {
            $config = new Config();
        }
        return $config->get($name);
    }
}
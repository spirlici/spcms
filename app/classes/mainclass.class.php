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
     * @var     Db      $db                 Database connection
     */
    public static $db;
    
    /**
     * @var     mixed   $loaded_models      Cache instances of loaded models to be used was a singletone
     */
    public static $loaded_models;
    
    /**
     * @var     mixed   $loaded_classes     Cache instances of loaded classes to be used was a singletone
     */
    public static $loaded_classes;
    
    /**
     * @var     Session $session            Cache instances of loaded models to be used was a singletone
     */
    public static $session;
    
    /**
     * @var     array
     */
    protected $_data;
    
    /**
     * Load a model<br>
     * This function will load an model and return an instance of this model
     * 
     * @param   string  $model      Model's name
     * @return  mixed               Model Instance 
     */
    public function loadModel($name) {
        
        if(empty(self::$loaded_models[$name])) {
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
                self::$loaded_models[$name] = $model;
            }
        }
        
        return !empty(self::$loaded_models[$name]) ? self::$loaded_models[$name] : false;
    }

    /**
     * Load a class
     * 
     * This function will load an class and return an instance of this clss
     * 
     * @param   string  $model      Class name
     * @return  mixed               Class Instance 
     */
    public function loadClass($name) {
        
        if(empty(self::$loaded_classes[$name])) {
            if(class_exists($name, true)) {
                $class = new $name;
            }
            self::$loaded_classes[$name] = $class;
        }
        return self::$loaded_classes[$name];
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
        if((!$ret = $this->loadModel($name))) {
            $ret = $this->loadClass($name);
        }
        return $ret;
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
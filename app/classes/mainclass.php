<?php

/**
 * Main class
 * From this class will be extended `Controller` and `Model` classes
 */
class MainClass {
    
    /**
     * Load a model
     * This function will load an model and return an instance of this model
     * 
     * @param   string  $model      Model's name
     * @return  mixed               Model Instance 
     */
    public function loadModel($name) {
        
    }
    
    /**
     * Getter function
     * By default this function will try to load `$name` model
     * 
     * @param   string   $name   Property name
     * @return  mixed
     */
    public function __get($name){
        return $this->loadModel($name);
    }
    
    /**
     * Return an Db instance
     * If not connection to the database is made, then firstly will try to connect to the database 
     * and then return an instance of Db Class
     * 
     * @return  $db;
     */
    public function db(){
        
    }
}
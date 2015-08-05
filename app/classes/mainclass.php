<?php

/**
 * Main class<br>
 * From this class will be extended `Controller` and `Model` classes
 */
class MainClass {
    
    /**
     * Load a model<br>
     * This function will load an model and return an instance of this model
     * 
     * @param   string  $model      Model's name
     * @return  mixed               Model Instance 
     */
    public function loadModel($name) {
        
        return $model;
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
        
        return $db;
    }
}
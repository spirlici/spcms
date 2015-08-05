<?php

/**
 * Take care of routes
 * 
 * @author  [Sergiu Pirlici](www.spirlici.com)
 */
class Router {
    
    /**
     * User defined routes
     */
    protected $routes;
    
    /**
     * Class constructor
     * 
     * @param  array    $routes
     */
    public function __construct($routes = NULL) {
        $this->routes = $routes;
    }
    
    /**
     * Parse `$uri` parameter and return an array with 2 values
     * 
     * E.g. `array('controller' => 'controller_name', 'method' => 'method_name');`
     * 
     * @param   string  $uri    Uri
     * @return  array
     */
    public function parse($uri){
        
        // params to be passed to controller's method
        $params = array();
        
        // check in user defined routes
        $found = $this->match($uri, $this->routes);
        
        // if no matching route was found try to generate automaticaly
        if(!$found) {
            $uri = trim($uri, '/');
            $uri = explode('/', $uri);
            $controller = $uri[0];
            $method = $uri[1];
        }
        
        if($found) {
            if(is_string($found)) {
                    
                $found = explode('@', $found);
                
                $controller = $found[0];
                
                if($found[1]) {
                    $method = $found[1];
                }
                else {
                    // if method wasn't explicitly specified use 'index' method by default
                    $method = 'index';
                }
            }
            elseif(is_array($found)) {
                $controller = $found['controller'];
                $method     = !empty($found['method']) ? $found['method'] : 'index';
            }
        }
        
        return compact('controller', 'method', 'params');
    }
    
    /**
     * Verify if an uri matches a route defined in self::$routes;
     * 
     * @param   string  $uri
     * @param   array   $routes
     * @return  
     */
    public function match($uri, $routes = NULL) {
        
        // remove slash from the end of uri
        $uri = trim($uri, '/');
        
        // if not $routes parameter is specified then use the ones passed to the class constructor
        empty($routes) and $this->routes = array();
        
        // if there are not user defined routes then return false
        if(empty($routes)) return false;
        
        // parse each route from $routes and check if match to given $uri
        foreach($routes as $k => $v) {
            
            $k = trim($k, '/');
            
            if($k == $uri) {
                return $v;
            }
        }
        return false;
    }
}
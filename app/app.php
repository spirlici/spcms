<?php

/**
 * The application class
 * 
 * @author  Sergiu Pirlici      <www.spirlici.com>
 */
class App {
    
    /**
     * Class constructor
     * 
     * Registers class autoloading
     */
    public function __construct() {
        $this->registerAutoload();
    }
    
    /**
     * Run the application
     */
    public function run() {
        
        $config = new Config();
        
        $router = new Router($config->get('routes'));
        
        $uri = explode('?', $_SERVER['REQUEST_URI']);
        $uri = reset($uri);
        
        $action = $router->parse($uri);
        
        if($action) {
            $this->runController($action['controller'], $action['method'], $action['params']);
        }
        
        return true;
    }
    
    /**
     * Run a controller
     * 
     * @param  string   $controller     Controller name
     * @param  string   $method         Method name
     * @param  array    $params         Params to be passed to the controller's method
     * @return mixed
     */
    public function runController($controller, $method = 'index', $params = array()){
        
        $file = strtolower($controller);
        
        // Check if controller exists
        if(is_file($ctrl = APPDIR.'controllers'.DS.$file.'.controller.php')) {
            include_once($ctrl);
            $controller = ucfirst($controller).'Controller';
            if(class_exists($controller)) {
                
                $controller = new $controller;
                $ret = call_user_func_array(array($controller, $method), $params);
                
                // if($ret) {
                    $view = $file.'/'.$method;
                    return $controller->makeResponse($ret, $view);
                // }
            }
            else {
                $ret = call_user_func_array(array('Error404', 'index'), array(array('controller' => $controller, 'method' => $method, 'params' => $params)));
            }
        }
    }
    
    /**
     * Register autoloading for classes
     */
    public function registerAutoload(){

        // firstly include vendor autoload
        include BASEDIR.'vendor'.DS.'autoload.php';
        
        // Classes from APPDIR/classes/ directory
        set_include_path(get_include_path().PATH_SEPARATOR.APPDIR.'classes'.DS);

        // all classes from the APPDIR.'classes' directory must have extension '.class.php'
        spl_autoload_extensions('.class.php');
        
        // all class files must lowercase name to avoid problems with case sensitivity
        spl_autoload_register(function ($class_name) {
            spl_autoload(strtolower($class_name));
        });
    }
}
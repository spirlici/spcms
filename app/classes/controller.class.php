<?php

/**
 * Main controller
 * 
 * From this controller will be extended all controllers
 * 
 * @author  Sergiu Pirlici      <www.spirlici.com>
 */
class Controller extends MainClass {
    
    /**
     * @var string  layout  Default layout for views
     */
    public $layout = 'layout';
    
    /**
     * make response
     */
    public function makeResponse($data, $view = NULL) {
        
        static $response_maked;
        
        if(!$response_maked) {
            
            if($this->is_ajax()) {
                header('Content-type: application/json; charset=UTF-8');
                
                // send json for ajax requests
                $response = is_string($data) ? $data : json_encode($data);
            }
            else {

                header('Content-type: text/html; charset=UTF-8');
                if(is_string($data)) {
                    $response = $data;
                }
                else {
                    // pass data to Smarty
                    $smarty = $this->smarty();
                    $data['user'] = $this->user;
                    if($data) 
                    foreach($data as $k => $v) {
                        $smarty->assign($k, $v);
                    }
                    $layout = $this->layout;
                    $view = $view.'.tpl';
                    if($layout) {
                        $view = 'extends:'.$layout.'.tpl|'.$view;
                    }
                    $response = $smarty->fetch($view);
                }
                
            }
            
            // display the response
            echo $response;
        }
        return $response;
    }
    
    /**
     * Check if a request is made via AJAX or not
     */
    public function is_ajax(){
        $is_ajax = false;
        
        if($_GET['__is_ajax']) {
            $is_ajax = true;
        }
        
        return $is_ajax;
    }
    
    /**
     * Return an instance of Smarty class;
     */
    public function smarty() {
        static $smarty;
        if(!$smarty) {
            $smarty = new Smarty();
            $smarty->setTemplateDir(APPDIR.'views'.DS);
        }
        return $smarty;
    }
    
}
<?php 

/**
 * User controller
 * 
 * @author  [Sergiu Pirlici](www.spirlici.com)
 */
class UserController extends Controller {
    
    /**
     * Default method
     * 
     * Redirects to /user/login if user is not logged in and to /user/profile if user is logged in
     */
    public function index(){
        if($this->user->is_log()) {
            redirect('/user/profile');
        }
        else {
            redirect('/user/login');
        }
    }
    
    /**
     * Display login form
     */
    public function login(){
        
        return array('s' => 'v');
    }
    
    /**
     * Displays signup form
     */
    public function signup(){
        
    }
    
    /**
     * Show user profile
     */
    public function profile(){
        
    }
}
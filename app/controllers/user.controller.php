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
            $this->redirect->to('/user/profile');
        }
        else {
            $this->redirect->to('/user/login');
        }
    }
    
    /**
     * Display login form and try to login user on post requests
     */
    public function login(){

        $ret = array();
        
        $fields = array('email', 'password');
        $data = array_intersect_key($_POST, array_flip($fields));
        
        if(!empty($_POST)) {
            
            // All fields from $fields array should be submitted via $_POST
            foreach($fields as $field) {
                if(empty($data[$field])) {
                    $error_fields[] = $field;
                }
            }
            
            if(!empty($error_fields)) {
                $errors[] = 'Fields <b>'.implode(', ', $error_fields).'</b> are required';
            }
            
            if(empty($errors)) {
                if(!($logged = $this->user->login($data['email'], $data['password']))){
                    $errors[] = 'Can not login!';
                }
            }
        }
        
        if($this->user->is_log()) $this->redirect->to('/user/profile');
        
        if(!empty($errors)) {
            $ret['errors'] = $errors;
        }
        
        return $ret;
    }
    
    /**
     * Logout
     */
    public function logout(){
        if($this->user->logout()) $this->redirect->to('/user/login');
        return $ret;
    }
    
    /**
     * Displays signup form
     */
    public function signup(){
        $fields = array('email', 'password', 'password_confirm', 'name');
        $data = array_intersect_key($_POST, array_flip($fields));
        
        if(!empty($_POST)) {
            
            //{{{ $_POST data validation
                
                // All fields from $fields array should be submitted via $_POST
                foreach($fields as $field) {
                    if(empty($data[$field])) {
                        $error_fields[] = $field;
                    }
                }
                if(!empty($error_fields)) {
                    $errors[] = 'Fields <b>'.implode(', ', $error_fields).'</b> are required';
                }
                // Fields 'password' and 'password_confirm' must be identical
                if($data['password'] != $data['password_confirm']) {
                    $errors[] = 'Fields <b>Password</b> and <b>Password confirmation</b> does not match';
                }
                
            //}}}
            
            if(empty($errors)) {
                $id = $this->user->getByEmail($data['email']);
                if(!$id) {
                    $this->user->register($data);
                    $this->redirect->to('/user/login');
                }
                else {
                    if($id === false) throw new Exception('An error occured');
                    $errors[] = 'It seems like an user is already registered with email you entered';
                }
            }
        }
        
        if(!empty($errors)) {
            $ret['errors'] = $errors;
        }
        $ret['data'] = array_diff_key($data, array_flip(array('password', 'password_confirm')));
        
        return $ret;
    }
    
    /**
     * Show user profile
     */
    public function profile(){
        if(!$this->user->is_log()) $this->redirect->to('/user/login');
    }
}
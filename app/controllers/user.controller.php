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
        $fields = array('email', 'password', 'password_confirm', 'name');
        $data = array_intersect_key($_POST, array_flip($fields));
        
        //{{{ $_POST data validation
            
            // All fields from $fields array should be submitted via $_POST
            foreach($fields as $field) {
                if(empty($data[$field])) {
                    $error_fields[] = $field;
                }
            }
            if($error_fields) {
                $errors[] = 'Fields <b>'.implode(', ', $error_fields).'</b> are required';
            }
            // Fields 'password' and 'password_confirm' must be identical
            if($data['password'] != $data['password_confirm']) {
                $errors[] = 'Fields <b>Password</b> and <b>Password confirmation</b> does not match';
            }
        
        //}}}
        
        if(!$errors) {
            $id = $this->user->getByEmail($data['email']);
            if(!$id) {
                // var_export($this->db()->last_query);
            }
            else {
                if($id === false) throw new Exception('An error occured');
                $errors[] = 'It seems like an user is already registered with email you entered';
            }
        }
        
        $ret = array(
            'errors' => $errors,
            'data' => array_diff_key($data, array_flip(array('password', 'password_confirm'))),
        );
        
        return $ret;
    }
    
    /**
     * Show user profile
     */
    public function profile(){
        
    }
}
<?php

/**
 * User class
 * 
 * This class will care about authentication
 * 
 * @author  Sergiu Pirlici      <www.spirlici.com>
 */
class User extends Model {
    
    /**
     * Check if a user is logged in
     */
    public function is_log(){
        $logged = false;
        
        return $logged;
    }
    
    /**
     * Register an user
     * 
     * @param   array   $data   User data
     * 
     * @return boolean
     */
    public function register($data){
        
    }
    
    /**
     * Check if an user with the given email exists
     * 
     * If the user doesn't exist then `false` is returned
     * 
     * @param   string      $email  Email
     * @return  mixed
     */
    public function getByEmail($email){
        $ret = $this->db()
            ->select('*')
            ->from('user')
            ->where(array('email' => $email))
            ->get_first()
        ;
        return $ret;
    }
    
}
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
     * @inheritdoc
     */
    protected $table = 'user';
    
    /**
     * @inheritdoc
     */
    protected $pk = 'id';
    
    /**
     * Check if a user is logged in
     */
    public function is_log(){
            
        if($logged = (int)$this->session->userID) {
            if(!$this->db()->select('id')->from('user')->where(array('id' => $this->session->userID))->get_first()) {
                $logged = false;
                unset($this->session->userID);
            }
        }

        // var_export($this->db()->last_query);
        
        return $logged;
    }
    
    /**
     * Table fields
     */
    public $table_fields = array('name', 'email', 'password', 'salt');
    
    /**
     * Try to login an user
     * 
     * @param   string  $email      Email
     * @param   string  $password   Password
     * @return  bool
     */
    public function login($email, $password) {
        $logged = false;
        $user = $this->db()
            ->select('*')
            ->from('user')
            ->where(array('email' => $email))
            ->get_first()
        ;
        if($user) {
            if($user['password'] == $this->encryptPassword($password, $user['salt'])) {
                $logged = true;
                $this->session->userID = $user['id'];
            }
        }
        return $logged;
    }    
    
    /**
     * Logout the user
     * 
     * @param   string  $email      Email
     * @param   string  $password   Password
     * @return  bool
     */
    public function logout() {
       
        unset($this->session->userID);
        
        return true;
    }
    
    /**
     * Register an user
     * 
     * @param   array   $data   User data
     * 
     * @return boolean
     */
    public function register($data){
        $fields = array_intersect_key($data, array_flip($this->table_fields));
        if($fields['salt'] = $this->generateSalt()) {
            if($fields['password'] = $this->encryptPassword($fields['password'], $fields['salt'])) {
                $id = $this->db()->insert($fields, $this->table);
            }
        }
        
        return $id;
    }
    
    /**
     * Encrypt the password and return the hash
     * 
     * `$salt` parameter is needed to add protection against the rainbow tables attacks
     * 
     * Password will be concatenated with the `$salt` and then will be hashed with sha256 algorithm
     * 
     * @param   string  $password   Password
     * @param   string  $salt       A random generated string
     * @return  string
     */
    public function encryptPassword($password, $salt){
        
        // Salt and Password are required parameters
        if(!$salt || !$password) return false;
        
        // Generate password hash from the concatenated Password and Salt
        $hash = hash('sha256', $password.':'.$salt);
        
        // Add some more sequrity
        $c = 1024;
        while($c-- > 0) {
            $hash = hash('sha256', $hash.':'.$salt);
        }
        
        return $hash;
    }
    
    /**
     * Generate a random string so-called `salt`
     * 
     * Salt will be generated with `uniqid` function with a prefix generated with `mt_rand` function.
     * 
     * `$more_entropy` parrameter is set to true to add additional entropy
     * 
     * @return  string
     */
    public function generateSalt(){
        
        // Generate salt
        $salt = uniqid(mt_rand(), true);
        
        return $salt;
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
    
    /**
     * Get one user field or all fields
     * 
     * @param   string  $name   Field name
     * @return  mixed
     */
    public function get($name = NULL){

        if($this->is_log() && !$this->_data) {
            $userID = $this->is_log();
            $this->_data = $this->db()
                ->select('id,name,email')
                ->from('user')
                ->where(array('id' => $userID))
                ->get_first()
            ;
        }
        if(!empty($this->_data)) {
            $ret = $this->_data;
            if($name) {
                $ret = !empty($this->_data[$name]) ? $this->_data[$name] : NULL;
            }
        }
        
        return $ret;
    }
}
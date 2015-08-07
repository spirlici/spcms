<?php

/**
 * Data base class
 * 
 * @author  [Sergiu Pirlici](www.spirlici.com)
 */
class Db {
    
    /**
     * @var     array       $config             Database configuration
     */
    protected $config;
    
    /**
     * @var     resource    $con                The connection to the database
     */
    protected $mysqli;
    
    /**
     * @var     string      $query_type         The query - select/udpate/insert/delete
     */
    protected $query_type;
    
    /**
     * @var     array       $select_columns     Columns that must be selected from the database
     */
    protected $select_columns;
    
    /**
     * @var     array       $where_clause       Where clause
     */
    protected $where_clause;
    
    /**
     * @var     string      $from_table         Table to wich is made the query
     */
    protected $from_table;
    
    /**
     * Class Constructor
     * 
     * @param  array    $config     Database configuration
     */
    public function __construct($config){
        $this->config = $config;
    }
    
    /**
     * Set columns that must be selected from the table
     * 
     * @param   mixed   $columns    Columns
     * @return  Db
     */
    public function select($columns){
        $this->query_type = 'SELECT';
        
        if(is_string($columns)) {
            $columns = explode(',', $columns);
        }
        elseif(is_array($columns)) {
            foreach($columns as $k => &$v) {
                if(is_string($k)) {
                    $v = $k.' AS '.$v;
                }
            }
            unset($v);
        }
        $this->select_columns = array_unique(array_merge((array)$this->select_columns, array_values($columns)));
        foreach($this->select_columns as &$v) {
            $v = $this->esc($v, false);
        }
        return $this;
    }
    
    /**
     * Insert `$data` into the table `$table`.
     * 
     * If `$insert_id` parameter is specifyed and is true then `last_insert_id` will be returned;
     * 
     * @param   array   $data       Data to be inserted
     * @param   string  $table      Table name
     * @param   bool    $insert_id  Return last insert id or not
     */
    public function insert($data, $table, $insert_id = true){
        $sql = 'INSERT INTO '.$this->esc($table, "`");
        foreach($data as $field => $value) {
            $columns[] = $this->esc($field, '`');
            $values[]  = $this->esc($value);
        }
        $sql .= ' ('.implode(',', $columns).')';
        $sql .= ' VALUES ('.implode(',', $values).')';
        
        if($ret = $this->query($sql)) {
            if($insert_id) $ret = $this->mysqli->insert_id;
        }
        else {
            $this->error();
        }
        return $ret;
    }
    
    /**
     * Throw an error
     * 
     * @param   string  $msg    Error message
     */
    public function error($msg = ''){
        $msg and $msg .= ':';
        throw new Exception($msg . $this->last_error);
    }
    
    /**
     * Set from table
     * 
     * @param   string  $table  Table
     */
    public function from($table) {
        $this->from_table = '`'.$this->esc($table, false).'`';
        return $this;
    }

    /**
     * Set Where clause
     * 
     * @param   mixed  $where  Where clause
     */
    public function where($where) {
        if(is_string($where)) {
            $this->where_clause[] = $where;
        }
        elseif(is_array($where)) {
            foreach($where as $key => $val) {
                if(is_string($key)) {
                    if(is_array($val)) {
                        foreach($val as &$v) if(!is_numeric($v)) $v = $this->esc($v);
                        $this->where_clause[] = $this->esc($key, false) . 'IN ('.implode(',', $val).')';
                    }
                    else {
                        $this->where_clause[] = $this->esc($key, false).'='.(is_numeric($val) ? $val : $this->esc($val));
                    }
                }
                else {
                    $this->where_clause[] = $val;
                }
            }
        }
        $this->where_clause = array_unique($this->where_clause);
        return $this;
    }
    
    /**
     * Select and return the first found item
     * 
     * If `$field` param is specified then only the value of `$field` field will be returned
     * 
     * @param   string      $field      Field name
     */
    public function get_first($field = NULL) {
        $ret = $this->get_rows();
        $ret = reset($ret);
        return $field ? $ret[$field] : $ret;
    }

    /**
     * Select and return the items found
     */
    public function get_rows() {
        
        if($result = $this->query($this->query_str())) { 
            $ret = array();
            while($row = $result->fetch_assoc()) {
                $ret[] = $row;
            }
        }
        else {
            $ret = false;
        }
        return $ret;
    }
    
    /**
     * Prepare the query string and return it
     */
    public function query_str() {
        $sql = $this->query_type;
        $sql .= ' '.($this->select_columns ? implode(',', $this->select_columns) : '*');
        $sql .= ' FROM '. $this->from_table;
        if($this->where_clause) {
            $sql .= ' WHERE ('.implode(') AND (', $this->where_clause).')';
        }
        return $sql;
    }
    
    /**
     * Clear the previous settings
     */
    public function clear(){
        unset(  $this->from_table,$this->where_clause,
                $this->select_columns, $this->query_type
        );
    }
    
    /**
     * Try to connect to the database
     */
    public function connect(){
        $this->mysqli = new Mysqli($this->config['host'],$this->config['user'], $this->config['password'], $this->config['name']);
        if ($this->mysqli->connect_errno) {
            printf("Connect failed: %s\n", $this->mysqli->connect_error);
            exit();
        }
        return $this;
    }
    
    /**
     * Run the query
     * 
     * @param  string   $query  The query
     */
    public function query($query){
        $this->clear();
        $result = $this->mysqli->query($query);
        if(!$result) {
            $this->last_error = $this->mysqli->error;
        }
        else {
            $this->last_error = false;
        }
        $this->last_query = $query;
        return $result;
    }
    
    /**
     * Escapes special characters in a string for use in an SQL statement
     * 
     * @param   string  $value              Value to be escaped
     * @param   mixed   $with_aphostrophe   
     * @return  string
     */
    public function esc($value, $with_aphostrophe = "'"){
        
        // To avoid sql injection
        $value = $this->mysqli->real_escape_string($value);
        // If `$with_aphostrophe` parameter is specified and it is string then use it
        $a = $with_aphostrophe ? is_string($with_aphostrophe) ? $with_aphostrophe : "'" : '';
        if($with_aphostrophe) $value = $a.$value.$a;
        
        return $value;
    }
}
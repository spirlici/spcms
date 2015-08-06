<?php

/**
 * Config helper
 * 
 * This class is a helper to config autoloading
 * 
 * Configuration items are stored in files
 * 
 * Each file must return an array containing configuration items
 * 
 */
class Config {
    
    /**
     * @var  string $cfg_ext    Extension of configuration files
     */
    protected $cfg_ext = '.cfg.php';
    
    /**
     * @var  array  $config     This property will serve as a cache for loaded configuration files
     */
    protected $config;
    
    /**
     * Load a configuration item
     * 
     * Name can be in the following formats:
     *  -   'filename.configitem'   In this case, 'configitem' property will be returned from the config in 'filename' file
     *  -   'filename'              The whole array returned by in 'filename' will be returned
     * 
     * @param   string  $name   Configuration item name
     * @return  mixed
     */
    public function get($name) {
        $name = explode('.', $name, 2);
        if(count($name)>0) {
            $filename = $name[0];
            
            
            if(count($name)>1) {
                $itemname = $name[1];
            }
            if($filename) {
                if(!($config = $this->config[$filename])) {
                    
                    $fn = CONFIGDIR.$filename.$this->cfg_ext;
                    // include the configuration file and store the result in $this->config property
                    if(is_file($fn)) {
                        $config = include($fn);
                        if($config !== false) {
                            $this->config[$filename] = $config;
                        }
                    }
                }
                return !empty($itemname) ? $this->config[$filename][$itemname] : $this->config[$filename];
            }
        }
    }
}
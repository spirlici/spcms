<?php

/**
 * Redirect class
 * 
 * Make redirects
 */
class Redirect {
    
    /**
     * Make the redirect to a new `$url`
     * @param  string   $url    The new location
     */
    public function to($url){
        $scheme = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http';
        $base = trim($_SERVER['SERVER_NAME'], '/');
        var_export('Location: '.$scheme.'://'.$base.'/'.ltrim($url, '/'));
        header('Location: '.$scheme.'://'.$base.'/'.ltrim($url, '/'));
        die();
    }
}
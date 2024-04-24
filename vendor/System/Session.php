<?php
namespace System;

class Session{
    private $app;
    public function __construct(Application $application){
        $this->app = $application;
    }
    /**
     * Start session.
     */
    public function start(){
    
            //Session is not started, let's start it!
            ini_set('session.gc_maxlifetime', 86400);//1 day
            ini_set('session.use_only_cookies', 1);
            if(! session_id()){
                session_start();
            }

    }
    /**
     * Set a value to the session.
     * @param string $key The key of the element you want to set.
     * @param mixed $value The value that will be associated with the given key.
     */
    public function set($key,$value){
        // echo $key .'=>' . $value.'<br>';
        $_SESSION[$key]=$value;
    }
    /**
     * Get an element from the session by its key. If this element does not exist return null.
     * @param string $key The key  of the element you want to get.
     * @return mixed|null Return the value associate with the given key or null if there is no such element.
     */
    public function get($key , $default=null){
        // return isset($_SESSION[$key]) ? $_SESSION[$key]:$default;
        // if(!isset($_SESSION[$key]))
        //     return null;
        // else
        //     return $_SESSION[$key];
        return array_get( $_SESSION , $key , $default );
    }
    /**
     * Destroy the current session and all data related to it. After calling this method, the session will be non-existent.
     * Destroy the current session and all data associated with it. After calling this method, the session will be non-existent.
     * Destroy the current session and all data related to it. After calling this method, any further access to this object will create a new
     * Check if an element exists in the session.
     * @param string $key The key of the element you want to check.
     * @return bool True if the element exists, false otherwise.
     */
    public function has($key){
        return isset($_SESSION[$key]);
    }

    public function pull($key){
        $ret = $this->get($key);
        $this->remove( $key );
        return $ret;




    }

    public function  remove($key) {
        unset($_SESSION[$key]);
    }
    public function all() {
        return $_SESSION;
    }
    public function destroy(){
        session_destroy();
        unset( $_SESSION );
    }
}
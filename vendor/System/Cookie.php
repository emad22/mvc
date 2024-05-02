<?php
namespace System;

class Cookie{
    private $app;
    private $path = '/';
    public function __construct(Application $application){
        $this->app = $application;
    }
   
  
    /**
     * Set a value to the session.
     * @param string $key The key of the element you want to set.
     * @param mixed $value The value that will be associated with the given key.
     */
    public function set($key,$value , $hours=1000) {
        // echo $key .'=>' . $value.'<br>';
        $expireTime = $hours == -1 ? -1 : time() + $hours * 3600;

        //        key   value   expire time        path   domain   secure httpOnly

        setcookie($key, $value, $expireTime,     $this->path,  '',    false, true);
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
        return array_get( $_COOKIE , $key , $default );
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
        return array_key_exists( $key , $_COOKIE ) ;
    }

    

    public function  remove($key) {
        setcookie( $key , '' , -1 , $this->path);
        unset($_COOKIE[ $key ]);
    }
    public function all() {
        return $_COOKIE;
    }
    public function destroy(){
        // foreach ($_COOKIE as $name => $value) {
        //     $this ->remove($name);
        // }
        // foreach(array_keys($_COOKIE) as $name) {
        //     $this->remove($name);
        // };
        foreach (array_keys($this->all()) as $name) {
            $this->remove($name);
        };
        //unset all cookie
        unset( $_COOKIE );  
    }
}
<?php

if( ! function_exists('pre')){
    /**
     * Pretty print the given variable.
     *
     * @param  mixed   $var
     * @return void
     */
    function pre($var)
    {
        echo '<pre>';
            print_r($var);
        echo '</pre>';
    }
}
if(!function_exists("array_get")){
    /**
     * Get an item from an array using "dot" notation.
     *
     * @param  array   $array
     * @param  string  $key
     * @param  mixed   $default
     * @return mixed
     */
    function array_get($array, $key, $default = null)
    {
        return isset($array[$key]) ? $array[$key] : $default ;
    }
}
if( ! function_exists('_e') ){
    function _e($val) {
        return htmlspecialchars($val);
    }
        
}
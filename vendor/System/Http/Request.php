<?php
namespace System\Http;
class Request{
   
    private $url;
    private $baseurl;

    public function __construct(){

    }

    public function prepareUrl(){
        // pre($_SERVER);
        // // echo $this->server('PATH_INFO' );
        // exit();
        $script_name = dirname( $this->server('SCRIPT_NAME') );
        $request_uri = $this->server('REQUEST_URI');
        if (strpos( $request_uri, '?')  !== false) {
            list($request_uri,$query) = explode('?',$request_uri);
        }
        // echo  $request_uri;
        // exit();
        $request_uri = preg_replace('#^'.$script_name.'#','',$request_uri);
        $this->url = $request_uri;
        $this->baseurl = $this->server('REQUEST_SCHEME').'://' . $this->server('HTTP_HOST').$script_name."/";

        // echo $this->baseurl;
        // echo  $request_uri;exit();

    }

    public function server($key , $default = null){
        //  return isset($_SERVER[$key]) ? $_SERVER[$key] : $default ;
        // if(isset($_SERVER[$key])){
        //     return $_SERVER[$key];
        // } 
        return array_get( $_SERVER, $key ,$default) ;
    }
    /**
     * Get the request method.
     *
     * @return string
     */
    public function get( $key, $default){
        return array_get( $_GET, $key, $default);
}
    public function post($key, $default)
    {
        return array_get( $_POST, $key, $default);
    }
    public function url(){
        return  $this->url;
    }

    public  function method(){
        return   $this->server('REQUEST_METHOD');
    }

    public function baseUrl(){
        return  $this->baseurl;

    }
}
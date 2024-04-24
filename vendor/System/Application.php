<?php
namespace System;

class Application{

    private $container = [];

    private static  $_instance ;
    private function __construct(File $file){
        $this->share('file' , $file) ;  // share method is used to share any dependency across the application. 
        //object(System\File)[1]
        //   private 'root' => string 'C:\wamp64\www\blog'
        $this->RegisterClasses();

        // static::$_instance= $this;

        $this->loadherpers();
        // pre($this->file);
        
    }
    /*
     * get application instance
     * 
     * */
    // public static function GetInstance(){
    //    if(!isset(self::$_instance)){
    //        self::$_instance=new Application(__get('file'));
    //    }
    //    return self::$_instance;
    // }
    public static function getInstance($file = null) {
        if (is_null(static::$_instance)) {
            static::$_instance = new static($file);
        }
        return static::$_instance;
    }

    public function __get($key) {
        return $this->get($key);
    }

    public function share($key , $value){
        return $this->container[$key] = $value;
    }

    private function RegisterClasses(){
        spl_autoload_register([$this , 'autoloader']);
    }

    public function autoloader($className){
        // print_r($this->file);
        // die;
        // die($className); // System\Test
        if(strpos($className , 'App')  === 0 ){
            $file = $className . '.php'; // C:\wamp64\www\blog\App\Controllers\Users\UsersCotroller
        }else{
            $file = 'vendor/'.$className .'.php';
            // die($file); //  C:\wamp64\www\blog\System\Test.php
        }
        if ($this->file->exist($file)) {
            $this->file->call($file);
        }

    }

    public function get($key){
        if(!$this->isSharing( $key)){
            if($this->isCoreAlias($key)){
                // die("core alias found : {$key}");
                $this->share($key , $this->createNewCoreObject($key));
            }else{
                die("core alias not found : {$key}");
            }
        }
         return $this->container[$key] ;
    }
    public function createNewCoreObject($class){
        $coreClasses = $this->coreClasses();
        $obj = $coreClasses[$class];
        return   new $obj($this);

    }
    public function isSharing($key){
        return isset($this->container[$key]);
    }
    
    public function isCoreAlias($alias){
        // return array_key_exists($key, $this->coreAliases);
        $coreClasses = $this->coreClasses();
        // var_dump($coreClasses);
        return isset( $coreClasses[$alias] );
    }

    private  function coreClasses(){
        return [
            'request'       => 'System\\Http\\Request',
            'response'      => 'System\\Http\\Response',
            'session'       => 'System\\Session',
            'route'         => 'System\\Route',
            'cookie'        => 'System\\Cookie',
            'load'          => 'System\\Loader',
            'html'          => 'System\\Html',
            'db'            => 'System\\Database',
            'view'          => 'System\\View\\ViewFactory',
            'url'           => 'System\\Url',
            'validator'     => 'System\\Validation',
            // 'pagination'    => 'System\\Pagination',
        ];
    }

    public  function run() {
        $this->session->start();
        $this->request->prepareUrl();
        $this->file->call('App/index.php');
        list($controllerName, $functionName , $arguments) = $this->route->getProperRoute();
        // var_dump($functionName) ; //C:\wamp64\www\blog\vendor\System\Application.php:116:string 'index' (length=5)
        // var_dump($controllerName) ; //C:\wamp64\www\blog\vendor\System\Application.php:117:string 'Main\Home' (length=9)
        // pre( $route );
        $output = $this->load->callAction($controllerName, $functionName, $arguments);
        // var_dump( $output );
        // exit;
        $this->response->sendOutput( $output );
        $this->response->send();
        
    }
    public function loadherpers()
    {
        return $this->file->call('vendor/helpers.php');
    }
}

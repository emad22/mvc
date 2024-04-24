<?php

namespace System;

class Route{
    private $app;

    private $routes = [];

    private $notfoundUrl;
    public function __construct(Application $application) {
        $this->app = $application;
    }
    /**
     * Add New route
     * 
     * */
    public function add($url, $action, $requestMethod = 'GET'){
        // Check if method is valid HTTP Method or not.        
        $route = [
            "url" => $url,
            "pattern"=> $this->generatePattern($url),
            "action"=> $this->getAction( $action),
            "method" => strtoupper($requestMethod)
        ];
        $this->routes[] = $route;
        
    }

    private  function generatePattern($url){
        $pattern = '#^';
        // :text ([a-zA-Z0-9-]+)
        // :id (\d+)
        // $url = str_replace([],[],$url);
        $pattern .= str_replace([':text',':id'],['([a-zA-Z0-9-]+)','(\d+)'],$url);

        $pattern .= '$#';
        return $pattern;

    }

    private function getAction($action){
        $action = str_replace('/','\\',$action);
        // var_dump($action);
        // exit;
        return strpos($action , '@') !==  false ? $action : $action.'@index';
    }
    public function notFound($url){
        $this->notfoundUrl=$url ;
    }

    public function getProperRoute(){
        //AND $this->isMatchingRequestMethod($route['method'])
        // var_dump($this->routes);exit;
        // var_dump($this->getAction($this->routes['action']));exit;

        foreach ($this->routes as $route) {
            // var_dump($route);
            // exit;
            // var_dump($this->routes['pattern']);
            // exit;
            if ($this->isMatching($route['pattern'])) {
                // echo $route['action'];
                 $arguments = $this->getArgumentsFrom($route['pattern']);
                 list($controllerName, $functionName)= explode('@', $route['action'] );
                return [$controllerName, $functionName, $arguments]; 
                //  $controller= new $controllerName();
                // var_dump($functionName);


            }
        }
    }

    

    /**
     * Determine if the given pattern matches the current request url
     *
     * @param string $pattern
     * @return bool
     */
    private function isMatching($pattern){
        return  preg_match( $pattern , $this->app->request->url() );
   }

    /**
     * Get Arguments from the current request url
     * based on the given pattern
     *
     * @param string $pattern
     * @return array
     */
   private function getArgumentsFrom($pattern){
    // var_dump($pattern);
    // var_dump($this->app->request->url());
    preg_match( $pattern, $this->app->request->url(),$matches);
    array_shift( $matches );
    //    var_dump($matches);exit;
    return $matches;
     
   }

}
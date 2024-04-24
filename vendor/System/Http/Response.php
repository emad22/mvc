<?php

namespace System\Http;

use System\Application;

class Response {
    // private $statusCode = 20
    private $app;
    private $content = '';
    private $header =[];
    public  function __construct(Application $application) {
        $this->app = $application;
    }

    public function setOutput($content){
        $this -> content= $content ;
    }
    public function sendHeader(){
        foreach($this->header  as $key=>$value){
            header("{$key}: {$value}");
        }
    }

    public function sendOutput(){
        echo $this->content;
    }
    /**
     * @param string|array $data  
     * @param int         $code     
     * @param array       $headers 
     */
    public function send(){
        $this->sendHeader();
        $this->sendOutput();
    }



   


}
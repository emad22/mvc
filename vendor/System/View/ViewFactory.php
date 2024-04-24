<?php
namespace System\View;
use System\Application;

class ViewFactory {
    private $app;
    
    public function __construct(Application  $application) {
        $this->app = $application;
    }
    /**
     * @param string $name the name of view to create.
     * @return \System\View\View a new instance of view with given name.
     */
    public function render($viewPath, array $data = []){

        // return new View($this->app->file , $viewPath, $data);
        return new View( $this->app->file ,  $viewPath, $data);
    }
}

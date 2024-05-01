<?php

namespace System;

class Loader{

    private $controller = [];
    private  $model     = [];
    private $app;
    public function __construct(Application $app){
        $this->app=$app;
    }
    /**
     * @param string $name controller name without suffix.
     * @return object|bool return an instance of the controller if it exists, otherwise false.
     */
    private function getController($controller) {
        
        return $this->controller[$controller];
    }
    public function Controller($controller){
        //check if the controller is already loaded
        $controller = $this->getControllerName($controller);
        // echo $controller;
        if (! $this->hasController($controller)) {
            return $this->addController($controller);
        }
        return $this->getController($controller);
    }
    /*
    * Get a valid controller name from user input.
    * If no argument passed, use current controller.
    * Throw exception if not found.
    */

    private function getControllerName($controller){
        $controller .= "Controller";
        $controller = '\\App\\Controllers\\' . ucwords($controller);
        $controller = str_replace('/', '\\', $controller) ;
        return $controller;
    }
    
    /*
    * Check if a controller has been added to
    * the loader's collection.
    */
    private function hasController($controller){
        return array_key_exists($controller,$this->controller);
    }

    /*
    * Add a new controller to the loader's collection.
    * Throws Exception if file does not exist or class does not extend BaseController.
    */
    private function addController($controller){     

        if (class_exists($controller)) {
            $object = new $controller($this->app);
            $this->controller[$controller] = $object;
            return $this->controller[$controller];
        } else {
            throw new \Exception("The {$controller} class doesn't exist.");
        }

    }


    public  function callAction( $controller, $method , array $arguments =[]){
        //get action method from uri
        $object =  $this->controller($controller);
        // var_dump($object);exit();
        return call_user_func_array([$object, $method], $arguments);
    }




    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    private function getModel($model)
    {

        return $this->model[$model];
    }
    public function model($model)
    {
        //check if the model is already loaded
        $model = $this->getModelName($model);
        // echo $model;
        if (!$this->hasModel($model)) {
            return $this->addModel($model);
        }
        return $this->getModel($model);
    }
    /*
    * Get a valid model name from user input.
    * If no argument passed, use current model.
    * Throw exception if not found.
    */

    private function getModelName($model)
    {
        $model .= "Model";
        $model = '\\App\\Models\\' . $model;
        $model = str_replace('/', '\\', $model);
        return $model;
    }

    /*
    * Check if a model has been added to
    * the loader's collection.
    */
    private function hasModel($model)
    {
        return array_key_exists($model, $this->model);
    }

    /*
    * Add a new model to the loader's collection.
    * Throws Exception if file does not exist or class does not extend Basemodel.
    */
    private function addModel($model)
    {

        if (class_exists($model)) {
            $object = new $model($this->app);
            $this->model[$model] = $object;
            return $this->model[$model];
        } else {
            throw new \Exception("The {$model} class doesn't exist.");
        }
    }




}
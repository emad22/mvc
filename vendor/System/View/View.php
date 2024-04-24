<?php
namespace System\View;

use System\File;

class View implements ViewInterface {
    /**
     * File Object
     */
    private $file;

    /**
     * View Path
     */
    private $viewPath;

    /**
     * Passed Data "variables" to the view path
     */
    private $data = [];

    /**
     * The output from the view file
     */
    private $output;

    public function __construct( File $file, $viewPath , array $data) {
        $this->file = $file;
        $this->viewPath = $this->setViewPath($viewPath);
        $this->data = $data;
    }
    /**
     * Set the view path and check if it exists. If not throw an error.
     * @param string $path  
     * @return void         
     */
    private function setViewPath($viewPath){
        
        $relativeViewPath = 'App/Views/' . $viewPath . '.php';
        // echo $relativeViewPath;
        $this->viewPath = $this->file->to($relativeViewPath);
        // echo ($this->viewPath);
        // echo $this->viewFileExists($relativeViewPath);
        if (!$this->viewFileExists($relativeViewPath)) {
            die('<b>' . $viewPath . ' View</b>' . ' does not exists in Views Folder');
        }
    }
    /**
     * Check if a given file exists in its location 
     * @param  String $file 
     * @return Boolean            
     */
    private function viewFileExists($file){
        return $this->file->exist($file);
    }
    /**
     * Get the view output
     */
    public function getOutput(){


        if (is_null($this->output)) {
            ob_start();
            extract($this->data);
            require $this->viewPath;
            $this->output = ob_get_clean();
        }
        return $this->output;
    }
    public function __toString(){
        return $this->getOutput();
    }
}
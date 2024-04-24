<?php

namespace System;

class File{

    const DS = DIRECTORY_SEPARATOR;
    private $root;
    public function __construct($root){
        $this->root = $root;
        // var_dump($root);
    }

    public function exist($file){
        return file_exists($this->to($file));
    }

    public function call($file){
        // require $file;
        return require $this->to($file);
    }
    public function toVendor($path){
        return $this->to('vendor/'. $path); // path = System\Test.php

    }

    public function to($path){
        // var_dump($this->root); // 'C:\wamp64\www\blog
        // var_dump($path); // vedor/System\Test.php
        return $this->root . static::DS. str_replace(array('\\', '/'), static::DS, $path);

    }

}

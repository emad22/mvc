<?php
namespace System;

abstract  class Model {
    protected $app;
    protected $table;
    public function __construct(Application $app) {
        $this->app = $app;
    }
    
    public function __get($name){
        return $this->app->get($name);
    }

    public function __call($method, $args) {
        return  call_user_func_array([$this->app->db, $method], $args);
    }
    public function all()
    {
        return $this->db->fetchAll($this->table);
    }
    public function getUserById($id)
    {
        return $this->db->where('id = ?', $id)->fetch($this->table);
    }
}
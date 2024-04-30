<?php
namespace System;
use PDO;
use PDOException;

class Database{
    private $app;
    private static $connection;
    private $tableName;
    private $data =[];
    private $bindings =[];
    private $wheres = [];
    private $lastID ;
    public  function __construct(Application $application){
        $this->app = $application;
        if( ! $this->isConnected() ){
            $this->connected();
        }
    }
    /**
     * @return bool|PDO
     */
    
    /**
     * Checks whether the database connection is established or not.
     * @return bool
     */
    private function isConnected() {
        return self::$connection instanceof PDO;
    }
  
    /**
     * Establishes a new database connection using the data from the configuration file.
     */
    private function connected(){
        $config = $this->app->file->call('Config.php');
        extract($config);
        // exit;
        try {
            //Set up some standard options for the PDO connection.
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"
            ];
            //Create a new PDO instance with the given host, dbname and password. The chosen driver is determined by the $dbType
            static::$connection = new PDO("mysql:host=$server; dbname=$dbname", $dbuser, $dbpass, $options);
            //Create a new PDO instance with the given 
        } catch (PDOException $e) {
            die("Database Connection failed: {$e->getMessage()}");
        }
    }
    /**
     * Closes the current database connection.
     */
    public function disconnect(){
        if ($this->isConnected()) {
            self::$connection = null;
        }
    }
    public function connection(){
        return static::$connection;
    }

    public function table($tableName){
        $this->tableName = $tableName;
        return $this;
    }
    public function from($tableName){
        return $this->tableName = $tableName;
    }

    public function data($key, $value = null){
        if(is_array($key)) {
            // foreach($key as $k=>$v) {
            //     $this->data[$k] = $v;
            // }
            $this->data =  array_merge($this->data, $key);
            $this->BindData($key);
        } else {
            $this->data[$key] = $value;
            $this->BindData($value);
        }
        return $this;
    }

    public  function insert($table = null){
            if ($table) {
                $this->table($table);
            }
            $sql = 'INSERT INTO ' .$this->tableName. ' SET ';
            foreach($this->data  as $column => $val) {
                $sql .= '`' . $column . '` = ? ,' ;
                $this->BindData($val);

            // $fields   = implode(',', array_keys($this->data));
            // $values   = ':' . implode(':,:', array_keys($this->data));
            // $sql      = "INSERT INTO {$this->tableName} ({$fields}) VALUES ({$values})";
        }
        $sql = rtrim($sql, ',');
        $this->query($sql, $this->bindings);
        $this->lastID = $this->connection()->lastInsertId();
        // pre($this->lastID); die;
        return $this;
        // echo  $sql."<br/>\n";
    }
    /**
     * update data to the table
     */
    public function update($table = null) {
        
        if ($table) {
            $this->table($table);
        }
        $sql = 'UPDATE '. $this->tableName. ' SET ';

        $sql .= $this->Setfields();

        // var_dump($this->wheres);die;
        if($this->wheres)    {
            $sql .= ' WHERE ' . implode(' ', $this->wheres);
        }
        // var_dump( $sql );die;
        // var_dump($this->bindings);
        // die;
        
        $this->query($sql, $this->bindings);
        
        return $this;
        
    }

    public function  where(){
        $args =  func_get_args();
        $sql = array_shift( $args );
        $this->BindData( $args );
        $this->wheres[] = $sql;
        return  $this;
    }

        // $updates = [];
    /**
     * @param string|null $field
     */
    public function lastID() {
        return $this->lastID;
    }
    public function query(){
        $args       = func_get_args();
        $sql        = array_shift($args);
        // var_dump($sql);
        // die;
        // pre($args);die;
        // var_dump(is_array($args));
        // die;
        if(count($args) == 1 AND  is_array( $args[0] ) ) {
            $args       = $args[0];
        }
        // pre($args);die;
        try{
            $stmt = $this->connection()->prepare($sql);           
            foreach ($args as $key => $value) {
                $stmt->bindValue($key + 1 , $value);
                
            }
            
             $stmt->execute();
            // pre($stmt);die;
            return  $stmt;
        }catch(PDOException $e){
            //    throw new PDOException("Database error: " . $e->getMessage());
            echo $sql;

            pre($this->bindings);

            die($e->getMessage());
        // die( 'Database Error:' .$e->getMessage());
        }

    }
    private  function BindData($val) {
        // var_dump(is_array($val));
        // die;
        if(is_array($val)){
            
            $this->bindings = array_merge( $this->bindings ,array_values( $val ));
            // var_dump($this->bindings);
            // die;
        }else{
            $this->bindings[] = _e($val);
        }
        
    }

    private  function Setfields(){
        $sql ='';
        foreach (array_keys($this->data) as $column ) {
            // $sql .= ' `' . $column . '`=?,';
            $sql .= '`' . $column . '` = ? , ';
            // $this->BindData($val);
        }
        $sql = rtrim($sql, ', ');
        return  $sql;

    }


}

//static::$connection = static::$connection = new PDO('mysql:host=' . $server . ';dbname=' . $dbname, $dbuser, $dbpass, $options);
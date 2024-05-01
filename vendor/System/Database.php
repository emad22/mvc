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
    private $selects = [];
    private $joins = [];
    private $limit;
    private $offset;

    private $having =[];
    

    private $groupBy;
    private $ordersBy = [];

    public  function __construct(Application $application){
        $this->app = $application;
        // var_dump(!$this->isConnected());die;
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
        // var_dump($config);die;
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
    
    /**
     * update data to the table
     */
    

    public function  where(){
        $args =  func_get_args();
        $sql = array_shift( $args );
        $this->BindData( $args );
        $this->wheres[] = $sql;
        return  $this;
    }
    public function join($join){
        $this->joins[] = $join;
        return $this;
    }
    public function orderBy($orderBy, $sort = 'ASC'){
        $this->ordersBy = [$orderBy, $sort];

        return $this;
    }

    public function select($select){
        // for those who use PHP 5.6
        // you can use the ... operator
        // otherwise , use the following line to get all passed arguments
        // $selects = func_get_args();
        // $this->selects = array_merge($this->selects, $selects);
        $this->selects[] = $select;
        return $this;
    }
    public function limit($limit, $offset = 0){
        $this->limit = $limit;
        $this->offset = $offset;
        return $this;
    }
      
    public function lastID() {
        return $this->lastID;
    }
    public function data($key, $value = null){
        if (is_array($key)) {
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
        $sql = 'INSERT INTO ' . $this->tableName . ' SET ';
        $sql .= $this->Setfields();
        //     foreach($this->data  as $column => $val) {
        //         $sql .= '`' . $column . '` = ? ,' ;
        //         $this->BindData($val);

        //     // $fields   = implode(',', array_keys($this->data));
        //     // $values   = ':' . implode(':,:', array_keys($this->data));
        //     // $sql      = "INSERT INTO {$this->tableName} ({$fields}) VALUES ({$values})";
        // }
        // $sql = rtrim($sql, ',');
        $this->query($sql, $this->bindings);
        $this->lastID = $this->connection()->lastInsertId();
        // pre($this->lastID); die;
        return $this;
        // echo  $sql."<br/>\n";
    }
    public function update($table = null)
    {

        if ($table) {
            $this->table($table);
        }
        $sql = 'UPDATE ' . $this->tableName . ' SET ';

        $sql .= $this->Setfields();

        // var_dump($this->wheres);die;
        if ($this->wheres) {
            $sql .= ' WHERE ' . implode(' ', $this->wheres);
        }
        // var_dump( $sql );die;
        // var_dump($this->bindings);
        // die;

        $this->query($sql, $this->bindings);

        return $this;
    }
    public function delete($table = null)
    {

        if ($table) {
            $this->table($table);
        }
        $sql = 'DELETE FROM ' . $this->tableName ;

        // $sql .= $this->Setfields();

        // var_dump($this->wheres);die;
        if ($this->wheres) {
            $sql .= ' WHERE ' . implode(' ', $this->wheres);
        }
        // var_dump( $sql );die;
        // var_dump($this->bindings);
        // die;
        $this->query($sql, $this->bindings);
        return $this;
    }
    public function fetch($table = null){
        if ($table) {
            $this->table($table);
        }
        $sql = $this->fetchStatments();
        // pre($sql);die;
       
        // $sql .= ' FROM ' . $this->table . ' ';
        // var_dump($sql);die;
        $stmt = $this->query($sql , $this->bindings);
        $res = $stmt->fetch();
        return $res;
    }
    public function fetchAll($table = null)    {
        if ($table) {
            $this->table($table);
        }
        $sql = $this->fetchStatments();
        // pre($sql);die;

        // $sql .= ' FROM ' . $this->table . ' ';
        // var_dump($sql);die;
        $stmt = $this->query($sql, $this->bindings);
        $ress = $stmt->fetchAll();
        return $ress;
    }


private function fetchStatments(){
        $sql = "SELECT ";
        if ($this->selects) {
            $sql .= implode(',', $this->selects);
        } else {
            $sql .= "*";
        }
        $sql .= " FROM " . $this->tableName . " ";
        if ($this->joins) {
            $sql  .= implode(" ", $this->joins) . " ";
        }
        if ($this->wheres) {
            $sql .= " WHERE " . implode("  ", $this->wheres) . " ";
        }
        if (!empty($this->groupBy)) {
            $sql .= " GROUP BY " . implode(",",$this->groupBy)." ";
        }
        if ($this->having) {
            $sql .= " HAVING ".$this->having." ";
        }
        if (!empty($this->ordersBy)) {
            $sql .= " ORDER BY " . implode(" ", $this->ordersBy) . " ";
        }
        if ($this->limit) {
            $sql .= " LIMIT " . $this->limit;
        }
        if ($this->offset) {
            $sql .= " OFFSET " . $this->offset;
        }
        return  $sql;
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
            // die($e->getMessage());
            throw new PDOException("Database error: " . $e->getMessage());
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
<?php
namespace System;
use PDO;
use PDOException;

class Database{
    private $app;
    private static $connection;
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
            static::$connection = new PDO("mysql:host=$server;dbname=$dbName", $dbUser, $dbpass, $options);
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
    public function connection()
    {
        return static::$connection;
    }

}

//static::$connection = static::$connection = new PDO('mysql:host=' . $server . ';dbname=' . $dbname, $dbuser, $dbpass, $options);
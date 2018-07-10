<?php
  class Database {

    private static $dbName = 'vfq_premium_almacen' ;
    private static $dbHost = 'localhost' ;
    private static $dbUsername = 'username';
    private static $dbUserPassword = 'passwd';
    private static $port = '3306';
    private static $cont  = null;
       
    public function __construct() {
      die('Init function is not allowed');
    }
       
    public static function connect() {
      // One connection through whole application
      if ( null == self::$cont ) {     
        try {
          self::$cont =  new PDO( "mysql:host=".self::$dbHost.";"."port=".self::$port.";"."dbname=".self::$dbName, self::$dbUsername, self::$dbUserPassword); 
          self::$cont -> exec("set names utf8");
        }catch(PDOException $e) {
            die( $e->getMessage() ); 
          }
      } 
      return self::$cont;
    }
       
    public static function disconnect() {
      self::$cont = null;
    }
    
  }
?>